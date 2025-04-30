<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

require 'db_connection.php';

if (isset($_POST['feedback_id'])) {
    $feedback_id = $_POST['feedback_id'];
    $new_status = $_POST['new_status'] == 'open' ? 'closed' : 'open';

    $update_sql = "UPDATE customer_service_conversations SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $new_status, $feedback_id);

    if ($stmt->execute()) {
        header("Location: user_feedback.php");
    } else {
        echo "Error updating feedback status!";
    }
} else {
    echo "No feedback ID specified!";
}
