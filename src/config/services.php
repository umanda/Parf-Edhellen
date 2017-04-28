<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'facebook' => [
        'client_id' => env('AUTH_FACEBOOK_APP_ID'),
        'client_secret' => env('AUTH_FACEBOOK_APP_SECRET'),
        'redirect' => env('AUTH_FACEBOOK_REDIRECT'),
    ],

    'google' => [
        'client_id' => env('AUTH_GOOGLE_APP_ID'),
        'client_secret' => env('AUTH_GOOGLE_APP_SECRET'),
        'redirect' => env('AUTH_GOOGLE_REDIRECT'),
    ],

    'twitter' => [
        'client_id' => env('AUTH_TWITTER_APP_ID'),
        'client_secret' => env('AUTH_TWITTER_APP_SECRET'),
        'redirect' => env('AUTH_TWITTER_REDIRECT'),
    ]

];