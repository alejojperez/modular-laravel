<?php

namespace ModularLaravel\Commands;

use Illuminate\Support\Stringable;
use ModularLaravel\Helpers\Names;

class MakeAppCommand extends AbstractMakeModuleCommand
{
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
}
