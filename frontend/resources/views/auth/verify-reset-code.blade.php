@extends('layouts.shop')
@section('title', 'Xác nhận mã | Luc Tech Shop')
@section('content')
    <div class="container py-5"><div class="row justify-content-center"><div class="col-md-8 col-lg-5"><div class="shop-card card bg-white"><div class="card-body p-4 p-md-5">
        <div class="shop-eyebrow mb-2">Khôi phục tài khoản</div><h1 class="shop-page-title h2 mb-2">Nhập mã xác thực</h1><p class="text-muted mb-4">Nhập mã 6 số chúng tôi vừa gửi tới Gmail của bạn.</p>
        <form method="POST" action="{{ route('password.code.verify') }}">@csrf
            <input name="email" type="hidden" value="{{ old('email', $email) }}">
            <div class="mb-4"><label for="code" class="form-label fw-semibold">Mã xác thực</label><input id="code" name="code" inputmode="numeric" maxlength="6" class="form-control @error('code') is-invalid @enderror" placeholder="Nhập 6 chữ số" required autofocus><div class="invalid-feedback">@error('code') {{ $message }} @enderror</div></div>
            <button class="btn btn-primary shop-register w-100 py-2" type="submit">Xác nhận mã</button>
        </form>
    </div></div></div></div></div>
@endsection
