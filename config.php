<?php

return [

    /**
     * Enable send errors to Errbit
     */
    'enabled'               => env('ERRBIT_ENABLED', false),

    /**
     * Errbit API key
     */
    'api_key'               => env('ERRBIT_API_KEY', ''),

    /**
     * Ignore environment according to conf, example: local, testing, etc
     */
    'ignore_environments'   => [],

    /**
     * Connection to Errbit server
     */
    'connection'            => [
        'host'      => env('ERRBIT_URL', 'errbit.app'),
        'port'      => env('ERRBIT_PORT', '443'),
        'secure'    => true,
    ],

    /**
     * Send to Errbit data the current user
     */
    'user' => [
        'enabled' => true,

        /**
         * Specific attributes user model to send, if empty send all attributes without guard
         */
        'attributes' => [],

        /**
         * Send attributes when the user is guest
         */
        'guest' => [
            'data' => ['name' => 'guest', 'type' => 'guest']
        ]
    ]
];