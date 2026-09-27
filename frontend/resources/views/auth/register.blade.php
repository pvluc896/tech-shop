@extends('layouts.shop')

@section('title', 'Đăng ký | Luc Tech Shop')

@section('content')
    <div class="container py-5 py-lg-6">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-6 col-xl-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4"><h1 class="h3 fw-bold mb-2">Tạo tài khoản</h1><p class="text-secondary mb-0">Đăng ký nhanh để mua sắm thuận tiện hơn.</p></div>
                        <form method="POST" action="{{ route('register.store') }}">@csrf
                            <div class="mb-3"><label for="name" class="form-label fw-semibold">Họ và tên</label><input id="name" name="name" value="{{ old('name') }}" autocomplete="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nguyễn Văn A" required autofocus>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="mb-3"><label for="email" class="form-label fw-semibold">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" class="form-control @error('email') is-invalid @enderror" placeholder="name@example.com" required>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <x-password-input name="password" label="Mật khẩu" autocomplete="new-password" />
                            <x-password-input name="password_confirmation" label="Xác nhận mật khẩu" autocomplete="new-password" />
                            <button class="btn btn-primary w-100 py-2 fw-semibold" type="submit">Tạo tài khoản</button>
                        </form>
                        <p class="text-center text-secondary small mt-4 mb-0">Đã có tài khoản? <a class="fw-semibold text-decoration-none" href="{{ route('login') }}">Đăng nhập</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
