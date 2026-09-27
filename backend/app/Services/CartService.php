<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CartService
{
    public function items(Request $request): Collection
    {
        $cart = $this->cart($request);
        $products = Product::with(['images', 'brand', 'category'])
            ->whereIn('id', array_keys($cart))
            ->where('status', 'active')
            ->get()
            ->keyBy('id');

        return collect($cart)->map(function ($quantity, $productId) use ($products) {
            $product = $products->get($productId);

            return $product ? ['product' => $product, 'quantity' => min($quantity, $product->quantity)] : null;
        })->filter()->values();
    }

    public function add(Request $request, Product $product, int $quantity = 1): void
    {
        $cart = $this->cart($request);
        $cart[$product->id] = min(($cart[$product->id] ?? 0) + $quantity, $product->quantity);
        $this->save($request, $cart);
    }

    public function update(Request $request, Product $product, int $quantity): bool
    {
        $cart = $this->cart($request);
        if (! isset($cart[$product->id])) {
            return false;
        }

        $cart[$product->id] = min($quantity, max($product->quantity, 1));
        $this->save($request, $cart);

        return true;
    }

    public function remove(Request $request, Product $product): void
    {
        $cart = $this->cart($request);
        unset($cart[$product->id]);
        $this->save($request, $cart);
    }

    public function clear(Request $request): void
    {
        $this->save($request, []);
    }

    public function save(Request $request, array $cart): void
    {
        if ($request->user()) {
            $request->user()->update(['cart' => $cart]);
        }

        $request->session()->put('cart', $cart);
    }

    private function cart(Request $request): array
    {
        return $request->user()?->cart ?? $request->session()->get('cart', []);
    }
}
