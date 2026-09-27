@extends('layouts.admin')
@section('title', 'Sửa voucher')
@section('page-title', 'Sửa voucher')
@section('content')
<div class="card border-0 shadow-sm"><div class="card-body p-4"><form method="POST" action="/admin/vouchers/{{ $voucher->id }}">@csrf @method('PUT')<x-voucher-form :voucher="$voucher" /><div class="mt-4"><button class="btn btn-primary" type="submit">Lưu thay đổi</button><a href="/admin/vouchers" class="btn btn-light">Hủy</a></div></form></div></div>
@endsection
