<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    // =========================
    // USER
    // =========================
    public function index()
    {
        $brands = Brand::all();

        return view('brands.index', compact('brands'));
    }

    // =========================
    // ADMIN
    // =========================
    public function adminIndex()
    {
        $brands = Brand::orderBy('id')->get();

        return view('admin.brands.index', compact('brands'));
    }

    // =========================
    // CREATE
    // =========================
    public function create()
    {
        return view('admin.brands.create');
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug',
            'description' => 'nullable|string',
        ]);
        Brand::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        return redirect('/admin/brands')
            ->with('success', 'Thêm thương hiệu thành công!');
    }

    // =========================
    // EDIT
    // =========================
    public function edit(Brand $brand)
    {
        return view(
            'admin.brands.edit',
            compact('brand')
        );
    }

    // =========================
    // UPDATE
    // =========================
    public function update(
        Request $request,
        Brand $brand
    ) {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug,'
                .$brand->id,
            'description' => 'nullable|string',
        ]);
        $brand->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        return redirect('/admin/brands')
            ->with('success', 'Cập nhật thương hiệu thành công!');
    }

    // =========================
    // DELETE
    // =========================
    public function destroy(Brand $brand)
    {
        if ($brand->products()->exists()) {
            return back()->with('error', 'Không thể xóa thương hiệu đang có sản phẩm. Hãy chuyển hoặc xóa các sản phẩm trước.');
        }

        $brand->delete();

        return redirect('/admin/brands')
            ->with('success', 'Xóa thương hiệu thành công!');
    }
}
