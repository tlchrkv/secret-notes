<?php

declare(strict_types=1);

namespace App\TextEncryption\Models;

use App\SharedKernel\Structs\ValueObject;

final class InitVector extends ValueObject
{
    public static function generate(int $length): self
    {
        return new self(openssl_random_pseudo_bytes($length));
    }
}
