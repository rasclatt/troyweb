<?php
namespace App\Providers;

use Illuminate\Support\Facades\Vite;
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
        # Fetches the main background image and caches it so it doesn't have to be fetched every time
        view()->composer('*', function ($view) {
            $view->with('siteHeroImage', app(\App\Services\SiteSetting::class)->getHeroImage());
        });
        Vite::prefetch(concurrency: 3);
    }
}