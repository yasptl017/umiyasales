@extends('layouts.admin')
@section('content')
<h1>Shop Settings</h1>
<form class="panel" method="post" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
  @csrf
  <h2>Branding</h2>
  <div class="f2">
    <div class="fld"><label>Shop name *</label><input type="text" name="shop_name" value="{{ old('shop_name', $s['shop_name']) }}" required></div>
    <div class="fld"><label>Tagline</label><input type="text" name="tagline" value="{{ old('tagline', $s['tagline']) }}"></div>
  </div>
  <div class="fld"><label>Logo</label><input type="file" name="logo" accept="image/*">
    <img class="preview" src="{{ \App\Models\Setting::logoUrl() }}" alt="">
    @if($s['logo'])<label style="font-weight:400;margin-top:6px"><input type="checkbox" name="reset_logo" value="1"> Remove custom logo (use default)</label>@endif
  </div>
  <div class="f2">
    <div class="fld"><label>Primary colour</label><input type="color" name="primary_color" value="{{ old('primary_color', $s['primary_color']) }}"></div>
    <div class="fld"><label>Accent colour</label><input type="color" name="accent_color" value="{{ old('accent_color', $s['accent_color']) }}"></div>
  </div>
  <div class="fld"><label>Title of the top section</label><input type="text" name="today_title" value="{{ old('today_title', $s['today_title']) }}" required></div>
  <div class="fld"><label>Footer text</label><input type="text" name="footer_text" value="{{ old('footer_text', $s['footer_text']) }}"></div>

  <h2 style="margin-top:24px">Contact &amp; WhatsApp</h2>
  <div class="fld"><label>Contact person *</label><input type="text" name="contact_person" value="{{ old('contact_person', $s['contact_person']) }}" required></div>
  <div class="f2">
    <div class="fld"><label>Country code *</label><input type="text" name="country_code" value="{{ old('country_code', $s['country_code']) }}" required></div>
    <div class="fld"><label>WhatsApp number *</label><input type="text" name="phone" value="{{ old('phone', $s['phone']) }}" required></div>
  </div>
  <div class="fld"><label>WhatsApp group link</label><input type="url" name="whatsapp_group" value="{{ old('whatsapp_group', $s['whatsapp_group']) }}"></div>
  <div class="fld"><label>Buy-button message *</label>
    <textarea name="whatsapp_message" required>{{ old('whatsapp_message', $s['whatsapp_message']) }}</textarea>
    <div class="hint">Placeholders: {contact} {shop} {product} {price} {link}. {link} is the Amazon/Flipkart product URL. The line with {link} is dropped when a product has no link. MRP is never sent on WhatsApp.</div>
  </div>
  <div class="f2">
    <div class="fld"><label>Email (shown on Contact page)</label><input type="email" name="email" value="{{ old('email', $s['email']) }}"></div>
    <div class="fld"><label>Working hours</label><input type="text" name="hours" value="{{ old('hours', $s['hours']) }}" placeholder="Mon–Sat, 10am – 8pm"></div>
  </div>
  <div class="fld"><label>Address</label><textarea name="address" style="min-height:60px">{{ old('address', $s['address']) }}</textarea></div>

  <h2 style="margin-top:24px">Social links</h2>
  <div class="hint" style="margin-bottom:10px">Empty links are hidden from the Social page.</div>
  <div class="f2">
    @foreach(['whatsapp_channel' => 'WhatsApp channel', 'instagram' => 'Instagram', 'facebook' => 'Facebook', 'youtube' => 'YouTube', 'telegram' => 'Telegram'] as $k => $label)
      <div class="fld"><label>{{ $label }}</label><input type="url" name="{{ $k }}" value="{{ old($k, $s[$k]) }}" placeholder="https://…"></div>
    @endforeach
  </div>
  <button class="btn accent" type="submit">Save Settings</button>
</form>

<form class="panel" method="post" action="{{ route('admin.password') }}">
  @csrf
  <h2>Change admin password</h2>
  <div class="f2">
    <div class="fld"><label>Current password</label><input type="password" name="current_password" required></div>
    <div></div>
    <div class="fld"><label>New password (min 8)</label><input type="password" name="password" required></div>
    <div class="fld"><label>Confirm new password</label><input type="password" name="password_confirmation" required></div>
  </div>
  <button class="btn" type="submit">Change Password</button>
</form>
@endsection
