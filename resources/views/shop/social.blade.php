@extends('layouts.shop')
@section('content')
<div class="wrap page">
  <h1>Follow &amp; Join Us</h1>
  <p class="lead">Join our community to get new deals first.</p>
  @php($links = collect(\App\Models\Setting::SOCIAL)->filter(fn ($m, $k) => !empty($site[$k])))
  @if($links->isEmpty())
    <div class="empty">No social links added yet.</div>
  @else
    <div class="social-grid">
      @foreach($links as $k => $m)
        <a class="social" href="{{ $site[$k] }}" target="_blank" rel="noopener" style="--c:{{ $m[2] }}">
          <span class="ico"><svg class="i"><use href="#i-{{ $m[1] }}"/></svg></span><b>{{ $m[0] }}</b><svg class="i go"><use href="#i-external"/></svg>
        </a>
      @endforeach
    </div>
  @endif
</div>
@endsection
