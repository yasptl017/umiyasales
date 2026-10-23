<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $slug = $request->query('category');
        $current = $slug ? Category::where('slug', $slug)->firstOrFail() : null;

        $base = Product::visible()->with('category')
            ->when($q !== '', fn ($query) => $query->where('title', 'like', "%{$q}%"));

        $today = (clone $base)->where('is_today', true)->orderByDesc('today_at')->get();

        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        if ($current || $q !== '') {
            $products = (clone $base)
                ->when($current, fn ($query) => $query->where('category_id', $current->id))
                ->orderByRaw("status = 'sold_out'")->latest()->paginate(12)->withQueryString();
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('shop.cards', ['products' => $products])->render(),
                    'next' => $products->nextPageUrl(),
                ]);
            }
            return view('shop.index', compact('today', 'categories', 'current', 'q') + ['products' => $products, 'sections' => null]);
        }

        $sections = $categories->map(function ($c) use ($base) {
            $c->setRelation('products', (clone $base)->where('category_id', $c->id)
                ->orderByRaw("status = 'sold_out'")->latest()->limit(8)->get());
            $c->total = (clone $base)->where('category_id', $c->id)->count();
            return $c;
        })->filter(fn ($c) => $c->products->isNotEmpty());

        return view('shop.index', compact('today', 'categories', 'current', 'q', 'sections') + ['products' => null]);
    }
}
