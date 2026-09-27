@props(['product', 'label' => null])

@php
    $image = $product->images->firstWhere('is_primary', true) ?? $product->images->sortBy('sort_order')->first();
    $price = $product->sale_price ?? $product->price;
@endphp

<article class="store-product-card card border-0 shadow-sm h-100 overflow-hidden position-relative">
    @auth
        <form method="POST" action="{{ $product->is_favorited ? route('favorites.destroy', $product) : route('favorites.store', $product) }}" class="favorite-toggle-card position-absolute top-0 end-0 m-2 z-2" data-favorite-toggle>@csrf @if($product->is_favorited) @method('DELETE') @endif<button type="submit" class="btn favorite-button {{ $product->is_favorited ? 'is-favorite' : '' }}" aria-label="{{ $product->is_favorited ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}" title="{{ $product->is_favorited ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}"><svg width="19" height="19" viewBox="0 0 24 24" fill="{{ $product->is_favorited ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.9-8.6a5.5 5.5 0 0 0-.1-7.8Z"/></svg></button></form>
    @endauth
    <a href="{{ route('products.show', $product->slug) }}" class="ratio ratio-1x1 bg-body-tertiary text-decoration-none position-relative">
        @if ($image)<img src="{{ asset('storage/' . $image->image) }}" alt="" class="w-100 h-100 object-fit-contain bg-white p-2" onerror="this.remove();">@else<div class="d-flex align-items-center justify-content-center text-secondary small">Chưa có ảnh</div>@endif
    </a>
    <div class="card-body p-3 d-flex flex-column"><div class="small text-primary fw-semibold mb-1">{{ $product->brand?->name ?? 'Luc Tech' }}</div><h3 class="h6 fw-bold lh-base"><a href="{{ route('products.show', $product->slug) }}" class="link-dark text-decoration-none">{{ $product->name }}</a></h3><div class="mt-auto"><div class="fw-bold text-danger">{{ number_format($price, 0, ',', '.') }} ₫</div>@if($product->sale_price)<del class="small text-secondary">{{ number_format($product->price, 0, ',', '.') }} ₫</del>@endif</div></div>
    @if($product->quantity > 0)<form method="POST" action="{{ route('cart.add', $product) }}" class="px-3 pb-3" data-cart-add>@csrf<button class="btn btn-outline-primary btn-sm w-100" type="submit">Thêm vào giỏ</button></form>@endif
</article>
