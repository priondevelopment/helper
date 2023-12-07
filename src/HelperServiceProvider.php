<?php

namespace PrionDevelopment\Helper;

/**
 * This file is part of Prion Development's Helper Package,
 *  a package filled with traits and model extensions to reduce replicate code.
 *
 * @license MIT
 * @company Prion Development
 * @package Helper
 */

use Illuminate\Support\ServiceProvider;
use PrionDevelopment\Helper\Providers\TestMigrationProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /** @var array */
    protected $setup = [
        TestMigrationProvider::class,
    ];

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSetting();
        $this->registerProviders();
    }

    /**
     * Register Setting in Laravel
     *
     */
    private function registerSetting(): void
    {
        $this->app->singleton('helper', function ($app) {
            return app(\PrionDevelopment\Helper\Helper::class, ['app' => $app]);
        });

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Helper', \PrionDevelopment\Helper\HelperFacade::class);
    }

    /**
     * Register Additional Providers, such as config setup
     * and command setup
     */
    private function registerProviders(): void
    {
        foreach($this->setup as $setup) {
            $this->app->register($setup);
        }
    }
}
