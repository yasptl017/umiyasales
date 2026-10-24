<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin – {{ $site['shop_name'] }}</title>
<meta name="robots" content="noindex">
<link rel="icon" href="{{ \App\Models\Setting::logoUrl() }}">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
<link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
<style>:root{--primary:{{ $site['primary_color'] }};--accent:{{ $site['accent_color'] }}}</style>
</head>
<body>
@include('partials.icons')
<div class="adm" id="adm">
  <div class="overlay" id="overlay"></div>
  <aside class="side">
    <a class="brand" href="{{ route('admin.dashboard') }}">
      <img src="{{ \App\Models\Setting::logoUrl() }}" alt="">
      <span><b>{{ $site['shop_name'] }}</b><small>Admin Panel</small></span>
    </a>
    <a class="nav {{ request()->routeIs('admin.dashboard') ? 'on' : '' }}" href="{{ route('admin.dashboard') }}"><svg class="i"><use href="#i-home"/></svg><span>Dashboard</span></a>
    <a class="nav {{ request()->routeIs('admin.products.*') ? 'on' : '' }}" href="{{ route('admin.products.index') }}"><svg class="i"><use href="#i-box"/></svg><span>Products</span></a>
    <a class="nav {{ request()->routeIs('admin.categories.*') ? 'on' : '' }}" href="{{ route('admin.categories.index') }}"><svg class="i"><use href="#i-folder"/></svg><span>Categories</span></a>
    <a class="nav {{ request()->routeIs('admin.settings') ? 'on' : '' }}" href="{{ route('admin.settings') }}"><svg class="i"><use href="#i-settings"/></svg><span>Settings</span></a>
    <a class="nav" href="{{ route('shop') }}" target="_blank"><svg class="i"><use href="#i-external"/></svg><span>View Shop</span></a>
    <form method="post" action="{{ route('admin.logout') }}">@csrf<button class="btn ghost sm" type="submit" style="width:100%"><svg class="i"><use href="#i-logout"/></svg> Logout</button></form>
  </aside>
  <main class="main">
    <div class="admbar">
      <button class="icon-btn" type="button" id="sideBtn" aria-label="Toggle menu"><svg class="i"><use href="#i-menu"/></svg></button>
      <b>{{ $site['shop_name'] }} · Admin</b>
    </div>
    @if(session('ok'))<div class="flash">{{ session('ok') }}</div>@endif
    @if($errors->any())<div class="flash bad">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif
    @yield('content')
  </main>
</div>
<script>
(function(){
  var a=document.getElementById('adm'),mq=window.matchMedia('(max-width:820px)');
  try{if(!mq.matches&&localStorage.getItem('sideCollapsed')==='1')a.classList.add('collapsed')}catch(e){}
  document.getElementById('sideBtn').addEventListener('click',function(){
    if(mq.matches){a.classList.toggle('open')}
    else{a.classList.toggle('collapsed');try{localStorage.setItem('sideCollapsed',a.classList.contains('collapsed')?'1':'0')}catch(e){}}
  });
  document.getElementById('overlay').addEventListener('click',function(){a.classList.remove('open')});
})();
</script>
</body>
</html>
