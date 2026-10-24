@extends('layouts.shop')
@section('title', $product->title)
@section('content')
@php($hasMrp = $product->mrp && $product->mrp > $product->price)
<div class="wrap">
  <div class="pd">
    <div class="pd-img"
      @if($product->image_url)
        data-zoom="{{ $product->image_url }}" data-title="{{ $product->title }}" data-price="₹{{ number_format($product->price, 0) }}"
        @if($hasMrp) data-mrp="₹{{ number_format($product->mrp, 0) }}" data-off="{{ $product->discount }}% OFF" @endif
        @if($product->sold_out) data-sold="1" @endif
        role="button" tabindex="0" aria-label="Zoom photo of {{ $product->title }}"
      @endif>
      @if($product->image_url)<img src="{{ $product->image_url }}" alt="{{ $product->title }}"><span class="hint">Tap to zoom</span>@endif
      @if($hasMrp)<span class="badge">{{ $product->discount }}% OFF</span>@endif
      @if($product->sold_out)<div class="sold-tag"><span>SOLD OUT</span></div>@endif
    </div>
    <div class="pd-info">
      <h1>{{ $product->title }}</h1>
      <div class="pd-price">
        <b>₹{{ number_format($product->price, 0) }}</b>
        @if($hasMrp)<s>MRP ₹{{ number_format($product->mrp, 0) }}</s><span class="pd-offer">{{ $product->discount }}% OFF</span>@endif
      </div>
      @if($hasMrp)<div class="pd-save">You save ₹{{ number_format($product->mrp - $product->price, 0) }}</div>@endif
      <div class="pd-meta">
        @foreach($product->categories as $c)<span>{{ $c->name }}</span>@endforeach
        @if($product->is_today && !$product->sold_out)<span>NEW</span>@endif
      </div>
      @if($product->description)<div class="pd-desc">{!! nl2br(e($product->description)) !!}</div>@endif
      @if($product->sold_out)
        <span class="btn disabled">Sold Out</span>
      @else
        <a class="btn wa" href="{{ $product->whatsappUrl() }}" target="_blank" rel="noopener"><svg class="i"><use href="#i-whatsapp"/></svg> Buy on WhatsApp</a>
      @endif
      @if($product->product_url)<a class="btn ghost" href="{{ $product->product_url }}" target="_blank" rel="noopener">View original link</a>@endif
    </div>
  </div>
  @if($related->isNotEmpty())
  <div class="pd-rel">
    <h2>Related products</h2>
    <div class="grid">@foreach($related as $p)@include('shop.card', ['p' => $p])@endforeach</div>
  </div>
  @endif
</div>
@endsection
