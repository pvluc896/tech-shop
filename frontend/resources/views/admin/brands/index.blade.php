@extends('layouts.admin')
@section('title', 'Thương hiệu')
@section('page-title', 'Quản lý thương hiệu')
@section('content')
<div class="container-fluid">
    {{-- Tiêu đề --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            Danh sách thương hiệu
        </h2>
        <a
            href="/admin/brands/create"
            class="btn btn-primary">
            + Thêm thương hiệu
        </a>
    </div>
    {{-- Bảng thương hiệu --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 70px;">
                                ID
                            </th>
                            <th>
                                Tên thương hiệu
                            </th>
                            <th>
                                Slug
                            </th>
                            <th>
                                Mô tả
                            </th>
                            <th style="width: 150px;">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($brands as $brand)
                        <tr>
                            {{-- ID --}}
                            <td>
                                {{ $brand->id }}
                            </td>
                            {{-- Tên --}}
                            <td>
                                <strong>
                                    {{ $brand->name }}
                                </strong>
                            </td>
                            {{-- Slug --}}
                            <td>
                                {{ $brand->slug }}
                            </td>
                            {{-- Mô tả --}}
                            <td>
                                {{ $brand->description ?? 'Không có mô tả' }}
                            </td>
                            {{-- Thao tác --}}
                            <td>
                                <div class="d-flex gap-2">
                                    <a
                                        href="/admin/brands/{{ $brand->id }}/edit"
                                        class="btn btn-warning btn-sm">
                                        Sửa
                                    </a>
                                    <form
                                        action="/admin/brands/{{ $brand->id }}"
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
                                colspan="5"
                                class="text-center py-4">
                                <span class="text-muted">
                                    Chưa có thương hiệu nào.
                                </span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
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
