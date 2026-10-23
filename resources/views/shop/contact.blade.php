@extends('layouts.shop')
@section('content')
<div class="wrap page">
  <h1>Contact Us</h1>
  <p class="lead">Questions about a product or an order? Message us on WhatsApp — it's the fastest way.</p>
  <div class="info-grid">
    <div class="info"><span class="ico"><svg class="i"><use href="#i-user"/></svg></span><div><small>Contact person</small><b>{{ $site['contact_person'] }}</b></div></div>
    <div class="info"><span class="ico"><svg class="i"><use href="#i-phone"/></svg></span><div><small>WhatsApp / Phone</small><b><a href="tel:+{{ $site['country_code'] }}{{ $site['phone'] }}">+{{ $site['country_code'] }} {{ $site['phone'] }}</a></b></div></div>
    @if($site['email'])<div class="info"><span class="ico"><svg class="i"><use href="#i-mail"/></svg></span><div><small>Email</small><b><a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a></b></div></div>@endif
    @if($site['hours'])<div class="info"><span class="ico"><svg class="i"><use href="#i-clock"/></svg></span><div><small>Working hours</small><b>{{ $site['hours'] }}</b></div></div>@endif
    @if($site['address'])<div class="info"><span class="ico"><svg class="i"><use href="#i-pin"/></svg></span><div><small>Address</small><b>{!! nl2br(e($site['address'])) !!}</b></div></div>@endif
  </div>
  <div class="cta-row">
    <a class="btn wa" href="{{ \App\Models\Setting::whatsappUrl('Hi '.$site['contact_person'].', I have a query.') }}" target="_blank" rel="noopener"><svg class="i"><use href="#i-whatsapp"/></svg> Chat on WhatsApp</a>
    <a class="btn ghost" href="tel:+{{ $site['country_code'] }}{{ $site['phone'] }}"><svg class="i"><use href="#i-phone"/></svg> Call now</a>
  </div>
</div>
@endsection
