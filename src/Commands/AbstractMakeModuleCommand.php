<?php

namespace ModularLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Stringable;

abstract class AbstractMakeModuleCommand extends Command
{
    const REPLACE = "COMMAND_NAME";

    public function __construct()
    {
        $this->signature = str_replace(self::REPLACE, $this->getCommandName(), $this->signature);
        $this->description = str_replace(self::REPLACE, $this->getCommandName(), $this->description);

        parent::__construct();
    }

    public $signature = 'modular-laravel:make:'.self::REPLACE.' {name?} {--empty}';

    public $description = 'Create a new '.self::REPLACE.' scalfolding';

    abstract function getCommandName(): string;

    abstract function getModuleType(): Stringable;

    abstract function getFolders(): array;

    public function handle(): int
    {
        $sourcePath = config("modular-laravel.sourceFolderName");
        $moduleType = $this->getModuleType();

        $slash = DIRECTORY_SEPARATOR;
        $fileSystem = Storage::build($sourcePath.$slash.$moduleType);

        $data = $this->data();
        $name = str($data["name"])->camel()->ucfirst();

        $fileSystem->makeDirectory($name);

        if(!!!$this->option("empty"))
        {
            foreach ($this->getFolders() as $folder)
            {
                $path = $name.$slash.str_replace(".", $slash, $folder);
                $fileSystem->makeDirectory($path);
            }
        }

        $this->comment($moduleType." created successfully.");

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
            exit(self::FAILURE);
        }

        return $value;
    }
}
