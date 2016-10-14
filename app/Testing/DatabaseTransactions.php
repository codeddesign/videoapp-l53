<?php

namespace App\Testing;

trait DatabaseTransactions
{
    /**
     * @before
     */
    public function beginDatabaseTransaction()
    {
        $this->prepareDb();

        $this->app['db']->beginTransaction();

        $this->beforeApplicationDestroyed(function () {
            $this->app['db']->rollBack();
        });
    }
}
