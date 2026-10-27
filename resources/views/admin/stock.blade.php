@extends('layouts.admin')
@section('content')
<h1>Update Qty</h1>
<form class="bar" method="get">
  <input class="sgrow" type="search" name="q" value="" placeholder="{{ request('q') ? 'Showing: '.request('q').' — search again…' : 'Search product…' }}" style="max-width:360px">
  <button class="btn" type="submit"><svg class="i"><use href="#i-search"/></svg> Search</button>
  @if(request('q'))<a class="btn ghost" href="{{ route('admin.stock') }}">Clear</a>@endif
</form>
<form method="post" action="{{ route('admin.stock.update') }}">
  @csrf
  <div class="panel" style="padding:0;overflow-x:auto">
  <table>
    <thead><tr><th></th><th>Product</th><th>Price</th><th style="width:130px">Qty</th></tr></thead>
    <tbody>
    @forelse($products as $p)
      <tr>
        <td class="c-img">@if($p->image_url)<img class="thumb" src="{{ $p->image_url }}" alt="">@endif</td>
        <td>{{ \Illuminate\Support\Str::limit($p->title, 70) }}</td>
        <td>₹{{ number_format($p->price, 0) }}</td>
        <td><input type="number" min="0" step="1" name="qty[{{ $p->id }}]" value="{{ old('qty.'.$p->id, $p->qty) }}" style="width:100px"></td>
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
