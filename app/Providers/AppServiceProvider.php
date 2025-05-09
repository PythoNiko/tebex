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
            $client = $app->make(Client::class);
        
            return new LookupService([
                new MinecraftLookupService($client),
                new SteamLookupService($client),
                new XblLookupService($client),
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
