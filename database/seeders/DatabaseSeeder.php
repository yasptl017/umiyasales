<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@umiyasales.com'], [
            'name' => 'Admin',
            'password' => Hash::make('Admin@12345'),
        ]);

        foreach (Setting::DEFAULTS as $k => $v) {
            if (! Setting::whereKey($k)->exists()) {
                Setting::put($k, $v);
            }
        }

        foreach (['Electronics', 'Fashion', 'Home & Kitchen', 'Beauty', 'Toys & Kids'] as $i => $name) {
            Category::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'sort_order' => $i]);
        }
    }
}
