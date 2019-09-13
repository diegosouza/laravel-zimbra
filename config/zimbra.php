<?php

return [

    'host'      => env('ZIMBRA_HOST', 'localhost'),
    'domain'    => env('ZIMBRA_EMAIL_DOMAIN'),

    'api'   => [
        'user'      => env('ZIMBRA_API_USER'),
        'password'  => env('ZIMBRA_API_PASSWORD'),
    ]

];
