<?php
error_reporting(0);

$servername = "localhost";
$username = "root";
$password = "";
$database = "food_recipes";


$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Database connection failed.");
}


if (!isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['contact'], $_POST['birth_date'])) {
    echo "Missing required fields.";
    exit;
}

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$contact = $_POST['contact'];
$birth_date = $_POST['birth_date'];


$sql = "INSERT INTO users (username, email, password, contact_number, birth_date) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "SQL Error: " . $conn->error;
    exit;
}

$stmt->bind_param("sssss", $username, $email, $password, $contact, $birth_date);


if ($stmt->execute()) {
    echo "success";
} else {
    echo "Registration error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

