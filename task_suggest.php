<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    echo json_encode([]);
    exit;
}
include "DB_connection.php";
$q = isset($_GET['q']) ? $_GET['q'] : '';
if (strlen($q) < 2) {
    echo json_encode([]);
    exit;
}
$stmt = $conn->prepare("SELECT title FROM tasks WHERE title LIKE ? OR description LIKE ? LIMIT 10");
$like = "%$q%";
$stmt->execute([$like, $like]);
$suggestions = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $suggestions[] = $row['title'];
}
echo json_encode($suggestions);