<?php

declare(strict_types=1);

namespace App\PasswordStrength\Models;

use App\SharedKernel\Structs\Enum;

final class StrengthLevel extends Enum
{
    public static function weak(): self
    {
        return new self('weak');
    }

    public static function medium(): self
    {
        return new self('medium');
    }

    public static function high(): self
    {
        return new self('high');
    }

    public function toNumber(): int
    {
        if ($this == StrengthLevel::weak()) {
            return 1;
        }

        if ($this == StrengthLevel::medium()) {
            return 2;
        }

        return 3;
    }
}
