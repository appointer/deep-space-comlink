<?php

namespace Appointer\DeepSpaceComlink\Safari;

use Appointer\DeepSpaceComlink\Certificate;
use ErrorException;
use ZipArchive;

class PackageGenerator
{
    /**
     * @var \Appointer\DeepSpaceComlink\Certificate
     */
    protected $certificate;

    /**
     * @var string
     */
    protected $basePushPackagePath;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $websiteName;

    /**
     * @var string
     */
    protected $websitePushId;

    /**
     * Construct.
     *
     * @param \Appointer\DeepSpaceComlink\Certificate $certificate
     * @param string $basePushPackagePath
     * @param string $host
     * @param string $websiteName
     * @param string $websitePushId
     */
    public function __construct(
        Certificate $certificate,
        string $basePushPackagePath,
        string $host,
        string $websiteName = '',
        string $websitePushId = ''
    ) {
        $this->certificate = $certificate;
        $this->basePushPackagePath = $basePushPackagePath;
        $this->host = $host;
        $this->websiteName = $websiteName;
        $this->websitePushId = $websitePushId;
    }

    /**
     * Create a safari website push notification package for the given User.
     *
     * @param string $authenticationToken Identifier (something like a userId) to create package for.
     * @return \Appointer\DeepSpaceComlink\Safari\Package $package Package instance.
     */
    public function createPushPackageForIdentifier(string $authenticationToken): Package
    {
        $packageDir = sprintf('/%s/pushPackage%s', sys_get_temp_dir(), time());
        $package = $this->createPackage($packageDir, $authenticationToken);

        $this->generatePackage($package);

        return $package;
    }

    /**
     * @param Package $package
     * @throws ErrorException
     */
    private function generatePackage(Package $package)
    {
        $packageDir = $package->getPackageDir();
        $zipPath = $package->getZipPath();

        if (!is_dir($packageDir)) {
            mkdir($packageDir, 0777, true);
        }

        $this->copyPackageFiles($package);
        $this->createPackageManifest($package);
        $this->createPackageSignature($package);

        $zip = $this->createZipArchive();

        if (!$zip->open($zipPath, ZipArchive::CREATE)) {
            throw new ErrorException(sprintf('Could not open package "%s"', $zipPath));
        }

        $packageFiles = Package::$packageFiles;
        $packageFiles[] = 'manifest.json';
        $packageFiles[] = 'signature';

        foreach ($packageFiles as $packageFile) {
            $filePath = sprintf('%s/%s', $packageDir, $packageFile);

            if (!file_exists($filePath)) {
                throw new ErrorException(sprintf('File does not exist "%s"', $filePath));
            }

            $zip->addFile($filePath, $packageFile);
        }

        if (false === $zip->close()) {
            throw new ErrorException(sprintf('Could not save package "%s"', $zipPath));
        }
    }

    /**
     * @param Package $package
     */
    private function copyPackageFiles(Package $package)
    {
        $packageDir = $package->getPackageDir();

        mkdir($packageDir . '/icon.iconset');

        foreach (Package::$packageFiles as $rawFile) {
            $filePath = sprintf('%s/%s', $packageDir, $rawFile);

            copy(sprintf('%s/%s', $this->basePushPackagePath, $rawFile), $filePath);

            if ($rawFile === 'website.json') {
                $websiteJson = file_get_contents($filePath);
                $websiteJson = str_replace('{{ authenticationToken }}', $package->getAuthenticationToken(), $websiteJson);
                $websiteJson = str_replace('{{ host }}', $this->host, $websiteJson);
                $websiteJson = str_replace('{{ websiteName }}', $this->websiteName, $websiteJson);
                $websiteJson = str_replace('{{ websitePushId }}', $this->websitePushId, $websiteJson);
                file_put_contents($filePath, $websiteJson);
            }
        }
    }

    /**
     * @param Package $package
     * @return string
     */
    private function createPackageManifest(Package $package)
    {
        return $this->createPackageManifester()->createManifest($package);
    }

    /**
     * @param Package $package
     * @return string
     */
    private function createPackageSignature(Package $package)
    {
        return $this->createPackageSigner()->createPackageSignature(
            $this->certificate, $package
        );
    }

    /**
     * @return PackageSigner
     */
    protected function createPackageSigner()
    {
        return new PackageSigner();
    }

    /**
     * @return PackageManifester
     */
    protected function createPackageManifester()
    {
        return new PackageManifester();
    }

    /**
     * @return ZipArchive
     */
    protected function createZipArchive()
    {
        return new ZipArchive();
    }

    /**
     * @param string $packageDir
     * @param string $authenticationToken
     * @return Package
     */
    protected function createPackage(string $packageDir, string $authenticationToken): Package
    {
        return new Package($packageDir, $authenticationToken);
    }
}
