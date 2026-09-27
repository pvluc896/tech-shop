@extends('layouts.shop')

@section('title', $product->name . ' | Luc Tech Shop')

@section('content')
    @php
        $images = $product->images->sortBy('sort_order');
        $primaryImage = $images->firstWhere('is_primary', true) ?? $images->first();
        $displayPrice = $product->sale_price ?? $product->price;
    @endphp

    <div class="container py-5">
        <a href="{{ route('products.index') }}" class="btn btn-link px-0 mb-3 text-decoration-none fw-semibold">← Quay lại sản phẩm</a>

        <div class="row g-4 g-lg-5 align-items-start">
            <div class="col-lg-5">
                <div class="overflow-hidden">
                    <div class="ratio ratio-1x1 bg-body-tertiary rounded-4">
                        @if ($primaryImage)
                            <img id="product-main-image" src="{{ asset('storage/' . $primaryImage->image) }}" alt="" class="w-100 h-100 object-fit-contain bg-white" onerror="this.remove();">
                        @else
                            <div class="d-flex align-items-center justify-content-center text-secondary">Sản phẩm chưa có hình ảnh</div>
                        @endif
                    </div>
                </div>

                @if ($images->count() > 1)
                    <div class="row row-cols-4 g-2 mt-2">
                        @foreach ($images as $image)
                            <div class="col">
                                <button type="button" class="btn p-1 border ratio ratio-1x1 {{ $image->id === $primaryImage?->id ? 'border-primary border-2' : '' }}" data-product-image="{{ asset('storage/' . $image->image) }}" aria-label="Xem ảnh {{ $loop->iteration }} của {{ $product->name }}">
                                    <img src="{{ asset('storage/' . $image->image) }}" alt="" class="w-100 h-100 object-fit-cover" onerror="this.remove();">
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-lg-7">
                <div class="bg-white border rounded-4 shadow-sm p-4 p-lg-5">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                        <div class="text-primary text-uppercase small fw-bold letter-spacing-1">{{ $product->brand?->name ?? 'Luc Tech Shop' }}</div>
                        <span class="badge rounded-pill px-3 py-2 {{ $product->quantity > 0 ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $product->quantity > 0 ? 'Còn hàng' : 'Tạm hết hàng' }}</span>
                    </div>

                    <h1 class="display-6 fw-bold lh-sm mb-3">{{ $product->name }}</h1>
                    <div class="d-flex flex-wrap gap-2 text-secondary small pb-4 border-bottom">
                        <span class="bg-body-tertiary rounded-pill px-3 py-2">Danh mục: {{ $product->category?->name ?? 'Đang cập nhật' }}</span>
                        @if ($product->sku)
                            <span class="bg-body-tertiary rounded-pill px-3 py-2">Mã SP: {{ $product->sku }}</span>
                        @endif
                    </div>

                    <div class="py-4">
                        <div class="text-secondary small mb-1">Giá bán</div>
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <span class="fs-2 fw-bold text-danger">{{ number_format($displayPrice, 0, ',', '.') }} ₫</span>
                            @if ($product->sale_price)
                                <del class="text-secondary">{{ number_format($product->price, 0, ',', '.') }} ₫</del>
                                <span class="badge text-bg-danger rounded-pill">Tiết kiệm {{ number_format($product->price - $product->sale_price, 0, ',', '.') }} ₫</span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex gap-2 mb-4">
                    @if ($product->quantity > 0)
                        <form method="POST" action="{{ route('cart.add', $product) }}" class="flex-grow-1" data-cart-add>
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button class="btn btn-primary btn-lg w-100 py-3 fw-bold" type="submit">
                                Thêm vào giỏ hàng
                            </button>
                        </form>
                    @else
                        <button class="btn btn-secondary btn-lg w-100 py-3" type="button" disabled>Hết hàng</button>
                    @endif
                    @auth
                        <form method="POST" action="{{ $product->is_favorited ? route('favorites.destroy', $product) : route('favorites.store', $product) }}" data-favorite-toggle>@csrf @if($product->is_favorited) @method('DELETE') @endif<button type="submit" class="btn favorite-button favorite-button-large {{ $product->is_favorited ? 'is-favorite' : '' }}" aria-label="{{ $product->is_favorited ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}" title="{{ $product->is_favorited ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}"><svg width="23" height="23" viewBox="0 0 24 24" fill="{{ $product->is_favorited ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.9-8.6a5.5 5.5 0 0 0-.1-7.8Z"/></svg></button></form>
                    @endauth
                    </div>

                    <div class="row g-2 g-sm-3 mb-4">
                        <div class="col-sm-4"><div class="h-100 rounded-3 bg-body-tertiary p-3 small"><strong class="d-block text-dark mb-1">Giao hàng nhanh</strong><span class="text-secondary">Đóng gói cẩn thận</span></div></div>
                        <div class="col-sm-4"><div class="h-100 rounded-3 bg-body-tertiary p-3 small"><strong class="d-block text-dark mb-1">Đổi trả dễ dàng</strong><span class="text-secondary">Hỗ trợ theo chính sách</span></div></div>
                        <div class="col-sm-4"><div class="h-100 rounded-3 bg-body-tertiary p-3 small"><strong class="d-block text-dark mb-1">Tư vấn tận tâm</strong><span class="text-secondary">Liên hệ khi cần hỗ trợ</span></div></div>
                    </div>

                    <div class="border-top pt-4">
                        <h2 class="h5 fw-bold mb-3">Thông tin sản phẩm</h2>
                        <div class="text-secondary lh-lg">{!! nl2br(e($product->description ?: 'Thông tin sản phẩm đang được cập nhật.')) !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const mainImage = document.getElementById('product-main-image');
        document.querySelectorAll('[data-product-image]').forEach((thumbnail) => {
            thumbnail.addEventListener('click', () => {
                if (!mainImage) return;
                mainImage.src = thumbnail.dataset.productImage;
                document.querySelectorAll('[data-product-image]').forEach((item) => item.classList.remove('border-primary', 'border-2'));
                thumbnail.classList.add('border-primary', 'border-2');
            });
        });
    </script>
@endsection
