<?php
// Підключення до конфігурації бази даних
require_once 'config.php';

// Список дозволених таблиць (щоб уникнути SQL-ін'єкцій)
$allowedTables = ['news', 'documents', 'teachers', 'vryaduvannya', 'documents_for_participants'];

// Перевірка параметрів
$id = $_GET['id'] ?? null;
$table = $_GET['table'] ?? null;

if (!$id || !is_numeric($id) || !in_array($table, $allowedTables)) {
    var_dump($id, $table, in_array($table, $allowedTables));
    die('Некоректний запит');    
}

// Отримуємо поточні дані для редагування
$sql = "SELECT * FROM `$table` WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die('Запис не знайдено');
}

// Виведення форми для редагування
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати запис</title>
</head>
<body>
    <h1>Редагувати запис</h1>
    <form action="update.php" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
        <input type="hidden" name="table" value="<?= htmlspecialchars($table) ?>">

        <?php foreach ($row as $column => $value): ?>
            <?php if ($column !== 'id'): ?>
                <label for="<?= htmlspecialchars($column) ?>">Поле <?= htmlspecialchars($column) ?>:</label>
                <input type="text" name="<?= htmlspecialchars($column) ?>" value="<?= htmlspecialchars($value) ?>" required><br><br>
            <?php endif; ?>
        <?php endforeach; ?>

        <button type="submit">Зберегти зміни</button>
    </form>
</body>
</html>
