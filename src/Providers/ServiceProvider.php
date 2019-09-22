<?php

declare(strict_types=1);

namespace Arlen\Omnicore\Providers;

use Arlen\Omnicore\ClientFactory;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() : void
    {
        $path = realpath(__DIR__.'/../../config/config.php');

        $this->publishes([$path => config_path('omnicored.php')], 'config');
        $this->mergeConfigFrom($path, 'omnicored');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() : void
    {
        $this->registerAliases();

        $this->registerFactory();
        $this->registerClient();
    }

    /**
     * Register aliases.
     *
     * @return void
     */
    protected function registerAliases() : void
    {
        $aliases = [
            'omnicored'         => 'Arlen\Omnicore\ClientFactory',
            'omnicored.client'  => 'Arlen\Omnicore\LaravelClient',
        ];

        foreach ($aliases as $key => $aliases) {
            foreach ((array) $aliases as $alias) {
                $this->app->alias($key, $alias);
            }
        }
    }

    /**
     * Register client factory.
     *
     * @return void
     */
    protected function registerFactory() : void
    {
        $this->app->singleton('omnicored', function ($app) {
            return new ClientFactory(config('omnicored'), $app['log']);
        });
    }

    /**
     * Register client shortcut.
     *
     * @return void
     */
    protected function registerClient() : void
    {
        $this->app->bind('omnicored.client', function ($app) {
            return $app['omnicored']->client();
        });
    }
}
