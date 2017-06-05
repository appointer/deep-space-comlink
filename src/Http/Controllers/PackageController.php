<?php

namespace Appointer\DeepSpaceComlink\Http\Controllers;

use Appointer\DeepSpaceComlink\Safari\PackageGenerator;

class PackageController
{
    /**
     * When serving the push package, return application/zip for the Content-type header.
     *
     * @param $version
     * @param $websitePushId
     * @param PackageGenerator $generator
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($version, $websitePushId, PackageGenerator $generator)
    {
        // Create the pushpackage.
        $package = $generator->createPushPackageForIdentifier(app('dsc.authentication-token'));

        // Return with the contents of the zip file.
        return response(file_get_contents($package->getZipPath()), 200, [
            'Content-type' => 'application/zip'
        ]);
    }
}