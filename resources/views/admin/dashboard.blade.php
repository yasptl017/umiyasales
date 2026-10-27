@extends('layouts.admin')
@section('content')
<h1>Dashboard</h1>
<div class="stats">
  <div class="stat"><span class="ico"><svg class="i"><use href="#i-folder"/></svg></span><div><small>Total categories</small><b>{{ number_format($categories) }}</b></div></div>
  <div class="stat"><span class="ico"><svg class="i"><use href="#i-box"/></svg></span><div><small>Total unique products</small><b>{{ number_format($products) }}</b><em>{{ $available }} available · {{ $soldOut }} sold out</em></div></div>
  <div class="stat"><span class="ico"><svg class="i"><use href="#i-users"/></svg></span><div><small>Total products (qty)</small><b>{{ number_format($totalQty) }}</b></div></div>
  <div class="stat"><span class="ico"><svg class="i"><use href="#i-zap"/></svg></span><div><small>Total price of products</small><b>₹{{ number_format($totalPrice, 0) }}</b><em>Price × qty</em></div></div>
</div>
<div class="panel">
  <h2>Recently added</h2>
  @forelse($recent as $p)
    <div style="display:flex;justify-content:space-between;gap:10px;padding:8px 0;border-top:1px solid var(--line)">
      <a href="{{ route('admin.products.edit', $p) }}">{{ $p->title }}</a><b>₹{{ number_format($p->price, 0) }}</b>
    </div>
  @empty
    <p>No products yet.</p>
  @endforelse
</div>
@endsection
