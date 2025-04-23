<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if the form was submitted and if a file was uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        // Handle file upload
        $tmp = $_FILES['profile_image']['tmp_name'];
        $name = basename($_FILES['profile_image']['name']);
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $new_name = "user_" . $user_id . "." . $ext;
        $destination = "uploads/" . $new_name;

        // Check if the file is a valid image type (jpg, jpeg, png, gif)
        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($ext), $valid_extensions)) {
            // Attempt to move the uploaded file to the 'uploads/' directory
            if (move_uploaded_file($tmp, $destination)) {
                // Update the profile picture in the database
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
        // If no file was uploaded, redirect back with an error
        header("Location: profile.php?error=no_file_selected");
        exit;
    }
}
?>