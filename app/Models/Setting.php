<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    protected $fillable = ['key', 'value'];

    public const DEFAULTS = [
        'shop_name' => 'Umiya Sales',
        'tagline' => 'Amazon & Flipkart products at 20% to 80% discount',
        'logo' => '',
        'contact_person' => 'Dhruvam Patel',
        'country_code' => '91',
        'phone' => '9998881524',
        'whatsapp_group' => 'https://chat.whatsapp.com/LOMmANNLstK1hbmT38PpzC',
        'whatsapp_message' => "Hi {contact}, I want to buy this product from {shop}:\n\n{product}\nPrice: ₹{price}\n{link}",
        'primary_color' => '#0a5bd6',
        'accent_color' => '#ff8a00',
        'footer_text' => 'Genuine Amazon & Flipkart deals. Message us on WhatsApp to order.',
        'today_title' => "Today's Upload",
        'email' => '',
        'address' => '',
        'hours' => '',
        'instagram' => '',
        'facebook' => '',
        'youtube' => '',
        'telegram' => '',
        'whatsapp_channel' => '',
    ];

    public const SOCIAL = [
        'whatsapp_group' => ['WhatsApp Group', 'users', '#25d366'],
        'whatsapp_channel' => ['WhatsApp Channel', 'chat', '#128c7e'],
        'instagram' => ['Instagram', 'instagram', '#e1306c'],
        'facebook' => ['Facebook', 'facebook', '#1877f2'],
        'youtube' => ['YouTube', 'youtube', '#ff0000'],
        'telegram' => ['Telegram', 'telegram', '#229ed9'],
    ];

    private static ?array $cache = null;

    public static function all_(): array
    {
        return self::$cache ??= array_merge(self::DEFAULTS, self::pluck('value', 'key')->map(fn ($v) => $v ?? '')->all());
    }

    public static function get(string $key): string
    {
        $v = self::all_()[$key] ?? '';
        return $v === '' && $key !== 'logo' && $key !== 'whatsapp_group' && $key !== 'footer_text' && $key !== 'tagline'
            ? (self::DEFAULTS[$key] ?? '')
            : $v;
    }

    public static function put(string $key, ?string $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
        self::$cache = null;
    }

    /** Split the shop name into [first part, second part] for the two-colour heading. "brandclick.in" -> ["brandclick", ".in"]. */
    public static function brandParts(string $name): array
    {
        $name = trim($name);
        // "brandclick.in" -> ["brand", "click", ".in"] (three colours, like the logo)
        if (preg_match('/^(.+?)(click)(\.[a-z0-9.]+)?$/i', $name, $m) && ! str_contains($name, ' ')) {
            return array_values(array_filter([$m[1], $m[2], $m[3] ?? ''], fn ($p) => $p !== ''));
        }
        if (str_contains($name, ' ')) {
            return explode(' ', $name, 2);
        }
        $dot = strrpos($name, '.');
        if ($dot !== false && $dot > 0) {
            return [substr($name, 0, $dot), substr($name, $dot)];
        }

        return [$name, ''];
    }

    public static function logoUrl(): string
    {
        $logo = self::all_()['logo'] ?? '';
        return $logo ? Storage::disk('public')->url($logo) : asset('images/logo.jpeg');
    }

    public static function whatsappUrl(string $text = ''): string
    {
        $num = preg_replace('/\D/', '', self::get('country_code') . self::get('phone'));
        return 'https://wa.me/' . $num . ($text !== '' ? '?text=' . rawurlencode($text) : '');
    }
}
