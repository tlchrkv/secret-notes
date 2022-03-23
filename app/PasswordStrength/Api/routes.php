<?php

declare(strict_types=1);

return [
    'check_password_strength' => [
        'pattern' => '/api/v1/check-password-strength',
        'paths' => [
            'namespace' => 'App\PasswordStrength\Api\V1',
            'controller' => 'password_strength',
            'action' => 'check',
        ],
        'httpMethods' => ['GET'],
    ],
];
