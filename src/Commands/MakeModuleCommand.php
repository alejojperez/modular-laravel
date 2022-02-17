<?php

namespace ModularLaravel\ModularLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MakeModuleCommand extends Command
{
    public $signature = 'modular-laravel:make:module {name?}';

    public $description = 'Create a new module scalfolding';

    public function handle(): int
    {
        $modulesPath = config("modular-laravel.modulesPath");

        $fileSystem = Storage::build($modulesPath);
        $slash = DIRECTORY_SEPARATOR;
        $data = $this->data();

        $moduleName = str($data["name"])->camel()->ucfirst();

        $fileSystem->makeDirectory($moduleName);

        $fileSystem->makeDirectory($moduleName.$slash."Application");
        $fileSystem->makeDirectory($moduleName.$slash."Application".$slash."Http");
        $fileSystem->makeDirectory($moduleName.$slash."Application".$slash."Http".$slash."Controllers");
        $fileSystem->makeDirectory($moduleName.$slash."Application".$slash."Http".$slash."Middleware");
        $fileSystem->makeDirectory($moduleName.$slash."Application".$slash."Http".$slash."Requests");
        $fileSystem->makeDirectory($moduleName.$slash."Application".$slash."Http".$slash."Resources");
        $fileSystem->makeDirectory($moduleName.$slash."Application".$slash."Policies");

        $fileSystem->makeDirectory($moduleName.$slash."Data");
        $fileSystem->makeDirectory($moduleName.$slash."Data".$slash."Database");
        $fileSystem->makeDirectory($moduleName.$slash."Data".$slash."Database".$slash."Factories");
        $fileSystem->makeDirectory($moduleName.$slash."Data".$slash."Database".$slash."Migrations");
        $fileSystem->makeDirectory($moduleName.$slash."Data".$slash."Database".$slash."Seeders");
        $fileSystem->makeDirectory($moduleName.$slash."Data".$slash."Repositories");

        $fileSystem->makeDirectory($moduleName.$slash."Domain");
        $fileSystem->makeDirectory($moduleName.$slash."Domain".$slash."Actions");
        $fileSystem->makeDirectory($moduleName.$slash."Domain".$slash."Events");
        $fileSystem->makeDirectory($moduleName.$slash."Domain".$slash."Exceptions");
        $fileSystem->makeDirectory($moduleName.$slash."Domain".$slash."Listeners");
        $fileSystem->makeDirectory($moduleName.$slash."Domain".$slash."Models");
        $fileSystem->makeDirectory($moduleName.$slash."Domain".$slash."Rules");

        $fileSystem->makeDirectory($moduleName.$slash."Interface");
        $fileSystem->makeDirectory($moduleName.$slash."Providers");
        $fileSystem->makeDirectory($moduleName.$slash."Tests");

        return self::SUCCESS;
    }

    /**
     * @return string[]
     */
    protected function data(): array
    {
        return [
            "name" => $this->argument("name") ?? $this->askRequired("What is the name of the module?")
        ];
    }

    /**
     * @param $question
     * @return string
     */
    protected function askRequired($question): string
    {
        $value = $this->ask($question, "REQUIRED");

        if($value === "REQUIRED")
        {
            $this->error("Value is required");
            exit(1);
        }

        return $value;
    }
}
