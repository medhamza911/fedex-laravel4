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
        // Get config loader
        $loader = $this->app['config']->getLoader();

        // Get environment name
        $env = $this->app['config']->getEnvironment();

        // Add package namespace with path set, override package if app config exists in the main app directory
        if (file_exists(app_path() .'/config/packages/krsman/fedex-laravel4')) {
            $loader->addNamespace('fedex-laravel4', app_path() .'/config/packages/krsman/fedex-laravel4');
        } else {
            $loader->addNamespace('fedex-laravel4',__DIR__.'/../../config');
        }

        // Load package override config file
        $config = $loader->load($env,'config','fedex-laravel4');

        // Override value
        $this->app['config']->set('fedex-laravel4::config',$config);

        $this->app->singleton('FedEx', function ($app) {
            return new FedEx($app['config']->get('fedex-laravel4::config'));
        });
    }
}
