<?php
// Database connection parameters
$host = "localhost";
$user = "root";
$pass = "";
$db   = "student_db";

// Establish a new MySQLi connection
$conn = new mysqli($host, $user, $pass, $db);

// Set charset to UTF-8 for better compatibility with various character sets
$conn->set_charset("utf8mb4");

// Improved connection error handling:
// If the connection fails, terminate the script and display an error message.
if ($conn->connect_error) {
    die("<div class='alert alert-danger'>Database connection failed: " . htmlspecialchars($conn->connect_error) . "</div>");
}
?>
