<?php

namespace ModularLaravel\ModularLaravel\Commands;

class MakeAppCommand extends AbstractMakeModuleCommand
{
    function getCommandName(): string
    {
        return "app";
    }

    function getModuleType(): string
    {
        return config("modular-laravel.appFolderName");
    }

    function getFolders(): array
    {
        return [
            "Controllers",
            "Middlewares",
            "Requests",
            "Resources",
            "ViewModels"
        ];
    }
}
