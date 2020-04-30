<?php

namespace RezuanKassim\BQAnalytic\Tests;

use Orchestra\Testbench\TestCase as TestBench;

abstract class TestCase extends TestBench
{
    use TestHelper;

    protected const TEST_APP_TEMPLATE = __DIR__ . '/../testbench/template';
    protected const TEST_APP = __DIR__ . '/../testbench/laravel';

    public static function setUpBeforeClass(): void
    {
        if (! file_exists(self::TEST_APP_TEMPLATE)) {
            self::setUpLocalTestbench();
        }
        parent::setUpBeforeClass();
    }

    protected function getBasePath()
    {
        return self::TEST_APP;
    }

    /**
     * Setup before each test.
     */
    public function setUp(): void
    {
        $this->installTestApp();

        parent::setUp();
        
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
        $this->withFactories(__DIR__ . '/../database/factories');
    }

    /**
     * Tear down after each test.
     */
    public function tearDown(): void
    {
        $this->uninstallTestApp();
        parent::tearDown();
    }

    /**
     * Tell Testbench to use this package.
     *
     * @param $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return ['RezuanKassim\BQAnalytic\BQAnalyticServiceProvider'];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
