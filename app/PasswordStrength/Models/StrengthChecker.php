<?php

declare(strict_types=1);

namespace App\PasswordStrength\Models;

final class StrengthChecker
{
    public $minLength;
    public $strengthLength;
    public $minAcceptableLevel;

    public function __construct(int $minLength, int $strengthLength, StrengthLevel $minAcceptableLevel)
    {
        $this->minLength = $minLength;
        $this->strengthLength = $strengthLength;
        $this->minAcceptableLevel = $minAcceptableLevel;
    }

    /**
     * @throws WeakPassword
     */
    public function check(string $password): void
    {
        if (!$this->isAcceptable($password)) {
            throw new WeakPassword();
        }
    }

    public function isAcceptable(string $password): bool
    {
        return $this->getStrengthLevel($password)->toNumber() >= $this->minAcceptableLevel->toNumber();
    }

    public function getStrengthLevel(string $password): StrengthLevel
    {
        $complexityLevel = $this->checkComplexity($password);
        $lengthLevel = $this->checkLength($password);

        if ($complexityLevel->toNumber() < $lengthLevel->toNumber()) {
            return $complexityLevel;
        }

        return $lengthLevel;
    }

    private function checkComplexity(string $password): StrengthLevel
    {
        $anyLetter = '/^[a-zA-Z]+$/';
        $onlyDigits = '/^[0-9]+$/';
        $onlyNonWordSymbols = '/^[\W]+$/';
        $anyLetterOrDigit = '/^[a-zA-Z0-9]+$/';
        $anyLetterOrNonWordSymbol = '/^[a-zA-Z\W]+$/';
        $digitOrNonWordSymbol = '/^[0-9\W]+$/';
        $lowerCaseLetterOrDigitOrNonWordSymbol = '/^[a-z0-9\W]+$/';
        $upperCaseLetterOrDigitOrNonWordSymbol = '/^[A-Z0-9\W]+$/';
        $anyLetterOrDigitOrNonWordSymbol = '/^[\w\W]+$/';

        switch (true) {
            case $this->isMatch($anyLetter, $password):
            case $this->isMatch($onlyDigits, $password):
            case $this->isMatch($onlyNonWordSymbols, $password):
            default:
                return StrengthLevel::weak();
            case $this->isMatch($anyLetterOrDigit, $password):
            case $this->isMatch($anyLetterOrNonWordSymbol, $password):
            case $this->isMatch($digitOrNonWordSymbol, $password):
            case $this->isMatch($lowerCaseLetterOrDigitOrNonWordSymbol, $password):
            case $this->isMatch($upperCaseLetterOrDigitOrNonWordSymbol, $password):
                return StrengthLevel::medium();
            case $this->isMatch($anyLetterOrDigitOrNonWordSymbol, $password):
                return StrengthLevel::high();
        }
    }

    private function checkLength(string $password): StrengthLevel
    {
        switch (true) {
            case strlen($password) < $this->minLength:
            default:
                return StrengthLevel::weak();
            case strlen($password) < $this->strengthLength:
                return StrengthLevel::medium();
            case strlen($password) >= $this->strengthLength:
                return StrengthLevel::high();
        }
    }

    private function isMatch(string $pattern, string $value): bool
    {
        return 1 === preg_match($pattern, $value);
    }
}
