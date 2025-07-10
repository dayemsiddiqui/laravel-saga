<?php

namespace dayemsiddiqui\Saga\Tests;

use dayemsiddiqui\Saga\SagaServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'dayemsiddiqui\\Saga\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            SagaServiceProvider::class,
            \Illuminate\Queue\QueueServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $migration = include __DIR__.'/../database/migrations/create_saga_runs_table.php';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_saga_steps_table.php';
        $migration->up();
    }
}
