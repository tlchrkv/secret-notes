<?php

declare(strict_types=1);

return [
    'code_length' => (int) getenv('NOTE_CODE_LENGTH'),
    'symbols_limit' => (int) getenv('NOTE_SYMBOLS_LIMIT'),
    'views_limit' => (int) getenv('NOTE_VIEWS_LIMIT'),
    'storage_time_limit_in_days' => (int) getenv('NOTE_STORAGE_TIME_LIMIT_IN_DAYS'),
];
