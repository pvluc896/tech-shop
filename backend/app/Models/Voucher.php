<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Voucher extends Model
{
    protected $fillable = ['code', 'claim_method', 'name', 'type', 'value', 'is_free_shipping', 'min_order_amount', 'max_discount', 'usage_limit', 'used_count', 'starts_at', 'ends_at', 'is_active'];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'is_active' => 'boolean', 'is_free_shipping' => 'boolean'];
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_vouchers')->withPivot(['claimed_at', 'used_at', 'order_id'])->withTimestamps();
    }
}
