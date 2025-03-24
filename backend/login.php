<?php
session_start();

// Включаємо відображення помилок
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Забороняємо кешування
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Підключаємо пароль з конфігурації
$config = require 'pass.php';
if (!isset($config['admin_password'])) {
    die("Помилка: Пароль адміністратора не знайдено в config.php");
}
$hashedPassword = $config['admin_password'];

// Якщо вже авторизований, перенаправляємо
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: ../frontend/admin_panel.php");
    exit();
}

// Перевіряємо введення пароля
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredPassword = $_POST['password'] ?? '';

    if (password_verify($enteredPassword, $hashedPassword)) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: ../frontend/admin_panel.php");
        exit();
    } else {
        $error = "Неправильний пароль!";
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

    <form method="POST">
        <label for="password">Пароль:</label>
        <input type="password" name="password" required>
        <button type="submit">Увійти</button>
    </form>

    <button onclick="window.location.href='../frontend/forTeacher.html';">Назад</button>
</body>
</html>
