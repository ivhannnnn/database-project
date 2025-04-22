<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


$servername = "localhost";
$username = "root";
$password = "";
$database = "food_recipes";

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Optional: Set character set to utf8mb4 for better Unicode support
$conn->set_charset("utf8mb4");
?>