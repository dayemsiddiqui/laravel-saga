<?php

namespace dayemsiddiqui\Saga\Commands;

use Illuminate\Console\Command;

class SagaCommand extends Command
{
    public $signature = 'laravel-saga';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
