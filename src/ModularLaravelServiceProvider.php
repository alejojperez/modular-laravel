<?php

namespace ModularLaravel\ModularLaravel;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use ModularLaravel\ModularLaravel\Commands\MakeModuleCommand;

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
            ->hasCommand(MakeModuleCommand::class);
    }
}
