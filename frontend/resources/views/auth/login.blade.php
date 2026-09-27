@extends('layouts.shop')

@section('title', 'Đăng nhập | Luc Tech Shop')

@section('content')
    <div class="container py-5 py-lg-6">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5 col-xl-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center p-3 fs-4 mb-3">🔒</div>
                            <h1 class="h3 fw-bold mb-2">Chào mừng trở lại</h1>
                            <p class="text-secondary mb-0">Đăng nhập để tiếp tục mua sắm.</p>
                        </div>
                        <form method="POST" action="{{ route('login.store') }}">@csrf
                            <div class="mb-3"><label for="email" class="form-label fw-semibold">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" class="form-control @error('email') is-invalid @enderror" placeholder="name@example.com" required autofocus>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <x-password-input name="password" label="Mật khẩu" />
                            <div class="d-flex justify-content-between align-items-center mb-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="remember" id="remember"><label class="form-check-label small" for="remember">Ghi nhớ đăng nhập</label></div><a class="small fw-semibold text-decoration-none" href="{{ route('password.request') }}">Quên mật khẩu?</a></div>
                            <button class="btn btn-primary w-100 py-2 fw-semibold" type="submit">Đăng nhập</button>
                        </form>
                        <p class="text-center text-secondary small mt-4 mb-0">Chưa có tài khoản? <a class="fw-semibold text-decoration-none" href="{{ route('register') }}">Đăng ký ngay</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
