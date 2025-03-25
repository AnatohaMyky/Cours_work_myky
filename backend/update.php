<?php
// Підключення до конфігурації бази даних
require_once 'config.php';

// Отримуємо дані з форми
$id = $_POST['id'];
$table = $_POST['table'];
$columns = array_keys($_POST);
$columns = array_diff($columns, ['id', 'table']); // Видаляємо "id" та "table"
$updates = [];

foreach ($columns as $column) {
    $updates[] = "$column = :$column";
}

$updates_str = implode(", ", $updates);

// Оновлення запису в таблиці
$sql = "UPDATE $table SET $updates_str WHERE id = :id";
$stmt = $pdo->prepare($sql);

// Додаємо дані для кожного стовпця
$params = ['id' => $id];
foreach ($columns as $column) {
    $params[":$column"] = $_POST[$column];
}

try {
    $stmt->execute($params);
    echo "Запис успішно змінено! <br><a href='../frontend/admin_panel.php?table=$table'>Назад на панель адміністратора</a>";
} catch (PDOException $e) {
    echo "Помилка: " . $e->getMessage();
}
?>
