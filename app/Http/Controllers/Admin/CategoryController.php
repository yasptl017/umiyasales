<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories', ['categories' => Category::withCount('products')->orderBy('sort_order')->orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:100|unique:categories,name', 'sort_order' => 'nullable|integer|min:0']);
        Category::create(['name' => $data['name'], 'slug' => $this->slug($data['name']), 'sort_order' => $data['sort_order'] ?? 0]);
        return back()->with('ok', 'Category added.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate(['name' => 'required|string|max:100|unique:categories,name,' . $category->id, 'sort_order' => 'nullable|integer|min:0']);
        $category->update(['name' => $data['name'], 'sort_order' => $data['sort_order'] ?? 0]);
        return back()->with('ok', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->withErrors(['category' => "Delete or move this category's products first."]);
        }
        $category->delete();
        return back()->with('ok', 'Category deleted.');
    }

    private function slug(string $name): string
    {
        $slug = Str::slug($name) ?: 'category';
        $base = $slug;
        for ($i = 2; Category::where('slug', $slug)->exists(); $i++) {
            $slug = "$base-$i";
        }
        return $slug;
    }
}
