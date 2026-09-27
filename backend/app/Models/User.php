<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Voucher;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'is_locked',
        'lock_reason',
        'password',
        'phone',
        'birthday',
        'gender',
        'address',
        'province',
        'district',
        'ward',
        'address_detail',
        'email_verified_at',
        'email_verification_code',
        'email_verification_code_expires_at',
        'cart',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birthday' => 'date',
            'email_verification_code_expires_at' => 'datetime',
            'cart' => 'array',
            'is_locked' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'user_vouchers')->withPivot(['claimed_at', 'used_at', 'order_id'])->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'favorites')->withTimestamps();
    }
}
