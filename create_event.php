<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once 'db.php';

// Get POST data
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$category = $_POST['category'] ?? '';
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';
$location = $_POST['location'] ?? '';
$capacity = $_POST['capacity'] ?? '';
$status = $_POST['status'] ?? 'upcoming';

if (empty($title) || empty($description) || empty($category) || empty($date) || empty($time) || empty($location) || empty($capacity)) {
    echo json_encode([
        'success' => false,
        'message' => 'All fields are required'
    ]);
    exit;
}

try {
    // Insert event
    $stmt = $pdo->prepare("
        INSERT INTO events (title, description, category, date, time, location, capacity, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([$title, $description, $category, $date, $time, $location, $capacity, $status]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Event created successfully',
        'event_id' => $pdo->lastInsertId()
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} 