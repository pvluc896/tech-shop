<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'voucher_id', 'shipping_voucher_id', 'order_number', 'customer_name', 'email', 'phone', 'address', 'province', 'district', 'ward', 'address_detail', 'payment_method', 'voucher_code', 'shipping_voucher_code', 'note', 'subtotal', 'shipping_fee', 'discount_amount', 'total', 'status', 'cancel_reason', 'sepay_transaction_id', 'paid_at'];

    protected function casts(): array
    {
        return ['paid_at' => 'datetime'];
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
