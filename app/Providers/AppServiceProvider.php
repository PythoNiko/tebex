<?php

namespace App\Providers;

use App\Services\Lookup\MinecraftLookupService;
use App\Services\Lookup\SteamLookupService;
use App\Services\Lookup\XblLookupService;
use App\Services\LookupService;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LookupService::class, function ($app) {
            return new LookupService([
                new MinecraftLookupService($app->make(Client::class)),
                new SteamLookupService($app->make(Client::class)),
                new XblLookupService($app->make(Client::class)),
            ]);
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
