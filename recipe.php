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


$stmt_reviews = $conn->prepare("SELECT * FROM recipe_reviews WHERE recipe_id = ? ORDER BY created_at DESC");
$stmt_reviews->bind_param("i", $recipe_id);
$stmt_reviews->execute();
$reviews_result = $stmt_reviews->get_result();


$stmt_avg_rating = $conn->prepare("SELECT AVG(rating) AS avg_rating FROM recipe_reviews WHERE recipe_id = ?");
$stmt_avg_rating->bind_param("i", $recipe_id);
$stmt_avg_rating->execute();
$avg_rating_result = $stmt_avg_rating->get_result();
$avg_rating = $avg_rating_result->fetch_assoc()['avg_rating'];
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
    .button {
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
    .button:hover {
      background-color: #009f75;
    }
    .review-form {
      margin-top: 30px;
    }
    .review-form textarea {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      margin-bottom: 10px;
      font-size: 14px;
    }
    .review-list {
      margin-top: 30px;
    }
    .review-item {
      background: rgba(255, 255, 255, 0.1);
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 12px;
    }
    .message {
      padding: 10px;
      background-color: #4CAF50;
      color: white;
      border-radius: 5px;
      margin-bottom: 20px;
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

    <hr>

 
    <h3>Average Rating: 
      <?= $avg_rating ? number_format($avg_rating, 1) : 'No ratings yet' ?>
    </h3>

  
    <?php if (isset($_GET['review_submitted']) && $_GET['review_submitted'] == 1): ?>
      <div class="message">Your review has been submitted successfully!</div>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
      
      <div class="review-form">
        <h3>Submit Your Review</h3>
        <form action="submit_review.php" method="POST">
          <input type="hidden" name="recipe_id" value="<?= $recipe_id ?>">
          <label for="rating">Rating:</label>
          <select name="rating" id="rating" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
          </select><br>
          
          <label for="review_text">Review:</label><br>
          <textarea name="review_text" id="review_text" rows="4" required></textarea><br>
          
          <button type="submit" class="button">Submit Review</button>
        </form>
      </div>
    <?php else: ?>
      <p>You need to be logged in to submit a review.</p>
    <?php endif; ?>

    <div class="review-list">
      <h3>Reviews</h3>
      <?php if ($reviews_result->num_rows > 0): ?>
        <?php while($review = $reviews_result->fetch_assoc()): ?>
          <div class="review-item">
            <p><strong>User <?= htmlspecialchars($review['user_id']); ?>:</strong> <?= htmlspecialchars($review['rating']); ?> stars</p>
            <p><?= nl2br(htmlspecialchars($review['review_text'])); ?></p>
            <p><small>Posted on <?= htmlspecialchars($review['created_at']); ?></small></p>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No reviews yet. Be the first to leave a review!</p>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>
