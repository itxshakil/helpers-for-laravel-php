<?php

namespace App\Providers;

use App\Support\Contracts\TimeTrackingInterface;
use App\Support\Services\TimeTrackingService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TimeTrackingInterface::class, function () {
            return new TimeTrackingService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
