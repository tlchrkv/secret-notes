<?php

declare(strict_types=1);

return [
    'add_note' => [
        'pattern' => '/api/v1/notes',
        'paths' => [
            'namespace' => 'App\Notes\Api\V1',
            'controller' => 'note',
            'action' => 'add',
        ],
        'httpMethods' => ['POST'],
    ],
    'add_encrypted_note' => [
        'pattern' => '/api/v1/encrypted-notes',
        'paths' => [
            'namespace' => 'App\Notes\Api\V1',
            'controller' => 'note',
            'action' => 'addEncrypted',
        ],
        'httpMethods' => ['POST'],
    ],
    'enable_note_auto_delete_on_views_limit_reached' => [
        'pattern' => '/api/v1/notes/{code}/auto-delete/on-views-limit-reached',
        'paths' => [
            'namespace' => 'App\Notes\Api\V1',
            'controller' => 'note',
            'action' => 'enableAutoDeleteOnViewsLimitReached',
        ],
        'httpMethods' => ['POST'],
    ],
    'enable_note_auto_delete_on_storage_time_expired' => [
        'pattern' => '/api/v1/notes/{code}/auto-delete/on-storage-time-expired',
        'paths' => [
            'namespace' => 'App\Notes\Api\V1',
            'controller' => 'note',
            'action' => 'enableAutoDeleteOnStorageTimeExpired',
        ],
        'httpMethods' => ['POST'],
    ],
    'get_note_by_code' => [
        'pattern' => '/api/v1/notes/{code}',
        'paths' => [
            'namespace' => 'App\Notes\Api\V1',
            'controller' => 'note',
            'action' => 'getByCode',
        ],
        'httpMethods' => ['GET'],
    ],
];
