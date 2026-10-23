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

        $base = Product::visible()->with('categories')
            ->when($q !== '', fn ($query) => $query->where('title', 'like', "%{$q}%"));

        $today = (clone $base)->where('is_today', true)->orderByDesc('today_at')->get();

        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        if ($current || $q !== '') {
            $products = (clone $base)
                ->when($current, fn ($query) => $query->whereHas('categories', fn ($c) => $c->where('categories.id', $current->id)))
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
            $c->setRelation('products', (clone $base)->whereHas('categories', fn ($q) => $q->where('categories.id', $c->id))
                ->orderByRaw("status = 'sold_out'")->latest()->limit(8)->get());
            $c->total = (clone $base)->whereHas('categories', fn ($q) => $q->where('categories.id', $c->id))->count();
            return $c;
        })->filter(fn ($c) => $c->products->isNotEmpty());

        return view('shop.index', compact('today', 'categories', 'current', 'q', 'sections') + ['products' => null]);
    }
}
