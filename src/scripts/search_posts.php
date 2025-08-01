<?php
require "../config/conn_db.php";
header('Content-Type: text/html; charset=utf-8');

$query = $_GET['query'] ?? '';
$results = [];

if (strlen($query) >= 3) {
    $sql = "
        SELECT DISTINCT posts.title, comments.body AS comment
        FROM comments
        JOIN posts ON comments.post_id = posts.id
        WHERE LOWER(comments.body) LIKE LOWER(:query)
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':query' => '%' . $query . '%']);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (empty($results)) {
    echo "<p>Не найдено постов с комментариями, содержащими строку <span class='bold'>" . htmlspecialchars($query) . "</span>.</p>";
} else {
    echo "<h3>Записи с комментариями, содержащими строку <span class='bold'>" . htmlspecialchars($query) . "</span>:</h3>";
    echo "<ol>";
    foreach ($results as $row) {
        echo "<li><span class='bold'>" . htmlspecialchars($row['title']) . "</span><br>";
        echo "<em>" . nl2br(htmlspecialchars($row['comment'])) . "</em></li>";
    }
    echo "</ol>";
}
