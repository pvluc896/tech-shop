<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $products = $request->user()->favoriteProducts()
            ->with(['images', 'brand', 'category'])
            ->where('status', 'active')
            ->selectRaw('products.*, 1 as is_favorited')
            ->latest('favorites.created_at')
            ->paginate(12);

        return view('favorites.index', compact('products'));
    }

    public function store(Request $request, Product $product)
    {
        $request->user()->favoriteProducts()->syncWithoutDetaching([$product->id]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Đã thêm sản phẩm vào yêu thích.', 'isFavorited' => true]);
        }

        return back()->with('success', 'Đã thêm sản phẩm vào yêu thích.');
    }

    public function destroy(Request $request, Product $product)
    {
        $request->user()->favoriteProducts()->detach($product->id);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Đã bỏ sản phẩm khỏi yêu thích.', 'isFavorited' => false]);
        }

        return back()->with('success', 'Đã bỏ sản phẩm khỏi yêu thích.');
    }
}
