<?php

namespace App\Providers;

use App\Http\Controllers\Arizona;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ArizonaProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        App::singleton('arizona', function ($app) {
            return new Arizona();
        });
    }
}
