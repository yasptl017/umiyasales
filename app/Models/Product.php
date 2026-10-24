<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    public const STATUSES = ['available' => 'Available', 'sold_out' => 'Sold Out', 'unavailable' => 'Unavailable'];

    protected $fillable = ['title', 'description', 'image', 'product_url', 'mrp', 'price', 'status', 'is_today', 'today_at'];

    protected $casts = ['is_today' => 'boolean', 'today_at' => 'datetime', 'mrp' => 'float', 'price' => 'float'];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function scopeVisible($q)
    {
        return $q->where('status', '!=', 'unavailable');
    }

    public function getDiscountAttribute(): int
    {
        return $this->mrp > 0 && $this->price <= $this->mrp ? max(0, (int) round(($this->mrp - $this->price) / $this->mrp * 100)) : 0;
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    public function getSoldOutAttribute(): bool
    {
        return $this->status === 'sold_out';
    }

    public function detailUrl(): string
    {
        return route('product.show', $this);
    }

    public function whatsappUrl(): string
    {
        $tpl = Setting::get('whatsapp_message');
        // MRP is intentionally never sent on WhatsApp: strip "(MRP {mrp})" and any bare {mrp}.
        $tpl = preg_replace('/\s*\([^()
]*\{mrp\}[^()
]*\)/u', '', $tpl);
        $tpl = str_replace('{mrp}', '', $tpl);
        if (! $this->product_url) {
            $tpl = preg_replace('/^[^
]*\{link\}[^
]*(
|$)/mu', '', $tpl);
        }
        $text = strtr(trim($tpl), [
            '{contact}' => Setting::get('contact_person'),
            '{shop}' => Setting::get('shop_name'),
            '{product}' => $this->title,
            '{price}' => number_format($this->price, 0),
            '{link}' => (string) $this->product_url,
        ]);
        $text .= "

Product details: ".$this->detailUrl();
        return Setting::whatsappUrl($text);
    }
}
