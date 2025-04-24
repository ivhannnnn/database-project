<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $ingredients = trim($_POST['ingredients']);
    $steps = trim($_POST['steps']);
    $user_id = $_SESSION['user_id'];

    $image_path = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
        $uploadFileDir = './uploads/';
        $dest_path = $uploadFileDir . $newFileName;

        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true);
        }

        if (in_array($fileExtension, $allowedfileExtensions)) {
            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                $image_path = $dest_path;
            } else {
                echo "<script>alert('There was an error moving the uploaded file.'); window.location.href='upload_recipe.php';</script>";
                exit;
            }
        } else {
            echo "<script>alert('Upload failed. Allowed file types: " . implode(',', $allowedfileExtensions) . "'); window.location.href='upload_recipe.php';</script>";
            exit;
        }
    }

    
    $stmt = $conn->prepare("INSERT INTO recipes (user_id, title, description, ingredients, steps, image_path, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("isssss", $user_id, $title, $description, $ingredients, $steps, $image_path);

    if ($stmt->execute()) {
        echo "<script>alert('Recipe submitted for approval. It will appear after admin approval.'); window.location.href='explore_recipes.php';</script>";
    } else {
        echo "<script>alert('Error uploading recipe. Please try again.'); window.location.href='upload_recipe.php';</script>";
    }
}
?>