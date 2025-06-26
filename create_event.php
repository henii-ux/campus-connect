<?php
require 'db.php'; // Include the PDO connection

header("Content-Type: application/json");

// Helper function for error response
function respond($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

// Validate and sanitize POST data
$title       = trim($_POST['title'] ?? '');
$category    = trim($_POST['category'] ?? '');
$description = trim($_POST['description'] ?? '');
$date        = trim($_POST['date'] ?? '');
$time        = trim($_POST['time'] ?? '');
$location    = trim($_POST['location'] ?? '');
$capacity    = intval($_POST['capacity'] ?? 0);
$featured    = isset($_POST['featured']) && $_POST['featured'] === 'true' ? 1 : 0;

// Validate required fields
if (!$title || !$description || !$date || !$time || !$location) {
    respond(false, "All required fields must be filled.");
}

// Optional: Validate date/time format
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    respond(false, "Invalid date format. Use YYYY-MM-DD.");
}
if (!preg_match('/^\d{2}:\d{2}$/', $time)) {
    respond(false, "Invalid time format. Use HH:MM.");
}
if ($capacity < 1 || $capacity > 10000) {
    respond(false, "Capacity must be between 1 and 10000.");
}

// Handle image upload
$image_path = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    $maxSize = 2 * 1024 * 1024; // 2MB

    if (!in_array($_FILES['image']['type'], $allowedTypes)) {
        respond(false, "Invalid image type. Only JPG, PNG, and WEBP are allowed.");
    }

    if ($_FILES['image']['size'] > $maxSize) {
        respond(false, "Image file too large. Max size is 2MB.");
    }

    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
    $targetFile = $uploadDir . $imageName;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        respond(false, "Failed to upload image.");
    }

    $image_path = $targetFile;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO events (title, category, description, date, time, location, capacity, featured, image_path)
        VALUES (:title, :category, :description, :date, :time, :location, :capacity, :featured, :image_path)
    ");

    $stmt->execute([
        ':title' => $title,
        ':category' => $category,
        ':description' => $description,
        ':date' => $date,
        ':time' => $time,
        ':location' => $location,
        ':capacity' => $capacity,
        ':featured' => $featured,
        ':image_path' => $image_path
    ]);

    respond(true, "Event created successfully.");
} catch (PDOException $e) {
    respond(false, "Database error: " . $e->getMessage());
}
