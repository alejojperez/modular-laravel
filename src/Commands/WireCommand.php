<?php

namespace ModularLaravel\Commands;

use Illuminate\Console\Command;
use ModularLaravel\Helpers\Names;
use ModularLaravel\ModularLaravelServiceProvider;

class WireCommand extends Command
{
    public $signature = 'modular-laravel:wire {appName} {arguments*}';

    public $description = 'Change the app namespace to the one passed and execute the laravel command';

    public function handle(): int
    {
        $srcName = config("modular-laravel.sourceFolderName");
        $appName = Names::app();

        $newApplication = app()
            ->useNamespace("$appName\\".$this->argument("appName")."\\")
            ->useLangPath("$srcName/$appName/{$this->argument("appName")}/lang")
            ->useDatabasePath("$srcName/$appName/{$this->argument("appName")}/Database")
            ->useResourcePath("$srcName/$appName/{$this->argument("appName")}/resources")
            ->useAppPath("$srcName/$appName/{$this->argument("appName")}");

        ModularLaravelServiceProvider::postInstallWiring($newApplication);

        $console = app(\Illuminate\Contracts\Console\Kernel::class);

        $reflector = new \ReflectionClass($console);
        $property = $reflector->getProperty("app");
        $property->setValue($console, $newApplication);

        return $console->call(implode(" ", $this->argument("arguments")));
    }
}
