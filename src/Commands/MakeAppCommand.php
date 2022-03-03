<?php

namespace ModularLaravel\Commands;

use Illuminate\Support\Stringable;
use ModularLaravel\Helpers\Names;

class MakeAppCommand extends AbstractMakeModuleCommand
{
    public $signature = 'make:'.self::REPLACE.' {name?} {subModule?} {--empty}';

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
            "Providers",
            "Requests",
            "Resources",
            "ViewModels",
            "routes",
        ];
    }

    public function getFiles(): array
    {
        return [
            "AppServiceProvider",
            "Providers.RouteServiceProvider",
            "routes.api",
            "routes.web",
        ];
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
        $content = str_replace("__MODULE_NAME__", $data["name"], $content);
        $content = str_replace("__MODULE_NAME_SLUG__", str($data["name"])->slug(), $content);
        $content = str_replace("__SUBMODULE_NAME__", $data["subModule"], $content);
        $content = str_replace("__SUBMODULE_NAME_SLUG__", str($data["subModule"])->slug(), $content);

        return $content;
    }

    public function resolveFolderFinalPath(array $data): string
    {
        return $data["name"].DIRECTORY_SEPARATOR.$data["subModule"].DIRECTORY_SEPARATOR;
    }
}
