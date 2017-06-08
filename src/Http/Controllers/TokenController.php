<?php

namespace Appointer\DeepSpaceComlink\Http\Controllers;

use Appointer\DeepSpaceComlink\Events\WebPushSubscribed;
use Appointer\DeepSpaceComlink\Events\WebPushUnsubscribed;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;

class TokenController
{
    /**
     * The event dispatcher instance.
     *
     * @var Dispatcher
     */
    private $events;

    /**
     * TokenController constructor.
     *
     * @param Dispatcher $events
     */
    function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store($version, $deviceToken, $websitePushId, Request $request)
    {
        // Decrypt our user info.
        $userInfo = $this->extractUserInfo(
            $this->extractAuthenticationToken($request)
        );

        $this->events->dispatch(new WebPushSubscribed($version, $deviceToken, $websitePushId, $userInfo));

        // Return with an empty OK response.
        return response('');
    }

    /**
     * Use this authentication token to remove the device token from your database,
     * as if the device had never registered to your service.
     *
     * @param $version
     * @param $deviceToken
     * @param $websitePushId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($version, $deviceToken, $websitePushId, Request $request)
    {
        // Decrypt our user info.
        $userInfo = $this->extractUserInfo(
            $this->extractAuthenticationToken($request)
        );

        $this->events->dispatch(new WebPushUnsubscribed($version, $deviceToken, $websitePushId, $userInfo));

        // Return with an empty OK response.
        return response('');
    }

    /**
     * Extract the user information from auth token.
     *
     * @param string $authenticationToken
     * @return array
     */
    private function extractUserInfo(string $authenticationToken): array
    {
        try {
            $userInfo = json_decode(
                decrypt($authenticationToken),
                true /* extract as assoc array */
            );

            if ($userInfo === null) {
                throw new Exception('UserInfo is not in a valid JSON format.');
            }

            // Return successful decoded user info.
            return $userInfo;
        } catch (Exception $exc) {
            // Return the plain auth token in error case. This could be, because
            // the user decided to handle the token resolver by himself.
            return $authenticationToken;
        }
    }

    /**
     * Extract the authentication header token.
     *
     * @param Request $request
     * @return string
     * @throws Exception
     */
    private function extractAuthenticationToken(Request $request): string
    {
        $authenticationHeader = collect(
            explode(' ', $request->header('Authorization'))
        );

        // Check if the header was valid.
        if ($authenticationHeader->count() < 2) {
            throw new Exception('Received invalid authentication header.');
        }

        // The last part is the actuall value.
        return $authenticationHeader->last();
    }
}