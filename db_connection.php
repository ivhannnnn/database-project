<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


$servername = "localhost";
$username = "root";
$password = "";
$database = "food_recipes";


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>