<?php

return [
    'website' => [
        // Name which is displayed in the notification.
        'name' => 'Your Website Name',

        // Push identifier to help the APNS resolve your application.
        'pushId' => 'web.com.company.website',
    ],

    'package' => [
        // Push certificate to sign the push package.
        'certificate' => '/path/to/certificate.pem',

        // Base which a pushpackage is created upon. It holds a template for the website.json as well as all icons.
        'template_path' => __DIR__ . '/../resources/pushpackage',
    ],
];