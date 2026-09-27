@extends('layouts.admin')
@section('title', 'Sản phẩm')
@section('page-title', 'Quản lý sản phẩm')
@section('content')
<div class="container-fluid">
    {{-- Tiêu đề --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            Danh sách sản phẩm
        </h2>
        <a
            href="/admin/products/create"
            class="btn btn-primary">
            + Thêm sản phẩm
        </a>
    </div>
    {{-- Bảng danh sách --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>SKU</th>
                            <th>Danh mục</th>
                            <th>Thương hiệu</th>
                            <th>Giá</th>
                            <th>Giá KM</th>
                            <th>Số lượng</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                        @php
                        $primaryImage = $product->images
                        ->where('is_primary', true)
                        ->first();
                        $firstImage = $primaryImage
                        ?? $product->images->first();
                        @endphp
                        <tr>
                            {{-- ID --}}
                            <td>
                                {{ $product->id }}
                            </td>
                            {{-- Hình ảnh --}}
                            <td>
                                @if ($firstImage)
                                <img
                                    src="{{ asset('storage/' . $firstImage->image) }}"
                                    onerror="this.remove();"
                                    alt="{{ $product->name }}"
                                    width="70"
                                    height="70"
                                    class="rounded"
                                    style="object-fit: cover;">
                                @else
                                <span class="text-muted">
                                    Chưa có ảnh
                                </span>
                                @endif
                            </td>
                            {{-- Tên --}}
                            <td>
                                <strong>
                                    {{ $product->name }}
                                </strong>
                                <br>
                                <small class="text-muted">
                                    {{ $product->slug }}
                                </small>
                            </td>
                            {{-- SKU --}}
                            <td>
                                {{ $product->sku }}
                            </td>
                            {{-- Danh mục --}}
                            <td>
                                {{ $product->category->name ?? 'Không có' }}
                            </td>
                            {{-- Thương hiệu --}}
                            <td>
                                {{ $product->brand->name ?? 'Không có' }}
                            </td>
                            {{-- Giá --}}
                            <td>
                                {{ number_format(
                                    $product->price,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                                VNĐ
                            </td>
                            {{-- Giá khuyến mãi --}}
                            <td>
                                @if ($product->sale_price)
                                <span class="text-danger fw-bold">
                                    {{ number_format(
                                        $product->sale_price,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                    VNĐ
                                </span>
                                @else
                                <span class="text-muted">
                                    —
                                </span>
                                @endif
                            </td>
                            {{-- Số lượng --}}
                            <td>
                                {{ $product->quantity }}
                            </td>
                            {{-- Trạng thái --}}
                            <td>
                                @if ($product->status === 'active')
                                <span class="badge bg-success">
                                    Active
                                </span>
                                @else
                                <span class="badge bg-secondary">
                                    Inactive
                                </span>
                                @endif
                            </td>
                            {{-- Thao tác --}}
                            <td>
                                <div class="d-flex gap-2">
                                    <a
                                        href="/admin/products/{{ $product->id }}/edit"
                                        class="btn btn-warning btn-sm">
                                        Sửa
                                    </a>
                                    <form
                                        action="/admin/products/{{ $product->id }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm">
                                            Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td
                                colspan="11"
                                class="text-center py-4">
                                <span class="text-muted">
                                    Chưa có sản phẩm nào.
                                </span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($products->hasPages())
            <div class="card-footer bg-white border-0 pt-3 pb-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                <span class="small text-secondary">Hiển thị <strong>{{ $products->firstItem() }}</strong>–<strong>{{ $products->lastItem() }}</strong> trong tổng số <strong>{{ $products->total() }}</strong> sản phẩm</span>
                {{ $products->onEachSide(1)->links() }}
            </div>
        @endif
    </div>
</div>
{{-- Ẩn thông báo sau 2 giây --}}
<script>
    setTimeout(function() {
        const alert =
            document.getElementById('success-alert');
        if (alert) {
            alert.classList.remove('show');
            setTimeout(function() {
                alert.remove();
            }, 150);
        }
    }, 2000);
</script>
@endsection
