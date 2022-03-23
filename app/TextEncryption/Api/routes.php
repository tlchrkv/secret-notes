<?php

declare(strict_types=1);

return [
    'encrypt_text' => [
        'pattern' => '/api/v1/encrypt-text',
        'paths' => [
            'namespace' => 'App\TextEncryption\Api\V1',
            'controller' => 'TextEncryption',
            'action' => 'encrypt',
        ],
        'httpMethods' => ['GET'],
    ],
    'decrypt_text' => [
        'pattern' => '/api/v1/decrypt-text',
        'paths' => [
            'namespace' => 'App\TextEncryption\Api\V1',
            'controller' => 'TextEncryption',
            'action' => 'decrypt',
        ],
        'httpMethods' => ['GET'],
    ],
];
