@extends('layouts.shop')

@section('title', 'Đặt hàng thành công | Luc Tech Shop')

@section('content')
<div class="container py-5"><div class="row justify-content-center"><div class="col-lg-7"><div class="card border-0 shadow-sm text-center"><div class="card-body p-4 p-md-5"><div class="display-4 mb-3">✓</div><h1 class="h2 fw-bold">Đặt hàng thành công!</h1><p class="text-secondary">Cảm ơn bạn đã mua sắm tại Luc Tech Shop. Chúng tôi sẽ sớm liên hệ để xác nhận đơn hàng.</p><div class="bg-body-tertiary rounded p-3 my-4"><span class="text-secondary d-block small">Mã đơn hàng</span><strong class="fs-5">{{ $order->order_number }}</strong><div class="mt-2">Tổng thanh toán: <strong class="text-primary">{{ number_format($order->total, 0, ',', '.') }} ₫</strong></div></div><div class="d-flex justify-content-center flex-wrap gap-2">@auth<a class="btn btn-outline-primary px-4" href="{{ route('orders.show', $order) }}">Xem đơn hàng</a>@endauth<a class="btn btn-primary px-4" href="{{ route('products.index') }}">Tiếp tục mua sắm</a></div></div></div></div></div>
@endsection
