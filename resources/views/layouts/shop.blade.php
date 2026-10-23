<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $site['shop_name'] ?? 'Umiya Sales' }} – {{ $site['tagline'] ?? '' }}</title>
<meta name="description" content="{{ $site['tagline'] ?? '' }}">
<link rel="icon" href="{{ \App\Models\Setting::logoUrl() }}">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
<link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
<style>:root{--primary:{{ $site['primary_color'] }};--accent:{{ $site['accent_color'] }}}</style>
</head>
<body>
@include('partials.icons')
<div class="overlay" id="overlay"></div>
<aside class="drawer" id="drawer" aria-label="Menu">
  <div class="drawer-h">
    <a class="brand" href="{{ route('shop') }}"><img src="{{ \App\Models\Setting::logoUrl() }}" alt=""><span><b>{{ $site['shop_name'] }}</b></span></a>
    <button class="icon-btn" type="button" data-close aria-label="Close menu"><svg class="i"><use href="#i-close"/></svg></button>
  </div>
  <a class="dnav {{ request()->routeIs('shop') && !request('category') ? 'on' : '' }}" href="{{ route('shop') }}"><svg class="i"><use href="#i-home"/></svg><span>Home</span></a>
  <a class="dnav {{ request()->routeIs('contact') ? 'on' : '' }}" href="{{ route('contact') }}"><svg class="i"><use href="#i-phone"/></svg><span>Contact</span></a>
  <a class="dnav {{ request()->routeIs('social') ? 'on' : '' }}" href="{{ route('social') }}"><svg class="i"><use href="#i-share"/></svg><span>Social</span></a>
  @if(!empty($menuCategories) && $menuCategories->isNotEmpty())
    <div class="dlabel">Categories</div>
    @foreach($menuCategories as $mc)
      <a class="dnav sub {{ request('category') === $mc->slug ? 'on' : '' }}" href="{{ route('shop', ['category' => $mc->slug]) }}">{{ $mc->name }}</a>
    @endforeach
  @endif
  <a class="btn wa" style="margin:16px" href="{{ \App\Models\Setting::whatsappUrl('Hi '.$site['contact_person'].', I have a query.') }}" target="_blank" rel="noopener">Chat on WhatsApp</a>
</aside>

<header class="top">
  <div class="wrap">
    <button class="icon-btn" type="button" id="menuBtn" aria-label="Open menu" aria-controls="drawer"><svg class="i"><use href="#i-menu"/></svg></button>
    <a class="brand" href="{{ route('shop') }}">
      <img src="{{ \App\Models\Setting::logoUrl() }}" alt="{{ $site['shop_name'] }}">
      <span><b>{{ $site['shop_name'] }}</b></span>
    </a>
    <form class="search" action="{{ route('shop') }}" method="get">
      <input type="search" name="q" value="{{ request('q') }}" placeholder="Search products…">
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
  var d=document.body;
  function set(o){d.classList.toggle('menu-open',o)}
  document.getElementById('menuBtn').addEventListener('click',function(){set(true)});
  document.getElementById('overlay').addEventListener('click',function(){set(false)});
  document.querySelectorAll('[data-close]').forEach(function(b){b.addEventListener('click',function(){set(false)})});
  document.addEventListener('keydown',function(e){if(e.key==='Escape')set(false)});
})();
</script>
</body>
</html>
