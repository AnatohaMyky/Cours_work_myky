<?php
// Підключення до конфігурації бази даних
require_once 'config.php';

// Список дозволених таблиць
$allowedTables = ['news', 'documents', 'teachers', 'vryaduvannya']; // Додайте свої таблиці

// Отримуємо ID та таблицю з URL
$id = $_GET['id'] ?? null;
$table = $_GET['table'] ?? null;

// Перевірка параметрів
if (!$id || !is_numeric($id) || !in_array($table, $allowedTables)) {
    die('Некоректний запит');
}

// Видалення запису
$sql = "DELETE FROM `$table` WHERE id = :id";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([':id' => $id]);
    echo "Запис успішно видалено! <br><a href='../frontend/admin_panel.php?table=$table'>Назад на панель адміністратора</a>";
} catch (PDOException $e) {
    echo "Помилка: " . $e->getMessage();
}
?>
