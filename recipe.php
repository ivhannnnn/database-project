<?php
session_start();
require 'db_connection.php';

if (!isset($_GET['id'])) {
    header("Location: explore_recipes.php");
    exit;
}

$recipe_id = $_GET['id'];
$stmt = $conn->prepare("SELECT r.*, u.username FROM recipes r JOIN users u ON r.user_id = u.id WHERE r.id = ?");
$stmt->bind_param("i", $recipe_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows < 1) {
    echo "Recipe not found.";
    exit;
}

$recipe = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($recipe['title']) ?> - FoodHub</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: url('bgp.jpg') no-repeat center center / cover;
      color: #fff;
      padding: 40px;
    }
    .recipe-container {
      max-width: 800px;
      margin: auto;
      background: rgba(0,0,0,0.6);
      padding: 30px;
      border-radius: 20px;
    }
    h1 {
      font-size: 32px;
      margin-bottom: 10px;
    }
    p {
      margin: 15px 0;
      line-height: 1.6;
    }
    img {
      width: 100%;
      max-height: 400px;
      object-fit: cover;
      border-radius: 15px;
      margin-bottom: 20px;
    }
    a.button {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 20px;
      background-color: #00c896;
      color: white;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 600;
      transition: 0.3s;
    }
    a.button:hover {
      background-color: #009f75;
    }
  </style>
</head>
<body>

  <div class="recipe-container">
    <h1><?= htmlspecialchars($recipe['title']) ?></h1>
    <p><strong>By:</strong> <?= htmlspecialchars($recipe['username']) ?></p>
    <?php if (!empty($recipe['image_path'])): ?>
      <img src="<?= htmlspecialchars($recipe['image_path']) ?>" alt="Recipe Image">
    <?php endif; ?>
    <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($recipe['description'])) ?></p>
    <p><strong>Ingredients:</strong><br><?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>
    <p><strong>Steps:</strong><br><?= nl2br(htmlspecialchars($recipe['steps'])) ?></p>

    <a class="button" href="save_recipes.php?id=<?= $recipe['id'] ?>">Save Recipe</a>
    <a class="button" href="explore_recipes.php">← Back to Explore</a>
  </div>

</body>
</html>