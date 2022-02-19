<?php

namespace ModularLaravel\Actions;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ModifyTheNamespaceOfAllClassesOnTheLaravelFolders implements ReversableAction
{
    private $folders = ["app", "bootstrap", "config"];

    private Filesystem $fileSystem;

    public function __construct(private Command $command, private string $name)
    {
        $this->fileSystem = app(Filesystem::class);
    }

    public function execute(): bool
    {
        foreach ($this->folders as $folder)
        {
            $this->fileSystem->copyDirectory($folder, "$folder.bak");

            foreach ($this->fileSystem->allFiles($folder) as $file)
            {
                if(str_contains($file, "cache")) continue;

                $count = 0;

                $success = $this->fileSystem->put(
                    $file,
                    str_replace("App\\", "App\\$this->name\\", $this->fileSystem->get($file), $count)
                );

                if(!!!$success)
                {
                    $this->rollback();

                    return false;
                }

                if($count)
                {
                    $this->command->comment("    $file");
                }
            }
        }

        return true;
    }

    public function finish(): bool
    {
        $errors = false;

        foreach ($this->folders as $folder)
        {
            if(!!!$this->fileSystem->deleteDirectory("$folder.bak"))
                $errors = true;
        }

        return !!!$errors;
    }

    public function message(): string
    {
        return "Modifying the namesace of all the classes in the files within the app folder...";
    }

    public function rollback(): bool
    {
        $errors = false;

        foreach ($this->folders as $folder)
        {
            if(!!!$this->fileSystem->moveDirectory("$folder.bak", "$folder", true))
                $errors = true;
        }

        return !!!$errors;
    }
}
