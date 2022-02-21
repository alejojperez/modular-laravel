<?php

namespace ModularLaravel\Commands;

use Illuminate\Console\Command;
use ModularLaravel\Actions\EditRoutesOnAnyFileWithinTheLaravelFolders;
use ModularLaravel\Actions\ModifyAppInTheBootstrapFile;
use ModularLaravel\Actions\ModifyComposerAutoloadPSR4;
use ModularLaravel\Actions\ModifyTheNamespaceOfAllClassesOnTheLaravelFolders;
use ModularLaravel\Actions\MoveFoldersAndFilesToTheNewAppFolder;
use ModularLaravel\Actions\ReversableAction;

class InstallCommand extends Command
{
    public $signature = 'modular-laravel:install {appName?}';

    public $description = 'Make the necessary changes to the project for it to work with the new organization.';

    public function handle(): int
    {
        if (! ! ! $this->confirm("Have you made a copy of the porject current stage before continuing (if somehting goes worng we might not be able to revert it)?")) {
            return self::SUCCESS;
        }

        $actions = [
            new ModifyComposerAutoloadPSR4(),
            new ModifyTheNamespaceOfAllClassesOnTheLaravelFolders($this, $this->data()["name"]),
            new EditRoutesOnAnyFileWithinTheLaravelFolders($this, $this->data()["name"]),

            // IMPORTANT: The action ModifyAppInTheBootstrapFile must run after the action
            // IMPORTANT: ModifyTheNamespaceOfAllClassesOnTheLaravelFolders becasue it
            // IMPORTANT: will change the application namesapce
            new ModifyAppInTheBootstrapFile($this->data()["name"]),

            // IMPORTANT: The last action must be when we move everything into the new folder
            // IMPORTANT: because all the other actions assume that the files are in the
            // IMPORTANT: default app folder that comes with Laravel
            new MoveFoldersAndFilesToTheNewAppFolder($this->data()["name"]),
        ];

        $this->runActions($actions);

        // TODO
        // test all the default artisan commands to work
        // edit phpunit.xml to refactor path to tests files
        // edit webpack.mix.js to refactor path to resources files

        // update docs:
        //// save before do install
        //// setup config for app and domain
        //// not recommended for existing apps
        //// explain that they need to register the DatabaseSeeder to the laravel container for the seeders to work: \ModularLaravel\ModularLaravelServiceProvider::postInstallWiring($this->app);

        $this->comment("Running [composer dump-autoload] commnad...");
        shell_exec("composer dump-autoload");

        return self::SUCCESS;
    }

    /**
     * @return string[]
     */
    protected function data(): array
    {
        return [
            "name" => $this->argument("appName") ?? "Default",
        ];
    }

    /**
     * @param ReversableAction[] $actions
     * @return bool
     */
    protected function runActions(array $actions): bool
    {
        $error = false;
        $currentAction = 0;
        $finishIndex = 0;

        for (/**/; $currentAction < count($actions); $currentAction++) {
            $this->comment($actions[$currentAction]->message());

            if($actions[$currentAction]->shouldFinishBefore())
            {
                $this->finishActions(array_slice($actions, $finishIndex, $currentAction));
                $finishIndex = $currentAction;
            }

            if (! ! ! $actions[$currentAction]->execute()) {
                $error = class_basename($actions[$currentAction]);

                break;
            }
        }

        if ($error) {
            $rollbackErrors = [];

            for (/**/; $currentAction >= 0; $currentAction--) {
                if (! ! ! $actions[$currentAction]->rollback()) {
                    $rollbackErrors[] = class_basename($actions[$currentAction]);
                }
            }

            $this->error("Error when trying to perform the installation: $error");

            if (count($rollbackErrors)) {
                $this->error("We could not rollback the following actions: ".implode(",", $rollbackErrors));
            }

            return false;
        } else {
            $this->finishActions(array_slice($actions, $finishIndex));
        }

        return true;
    }

    protected function finishActions(array $actions): bool
    {
        $finishErrors = [];

        for ($currentAction = 0; $currentAction < count($actions); $currentAction++) {
            if (! ! ! $actions[$currentAction]->finish()) {
                $finishErrors[] = get_class($actions[$currentAction]);
            }
        }

        if (count($finishErrors)) {
            $this->error("We could not finish the following actions: ".implode(", ", $finishErrors));

            return false;
        }

        return true;
    }
}
