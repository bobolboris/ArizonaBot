<?php

namespace App\Providers;

use App\Http\Controllers\Keeper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class KeeperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() { }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        App::singleton('keeper', function ($app) {
            return new Keeper();
        });
    }
}
