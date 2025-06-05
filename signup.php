<?php
require 'db.php';

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password_raw = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$role = $_POST['role'] ?? '';
$department = $_POST['department'] ?? 'General';

if (!$name || !$email || !$password_raw || !$role || !$confirm_password) {
    http_response_code(400);
    echo json_encode(["message" => "Missing required fields"]);
    exit;
}

if ($password_raw !== $confirm_password) {
    http_response_code(400);
    echo json_encode(["message" => "Passwords do not match"]);
    exit;
}

$password = password_hash($password_raw, PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO users (name, email, password, role, department) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $password, $role, $department);

if ($stmt->execute()) {
    echo json_encode(["message" => "Signup successful"]);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Error: " . $stmt->error]);
}
$stmt->close();
?>
