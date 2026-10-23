<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Demo data only. Run: php artisan db:seed --class=DummyProductSeeder
 * Dummy products have product_url on example.com / no link so they are easy to find and delete.
 */
class DummyProductSeeder extends Seeder
{
    public function run(): void
    {
        $cats = Category::pluck('id', 'slug');

        // [category, title, mrp|null, price, status, today, link?]
        $items = [
            ['electronics', 'boAt Rockerz 450 Bluetooth Wireless Headphones', 3990, 1299, 'available', true, true],
            ['electronics', 'Noise ColorFit Pulse Smart Watch 1.69" Display', 4999, 1199, 'available', true, true],
            ['electronics', 'Mi 20000mAh Power Bank 3i 18W Fast Charging', 2999, 1599, 'available', false, true],
            ['electronics', 'Redmi 10A 4GB RAM 64GB Storage Smartphone', 11999, 7499, 'available', false, true],
            ['electronics', 'Logitech M221 Silent Wireless Mouse', 1495, 599, 'available', false, true],
            ['electronics', 'JBL Go 3 Portable Waterproof Speaker', 3999, 2199, 'sold_out', false, true],
            ['electronics', 'TP-Link N300 WiFi Wireless Router', 1999, 799, 'available', false, true],
            ['electronics', 'SanDisk Ultra 128GB microSD Card', 1850, 749, 'available', false, true],
            ['electronics', 'Portronics 65W Fast Charger with Type-C Cable', null, 899, 'available', false, false],
            ['electronics', 'Zebronics Gaming Keyboard and Mouse Combo', 2499, 899, 'available', false, true],
            ['electronics', 'Amazon Fire TV Stick 4K Streaming Device', 5999, 3299, 'available', false, true],
            ['electronics', 'boAt Airdopes 141 True Wireless Earbuds', 4490, 1099, 'available', true, true],
            ['electronics', 'Ambrane 10000mAh Slim Power Bank', 1999, 849, 'available', false, true],
            ['electronics', 'Old Model Webcam 720p (hidden demo)', 1999, 699, 'unavailable', false, true],
            ['fashion', 'Men\'s Cotton Round Neck T-Shirt Pack of 3', 1499, 499, 'available', false, true],
            ['fashion', 'Women\'s Printed Cotton Kurti', 1299, 449, 'available', false, true],
            ['fashion', 'Campus Men\'s Running Shoes', 2499, 899, 'sold_out', false, true],
            ['home-kitchen', 'Pigeon Stainless Steel Pressure Cooker 3L', 1895, 1049, 'available', false, true],
            ['home-kitchen', 'Milton Thermosteel Flask 1000 ml', 1450, 799, 'available', false, true],
            ['beauty', 'Philips Beard Trimmer BT1235 Cordless', 1495, 899, 'available', false, true],
        ];

        // products shown in a second category too
        $extra = ['Philips Beard Trimmer BT1235 Cordless' => $cats['electronics'], 'Milton Thermosteel Flask 1000 ml' => $cats['beauty']];

        $palette = [['#0a5bd6', '#e8f0ff'], ['#ff8a00', '#fff1de'], ['#16a34a', '#e3f7ea'], ['#7c3aed', '#efe7ff'], ['#e8340c', '#ffe9e3'], ['#0891b2', '#e0f6fb']];

        Storage::disk('public')->makeDirectory('products');

        foreach ($items as $i => [$slug, $title, $mrp, $price, $status, $today, $link]) {
            if (Product::where('title', $title)->exists()) {
                continue;
            }
            [$c1, $c2] = $palette[$i % count($palette)];
            $words = array_slice(explode(' ', preg_replace('/[^\w\s]/', '', $title)), 0, 3);
            $label = e(implode(' ', $words));
            $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 600"><rect width="600" height="600" fill="{$c2}"/><circle cx="300" cy="260" r="150" fill="{$c1}" opacity=".15"/><rect x="190" y="150" width="220" height="220" rx="36" fill="{$c1}"/><rect x="215" y="175" width="170" height="90" rx="14" fill="#fff" opacity=".9"/><circle cx="300" cy="325" r="22" fill="#fff" opacity=".9"/><text x="300" y="470" font-family="Arial,sans-serif" font-size="34" font-weight="700" text-anchor="middle" fill="#14213d">{$label}</text><text x="300" y="515" font-family="Arial,sans-serif" font-size="22" text-anchor="middle" fill="#5b6578">Demo product</text></svg>
SVG;
            $path = 'products/demo-' . ($i + 1) . '.svg';
            Storage::disk('public')->put($path, $svg);

            $p = Product::create([
                'title' => $title,
                'image' => $path,
                'product_url' => $link ? 'https://www.amazon.in/dp/DEMO' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT) : null,
                'mrp' => $mrp,
                'price' => $price,
                'status' => $status,
                'is_today' => $today,
                'today_at' => $today ? now()->subMinutes($i) : null,
            ]);
            $p->categories()->sync(array_filter([$cats[$slug], $extra[$title] ?? null]));
        }
    }
}
