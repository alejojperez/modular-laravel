<?php

namespace ModularLaravel\Actions;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use ModularLaravel\Helpers\Names;

class ModifyTheWebpackMixPaths implements ReversableAction
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
        $this->fileSystem->copy("webpack.mix.js", "webpack.mix.js.bak");
        $content = $this->fileSystem->get("webpack.mix.js");

        $regex = '/[\'|"]resources\/(.+)\.(js|css)[\'|"]/';
        $replace = "'".$newAppFolderPath.$slash."resources/$1.$2'";

        $content = preg_replace($regex, $replace, $content);

        return $this->fileSystem->put("webpack.mix.js", $content);
    }

    public function finish(): bool
    {
        return $this->fileSystem->delete("webpack.mix.js.bak");
    }

    public function message(): string
    {
        return "Modifying the webpack.mix.js file to load the new resources location instead...";
    }

    public function rollback(): bool
    {
        return
            $this->fileSystem->put("webpack.mix.js", $this->fileSystem->get("webpack.mix.js.bak"))
            &&
            $this->fileSystem->delete("webpack.mix.js.bak");
    }

    public function shouldFinishBefore(): bool
    {
        return false;
    }
}
