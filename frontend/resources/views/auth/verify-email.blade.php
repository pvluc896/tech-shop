@extends('layouts.shop')
@section('title', 'Xác thực email | Luc Tech Shop')
@section('content')
    <div class="container py-5"><div class="row justify-content-center"><div class="col-md-8 col-lg-5"><div class="shop-card card bg-white"><div class="card-body p-4 p-md-5">
        <div class="shop-eyebrow mb-2">Bảo mật tài khoản</div><h1 class="shop-page-title h2 mb-2">Xác thực email</h1><p class="text-muted mb-4">Mã gồm 6 số sẽ được gửi tới <strong>{{ auth()->user()->email }}</strong>.</p>
        <form method="POST" action="{{ route('email.verification.send') }}" class="mb-4">@csrf<button class="btn btn-outline-primary w-100" type="submit">Gửi mã xác thực</button></form>
        <form method="POST" action="{{ route('email.verification.check') }}">@csrf<div class="mb-4"><label for="code" class="form-label fw-semibold">Mã xác thực</label><input id="code" name="code" inputmode="numeric" maxlength="6" class="form-control @error('code') is-invalid @enderror" placeholder="Nhập 6 chữ số" required><div class="invalid-feedback">@error('code') {{ $message }} @enderror</div></div><button class="btn btn-primary shop-register w-100 py-2" type="submit">Xác nhận email</button></form>
    </div></div></div></div></div>
@endsection
