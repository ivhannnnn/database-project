<?php
session_start();
require 'db_connection.php';

// Make sure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username'], $_POST['email'], $_POST['message'])) {
        $user_id = $_SESSION['user_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        // Include user_id and created_at in the insert
        $stmt = $conn->prepare("INSERT INTO user_feedback (user_id, username, email, message, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("isss", $user_id, $username, $email, $message);

        if ($stmt->execute()) {
            header("Location: customer_service_form.php?success=1");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: Missing form data.";
        echo "POST data: " . print_r($_POST, true);
    }
}

$conn->close();
?>
