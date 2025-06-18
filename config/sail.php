<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Docker Configuration
    |--------------------------------------------------------------------------
    |
    | This is the configuration for Laravel Sail's Docker environment.
    |
    */

    'default' => env('SAIL_SERVICES', 'mysql,redis,mailpit'),

    'services' => [

        'mysql' => [
            'image' => 'mysql:8.0',
            'ports' => [
                '${FORWARD_DB_PORT:-3306}:3306',
            ],
            'environment' => [
                'MYSQL_ROOT_PASSWORD' => 'password',
                'MYSQL_ROOT_HOST' => '%',
                'MYSQL_DATABASE' => '${DB_DATABASE}',
                'MYSQL_USER' => '${DB_USERNAME}',
                'MYSQL_PASSWORD' => '${DB_PASSWORD}',
                'MYSQL_ALLOW_EMPTY_PASSWORD' => 1,
            ],
            'volumes' => [
                'sail-mysql:/var/lib/mysql',
                './docker/mysql/create-testing-database.sh:/docker-entrypoint-initdb.d/10-create-testing-database.sh',
            ],
            'networks' => [
                'sail',
            ],
            'healthcheck' => [
                'test' => ['CMD', 'mysqladmin', 'ping', '-p${DB_PASSWORD}'],
                'retries' => 3,
                'timeout' => 5,
            ],
        ],

        'pgsql' => [
            'image' => 'postgres:15',
            'ports' => [
                '${FORWARD_DB_PORT:-5432}:5432',
            ],
            'environment' => [
                'PGPASSWORD' => '${DB_PASSWORD:-secret}',
                'POSTGRES_DB' => '${DB_DATABASE}',
                'POSTGRES_USER' => '${DB_USERNAME}',
                'POSTGRES_PASSWORD' => '${DB_PASSWORD:-secret}',
            ],
            'volumes' => [
                'sail-pgsql:/var/lib/postgresql/data',
                './docker/pgsql/create-testing-database.sql:/docker-entrypoint-initdb.d/10-create-testing-database.sql',
            ],
            'networks' => [
                'sail',
            ],
            'healthcheck' => [
                'test' => ['CMD', 'pg_isready', '-q', '-d', '${DB_DATABASE}', '-U', '${DB_USERNAME}'],
                'retries' => 3,
                'timeout' => 5,
            ],
        ],

        'redis' => [
            'image' => 'redis:7.2-alpine',
            'ports' => [
                '${FORWARD_REDIS_PORT:-6379}:6379',
            ],
            'volumes' => [
                'sail-redis:/data',
            ],
            'networks' => [
                'sail',
            ],
            'healthcheck' => [
                'test' => ['CMD', 'redis-cli', 'ping'],
                'retries' => 3,
                'timeout' => 5,
            ],
        ],

        'mailpit' => [
            'image' => 'axllent/mailpit:latest',
            'ports' => [
                '${FORWARD_MAILPIT_PORT:-1025}:1025',
                '${FORWARD_MAILPIT_DASHBOARD_PORT:-8025}:8025',
            ],
            'networks' => [
                'sail',
            ],
        ],

        'selenium' => [
            'image' => 'selenium/standalone-chrome',
            'volumes' => [
                '/dev/shm:/dev/shm',
            ],
            'networks' => [
                'sail',
            ],
        ],

    ],

];
