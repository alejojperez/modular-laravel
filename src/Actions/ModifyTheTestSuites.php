<?php

namespace ModularLaravel\Actions;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use ModularLaravel\Helpers\Names;

class ModifyTheTestSuites implements ReversableAction
{
    private Filesystem $fileSystem;

    public function __construct(private string $name)
    {
        $this->fileSystem = Storage::build(base_path());
    }

    public function execute(): bool
    {
        $srcName = config("modular-laravel.sourceFolderName");
        $appName = Names::app();
        $newAppFolderPath = $srcName.DIRECTORY_SEPARATOR.$appName.DIRECTORY_SEPARATOR.$this->name;

        $slash = DIRECTORY_SEPARATOR;
        $this->fileSystem->copy("phpunit.xml", "phpunit.xml.bak");
        $content = $this->fileSystem->get("phpunit.xml");

        $content = preg_replace("/name=\"Unit\"/", "name=\"".Names::app()."/".$this->name."/Unit\"", $content);
        $content = preg_replace("/\.\/tests\/Unit/", "./".$newAppFolderPath.$slash."tests/Unit", $content);

        $content = preg_replace("/name=\"Feature\"/", "name=\"".Names::app()."/".$this->name."/Feature\"", $content);
        $content = preg_replace("/\.\/tests\/Feature/", "./".$newAppFolderPath.$slash."tests/Feature", $content);

        $content = preg_replace("/\.\/app/", $newAppFolderPath, $content);

        return $this->fileSystem->put("phpunit.xml", $content);
    }

    public function finish(): bool
    {
        return $this->fileSystem->delete("phpunit.xml.bak");
    }

    public function message(): string
    {
        return "Modifying the phpunit.xml file to load the new tests location instead...";
    }

    public function rollback(): bool
    {
        return
            $this->fileSystem->put("phpunit.xml", $this->fileSystem->get("phpunit.xml.bak"))
            &&
            $this->fileSystem->delete("phpunit.xml.bak");
    }

    public function shouldFinishBefore(): bool
    {
        return false;
    }
}
