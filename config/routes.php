<?php

declare(strict_types=1);

return array_merge(
    include __DIR__ . '/../app/Notes/Api/routes.php',
    include __DIR__ . '/../app/PasswordStrength/Api/routes.php',
    include __DIR__ . '/../app/TextEncryption/Api/routes.php'
);
