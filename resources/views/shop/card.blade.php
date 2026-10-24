@php
  $zoomAttrs = '';
  if ($p->image_url) {
      $a = ['data-zoom' => $p->image_url, 'data-title' => $p->title, 'data-price' => '₹'.number_format($p->price, 0), 'role' => 'button', 'tabindex' => '0', 'aria-label' => 'View photo of '.$p->title];
      if ($p->mrp && $p->mrp > $p->price) { $a['data-mrp'] = '₹'.number_format($p->mrp, 0); $a['data-off'] = $p->discount.'% OFF'; }
      if ($p->sold_out) { $a['data-sold'] = '1'; }
      foreach ($a as $k => $v) { $zoomAttrs .= ' '.$k.'="'.e($v).'"'; }
  }
@endphp
<div class="card {{ $p->sold_out ? 'sold' : '' }}">
  <div class="img"{!! $zoomAttrs !!}>
    @if($p->image_url)<img src="{{ $p->image_url }}" alt="{{ $p->title }}" loading="lazy">@endif
    @if($p->discount > 0)<span class="badge">{{ $p->discount }}% OFF</span>@endif
    @if($p->is_today && !$p->sold_out)<span class="badge new">NEW</span>@endif
    @if($p->sold_out)<div class="sold-tag"><span>SOLD OUT</span></div>@endif
  </div>
  <div class="body">
    <h3 title="{{ $p->title }}"><a href="{{ route('product.show', $p) }}">{{ $p->title }}</a></h3>
    <div class="price">
      <b>₹{{ number_format($p->price, 0) }}</b>
      @if($p->mrp && $p->mrp > $p->price)<s>₹{{ number_format($p->mrp, 0) }}</s>@endif
    </div>
    @if($p->sold_out)
      <span class="btn disabled">Sold Out</span>
    @else
      <a class="btn wa" href="{{ $p->whatsappUrl() }}" target="_blank" rel="noopener"><svg class="i"><use href="#i-whatsapp"/></svg> Buy on WhatsApp</a>
    @endif
  </div>
</div>
