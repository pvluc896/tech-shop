@extends('layouts.admin')

@section('title', 'Quản lý khách hàng | Luc Tech Shop')
@section('page-title', 'Quản lý khách hàng')

@section('content')
@php
    $lockReasons = [
        'suspicious_activity' => 'Hoạt động đăng nhập bất thường',
        'policy_violation' => 'Vi phạm chính sách cửa hàng',
        'fraudulent_order' => 'Nghi ngờ đơn hàng gian lận',
        'customer_request' => 'Theo yêu cầu của khách hàng',
        'other' => 'Lý do khác',
    ];
@endphp

<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <div class="admin-dashboard-eyebrow">Quản trị tài khoản</div>
            <h2 class="admin-dashboard-title mb-1">Khách hàng</h2>
            <p class="text-secondary mb-0">Khóa tài khoản cần chọn lý do; tài khoản quản trị viên luôn được bảo vệ.</p>
        </div>
        <span class="badge text-bg-light border text-secondary px-3 py-2">{{ $users->total() }} tài khoản</span>
    </div>

    <form method="GET" class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3"><div class="row g-2">
            <div class="col-md-7 col-lg-6"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
            <div class="col-md-3 col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="user" @selected(request('role') === 'user')>Khách hàng</option><option value="admin" @selected(request('role') === 'admin')>Quản trị viên</option></select></div>
            <div class="col-md-2"><button class="btn btn-primary w-100" type="submit">Lọc</button></div>
            @if(request()->filled('search') || request()->filled('role'))<div class="col-lg-2"><a href="{{ route('admin.users.index') }}" class="btn btn-light border w-100">Xóa lọc</a></div>@endif
        </div></div>
    </form>

    <div class="card border-0 shadow-sm admin-dashboard-panel"><div class="table-responsive">
        <table class="table table-hover align-middle mb-0"><thead><tr><th class="ps-4">Khách hàng</th><th>Liên hệ</th><th>Ngày tham gia</th><th>Đơn hàng</th><th>Tổng chi tiêu</th><th>Quyền</th><th>Trạng thái</th><th class="text-end pe-4">Thao tác</th></tr></thead><tbody>
        @forelse($users as $user)
            <tr>
                <td class="ps-4"><strong class="d-block">{{ $user->name }}</strong><small class="text-secondary">#{{ $user->id }}</small></td>
                <td><span class="d-block">{{ $user->email }}</span><small class="text-secondary">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
                <td class="text-secondary">{{ $user->created_at->format('d/m/Y') }}</td>
                <td><span class="badge text-bg-light border text-dark">{{ $user->orders_count }} đơn</span></td>
                <td class="fw-semibold">{{ number_format($user->orders_total ?? 0, 0, ',', '.') }} ₫</td>
                <td><span class="badge rounded-pill {{ $user->role === 'admin' ? 'text-bg-primary' : 'text-bg-secondary' }}">{{ $user->role === 'admin' ? 'Quản trị viên' : 'Khách hàng' }}</span></td>
                <td><span class="badge rounded-pill {{ $user->is_locked ? 'text-bg-danger' : 'text-bg-success' }}">{{ $user->is_locked ? 'Đã khóa' : 'Hoạt động' }}</span>@if($user->is_locked && $user->lock_reason)<small class="d-block text-danger mt-1">{{ $lockReasons[$user->lock_reason] ?? $user->lock_reason }}</small>@endif</td>
                <td class="text-end pe-4">
                    @if($user->role === 'admin')
                        <span class="small text-secondary">Được bảo vệ</span>
                    @elseif($user->is_locked)
                        <div class="d-inline-flex gap-2"><form method="POST" action="{{ route('admin.users.lock', $user) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-success" type="submit">Mở khóa</button></form><form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Xóa tài khoản {{ $user->name }}? Thao tác này không thể hoàn tác.');">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button></form></div>
                    @else
                        <div class="d-inline-flex gap-2"><button class="btn btn-sm btn-outline-warning" type="button" data-bs-toggle="modal" data-bs-target="#adminLockUserModal" data-lock-action="{{ route('admin.users.lock', $user) }}" data-user-name="{{ $user->name }}">Khóa</button><form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Xóa tài khoản {{ $user->name }}? Thao tác này không thể hoàn tác.');">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button></form></div>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center text-secondary py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
        @endforelse
        </tbody></table>
    </div>
    @if($users->hasPages())<div class="card-footer bg-white border-0 px-4 py-3 d-flex flex-wrap justify-content-between align-items-center gap-3"><span class="small text-secondary">Hiển thị {{ $users->firstItem() }}–{{ $users->lastItem() }} trong {{ $users->total() }} tài khoản</span>{{ $users->onEachSide(1)->links() }}</div>@endif
    </div>
</div>
@endsection
