<?php

namespace App\Console\Commands;

use Illuminate\Console\Command as BaseCommand;
use Illuminate\Log\Writer;

class Command extends BaseCommand
{
    protected function log($message)
    {
        $this->getLogger()->info("[{$this->signature}] - {$message}");
    }

    /**
     * @return \Illuminate\Log\Writer
     */
    protected function getLogger()
    {
        return app(Writer::class);
    }
}
