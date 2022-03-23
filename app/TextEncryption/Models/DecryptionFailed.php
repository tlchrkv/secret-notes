<?php

declare(strict_types=1);

namespace App\TextEncryption\Models;

use App\SharedKernel\Exceptions\AccessDeniedException;

final class DecryptionFailed extends AccessDeniedException
{
    public function __construct()
    {
        parent::__construct('Decryption failed');
    }
}
