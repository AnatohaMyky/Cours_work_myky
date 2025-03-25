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

            // Для булевих полів встановлюємо значення 1 чи 0
            if ($key === 'for_parents' || $key === 'for_teachers' || $key === 'for_students') {
                $value = isset($value) ? (int)$value : 0; // Преобразуємо значення до числа
            }

            // Якщо зображення порожнє, встановлюємо дефолтне
            if ($key === 'image' && empty($value)) {
                $value = 'default.jpg';
            }

            $columns[] = "`$key`"; // Використовуємо бектики для захисту назв стовпців
            $values[] = ":$key";
            $data[":$key"] = $value;
        }
    }

    // Перевірка даних
    echo '<pre>';
    print_r($data);
    echo '</pre>'; 


    // Перевіряємо, чи є хоч одне значення для вставки
    if (empty($columns)) {
        die('Немає даних для вставки');
    }

    // Формуємо SQL-запит
    $columns_str = implode(", ", $columns);
    $values_str = implode(", ", $values);

    // Перевірка запиту
    echo "SQL запит: INSERT INTO `$table` ($columns_str) VALUES ($values_str)<br>";

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
