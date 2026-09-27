@extends('layouts.shop')

@section('title', 'Kho voucher | Luc Tech Shop')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <div class="text-primary text-uppercase small fw-bold">Ưu đãi dành cho bạn</div>
        <h1 class="h2 fw-bold mb-1">Kho voucher</h1>
        <p class="text-secondary mb-0">Nhận voucher trước, sau đó nhập mã tại bước thanh toán.</p>
    </div>

    <form method="POST" action="{{ route('vouchers.redeem') }}" class="card border-0 shadow-sm mb-4">
        @csrf
        <div class="card-body p-3 p-md-4">
            <label class="form-label fw-bold" for="voucher_code">Nhập mã voucher</label>
            <div class="input-group">
                <input id="voucher_code" name="voucher_code" value="{{ old('voucher_code') }}" class="form-control text-uppercase @error('voucher_code') is-invalid @enderror" placeholder="Ví dụ: LUC50K" aria-describedby="voucher-code-help">
                <button class="btn btn-primary px-4" type="submit">Nhận mã</button>
            </div>
            <div id="voucher-code-help" class="form-text">Có mã khuyến mãi? Nhập tại đây để thêm vào ví voucher của bạn.</div>
            @error('voucher_code')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
        </div>
    </form>

    <div class="row g-3 mb-5">
        @forelse ($vouchers as $voucher)
            <div class="col-md-6 col-lg-4">
                <article class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between gap-2">
                            <span class="badge text-bg-primary align-self-start">{{ $voucher->code }}</span>
                            @if ($voucher->ends_at)
                                <span class="small text-secondary">HSD {{ $voucher->ends_at->format('d/m/Y') }}</span>
                            @endif
                        </div>
                        <h2 class="h5 fw-bold mt-3">{{ $voucher->name }}</h2>
                        <p class="text-secondary small">
                            Đơn tối thiểu {{ number_format($voucher->min_order_amount, 0, ',', '.') }} ₫
                            @if ($voucher->usage_limit)
                                · Còn {{ max(0, $voucher->usage_limit - $voucher->used_count) }} lượt
                            @endif
                        </p>

                        @if ($claimedVoucherIds->contains($voucher->id))
                            <button class="btn btn-light border w-100" disabled>Đã nhận</button>
                        @else
                            <form method="POST" action="{{ route('vouchers.claim', $voucher) }}">
                                @csrf
                                <button class="btn btn-primary w-100" type="submit">Nhận voucher</button>
                            </form>
                        @endif
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-info mb-0">Hiện chưa có voucher phù hợp.</div></div>
        @endforelse
    </div>

    <h2 class="h4 fw-bold mb-3">Voucher của tôi</h2>
    <div class="row g-3">
        @forelse ($claimedVouchers as $voucher)
            @php($expiresAt = \Carbon\Carbon::parse($voucher->pivot->claimed_at)->addDay())
            @php($isExpired = ! $voucher->pivot->used_at && $expiresAt->isPast())
            <div class="col-md-6 col-lg-4">
                <article class="card border h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <strong class="text-primary">{{ $voucher->code }}</strong>
                            <span class="badge {{ $voucher->pivot->used_at || $isExpired ? 'text-bg-secondary' : 'text-bg-success' }}">{{ $voucher->pivot->used_at ? 'Đã dùng' : ($isExpired ? 'Đã hết hạn' : 'Sẵn sàng dùng') }}</span>
                        </div>
                        <div class="mt-2">{{ $voucher->name }}</div>
                        <small class="text-secondary">
                            {{ $voucher->pivot->used_at ? 'Đã dùng ngày ' . \Carbon\Carbon::parse($voucher->pivot->used_at)->format('d/m/Y') : ($isExpired ? 'Đã hết hạn ngày ' . $expiresAt->format('d/m/Y') : 'Có hiệu lực đến ' . $expiresAt->format('d/m/Y')) }}
                        </small>
                        @if (! $voucher->pivot->used_at)
                            <form method="POST" action="{{ route('vouchers.remove', $voucher) }}" class="mt-3" onsubmit="return confirm('Xóa voucher {{ $voucher->code }} khỏi ví của bạn?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger w-100" type="submit">Xóa voucher</button>
                            </form>
                        @endif
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12"><p class="text-secondary">Bạn chưa nhận voucher nào.</p></div>
        @endforelse
    </div>
</div>
@endsection
