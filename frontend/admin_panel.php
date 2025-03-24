<?php
// Підключення до конфігурації бази даних
require_once '../backend/config.php';

// Список дозволених таблиць
$allowedTables = ['news', 'documents', 'teachers', 'vryaduvannya']; // Додайте свої таблиці

// Отримуємо список таблиць з бази
$sql = "SHOW TABLES";
$result = $pdo->query($sql);
$tables = [];

if ($result) {
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0]; // Беремо назву таблиці
    }
} else {
    die("Помилка отримання таблиць.");
}

// Вибір таблиці (перевірка на допустимі значення)
$table = $_GET['table'] ?? ($tables[0] ?? '');
if (!in_array($table, $allowedTables)) {
    die("Некоректна таблиця.");
}

// Отримуємо дані з вибраної таблиці
$sql = "SELECT * FROM `$table`";
$table_data = $pdo->query($sql);

// Отримуємо список стовпців таблиці
$columns = [];
$columnsQuery = $pdo->query("DESCRIBE `$table`");
if ($columnsQuery) {
    while ($column = $columnsQuery->fetch(PDO::FETCH_ASSOC)) {
        $columns[] = $column['Field'];
    }
}
?>

<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати дані</title>
    <link rel="stylesheet" href="../frontend/styles.css">
</head>

<body>
    <button onclick="window.location.href='index.html';">На головну</button>

    <h1>Інтерфейс редагування даних</h1>

    <h3>Виберіть таблицю для редагування:</h3>
    <form method="GET">
        <select name="table" onchange="this.form.submit()">
            <?php foreach ($tables as $table_name): ?>
                <option value="<?= htmlspecialchars($table_name) ?>" <?= $table === $table_name ? 'selected' : '' ?>>
                    <?= htmlspecialchars($table_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <h3>Таблиця: <?= htmlspecialchars($table) ?></h3>

    <table border="1">
        <thead>
            <tr>
                <?php foreach ($columns as $column): ?>
                    <th><?= htmlspecialchars($column) ?></th>
                <?php endforeach; ?>
                <th>Дії</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $table_data->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <?php foreach ($columns as $column): ?>
                        <td><?= htmlspecialchars($row[$column] ?? '') ?></td>
                    <?php endforeach; ?>
                    <td>
                        <a href="../backend/edit.php?id=<?= $row['id'] ?>&table=<?= $table ?>">Редагувати</a> |
                        <a href="../backend/delete.php?id=<?= $row['id'] ?>&table=<?= $table ?>">Видалити</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <br>
    <h3>Додати новий запис:</h3>
    <form action="../backend/insert.php" method="POST">
        <input type="hidden" name="table" value="<?= htmlspecialchars($table) ?>">

        <?php foreach ($columns as $column): ?>
            <?php if ($column !== 'id'): ?> <!-- ID зазвичай автоінкрементний -->
                <label for="<?= $column ?>">Поле <?= htmlspecialchars($column) ?>:</label>
                <input type="text" name="<?= $column ?>" required><br><br>
            <?php endif; ?>
        <?php endforeach; ?>

        <button type="submit">Додати</button>
    </form>

</body>

</html>