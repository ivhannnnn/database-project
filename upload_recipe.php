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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    :root {
        --white: #ffffff;
        --glass-bg: rgba(255, 255, 255, 0);
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
        background-attachment: fixed;
    }
    body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 150%;
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
        box-shadow: 0 0 8px rgba(255,255,255,0.3);
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

    .content h2 {
        font-size: 34px;
        margin-bottom: 20px;
        text-align: center;
        color: #ffffff;
        text-shadow: 0 3px 8px rgba(0, 0, 0, 0.7);
    }

    .form-container {
    max-width: 420px;
    width: 100%;
    margin: 0 auto;
    background: rgba(0, 0, 0, 0.5);
    padding: 25px 20px; 
    border-radius: 16px;
    text-align: center; 
}

input, textarea, button {
    width: 100%;
    padding: 10px;
    margin-top: 12px;
    border: none;
    border-radius: 6px;
    font-size: 15px;
}

    label {
        font-weight: 600;
        display: block;
        margin-bottom: 8px;
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
    background-color: transparent; 
    border: 2px solid #fff; 
    color: #fff; 
    cursor: pointer;
    font-weight: 600;
    transition: background-color 0.3s, color 0.3s, border-color 0.3s;
}

button:hover {
    background-color: transparent; 
    color: white; 
    border-color: transparent; 
}

  </style>
</head>
<body>

<div class="navbar">
    <a href="dashboard.php">Dashboard</a>
    <a href="upload_recipe.php" class="active">Upload Recipe</a>
    <a href="saved_recipes.php">Saved Recipes</a>
    <a href="admin_logout.php">Logout</a>
</div>

<div class="main-content">
    <div class="content">
        <h2>Upload a New Recipe</h2>
        <div class="form-container">
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
    </div>
</div>

</body>
</html>