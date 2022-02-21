<?php

namespace ModularLaravel;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Factories\Factory;
use ModularLaravel\Commands\InstallCommand;
use ModularLaravel\Commands\MakeAppCommand;
use ModularLaravel\Commands\MakeDomainCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ModularLaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('modular-laravel')
            ->hasConfigFile()
            ->hasCommand(InstallCommand::class)
            ->hasCommand(MakeAppCommand::class)
            ->hasCommand(MakeDomainCommand::class);
    }

    public static function postInstallWiring(Application $application): void
    {
        self::registerDatabaseSeeder($application);

        self::registerFactoryNamespace($application);
    }

    #region Post Install Wiring

    protected static function registerDatabaseSeeder(Application $application): void
    {
        $application->bind("DatabaseSeeder", $application->getNamespace()."Database\Seeders\DatabaseSeeder");
    }

    protected static function registerFactoryNamespace(Application $application): void
    {
        Factory::useNamespace($application->getNamespace()."Database\\Factories\\");
    }

    #endregion
}
