@extends('layouts.admin')
@section('content')
<h1>{{ $product->exists ? 'Edit Product' : 'Add Product' }}</h1>
<form class="panel" method="post" enctype="multipart/form-data"
      action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
  @csrf
  @if($product->exists) @method('PUT') @endif

  <div class="fld"><label>Title *</label><input type="text" name="title" value="{{ old('title', $product->title) }}" required></div>
  <div class="f2">
    <div class="fld"><label>Category *</label>
      <select name="category_id" required>
        <option value="">Select…</option>
        @foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->name }}</option>@endforeach
      </select>
      @if($categories->isEmpty())<div class="err">Add a category first.</div>@endif
    </div>
    <div class="fld"><label>Status *</label>
      <select name="status">
        @foreach(\App\Models\Product::STATUSES as $k => $v)<option value="{{ $k }}" @selected(old('status', $product->status) === $k)>{{ $v }}</option>@endforeach
      </select>
      <div class="hint">Unavailable products are hidden from the website. Sold out are shown but cannot be bought.</div>
    </div>
  </div>
  <div class="f2">
    <div class="fld"><label>MRP (₹) <span style="font-weight:400;color:var(--muted)">(optional)</span></label><input type="number" step="0.01" min="1" name="mrp" value="{{ old('mrp', $product->mrp) }}"></div>
    <div class="fld"><label>Our Price (₹) *</label><input type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}" required>
      <div class="hint">Discount % is shown only when MRP is entered.</div></div>
  </div>
  <div class="fld"><label>Amazon / Flipkart product link <span style="font-weight:400;color:var(--muted)">(optional)</span></label><input type="url" name="product_url" value="{{ old('product_url', $product->product_url) }}" placeholder="https://www.amazon.in/…"></div>
  <div class="fld"><label>Product image {{ $product->exists ? '' : '*' }}</label>
    <input type="file" name="image" accept="image/*" {{ $product->exists ? '' : 'required' }}>
    @if($product->image_url)<img class="preview" src="{{ $product->image_url }}" alt="">@endif
  </div>
  <div class="fld"><label>Description (optional)</label><textarea name="description">{{ old('description', $product->description) }}</textarea></div>
  <div class="fld"><label style="font-weight:600"><input type="checkbox" name="is_today" value="1" @checked(old('is_today', $product->is_today))> Show at top as “Today's Upload”</label></div>

  <div class="acts"><button class="btn accent" type="submit">Save Product</button><a class="btn ghost" href="{{ route('admin.products.index') }}">Cancel</a></div>
</form>
@endsection
