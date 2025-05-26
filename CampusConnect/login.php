<?php
require 'connection.php';

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    echo json_encode([
        "status" => "success",
        "role" => $user['role'],
        "name" => $user['name'],
        "email" => $user['email']
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
}
?>
