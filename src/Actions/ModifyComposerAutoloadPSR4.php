<?php

namespace ModularLaravel\Actions;

use Illuminate\Support\Facades\Storage;
use ModularLaravel\Helpers\Names;

class ModifyComposerAutoloadPSR4 implements ReversableAction
{
    const BAK_FILENAME = "composer.json.bak";
    private \Illuminate\Contracts\Filesystem\Filesystem $fileSystem;

    public function __construct()
    {
        $this->fileSystem = Storage::build(base_path());
    }

    public function execute(): bool
    {
        $this->fileSystem->copy("composer.json", self::BAK_FILENAME);

        $content = json_decode($this->fileSystem->get("composer.json"), true);

        $srcName = config("modular-laravel.sourceFolderName");
        $appName = Names::app();
        $domainName = Names::domain();
        $slash = DIRECTORY_SEPARATOR;

        $content["autoload"]["psr-4"] = [
            "$appName\\" => ".".$slash.$srcName.$slash.$appName.$slash,
            "$domainName\\" => ".".$slash.$srcName.$slash.$domainName.$slash,
        ];

        $content = str_replace("\\/", "/", json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $this->fileSystem->put("composer.json", $content);
    }

    public function finish(): bool
    {
        return $this->fileSystem->delete(self::BAK_FILENAME);
    }

    public function message(): string
    {
        return "Modifying the composer autoload PSR-4 section...";
    }

    public function rollback(): bool
    {
        return
            $this->fileSystem->put("composer.json", $this->fileSystem->get(self::BAK_FILENAME))
            &&
            $this->fileSystem->delete(self::BAK_FILENAME);
    }
}
