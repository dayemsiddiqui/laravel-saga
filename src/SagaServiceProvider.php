<?php

namespace dayemsiddiqui\Saga;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use dayemsiddiqui\Saga\Commands\SagaCommand;

class SagaServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-saga')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_saga_table')
            ->hasCommand(SagaCommand::class);
    }
}
