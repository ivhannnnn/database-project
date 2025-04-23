<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
  SELECT r.* FROM recipes r
  JOIN saved_recipes s ON r.id = s.recipe_id
  WHERE s.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Saved Recipes - FoodHub</title>
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: url('bgp.jpg') no-repeat center center / cover;
      color: #fff;
    }
    .container {
      padding: 50px;
      max-width: 1200px;
      margin: auto;
    }
    h1 {
      font-size: 36px;
      margin-bottom: 40px;
      text-align: center;
    }
    .recipe-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
    }
    .card {
      background: rgba(0, 0, 0, 0.7);
      border-radius: 15px;
      overflow: hidden;
      transition: transform 0.3s ease;
    }
    .card:hover {
      transform: scale(1.03);
    }
    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }
    .card-content {
      padding: 20px;
    }
    .card-title {
      font-size: 20px;
      margin: 0 0 10px;
      color: #00f2c3;
    }
    .card-description {
      font-size: 14px;
      color: #ddd;
      line-height: 1.6;
    }
    .card a {
      display: inline-block;
      margin-top: 12px;
      padding: 8px 15px;
      background: #00c896;
      color: #fff;
      border-radius: 10px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s;
    }
    .card a:hover {
      background: #009f75;
    }

    /* Styling for the back button */
    .back-btn {
      display: inline-block;
      padding: 12px 24px;
      color: white;
      background-color: transparent;
      text-decoration: none;
      border-radius: 12px;
      font-weight: 600;
      margin-bottom: 30px;
      transition: background-color 0.3s ease, transform 0.2s ease;
      font-size: 1em;
      border: 2px solid transparent; /* Transparent border */
    }

    .back-btn:hover {
      background: rgba(255, 255, 255, 0.1); /* Light hover effect */
      color: #fff; /* Ensure text color stays white */
      transform: scale(1.05); /* Scale on hover */
    }
  </style>
</head>
<body>

  <div class="container">
    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    <h1>Your Saved Recipes</h1>
    <div class="recipe-grid">
      <?php while ($recipe = $result->fetch_assoc()): ?>
        <div class="card">
          <?php if (!empty($recipe['image_path'])): ?>
            <img src="<?= htmlspecialchars($recipe['image_path']) ?>" alt="Recipe Image">
          <?php else: ?>
            <img src="default_recipe.jpg" alt="Default Recipe Image">
          <?php endif; ?>
          <div class="card-content">
            <h2 class="card-title"><?= htmlspecialchars($recipe['title']) ?></h2>
            <p class="card-description">
              <?= nl2br(htmlspecialchars(substr($recipe['description'], 0, 100))) ?>...
            </p>
            <a href="recipe.php?id=<?= $recipe['id'] ?>">View Recipe</a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

</body>
</html>