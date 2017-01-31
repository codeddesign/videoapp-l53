<?php

namespace Tests;

use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * The list of database tables.
     *
     * @var array
     */
    protected static $tables = [];

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    public function prepareDb()
    {
        if (defined('TESTS_DB_PREPARED')) {
            return;
        }

        define('TESTS_DB_PREPARED', true);

        /** @var DatabaseManager $db */
        $db = $this->app['db'];

        if (strpos($db->connection()->getDatabaseName(), 'testing') === false) {
            die("Tests should be ran on properly configured development machines only.\n");
        }

        if ($this->app['migrator']->repositoryExists()) {
            $db->statement('DROP SCHEMA public CASCADE');
            $db->statement('CREATE SCHEMA public');
        }

        $this->artisan('migrate');

        static::$tables = $db->table('information_schema.tables')->where('table_schema', 'public')->get()->pluck('table_name');
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()
        );
    }
}
