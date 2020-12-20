<?php

declare(strict_types=1);

return [
    'config' => [
        'doctrine' => [
            'dev_mode' => true,
            'cache_dir' => './var/doctrine',
            'connection' => [
                'driver' => 'pdo_pgsql',
                'host' => 'db',
                'port' => 5432,
                'dbname' => 'slim',
                'user' => 'postgres',
                'password' => 'root',
                'charset' => 'utf-8'
            ],
            'metadata_dirs' => ['./app/Web/entities'],
        ],
    ],
];
