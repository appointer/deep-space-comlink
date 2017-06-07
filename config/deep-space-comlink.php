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
        'certificate' => [
            'intermediate' => '/path/to/extracert.pem',
            'path' => '/path/to/certificate.p12',
            'passphrase' => '',
        ],

        // Base which a pushpackage is created upon. It holds a template for the website.json as well as all icons.
        'template_path' => __DIR__ . '/../resources/pushpackage',
    ],
];