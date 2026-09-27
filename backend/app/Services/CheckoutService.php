<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function __construct(private readonly CartService $cartService) {}

    public function items(Request $request)
    {
        return $this->cartService->items($request);
    }

    public function shippingFee(float $subtotal, ?string $province): int
    {
        if (! $province || $subtotal >= 5_000_000) return 0;
        return $province === 'Thành phố Hồ Chí Minh' ? 25_000 : 40_000;
    }

    public function availableVouchers(Request $request)
    {
        return $request->user()
            ? $request->user()->vouchers()->wherePivotNull('used_at')->wherePivot('claimed_at', '>=', now()->subDay())->where('vouchers.is_active', true)->where(fn ($query) => $query->whereNull('starts_at')->orWhere('starts_at', '<=', now()))->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>=', now()))->get()
            : collect();
    }

    public function orderNumber(Request $request): string
    {
        if ($orderNumber = $request->session()->get('checkout_order_number')) return $orderNumber;
        do { $orderNumber = 'LTS-'.now()->format('Ymd').'-'.str()->upper(str()->random(6)); }
        while (Order::where('order_number', $orderNumber)->exists());
        $request->session()->put('checkout_order_number', $orderNumber);
        return $orderNumber;
    }

    public function placeOrder(Request $request, array $data): Order
    {
        $items = $this->items($request);
        if ($items->isEmpty()) throw ValidationException::withMessages(['cart' => 'Giỏ hàng của bạn đang trống.']);

        $data['address'] = implode(', ', [$data['address_detail'], $data['ward'], $data['district'], $data['province']]);
        $shippingFee = $this->shippingFee($items->sum(fn ($item) => ($item['product']->sale_price ?? $item['product']->price) * $item['quantity']), $data['province']);
        $orderNumber = $this->orderNumber($request);

        $order = DB::transaction(function () use ($items, $data, $request, $shippingFee, $orderNumber) {
            $subtotal = 0;
            $lockedProducts = Product::whereIn('id', $items->pluck('product.id'))->lockForUpdate()->get()->keyBy('id');
            foreach ($items as $item) {
                $product = $lockedProducts->get($item['product']->id);
                if (! $product || $product->status !== 'active' || $product->quantity < $item['quantity']) throw ValidationException::withMessages(['cart' => 'Một số sản phẩm không còn đủ số lượng. Vui lòng kiểm tra lại giỏ hàng.']);
                $subtotal += ($product->sale_price ?? $product->price) * $item['quantity'];
            }

            [$voucher, $shippingVoucher, $userVouchers, $discount, $finalShippingFee, $shippingDiscount] = $this->applyVouchers($request, $data, $subtotal, $shippingFee);
            $order = Order::create(array_merge($data, [
                'user_id' => $request->user()?->id, 'voucher_id' => $voucher?->id, 'shipping_voucher_id' => $shippingVoucher?->id,
                'voucher_code' => $voucher?->code, 'shipping_voucher_code' => $shippingVoucher?->code, 'order_number' => $orderNumber,
                'subtotal' => $subtotal, 'shipping_fee' => $finalShippingFee, 'discount_amount' => $discount + $shippingDiscount,
                'total' => max(0, $subtotal + $finalShippingFee - $discount), 'status' => 'pending',
            ]));
            foreach (array_filter([$voucher, $shippingVoucher]) as $appliedVoucher) {
                $appliedVoucher->increment('used_count');
                DB::table('user_vouchers')->where('id', $userVouchers[$appliedVoucher->id]->id)->update(['order_id' => $order->id, 'used_at' => now(), 'updated_at' => now()]);
            }
            foreach ($items as $item) {
                $product = $lockedProducts->get($item['product']->id); $price = $product->sale_price ?? $product->price;
                $order->items()->create(['product_id' => $product->id, 'product_name' => $product->name, 'unit_price' => $price, 'quantity' => $item['quantity'], 'line_total' => $price * $item['quantity']]);
                $product->decrement('quantity', $item['quantity']);
            }
            return $order;
        });

        if ($request->user()) $request->user()->update(['phone' => $data['phone'], 'address' => $data['address'], 'province' => $data['province'], 'district' => $data['district'], 'ward' => $data['ward'], 'address_detail' => $data['address_detail']]);
        $this->cartService->clear($request);
        $request->session()->put('last_checkout_order_id', $order->id);
        $request->session()->forget('checkout_order_number');
        return $order;
    }

    public function previewVoucher(Request $request, string $code): array
    {
        $items = $this->items($request);
        if ($items->isEmpty()) throw ValidationException::withMessages(['voucher_code' => 'Giỏ hàng của bạn đang trống.']);
        if (! $request->user()) throw ValidationException::withMessages(['voucher_code' => 'Vui lòng đăng nhập để sử dụng voucher.']);
        $voucher = Voucher::where('code', strtoupper(trim($code)))->first();
        $hasVoucher = $request->user()->vouchers()->whereKey($voucher?->id)->wherePivotNull('used_at')->wherePivot('claimed_at', '>=', now()->subDay())->exists();
        if (! $hasVoucher) throw ValidationException::withMessages(['voucher_code' => 'Bạn chưa nhận voucher này hoặc voucher đã được sử dụng.']);
        $subtotal = $items->sum(fn ($item) => ($item['product']->sale_price ?? $item['product']->price) * $item['quantity']);
        return ['code' => $voucher->code, 'name' => $voucher->name, 'discount' => $this->discountFor($voucher, $subtotal), 'freeShipping' => $voucher->is_free_shipping, 'type' => $voucher->type, 'value' => $voucher->value, 'maxDiscount' => $voucher->max_discount];
    }

    public function cancelPendingPayment(Order $order): array
    {
        return DB::transaction(function () use ($order) {
            $lockedOrder = Order::with('items')->lockForUpdate()->findOrFail($order->id);
            abort_unless($lockedOrder->status === 'pending' && $lockedOrder->paid_at === null, 409);
            foreach ($lockedOrder->items as $item) Product::whereKey($item->product_id)->increment('quantity', $item->quantity);
            foreach (array_filter([$lockedOrder->voucher_id, $lockedOrder->shipping_voucher_id]) as $voucherId) Voucher::whereKey($voucherId)->where('used_count', '>', 0)->decrement('used_count');
            DB::table('user_vouchers')->where('order_id', $lockedOrder->id)->update(['order_id' => null, 'used_at' => null, 'updated_at' => now()]);
            $lockedOrder->update(['status' => 'cancelled', 'cancel_reason' => 'change_order_info']);
            return $lockedOrder->items->mapWithKeys(fn ($item) => [$item->product_id => $item->quantity])->all();
        });
    }

    private function applyVouchers(Request $request, array $data, float $subtotal, float $shippingFee): array
    {
        $voucher = $shippingVoucher = null; $userVouchers = []; $discount = $shippingDiscount = 0;
        $voucherCode = strtoupper(trim($data['voucher_code'] ?? ''));
        if ($voucherCode) {
            $voucher = Voucher::where('code', $voucherCode)->lockForUpdate()->first();
            if (! $voucher) throw ValidationException::withMessages(['voucher_code' => 'Voucher giảm giá không tồn tại.']);
            if (! $request->user()) throw ValidationException::withMessages(['voucher_code' => 'Vui lòng đăng nhập để sử dụng voucher.']);
            $userVouchers[$voucher->id] = DB::table('user_vouchers')->where('user_id', $request->user()->id)->where('voucher_id', $voucher->id)->whereNull('used_at')->where('claimed_at', '>=', now()->subDay())->lockForUpdate()->first();
            if (! $userVouchers[$voucher->id] || $voucher->is_free_shipping) throw ValidationException::withMessages(['voucher_code' => 'Bạn chưa nhận voucher này hoặc voucher đã được sử dụng.']);
            $discount = $this->discountFor($voucher, $subtotal);
        }
        $shippingVoucherCode = strtoupper(trim($data['shipping_voucher_code'] ?? ''));
        if ($shippingVoucherCode) {
            $shippingVoucher = Voucher::where('code', $shippingVoucherCode)->lockForUpdate()->first();
            if (! $shippingVoucher) throw ValidationException::withMessages(['shipping_voucher_code' => 'Voucher vận chuyển không tồn tại.']);
            if (! $request->user()) throw ValidationException::withMessages(['shipping_voucher_code' => 'Vui lòng đăng nhập để sử dụng voucher.']);
            $userVouchers[$shippingVoucher->id] = DB::table('user_vouchers')->where('user_id', $request->user()->id)->where('voucher_id', $shippingVoucher->id)->whereNull('used_at')->where('claimed_at', '>=', now()->subDay())->lockForUpdate()->first();
            if (! $userVouchers[$shippingVoucher->id] || ! $shippingVoucher->is_free_shipping) throw ValidationException::withMessages(['shipping_voucher_code' => 'Voucher vận chuyển không hợp lệ hoặc đã được sử dụng.']);
            $this->discountFor($shippingVoucher, $subtotal);
            $shippingDiscount = (float) $shippingVoucher->value <= 0 ? $shippingFee : $this->discountAmount($shippingVoucher, $shippingFee);
            $shippingFee = max(0, $shippingFee - $shippingDiscount);
        }
        return [$voucher, $shippingVoucher, $userVouchers, $discount, $shippingFee, $shippingDiscount];
    }

    private function discountFor(?Voucher $voucher, float $subtotal): float
    {
        if (! $voucher || ! $voucher->is_active || ($voucher->starts_at && $voucher->starts_at->isFuture()) || ($voucher->ends_at && $voucher->ends_at->isPast()) || ($voucher->usage_limit && $voucher->used_count >= $voucher->usage_limit) || $subtotal < $voucher->min_order_amount) throw ValidationException::withMessages(['voucher_code' => 'Mã giảm giá không hợp lệ hoặc chưa đủ điều kiện áp dụng.']);
        return $this->discountAmount($voucher, $subtotal);
    }

    private function discountAmount(Voucher $voucher, float $amount): float
    {
        $discount = $voucher->type === 'percent' ? $amount * ($voucher->value / 100) : $voucher->value;
        return min($discount, $voucher->max_discount ?? $discount, $amount);
    }
}
