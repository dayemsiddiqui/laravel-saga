<?php

namespace dayemsiddiqui\Saga;

use dayemsiddiqui\Saga\Commands\MakeSagaStepCommand;
use dayemsiddiqui\Saga\Commands\SagaCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SagaServiceProvider extends PackageServiceProvider
{
    public function packageRegistered()
    {
        $this->app->singleton(Saga::class, function ($app) {
            return new Saga;
        });
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-saga')
            ->discoversMigrations()
            ->runsMigrations()
            ->hasCommand(SagaCommand::class)
            ->hasCommand(MakeSagaStepCommand::class)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishAssets()
                    ->askToRunMigrations()
                    ->copyAndRegisterServiceProviderInApp()
                    ->askToStarRepoOnGitHub('dayemsiddiqui/laravel-saga');
            });
    }
}
