<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
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
        View::composer('*', function ($view) {
            $settings = [];
            try {
                if (Schema::hasTable('settings')) {
                    $settings = Cache::remember('global_site_settings', 3600, function () {
                        return Setting::pluck('value', 'key')->toArray();
                    });
                }
            } catch (\Throwable $e) {
                $settings = [];
            }
            $view->with('siteSettings', $settings);
        });

        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}
