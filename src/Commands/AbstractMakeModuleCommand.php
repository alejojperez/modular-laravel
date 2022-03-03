<?php

namespace ModularLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Stringable;

abstract class AbstractMakeModuleCommand extends Command
{
    public const REPLACE = "COMMAND_NAME";

    public function __construct()
    {
        $this->signature = str_replace(self::REPLACE, $this->getCommandName(), $this->signature);
        $this->description = str_replace(self::REPLACE, $this->getCommandName(), $this->description);

        parent::__construct();
    }

    public $signature = 'make:'.self::REPLACE.' {name?} {--empty}';

    public $description = 'Create a new '.self::REPLACE.' scalfolding';

    abstract public function getCommandName(): string;

    abstract public function getModuleType(): Stringable;

    abstract public function getFiles(): array;

    abstract public function getFolders(): array;

    abstract public function replaceFileContent(array $data, string $content): string;

    public function resolveFolderFinalPath(array $data): string
    {
        return $data["name"].DIRECTORY_SEPARATOR;
    }

    public function resolveFileFinalPath(array $data): string
    {
        return $data["name"].DIRECTORY_SEPARATOR;
    }

    public function handle(): int
    {
        $sourcePath = config("modular-laravel.sourceFolderName");
        $moduleType = $this->getModuleType();

        $slash = DIRECTORY_SEPARATOR;
        $fileSystem = Storage::build($sourcePath.$slash.$moduleType);

        $data = $this->data();
        $name = $data["name"];

        $fileSystem->makeDirectory($name);

        if (! ! ! $this->option("empty")) {
            foreach ($this->getFolders() as $folder) {
                $folder = str_replace(".", $slash, $folder);
                $path = $this->resolveFolderFinalPath($data).$folder;
                $fileSystem->makeDirectory($path);
            }

            foreach ($this->getFiles() as $file) {
                $file = str_replace(".", $slash, $file).".php";
                $content = file_get_contents(__DIR__.$slash."..".$slash."stubs".$slash.$this->getModuleType().$slash.$file.".stub");
                $content = $this->replaceFileContent($data, $content);
                $path = $this->resolveFileFinalPath($data).$file;
                $fileSystem->put($path, $content);
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
            "name" => str($this->argument("name") ?? $this->askRequired("What is the name of the module?"))->camel()->ucfirst(),
        ];
    }

    /**
     * @param $question
     * @return string
     */
    protected function askRequired($question): string
    {
        $value = $this->ask($question, "REQUIRED");

        if ($value === "REQUIRED") {
            $this->error("Value is required");
            exit(self::FAILURE);
        }

        return $value;
    }
}
