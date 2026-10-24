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
            'totalPrice' => (float) Product::sum('price'),
            'totalMrp' => (float) Product::sum('mrp'),
            'recent' => Product::with('categories')->latest()->limit(5)->get(),
        ]);
    }
}
