<?php
session_start();


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

require 'db_connection.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];


    if (!is_numeric($user_id)) {
        die("Invalid ID.");
    }

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }


    header("Location: users.php?deleted=1");
    exit();
} else {
    echo "No user ID provided.";
    exit();
}
?>
