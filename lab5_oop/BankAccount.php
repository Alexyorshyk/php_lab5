<?php

declare(strict_types=1);

require_once __DIR__ . '/AccountInterface.php';

class BankAccount implements AccountInterface
{
    public const MIN_BALANCE = 0.0;

    protected float $balance;
    protected string $currency;

    public function __construct(string $currency = 'USD', float $initialBalance = 0.0)
    {
        $currency = strtoupper(trim($currency));

        if ($currency === '') {
            throw new Exception('Валюта рахунку не може бути порожньою');
        }

        if ($initialBalance < self::MIN_BALANCE) {
            throw new Exception('Початковий баланс не може бути негативним');
        }

        $this->currency = $currency;
        $this->balance = $initialBalance;
    }

    public function deposit(float $amount): void
    {
        $this->validateAmount($amount);
        $this->balance += $amount;
    }

    public function withdraw(float $amount): void
    {
        $this->validateAmount($amount);

        if ($this->balance - $amount < self::MIN_BALANCE) {
            throw new Exception('Недостатньо коштів');
        }

        $this->balance -= $amount;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    protected function validateAmount(float $amount): void
    {
        if (!is_finite($amount)) {
            throw new Exception('Сума має бути скінченним числом');
        }

        if ($amount <= 0) {
            throw new Exception('Сума операції має бути більшою за нуль');
        }
    }
}
