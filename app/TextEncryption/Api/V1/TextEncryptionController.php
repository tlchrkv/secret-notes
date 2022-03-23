<?php

declare(strict_types=1);

namespace App\TextEncryption\Api\V1;

use App\SharedKernel\Http\Validation;
use App\TextEncryption\Models\Cipher;
use App\TextEncryption\Models\Decryptor;
use App\TextEncryption\Models\Encryptor;

final class TextEncryptionController extends \Phalcon\Mvc\Controller
{
    public function encryptAction(): void
    {
        $validation = new Validation(['text' => 'required', 'passphrase' => 'required']);
        $validation->validate($_GET);

        $text = $_GET['text'];
        $passphrase = $_GET['passphrase'];

        $encryptor = new Encryptor($this->config['text_encryption']['init_vector_length']);
        $cipher = $encryptor($text, $this->config['text_encryption']['algorithm'], $passphrase);

        $this->response
            ->setStatusCode(200)
            ->setJsonContent([
                'cipher' => base64_encode($cipher->value),
                'init_vector' => base64_encode($cipher->initVector),
                'encoding' => 'base64',
            ])
            ->send();
    }

    public function decryptAction(): void
    {
        $validation = new Validation([
            'encoding' => 'required|equal:base64',
            'cipher' => 'required',
            'init_vector' => 'required',
            'passphrase' => 'required',
        ]);
        $validation->validate($_GET);

        $cipher = base64_decode($_GET['cipher']);
        $initVector = base64_decode($_GET['init_vector']);
        $passphrase = $_GET['passphrase'];

        $decryptor = new Decryptor();

        $this->response
            ->setStatusCode(200)
            ->setJsonContent([
                'text' => $decryptor(
                    new Cipher($cipher, $this->config['text_encryption']['algorithm'], $initVector),
                    $passphrase
                ),
            ])
            ->send();
    }
}
