<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%' . $request->q . '%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->category))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.products.form', ['product' => new Product(['status' => 'available']), 'categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $this->applyToday($data);
        Product::create($data);
        return redirect()->route('admin.products.index')->with('ok', 'Product added.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', ['product' => $product, 'categories' => Category::orderBy('name')->get()]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, false);
        if (isset($data['image']) && $product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $this->applyToday($data, $product);
        $product->update($data);
        return redirect()->route('admin.products.index')->with('ok', 'Product updated.');
    }

    public function status(Request $request, Product $product)
    {
        $request->validate(['status' => ['required', Rule::in(array_keys(Product::STATUSES))]]);
        $product->update(['status' => $request->status]);
        return back()->with('ok', 'Status updated.');
    }

    public function today(Product $product)
    {
        $product->update(['is_today' => ! $product->is_today, 'today_at' => ! $product->is_today ? now() : null]);
        return back()->with('ok', $product->is_today ? "Added to Today's Upload." : "Removed from Today's Upload.");
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return back()->with('ok', 'Product deleted.');
    }

    private function validated(Request $request, bool $imageRequired): array
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'product_url' => 'nullable|url|max:2000',
            'mrp' => 'nullable|numeric|min:1',
            'price' => ['required', 'numeric', 'min:0', Rule::when($request->filled('mrp'), 'lte:mrp')],
            'status' => ['required', Rule::in(array_keys(Product::STATUSES))],
            'image' => ($imageRequired ? 'required' : 'nullable') . '|image|max:5120',
        ]);
        $data['is_today'] = $request->boolean('is_today');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($data['image']);
        }
        return $data;
    }

    private function applyToday(array &$data, ?Product $product = null): void
    {
        if ($data['is_today']) {
            $data['today_at'] = ($product && $product->is_today && $product->today_at) ? $product->today_at : now();
        } else {
            $data['today_at'] = null;
        }
    }
}
