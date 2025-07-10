<?php

namespace dayemsiddiqui\Saga\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeSagaStepCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:saga-step {name : The name of the saga step class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new saga step class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'SagaStep';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/saga-step.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Saga\Steps';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        return str_replace('{{ class }}', $class, $stub);
    }
}
