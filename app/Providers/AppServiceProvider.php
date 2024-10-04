<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Settings;

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
