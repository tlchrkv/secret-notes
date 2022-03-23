<?php

declare(strict_types=1);

namespace App\SharedKernel;

final class StringConverter
{
    public static function snakeCaseToCamelCase(string $value): string
    {
        $result = str_replace(' ', '', ucwords(str_replace('_', ' ', $value)));
        $result[0] = strtolower($result[0]);

        return $result;
    }

    public static function snakeCaseToReadable(string $value, bool $upFirst = false): string
    {
        $result = str_replace('_', ' ', $value);

        return $upFirst ? ucfirst($result) : $result;
    }
}
