<?php

namespace Tests\Appointer\DeepSpaceComlink;

use Appointer\DeepSpaceComlink\ComlinkServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ComlinkServiceProvider::class,
        ];
    }

    protected function setUp()
    {
        parent::setUp();
        // Some changes to the container.
//        $this->app->instance('path.lang', __DIR__ . '/testfiles');
    }

    protected function getEnvironmentSetUp($app)
    {
        // Set some defaults.
//        $app['config']->set('app.locale', 'en');
//        $app['config']->set('app.fallback_locale', 'en');
    }
}