<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with(['images', 'category', 'brand'])->where('status', 'active');
        if (auth()->check()) {
            $products->withExists(['favoritedBy as is_favorited' => fn ($query) => $query->whereKey(auth()->id())]);
        } else {
            $products->selectRaw('products.*, 0 as is_favorited');
        }

        $bestSellers = (clone $products)->withSum('orderItems', 'quantity')
            ->orderByDesc('order_items_sum_quantity')->latest()->take(5)->get();
        $newProducts = (clone $products)->latest()->take(5)->get();
        $saleProducts = (clone $products)->whereNotNull('sale_price')
            ->orderByRaw('(price - sale_price) desc')->take(5)->get();
        $featuredLaptops = (clone $products)->whereHas('category', fn ($query) => $query->where('slug', 'laptop'))
            ->latest()->take(5)->get();
        $accessories = (clone $products)->whereHas('category', fn ($query) => $query->where('slug', 'phu-kien'))
            ->latest()->take(5)->get();

        return view('home', compact('bestSellers', 'newProducts', 'saleProducts', 'featuredLaptops', 'accessories'));
    }
}
