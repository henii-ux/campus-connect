<?php
// Enable CORS for local frontend access (adjust IP/port if needed)
// REQUIRED headers
header("Access-Control-Allow-Origin: http://127.0.0.1:5501");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");
session_start();


// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "User not logged in"
    ]);
    exit;
}

// Database connection (update with your DB config)
$host = "localhost";
$dbname = "your_database_name";
$username = "your_db_user";
$password = "your_db_password";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed"
    ]);
    exit;
}

// Get user input from POST
$user_id   = $_SESSION['user_id'];
$name      = $_POST['name'] ?? '';
$birthdate = $_POST['birthdate'] ?? '';
$gender    = $_POST['gender'] ?? '';
$phone     = $_POST['phone'] ?? '';
$city      = $_POST['city'] ?? '';

// Basic validation (optional, but helpful)
if (empty($name) || empty($birthdate) || empty($gender) || empty($phone) || empty($city)) {
    echo json_encode([
        "success" => false,
        "message" => "All fields are required."
    ]);
    exit;
}

// Update query
$sql = "UPDATE users SET name = ?, birthdate = ?, gender = ?, phone = ?, city = ? WHERE id = ?";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([$name, $birthdate, $gender, $phone, $city, $user_id]);

    echo json_encode([
        "success" => true,
        "message" => "Profile updated successfully"
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to update profile: " . $e->getMessage()
    ]);
}
?>
