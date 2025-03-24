<?php
// Підключення до конфігурації бази даних
require_once 'config.php';

// Список дозволених таблиць
$allowedTables = ['news', 'documents', 'teachers', 'vryaduvannya', 'documents_for_participants'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Перевіряємо, чи вказана таблиця
    $table = $_POST['table'] ?? null;
    
    if (!$table || !in_array($table, $allowedTables)) {
        die('Некоректна таблиця');
    }
    
    // Формуємо список стовпців і значень для вставки
    $columns = [];
    $values = [];
    $data = [];

    foreach ($_POST as $key => $value) {
        if ($key !== 'table') {
            // Якщо значення порожнє, встановлюємо NULL (крім зображення)
            if (empty($value) && $key !== 'image') {
                $value = null;
            }
            if ($key === 'image' && empty($value)) {
                $value = 'default.jpg'; // Дефолтне зображення
            }

            $columns[] = "`$key`"; // Використовуємо бектики для захисту назв стовпців
            $values[] = ":$key";
            $data[":$key"] = $value;
        }
    }

    // Перевіряємо, чи є хоч одне значення для вставки
    if (empty($columns)) {
        die('Немає даних для вставки');
    }

    // Формуємо SQL-запит
    $columns_str = implode(", ", $columns);
    $values_str = implode(", ", $values);

    $sql = "INSERT INTO `$table` ($columns_str) VALUES ($values_str)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        // Перенаправлення на панель адміністратора
        header("Location: ../frontend/admin_panel.php?table=$table");
        exit();
    } catch (PDOException $e) {
        echo "Помилка вставки: " . $e->getMessage();
    }
}
?>
