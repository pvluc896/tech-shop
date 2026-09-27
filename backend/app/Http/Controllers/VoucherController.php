<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
    public function index(Request $request, VoucherService $vouchers)
    {
        return view('vouchers.index', $vouchers->wallet($request->user()));
    }

    public function claim(Request $request, Voucher $voucher, VoucherService $vouchers)
    {
        $vouchers->claim($request->user(), $voucher);
        return back()->with('success', 'Đã nhận voucher '.$voucher->code.'.');
    }

    public function redeem(Request $request, VoucherService $vouchers)
    {
        $data = $request->validate(['voucher_code' => ['required', 'string', 'max:50']]);
        $voucher = $vouchers->redeem($request->user(), $data['voucher_code']);
        return back()->with('success', 'Đã nhận voucher '.$voucher->code.'.');
    }

    public function remove(Request $request, Voucher $voucher, VoucherService $vouchers)
    {
        if (! $vouchers->remove($request->user(), $voucher)) return back()->withErrors(['voucher' => 'Voucher đã sử dụng không thể xóa khỏi lịch sử.']);
        return back()->with('success', 'Đã xóa voucher '.$voucher->code.' khỏi ví.');
    }

    public function adminIndex() { return view('admin.vouchers.index', ['vouchers' => Voucher::orderBy('id')->get()]); }
    public function create() { return view('admin.vouchers.create'); }

    public function store(Request $request, VoucherService $vouchers)
    {
        $vouchers->create($this->validated($request));
        return redirect('/admin/vouchers')->with('success', 'Đã tạo voucher.');
    }

    public function edit(Voucher $voucher) { return view('admin.vouchers.edit', compact('voucher')); }

    public function update(Request $request, Voucher $voucher, VoucherService $vouchers)
    {
        $vouchers->update($voucher, $this->validated($request, $voucher));
        return redirect('/admin/vouchers')->with('success', 'Đã cập nhật voucher.');
    }

    public function destroy(Voucher $voucher, VoucherService $vouchers)
    {
        $vouchers->delete($voucher);
        return back()->with('success', 'Đã xóa voucher.');
    }

    private function validated(Request $request, ?Voucher $voucher = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('vouchers', 'code')->ignore($voucher?->id)], 'claim_method' => ['required', 'in:claim,code'], 'name' => ['required', 'string', 'max:255'],
            'voucher_kind' => ['required', 'in:discount,shipping'], 'type' => ['nullable', 'required_unless:voucher_kind,shipping', 'in:fixed,percent'], 'value' => ['nullable', 'required_unless:voucher_kind,shipping', 'numeric', 'min:0'],
            'min_order_amount' => ['required', 'numeric', 'min:0'], 'max_discount' => ['nullable', 'numeric', 'min:0'], 'usage_limit' => ['nullable', 'integer', 'min:1'], 'starts_at' => ['nullable', 'date'], 'ends_at' => ['nullable', 'date', 'after:starts_at'], 'is_active' => ['required', 'boolean'],
        ]);
        $data['is_free_shipping'] = $data['voucher_kind'] === 'shipping';
        unset($data['voucher_kind']);
        return $data;
    }
}
