<?php

namespace Appointer\DeepSpaceComlink\Events;

abstract class BaseEvent
{
    /**
     * The version of the push client.
     *
     * @var string
     */
    public $version;

    /**
     * The token to address this client.
     *
     * @var string
     */
    public $deviceToken;

    /**
     * The website push identifier.
     *
     * @var string
     */
    public $websitePushId;

    /**
     * The userinfo dicationary as an associative array.
     *
     * @var string
     */
    public $userInfo;

    /**
     * Create a new event instance.
     *
     * @param $version
     * @param $deviceToken
     * @param $websitePushId
     * @param $userInfo
     */
    public function __construct($version, $deviceToken, $websitePushId, $userInfo)
    {
        $this->version = $version;
        $this->deviceToken = $deviceToken;
        $this->websitePushId = $websitePushId;
        $this->userInfo = $userInfo;
    }
}