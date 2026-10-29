<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@hasSection('title')@yield('title') – {{ $site['shop_name'] ?? 'Umiya Sales' }}@else{{ $site['shop_name'] ?? 'Umiya Sales' }} – {{ $site['tagline'] ?? '' }}@endif</title>
<meta name="description" content="{{ $site['tagline'] ?? '' }}">
<link rel="icon" href="{{ \App\Models\Setting::logoUrl() }}">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap">
<link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
<style>:root{--primary:{{ $site['primary_color'] }};--accent:{{ $site['accent_color'] }}}</style>
</head>
<body>
@include('partials.icons')
<div class="lb" id="lb" hidden>
  <div class="lb-bar">
    <span class="lb-title" id="lbTitle"></span>
    <div class="lb-tools">
      <button type="button" class="icon-btn" id="lbOut" aria-label="Zoom out">&minus;</button>
      <button type="button" class="icon-btn" id="lbIn" aria-label="Zoom in">+</button>
      <button type="button" class="icon-btn" id="lbClose" aria-label="Close"><svg class="i"><use href="#i-close"/></svg></button>
    </div>
  </div>
  <div class="lb-stage" id="lbStage"><img id="lbImg" alt="" draggable="false"></div>
  <button type="button" class="lb-nav prev" id="lbPrev" aria-label="Previous photo" hidden>&#8249;</button>
  <button type="button" class="lb-nav next" id="lbNext" aria-label="Next photo" hidden>&#8250;</button>
  <div class="lb-count" id="lbCount" hidden></div>
  <div class="lb-price" id="lbPrice" hidden>
    <span class="lb-label">Our price</span>
    <b id="lbP"></b><s id="lbM"></s><em id="lbO"></em>
  </div>
  <div class="lb-hint">Double-tap or scroll to zoom · drag to move</div>
</div>
<div class="overlay" id="overlay"></div>
<aside class="drawer" id="drawer" aria-label="Menu">
  <div class="drawer-h">
    <a class="brand" href="{{ route('shop') }}"><img src="{{ \App\Models\Setting::logoUrl() }}" alt=""><span class="brand-name">@php($w = \App\Models\Setting::brandParts($site['shop_name']))<b class="a">{{ $w[0] }}</b>@if(!empty($w[1]))<b class="b{{ str_starts_with($w[1], '.') ? ' dot' : '' }}">{{ $w[1] }}</b>@endif</span></a>
    <button class="icon-btn" type="button" data-close aria-label="Close menu"><svg class="i"><use href="#i-close"/></svg></button>
  </div>
  <a class="dnav {{ request()->routeIs('shop') && !request('category') ? 'on' : '' }}" href="{{ route('shop') }}"><svg class="i"><use href="#i-home"/></svg><span>Home</span></a>
  <a class="dnav {{ request()->routeIs('contact') ? 'on' : '' }}" href="{{ route('contact') }}"><svg class="i"><use href="#i-phone"/></svg><span>Contact</span></a>
  <a class="dnav {{ request()->routeIs('social') ? 'on' : '' }}" href="{{ route('social') }}"><svg class="i"><use href="#i-share"/></svg><span>Social</span></a>
  @if(!empty($menuCategories) && $menuCategories->isNotEmpty())
    <div class="dlabel">Categories</div>
    @foreach($menuCategories as $mc)
      <a class="dnav sub {{ request('category') === $mc->slug ? 'on' : '' }}" href="{{ route('shop', ['category' => $mc->slug]) }}">{{ $mc->name }} <span class="cnt">({{ $mc->products_count }})</span></a>
    @endforeach
  @endif
  <div class="visitors"><svg class="i"><use href="#i-users"/></svg><span>Visitors<b>{{ number_format((int) ($site['visitor_count'] ?? 0)) }}</b></span></div>
  <a class="btn wa" style="margin:16px" href="{{ \App\Models\Setting::whatsappUrl('Hi '.$site['contact_person'].', I have a query.') }}" target="_blank" rel="noopener">Chat on WhatsApp</a>
</aside>

<header class="top">
  <div class="wrap">
    <button class="icon-btn" type="button" id="menuBtn" aria-label="Open menu" aria-controls="drawer"><svg class="i"><use href="#i-menu"/></svg></button>
    <a class="brand" href="{{ route('shop') }}">
      <img src="{{ \App\Models\Setting::logoUrl() }}" alt="{{ $site['shop_name'] }}">
      <span class="brand-name">@php($w = \App\Models\Setting::brandParts($site['shop_name']))<b class="a">{{ $w[0] }}</b>@if(!empty($w[1]))<b class="b{{ str_starts_with($w[1], '.') ? ' dot' : '' }}">{{ $w[1] }}</b>@endif</span>
    </a>
    <button class="icon-btn search-toggle" type="button" id="searchBtn" aria-label="Search"><svg class="i"><use href="#i-search"/></svg></button>
    <form class="search" action="{{ route('shop') }}" method="get">
      <input type="search" name="q" value="" placeholder="{{ request('q') ? 'Showing: '.request('q') : 'Search products…' }}">
      <button class="icon-btn search-close" type="button" id="searchClose" aria-label="Close search"><svg class="i"><use href="#i-close"/></svg></button>
      <button class="btn" type="submit" aria-label="Search"><svg class="i"><use href="#i-search"/></svg></button>
    </form>
  </div>
</header>

@yield('content')

<footer class="foot">
  <div class="wrap">
    <div>
      <b>{{ $site['shop_name'] }}</b><br>
      {{ $site['footer_text'] }}
      @if(!empty($site['address']))<br><span class="foot-addr">{!! nl2br(e($site['address'])) !!}</span>@endif
    </div>
    <div>
      Contact: <b>{{ $site['contact_person'] }}</b><br>
      WhatsApp: <a href="{{ \App\Models\Setting::whatsappUrl() }}" target="_blank" rel="noopener">+{{ $site['country_code'] }} {{ $site['phone'] }}</a>
    </div>
    @if($site['whatsapp_group'])
      <a class="btn wa" href="{{ $site['whatsapp_group'] }}" target="_blank" rel="noopener">Join WhatsApp Group</a>
    @endif
  </div>
</footer>

<a class="float-wa" href="{{ \App\Models\Setting::whatsappUrl('Hi '.$site['contact_person'].', I have a query.') }}" target="_blank" rel="noopener" title="Chat on WhatsApp"><svg class="i lg"><use href="#i-whatsapp"/></svg></a>
<script>
(function(){
  var lb=document.getElementById('lb'),st=document.getElementById('lbStage'),im=document.getElementById('lbImg'),ti=document.getElementById('lbTitle');
  var sc=1,x=0,y=0,pts={},pd=0,drag=null;
  function apply(){im.style.transform='translate('+x+'px,'+y+'px) scale('+sc+')';st.classList.toggle('zoomed',sc>1)}
  function zoom(n){sc=Math.min(5,Math.max(1,n));if(sc===1){x=0;y=0}apply()}
  var gal=[],gi=0;
  function show(i){gi=(i+gal.length)%gal.length;im.src=gal[gi];sc=1;x=0;y=0;apply();document.getElementById('lbCount').textContent=(gi+1)+' / '+gal.length}
  function open(src,t,d){d=d||{};gal=[];try{gal=d.gallery?JSON.parse(d.gallery):[]}catch(e){}
    gi=gal.indexOf(src);if(gi<0)gal=[];
    var multi=gal.length>1;['lbPrev','lbNext','lbCount'].forEach(function(k){document.getElementById(k).hidden=!multi});
    if(multi)document.getElementById('lbCount').textContent=(gi+1)+' / '+gal.length;
    im.src=src;ti.textContent=t||'';var pr=document.getElementById('lbPrice');pr.hidden=!d.price;document.getElementById('lbP').textContent=d.price||'';var m=document.getElementById('lbM'),o=document.getElementById('lbO');m.textContent=d.mrp||'';m.hidden=!d.mrp;o.textContent=d.off||'';o.hidden=!d.off;if(d.sold){o.hidden=false;o.textContent='SOLD OUT';o.className='sold'}else{o.className=''}sc=1;x=0;y=0;apply();lb.hidden=false;document.body.classList.add('lb-open')}
  function close(){lb.hidden=true;document.body.classList.remove('lb-open');im.removeAttribute('src')}
  document.addEventListener('click',function(e){var z=e.target.closest('[data-zoom]');if(z){e.preventDefault();open(z.dataset.zoom,z.dataset.title,z.dataset)}});
  document.addEventListener('keydown',function(e){
    var z=e.target.closest&&e.target.closest('[data-zoom]');
    if(z&&(e.key==='Enter'||e.key===' ')){e.preventDefault();open(z.dataset.zoom,z.dataset.title,z.dataset)}
    if(!lb.hidden){if(e.key==='Escape')close();if(gal.length>1){if(e.key==='ArrowRight')show(gi+1);if(e.key==='ArrowLeft')show(gi-1)}if(e.key==='+'||e.key==='=')zoom(sc+.5);if(e.key==='-')zoom(sc-.5)}
  });
  document.getElementById('lbClose').onclick=close;
  document.getElementById('lbPrev').onclick=function(){show(gi-1)};
  document.getElementById('lbNext').onclick=function(){show(gi+1)};
  document.getElementById('lbIn').onclick=function(){zoom(sc+.75)};
  document.getElementById('lbOut').onclick=function(){zoom(sc-.75)};
  st.addEventListener('click',function(e){if(e.target===st&&!drag)close()});
  st.addEventListener('dblclick',function(){zoom(sc>1?1:2.5)});
  st.addEventListener('wheel',function(e){e.preventDefault();zoom(sc+(e.deltaY<0?.3:-.3))},{passive:false});
  st.addEventListener('pointerdown',function(e){st.setPointerCapture(e.pointerId);pts[e.pointerId]=e;var k=Object.keys(pts);
    if(k.length===2)pd=Math.hypot(pts[k[0]].clientX-pts[k[1]].clientX,pts[k[0]].clientY-pts[k[1]].clientY);
    drag={sx:e.clientX,sy:e.clientY,ox:x,oy:y,moved:false}});
  st.addEventListener('pointermove',function(e){if(!pts[e.pointerId])return;pts[e.pointerId]=e;var k=Object.keys(pts);
    if(k.length===2){var d=Math.hypot(pts[k[0]].clientX-pts[k[1]].clientX,pts[k[0]].clientY-pts[k[1]].clientY);if(pd)zoom(sc*d/pd);pd=d;if(drag)drag.moved=true;return}
    if(drag&&sc>1){x=drag.ox+e.clientX-drag.sx;y=drag.oy+e.clientY-drag.sy;drag.moved=true;apply()}});
  function up(e){if(drag&&e.type==='pointerup'&&sc===1&&gal.length>1&&Object.keys(pts).length===1){var dx=e.clientX-drag.sx;if(Math.abs(dx)>50&&Math.abs(e.clientY-drag.sy)<80)show(gi+(dx<0?1:-1))}delete pts[e.pointerId];pd=0;setTimeout(function(){drag=null},0)}
  st.addEventListener('pointerup',up);st.addEventListener('pointercancel',up);
  // double-tap on touch screens
  var lt=0;st.addEventListener('touchend',function(e){var n=Date.now();if(n-lt<300&&e.touches.length===0){zoom(sc>1?1:2.5)}lt=n});
})();
</script>
<script>
(function(){
  var d=document.body;
  function set(o){d.classList.toggle('menu-open',o)}
  var hdr=document.querySelector('header.top'),sq=hdr.querySelector('.search input');
  document.getElementById('searchBtn').addEventListener('click',function(){hdr.classList.add('search-open');sq.focus()});
  document.getElementById('searchClose').addEventListener('click',function(){hdr.classList.remove('search-open')});
  document.getElementById('menuBtn').addEventListener('click',function(){set(true)});
  document.getElementById('overlay').addEventListener('click',function(){set(false)});
  document.querySelectorAll('[data-close]').forEach(function(b){b.addEventListener('click',function(){set(false)})});
  document.addEventListener('keydown',function(e){if(e.key==='Escape'){set(false);hdr.classList.remove('search-open')}});
})();
</script>
</body>
</html>
