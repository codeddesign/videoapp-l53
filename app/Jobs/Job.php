<?php

namespace App\Jobs;

use Exception;
use Illuminate\Log\Writer;
use Illuminate\Redis\RedisManager;

class Job
{
    protected $id;

    public function __construct($id = null)
    {
        $this->id = $id ?? uniqid();
    }

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

    /**
     * @return bool Returns true if a lock was obtained, false otherwise.
     * @throws \Exception
     */
    protected function lockJob()
    {
        if (! $this->id) {
            throw new Exception('Tried to lock a job without a valid ID');
        }

        /** @var RedisManager $redis */
        $redis = app(RedisManager::class);

        $key = "lock.job.{$this->id}";

        $expiry = 60 * 10; //lock it for 10 minutes

        if (! $redis->get($key)) {
            $redis->setex($key, $expiry, 'locked');

            return true;
        }

        return false;
    }
}
