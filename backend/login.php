<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Підключення конфігурації
$config = require 'pass.php';
if (!isset($config['admin_password'])) {
    die("Помилка: Пароль адміністратора не знайдено в config.php");
}
$hashedPassword = $config['admin_password'];

// Ініціалізація сесійних змінних
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['blocked_until'])) {
    $_SESSION['blocked_until'] = null;
}

// Перевірка часу блокування
$blocked = false;
if ($_SESSION['blocked_until']) {
    $currentTime = time();
    if ($currentTime < $_SESSION['blocked_until']) {
        $blocked = true;
        $remaining = $_SESSION['blocked_until'] - $currentTime;
        $minutes = floor($remaining / 60);
        $seconds = $remaining % 60;
        $error = "Доступ заблоковано. Спробуйте знову через {$minutes} хв. {$seconds} сек.";
    } else {
        // Розблокування після завершення часу
        $_SESSION['blocked_until'] = null;
        $_SESSION['login_attempts'] = 0;
    }
}

// Перенаправлення, якщо вже авторизований
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: ../frontend/admin_panel.php");
    exit();
}

// Обробка форми
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$blocked) {
    $enteredPassword = $_POST['password'] ?? '';

    if (password_verify($enteredPassword, $hashedPassword)) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['login_attempts'] = 0;
        $_SESSION['blocked_until'] = null;
        header("Location: ../frontend/admin_panel.php");
        exit();
    } else {
        $_SESSION['login_attempts']++;

        if ($_SESSION['login_attempts'] >= 3) {
            $_SESSION['blocked_until'] = time() + 300; // 5 хвилин (300 секунд)
            $error = "Забагато невдалих спроб. Доступ заблоковано на 5 хвилин.";
        } else {
            $error = "Неправильний пароль!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід</title>
    <link rel="stylesheet" href="../frontend/styles.css">
</head>
<body>
    <h2>Вхід в панель адміністратора</h2>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <?php if (!$blocked): ?>
        <form method="POST">
            <label for="password">Пароль:</label>
            <input type="password" name="password" required>
            <button type="submit">Увійти</button>
        </form>
    <?php else: ?>
        <p>Форма входу тимчасово заблокована через надто багато невдалих спроб.</p>
    <?php endif; ?>

    <button onclick="window.location.href='../frontend/for_teacher.html';">Назад</button>
</body>
</html>
