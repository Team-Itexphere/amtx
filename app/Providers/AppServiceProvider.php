<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Settings;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Config;

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
     * App Name chaned at AppServiceProvider.php
     */
    public function boot()
    {
        if (mb_strpos(env('APP_URL'), 'https') === 0) {
            URL::forceScheme('https');
        }
        
        if (Request::is('api/*')) {
            // Use UTC for API responses
            Config::set('app.timezone', 'UTC');
            date_default_timezone_set('UTC');
        } else {
            // Use America/Chicago for web
            Config::set('app.timezone', 'America/Chicago');
            date_default_timezone_set('America/Chicago');
        }

        $settings = Settings::find(1);

        if ($settings) {

            $main_numbers = $settings->main_numbers ? $settings->main_numbers : '';

            config([
                'app.name' => $settings->site_name,
                'app.logo' => $settings->site_logo_path,
                'app.favicon' => $settings->favicon_path,
                'app.country_code' => $settings->country_code,
                'app.main_numbers' => $main_numbers,
            ]);
        } else {
            config([
                'app.name' => 'MSMS',
                'app.logo' => '',
                'app.favicon' => '',
                'app.country_code' => '+1',
                'app.main_numbers' => '',
            ]);
        }
    }
}
