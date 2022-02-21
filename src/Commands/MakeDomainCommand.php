<?php

namespace ModularLaravel\Commands;

use Illuminate\Support\Stringable;
use ModularLaravel\Helpers\Names;

class MakeDomainCommand extends AbstractMakeModuleCommand
{
    public function getCommandName(): string
    {
        return "domain";
    }

    public function getModuleType(): Stringable
    {
        return Names::domain();
    }

    public function getFolders(): array
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
