<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // =========================
    // USER
    // =========================
    public function index()
    {
        $categories = Category::all();

        return view('categories.index', compact('categories'));
    }

    // =========================
    // ADMIN
    // =========================
    public function adminIndex()
    {
        $categories = Category::orderBy('id')->get();

        return view('admin.categories.index', compact('categories'));
    }

    // =========================
    // CREATE
    // =========================
    public function create()
    {
        return view('admin.categories.create');
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
        ]);
        Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        return redirect('/admin/categories')
            ->with('success', 'Thêm danh mục thành công!');
    }

    // =========================
    // EDIT
    // =========================
    public function edit(Category $category)
    {
        return view(
            'admin.categories.edit',
            compact('category')
        );
    }

    // =========================
    // UPDATE
    // =========================
    public function update(
        Request $request,
        Category $category
    ) {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,'
                .$category->id,
            'description' => 'nullable|string',
        ]);
        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        return redirect('/admin/categories')
            ->with('success', 'Cập nhật danh mục thành công!');
    }

    // =========================
    // DELETE
    // =========================
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Không thể xóa danh mục đang có sản phẩm. Hãy chuyển hoặc xóa các sản phẩm trước.');
        }

        $category->delete();

        return redirect('/admin/categories')
            ->with('success', 'Xóa danh mục thành công!');
    }
}
