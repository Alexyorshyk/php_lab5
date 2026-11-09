<?php

declare(strict_types=1);

require_once __DIR__ . '/BankAccount.php';

class SavingsAccount extends BankAccount
{
    protected static float $interestRate = 0.05;

    public static function setInterestRate(float $interestRate): void
    {
        if (!is_finite($interestRate) || $interestRate < 0) {
            throw new Exception('Відсоткова ставка не може бути негативною');
        }

        self::$interestRate = $interestRate;
    }

    public static function getInterestRate(): float
    {
        return self::$interestRate;
    }

    public function applyInterest(): void
    {
        $interest = $this->balance * self::$interestRate;

        if ($interest > 0) {
            $this->deposit($interest);
        }
    }
}
