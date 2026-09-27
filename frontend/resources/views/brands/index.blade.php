@extends('layouts.shop')

@section('title', 'Thương hiệu | Luc Tech Shop')

@section('content')
    <div class="container pt-5">
    <div class="mb-5">
        <div class="shop-eyebrow mb-2">Đối tác đáng tin cậy</div>
        <h1 class="shop-page-title display-6 mb-2">Thương hiệu</h1>
        <p class="text-muted mb-0">Khám phá sản phẩm từ các thương hiệu đang có tại cửa hàng.</p>
    </div>

    <div class="row g-4">
        @forelse ($brands as $brand)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="shop-card card h-100 bg-white">
                    <div class="card-body p-4">
                        <div class="text-primary small fw-semibold text-uppercase mb-2">Thương hiệu</div>
                        <h2 class="h4 fw-bold">{{ $brand->name }}</h2>
                        <p class="text-muted mb-0">{{ $brand->description ?: 'Xem các sản phẩm của thương hiệu này.' }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-info mb-0">Chưa có thương hiệu nào.</div></div>
        @endforelse
    </div>
    </div>
@endsection
