<?php

namespace ModularLaravel\Actions;

use Illuminate\Filesystem\Filesystem;
use ModularLaravel\Helpers\Names;

class MoveFoldersAndFilesToTheNewAppFolder implements ReversableAction
{
    private Filesystem $fileSystem;

    private $folders = ["app", "database", "lang", "resources", "routes", "tests"];

    private string $newAppPath;

    public function __construct(string $name)
    {
        $this->fileSystem = app(Filesystem::class);

        $srcName = config("modular-laravel.sourceFolderName");
        $appName = Names::app();
        $this->newAppPath = $srcName.DIRECTORY_SEPARATOR.$appName.DIRECTORY_SEPARATOR.$name;
    }

    public function execute(): bool
    {
        foreach ($this->folders as $folder) {
            switch ($folder) {
                case "app":
                    $target = $this->newAppPath.($folder === "app" ? "" : DIRECTORY_SEPARATOR.$folder);

                    break;
                case "database":
                    $target = $this->newAppPath.DIRECTORY_SEPARATOR.ucfirst($folder);

                    break;
                default:
                    $target = $this->newAppPath.DIRECTORY_SEPARATOR.$folder;
            }

            if (! ! ! $this->fileSystem->copyDirectory($folder, $target)) {
                return false;
            }

            if ($folder === "database") {
                $slash = DIRECTORY_SEPARATOR;

                foreach ($this->fileSystem->directories($target) as $directory) {
                    if (str_contains($directory, "migrations")) {
                        continue;
                    }

                    $pathParts = explode($slash, $directory);
                    $newDirectory = Names::name(array_pop($pathParts));
                    $pathParts[] = $newDirectory;

                    $this->fileSystem->move($directory, implode($slash, $pathParts));
                }
            }
        }

        return true;
    }

    public function finish(): bool
    {
        foreach ($this->folders as $folder) {
            if (! ! ! $this->fileSystem->deleteDirectory($folder)) {
                return false;
            }
        }

        return true;
    }

    public function message(): string
    {
        return "Moving content from the Laravel standard folders into the new app folder...";
    }

    public function rollback(): bool
    {
        return $this->fileSystem->delete($this->newAppPath);
    }
}
