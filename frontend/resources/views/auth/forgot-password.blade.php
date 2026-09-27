@extends('layouts.shop')
@section('title', 'Quên mật khẩu | Luc Tech Shop')
@section('content')
    <div class="container py-5"><div class="row justify-content-center"><div class="col-md-8 col-lg-5"><div class="card border-0 shadow-sm"><div class="card-body p-4 p-md-5">
        <h1 class="h3 fw-bold mb-2">Quên mật khẩu?</h1><p class="text-secondary mb-4">Nhập email đăng ký, chúng tôi sẽ gửi mã đặt lại mật khẩu về Gmail của bạn.</p>
        <form method="POST" action="{{ route('password.email.send') }}">@csrf<div class="mb-4"><label for="email" class="form-label fw-semibold">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div><button class="btn btn-primary w-100 py-2 fw-semibold" type="submit">Gửi mã xác thực</button></form>
        <p class="text-center small mt-4 mb-0"><a class="fw-semibold text-decoration-none" href="{{ route('login') }}">Quay lại đăng nhập</a></p>
    </div></div></div></div></div>
@endsection
