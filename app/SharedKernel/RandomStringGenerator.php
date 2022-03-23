<?php

declare(strict_types=1);

namespace App\SharedKernel;

final class RandomStringGenerator
{
    private const SYMBOLS_COLLECTION = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const NUMBERS_COLLECTION = '0123456789';

    public static function generate(int $length): string
    {
        $input = self::NUMBERS_COLLECTION . self::SYMBOLS_COLLECTION;

        $inputLength = strlen($input);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomCharacter = $input[rand(0, $inputLength - 1)];
            $randomString .= $randomCharacter;
        }

        return $randomString;
    }
}
