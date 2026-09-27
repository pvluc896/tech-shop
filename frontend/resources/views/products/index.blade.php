@extends('layouts.shop')

@section('title', 'Sản phẩm | Luc Tech Shop')

@section('content')
<div class="container pt-5">
<div id="product-catalog-content">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <div class="text-primary text-uppercase small fw-bold mb-2">Khám phá cửa hàng</div>
            <h1 class="display-6 fw-bold mb-2">Sản phẩm công nghệ</h1>
            <p class="text-muted mb-0">Khám phá các sản phẩm công nghệ tại Luc Tech Shop.</p>
        </div>
        <span class="badge rounded-pill text-bg-light border text-secondary px-3 py-2">{{ $products->total() }} sản phẩm</span>
    </div>

    <!-- Bộ lọc và tìm kiếm -->
    <form method="GET" id="filterForm" class="product-filter-panel card border-0 shadow-sm mb-4">
        <div class="row g-3">
            <!-- Thanh tìm kiếm -->
            <div class="col-12 col-md-6 col-lg-5">
                <div class="input-group filter-search-group">
                    <span class="filter-search-icon"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg></span>
                    <input type="text" name="search" class="form-control" placeholder="Tìm sản phẩm, SKU..." value="{{ request('search') }}" aria-label="Tìm sản phẩm">
                    <button class="btn btn-primary fw-semibold px-4" type="submit">Tìm kiếm</button>
                </div>
            </div>

            <!-- Lọc theo danh mục -->
            <div class="col-12 col-md-4 col-lg-2">
                <div class="filter-select-wrap"><svg class="filter-select-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7.5 12 3l9 4.5L12 12 3 7.5Z"/><path d="M3 12l9 4.5 9-4.5M3 16.5 12 21l9-4.5"/></svg><select name="category" class="form-select product-filter-control" onchange="submitProductFilter()">
                    <option value="">Danh mục</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select></div>
            </div>

            <!-- Lọc theo thương hiệu -->
            <div class="col-12 col-md-4 col-lg-2">
                <div class="filter-select-wrap"><svg class="filter-select-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 13V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h7"/><path d="M8 9h8M8 13h5"/><path d="m17 18 2 2 4-4"/></svg><select name="brand" class="form-select product-filter-control" onchange="submitProductFilter()">
                    <option value="">Thương hiệu</option>
                    @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                    @endforeach
                </select></div>
            </div>

            <!-- Sắp xếp -->
            <div class="col-12 col-md-4 col-lg-3">
                <div class="filter-select-wrap"><svg class="filter-select-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M6 12h12M10 18h4"/></svg><select name="sort" class="form-select product-filter-control" onchange="submitProductFilter()">
                    <option value="">Sắp xếp: Mới nhất</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Sắp xếp: Giá thấp → cao</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Sắp xếp: Giá cao → thấp</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Sắp xếp: Tên (A-Z)</option>
                </select></div>
            </div>

        </div>

        <!-- Hiển thị filter được áp dụng -->
        @if (request()->filled('search') || request()->filled('category') || request()->filled('brand') || request()->filled('sort'))
        <div class="mt-3 pt-3 border-top">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <span class="small text-uppercase text-secondary fw-bold">Bộ lọc đang áp dụng:</span>

                @if (request()->filled('search'))
                <a href="{{ route('products.index', array_merge(request()->except('search'), ['page' => 1])) }}" class="badge rounded-pill text-bg-light border text-dark text-decoration-none p-2">
                    🔍 {{ request('search') }} ×
                </a>
                @endif

                @if (request()->filled('category'))
                <a href="{{ route('products.index', array_merge(request()->except('category'), ['page' => 1])) }}" class="badge rounded-pill text-bg-light border text-dark text-decoration-none p-2">
                    📁 {{ $categories->find(request('category'))?->name ?? 'Danh mục' }} ×
                </a>
                @endif

                @if (request()->filled('brand'))
                <a href="{{ route('products.index', array_merge(request()->except('brand'), ['page' => 1])) }}" class="badge rounded-pill text-bg-light border text-dark text-decoration-none p-2">
                    🏷️ {{ $brands->find(request('brand'))?->name ?? 'Thương hiệu' }} ×
                </a>
                @endif

                @if (request()->filled('sort'))
                <a href="{{ route('products.index', array_merge(request()->except('sort'), ['page' => 1])) }}" class="badge rounded-pill text-bg-light border text-dark text-decoration-none p-2">
                    @switch(request('sort'))
                    @case('price_asc')
                    ⬆️ Giá: Thấp → cao
                    @break
                    @case('price_desc')
                    ⬇️ Giá: Cao → thấp
                    @break
                    @case('name_asc')
                    🔤 Tên (A-Z)
                    @break
                    @endswitch
                    ×
                </a>
                @endif

                <a href="{{ route('products.index') }}" class="btn btn-link btn-sm p-0">Xóa bộ lọc</a>
            </div>
        </div>
        @endif
    </form>

    <div id="product-list-content">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-3">
        @forelse ($products as $product)
        @php
        $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->sortBy('sort_order')->first();
        $displayPrice = $product->sale_price ?? $product->price;
        @endphp
        <div class="col">
            <article class="store-product-card card h-100 shadow-sm overflow-hidden position-relative">
                @auth
                    <form method="POST" action="{{ $product->is_favorited ? route('favorites.destroy', $product) : route('favorites.store', $product) }}" class="favorite-toggle-card position-absolute top-0 end-0 m-2 z-2" data-favorite-toggle>@csrf @if($product->is_favorited) @method('DELETE') @endif<button type="submit" class="btn favorite-button {{ $product->is_favorited ? 'is-favorite' : '' }}" aria-label="{{ $product->is_favorited ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}" title="{{ $product->is_favorited ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}"><svg width="19" height="19" viewBox="0 0 24 24" fill="{{ $product->is_favorited ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.9-8.6a5.5 5.5 0 0 0-.1-7.8Z"/></svg></button></form>
                @endauth
                <div class="bg-body-secondary ratio ratio-4x3">
                    @if ($primaryImage)
                    <img src="{{ asset('storage/' . $primaryImage->image) }}" alt="" class="w-100 h-100 object-fit-contain bg-white p-2" onerror="this.remove();">
                    @else
                    <div class="d-flex align-items-center justify-content-center text-muted">Chưa có ảnh</div>
                    @endif
                </div>
                <div class="card-body d-flex flex-column p-3">
                    <div class="small text-primary fw-semibold mb-2">
                        {{ $product->category?->name }} @if ($product->brand) <span class="text-muted">• {{ $product->brand->name }}</span> @endif
                    </div>
                    <h2 class="h6 card-title fw-bold lh-base">
                        <a href="{{ route('products.show', $product->slug) }}" class="link-dark text-decoration-none">{{ $product->name }}</a>
                    </h2>
                    <p class="fw-bold text-danger fs-5 mb-1">{{ number_format($displayPrice, 0, ',', '.') }} ₫</p>
                    @if ($product->sale_price)
                    <del class="small text-muted">{{ number_format($product->price, 0, ',', '.') }} ₫</del>
                    @endif
                    <div class="mt-auto pt-3 small {{ $product->quantity > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $product->quantity > 0 ? 'Còn hàng' : 'Tạm hết hàng' }}
                    </div>
                    @if ($product->quantity > 0)
                        <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-3" data-cart-add>@csrf<button class="btn btn-primary btn-sm w-100" type="submit">Thêm vào giỏ</button></form>
                    @endif
                </div>
            </article>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info mb-0">Chưa có sản phẩm nào được hiển thị.</div>
        </div>
        @endforelse
    </div>
    <div class="mt-5 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <select name="per_page" form="filterForm" class="form-select form-select-sm w-auto" onchange="changeProductPerPage(this.value)" aria-label="Số sản phẩm mỗi trang"><option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 / trang</option><option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 / trang</option><option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30 / trang</option><option value="all" {{ request('per_page') === 'all' ? 'selected' : '' }}>Tất cả</option></select>
            <div class="d-flex flex-wrap justify-content-end align-items-center gap-3"><span class="text-secondary small">Hiển thị <span class="fw-semibold">{{ $products->firstItem() }}</span> đến <span class="fw-semibold">{{ $products->lastItem() }}</span> trên tổng số <span class="fw-semibold">{{ $products->total() }}</span> sản phẩm</span>{{ $products->links() }}</div>
        </div>
    </div>
    </div>
</div>

<script>
    async function updateProductCatalog(url) {
        const catalog = document.getElementById('product-catalog-content');
        catalog.setAttribute('aria-busy', 'true');

        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) throw new Error('Không thể tải danh sách sản phẩm.');

            const documentData = new DOMParser().parseFromString(await response.text(), 'text/html');
            const newCatalog = documentData.getElementById('product-catalog-content');
            if (!newCatalog) throw new Error('Không tìm thấy danh sách sản phẩm.');

            catalog.replaceWith(newCatalog);
            window.history.pushState({}, '', url);
        } catch (error) {
            catalog.removeAttribute('aria-busy');
            alert('Không thể cập nhật danh sách. Vui lòng thử lại.');
        }
    }

    function getProductFilterParams(form) {
        const params = new URLSearchParams();
        for (const [key, value] of new FormData(form)) {
            if (value) params.append(key, value);
        }

        return params;
    }

    function submitProductFilter() {
        const form = document.getElementById('filterForm');
        const params = getProductFilterParams(form);

        const url = new URL(form.action || window.location.href, window.location.origin);
        url.search = params.toString();
        updateProductCatalog(url);
    }

    function changeProductPerPage(perPage) {
        const form = document.getElementById('filterForm');
        const params = getProductFilterParams(form);
        params.set('per_page', perPage);

        const url = new URL(form.action || window.location.href, window.location.origin);
        url.search = params.toString();
        updateProductCatalog(url);
    }

    document.addEventListener('submit', (event) => {
        if (event.target.id !== 'filterForm') return;
        event.preventDefault();
        submitProductFilter();
    });

    document.addEventListener('click', (event) => {
        const paginationLink = event.target.closest('#product-catalog-content .pagination a');
        if (!paginationLink) return;
        event.preventDefault();
        updateProductCatalog(paginationLink.href);
    });
</script>
@endsection
