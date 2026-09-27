@extends('layouts.shop')

@section('title', 'Thanh toán | Luc Tech Shop')

@section('content')
<div class="container py-5">
    <div class="mb-4"><div class="text-primary text-uppercase small fw-bold">Thanh toán an toàn</div><h1 class="h2 fw-bold mb-1">Hoàn tất đơn hàng</h1><p class="text-secondary mb-0">Vui lòng điền thông tin giao hàng bên dưới.</p></div>
    <form id="checkout-form" method="POST" action="{{ route('checkout.store') }}">@csrf
        <div class="row g-4"><div class="col-lg-7"><div class="card border-0 shadow-sm"><div class="card-body p-4 p-md-5"><h2 class="h5 fw-bold mb-4">Thông tin nhận hàng</h2>
            @error('cart')<div class="alert alert-danger">{{ $message }}</div>@enderror
            <div class="mb-3"><label class="form-label fw-semibold" for="customer_name">Họ và tên</label><input id="customer_name" name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" class="form-control @error('customer_name') is-invalid @enderror" required>@error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="row"><div class="col-md-6 mb-3"><label class="form-label fw-semibold" for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email', auth()->user()?->email) }}" class="form-control @error('email') is-invalid @enderror" required>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div><div class="col-md-6 mb-3"><label class="form-label fw-semibold" for="phone">Số điện thoại</label><input id="phone" name="phone" value="{{ old('phone', auth()->user()?->phone) }}" class="form-control @error('phone') is-invalid @enderror" placeholder="0901234567" required>@error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror</div></div>
            <div class="mb-3"><label class="form-label fw-semibold">Địa chỉ nhận hàng</label><div class="row g-3"><div class="col-md-6"><select id="province" name="province" class="form-select @error('province') is-invalid @enderror" data-selected="{{ old('province', auth()->user()?->province) }}" required><option value="">Chọn Tỉnh / Thành phố</option></select>@error('province')<div class="invalid-feedback">{{ $message }}</div>@enderror</div><div class="col-md-6"><select id="district" name="district" class="form-select @error('district') is-invalid @enderror" data-selected="{{ old('district', auth()->user()?->district) }}" disabled required><option value="">Chọn Quận / Huyện</option></select>@error('district')<div class="invalid-feedback">{{ $message }}</div>@enderror</div><div class="col-md-6"><select id="ward" name="ward" class="form-select @error('ward') is-invalid @enderror" data-selected="{{ old('ward', auth()->user()?->ward) }}" disabled required><option value="">Chọn Phường / Xã</option></select>@error('ward')<div class="invalid-feedback">{{ $message }}</div>@enderror</div><div class="col-md-6"><input id="address_detail" name="address_detail" value="{{ old('address_detail', auth()->user()?->address_detail) }}" class="form-control @error('address_detail') is-invalid @enderror" placeholder="Số nhà, tên đường" required>@error('address_detail')<div class="invalid-feedback">{{ $message }}</div>@enderror</div></div><div class="form-text">Chọn đầy đủ khu vực để giao hàng nhanh và chính xác hơn.</div></div>
            <div class="mb-3"><label class="form-label fw-semibold">Phương thức thanh toán</label><div class="vstack gap-2"><label class="border rounded p-3 d-flex align-items-center gap-2"><input class="form-check-input m-0" type="radio" name="payment_method" value="cod" @checked(old('payment_method', 'cod') === 'cod')> <span><strong>Thanh toán khi nhận hàng</strong><small class="d-block text-secondary">Thanh toán bằng tiền mặt cho đơn vị giao hàng.</small></span></label><label class="border rounded p-3 d-flex align-items-center gap-2"><input class="form-check-input m-0" type="radio" name="payment_method" value="bank_transfer" @checked(old('payment_method') === 'bank_transfer')> <span><strong>Thanh toán bằng mã QR</strong><small class="d-block text-secondary">Quét mã VietQR để chuyển khoản ngay.</small></span></label></div></div>
            <div class="row g-3 mb-3"><div class="col-md-6"><label class="form-label fw-semibold" for="voucher_code">Voucher giảm giá</label><div class="input-group"><input id="voucher_code" name="voucher_code" value="{{ old('voucher_code') }}" class="form-control text-uppercase @error('voucher_code') is-invalid @enderror" placeholder="Chọn mã giảm giá" autocomplete="off" readonly data-bs-toggle="modal" data-bs-target="#voucherPickerModal"><button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#voucherPickerModal">Chọn</button></div>@error('voucher_code')<div class="invalid-feedback">{{ $message }}</div>@enderror</div><div class="col-md-6"><label class="form-label fw-semibold" for="shipping_voucher_code">Voucher vận chuyển</label><div class="input-group"><input id="shipping_voucher_code" name="shipping_voucher_code" value="{{ old('shipping_voucher_code') }}" class="form-control text-uppercase @error('shipping_voucher_code') is-invalid @enderror" placeholder="Chọn mã freeship" autocomplete="off" readonly data-bs-toggle="modal" data-bs-target="#voucherPickerModal"><button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#voucherPickerModal">Chọn</button></div>@error('shipping_voucher_code')<div class="invalid-feedback">{{ $message }}</div>@enderror</div></div>
            <div><label class="form-label fw-semibold" for="note">Ghi chú</label><textarea id="note" name="note" rows="2" class="form-control" placeholder="Ghi chú cho đơn hàng (không bắt buộc)">{{ old('note') }}</textarea></div>
        </div></div></div>
        <div class="col-lg-5"><div class="card border-0 shadow-sm"><div class="card-body p-4"><h2 class="h5 fw-bold mb-4">Đơn hàng của bạn</h2>@foreach($items as $item)<div class="d-flex justify-content-between gap-3 mb-3"><span>{{ $item['product']->name }} <span class="text-secondary">× {{ $item['quantity'] }}</span></span><strong class="text-nowrap">{{ number_format(($item['product']->sale_price ?? $item['product']->price) * $item['quantity'], 0, ',', '.') }} ₫</strong></div>@endforeach<div class="border-top pt-3 mt-3" data-order-summary data-subtotal="{{ $subtotal }}"><div class="d-flex justify-content-between mb-2"><span>Tạm tính</span><span>{{ number_format($subtotal, 0, ',', '.') }} ₫</span></div><div class="d-flex justify-content-between mb-2"><span>Vận chuyển</span><span id="shipping-fee">{{ $shippingFee === 0 ? ($subtotal >= 5000000 ? 'Miễn phí' : 'Chọn địa chỉ') : number_format($shippingFee, 0, ',', '.').' ₫' }}</span></div><div id="voucher-discount" class="voucher-discount d-none mb-3"><div class="d-flex justify-content-between align-items-center"><span class="d-flex align-items-center gap-2"><span class="voucher-discount-icon">%</span><span>Giảm giá <small id="voucher-applied-code" class="d-block fw-semibold"></small></span></span><strong id="voucher-discount-amount"></strong></div><small id="voucher-discount-name" class="d-block mt-1"></small></div><div id="shipping-voucher-discount" class="voucher-discount d-none mb-3"><div class="d-flex justify-content-between align-items-center"><span class="d-flex align-items-center gap-2"><span class="voucher-discount-icon">✓</span><span>Ưu đãi vận chuyển <small id="shipping-voucher-applied-code" class="d-block fw-semibold"></small></span></span><strong>Miễn phí</strong></div><small id="shipping-voucher-discount-name" class="d-block mt-1"></small></div><div id="voucher-feedback" class="small d-none mb-3" aria-live="polite"></div><div class="d-flex justify-content-between border-top pt-3"><strong>Tổng cộng</strong><strong id="order-total" class="fs-5 text-primary">{{ number_format($subtotal + $shippingFee, 0, ',', '.') }} ₫</strong></div><p class="small text-secondary mb-0 mt-3">Miễn phí vận chuyển cho đơn từ 5.000.000 ₫.</p></div><button class="btn btn-primary w-100 py-2 fw-semibold mt-4" type="submit">Đặt hàng</button><p class="small text-secondary text-center mt-3 mb-0">Bằng việc đặt hàng, bạn đồng ý với điều khoản mua hàng của Luc Tech Shop.</p></div></div></div></div>
    </form>
</div>
<div class="modal fade" id="bank-qr-modal" tabindex="-1" aria-labelledby="bank-qr-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content border-0 shadow-lg overflow-hidden"><div class="modal-header border-0 bg-primary text-white px-4 py-3"><div><span class="badge bg-white text-primary rounded-pill">VietQR · MB Bank</span><h2 id="bank-qr-modal-label" class="modal-title h5 fw-bold mt-2 mb-0">Thanh toán đơn hàng</h2></div></div><div class="modal-body p-4"><div class="row align-items-center g-4"><div class="col-md-6 text-center"><img id="bank-qr-image" class="d-block mx-auto" width="320" height="320" alt="Mã VietQR thanh toán cho PHAN VAN LUC"></div><div class="col-md-6"><div class="rounded-4 bg-primary-subtle p-3 mb-3"><span class="small text-secondary d-block">Tổng thanh toán</span><strong id="bank-qr-amount" class="fs-3 text-primary"></strong></div><div class="border rounded-4 p-3"><div class="d-flex justify-content-between gap-3 small"><span class="text-secondary">Ngân hàng</span><strong>MB Bank</strong></div><div class="d-flex justify-content-between gap-3 small border-top pt-2 mt-2"><span class="text-secondary">Số tài khoản</span><strong>0787899863</strong></div><div class="d-flex justify-content-between gap-3 small border-top pt-2 mt-2"><span class="text-secondary">Chủ tài khoản</span><strong>PHAN VAN LUC</strong></div><div class="d-flex justify-content-between gap-3 small border-top pt-2 mt-2"><span class="text-secondary">Nội dung chuyển khoản</span><strong>{{ $orderNumber }}</strong></div></div><p class="small text-secondary mb-0 mt-3">Đang chờ SePay xác nhận thanh toán tự động…</p></div></div><div class="d-flex justify-content-center mt-4"><button id="cancel-bank-transfer" type="button" class="btn btn-outline-secondary px-4">Quay lại</button></div></div></div></div>
</div>
<button id="bank-qr-trigger" class="d-none" type="button" data-bs-toggle="modal" data-bs-target="#bank-qr-modal" aria-hidden="true"></button>
<div class="modal fade" id="voucherPickerModal" tabindex="-1" aria-labelledby="voucherPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered"><div class="modal-content border-0 shadow"><div class="modal-header"><div><h2 id="voucherPickerModalLabel" class="modal-title fs-5 fw-bold">Chọn mã giảm giá</h2><p class="small text-secondary mb-0">Chọn voucher phù hợp nhất cho đơn hàng của bạn.</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button></div><div class="modal-body p-4">
        @forelse ($availableVouchers as $voucher)
            @php($discountLabel = $voucher->type === 'percent' ? 'Giảm '.$voucher->value.'%'.($voucher->max_discount ? ' · tối đa '.number_format($voucher->max_discount, 0, ',', '.').' ₫' : '') : 'Giảm '.number_format($voucher->value, 0, ',', '.').' ₫')
            @php($canApply = $subtotal >= $voucher->min_order_amount)
            <article class="voucher-choice {{ $canApply ? '' : 'voucher-choice-disabled' }} mb-3"><div class="voucher-choice-stamp">%</div><div class="flex-grow-1"><div class="d-flex flex-wrap justify-content-between align-items-start gap-2"><div><strong class="text-primary">{{ $voucher->code }}</strong><div class="fw-semibold mt-1">{{ $voucher->name }}</div></div><span class="badge {{ $canApply ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $canApply ? 'Sẵn sàng dùng' : 'Chưa đủ điều kiện' }}</span></div><p class="mb-1 mt-2 text-success fw-semibold">{{ $discountLabel }}</p><small class="text-secondary">Đơn tối thiểu {{ number_format($voucher->min_order_amount, 0, ',', '.') }} ₫@if($voucher->ends_at) · HSD {{ $voucher->ends_at->format('d/m/Y') }}@endif</small></div><button class="btn btn-primary align-self-center text-nowrap" type="button" data-voucher-code="{{ $voucher->code }}" data-bs-dismiss="modal" @disabled(! $canApply)>{{ $canApply ? 'Dùng mã này' : 'Chưa đủ đơn' }}</button></article>
        @empty
            <div class="text-center py-4"><div class="fs-1">🎟️</div><h3 class="h6 fw-bold mt-3">Chưa có voucher khả dụng</h3><p class="text-secondary mb-0">Hãy vào Kho voucher để nhận thêm ưu đãi.</p></div>
        @endforelse
    </div><div class="modal-footer"><a href="{{ route('vouchers.index') }}" class="btn btn-outline-primary">Đến kho voucher</a><button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button></div></div></div>
</div>
<script>
    const addressApi = 'https://provinces.open-api.vn/api';
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    const voucherInput = document.getElementById('voucher_code');
    const shippingVoucherInput = document.getElementById('shipping_voucher_code');
    const voucherDiscount = document.getElementById('voucher-discount');
    const shippingVoucherDiscount = document.getElementById('shipping-voucher-discount');
    const voucherFeedback = document.getElementById('voucher-feedback');
    let appliedDiscount = 0;
    let freeShipping = false;
    let shippingVoucherConfig = null;
    let voucherRequestId = 0;
    let voucherTimer;

    let activeVoucherInput = voucherInput;
    document.querySelectorAll('#voucher_code, #voucher_code + button').forEach((element) => element.addEventListener('click', () => activeVoucherInput = voucherInput));
    document.querySelectorAll('#shipping_voucher_code, #shipping_voucher_code + button').forEach((element) => element.addEventListener('click', () => activeVoucherInput = shippingVoucherInput));
    document.querySelectorAll('[data-voucher-code]').forEach((button) => button.addEventListener('click', () => {
        activeVoucherInput.value = button.dataset.voucherCode;
        activeVoucherInput.dispatchEvent(new Event('input'));
    }));
    const orderSummary = document.querySelector('[data-order-summary]');
    const currency = new Intl.NumberFormat('vi-VN');
    const checkoutForm = document.getElementById('checkout-form');
    const bankQrImage = document.getElementById('bank-qr-image');
    const bankQrAmount = document.getElementById('bank-qr-amount');
    const bankQrTrigger = document.getElementById('bank-qr-trigger');
    const cancelBankTransfer = document.getElementById('cancel-bank-transfer');
    let transferReference = @json($orderNumber);
    let qrTotal = null;
    let paymentStatusUrl = null;
    let paymentCancelUrl = null;
    let paymentSuccessUrl = null;
    let paymentPollingTimer = null;

    const rawShippingFee = () => {
        const subtotal = Number(orderSummary.dataset.subtotal);
        return subtotal >= 5000000 ? 0 : (provinceSelect.value === 'Thành phố Hồ Chí Minh' ? 25000 : (provinceSelect.value ? 40000 : 0));
    };
    const shippingDiscountFor = (fee) => {
        if (!shippingVoucherConfig || fee === 0) return 0;
        if (Number(shippingVoucherConfig.value) <= 0) return fee;
        const discount = shippingVoucherConfig.type === 'percent' ? fee * (Number(shippingVoucherConfig.value) / 100) : Number(shippingVoucherConfig.value);
        return Math.min(discount, shippingVoucherConfig.maxDiscount ? Number(shippingVoucherConfig.maxDiscount) : discount, fee);
    };
    function updateBankQr() {
        const subtotal = Number(orderSummary.dataset.subtotal);
        const fee = Math.max(0, rawShippingFee() - shippingDiscountFor(rawShippingFee()));
        const total = qrTotal ?? Math.max(0, subtotal + fee - appliedDiscount);
        bankQrAmount.textContent = `${currency.format(total)} ₫`;
        bankQrImage.src = `https://img.vietqr.io/image/MB-0787899863-compact2.png?amount=${Math.round(total)}&addInfo=${encodeURIComponent(transferReference)}&accountName=${encodeURIComponent('PHAN VAN LUC')}`;
    }
    const updateShippingFee = () => {
        const subtotal = Number(orderSummary.dataset.subtotal);
        const baseFee = rawShippingFee();
        const shippingDiscount = shippingDiscountFor(baseFee);
        const fee = Math.max(0, baseFee - shippingDiscount);
        document.getElementById('shipping-fee').textContent = fee === 0 ? (shippingVoucherConfig || subtotal >= 5000000 ? 'Miễn phí' : 'Chọn địa chỉ') : `${currency.format(fee)} ₫`;
        document.getElementById('order-total').textContent = `${currency.format(Math.max(0, subtotal + fee - appliedDiscount))} ₫`;
        updateBankQr();
    };

    const checkSePayPayment = async () => {
        if (!paymentStatusUrl) return false;

        const response = await fetch(paymentStatusUrl, { headers: { Accept: 'application/json' } });
        const result = await response.json();
        if (result.paid) {
            window.clearInterval(paymentPollingTimer);
            window.location.assign(paymentSuccessUrl);
            return true;
        }

        return false;
    };

    checkoutForm.addEventListener('submit', async (event) => {
        const isQrPayment = document.querySelector('input[name="payment_method"]:checked')?.value === 'bank_transfer';
        if (!isQrPayment) return;

        event.preventDefault();
        if (paymentStatusUrl) {
            bankQrTrigger.click();
            return;
        }

        const submitButton = checkoutForm.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        try {
            const response = await fetch(checkoutForm.action, {
                method: 'POST',
                body: new FormData(checkoutForm),
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.errors ? Object.values(result.errors).flat()[0] : 'Không thể tạo đơn hàng.');

            transferReference = result.orderNumber;
            qrTotal = Number(result.total);
            paymentStatusUrl = result.statusUrl;
            paymentCancelUrl = result.cancelUrl;
            paymentSuccessUrl = result.successUrl;
            updateBankQr();
            bankQrTrigger.click();
            checkSePayPayment();
            paymentPollingTimer = window.setInterval(checkSePayPayment, 4000);
        } catch (error) {
            alert(error.message || 'Không thể tạo đơn hàng. Vui lòng thử lại.');
        } finally {
            submitButton.disabled = false;
        }
    });

    cancelBankTransfer.addEventListener('click', async () => {
        if (!paymentCancelUrl) return;

        cancelBankTransfer.disabled = true;
        try {
            const response = await fetch(paymentCancelUrl, {
                method: 'POST',
                body: new URLSearchParams({ _token: '{{ csrf_token() }}' }),
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!response.ok) throw new Error('Không thể khôi phục giỏ hàng.');
            window.clearInterval(paymentPollingTimer);
            window.location.reload();
        } catch (error) {
            alert(error.message || 'Không thể khôi phục giỏ hàng.');
            cancelBankTransfer.disabled = false;
        }
    });

    const resetVoucherPreview = () => {
        appliedDiscount = 0;
        freeShipping = false;
        shippingVoucherConfig = null;
        voucherDiscount.classList.add('d-none');
        shippingVoucherDiscount.classList.add('d-none');
        voucherFeedback.className = 'small d-none mb-3';
        voucherFeedback.textContent = '';
        voucherInput.classList.remove('is-valid', 'is-invalid');
        updateShippingFee();
    };

    const previewVoucher = async () => {
        const code = voucherInput.value.trim().toUpperCase();
        const requestId = ++voucherRequestId;
        if (!code) return resetVoucherPreview();

        voucherInput.value = code;
        voucherInput.classList.remove('is-valid', 'is-invalid');
        voucherFeedback.className = 'small text-secondary mb-3';
        voucherFeedback.textContent = 'Đang kiểm tra mã giảm giá...';

        try {
            const formData = new FormData();
            formData.append('voucher_code', code);
            formData.append('_token', '{{ csrf_token() }}');
            const response = await fetch('{{ route('checkout.voucher-preview') }}', {
                method: 'POST',
                body: formData,
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            const result = await response.json();
            if (requestId !== voucherRequestId) return;
            if (!response.ok) throw new Error(result.errors?.voucher_code?.[0] || result.message || 'Không thể áp dụng mã này.');
            if (result.freeShipping) throw new Error('Vui lòng chọn mã này ở ô voucher vận chuyển.');

            appliedDiscount = Number(result.discount);
            freeShipping = Boolean(result.freeShipping);
            document.getElementById('voucher-applied-code').textContent = result.code;
            document.getElementById('voucher-discount-name').textContent = result.name;
            document.getElementById('voucher-discount-amount').textContent = freeShipping ? 'Miễn phí vận chuyển' : `−${currency.format(appliedDiscount)} ₫`;
            voucherDiscount.classList.remove('d-none');
            voucherFeedback.className = 'small text-success mb-3';
            voucherFeedback.textContent = 'Áp dụng mã giảm giá thành công.';
            voucherInput.classList.add('is-valid');
            updateShippingFee();
        } catch (error) {
            appliedDiscount = 0;
            voucherDiscount.classList.add('d-none');
            voucherFeedback.className = 'small text-danger mb-3';
            voucherFeedback.textContent = error.message;
            voucherInput.classList.add('is-invalid');
            updateShippingFee();
        }
    };

    voucherInput.addEventListener('input', () => {
        window.clearTimeout(voucherTimer);
        voucherTimer = window.setTimeout(previewVoucher, 450);
    });

    shippingVoucherInput.addEventListener('input', async () => {
        const code = shippingVoucherInput.value.trim().toUpperCase();
        freeShipping = false;
        shippingVoucherConfig = null;
        if (!code) {
            shippingVoucherDiscount.classList.add('d-none');
            return updateShippingFee();
        }

        try {
            const formData = new FormData();
            formData.append('voucher_code', code);
            formData.append('_token', '{{ csrf_token() }}');
            const response = await fetch('{{ route('checkout.voucher-preview') }}', {
                method: 'POST', body: formData, headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            const result = await response.json();
            if (!response.ok || !result.freeShipping) throw new Error('Vui lòng chọn voucher vận chuyển hợp lệ.');
            shippingVoucherConfig = result;
            shippingVoucherInput.classList.add('is-valid');
            document.getElementById('shipping-voucher-applied-code').textContent = result.code;
            document.getElementById('shipping-voucher-discount-name').textContent = result.name;
            const baseFee = rawShippingFee();
            document.querySelector('#shipping-voucher-discount strong').textContent = `−${currency.format(shippingDiscountFor(baseFee))} ₫`;
            shippingVoucherDiscount.classList.remove('d-none');
            updateShippingFee();
        } catch (error) {
            shippingVoucherInput.value = '';
            shippingVoucherInput.classList.remove('is-valid');
            shippingVoucherConfig = null;
            shippingVoucherDiscount.classList.add('d-none');
            alert(error.message || 'Voucher vận chuyển không hợp lệ.');
            updateShippingFee();
        }
    });

    if (voucherInput.value.trim()) previewVoucher();

    const setOptions = (select, items, placeholder, selected = '') => {
        select.innerHTML = `<option value="">${placeholder}</option>` + items.map((item) => `<option value="${item.name}" data-code="${item.code}">${item.name}</option>`).join('');
        select.disabled = false;
        if (selected) select.value = selected;
    };

    const selectedCode = (select) => select.options[select.selectedIndex]?.dataset.code;

    fetch(`${addressApi}/p/`).then((response) => response.json()).then((provinces) => {
        setOptions(provinceSelect, provinces, 'Chọn Tỉnh / Thành phố', provinceSelect.dataset.selected);
        updateShippingFee();
        if (provinceSelect.value) provinceSelect.dispatchEvent(new Event('change'));
    }).catch(() => provinceSelect.innerHTML = '<option value="">Không tải được danh sách khu vực</option>');

    provinceSelect.addEventListener('change', () => {
        updateShippingFee();
        districtSelect.disabled = true; wardSelect.disabled = true;
        districtSelect.innerHTML = '<option value="">Đang tải Quận / Huyện...</option>';
        wardSelect.innerHTML = '<option value="">Chọn Phường / Xã</option>';
        const code = selectedCode(provinceSelect); if (!code) return;
        fetch(`${addressApi}/p/${code}?depth=2`).then((response) => response.json()).then((province) => {
            setOptions(districtSelect, province.districts, 'Chọn Quận / Huyện', districtSelect.dataset.selected);
            districtSelect.dataset.selected = '';
            if (districtSelect.value) districtSelect.dispatchEvent(new Event('change'));
        });
    });

    districtSelect.addEventListener('change', () => {
        wardSelect.disabled = true; wardSelect.innerHTML = '<option value="">Đang tải Phường / Xã...</option>';
        const code = selectedCode(districtSelect); if (!code) return;
        fetch(`${addressApi}/d/${code}?depth=2`).then((response) => response.json()).then((district) => {
            setOptions(wardSelect, district.wards, 'Chọn Phường / Xã', wardSelect.dataset.selected);
            wardSelect.dataset.selected = '';
        });
    });
</script>
@endsection
