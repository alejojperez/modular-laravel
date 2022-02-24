<?php

namespace ModularLaravel\Commands;

use Illuminate\Console\Command;
use ModularLaravel\Helpers\Names;
use ModularLaravel\ModularLaravelServiceProvider;

class WireAppCommand extends Command
{
    public $signature = 'modular-laravel:wire-app {appName} {subModule} {arguments*}';

    public $description = 'Change the app namespace to the one passed and execute the laravel command';

    public function handle(): int
    {
        $srcName = config("modular-laravel.sourceFolderName");
        $appName = Names::app();

        $newApplication = app()
            ->useNamespace("$appName\\{$this->argument("appName")}\\{$this->argument("subModule")}\\")
            ->useLangPath("$srcName/$appName/{$this->argument("appName")}/{$this->argument("subModule")}/lang")
            ->useDatabasePath("$srcName/$appName/{$this->argument("appName")}/{$this->argument("subModule")}/Database")
            ->useResourcePath("$srcName/$appName/{$this->argument("appName")}/{$this->argument("subModule")}/resources")
            ->useAppPath("$srcName/$appName/{$this->argument("appName")}/{$this->argument("subModule")}");

        ModularLaravelServiceProvider::postInstallWiring($newApplication);

        $console = app(\Illuminate\Contracts\Console\Kernel::class);

        $reflector = new \ReflectionClass($console);
        $property = $reflector->getProperty("app");
        $property->setValue($console, $newApplication);

        return $console->call(implode(" ", $this->argument("arguments")));
    }
}
