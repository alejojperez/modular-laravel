<?php

namespace ModularLaravel\ModularLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use ModularLaravel\ModularLaravel\Helpers\Names;

class InstallCommand extends Command
{
    public $signature = 'modular-laravel:install';

    public $description = 'Make the necessary cahnges to the project for it to work.';

    public function handle(): int
    {
//        if(!!!$this->confirm("Have you made a copy of the porject current stage before continuing (if somehting goes worng we can't revert it)?")) return self::SUCCESS;
//
//        $this->crateTheDefaultAppScalfolding();

        $this->modifyComposerAutoloadPSR4();

        // edit the bootstrap app.php
        // edit the config/app.php providers namespace
        // move all the app folder to the new default app
        // rename all that is within app, config, etc.. is replace by App\ --> App\Default\
        // move database to folder to default, remove psr-4 from composer, and rename all the folder to be capital
        // move lang to the correct place
        // move resources to the correct place and make sure to edit webpack config
        // move routes to the correct place and edit the route service provider
        // check all the other files on the root to make sure that they are correct
        // update docs:
        //// save before do install
        //// setup config for app and domain
        //// not recommended for existing apps

        $this->callComposerDumpAutoload();

        return self::SUCCESS;
    }

    /**
     * @return void
     */
    protected function callComposerDumpAutoload(): void
    {
        shell_exec("composer dump-autoload");
    }

    /**
     * @return void
     */
    protected function crateTheDefaultAppScalfolding(): void
    {
        $this->comment("Creating the default app...");
        if (self::SUCCESS !== Artisan::call("modular-laravel:make:app Default --empty"))
            throw new \RuntimeException("We could not create the default app to place all the default file that Laravel brings.");
    }

    protected function modifyComposerAutoloadPSR4(): void
    {
        $this->comment("Modifying the composer autoload PSR-4 section...");
        $content = json_decode(file_get_contents(base_path("composer.json")), true);

        $srcName = config("modular-laravel.sourceFolderName");
        $appName = Names::app();
        $domainName = Names::domain();
        $slash = DIRECTORY_SEPARATOR;

        $content["autoload"]["psr-4"] = [
            "$appName\\\\" => ".".$slash.$srcName.$slash.$appName.$slash,
            "$domainName\\\\" => ".".$slash.$srcName.$slash.$domainName.$slash,
        ];
        dd($content);
    }
}
