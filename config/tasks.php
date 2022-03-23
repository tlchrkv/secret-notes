<?php

declare(strict_types=1);

return [
    'migrate' => [
        'desc' => 'Execute migrations',
        'namespace' => 'App\SharedKernel\Tasks',
        'slug' => 'run-migration',
        'action' => 'main',
    ],
    'delete-overdue-notes' => [
        'desc' => 'Delete notes where storage time expired',
        'namespace' => 'App\Notes\Console',
        'slug' => 'delete-overdue-notes',
        'action' => 'main',
    ]
];
