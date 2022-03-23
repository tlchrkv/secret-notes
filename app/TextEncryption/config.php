<?php

declare(strict_types=1);

return [
    'algorithm' => getenv('ENCRYPTION_ALGORITHM'),
    'init_vector_length' => (int) getenv('ENCRYPTION_INIT_VECTOR_LENGTH'),
];
