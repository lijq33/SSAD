<?php
namespace App\Services;

use Illuminate\Support\ServiceProvider;

/**
 * Class MapServiceProvider
 *
 * @package App\Services
 */
class GoogleServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('MapService', function ($app) {
            return new MapService();
        });
    }
}
