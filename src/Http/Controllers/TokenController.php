<?php

namespace Appointer\DeepSpaceComlink\Http\Controllers;

use Exception;

class TokenController
{
    /**
     * Respond to this request by saving the device token in a database that you can later reference when you send push notifications.
     * Also, change the user’s settings in your database to the values indicated by the parameterized dictionary for the device.
     * If you have an iOS app that sends push notifications, and users log in to your app with the same credentials
     * they use to log in to your website, set their website push notification settings to match
     * their existing iOS push notification settings.
     *
     * @param $version
     * @param $deviceToken
     * @param $websitePushId
     * @throws Exception
     */
    public function store($version, $deviceToken, $websitePushId)
    {
        throw new Exception('This is not yet implemented. Please overwrite \'' . TokenController::class . '@store\' and implement it yourself.');
    }

    /**
     * Use this authentication token to remove the device token from your database,
     * as if the device had never registered to your service.
     *
     * @param $version
     * @param $deviceToken
     * @param $websitePushId
     * @throws Exception
     */
    public function destroy($version, $deviceToken, $websitePushId)
    {
        throw new Exception('This is not yet implemented. Please overwrite \'' . TokenController::class . '@destroy\' and implement it yourself.');
    }
}