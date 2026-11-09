<?php

declare(strict_types=1);

interface AccountInterface
{
    public function deposit(float $amount): void;

    public function withdraw(float $amount): void;

    public function getBalance(): float;
}
