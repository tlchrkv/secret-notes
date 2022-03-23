<?php

declare(strict_types=1);

namespace App\TextEncryption\Models;

final class Decryptor
{
    /**
     * @throws DecryptionFailed
     */
    public function __invoke(Cipher $cipher, string $passphrase): string
    {
        $key = Key::fromPassphrase($passphrase);

        $plaintext = openssl_decrypt(
            $cipher->value,
            $cipher->algorithm,
            $key->value,
            OPENSSL_RAW_DATA,
            $cipher->initVector
        );

        if ($plaintext === false) {
            throw new DecryptionFailed();
        }

        return $plaintext;
    }
}
