<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class PaginationController extends Controller
{

    public function index(Request $request)
    {
        $productsQuery = Product::with(['category', 'brand', 'images'])
            ->where('status', 'active')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->trim();

                $query->where(function ($productQuery) use ($search) {
                    $productQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')))
            ->when($request->filled('brand'), fn ($query) => $query->where('brand_id', $request->integer('brand')))
            ->when($request->filled('sort'), function ($query) use ($request) {
                return match ($request->input('sort')) {
                    'price_asc' => $query->orderByRaw('COALESCE(sale_price, price) asc'),
                    'price_desc' => $query->orderByRaw('COALESCE(sale_price, price) desc'),
                    'name_asc' => $query->orderBy('name'),
                    default => $query->latest(),
                };
            }, fn ($query) => $query->latest());

        if ($request->user()) {
            $productsQuery->withExists(['favoritedBy as is_favorited' => fn ($query) => $query->whereKey($request->user()->id)]);
        } else {
            $productsQuery->selectRaw('products.*, 0 as is_favorited');
        }

        $perPageOptions = [10, 20, 30];
        $perPage = $request->integer('per_page', 10);

        if ($perPage === 0 || $request->input('per_page') === 'all') {
            $products = $productsQuery->paginate(max($productsQuery->count(), 1))->withQueryString();
        } else {
            $perPage = in_array($perPage, $perPageOptions, true) ? $perPage : 10;
            $products = $productsQuery->paginate($perPage)->withQueryString();
        }

        $categories = Category::orderBy('name')->get(['id', 'name']);
        $brands = Brand::orderBy('name')->get(['id', 'name']);

        return view('products.index', compact('products', 'categories', 'brands', 'perPageOptions', 'perPage'));
    }
}
