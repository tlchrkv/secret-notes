<?php

declare(strict_types=1);

return [
    'app_env' => getenv('APP_ENV'),
    'app_name' => getenv('APP_NAME'),
    'notes' => include __DIR__ . '/../app/Notes/config.php',
    'password_strength' => include __DIR__ . '/../app/PasswordStrength/config.php',
    'text_encryption' => include __DIR__ . '/../app/TextEncryption/config.php',
];
