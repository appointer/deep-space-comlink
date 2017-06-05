<?php

namespace Appointer\DeepSpaceComlink\Safari;

class Package
{
    /**
     * @var array
     */
    public static $packageFiles = [
        'icon.iconset/icon_16x16.png',
        'icon.iconset/icon_16x16@2x.png',
        'icon.iconset/icon_32x32.png',
        'icon.iconset/icon_32x32@2x.png',
        'icon.iconset/icon_128x128.png',
        'icon.iconset/icon_128x128@2x.png',
        'website.json',
    ];

    /**
     * A string that helps you identify the user. It is included in later requests to your web service.
     * This string must 16 characters or greater.
     *
     * @var string
     */
    private $authenticationToken;

    /**
     * @var string
     */
    private $packageDir;

    /**
     * @var string
     */
    private $zipPath;

    /**
     * @param string $packageDir
     * @param string $authenticationToken
     */
    public function __construct(string $packageDir, string $authenticationToken)
    {
        $this->packageDir = $packageDir;
        $this->authenticationToken = $authenticationToken;
        $this->zipPath = sprintf('%s.zip', $packageDir);
    }

    /**
     * Gets path to the zip package directory.
     *
     * @return string $packageDir
     */
    public function getPackageDir(): string
    {
        return $this->packageDir;
    }

    /**
     * Gets the authentication token.
     *
     * @return string $userId
     */
    public function getAuthenticationToken(): string
    {
        return $this->authenticationToken;
    }

    /**
     * Gets path to the zip package.
     *
     * @return string $zipPath
     */
    public function getZipPath(): string
    {
        return $this->zipPath;
    }
}
