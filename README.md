# deep space comlink <a href="https://circleci.com/gh/appointer/deep-space-comlink"><img src="https://circleci.com/gh/appointer/deep-space-comlink.svg?style=svg" alt="Build Status"></a>

This libary will help to integrate all necessary boilerplate to support web push notifications. 
The primary target is to integrate a nice API around the native macOS push posibillities through Safari.

### Other approaches and inspiration

It all started with the search for a feasible way to create Safari `*.pushpackages`. After hours and hours of searching we decided
to build our own solution for this issue. We found inspiration on some projects, mainly [jwage's php-apns](https://github.com/jwage/php-apns). Credits to him for his work.

## Installation

Navigate to your project and run the composer command:

```bash
composer require appointer/deep-space-comlink
```

The next step is to register the service provider:

```php
// config/app.php
'providers' => [
    ...
    \Appointer\DeepSpaceComlink\ComlinkServiceProvider::class,
];
```

Finally, you have to register the routes of this package:

```php
// app/Providers/RouteServiceProvider.php
public function boot()
{
    parent::boot();

    Appointer\DeepSpaceComlink\DeepSpaceComlink::routes();
}
```

**Notice** If you want to, you can publish the config or the template for the pushpackage. This gives you full control over icons and application title amongst other things. 
Use the following artisan command:

```bash
php artisan vendor:publish --provider="Appointer\DeepSpaceComlink\ComlinkServiceProvider" --tag="config"
php artisan vendor:publish --provider="Appointer\DeepSpaceComlink\ComlinkServiceProvider" --tag="pushpackage"
```

### Implementing the javascript

No worries, its a piece of cake. We are using `axios` as an example HTTP client. If you got a stock laravel frontend, 
you probably got it installed already. You just have to replace your current locale population with the following implementation:

```javascript
// Example is taken from https://developer.apple.com/library/content/documentation/NetworkingInternet/Conceptual/NotificationProgrammingGuideForWebsites/PushNotifications/PushNotifications.html

var p = document.getElementById("foo");
p.onclick = function() {
    // Ensure that the user can receive Safari Push Notifications.
    if ('safari' in window && 'pushNotification' in window.safari) {
        var permissionData = window.safari.pushNotification.permission('web.com.example.domain');
        checkRemotePermission(permissionData);
    }
};
 
var checkRemotePermission = function (permissionData) {
    if (permissionData.permission === 'default') {
        // This is a new web service URL and its validity is unknown.
        window.safari.pushNotification.requestPermission(
            'https://domain.example.com/dsc', // The web service URL.
            'web.com.example.domain',     // The Website Push ID.
            {}, // Data that you choose to send to your server to help you identify the user.
            checkRemotePermission         // The callback function.
        );
    }
    else if (permissionData.permission === 'denied') {
        // The user said no.
    }
    else if (permissionData.permission === 'granted') {
        // The web service URL is a valid push provider, and the user said yes.
        // permissionData.deviceToken is now available to use.
    }
};
```

## Testing

Tests can be executed using the command:

```bash
./vendor/bin/phpunit
```

## Contibuting

Every help is very welcome. Do you got an issue, or having a great idea for extending this project? 
Feel free to open a pull request or submit an issue.

If you file a bug report, your issue should contain a title and a clear description of the issue. You should also include as much relevant 
information as possible and a code sample that demonstrates the issue. The goal of a bug report is to make it easy for yourself - and others - 
to replicate the bug and develop a fix.

Please use the [issue tracker](https://github.com/appointer/deep-space-comlink/issues) to report issues.

## License

This library is open-sourced software licensed under the [MIT](https://github.com/appointer/deep-space-comlink/blob/master/LICENSE) license.