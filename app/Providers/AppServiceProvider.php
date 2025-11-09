<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
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
        $basePackages = config('investment.base_packages', config('investment.packages', []));
        Config::set('investment.base_packages', $basePackages);

        try {
            if (Schema::hasTable('settings')) {
                Setting::apply($basePackages);
            }
        } catch (\Throwable $exception) {
            // During initial migrations the settings table may not exist.
        }
    }
}
