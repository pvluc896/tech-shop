<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request, CartService $cartService)
    {
        $items = $cartService->items($request);
        $subtotal = $items->sum(fn ($item) => ($item['product']->sale_price ?? $item['product']->price) * $item['quantity']);

        return view('cart.index', compact('items', 'subtotal'));
    }

    public function add(Request $request, Product $product, CartService $cartService)
    {
        abort_unless($product->status === 'active', 404);
        $data = $request->validate(['quantity' => ['nullable', 'integer', 'min:1']]);
        $quantity = $data['quantity'] ?? 1;

        if ($product->quantity < 1) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Sản phẩm hiện đã hết hàng.'], 422);
            }

            return back()->with('error', 'Sản phẩm hiện đã hết hàng.');
        }

        $cartService->add($request, $product, $quantity);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Đã thêm sản phẩm vào giỏ hàng.',
                'cartCount' => $cartService->items($request)->count(),
            ]);
        }

        return back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function update(Request $request, Product $product, CartService $cartService)
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1']]);
        if (! $cartService->update($request, $product, $data['quantity'])) {
            return redirect()->route('cart.index');
        }

        return back()->with('success', 'Đã cập nhật giỏ hàng.');
    }

    public function destroy(Request $request, Product $product, CartService $cartService)
    {
        $cartService->remove($request, $product);

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

}
