@extends('layouts.admin')
@section('title', 'Thêm danh mục')
@section('page-title', 'Thêm danh mục')
@section('content')
<div class="container-fluid admin-form-page">
    <div class="card shadow">
        <div class="card-header">
            <h1 class="h3 mb-0">
                Thêm danh mục
            </h1>
        </div>
        <div class="card-body">
            <form
                action="/admin/categories"
                method="POST">
                @csrf
                {{-- Tên danh mục --}}
                <div class="mb-3">
                    <label class="form-label">
                        Tên danh mục:
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
                {{-- Slug --}}
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
                {{-- Mô tả --}}
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
                {{-- Nút --}}
                <button
                    type="submit"
                    class="btn btn-primary">
                    Thêm danh mục
                </button>
                <a
                    href="/admin/categories"
                    class="btn btn-secondary">
                    Quay lại
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
