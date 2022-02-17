<?php

namespace ModularLaravel\ModularLaravel;

use ModularLaravel\ModularLaravel\Commands\MakeModuleCommand;
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
            ->hasCommand(MakeModuleCommand::class);
    }
}
