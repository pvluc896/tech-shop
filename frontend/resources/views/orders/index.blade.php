@extends('layouts.shop')

@section('title', 'Đơn hàng của tôi | Luc Tech Shop')

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
<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div><div class="text-primary text-uppercase small fw-bold mb-2">Tài khoản của tôi</div><h1 class="h2 fw-bold mb-1">Đơn hàng của tôi</h1><p class="text-secondary mb-0">Theo dõi các đơn hàng bạn đã đặt tại Luc Tech Shop.</p></div>
        <a class="btn btn-outline-primary rounded-pill px-4" href="{{ route('products.index') }}">Tiếp tục mua sắm</a>
    </div>

    <div class="d-flex flex-nowrap overflow-auto gap-2 pb-3 mb-3 border-bottom">
        @foreach($statusTabs as $value => $label)
            <a href="{{ route('orders.index', array_filter(['status' => $value])) }}" class="btn btn-sm rounded-pill text-nowrap {{ ($status ?? '') === $value ? 'btn-primary' : 'btn-light border text-secondary' }}">{{ $label }}</a>
        @endforeach
    </div>

    @forelse ($orders as $order)
        @php($status = match($order->status) { 'confirmed' => ['Đã xác nhận', 'text-bg-info'], 'shipping' => ['Đang giao', 'text-bg-primary'], 'delivered' => ['Đã giao', 'text-bg-success'], 'cancelled' => ['Đã hủy', 'text-bg-danger'], default => ['Chờ xác nhận', 'text-bg-warning'] })
        <article class="order-card card border-0 shadow-sm mb-3"><div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3"><div><div class="small text-secondary mb-1">Mã đơn hàng</div><a href="{{ route('orders.show', $order) }}" class="h5 fw-bold text-decoration-none mb-0">{{ $order->order_number }}</a></div><span class="badge rounded-pill {{ $status[1] }} px-3 py-2">{{ $status[0] }}</span></div>
            <div class="order-card-details row g-3 mt-2"><div class="col-sm-4"><span>Ngày đặt</span><strong>{{ $order->created_at->format('d/m/Y') }}</strong></div><div class="col-sm-4"><span>Sản phẩm</span><strong>{{ $order->items_count }} sản phẩm</strong></div><div class="col-sm-4"><span>Tổng thanh toán</span><strong class="text-primary">{{ number_format($order->total, 0, ',', '.') }} ₫</strong></div></div>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 border-top mt-4 pt-3"><span class="small text-secondary">{{ $order->payment_method === 'bank_transfer' ? 'Chuyển khoản ngân hàng' : 'Thanh toán khi nhận hàng' }}</span><div class="d-flex gap-2"><a class="btn btn-primary btn-sm px-3" href="{{ route('orders.show', $order) }}">Xem chi tiết</a>@if($order->status === 'pending')<button class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm" type="button" data-bs-toggle="modal" data-bs-target="#cancelOrderModal" data-cancel-action="{{ route('orders.cancel', $order) }}" data-order-number="{{ $order->order_number }}">Hủy đơn</button>@endif</div></div>
        </div></article>
    @empty
        <div class="card border-0 shadow-sm"><div class="card-body text-center py-5"><div class="empty-order-icon mx-auto mb-3"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 3h12a2 2 0 0 1 2 2v16l-3-2-3 2-3-2-3 2-2-1V5a2 2 0 0 1 2-2Z"/><path d="M9 8h6M9 12h6"/></svg></div><h2 class="h4 fw-bold">Bạn chưa có đơn hàng nào</h2><p class="text-secondary mb-4">Hãy khám phá các sản phẩm công nghệ phù hợp với bạn.</p><a class="btn btn-primary px-4" href="{{ route('products.index') }}">Khám phá sản phẩm</a></div></div>
    @endforelse

    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
