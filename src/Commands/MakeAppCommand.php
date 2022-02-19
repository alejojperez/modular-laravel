<?php

namespace ModularLaravel\Commands;

use Illuminate\Support\Stringable;
use ModularLaravel\Helpers\Names;

class MakeAppCommand extends AbstractMakeModuleCommand
{
    function getCommandName(): string
    {
        return "app";
    }

    function getModuleType(): Stringable
    {
        return Names::app();
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
