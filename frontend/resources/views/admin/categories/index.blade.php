@extends('layouts.admin')
@section('title', 'Danh mục')
@section('page-title', 'Quản lý danh mục')
@section('content')
<div class="container-fluid">
    {{-- Tiêu đề --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            Danh sách danh mục
        </h2>
        <a
            href="/admin/categories/create"
            class="btn btn-primary">
            + Thêm danh mục
        </a>
    </div>
    {{-- Bảng danh mục --}}
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
                                Tên danh mục
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
                        @forelse ($categories as $category)
                        <tr>
                            {{-- ID --}}
                            <td>
                                {{ $category->id }}
                            </td>
                            {{-- Tên --}}
                            <td>
                                <strong>
                                    {{ $category->name }}
                                </strong>
                            </td>
                            {{-- Slug --}}
                            <td>
                                {{ $category->slug }}
                            </td>
                            {{-- Mô tả --}}
                            <td>
                                {{ $category->description ?? 'Không có mô tả' }}
                            </td>
                            {{-- Thao tác --}}
                            <td>
                                <div class="d-flex gap-2">
                                    <a
                                        href="/admin/categories/{{ $category->id }}/edit"
                                        class="btn btn-warning btn-sm">
                                        Sửa
                                    </a>
                                    <form
                                        action="/admin/categories/{{ $category->id }}"
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
                                    Chưa có danh mục nào.
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
