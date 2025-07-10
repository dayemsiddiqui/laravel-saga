<?php

namespace dayemsiddiqui\Saga;

use dayemsiddiqui\Saga\Commands\SagaCommand;
use Illuminate\Support\Facades\Route;
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

    public function packageBooted()
    {
        $this->registerRoutes();
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
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_saga_runs_table')
            ->hasMigration('create_saga_steps_table')
            ->hasCommand(SagaCommand::class);
    }

    protected function registerRoutes(): void
    {
        $config = config('saga.api');

        if (! $config['enabled']) {
            return;
        }

        Route::group([
            'prefix' => $config['prefix'],
            'middleware' => $config['middleware'],
            'as' => $config['name'].'.',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }
}
