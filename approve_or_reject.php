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
        $notification_message = "Your recipe has been approved by the admin!";
        $notification_type = 'approval';
    } elseif ($action == 'reject') {
   
        $sql = "UPDATE recipes SET status = 'rejected', approval_status = 'rejected' WHERE id = ?";
        $notification_message = "Your recipe has been rejected by the admin.";
        $notification_type = 'rejected';
    }

   
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
       
        $get_user_id_sql = "SELECT user_id FROM recipes WHERE id = ?";
        $get_user_id_stmt = $conn->prepare($get_user_id_sql);
        $get_user_id_stmt->bind_param("i", $recipe_id);
        $get_user_id_stmt->execute();
        $get_user_id_result = $get_user_id_stmt->get_result();
        
        if ($get_user_id_result->num_rows > 0) {
            $row = $get_user_id_result->fetch_assoc();
            $user_id = $row['user_id'];


            $insert_notification_sql = "INSERT INTO notifications (user_id, message, type, created_at, dismissed) VALUES (?, ?, ?, NOW(), 0)";
            $insert_notification_stmt = $conn->prepare($insert_notification_sql);
            $insert_notification_stmt->bind_param("iss", $user_id, $notification_message, $notification_type);
            $insert_notification_stmt->execute();
        }

      
        header("Location: posting_approval.php"); 
        exit();
    } else {
        echo "Failed to update recipe status.";
    }
}
?>
