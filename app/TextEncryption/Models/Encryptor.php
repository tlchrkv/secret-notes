<?php

declare(strict_types=1);

namespace App\TextEncryption\Models;

final class Encryptor
{
    private $initVectorLength;

    public function __construct(int $initVectorLength)
    {
        $this->initVectorLength = $initVectorLength;
    }

    public function __invoke(string $text, string $algorithm, string $passphrase): Cipher
    {
        $initVector = InitVector::generate($this->initVectorLength);
        $key = Key::fromPassphrase($passphrase);
        $rawCipher = openssl_encrypt(
            $text,
            $algorithm,
            $key->value,
            OPENSSL_RAW_DATA,
            $initVector->value
        );

        return new Cipher($rawCipher, $algorithm, $initVector->value);
    }
}
