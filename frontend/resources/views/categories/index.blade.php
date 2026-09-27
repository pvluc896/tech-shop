@extends('layouts.shop')

@section('title', 'Danh mục | Luc Tech Shop')

@section('content')
    <div class="container pt-5">
    <div class="mb-5">
        <div class="shop-eyebrow mb-2">Mua sắm dễ dàng</div>
        <h1 class="shop-page-title display-6 mb-2">Danh mục sản phẩm</h1>
        <p class="text-muted mb-0">Chọn danh mục phù hợp với nhu cầu của bạn.</p>
    </div>

    <div class="row g-4">
        @forelse ($categories as $category)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="shop-card card h-100 bg-white">
                    <div class="card-body p-4">
                        <div class="text-primary small fw-semibold text-uppercase mb-2">Danh mục</div>
                        <h2 class="h4 fw-bold">{{ $category->name }}</h2>
                        <p class="text-muted mb-0">{{ $category->description ?: 'Xem các sản phẩm thuộc danh mục này.' }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-info mb-0">Chưa có danh mục nào.</div></div>
        @endforelse
    </div>
    </div>
@endsection
