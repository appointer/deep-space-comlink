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
            __DIR__ . '/../resources/pushpackage/' => resource_path('pushpackage'),
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
        $this->registerCertificate();
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
                $app['dsc.certificate'],
                $app['config']->get('deep-space-comlink.package.template_path'),
                $app['config']->get('app.url'),
                $app['config']->get('deep-space-comlink.website.name'),
                $app['config']->get('deep-space-comlink.website.pushId')
            );
        });
    }

    /**
     * Register the certificate handle.
     *
     * @return void
     */
    protected function registerCertificate()
    {
        $this->app->bind('dsc.certificate', function ($app) {
            return new Certificate(
                $app['config']->get('deep-space-comlink.package.certificate.path'),
                $app['config']->get('deep-space-comlink.package.certificate.passphrase'),
                $app['config']->get('deep-space-comlink.package.certificate.intermediate')
            );
        });
    }

    /**
     * Register a resolver for the authentication token used by the APNS.
     */
    protected function registerAuthenticationTokenResolver()
    {
        $this->app->bind('dsc.authentication-token', function ($app) {
            return function($userInfo) {
                return encrypt($userInfo);
            };
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            PackageGenerator::class,
            'dsc.authentication-token',
            'dsc.certificate',
        ];
    }
}