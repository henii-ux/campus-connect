<?php
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$name = $data['name'];
$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_BCRYPT);
$role = $data['role'];
$department = $data['department'] ?? 'General';

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
