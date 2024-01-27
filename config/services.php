<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'isms' => [
        'login'  => env('ISMS_LOGIN'),
        'password' => env('ISMS_PASSWORD'),
        'from' => env('ISMS_FROM', '0'),
        'wsdl' => env('ISMS_WSDL', 'https://isms.center/soap'),
    ],

    'getsms' => [
        'login'  => env('GETSMS_LOGIN'),
        'password' => env('GETSMS_PASSWORD'),
        'host' => env('GETSMS_HOST', 'http://185.8.212.184/'),
    ],

    'rgl' => [
        'key' => env('BALANCE_KEY'),
        'promo_id' => env('BALANCE_PROMO_ID')
    ]

];
