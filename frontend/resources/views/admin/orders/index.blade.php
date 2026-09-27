@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng | Luc Tech Shop')
@section('page-title', 'Quản lý đơn hàng')

@section('content')
@php
    $statusTabs = [
        '' => 'Tất cả đơn hàng',
        'pending' => 'Chờ xác nhận',
        'confirmed' => 'Đã xác nhận',
        'shipping' => 'Đang giao',
        'delivered' => 'Đã giao',
        'cancelled' => 'Đã hủy',
    ];
@endphp
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4"><div><div class="admin-dashboard-eyebrow">Vận hành cửa hàng</div><h2 class="admin-dashboard-title mb-1">Đơn hàng</h2><p class="text-secondary mb-0">Theo dõi và cập nhật tiến trình xử lý đơn hàng.</p></div><span class="badge text-bg-light border text-secondary px-3 py-2">{{ $orders->total() }} đơn hàng</span></div>

    <div class="card border-0 shadow-sm mb-4"><div class="card-body p-3">
        <form method="GET" class="row g-2">@if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif<div class="col-md-8 col-lg-6"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="Tìm mã đơn, tên khách hàng, số điện thoại"></div><div class="col-md-4 col-lg-2"><button class="btn btn-primary w-100" type="submit">Tìm đơn</button></div>@if(request()->filled('search'))<div class="col-lg-2"><a href="{{ route('admin.orders.index', ['status' => request('status')]) }}" class="btn btn-light border w-100">Xóa tìm kiếm</a></div>@endif</form>
        <div class="d-flex flex-nowrap overflow-auto gap-2 pt-3 mt-3 border-top">
            @foreach($statusTabs as $value => $label)
                @php($isActive = request('status', '') === $value)
                <a href="{{ route('admin.orders.index', array_filter(['status' => $value, 'search' => request('search')])) }}" class="btn btn-sm rounded-pill text-nowrap {{ $isActive ? 'btn-primary' : 'btn-light border text-secondary' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div></div>

    <div class="card border-0 shadow-sm admin-dashboard-panel"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th class="ps-4">Mã đơn</th><th>Khách hàng</th><th>Ngày đặt</th><th>Thanh toán</th><th>Tổng tiền</th><th>Trạng thái</th><th class="text-end pe-4"></th></tr></thead><tbody>
        @forelse($orders as $order)
            @php($status = match($order->status) { 'confirmed' => ['Đã xác nhận', 'text-bg-info'], 'shipping' => ['Đang giao', 'text-bg-primary'], 'delivered' => ['Đã giao', 'text-bg-success'], 'cancelled' => ['Đã hủy', 'text-bg-danger'], default => ['Chờ xác nhận', 'text-bg-warning'] })
            <tr><td class="ps-4"><a href="{{ route('admin.orders.show', $order) }}" class="fw-bold text-decoration-none">{{ $order->order_number }}</a></td><td><strong class="d-block">{{ $order->customer_name }}</strong><small class="text-secondary">{{ $order->phone }}</small></td><td class="text-secondary">{{ $order->created_at->format('d/m/Y') }}</td><td><span class="small">{{ $order->payment_method === 'bank_transfer' ? 'Chuyển khoản' : 'COD' }}</span></td><td class="fw-semibold">{{ number_format($order->total, 0, ',', '.') }} ₫</td><td><span class="badge rounded-pill {{ $status[1] }}">{{ $status[0] }}</span></td><td class="text-end pe-4"><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a></td></tr>
        @empty
            <tr><td colspan="7" class="text-center text-secondary py-5">Không tìm thấy đơn hàng phù hợp.</td></tr>
        @endforelse
    </tbody></table></div>
    @if($orders->hasPages())<div class="card-footer bg-white border-0 px-4 py-3 d-flex flex-wrap justify-content-between align-items-center gap-3"><span class="small text-secondary">Hiển thị {{ $orders->firstItem() }}–{{ $orders->lastItem() }} trong {{ $orders->total() }} đơn hàng</span>{{ $orders->onEachSide(1)->links() }}</div>@endif
    </div>
</div>
@endsection
