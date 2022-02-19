<?php

namespace ModularLaravel\Actions;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ModifyTheNamespaceOfAllClassesOnTheDatabaseFolder implements ReversableAction
{
    private Filesystem $fileSystem;

    public function __construct(private Command $command, private string $name)
    {
        $this->fileSystem = app(Filesystem::class);
    }

    public function execute(): bool
    {
        $this->fileSystem->copyDirectory("database", "database.bak");

        foreach ($this->fileSystem->allFiles("database") as $file)
        {
            $count = 0;

            $success = $this->fileSystem->put(
                $file,
                str_replace(
                    ["namespace Database\\", "use Database\\"],
                    "App\\$this->name\\Database\\",
                    $this->fileSystem->get($file),
                    $count
                )
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

        return true;
    }

    public function finish(): bool
    {
        return $this->fileSystem->deleteDirectory("database.bak");
    }

    public function message(): string
    {
        return "Modifying the namesace of all the classes in the files within the app folder...";
    }

    public function rollback(): bool
    {
        return $this->fileSystem->moveDirectory("database.bak", "database", true);
    }
}
