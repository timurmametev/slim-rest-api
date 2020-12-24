<?php

declare(strict_types=1);

return [
    'config' => [
        'doctrine' => [
            'dev_mode' => true,
            'cache_dir' => __DIR__ . '/../var/doctrine',
            'connection' => [
                'driver' => 'pdo_pgsql',
                'host' => 'db',
                'port' => 5432,
                'dbname' => 'slim',
                'user' => 'postgres',
                'password' => 'root',
                'charset' => 'utf-8'
            ],
            'metadata_dirs' => [__DIR__ . '/../app/Web/doctrine/entity'],
        ],
    ],
];
