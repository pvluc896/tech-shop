@extends('layouts.shop')

@section('title', 'Luc Tech Shop | Công nghệ cho cuộc sống')

@section('content')
<div class="home-page">
    <section class="container py-4 py-md-5">
        <div id="homeHeroCarousel" class="carousel slide hero-carousel shadow-lg" data-bs-ride="carousel" data-bs-interval="2000" data-bs-pause="hover">
            <div class="carousel-indicators mb-3 mb-md-4">
                <button type="button" data-bs-target="#homeHeroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Banner 1"></button>
                <button type="button" data-bs-target="#homeHeroCarousel" data-bs-slide-to="1" aria-label="Banner 2"></button>
                <button type="button" data-bs-target="#homeHeroCarousel" data-bs-slide-to="2" aria-label="Banner 3"></button>
            </div>
            <div class="carousel-inner rounded-4">
                <article class="carousel-item active hero-slide">
                    <img src="{{ asset('images/banners/hero-laptop.png') }}" class="hero-slide-image" alt="Laptop, điện thoại và tai nghe công nghệ">
                    <div class="hero-slide-content"><span class="hero-kicker">Công nghệ chính hãng</span><h1>Nâng tầm trải nghiệm số của bạn.</h1><p>Khám phá thiết bị được tuyển chọn kỹ lưỡng, giá minh bạch và hỗ trợ tận tâm.</p><a href="{{ route('products.index') }}" class="btn btn-light btn-lg px-4 fw-semibold">Khám phá sản phẩm</a></div>
                </article>
                <article class="carousel-item hero-slide">
                    <img src="{{ asset('images/banners/hero-mobile.png') }}" class="hero-slide-image" alt="Điện thoại, đồng hồ thông minh và máy ảnh">
                    <div class="hero-slide-content"><span class="hero-kicker">Bắt trọn mọi khoảnh khắc</span><h2>Thiết bị thông minh, phong cách của riêng bạn.</h2><p>Điện thoại, smartwatch và phụ kiện mới nhất sẵn sàng đồng hành mỗi ngày.</p><a href="{{ route('products.index') }}" class="btn btn-light btn-lg px-4 fw-semibold">Xem bộ sưu tập</a></div>
                </article>
                <article class="carousel-item hero-slide">
                    <img src="{{ asset('images/banners/hero-gaming.png') }}" class="hero-slide-image" alt="Bộ máy tính chơi game với màn hình cong và tai nghe">
                    <div class="hero-slide-content"><span class="hero-kicker">Hiệu năng không giới hạn</span><h2>Chinh phục mọi trận đấu.</h2><p>Nâng cấp góc gaming với dàn máy mạnh mẽ và phụ kiện đậm chất riêng.</p><a href="{{ route('products.index') }}" class="btn btn-light btn-lg px-4 fw-semibold">Khám phá gaming</a></div>
                </article>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#homeHeroCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Trước</span></button>
            <button class="carousel-control-next" type="button" data-bs-target="#homeHeroCarousel" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Tiếp theo</span></button>
        </div>
    </section>

    <section class="container pb-5">
        <div class="d-flex justify-content-between align-items-end mb-3"><div><div class="text-primary text-uppercase small fw-bold">Được yêu thích</div><h2 class="h3 fw-bold mb-0">Sản phẩm bán chạy</h2></div><a href="{{ route('products.index') }}" class="btn btn-link text-decoration-none">Xem tất cả</a></div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">@foreach($bestSellers as $product)<div class="col"><x-home-product-card :product="$product" label="Bán chạy" /></div>@endforeach</div>
    </section>

    <section class="container pb-5">
        <div class="d-flex justify-content-between align-items-end mb-3"><div><div class="text-primary text-uppercase small fw-bold">Vừa về hàng</div><h2 class="h3 fw-bold mb-0">Sản phẩm mới</h2></div><a href="{{ route('products.index', ['sort' => 'name_asc']) }}" class="btn btn-link text-decoration-none">Xem tất cả</a></div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">@foreach($newProducts as $product)<div class="col"><x-home-product-card :product="$product" label="Mới" /></div>@endforeach</div>
    </section>

    <section class="container pb-5">
        <div class="d-flex justify-content-between align-items-end mb-3"><div><div class="text-danger text-uppercase small fw-bold">Giá tốt hôm nay</div><h2 class="h3 fw-bold mb-0">Sản phẩm giảm giá</h2></div><a href="{{ route('products.index') }}" class="btn btn-link text-decoration-none">Xem tất cả</a></div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">@foreach($saleProducts as $product)<div class="col"><x-home-product-card :product="$product" label="Giảm giá" /></div>@endforeach</div>
    </section>

    <section class="container pb-5">
        <div class="d-flex justify-content-between align-items-end mb-3"><div><div class="text-primary text-uppercase small fw-bold">Hiệu năng & sáng tạo</div><h2 class="h3 fw-bold mb-0">Laptop nổi bật</h2></div><a href="{{ route('products.index') }}" class="btn btn-link text-decoration-none">Xem tất cả</a></div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">@foreach($featuredLaptops as $product)<div class="col"><x-home-product-card :product="$product" label="Laptop" /></div>@endforeach</div>
    </section>

    <section class="container pb-5">
        <div class="d-flex justify-content-between align-items-end mb-3"><div><div class="text-primary text-uppercase small fw-bold">Hoàn thiện góc làm việc</div><h2 class="h3 fw-bold mb-0">Phụ kiện tiện ích</h2></div><a href="{{ route('products.index') }}" class="btn btn-link text-decoration-none">Xem tất cả</a></div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">@foreach($accessories as $product)<div class="col"><x-home-product-card :product="$product" label="Phụ kiện" /></div>@endforeach</div>
    </section>

    <section class="container pb-5"><div class="row g-3"><div class="col-md-4"><div class="card card-body border-0 shadow-sm h-100"><div class="fs-3 mb-2">✓</div><h2 class="h5 fw-bold">Hàng chính hãng</h2><p class="text-secondary mb-0">Nguồn gốc rõ ràng, bảo hành minh bạch.</p></div></div><div class="col-md-4"><div class="card card-body border-0 shadow-sm h-100"><div class="fs-3 mb-2">↗</div><h2 class="h5 fw-bold">Giao hàng tiện lợi</h2><p class="text-secondary mb-0">Giao nhanh, an toàn trên toàn quốc.</p></div></div><div class="col-md-4"><div class="card card-body border-0 shadow-sm h-100"><div class="fs-3 mb-2">◌</div><h2 class="h5 fw-bold">Hỗ trợ tận tâm</h2><p class="text-secondary mb-0">Đội ngũ luôn sẵn sàng đồng hành cùng bạn.</p></div></div></div></section>
</div>
@endsection
