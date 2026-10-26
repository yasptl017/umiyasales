<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $this->countVisitor($request);

        $q = trim((string) $request->query('q', ''));
        $slug = $request->query('category');
        $current = $slug ? Category::where('slug', $slug)->firstOrFail() : null;

        $base = Product::visible()->with('categories')
            ->when($q !== '', fn ($query) => $query->where('title', 'like', "%{$q}%"));

        $today = (clone $base)->where('is_today', true)->orderByDesc('today_at')->get();

        $categories = Category::withCount(['products' => fn ($q) => $q->visible()])->orderBy('sort_order')->orderBy('name')->get();

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

    /** Count each browser once (1-year cookie); skips ajax and bots. */
    private function countVisitor(Request $request): void
    {
        if ($request->ajax() || $request->hasCookie('umiya_vid') || preg_match('/bot|crawl|spider|slurp|facebookexternalhit|preview/i', (string) $request->userAgent())) {
            return;
        }
        \App\Models\Setting::firstOrCreate(['key' => 'visitor_count'], ['value' => '0']);
        \App\Models\Setting::where('key', 'visitor_count')->increment('value');
        \Illuminate\Support\Facades\Cookie::queue('umiya_vid', '1', 60 * 24 * 365);
    }

    public function show(Product $product)
    {
        abort_if($product->status === 'unavailable', 404);
        $product->load('categories');
        $catIds = $product->categories->pluck('id');
        $related = Product::visible()->where('id', '!=', $product->id)
            ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $catIds))
            ->latest()->limit(4)->get();

        return view('shop.product', compact('product', 'related'));
    }
}
