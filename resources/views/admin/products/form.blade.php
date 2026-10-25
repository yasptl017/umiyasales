@extends('layouts.admin')
@section('content')
<h1>{{ $product->exists ? 'Edit Product' : 'Add Product' }}</h1>
<div class="panel psearch">
  <div class="bar" style="margin:0">
    <input class="grow" type="search" id="psQ" placeholder="Search existing product to edit…" autocomplete="off">
    <button class="btn ghost" type="button" id="psBtn">Search</button>
  </div>
  <div id="psRes" class="psres"></div>
</div>
<script>
(function(){
  var q=document.getElementById('psQ'),r=document.getElementById('psRes'),t,url="{{ route('admin.products.search') }}";
  function run(){
    var v=q.value.trim();if(!v){r.innerHTML='';return}
    fetch(url+'?q='+encodeURIComponent(v),{headers:{'Accept':'application/json'}}).then(function(x){return x.json()}).then(function(a){
      r.innerHTML='';
      if(!a.length){r.textContent='No products found.';return}
      a.forEach(function(p){
        var l=document.createElement('a');l.href=p.url;
        if(p.image){var i=document.createElement('img');i.src=p.image;l.appendChild(i)}
        var s=document.createElement('span');s.textContent=p.title;l.appendChild(s);
        var b=document.createElement('b');b.textContent=p.price;l.appendChild(b);
        r.appendChild(l)});
    });
  }
  q.addEventListener('input',function(){clearTimeout(t);t=setTimeout(run,250)});
  q.addEventListener('keydown',function(e){if(e.key==='Enter'){e.preventDefault();run()}});
  document.getElementById('psBtn').addEventListener('click',run);
})();
</script>
<form class="panel" method="post" enctype="multipart/form-data"
      action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
  @csrf
  @if($product->exists) @method('PUT') @endif

  <div class="fld"><label>Title *</label><input type="text" name="title" value="{{ old('title', $product->title) }}" required></div>
  <div class="f2">
    <div class="fld"><label>Categories *</label>
      @php($sel = collect(old('category_ids', $product->exists ? $product->categories->pluck('id')->all() : [])))
      <div class="checks">
        @foreach($categories as $c)
          <label class="check"><input type="checkbox" name="category_ids[]" value="{{ $c->id }}" @checked($sel->contains($c->id))> {{ $c->name }}</label>
        @endforeach
      </div>
      <div class="hint">Tick one or more. The product appears under every selected category.</div>
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
  <div class="fld"><label>More photos <span style="font-weight:400;color:var(--muted)">(optional, select several)</span></label>
    <input type="file" name="gallery[]" accept="image/*" multiple>
    <div class="hint">Shown after the main image. Customers can flip between photos.</div>
    @if($product->exists && $product->images->isNotEmpty())
      <div class="checks" style="margin-top:8px">
        @foreach($product->images as $img)
          <label class="check"><img class="preview" style="width:80px;height:80px;object-fit:cover;margin:0" src="{{ $img->url }}" alt=""><br><input type="checkbox" name="remove_images[]" value="{{ $img->id }}"> Remove</label>
        @endforeach
      </div>
    @endif
  </div>
  <div class="fld"><label>Description (optional)</label><textarea name="description">{{ old('description', $product->description) }}</textarea></div>
  <div class="fld"><label style="font-weight:600"><input type="checkbox" name="is_today" value="1" @checked(old('is_today', $product->is_today))> Show at top as “Today's Upload”</label></div>

  <div class="acts"><button class="btn accent" type="submit">Save Product</button><a class="btn ghost" href="{{ route('admin.products.index') }}">Cancel</a></div>
</form>
@endsection
