<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['recipe_id'])) {
    $recipe_id = $_POST['recipe_id'];
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM recipes WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $recipe_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
       
        $delete_sql = "DELETE FROM recipes WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $recipe_id);
        $delete_stmt->execute();

        if ($delete_stmt->affected_rows > 0) {
      e
            header("Location: posted_recipes.php?success=1");
            exit;
        } else {
          
            header("Location: posted_recipes.php?error=delete_failed");
            exit;
        }
    } else {
 
        header("Location: posted_recipes.php?error=not_found");
        exit;
    }
} else {

    header("Location: posted_recipes.php?error=invalid_request");
    exit;
}
?>
