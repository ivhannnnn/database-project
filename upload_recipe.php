<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Recipe - FoodHub</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: url('bgp.jpg') no-repeat center center / cover;
      padding: 40px;
      color: #fff;
    }
    .form-container {
      max-width: 600px;
      margin: auto;
      background: rgba(0,0,0,0.5);
      padding: 30px;
      border-radius: 20px;
    }
    input, textarea, button {
      width: 100%;
      padding: 12px;
      margin-top: 15px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
    }
    button {
      background-color: #00c896;
      color: white;
      cursor: pointer;
      font-weight: 600;
    }
    button:hover {
      background-color: #00a37a;
    }
    label {
      font-weight: 600;
    }
    .back-btn {
      display: inline-block;
      margin-bottom: 30px;
      padding: 12px 20px;
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
      border-radius: 25px;
      font-weight: 600;
      text-decoration: none;
      transition: background 0.3s ease, transform 0.2s ease;
      margin-top: 20px;
      font-size: 18px;
      text-align: center;
    }
    .back-btn:hover {
      background: rgba(255, 255, 255, 0.35);
      transform: scale(1.05);
    }
  </style>
</head>
<body>

  <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>

  <div class="form-container">
    <h2>Upload a New Recipe</h2>
    <form action="handle_upload.php" method="POST" enctype="multipart/form-data">
      <label for="title">Recipe Title</label>
      <input type="text" name="title" id="title" required>

      <label for="description">Description</label>
      <textarea name="description" id="description" rows="4" required></textarea>

      <label for="ingredients">Ingredients</label>
      <textarea name="ingredients" id="ingredients" rows="4" required></textarea>

      <label for="steps">Steps</label>
      <textarea name="steps" id="steps" rows="4" required></textarea>

      <label for="image">Recipe Image</label>
      <input type="file" name="image" id="image" accept="image/*">

      <button type="submit">Post Recipe</button>
    </form>
  </div>

</body>
</html>