<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'categories' => Category::count(),
            'products' => Product::count(),
            'available' => Product::where('status', 'available')->count(),
            'soldOut' => Product::where('status', 'sold_out')->count(),
            'totalQty' => (int) Product::sum('qty'),
            'totalPrice' => (float) Product::selectRaw('COALESCE(SUM(price * qty), 0) as t')->value('t'),
            'recent' => Product::with('categories')->latest()->limit(5)->get(),
        ]);
    }
}
