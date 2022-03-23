<?php

declare(strict_types=1);

namespace App\SharedKernel\Structs;

use App\SharedKernel\StringConverter;

abstract class Enum extends ValueObject
{
    /**
     * @throws \InvalidArgumentException
     */
    public static function fromValue(string $value): self
    {
        $methodName = StringConverter::snakeCaseToCamelCase($value);

        if (!method_exists(static::class, $methodName)) {
            throw new \InvalidArgumentException(sprintf('Passed value \'%s\' doesn\'t allowed here', $value));
        }

        return static::$methodName();
    }
}
