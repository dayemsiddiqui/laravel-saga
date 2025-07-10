<?php

namespace dayemsiddiqui\Saga;

use dayemsiddiqui\Saga\Commands\SagaCommand;
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
            ->hasMigrations([
                '2025_07_10_144420_create_saga_runs_table',
                '2025_07_10_144427_create_saga_steps_table',
            ])
            ->hasCommand(SagaCommand::class);
    }
}
