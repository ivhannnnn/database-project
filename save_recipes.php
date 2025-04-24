<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: explore_recipes.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$recipe_id = $_GET['id'];


$check = $conn->prepare("SELECT * FROM saved_recipes WHERE user_id = ? AND recipe_id = ?");
$check->bind_param("ii", $user_id, $recipe_id);
$check->execute();
$check_result = $check->get_result();

if ($check_result->num_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO saved_recipes (user_id, recipe_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $recipe_id);
    $stmt->execute();
}

header("Location: saved_recipes.php");
exit;