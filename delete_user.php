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


    $stmtReviews = $conn->prepare("DELETE FROM recipe_reviews WHERE user_id = ?");
    $stmtReviews->bind_param("i", $user_id);
    if (!$stmtReviews->execute()) {
        die("Failed to delete user reviews: " . $stmtReviews->error);
    }
    $stmtReviews->close();


    $stmtRecipes = $conn->prepare("DELETE FROM recipes WHERE user_id = ?");
    $stmtRecipes->bind_param("i", $user_id);
    if (!$stmtRecipes->execute()) {
        die("Failed to delete user recipes: " . $stmtRecipes->error);
    }
    $stmtRecipes->close();


    $stmtUser = $conn->prepare("DELETE FROM users WHERE id = ?");
    if ($stmtUser === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmtUser->bind_param("i", $user_id);
    if (!$stmtUser->execute()) {
        die("Execute failed: " . $stmtUser->error);
    }
    $stmtUser->close();

    header("Location: users.php?deleted=1");
    exit();

} else {
    echo "No user ID provided.";
    exit();
}
?>
