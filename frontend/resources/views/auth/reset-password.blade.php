@extends('layouts.shop')

@section('title', 'Đặt lại mật khẩu | Luc Tech Shop')

@section('content')
    <div class="container py-5"><div class="row justify-content-center"><div class="col-md-8 col-lg-5"><div class="card border-0 shadow-sm"><div class="card-body p-4 p-md-5">
        <h1 class="h3 fw-bold mb-2">Đặt mật khẩu mới</h1><p class="text-secondary mb-4">Mã đã được xác nhận. Hãy tạo mật khẩu mới cho tài khoản của bạn.</p>
        <form method="POST" action="{{ route('password.reset.store') }}">@csrf
            <x-password-input name="password" label="Mật khẩu mới" autocomplete="new-password" :autofocus="true" />
            <x-password-input name="password_confirmation" label="Xác nhận mật khẩu mới" autocomplete="new-password" />
            <button class="btn btn-primary w-100 py-2 fw-semibold" type="submit">Cập nhật mật khẩu</button>
        </form>
    </div></div></div></div></div>
@endsection
