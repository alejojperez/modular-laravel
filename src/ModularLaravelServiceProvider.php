<?php

namespace ModularLaravel\ModularLaravel;

use ModularLaravel\ModularLaravel\Commands\InstallCommand;
use ModularLaravel\ModularLaravel\Commands\MakeAppCommand;
use ModularLaravel\ModularLaravel\Commands\MakeDomainCommand;
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
}
