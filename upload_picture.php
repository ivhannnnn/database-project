<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
       
        $tmp = $_FILES['profile_image']['tmp_name'];
        $name = basename($_FILES['profile_image']['name']);
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $new_name = "user_" . $user_id . "." . $ext;
        $destination = "uploads/" . $new_name;


        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($ext), $valid_extensions)) {
            
            if (move_uploaded_file($tmp, $destination)) {
           
                $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                $stmt->bind_param("si", $new_name, $user_id);
                $stmt->execute();
                $stmt->close();
                header("Location: profile.php");
                exit;
            } else {
                header("Location: profile.php?error=upload_failed");
                exit;
            }
        } else {
            header("Location: profile.php?error=invalid_file_type");
            exit;
        }
    } else {
 
        header("Location: profile.php?error=no_file_selected");
        exit;
    }
}
?>