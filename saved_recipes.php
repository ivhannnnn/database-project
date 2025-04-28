<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
  SELECT r.*, s.id as saved_id FROM recipes r
  JOIN saved_recipes s ON r.id = s.recipe_id
  WHERE s.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unsave_recipe'])) {
    $recipe_id = $_POST['recipe_id'];
    $unsave_stmt = $conn->prepare("DELETE FROM saved_recipes WHERE user_id = ? AND recipe_id = ?");
    $unsave_stmt->bind_param("ii", $user_id, $recipe_id);
    $unsave_stmt->execute();
    header("Location: saved_recipes.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Saved Recipes - FoodHub</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --white: #ffffff;
      --glass-bg: rgba(255, 255, 255, 0.1);
      --glass-border: rgba(255, 255, 255, 0.1);
      --nav-glass-bg: rgba(0, 0, 0, 0.2);
      --nav-hover-bg: rgba(255, 255, 255, 0.1);
      --active-bg: rgba(255, 255, 255, 0.25);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: url('bgp.jpg') no-repeat center center / cover;
      height: 100vh;
      display: flex;
      flex-direction: column;
      color: white;
    }
    body::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5); 
      z-index: -1;
    }
    .navbar {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 16px;
      padding: 10px 20px;
      background: var(--nav-glass-bg);
      backdrop-filter: blur(3px);
      -webkit-backdrop-filter: blur(3px);
      border-bottom: 1px solid var(--glass-border);
    }

    .navbar a {
      color: var(--white);
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 12px;
      font-weight: 600;
      background: transparent;
      transition: all 0.3s ease;
    }

    .navbar a:hover {
      background: var(--nav-hover-bg);
      transform: scale(1.05);
    }

    .navbar a.active {
      background: var(--active-bg);
      box-shadow: 0 0 8px rgba(255, 255, 255, 0.3);
    }

    .main-content {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 20px;
    }

    .content {
      max-width: 950px;
      width: 90%;
      padding: 40px 30px;
      border-radius: 20px;
      background: var(--glass-bg);
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      border: 1px solid var(--glass-border);
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }

    .content h1 {
      font-size: 36px;
      text-align: center;
      margin-bottom: 40px;
    }

    .recipe-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
    }

    .card {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 15px;
      overflow: hidden;
      transition: transform 0.3s ease;
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
    }

    .card:hover {
      transform: scale(1.05);
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

    .unsave-btn {
      display: inline-block;
      padding: 8px 15px;
      color: white;
      background-color: #ff3b3b;
      text-decoration: none;
      border-radius: 10px;
      font-weight: bold;
      margin-top: 10px;
      transition: background 0.3s;
    }

    .unsave-btn:hover {
      background-color: #cc2f2f;
    }
  </style>
</head>
<body>

  <div class="navbar">
    <a href="dashboard.php">Dashboard</a>
    <a href="saved_recipes.php" class="active">Saved Recipes</a>
    <a href="logout.php">Logout</a>
  </div>

  <div class="main-content">
    <div class="content">
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
              <form action="saved_recipes.php" method="POST">
                <input type="hidden" name="recipe_id" value="<?= $recipe['id'] ?>">
                <button type="submit" name="unsave_recipe" class="unsave-btn">Unsave Recipe</button>
              </form>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>

</body>
</html>