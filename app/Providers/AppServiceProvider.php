<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::defaultView('pagination');
        \Illuminate\Support\Facades\View::composer(['layouts.*', 'shop.*', 'admin.*'], function ($view) {
            $view->with('site', \App\Models\Setting::all_());
            if ($view->name() === 'layouts.shop') {
                $view->with('menuCategories', \App\Models\Category::withCount(['products' => fn ($q) => $q->visible()])->orderBy('sort_order')->orderBy('name')->get());
            }
        });
    }
}
