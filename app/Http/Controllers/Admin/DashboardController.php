<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // Guard so the page still loads if the qty migration hasn't been run yet.
        $hasQty = Schema::hasColumn('products', 'qty');

        return view('admin.dashboard', [
            'categories' => Category::count(),
            'products' => Product::count(),
            'available' => Product::where('status', 'available')->count(),
            'soldOut' => Product::where('status', 'sold_out')->count(),
            'totalQty' => $hasQty ? (int) Product::sum('qty') : 0,
            'totalPrice' => $hasQty ? (float) Product::selectRaw('COALESCE(SUM(price * qty), 0) as t')->value('t') : 0.0,
            'recent' => Product::with('categories')->latest()->limit(5)->get(),
        ]);
    }
}
