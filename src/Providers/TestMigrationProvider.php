<?php

namespace PrionDevelopment\Helper\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;
use PrionDevelopment\Geography\Contracts\SetupInterface;

class TestMigrationProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        if (app()->environment('testing')) {
            $this->publishes([
                __DIR__.'/../../src/tests/database/migrations/' => database_path('migrations')
            ], 'helper-test-migrations');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
    }
}
