<?php

declare(strict_types=1);

namespace App\PasswordStrength\Models;

final class WeakPassword extends \DomainException
{
    public function __construct()
    {
        parent::__construct('The entered password is weak');
    }
}
