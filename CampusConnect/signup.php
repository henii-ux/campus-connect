<?php
require 'connection.php';

header('Content-Type: application/json');

if (!isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['role'])) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];
$department = $_POST['department'] ?? null;

$stmt = $conn->prepare("INSERT INTO users (name, email, password, role, department) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $password, $role, $department);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>
