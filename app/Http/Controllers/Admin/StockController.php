<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%' . $request->q . '%'))
            ->latest()->paginate(50)->withQueryString();

        return view('admin.stock', compact('products'));
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
