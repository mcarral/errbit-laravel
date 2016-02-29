<?php

return [
    'enabled'               => env('ERRBIT_ENABLED', false),
    'api_key'               => env('ERRBIT_API_KEY', ''),
    'ignore_environments'   => [],
    'connection'            => [
        'host'      => 'errbit.app',
        'port'      => '443',
        'secure'    => true,
    ],
    'user' => [
        'enabled' => true,
        'attributes' => [],
        'guest' => [
            'data' => ['name' => 'guest', 'type' => 'guest']
        ]
    ]
];