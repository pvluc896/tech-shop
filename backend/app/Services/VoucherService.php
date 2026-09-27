<?php

namespace App\Services;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Validation\ValidationException;

class VoucherService
{
    public function wallet(User $user): array
    {
        $claimedVoucherIds = $user->vouchers()->pluck('vouchers.id');
        $vouchers = Voucher::where('is_active', true)->where('claim_method', 'claim')->whereNotIn('id', $claimedVoucherIds)
            ->where(fn ($query) => $query->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->where(fn ($query) => $query->whereNull('usage_limit')->orWhereColumn('used_count', '<', 'usage_limit'))
            ->latest()->get();
        return compact('vouchers', 'claimedVoucherIds') + ['claimedVouchers' => $user->vouchers()->orderByPivot('claimed_at', 'desc')->get()];
    }

    public function claim(User $user, Voucher $voucher): void
    {
        abort_unless($voucher->claim_method === 'claim' && $this->isAvailable($voucher), 404);
        $user->vouchers()->syncWithoutDetaching([$voucher->id => ['claimed_at' => now()]]);
    }

    public function redeem(User $user, string $code): Voucher
    {
        $voucher = Voucher::where('claim_method', 'code')->whereRaw('UPPER(code) = ?', [strtoupper(trim($code))])->first();
        if (! $voucher) throw ValidationException::withMessages(['voucher_code' => 'Không tìm thấy mã voucher này.']);
        if (! $this->isAvailable($voucher)) throw ValidationException::withMessages(['voucher_code' => $this->unavailableMessage($voucher)]);
        $user->vouchers()->syncWithoutDetaching([$voucher->id => ['claimed_at' => now()]]);
        return $voucher;
    }

    public function remove(User $user, Voucher $voucher): bool
    {
        return (bool) $user->vouchers()->wherePivotNull('used_at')->detach($voucher->id);
    }

    public function create(array $data): Voucher { return Voucher::create($data); }
    public function update(Voucher $voucher, array $data): void { $voucher->update($data); }
    public function delete(Voucher $voucher): void { $voucher->delete(); }

    private function isAvailable(Voucher $voucher): bool
    {
        return $voucher->is_active && (! $voucher->starts_at || $voucher->starts_at->isPast()) && (! $voucher->ends_at || $voucher->ends_at->isFuture()) && (! $voucher->usage_limit || $voucher->used_count < $voucher->usage_limit);
    }

    private function unavailableMessage(Voucher $voucher): string
    {
        if (! $voucher->is_active) return 'Mã voucher này đang tạm ngưng.';
        if ($voucher->starts_at && $voucher->starts_at->isFuture()) return 'Voucher có hiệu lực từ ngày '.$voucher->starts_at->format('d/m/Y').'.';
        if ($voucher->ends_at && $voucher->ends_at->isPast()) return 'Voucher này đã hết hạn.';
        if ($voucher->usage_limit && $voucher->used_count >= $voucher->usage_limit) return 'Voucher này đã hết lượt sử dụng.';
        return 'Mã voucher hiện chưa thể sử dụng.';
    }
}
