<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['message'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $message = $_POST['message'];

     
        $stmt = $conn->prepare("INSERT INTO user_feedback (username, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $message);

        if ($stmt->execute()) {
 
            header("Location: customer_service_form.php?success=1");
            exit();
        } else {
       
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {

        echo "Error: Missing form data. ";
        echo "POST data: " . print_r($_POST, true);
    }
}

$conn->close();
?>