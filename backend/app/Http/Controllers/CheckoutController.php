<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function index(Request $request, CheckoutService $checkout)
    {
        $items = $checkout->items($request);
        if ($items->isEmpty()) return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        $subtotal = $items->sum(fn ($item) => ($item['product']->sale_price ?? $item['product']->price) * $item['quantity']);
        $shippingFee = $checkout->shippingFee($subtotal, old('province', $request->user()?->province));
        $availableVouchers = $checkout->availableVouchers($request);
        $orderNumber = $checkout->orderNumber($request);
        return view('checkout.index', compact('items', 'subtotal', 'shippingFee', 'availableVouchers', 'orderNumber'));
    }

    public function store(Request $request, CheckoutService $checkout)
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'max:255'],
            'phone' => array_filter(['required', 'regex:/^0\d{9}$/', $request->user() ? Rule::unique('users', 'phone')->ignore($request->user()->id) : null]),
            'province' => ['required', 'string', 'max:100'], 'district' => ['required', 'string', 'max:100'], 'ward' => ['required', 'string', 'max:100'], 'address_detail' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'in:cod,bank_transfer'], 'voucher_code' => ['nullable', 'string', 'max:50'], 'shipping_voucher_code' => ['nullable', 'string', 'max:50'], 'note' => ['nullable', 'string', 'max:1000'],
        ]);
        if ($checkout->items($request)->isEmpty()) return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        $order = $checkout->placeOrder($request, $data);
        if ($order->payment_method === 'bank_transfer' && $request->expectsJson()) return response()->json(['orderNumber' => $order->order_number, 'total' => $order->total, 'statusUrl' => route('checkout.payment-status', $order), 'cancelUrl' => route('checkout.payment-cancel', $order), 'successUrl' => route('checkout.success', ['order' => $order, 'payment_confirmed' => 1])]);
        $message = 'Đặt hàng thành công. Cảm ơn bạn đã mua sắm!'.($request->user() ? ' Thông tin giao hàng đã được lưu cho lần sau.' : '');
        return redirect()->route('checkout.success', $order)->with('success', $message);
    }

    public function previewVoucher(Request $request, CheckoutService $checkout)
    {
        $data = $request->validate(['voucher_code' => ['required', 'string', 'max:50']]);
        return response()->json($checkout->previewVoucher($request, $data['voucher_code']));
    }

    public function success(Request $request, Order $order)
    {
        abort_unless(session('last_checkout_order_id') === $order->id || (auth()->check() && $order->user_id === auth()->id()), 403);
        if ($request->boolean('payment_confirmed') && $order->paid_at) return redirect()->route('checkout.success', $order)->with('success', 'Thanh toán thành công! Đơn hàng của bạn đã được xác nhận.');
        return view('checkout.success', compact('order'));
    }

    public function paymentStatus(Request $request, Order $order)
    {
        abort_unless($request->session()->get('last_checkout_order_id') === $order->id || ($request->user() && $order->user_id === $request->user()->id), 403);
        return response()->json(['paid' => $order->paid_at !== null, 'status' => $order->status]);
    }

    public function cancelPendingPayment(Request $request, Order $order, CheckoutService $checkout)
    {
        abort_unless($request->session()->get('last_checkout_order_id') === $order->id || ($request->user() && $order->user_id === $request->user()->id), 403);
        abort_unless($order->payment_method === 'bank_transfer' && $order->status === 'pending' && $order->paid_at === null, 409);
        $cart = $checkout->cancelPendingPayment($order);
        $request->session()->put('cart', $cart);
        if ($request->user()) $request->user()->update(['cart' => $cart]);
        $request->session()->forget(['last_checkout_order_id', 'checkout_order_number']);
        return response()->json(['success' => true]);
    }
}
