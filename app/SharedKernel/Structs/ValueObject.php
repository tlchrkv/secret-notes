<?php

declare(strict_types=1);

namespace App\SharedKernel\Structs;

abstract class ValueObject implements \Stringable
{
    public $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
