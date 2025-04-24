<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

require 'db_connection.php';

if (isset($_POST['action']) && isset($_POST['recipe_id'])) {
    $action = $_POST['action'];
    $recipe_id = $_POST['recipe_id'];

    if ($action == 'approve') {
       
        $sql = "UPDATE recipes SET status = 'approved', approval_status = 'approved' WHERE id = ?";
    } elseif ($action == 'reject') {
    
        $sql = "UPDATE recipes SET status = 'rejected', approval_status = 'rejected' WHERE id = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {

        header("Location: posting_approval.php"); 
        exit();
    } else {
        echo "Failed to update recipe status.";
    }
}
?>