<?php
require 'db.php'; // Use your existing PDO connection

header('Content-Type: application/json');

// Optional: limit results or add filtering in the future

try {
    $stmt = $pdo->prepare("SELECT id, title, category, description, date, time, location, capacity, featured, image_path FROM events ORDER BY date ASC");
    $stmt->execute();

    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $events
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
