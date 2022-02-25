<?php

namespace ModularLaravel\Commands;

use Illuminate\Console\Command;
use ModularLaravel\Helpers\Names;
use ModularLaravel\ModularLaravelServiceProvider;

class WireDomainCommand extends Command
{
    public $signature = 'modular-laravel:wire:domain {domain} {arguments*}';

    public $description = 'Change the app namespace to the one passed and execute the laravel command';

    public function handle(): int
    {
        $srcName = config("modular-laravel.sourceFolderName");
        $domain = Names::domain();

        $newApplication = app()
            ->useNamespace("$domain\\".$this->argument("domain")."\\")
            ->useLangPath("$srcName/$domain/{$this->argument("domain")}/lang")
            ->useDatabasePath("$srcName/$domain/{$this->argument("domain")}/Database")
            ->useResourcePath("$srcName/$domain/{$this->argument("domain")}/resources")
            ->useAppPath("$srcName/$domain/{$this->argument("domain")}");

        ModularLaravelServiceProvider::postInstallWiring($newApplication);

        $console = app(\Illuminate\Contracts\Console\Kernel::class);

        $reflector = new \ReflectionClass($console);
        $property = $reflector->getProperty("app");
        $property->setValue($console, $newApplication);

        return $console->call(implode(" ", $this->argument("arguments")), [], $this->getOutput());
    }
}
