<?php

declare(strict_types=1);

namespace App\TextEncryption\Models;

final class Cipher
{
    public $value;
    public $algorithm;
    public $initVector;

    public function __construct(string $value, string $algorithm, string $initVector)
    {
        $this->value = $value;
        $this->algorithm = $algorithm;
        $this->initVector = $initVector;
    }
}
