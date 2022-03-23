<?php

declare(strict_types=1);

namespace App\PasswordStrength\Api\V1;

use App\PasswordStrength\Models\StrengthChecker;
use App\PasswordStrength\Models\StrengthLevel;
use App\SharedKernel\Http\Validation;

final class PasswordStrengthController extends \Phalcon\Mvc\Controller
{
    public function checkAction(): void
    {
        $validation = new Validation(['password' => 'required']);
        $validation->validate($_GET);

        $password = $_GET['password'];

        $strengthChecker = new StrengthChecker(
            $this->config['password_strength']['min_length'],
            $this->config['password_strength']['strength_length'],
            StrengthLevel::fromValue($this->config['password_strength']['min_acceptable_level'])
        );

        $this->response
            ->setStatusCode(200)
            ->setJsonContent([
                'is_acceptable' => $strengthChecker->isAcceptable($password),
                'strength_level' => $strengthChecker->getStrengthLevel($password)->value,
            ])
            ->send();
    }
}
