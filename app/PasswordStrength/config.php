<?php

declare(strict_types=1);

return [
    'min_length' => (int) getenv('PASSWORD_MIN_LENGTH'),
    'strength_length' => (int) getenv('PASSWORD_STRENGTH_LENGTH'),
    'min_acceptable_level' => getenv('PASSWORD_MIN_ACCEPTABLE_LEVEL'),
];
