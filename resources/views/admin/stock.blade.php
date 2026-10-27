@extends('layouts.admin')
@section('content')
<h1>Update Qty <small style="font-weight:400;color:var(--muted);font-size:14px">({{ $products->total() }} products)</small></h1>
<form class="bar" method="get">
  <input class="sgrow" type="search" name="q" value="" placeholder="{{ request('q') ? 'Showing: '.request('q').' — search again…' : 'Search product…' }}" style="max-width:360px">
  <select name="category" style="width:auto" onchange="this.form.submit()"><option value="">All categories</option>
    @foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>@endforeach
  </select>
  <select name="status" style="width:auto" onchange="this.form.submit()"><option value="">All status</option>
    @foreach(\App\Models\Product::STATUSES as $k => $v)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>@endforeach
  </select>
  <select name="qty" style="width:auto" onchange="this.form.submit()"><option value="">Any qty</option>
    <option value="zero" @selected(request('qty') === 'zero')>Qty = 0</option>
    <option value="instock" @selected(request('qty') === 'instock')>Qty &gt; 0</option>
  </select>
  <button class="btn" type="submit"><svg class="i"><use href="#i-search"/></svg> Search</button>
  @if(request()->hasAny(['q', 'category', 'status', 'qty']))<a class="btn ghost" href="{{ route('admin.stock') }}">Clear</a>@endif
</form>
<form method="post" action="{{ route('admin.stock.update') }}">
  @csrf
  <div class="panel" style="padding:0;overflow-x:auto">
  <table class="rt">
    <thead><tr><th></th><th>Product</th><th>Price</th><th style="width:130px">Qty</th></tr></thead>
    <tbody>
    @forelse($products as $p)
      <tr>
        <td class="c-img">@if($p->image_url)<img class="thumb" src="{{ $p->image_url }}" alt="" width="52" height="52" style="display:block;width:52px;height:52px;object-fit:contain">@endif</td>
        <td class="c-title"><b>{{ \Illuminate\Support\Str::limit($p->title, 70) }}</b><br><small style="color:var(--muted)">{{ $p->categories->pluck('name')->join(', ') }}</small></td>
        <td data-l="Price">₹{{ number_format($p->price, 0) }}</td>
        <td data-l="Qty"><input type="number" min="0" step="1" name="qty[{{ $p->id }}]" value="{{ old('qty.'.$p->id, $p->qty) }}" style="width:100px"></td>
      </tr>
    @empty
      <tr><td colspan="4" style="text-align:center;padding:24px">No products found.</td></tr>
    @endforelse
    </tbody>
  </table>
  </div>
  <div class="acts"><button class="btn accent" type="submit">Save Quantities</button></div>
</form>
<div style="margin-top:14px">{{ $products->links() }}</div>
@endsection
