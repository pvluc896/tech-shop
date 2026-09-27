@extends('layouts.admin')

@section('title', 'Thêm voucher')
@section('page-title', 'Thêm voucher')

@section('content')
<section class="admin-form-card mx-auto">
    <header class="admin-form-heading">
        <span class="admin-form-icon">%</span>
        <div><p>Khuyến mãi</p><h2>Tạo voucher mới</h2><span>Thiết lập ưu đãi cho sản phẩm hoặc phí vận chuyển.</span></div>
    </header>
    <form class="admin-pretty-form" method="POST" action="/admin/vouchers">
        @csrf
        <x-voucher-form />
        <footer class="admin-form-actions"><a href="/admin/vouchers" class="btn btn-light border">Hủy</a><button class="btn btn-primary px-4" type="submit">Tạo voucher</button></footer>
    </form>
</section>
@endsection
