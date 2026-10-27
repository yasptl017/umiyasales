<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%' . $request->q . '%'))
            ->when($request->filled('category'), fn ($q) => $q->whereHas('categories', fn ($c) => $c->where('categories.id', $request->category)))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->query('qty') === 'zero', fn ($q) => $q->where('qty', 0))
            ->when($request->query('qty') === 'instock', fn ($q) => $q->where('qty', '>', 0))
            ->latest()->paginate(100)->withQueryString();

        return view('admin.stock', ['products' => $products, 'categories' => Category::orderBy('sort_order')->orderBy('name')->get()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'qty' => 'required|array',
            'qty.*' => 'nullable|integer|min:0|max:1000000',
        ]);
        foreach ($data['qty'] as $id => $qty) {
            Product::whereKey($id)->update(['qty' => (int) $qty]);
        }

        return back()->with('ok', 'Quantities updated.');
    }
}
