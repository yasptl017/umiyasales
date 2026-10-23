<div class="card {{ $p->sold_out ? 'sold' : '' }}">
  <div class="img">
    @if($p->image_url)<img src="{{ $p->image_url }}" alt="{{ $p->title }}" loading="lazy">@endif
    @if($p->discount > 0)<span class="badge">{{ $p->discount }}% OFF</span>@endif
    @if($p->is_today && !$p->sold_out)<span class="badge new">NEW</span>@endif
    @if($p->sold_out)<div class="sold-tag"><span>SOLD OUT</span></div>@endif
  </div>
  <div class="body">
    <h3 title="{{ $p->title }}">{{ $p->title }}</h3>
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
