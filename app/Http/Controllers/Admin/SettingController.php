<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings', ['s' => Setting::all_()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'shop_name' => 'required|string|max:100',
            'tagline' => 'nullable|string|max:255',
            'contact_person' => 'required|string|max:100',
            'country_code' => 'required|digits_between:1,4',
            'phone' => 'required|digits_between:6,15',
            'whatsapp_group' => 'nullable|url|max:500',
            'whatsapp_message' => 'required|string|max:1000',
            'primary_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'accent_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'footer_text' => 'nullable|string|max:500',
            'today_title' => 'required|string|max:60',
            'logo' => 'nullable|image|max:3072',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string|max:500',
            'hours' => 'nullable|string|max:200',
            'instagram' => 'nullable|url|max:500',
            'facebook' => 'nullable|url|max:500',
            'youtube' => 'nullable|url|max:500',
            'telegram' => 'nullable|url|max:500',
            'whatsapp_channel' => 'nullable|url|max:500',
        ]);

        $old = Setting::all_()['logo'] ?? '';
        if ($request->hasFile('logo')) {
            if ($old) {
                Storage::disk('public')->delete($old);
            }
            Setting::put('logo', $request->file('logo')->store('branding', 'public'));
        } elseif ($request->boolean('reset_logo')) {
            if ($old) {
                Storage::disk('public')->delete($old);
            }
            Setting::put('logo', '');
        }
        unset($data['logo']);

        foreach ($data as $k => $v) {
            Setting::put($k, $v);
        }
        return back()->with('ok', 'Settings saved.');
    }
}
