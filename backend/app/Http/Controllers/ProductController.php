<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Brand;

class ProductController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $product = Product::with(['category', 'brand', 'images'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $product->is_favorited = $request->user()
            ? $product->favoritedBy()->whereKey($request->user()->id)->exists()
            : false;

        return view('products.show', compact('product'));
    }

    public function adminIndex()
    {
        $products = Product::with([
            'category',
            'brand',
            'images',
        ])->orderBy('id')->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'sku' => 'required|string|max:255|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:price',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        $product = Product::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'sku' => $request->sku,
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'quantity' => $request->quantity,
            'status' => $request->status,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
        ]);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'image' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect('/admin/products')
            ->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $product->load('images');

        return view(
            'admin.products.edit',
            compact('product', 'categories', 'brands')
        );
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:price',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        $product->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'sku' => $request->sku,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'quantity' => $request->quantity,
            'status' => $request->status,
        ]);
        // Nếu người dùng chọn ảnh mới
        if ($request->hasFile('images')) {
            // Xóa ảnh cũ trong storage
            foreach ($product->images as $productImage) {
                Storage::disk('public')
                    ->delete($productImage->image);
            }
            // Xóa ảnh cũ trong database
            $product->images()->delete();
            // Thêm ảnh mới
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'image' => $path,
                    // Ảnh đầu tiên là ảnh chính
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect('/admin/products')
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $productImage) {
            Storage::disk('public')
                ->delete($productImage->image);
        }
        $product->delete();

        return redirect('/admin/products')
            ->with('success', 'Xóa sản phẩm thành công!');
    }
}
