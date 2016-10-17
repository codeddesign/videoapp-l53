<?php

namespace App\Testing;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\DatabaseManager;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * The list of database tables.
     *
     * @var array
     */
    protected static $tables = [];

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    protected function setUp()
    {
        parent::setUp();
        $this->prepareDb();
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    public function prepareDb()
    {
        if (defined('TESTS_DB_PREPARED')) {
            return;
        }

        define('TESTS_DB_PREPARED', true);

        /** @var DatabaseManager $db */
        $db = $this->app['db'];

        if ($this->app['migrator']->repositoryExists()) {
            $db->statement('DROP SCHEMA public CASCADE');
            $db->statement('CREATE SCHEMA public');
        }

        $this->artisan('migrate');

        static::$tables = $db->table('information_schema.tables')->where('table_schema', 'public')->get()->pluck('table_name');
    }
}
