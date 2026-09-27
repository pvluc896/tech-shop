<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $productCount = Product::count();
        $categoryCount = Category::count();
        $brandCount = Brand::count();
        $orderCount = Order::count();
        $pendingOrderCount = Order::where('status', 'pending')->count();
        $lowStockCount = Product::where('quantity', '<=', 5)->count();
        $activeVoucherCount = Voucher::where('is_active', true)->count();
        $monthlyRevenue = Order::where('status', '!=', 'cancelled')->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('total');
        $recentOrders = Order::latest()->take(5)->get();

        return view('admin.dashboard', compact('productCount', 'categoryCount', 'brandCount', 'orderCount', 'pendingOrderCount', 'lowStockCount', 'activeVoucherCount', 'monthlyRevenue', 'recentOrders'));
    }

    public function users(Request $request)
    {
        $users = User::query()
            ->withCount('orders')
            ->withSum(['orders as orders_total' => fn ($query) => $query->where('status', '!=', 'cancelled')], 'total')
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($users) => $users->where('name', 'like', '%'.$request->string('search')->trim().'%')->orWhere('email', 'like', '%'.$request->string('search')->trim().'%')->orWhere('phone', 'like', '%'.$request->string('search')->trim().'%')))
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->input('role')))
            ->orderBy('id')
            ->paginate(12)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function toggleUserLock(Request $request, User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Không thể khóa tài khoản quản trị viên.');
        }

        if ($user->is_locked) {
            $user->update([
                'is_locked' => false,
                'lock_reason' => null,
            ]);

            return back()->with('success', 'Đã mở khóa tài khoản khách hàng.');
        }

        $validated = $request->validate([
            'lock_reason' => ['required', 'in:suspicious_activity,policy_violation,fraudulent_order,customer_request,other'],
        ], [
            'lock_reason.required' => 'Vui lòng chọn lý do khóa tài khoản.',
            'lock_reason.in' => 'Lý do khóa không hợp lệ.',
        ]);

        $user->update([
            'is_locked' => true,
            'lock_reason' => $validated['lock_reason'],
        ]);

        return back()->with('success', 'Đã khóa tài khoản khách hàng.');
    }

    public function destroyUser(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Không thể xóa tài khoản quản trị viên.');
        }

        $user->delete();

        return back()->with('success', 'Đã xóa tài khoản khách hàng.');
    }
}
