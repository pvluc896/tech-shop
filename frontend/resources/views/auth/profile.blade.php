@extends('layouts.shop')
@section('title', 'Thông tin cá nhân | Luc Tech Shop')
@section('content')
    <div class="container py-5"><div class="row justify-content-center"><div class="col-lg-7"><div class="shop-card card bg-white"><div class="card-body p-4 p-md-5">
        <div class="d-flex justify-content-between align-items-start gap-3 mb-4"><div><div class="shop-eyebrow mb-2">Tài khoản của tôi</div><h1 class="shop-page-title h2 mb-0">Thông tin cá nhân</h1></div><a href="{{ route('password.change') }}" class="btn btn-outline-primary btn-sm">Đổi mật khẩu</a></div>
        <div class="rounded-3 p-3 mb-4" style="background: #f8fafc; border: 1px solid #eaecf0;"><div class="row g-3 small">
            <div class="col-sm-6"><span class="text-muted d-block">Trạng thái email</span><strong class="{{ auth()->user()->email_verified_at ? 'text-success' : 'text-warning' }}">{{ auth()->user()->email_verified_at ? 'Đã xác thực' : 'Chưa xác thực' }}</strong></div>
            <div class="col-sm-6">@if (! auth()->user()->email_verified_at)<a href="{{ route('email.verify') }}" class="btn btn-outline-primary btn-sm">Xác thực email</a>@endif</div>
            <div class="col-sm-6"><span class="text-muted d-block">Ngày tạo tài khoản</span><strong>{{ auth()->user()->created_at?->format('d/m/Y') }}</strong></div>
            <div class="col-sm-6"><span class="text-muted d-block">Cập nhật lần cuối</span><strong>{{ auth()->user()->updated_at?->format('d/m/Y') }}</strong></div>
        </div></div>
        <form method="POST" action="{{ route('profile.update') }}">@csrf @method('PUT')
            <div class="mb-3"><label for="name" class="form-label fw-semibold">Họ và tên</label><input id="name" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control @error('name') is-invalid @enderror" required><div class="invalid-feedback">@error('name') {{ $message }} @enderror</div></div>
            <div class="mb-3"><label for="email" class="form-label fw-semibold">Email</label><input id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control @error('email') is-invalid @enderror" required><div class="invalid-feedback">@error('email') {{ $message }} @enderror</div></div>
            <div class="mb-3"><label for="phone" class="form-label fw-semibold">Số điện thoại</label><input id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="form-control @error('phone') is-invalid @enderror" placeholder="VD: 0901234567"><div class="invalid-feedback">@error('phone') {{ $message }} @enderror</div></div>
            <div class="row"><div class="col-md-6 mb-3"><label for="birthday" class="form-label fw-semibold">Ngày sinh</label><input id="birthday" type="date" name="birthday" value="{{ old('birthday', auth()->user()->birthday?->format('Y-m-d')) }}" class="form-control @error('birthday') is-invalid @enderror"><div class="invalid-feedback">@error('birthday') {{ $message }} @enderror</div></div><div class="col-md-6 mb-3"><label for="gender" class="form-label fw-semibold">Giới tính</label><select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror"><option value="">Chọn giới tính</option><option value="male" @selected(old('gender', auth()->user()->gender) === 'male')>Nam</option><option value="female" @selected(old('gender', auth()->user()->gender) === 'female')>Nữ</option><option value="other" @selected(old('gender', auth()->user()->gender) === 'other')>Khác</option></select><div class="invalid-feedback">@error('gender') {{ $message }} @enderror</div></div></div>
            <div class="mb-4"><label for="address" class="form-label fw-semibold">Địa chỉ</label><textarea id="address" name="address" rows="3" class="form-control @error('address') is-invalid @enderror" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố">{{ old('address', auth()->user()->address) }}</textarea><div class="invalid-feedback">@error('address') {{ $message }} @enderror</div></div>
            <button class="btn btn-primary shop-register" type="submit">Lưu thay đổi</button>
        </form>
    </div></div></div></div></div>
@endsection
