<?php

namespace Appointer\DeepSpaceComlink\Safari;

class PackageManifester
{
    /**
     * Generates a manifest JSON file and returns the path.
     *
     * @param \Appointer\DeepSpaceComlink\Safari\Package $package
     * @return string $manifestJsonPath
     */
    public function createManifest(Package $package): string
    {
        $manifestData = [];
        foreach (Package::$packageFiles as $rawFile) {
            $filePath = sprintf('%s/%s', $package->getPackageDir(), $rawFile);
            $manifestData[$rawFile] = sha1(file_get_contents($filePath));
        }

        $manifestJsonPath = sprintf('%s/manifest.json', $package->getPackageDir());
        $manifestJson = json_encode((object) $manifestData);

        file_put_contents($manifestJsonPath, $manifestJson);

        return $manifestJsonPath;
    }
}
