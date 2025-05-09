<?php
session_start();
require 'db_connection.php';


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

        $stmt = $conn->prepare("INSERT INTO user_feedback (user_id, username, email, message, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("isss", $user_id, $username, $email, $message);

        if ($stmt->execute()) {
          
            $_SESSION['service_request_status'] = 'Your request has been successfully submitted!';
        } else {
     
            $_SESSION['service_request_status'] = 'There was an issue submitting your request. Please try again later.';
        }

        $stmt->close();
    } else {
 
        $_SESSION['service_request_status'] = 'Error: Missing form data.';
    }


    header("Location: customer_service_form.php");
    exit();
}

$conn->close();
?>
