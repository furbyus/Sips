<?php
namespace Furbyus\Sips;

use Illuminate\Support\ServiceProvider;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
        * Publish the sips.php config file
        */
        $this->publishes([
            __DIR__ . '/config/sips.php' => config_path('sips.php'),
        ]);
    }
}
