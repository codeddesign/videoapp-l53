<?php

namespace App\Jobs;

use Illuminate\Log\Writer;

class Job
{
    protected function log($message)
    {
        $className = (new \ReflectionClass($this))->getShortName();
        $this->getLogger()->info("[{$className}] - {$message}");
    }

    /**
     * @return \Illuminate\Log\Writer
     */
    protected function getLogger()
    {
        return app(Writer::class);
    }
}
