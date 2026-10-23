<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login – {{ $site['shop_name'] }}</title>
<meta name="robots" content="noindex">
<link rel="icon" href="{{ \App\Models\Setting::logoUrl() }}">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<style>:root{--primary:{{ $site['primary_color'] }};--accent:{{ $site['accent_color'] }}}</style>
</head>
<body>
<div class="login">
  <form class="panel" method="post" action="{{ url('/admin/login') }}">
    @csrf
    <div class="brand"><img src="{{ \App\Models\Setting::logoUrl() }}" alt=""><b>{{ $site['shop_name'] }}</b></div>
    @if($errors->any())<div class="flash bad">{{ $errors->first() }}</div>@endif
    <div class="fld"><label>Email</label><input type="email" name="email" value="{{ old('email') }}" required autofocus></div>
    <div class="fld"><label>Password</label><input type="password" name="password" required></div>
    <div class="fld"><label style="font-weight:400"><input type="checkbox" name="remember" value="1"> Remember me</label></div>
    <button class="btn" style="width:100%" type="submit">Login</button>
  </form>
</div>
</body>
</html>
