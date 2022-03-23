<?php

declare(strict_types=1);

namespace App\TextEncryption\Models;

use App\SharedKernel\Structs\ValueObject;

final class Key extends ValueObject
{
    public static function fromPassphrase(string $passphrase): self
    {
        return new self(hash('sha256', $passphrase));
    }
}
