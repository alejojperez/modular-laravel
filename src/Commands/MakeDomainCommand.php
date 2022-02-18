<?php

namespace ModularLaravel\ModularLaravel\Commands;

use Illuminate\Support\Stringable;
use ModularLaravel\ModularLaravel\Helpers\Names;

class MakeDomainCommand extends AbstractMakeModuleCommand
{
    function getCommandName(): string
    {
        return "domain";
    }

    function getModuleType(): Stringable
    {
        return Names::domain();
    }

    function getFolders(): array
    {
        return [
            "Actions",
            "QueryBuilders",
            "Collections",
            "DataTransferObjects",
            "Events",
            "Exceptions",
            "Listeners",
            "Models",
            "Rules",
            "States",
        ];
    }
}
