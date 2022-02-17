<?php

namespace ModularLaravel\ModularLaravel\Commands;

class MakeDomainCommand extends AbstractMakeModuleCommand
{
    public function getCommandName(): string
    {
        return "domain";
    }

    public function getModuleType(): string
    {
        return config("modular-laravel.domainFolderName");
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
