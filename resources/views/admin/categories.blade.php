@extends('layouts.admin')
@section('content')
<h1>Categories</h1>
<form class="panel bar" method="post" action="{{ route('admin.categories.store') }}">
  @csrf
  <input type="text" name="name" placeholder="New category name" required style="max-width:280px">
  <input type="number" name="sort_order" placeholder="Order" min="0" style="max-width:100px">
  <button class="btn accent" type="submit">Add Category</button>
</form>
<div class="panel" style="padding:0;overflow-x:auto">
<table>
  <thead><tr><th>Name</th><th>Order</th><th>Products</th><th></th></tr></thead>
  <tbody>
  @foreach($categories as $c)
    <tr>
      <td colspan="2">
        <form class="acts" method="post" action="{{ route('admin.categories.update', $c) }}">
          @csrf @method('PUT')
          <input type="text" name="name" value="{{ $c->name }}" style="max-width:260px" required>
          <input type="number" name="sort_order" value="{{ $c->sort_order }}" min="0" style="max-width:80px">
          <button class="btn sm ghost" type="submit">Save</button>
        </form>
      </td>
      <td>{{ $c->products_count }}</td>
      <td>
        <form method="post" action="{{ route('admin.categories.destroy', $c) }}" onsubmit="return confirm('Delete this category?')">
          @csrf @method('DELETE')<button class="btn sm danger" type="submit">Delete</button></form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
</div>
@endsection
