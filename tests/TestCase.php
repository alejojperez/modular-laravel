<?php

namespace ModularLaravel\ModularLaravel\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use ModularLaravel\ModularLaravel\ModularLaravelServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'ModularLaravel\\ModularLaravel\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ModularLaravelServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_modular-laravel_table.php.stub';
        $migration->up();
        */
    }
}
