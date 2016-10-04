<?php
namespace Krsman\FedEx\Laravel\Providers;

use Krsman\FedEx\Laravel\FedEx;
use Illuminate\Support\ServiceProvider;

class FedExServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Set up the publishing of configuration
     */
    public function boot()
    {
        $this->package('krsman/fedex-laravel4');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['FedEx'];
    }

    /**
     * Register the FedEx Instance with the IoC-container
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('FedEx', function ($app) {
            return new FedEx($app['config']['fedex']);
        });
    }
}
