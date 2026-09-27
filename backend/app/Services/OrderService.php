<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function cancel(Order $order, User $user, string $reason): void
    {
        DB::transaction(function () use ($order, $user, $reason) {
            $lockedOrder = Order::query()->lockForUpdate()->findOrFail($order->id);
            if ($lockedOrder->user_id !== $user->id || $lockedOrder->status !== 'pending') abort(422, 'Đơn hàng này không còn có thể hủy.');
            $items = $lockedOrder->items()->get();
            $products = Product::whereIn('id', $items->pluck('product_id')->filter())->lockForUpdate()->get()->keyBy('id');
            foreach ($items as $item) $products->get($item->product_id)?->increment('quantity', $item->quantity);
            $userVoucher = DB::table('user_vouchers')->where('user_id', $user->id)->where('order_id', $lockedOrder->id)->whereNotNull('used_at')->lockForUpdate()->first();
            if ($userVoucher) {
                DB::table('user_vouchers')->where('id', $userVoucher->id)->update(['order_id' => null, 'used_at' => null, 'updated_at' => now()]);
                Voucher::whereKey($lockedOrder->voucher_id)->where('used_count', '>', 0)->decrement('used_count');
            }
            $lockedOrder->update(['status' => 'cancelled', 'cancel_reason' => $reason]);
        });
    }

    public function updateStatus(Order $order, string $status): bool
    {
        if ($order->status === 'cancelled') return false;
        $order->update(['status' => $status]);
        return true;
    }
}
