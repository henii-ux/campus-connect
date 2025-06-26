<?php
header('Content-Type: application/json');
require 'db.php'; // Your database connection

$email = $_POST['email'] ?? '';
$event_id = $_POST['event_id'] ?? '';

if (!$email || !$event_id) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

// Optional: Prevent duplicates
$stmt = $conn->prepare("SELECT * FROM event_participants WHERE email = ? AND event_id = ?");
$stmt->bind_param("si", $email, $event_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Already joined this event.']);
    exit;
}

// Insert into event_participants table
$stmt = $conn->prepare("INSERT INTO event_participants (email, event_id) VALUES (?, ?)");
$stmt->bind_param("si", $email, $event_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database insert failed']);
}
?>
