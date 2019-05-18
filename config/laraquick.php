<?php

return [
    'tests' => [
        // Class definitions
        'classes' => [
            'state' => 'Laraquick\Tests\State'
        ],
        'commands' => [
            // commands to run in the setUp method
            'set_up' => [
                // These would only run once in the test life cycle
                'once' => [
                    'migrate:fresh' => [
                        // options
                        '--path' => 'database/migrations/v1'
                    ],
                    'migrate' => [],
                    'db:seed' => []
                ],
                // These would always run before each test
                'always' => [

                ]
            ],
            // These would always run after each test
            'tear_down' => [

            ]
        ],
        // Headers to pass into every request
        'headers' => [],
        // Use jwt token based on the tymon/jwt-auth package
        'jwt' => true,
        // The path in the storage where responses are to be stored
        'storage_path' => 'test-responses',
        // The information of the users to create when setting up.
        // The instance is picked from config **auth.providers.users.model**
        'users' => [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'jdoe@email.com'
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'jane.doe@email.com'
            ]
        ],
    ],
    'websocket' => [
        // 0.0.0.0 <=> any ip address
        'allowed_ip_address' => '0.0.0.0',
        // The websocket controller
        'controller' => 'Laraquick\\Controllers\\WebSocketController',
        // port to run websocket on
        'port' => '8080'
    ]
];
