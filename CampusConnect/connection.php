<?php
$host = "sql111.infinityfree.com";
$user = "if0_39047770";
$pass = "project2104";
$db = "if0_39047770_campusconnect";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
