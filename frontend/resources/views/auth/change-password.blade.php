@extends('layouts.shop')

@section('title', 'Đổi mật khẩu | Luc Tech Shop')

@section('content')
    <div class="container py-5"><div class="row justify-content-center"><div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm"><div class="card-body p-4 p-md-5">
            <h1 class="h3 fw-bold mb-2">Đổi mật khẩu</h1><p class="text-secondary mb-4">Bảo vệ tài khoản với mật khẩu mạnh và riêng tư.</p>
            <form method="POST" action="{{ route('password.update') }}">@csrf @method('PUT')
                <x-password-input name="current_password" label="Mật khẩu hiện tại" />
                <x-password-input name="password" label="Mật khẩu mới" autocomplete="new-password" />
                <x-password-input name="password_confirmation" label="Xác nhận mật khẩu mới" autocomplete="new-password" />
                <button class="btn btn-primary w-100 py-2 fw-semibold" type="submit">Cập nhật mật khẩu</button>
            </form>
            <p class="small mt-3 mb-0"><a class="fw-semibold text-decoration-none" href="{{ route('password.request') }}">Quên mật khẩu hiện tại?</a></p>
        </div></div>
    </div></div></div>
@endsection
