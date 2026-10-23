@extends('layouts.shop')
@section('content')
<section class="hero">
  <div class="wrap">
    <div>
      <span class="pill">20% – 80% OFF</span>
      <h1>{{ $site['tagline'] }}</h1>
      <p>Pick a product and tap <b>Buy on WhatsApp</b> — {{ $site['contact_person'] }} will confirm your order.</p>
    </div>
    <div class="cta">
      <a class="hbtn hbtn-wa" href="{{ \App\Models\Setting::whatsappUrl('Hi '.$site['contact_person'].', I have a query.') }}" target="_blank" rel="noopener">
        <svg class="i"><use href="#i-whatsapp"/></svg><span><small>Order or ask a question</small>Chat on WhatsApp</span>
      </a>
      @if($site['whatsapp_group'])
        <a class="hbtn hbtn-group" href="{{ $site['whatsapp_group'] }}" target="_blank" rel="noopener">
          <svg class="i"><use href="#i-whatsapp"/></svg><span><small>Get daily deals first</small>Join WhatsApp Group</span>
        </a>
      @endif
    </div>
  </div>
</section>

<div class="wrap">
  <nav class="chips">
    <a class="chip {{ !$current ? 'on' : '' }}" href="{{ route('shop') }}">All</a>
    @foreach($categories as $c)
      <a class="chip {{ $current && $current->id === $c->id ? 'on' : '' }}" href="{{ route('shop', ['category' => $c->slug]) }}">{{ $c->name }}</a>
    @endforeach
  </nav>

  @if($today->isNotEmpty() && !$current)
  <section class="sec today">
    <div class="sec-h"><h2><svg class="i zap"><use href="#i-zap"/></svg>{{ $site['today_title'] }}</h2></div>
    <div class="grid">@foreach($today as $p) @include('shop.card', ['p' => $p]) @endforeach</div>
  </section>
  @endif

  @if($products)
    <section class="sec">
      <div class="sec-h"><h2>{{ $current ? $current->name : 'Search results for “'.$q.'”' }}</h2></div>
      @if($products->isEmpty())
        <div class="empty">No products found.</div>
      @else
        <div class="grid" id="productGrid">@include('shop.cards', ['products' => $products])</div>
        @if($products->hasMorePages())
          <div class="more" id="moreWrap">
            <a class="btn ghost load-more" id="loadMore" href="{{ $products->nextPageUrl() }}" data-next="{{ $products->nextPageUrl() }}">Load more products</a>
          </div>
          <script>
          (function(){
            var btn=document.getElementById('loadMore'),grid=document.getElementById('productGrid');
            btn.addEventListener('click',function(e){
              e.preventDefault();
              var url=btn.dataset.next;if(!url)return;
              btn.classList.add('loading');btn.textContent='Loading…';
              fetch(url,{headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}})
                .then(function(r){if(!r.ok)throw 0;return r.json()})
                .then(function(d){
                  grid.insertAdjacentHTML('beforeend',d.html);
                  if(d.next){btn.dataset.next=d.next;btn.href=d.next;btn.classList.remove('loading');btn.textContent='Load more products'}
                  else{document.getElementById('moreWrap').remove()}
                })
                .catch(function(){window.location.href=url});
            });
          })();
          </script>
        @endif
      @endif
    </section>
  @else
    @forelse($sections as $c)
      <section class="sec">
        <div class="sec-h">
          <h2>{{ $c->name }}</h2>
          @if($c->total > $c->products->count())
            <a href="{{ route('shop', ['category' => $c->slug]) }}">View all ({{ $c->total }}) →</a>
          @endif
        </div>
        <div class="grid">@foreach($c->products as $p) @include('shop.card', ['p' => $p]) @endforeach</div>
      </section>
    @empty
      <div class="empty" style="margin-top:20px">New products are coming soon. Join our WhatsApp group for updates!</div>
    @endforelse
  @endif
</div>
@endsection
