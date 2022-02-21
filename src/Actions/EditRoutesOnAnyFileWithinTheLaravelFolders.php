<?php

namespace ModularLaravel\Actions;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use ModularLaravel\Helpers\Names;

class EditRoutesOnAnyFileWithinTheLaravelFolders implements ReversableAction
{
    private Filesystem $fileSystem;

    private array $files;

    private string $newAppPath;

    public function __construct(private Command $command, string $name)
    {
        $this->fileSystem = app(Filesystem::class);

        $this->files = collect($this->fileSystem->allFiles("app"))->toArray();

        $srcName = config("modular-laravel.sourceFolderName");
        $appName = Names::app();
        $this->newAppPath = $srcName.DIRECTORY_SEPARATOR.$appName.DIRECTORY_SEPARATOR.$name;
    }

    public function execute(): bool
    {
        $errors = false;

        foreach ($this->files as $key => $file) {
            $content = $this->fileSystem->get($file);

            $regex = '/base_path\([\'|"]routes\/(.+).php[\'|"]\)/';
            $replace = "base_path('$this->newAppPath".DIRECTORY_SEPARATOR."routes/$1.php')";

            if (preg_match($regex, $content)) {
                $this->fileSystem->copy($file, "$file.bak");
                $this->command->comment("    $file");
                $content = preg_replace($regex, $replace, $content);

                if (! ! ! $this->fileSystem->put($file, $content)) {
                    $errors = true;
                }
            } else {
                unset($this->files[$key]);
            }
        }

        return ! ! ! $errors;
    }

    public function finish(): bool
    {
        $errors = false;

        foreach ($this->files as $file) {
            if (! ! ! $this->fileSystem->delete("$file.bak")) {
                $errors = true;
            }
        }

        return ! ! ! $errors;
    }

    public function message(): string
    {
        return "Editing the routes path on the route service procider...";
    }

    public function rollback(): bool
    {
        $errors = false;

        foreach ($this->files as $file) {
            if (! ! ! $this->fileSystem->move("$file.bak", $file)) {
                $errors = true;
            }
        }

        return ! ! ! $errors;
    }

    public function shouldFinishBefore(): bool
    {
        return false;
    }
}
