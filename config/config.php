<?php
declare(strict_types=1);

return [
    'db' => [
        'host' => getenv('DB_HOST') ?: '172.26.192.1',
        'port' => getenv('DB_PORT') ?: '3309',
        'database' => getenv('DB_DATABASE') ?: 'app',
        'username' => getenv('DB_USERNAME') ?: 'newuser1',
        'password' => getenv('DB_PASSWORD') ?: 'newuser123qwe!',
        'charset' => 'utf8mb4',
    ],
];
