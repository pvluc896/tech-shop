@extends('layouts.shop')

@section('title', 'Giỏ hàng | Luc Tech Shop')

@section('content')
<div class="container py-4 py-lg-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 mb-lg-5">
        <div>
            <div class="text-primary text-uppercase fw-bold small mb-2">Luc Tech Shop</div>
            <h1 class="display-6 fw-bold mb-1">Giỏ hàng của bạn</h1>
            <p class="text-secondary mb-0">Kiểm tra sản phẩm trước khi thanh toán.</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary rounded-pill px-4">← Tiếp tục mua sắm</a>
    </div>

    @if ($items->isEmpty())
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center py-5 px-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary fs-2 mb-3" style="width: 76px; height: 76px;">🛒</div>
                <h2 class="h3 fw-bold">Giỏ hàng đang trống</h2>
                <p class="text-secondary mb-4">Hãy chọn những sản phẩm công nghệ bạn yêu thích.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-4 py-2">Khám phá sản phẩm</a>
            </div>
        </div>
    @else
        <div class="row g-4 g-lg-5 align-items-start">
            <div class="col-lg-8">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h2 class="h5 fw-bold mb-0">Sản phẩm đã chọn <span class="text-secondary fw-normal">({{ $items->count() }})</span></h2>
                </div>

                <div class="vstack gap-3">
                    @foreach ($items as $item)
                        @php
                            $product = $item['product'];
                            $image = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                            $unitPrice = $product->sale_price ?? $product->price;
                            $initial = mb_strtoupper(mb_substr($product->name, 0, 1));
                        @endphp
                        <article class="card border-0 shadow-sm rounded-4 overflow-hidden" data-unit-price="{{ $unitPrice }}">
                            <div class="card-body p-3 p-md-4">
                                <div class="row g-3 align-items-center">
                                    <div class="col-4 col-sm-3 col-md-2">
                                        <div class="ratio ratio-1x1 rounded-3 overflow-hidden bg-body-tertiary position-relative">
                                            @if ($image)
                                                <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-contain bg-white p-2" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                            @endif
                                            <div class="{{ $image ? 'd-none' : '' }} w-100 h-100 d-flex align-items-center justify-content-center bg-primary-subtle text-primary fs-2 fw-bold">{{ $initial }}</div>
                                        </div>
                                    </div>

                                    <div class="col-8 col-sm-9 col-md-4">
                                        <div class="small text-primary fw-semibold mb-1">{{ $product->brand?->name ?? $product->category?->name ?? 'Luc Tech Shop' }}</div>
                                        <a class="fw-bold text-dark text-decoration-none lh-base" href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                        <div class="text-secondary small mt-2">{{ number_format($unitPrice, 0, ',', '.') }} ₫ / sản phẩm</div>
                                    </div>

                                    <div class="col-7 col-md-3">
                                        <form method="POST" action="{{ route('cart.update', $product) }}" class="d-flex align-items-center gap-2" data-cart-update-form data-current-quantity="{{ $item['quantity'] }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="d-inline-flex align-items-stretch border rounded overflow-hidden bg-white" style="width: 108px;">
                                                <button class="btn btn-light border-0 rounded-0 px-0" style="width: 34px;" type="button" data-quantity-step="-1" aria-label="Giảm số lượng">−</button>
                                                <input class="form-control text-center border-0 rounded-0 px-0 shadow-none" style="width: 40px; flex: 0 0 40px;" type="number" name="quantity" min="1" max="{{ $product->quantity }}" value="{{ $item['quantity'] }}" aria-label="Số lượng">
                                                <button class="btn btn-light border-0 rounded-0 px-0" style="width: 34px;" type="button" data-quantity-step="1" aria-label="Tăng số lượng">+</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-4 col-md-2 text-end"><div class="fw-bold" data-line-total>{{ number_format($unitPrice * $item['quantity'], 0, ',', '.') }} ₫</div></div>

                                    <div class="col-1 text-end">
                                        <form method="POST" action="{{ route('cart.destroy', $product) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-light text-danger rounded-circle" type="submit" aria-label="Xóa {{ $product->name }}">×</button></form>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <aside class="col-lg-4">
                <div id="cart-summary" class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 1.5rem;" data-subtotal="{{ $subtotal }}">
                    <div class="card-body p-4">
                        <h2 class="h4 fw-bold mb-4">Tóm tắt đơn hàng</h2>
                        <div class="d-flex justify-content-between text-secondary mb-3"><span>Tạm tính</span><span data-cart-subtotal>{{ number_format($subtotal, 0, ',', '.') }} ₫</span></div>
                        <div class="d-flex justify-content-between text-secondary mb-2"><span>Phí vận chuyển</span><span>{{ $subtotal >= 5000000 ? 'Miễn phí' : 'Từ 25.000 ₫' }}</span></div>
                        <p class="small text-secondary mb-4">Phí chính xác được tính theo địa chỉ giao hàng ở bước thanh toán.</p>
                        <div class="border-top pt-3 mb-4 d-flex justify-content-between align-items-end"><span class="fw-bold">Tạm tính</span><strong class="fs-4 text-primary" data-cart-subtotal>{{ number_format($subtotal, 0, ',', '.') }} ₫</strong></div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg w-100 rounded-3 fw-bold py-3">Tiến hành thanh toán</a>
                        <div class="d-flex gap-2 align-items-start small text-secondary mt-3"><span class="text-success">✓</span><span>Thông tin đơn hàng và thanh toán của bạn được bảo mật.</span></div>
                    </div>
                </div>
            </aside>
        </div>
    @endif
</div>

<script>
    const formatCurrency = (value) => `${new Intl.NumberFormat('vi-VN').format(value)} ₫`;

    async function updateCartItem(form) {
        const input = form.querySelector('input[name="quantity"]');
        const previousQuantity = Number(form.dataset.currentQuantity);
        const quantity = Math.min(Number(input.max), Math.max(Number(input.min), Number(input.value) || 1));
        const article = form.closest('[data-unit-price]');
        const unitPrice = Number(article.dataset.unitPrice);
        const summary = document.getElementById('cart-summary');

        input.value = quantity;
        if (quantity === previousQuantity) return;

        const previousSubtotal = Number(summary.dataset.subtotal);
        const subtotal = previousSubtotal + (quantity - previousQuantity) * unitPrice;
        form.dataset.currentQuantity = quantity;
        article.querySelector('[data-line-total]').textContent = formatCurrency(unitPrice * quantity);
        document.querySelectorAll('[data-cart-subtotal]').forEach((element) => element.textContent = formatCurrency(subtotal));
        summary.dataset.subtotal = subtotal;
        const requestData = new FormData(form);
        form.querySelectorAll('button, input').forEach((element) => element.disabled = true);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: requestData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!response.ok) throw new Error('Không thể cập nhật giỏ hàng.');
        } catch (error) {
            input.value = previousQuantity;
            form.dataset.currentQuantity = previousQuantity;
            article.querySelector('[data-line-total]').textContent = formatCurrency(unitPrice * previousQuantity);
            document.querySelectorAll('[data-cart-subtotal]').forEach((element) => element.textContent = formatCurrency(previousSubtotal));
            summary.dataset.subtotal = previousSubtotal;
            alert('Không thể cập nhật giỏ hàng. Vui lòng thử lại.');
        } finally {
            form.querySelectorAll('button, input').forEach((element) => element.disabled = false);
        }
    }

    document.querySelectorAll('[data-cart-update-form]').forEach((form) => {
        form.addEventListener('submit', (event) => { event.preventDefault(); updateCartItem(form); });
        form.querySelector('input[name="quantity"]').addEventListener('change', () => updateCartItem(form));
        form.querySelectorAll('[data-quantity-step]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = form.querySelector('input[name="quantity"]');
                input.value = Number(input.value) + Number(button.dataset.quantityStep);
                updateCartItem(form);
            });
        });
    });
</script>
@endsection
