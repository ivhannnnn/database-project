<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $recipe_id = $_POST['recipe_id'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];


    $sql = "INSERT INTO recipe_reviews (user_id, recipe_id, rating, review_text) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiis', $user_id, $recipe_id, $rating, $review_text);

    if ($stmt->execute()) {
       
        $sql_owner = "SELECT user_id FROM recipes WHERE id = ?";
        $stmt_owner = $conn->prepare($sql_owner);
        $stmt_owner->bind_param('i', $recipe_id);
        $stmt_owner->execute();
        $result_owner = $stmt_owner->get_result();
        
        if ($owner = $result_owner->fetch_assoc()) {
           
            $sql_reviewer = "SELECT username FROM users WHERE id = ?";
            $stmt_reviewer = $conn->prepare($sql_reviewer);
            $stmt_reviewer->bind_param('i', $user_id);
            $stmt_reviewer->execute();
            $result_reviewer = $stmt_reviewer->get_result();
            $reviewer = $result_reviewer->fetch_assoc();

            $message = $reviewer['username'] . " has reviewed your recipe: " . $rating . " stars. Review: " . $review_text;

           
            $notification_sql = "INSERT INTO notifications (user_id, message, recipe_id, review_info) VALUES (?, ?, ?, ?)";
            $review_info = json_encode([
                'reviewer' => $reviewer['username'],
                'rating' => $rating,
                'review_text' => $review_text
            ]);
            $stmt_notify = $conn->prepare($notification_sql);
            $stmt_notify->bind_param('isis', $owner['user_id'], $message, $recipe_id, $review_info);
            $stmt_notify->execute();
        }

        
        header("Location: recipe.php?id=" . $recipe_id . "&review_submitted=1");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>