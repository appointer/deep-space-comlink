<?php

namespace Appointer\DeepSpaceComlink;

class Certificate
{
    /**
     * @var string
     */
    private $certificateString;

    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $extraCert;

    /**
     * Construct.
     *
     * @param string $path
     * @param string $password
     * @param string $extraCert
     */
    public function __construct(string $path, ?string $password = null, ?string $extraCert = null)
    {
        $this->certificateString = file_get_contents($path);
        $this->password = $password;
        $this->extraCert = $extraCert;
    }

    /**
     * Gets the certificate string.
     *
     * @return string $certificateString
     */
    public function getCertificateString(): string
    {
        return $this->certificateString;
    }

    /**
     * Gets the certificate password.
     *
     * @return string $password
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Gets the path to the intermediate certificate.
     *
     * @return string
     */
    public function getExtraCert(): string
    {
        return $this->extraCert;
    }

    /**
     * Writes the certificate to the given file path.
     *
     * @param string $path
     */
    public function writeTo(string $path)
    {
        file_put_contents($path, $this->certificateString);
    }

    /**
     * Writes the certificate to a temporary file and returns the path.
     *
     * @return string $path
     */
    public function writeToTmp(): string
    {
        $path = tempnam(sys_get_temp_dir(), 'cert_');

        $this->writeTo($path);

        return $path;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->certificateString;
    }
}
