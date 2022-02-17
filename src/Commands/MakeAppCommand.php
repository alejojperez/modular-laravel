<?php

namespace ModularLaravel\ModularLaravel\Commands;

class MakeAppCommand extends AbstractMakeModuleCommand
{
    public function getCommandName(): string
    {
        return "app";
    }

    public function getModuleType(): string
    {
        return config("modular-laravel.appFolderName");
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
}
