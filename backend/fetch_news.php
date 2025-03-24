<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// Підключаємо базу
include "config.php";

try {
    // Отримуємо новини з бази
    $stmt = $pdo->prepare("SELECT id, image, title, DATE_FORMAT(date, '%Y-%m-%d') AS date, category, description, social_link FROM news ORDER BY date DESC LIMIT 40");
    $stmt->execute();
    $news = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Відправляємо JSON-відповідь
    echo json_encode($news, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo json_encode(["error" => "Помилка отримання новин: " . $e->getMessage()]);
}
?>
