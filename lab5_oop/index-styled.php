<?php

declare(strict_types=1);

require_once __DIR__ . '/BankAccount.php';
require_once __DIR__ . '/SavingsAccount.php';

$accounts = [];
$operations = [];

function recordOperation(array &$operations, string $title, callable $operation): void
{
    try {
        $message = $operation();
        $operations[] = ['type' => 'success', 'title' => $title, 'message' => $message];
    } catch (Exception $exception) {
        $operations[] = ['type' => 'error', 'title' => $title, 'message' => $exception->getMessage()];
    }
}

try {
    $mainAccount = new BankAccount('USD', 500.00);
    $secondAccount = new BankAccount('EUR', 150.00);
    $savingsAccount = new SavingsAccount('UAH', 10_000.00);

    recordOperation($operations, 'Поповнення основного рахунку на 250 USD', function () use ($mainAccount): string {
        $mainAccount->deposit(250.00);
        return 'Новий баланс: ' . number_format($mainAccount->getBalance(), 2, '.', ' ') . ' USD';
    });

    recordOperation($operations, 'Зняття 100 USD з основного рахунку', function () use ($mainAccount): string {
        $mainAccount->withdraw(100.00);
        return 'Новий баланс: ' . number_format($mainAccount->getBalance(), 2, '.', ' ') . ' USD';
    });

    recordOperation($operations, 'Нарахування 5% на накопичувальний рахунок', function () use ($savingsAccount): string {
        $savingsAccount->applyInterest();
        return 'Баланс після нарахування: ' . number_format($savingsAccount->getBalance(), 2, '.', ' ') . ' UAH';
    });

    recordOperation($operations, 'Спроба зняти 1 000 EUR', function () use ($secondAccount): string {
        $secondAccount->withdraw(1_000.00);
        return 'Операцію виконано';
    });

    recordOperation($operations, 'Спроба поповнення на -50 USD', function () use ($mainAccount): string {
        $mainAccount->deposit(-50.00);
        return 'Операцію виконано';
    });

    $accounts = [
        ['name' => 'Основний рахунок', 'account' => $mainAccount, 'note' => 'Поточний банківський рахунок'],
        ['name' => 'Другий рахунок', 'account' => $secondAccount, 'note' => 'Рахунок у євро'],
        ['name' => 'Накопичувальний', 'account' => $savingsAccount, 'note' => 'Ставка ' . SavingsAccount::getInterestRate() * 100 . '%'],
    ];
} catch (Exception $exception) {
    $operations[] = ['type' => 'error', 'title' => 'Створення рахунків', 'message' => $exception->getMessage()];
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Система банківських рахунків</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="container">
    <header class="hero">
        <div>
            <p class="eyebrow">Лабораторна робота №5</p>
            <h1>Банківські рахунки</h1>
            <p class="subtitle">Демонстрація інтерфейсів, спадкування, статичних властивостей, поліморфізму та обробки винятків у PHP.</p>
        </div>
        <div class="badge">PHP OOP</div>
    </header>

    <section class="accounts" aria-label="Банківські рахунки">
        <?php foreach ($accounts as $item): ?>
            <article class="card">
                <div class="card__top">
                    <p class="card__name"><?= htmlspecialchars($item['name']) ?></p>
                    <span class="currency"><?= htmlspecialchars($item['account']->getCurrency()) ?></span>
                </div>
                <p class="balance"><?= number_format($item['account']->getBalance(), 2, '.', ' ') ?></p>
                <p class="card__note"><?= htmlspecialchars($item['note']) ?></p>
            </article>
        <?php endforeach; ?>
    </section>

    <section class="operations">
        <h2>Журнал тестових операцій</h2>
        <div class="operation-list">
            <?php foreach ($operations as $operation): ?>
                <article class="operation operation--<?= $operation['type'] ?>">
                    <div class="operation__icon"><?= $operation['type'] === 'success' ? '✓' : '!' ?></div>
                    <div>
                        <p class="operation__title"><?= htmlspecialchars($operation['title']) ?></p>
                        <p class="operation__message"><?= htmlspecialchars($operation['message']) ?></p>
                    </div>
                    <span class="status"><?= $operation['type'] === 'success' ? 'Успішно' : 'Помилка' ?></span>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <p class="footer">BankAccount та SavingsAccount · Обробка операцій через Exception</p>
</main>
</body>
</html>
