<?php

namespace ModularLaravel\Actions;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use ModularLaravel\Helpers\Names;

class ModifyAppInTheBootstrapFile implements ReversableAction
{
    public const BAK_FILENAME = "bootstrap".DIRECTORY_SEPARATOR."app.php.bak";

    private Filesystem $fileSystem;

    private string $newAppFolderPath;

    private string $newAppPath;

    public function __construct(private string $name)
    {
        $this->fileSystem = Storage::build(base_path());

        $srcName = config("modular-laravel.sourceFolderName");
        $appName = Names::app();
        $this->newAppFolderPath = $srcName.DIRECTORY_SEPARATOR.$appName.DIRECTORY_SEPARATOR.$name;
        $this->newAppPath = $srcName.DIRECTORY_SEPARATOR.$appName;
    }

    public function execute(): bool
    {
        $slash = DIRECTORY_SEPARATOR;
        $this->fileSystem->copy("bootstrap".$slash."app.php", "bootstrap".$slash."app.php.bak");
        $content = $this->fileSystem->get("bootstrap".$slash."app.php");

        if (! ! ! ($appContent = file_get_contents(__DIR__.$slash."..".$slash."stubs".$slash."Application.stub"))) {
            return false;
        }
        if (! ! ! $appContent = str_replace("__APP_NAMESPACE__", Names::app()."\\\\$this->name\\\\", $appContent)) {
            return false;
        }
        if (! ! ! $this->fileSystem->put($this->newAppPath.$slash."Application.php", $appContent)) {
            return false;
        }

        $content = preg_replace("/Illuminate\\\\Foundation\\\\Application/", Names::app()."\\Application", $content);

        $replacePaths =
            "// Adding the new app path\n".
            "\$app\n".
            "    ->useLangPath('".$this->newAppFolderPath.$slash."lang')\n".
            "    ->useDatabasePath('".$this->newAppFolderPath.$slash."Database')\n".
            "    ->useResourcePath('".$this->newAppFolderPath.$slash."resources')\n".
            "    ->useAppPath('".$this->newAppFolderPath."');\n".
            "\n".
            "$1";

        $content = preg_replace('/(return \$app;)/', $replacePaths, $content);

        return $this->fileSystem->put("bootstrap".$slash."app.php", $content);
    }

    public function finish(): bool
    {
        return $this->fileSystem->delete("bootstrap".DIRECTORY_SEPARATOR."app.php.bak");
    }

    public function message(): string
    {
        return "Modifying the bootstrap".DIRECTORY_SEPARATOR."app.php file to load our own Application instead...";
    }

    public function rollback(): bool
    {
        return
            $this->fileSystem->put("app.php", $this->fileSystem->get("bootstrap".DIRECTORY_SEPARATOR."app.php.bak"))
            &&
            $this->fileSystem->delete("bootstrap".DIRECTORY_SEPARATOR."app.php.bak")
            &&
            $this->fileSystem->delete($this->newAppPath.DIRECTORY_SEPARATOR."Application.php");
    }
}
