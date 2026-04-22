<?php

return [
    'default' => env('BROADCAST_CONNECTION', 'pusher'),

    'connections' => [
        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_KEY', 'e575c1030bfa878c3e0d'),
            'secret' => env('PUSHER_SECRET', '6a82c26f25a581c2c693'),
            'app_id' => env('PUSHER_APP_ID', '2141568'),
            'options' => [
                'cluster' => env('PUSHER_CLUSTER', 'eu'),
                'useTLS' => true,
                'encrypted' => true,
            ],
            'client_options' => [
                'curl' => [
                    CURLOPT_CAINFO => 'C:\cacert.pem',
                ],
            ],
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],
    ],
];
