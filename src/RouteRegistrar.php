<?php

namespace Appointer\DeepSpaceComlink;

use Illuminate\Contracts\Routing\Registrar as Router;

class RouteRegistrar
{
    /**
     * The router implementation.
     *
     * @var Router
     */
    protected $router;

    /**
     * Create a new route registrar instance.
     *
     * @param  Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Register routes.
     *
     * @return void
     */
    public function all()
    {
        $this->forPushPackage();
        $this->forDeviceTokens();
        $this->forDebugLog();
    }

    /**
     * Register the routes needed for managing push packages.
     */
    public function forPushPackage()
    {
        $this->router->group([], function ($router) {
            $router->post('{version}/pushPackages/{websitePushId}', [
                'uses' => 'PackageController@show',
            ]);
        });
    }

    /**
     * Register the routes needed for device token handling.
     */
    public function forDeviceTokens()
    {
        $this->router->group([], function ($router) {
            $router->post('{version}/devices/{deviceToken}/registrations/{websitePushId}', [
                'uses' => 'TokenController@store',
            ]);

            $router->delete('{version}/devices/{deviceToken}/registrations/{websitePushId}', [
                'uses' => 'TokenController@destroy',
            ]);
        });
    }

    /**
     * Register the routes needed for log output.
     */
    public function forDebugLog()
    {
        $this->router->group([], function ($router) {
            $router->post('{version}/log', [
                'uses' => 'LogController@store',
            ]);
        });
    }
}