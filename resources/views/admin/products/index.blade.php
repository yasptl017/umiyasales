@extends('layouts.admin')
@section('content')
<h1>Products</h1>
<form class="bar" method="get">
  <input class="sgrow" type="search" name="q" value="" placeholder="{{ request('q') ? 'Showing: '.request('q').' — search again…' : 'Search title…' }}" style="max-width:360px">
  <select name="category" style="width:auto"><option value="">All categories</option>
    @foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>@endforeach
  </select>
  <select name="status" style="width:auto"><option value="">All status</option>
    @foreach(\App\Models\Product::STATUSES as $k => $v)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>@endforeach
  </select>
  <button class="btn" type="submit"><svg class="i"><use href="#i-search"/></svg> Search</button>
  @if(request()->hasAny(['q', 'category', 'status']))<a class="btn ghost" href="{{ route('admin.products.index') }}">Clear</a>@endif
  <span class="grow"></span>
  <a class="btn accent" href="{{ route('admin.products.create') }}">+ Add Product</a>
</form>

<div class="panel" style="padding:0;overflow-x:auto">
<table class="rt">
  <thead><tr><th></th><th>Product</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead>
  <tbody>
  @forelse($products as $p)
    <tr>
      <td class="c-img">@if($p->image_url)<img class="thumb" src="{{ $p->image_url }}" alt="">@endif</td>
      <td class="c-title"><b>{{ \Illuminate\Support\Str::limit($p->title, 60) }}</b><br>
        <small style="color:var(--muted)">{{ $p->categories->pluck('name')->join(', ') }}</small>
        @if($p->is_today)<span class="tag today">Today's Upload</span>@endif</td>
      <td data-l="Price">₹{{ number_format($p->price, 0) }} @if($p->mrp)<small style="color:var(--muted)"><s>₹{{ number_format($p->mrp, 0) }}</s> ({{ $p->discount }}%)</small>@endif</td>
      <td data-l="Status">
        <form method="post" action="{{ route('admin.products.status', $p) }}" class="acts">
          @csrf @method('PATCH')
          <select name="status" onchange="this.form.submit()" class="tag {{ $p->status }}">
            @foreach(\App\Models\Product::STATUSES as $k => $v)<option value="{{ $k }}" @selected($p->status === $k)>{{ $v }}</option>@endforeach
          </select>
        </form>
      </td>
      <td class="c-act">
        <div class="acts">
          <form method="post" action="{{ route('admin.products.today', $p) }}">@csrf @method('PATCH')
            <button class="btn sm {{ $p->is_today ? 'accent' : 'ghost' }}" type="submit">{{ $p->is_today ? '★ Today' : '☆ Today' }}</button></form>
          <a class="btn sm ghost" href="{{ route('admin.products.edit', $p) }}">Edit</a>
          <form method="post" action="{{ route('admin.products.destroy', $p) }}" onsubmit="return confirm('Delete this product permanently?')">@csrf @method('DELETE')
            <button class="btn sm danger" type="submit">Delete</button></form>
        </div>
      </td>
    </tr>
  @empty
    <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:30px">No products yet.</td></tr>
  @endforelse
  </tbody>
</table>
</div>
{{ $products->links() }}
@endsection
