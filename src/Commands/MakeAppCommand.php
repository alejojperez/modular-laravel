<?php

namespace ModularLaravel\Commands;

use Illuminate\Support\Stringable;
use ModularLaravel\Helpers\Names;

class MakeAppCommand extends AbstractMakeModuleCommand
{
    public $signature = 'modular-laravel:make:'.self::REPLACE.' {name?} {subModule?} {--empty}';

    public function getCommandName(): string
    {
        return "app";
    }

    public function getModuleType(): Stringable
    {
        return Names::app();
    }

    public function getFolders(): array
    {
        return [
            "Controllers",
            "Middlewares",
            "Requests",
            "Resources",
            "ViewModels",
        ];
    }

    public function getFiles(): array
    {
        return [ ];
    }

    /**
     * @return string[]
     */
    protected function data(): array
    {
        return array_merge(
            parent::data(),
            [
                "subModule" => str($this->argument("subModule") ?? $this->askRequired("What is the name of the sub module?"))->camel()->ucfirst(),
            ]
        );
    }

    public function resolveFileFinalPath(array $data): string
    {
        return parent::resolveFileFinalPath($data).$data["subModule"].DIRECTORY_SEPARATOR;
    }

    public function replaceFileContent(array $data, string $content): string
    {
        return str_replace("__MODULE_NAME__", $data["subModule"], $content);
    }

    public function resolveFolderFinalPath(array $data): string
    {
        return $data["name"].DIRECTORY_SEPARATOR.$data["subModule"].DIRECTORY_SEPARATOR;
    }
}
