<?php
session_start();
error_reporting(0);

$servername = "localhost";
$username = "root";
$password = "";
$database = "food_recipes";


$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Database connection failed.");
}


if (!isset($_POST['username'], $_POST['password'])) {
    echo "Missing required fields.";
    exit;
}

$username = $_POST['username'];
$password = $_POST['password'];


$sql = "SELECT id, username, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "SQL Error: " . $conn->error;
    exit;
}

$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();


if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $db_username, $hashed_password);
    $stmt->fetch();


    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $db_username;
        echo "success"; 
    } else {
        echo "Invalid credentials.";
    }
} else {
    echo "User not found.";
}

$stmt->close();
$conn->close();
?>
