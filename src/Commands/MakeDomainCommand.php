<?php

namespace ModularLaravel\ModularLaravel\Commands;

class MakeDomainCommand extends AbstractMakeModuleCommand
{
    function getCommandName(): string
    {
        return "domain";
    }

    function getModuleType(): string
    {
        return config("modular-laravel.domainFolderName");
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
