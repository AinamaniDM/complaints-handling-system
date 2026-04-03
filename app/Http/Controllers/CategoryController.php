<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('complaints')->orderBy('name')->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:80|unique:categories,name',
            'description' => 'nullable|string|max:255',
        ]);

        Category::create($request->only('name', 'description'));

        return back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => 'required|string|max:80|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:255',
        ]);

        $category->update($request->only('name', 'description'));

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->complaints()->count() > 0) {
            return back()->with('error', 'Cannot delete a category that has complaints.');
        }
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }
}
