<?php

return [
    'seed_user' => [
        'name' => env('ADMIN_USER_NAME', 'HappyKiddos Admin'),
        'email' => env('ADMIN_USER_EMAIL', 'admin@happykiddos.ph'),
        'password' => env('ADMIN_USER_PASSWORD'),
    ],
];
