@extends('layouts.admin')
@section('title', 'Sửa thương hiệu')
@section('page-title', 'Sửa thương hiệu')
@section('content')
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header">
            <h1 class="h3 mb-0">
                Sửa thương hiệu
            </h1>
        </div>
        <div class="card-body">
            <form
                action="/admin/brands/{{ $brand->id }}"
                method="POST">
                @csrf
                @method('PUT')
                {{-- Tên thương hiệu --}}
                <div class="mb-3">
                    <label class="form-label">
                        Tên thương hiệu:
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $brand->name) }}"
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                {{-- Slug --}}
                <div class="mb-3">
                    <label class="form-label">
                        Slug:
                    </label>
                    <input
                        type="text"
                        name="slug"
                        value="{{ old('slug', $brand->slug) }}"
                        class="form-control @error('slug') is-invalid @enderror">
                    @error('slug')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                {{-- Mô tả --}}
                <div class="mb-3">
                    <label class="form-label">
                        Mô tả:
                    </label>
                    <textarea
                        name="description"
                        rows="4"
                        class="form-control @error('description') is-invalid @enderror">{{ old('description', $brand->description) }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                {{-- Nút --}}
                <button
                    type="submit"
                    class="btn btn-warning">
                    Cập nhật thương hiệu
                </button>
                <a
                    href="/admin/brands"
                    class="btn btn-secondary">
                    Quay lại
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
