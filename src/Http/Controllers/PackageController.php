<?php

namespace Appointer\DeepSpaceComlink\Http\Controllers;

use Appointer\DeepSpaceComlink\Safari\PackageGenerator;
use Illuminate\Http\Request;

class PackageController
{
    /**
     * When serving the push package, return application/zip for the Content-type header.
     *
     * @param $version
     * @param $websitePushId
     * @param Request $request
     * @param PackageGenerator $generator
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($version, $websitePushId, Request $request, PackageGenerator $generator)
    {
        // Our encrypted userInfo will be our auth token.
        $authenticationToken = app('dsc.authentication-token')($request->input());

        // Create the pushpackage.
        $package = $generator->createPushPackageForIdentifier($authenticationToken);

        // Return with the contents of the zip file.
        return response(file_get_contents($package->getZipPath()), 200, [
            'Content-type' => 'application/zip'
        ]);
    }
}