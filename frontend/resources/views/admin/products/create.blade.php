@extends('layouts.admin')
@section('title', 'Thêm sản phẩm')
@section('page-title', 'Thêm sản phẩm')
@section('content')
<div class="card shadow admin-form-page">
    <div class="card-header">
        <h1 class="h3 mb-0">Thêm sản phẩm</h1>
    </div>
    <div class="card-body">
        <form
            action="/admin/products"
            method="POST"
            enctype="multipart/form-data">
            @csrf
            <!-- Tên sản phẩm -->
            <div class="mb-3">
                <label class="form-label">
                    Tên sản phẩm:
                </label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror">
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <!-- Slug -->
            <div class="mb-3">
                <label class="form-label">
                    Slug:
                </label>
                <input
                    type="text"
                    name="slug"
                    value="{{ old('slug') }}"
                    class="form-control @error('slug') is-invalid @enderror">
                @error('slug')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <!-- SKU -->
            <div class="mb-3">
                <label class="form-label">
                    SKU:
                </label>
                <input
                    type="text"
                    name="sku"
                    value="{{ old('sku') }}"
                    class="form-control @error('sku') is-invalid @enderror">
                @error('sku')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <!-- Danh mục -->
            <div class="mb-3">
                <label class="form-label">
                    Danh mục:
                </label>
                <select
                    name="category_id"
                    class="form-select @error('category_id') is-invalid @enderror">
                    <option value="">-- Chọn danh mục --</option>
                    @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
                @error('category_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <!-- Thương hiệu -->
            <div class="mb-3">
                <label class="form-label">
                    Thương hiệu:
                </label>
                <select
                    name="brand_id"
                    class="form-select @error('brand_id') is-invalid @enderror">
                    <option value="">-- Chọn thương hiệu --</option>
                    @foreach ($brands as $brand)
                    <option
                        value="{{ $brand->id }}"
                        {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                    @endforeach
                </select>
                @error('brand_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <!-- Mô tả -->
            <div class="mb-3">
                <label class="form-label">
                    Mô tả:
                </label>
                <textarea
                    name="description"
                    rows="4"
                    class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <!-- Giá -->
            <div class="mb-3">
                <label class="form-label">
                    Giá:
                </label>
                <input
                    type="number"
                    name="price"
                    value="{{ old('price') }}"
                    min="0"
                    step="0.01"
                    class="form-control @error('price') is-invalid @enderror">
                @error('price')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <!-- Giá khuyến mãi -->
            <div class="mb-3">
                <label class="form-label">
                    Giá khuyến mãi:
                </label>
                <input
                    type="number"
                    name="sale_price"
                    value="{{ old('sale_price') }}"
                    min="0"
                    step="0.01"
                    class="form-control @error('sale_price') is-invalid @enderror">
                @error('sale_price')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <!-- Số lượng -->
            <div class="mb-3">
                <label class="form-label">
                    Số lượng:
                </label>
                <input
                    type="number"
                    name="quantity"
                    value="{{ old('quantity', 0) }}"
                    min="0"
                    class="form-control @error('quantity') is-invalid @enderror">
                @error('quantity')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <!-- Trạng thái -->
            <div class="mb-3">
                <label class="form-label">
                    Trạng thái:
                </label>
                <select
                    name="status"
                    class="form-select @error('status') is-invalid @enderror">
                    <option
                        value="active"
                        {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option
                        value="inactive"
                        {{ old('status') == 'inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
                @error('status')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <!-- Hình ảnh -->
            <hr class="my-4">
            <h4 class="mb-3">
                Hình ảnh sản phẩm
            </h4>
            <div class="mb-3">
                <label class="form-label">
                    Chọn hình ảnh:
                </label>
                <input
                    type="file"
                    name="images[]"
                    id="images"
                    accept="image/*"
                    multiple
                    class="form-control @error('images') is-invalid @enderror">
                @error('images')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
                @error('images.*')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
                <div class="form-text">
                    Có thể chọn nhiều ảnh.
                    Ảnh đầu tiên sẽ là ảnh chính.
                </div>
            </div>
            <!-- Preview -->
            <div
                id="preview-container"
                class="row mb-4">
            </div>
            <!-- Nút -->
            <button
                type="submit"
                class="btn btn-primary">
                Thêm sản phẩm
            </button>
            <a
                href="/admin/products"
                class="btn btn-secondary">
                Quay lại
            </a>
        </form>
    </div>
</div>
<!-- Preview ảnh -->
<script>
    document.getElementById('images').addEventListener('change', function(event) {
        const previewContainer =
            document.getElementById('preview-container');
        previewContainer.innerHTML = '';
        const files = event.target.files;
        Array.from(files).forEach(function(file, index) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-3 mb-3';
                col.innerHTML = `
                <div class="card h-100">
                    <img
                        src="${e.target.result}"
                        class="card-img-top"
                        style="height: 180px; object-fit: cover;">
                    <div class="card-body text-center">
                        ${
                            index === 0
                            ? '<span class="badge bg-success">Ảnh chính</span>'
                            : '<span class="badge bg-secondary">Ảnh phụ</span>'
                        }
                    </div>
                </div>
            `;
                previewContainer.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endsection
