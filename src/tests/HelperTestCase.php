<?php

namespace PrionDevelopment\Helper\tests;

abstract class HelperTestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadLaravelMigrations();
        $this->artisan('migrate')->run();

        $migrationsDirectory = "{$this->baseDirectory()}/src/tests/database/migrations";
        $this->loadMigrationsFrom($migrationsDirectory);
        $this->artisan('migrate', [
            '--realpath' => realpath($migrationsDirectory),
        ])->run();
    }

    protected function getPackageProviders($app)
    {
        return [
            'PrionDevelopment\Helper\HelperServiceProvider',
        ];
    }

    protected function baseDirectory(): string
    {
        return dirname(__DIR__, 2);
    }
}