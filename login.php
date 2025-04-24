<?php
session_start();
error_reporting(0);


$servername = "localhost";
$username = "root";
$password = "";
$database = "food_recipes";


$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed.");
}


if (!isset($_POST['username'], $_POST['password'])) {
    echo "Missing credentials.";
    exit;
}

$username = $_POST['username'];
$password = $_POST['password'];


$sql = "SELECT id, username, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();


if ($stmt->num_rows === 1) {
    $stmt->bind_result($user_id, $user_name, $hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
     
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $user_name;
        echo "success";
    } else {
        echo "Incorrect password.";
    }
} else {
    echo "User not found.";
}

$stmt->close();
$conn->close();
?>