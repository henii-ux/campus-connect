<?php
$host = 'sql111.infinityfree.com';
$db = 'if0_39047770_campusconnect';
$user = 'if0_39047770';
$pass = 'project2104';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
