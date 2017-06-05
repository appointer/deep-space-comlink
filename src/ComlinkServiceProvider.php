<?php

namespace Appointer\DeepSpaceComlink;

use Appointer\DeepSpaceComlink\Safari\PackageGenerator;
use Illuminate\Support\ServiceProvider;

class ComlinkServiceProvider extends ServiceProvider
{
    /**
     * Lazy load service provider.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        // Publish pushpackge to modify icons and stuff.
        $this->publishes([
            __DIR__ . '/../resources/pushpackage' => resource_path('pushpackage'),
        ], 'pushpackage');

        // Publish package config.
        $this->publishes([
            __DIR__ . '/../config/deep-space-comlink.php' => config_path('deep-space-comlink.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/deep-space-comlink.php', 'deep-space-comlink');

        $this->registerPackager();
        $this->registerAuthenticationTokenResolver();
    }

    /**
     * Register the packager.
     *
     * @return void
     */
    protected function registerPackager()
    {
        $this->app->bind(PackageGenerator::class, function ($app) {
            return new PackageGenerator(
                $app['config']('deep-space-comlink.package.certificate'),
                $app['config']('deep-space-comlink.package.template_path'),
                $app['config']('app.url'),
                $app['config']('deep-space-comlink.website.name'),
                $app['config']('deep-space-comlink.website.name')
            );
        });
    }

    /**
     * Register a resolver for the authentication token used by the APNS.
     */
    protected function registerAuthenticationTokenResolver()
    {
        $this->app->bind(PackageGenerator::class, function ($app) {
            return bcrypt( $app['auth']->user()->id );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [PackageGenerator::class];
    }
}