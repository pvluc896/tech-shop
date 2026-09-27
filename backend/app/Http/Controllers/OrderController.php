<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $status = in_array($status, ['pending', 'confirmed', 'shipping', 'delivered', 'cancelled'], true) ? $status : null;
        $orders = $request->user()->orders()->withCount('items')->when($status, fn ($query) => $query->where('status', $status))->latest()->paginate(10)->withQueryString();
        return view('orders.index', compact('orders', 'status'));
    }

    public function show(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        $order->load('items.product');
        return view('orders.show', compact('order'));
    }

    public function cancel(Request $request, Order $order, OrderService $orders)
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        $data = $request->validate(['cancel_reason' => ['required', 'in:ordered_by_mistake,change_order_info,delivery_time,better_price,other']], ['cancel_reason.required' => 'Vui lòng chọn lý do hủy đơn.', 'cancel_reason.in' => 'Lý do hủy đơn không hợp lệ.']);
        $orders->cancel($order, $request->user(), $data['cancel_reason']);
        return redirect()->route('orders.index')->with('success', 'Đã hủy đơn hàng. Số lượng sản phẩm và voucher (nếu có) đã được hoàn lại.');
    }

    public function adminIndex(Request $request)
    {
        $orders = Order::query()
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($orders) => $orders->where('order_number', 'like', '%'.$request->string('search')->trim().'%')->orWhere('customer_name', 'like', '%'.$request->string('search')->trim().'%')->orWhere('phone', 'like', '%'.$request->string('search')->trim().'%')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->orderBy('id')->paginate(12)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function adminShow(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order, OrderService $orders)
    {
        if ($order->status === 'cancelled') return back()->with('error', 'Đơn hàng đã hủy nên không thể cập nhật trạng thái.');
        $data = $request->validate(['status' => ['required', 'in:pending,confirmed,shipping,delivered,cancelled']]);
        $orders->updateStatus($order, $data['status']);
        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }
}
