<?php

declare(strict_types=1);

return [
    'adapter' => 'Postgresql',
    'host' => getenv('POSTGRES_HOST'),
    'username' => getenv('POSTGRES_USER'),
    'password' => getenv('POSTGRES_PASSWORD'),
    'dbname' => getenv('POSTGRES_DB'),
];
