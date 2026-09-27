@props(['voucher' => null])

@php($voucherKind = old('voucher_kind', $voucher?->is_free_shipping ? 'shipping' : 'discount'))

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label fw-semibold">Loại voucher</label>
        <select id="voucher_kind" name="voucher_kind" class="form-select">
            <option value="discount" @selected($voucherKind === 'discount')>Voucher giảm giá sản phẩm</option>
            <option value="shipping" @selected($voucherKind === 'shipping')>Voucher giảm giá vận chuyển</option>
        </select>
        <div class="form-text">Chọn loại ưu đãi trước khi điền thông tin.</div>
    </div>
    <div class="col-md-4"><label class="form-label fw-semibold">Mã voucher</label><input name="code" value="{{ old('code', $voucher?->code) }}" class="form-control text-uppercase @error('code') is-invalid @enderror" required>@error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="col-md-4"><label class="form-label fw-semibold">Tên chương trình</label><input name="name" value="{{ old('name', $voucher?->name) }}" class="form-control @error('name') is-invalid @enderror" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="col-md-4"><label class="form-label fw-semibold">Cách nhận voucher</label><select name="claim_method" class="form-select"><option value="claim" @selected(old('claim_method', $voucher?->claim_method ?? 'claim') === 'claim')>Hiển thị để bấm nhận</option><option value="code" @selected(old('claim_method', $voucher?->claim_method) === 'code')>Chỉ nhận bằng nhập mã</option></select></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Loại giảm giá</label><select name="type" class="form-select"><option value="percent" @selected(old('type', $voucher?->type) === 'percent')>Phần trăm (%)</option><option value="fixed" @selected(old('type', $voucher?->type) === 'fixed')>Số tiền (₫)</option></select></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Giá trị giảm</label><input name="value" type="number" min="0" value="{{ old('value', $voucher?->value) }}" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label fw-semibold">Đơn tối thiểu</label><input name="min_order_amount" type="number" min="0" value="{{ old('min_order_amount', $voucher?->min_order_amount ?? 0) }}" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Giảm tối đa</label><input name="max_discount" type="number" min="0" value="{{ old('max_discount', $voucher?->max_discount) }}" class="form-control"><div class="form-text">Chỉ áp dụng với loại phần trăm.</div></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Số lượt dùng</label><input name="usage_limit" type="number" min="1" value="{{ old('usage_limit', $voucher?->usage_limit) }}" class="form-control"></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Bắt đầu</label><input name="starts_at" type="datetime-local" value="{{ old('starts_at', $voucher?->starts_at?->format('Y-m-d\TH:i')) }}" class="form-control"></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Kết thúc</label><input name="ends_at" type="datetime-local" value="{{ old('ends_at', $voucher?->ends_at?->format('Y-m-d\TH:i')) }}" class="form-control"></div>
    <div class="col-12"><input type="hidden" name="is_active" value="0"><div class="form-check"><input id="is_active" class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $voucher?->is_active ?? true))><label class="form-check-label" for="is_active">Đang hoạt động</label></div></div>
</div>

<script>
    (() => {
        const kind = document.getElementById('voucher_kind');
        kind.addEventListener('change', () => {});
    })();
</script>
