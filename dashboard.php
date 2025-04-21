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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FoodHub Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --white: #ffffff;
      --glass-bg: rgba(255, 255, 255, 0);
      --glass-border: rgba(255, 255, 255, 0.1);
      --nav-glass-bg: rgba(0, 0, 0, 0.2);
      --nav-hover-bg: rgba(255, 255, 255, 0.1);
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
      color: #fff;
    }

    .nav-top {
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

    .nav-top a {
      color: var(--white);
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 12px;
      font-weight: 600;
      background: transparent;
      transition: all 0.3s ease;
    }

    .nav-top a:hover {
      background: var(--nav-hover-bg);
      transform: scale(1.05);
    }

    .main-content {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 20px;
    }

    .welcome-bubble {
      max-width: 950px;
      width: 90%;
      padding: 60px 40px;
      border-radius: 30px;
      background: var(--glass-bg);
      backdrop-filter: blur(4px); 
      -webkit-backdrop-filter: blur(4px);
      border: 1px solid var(--glass-border);
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.35);
      text-align: center;
    }

    .welcome-bubble h2 {
      font-size: 34px;
      margin-bottom: 20px;
      color: #ffffff;
      text-shadow: 0 3px 8px rgba(0, 0, 0, 0.7);
    }

    .welcome-bubble p {
      font-size: 18px;
      line-height: 1.8;
      color: #f2f2f2;
      text-shadow: 0 2px 6px rgba(0, 0, 0, 0.7);
    }

    @media (max-width: 600px) {
      .nav-top {
        flex-direction: column;
        gap: 10px;
      }

      .welcome-bubble {
        padding: 40px 20px;
      }

      .welcome-bubble h2 {
        font-size: 24px;
      }

      .welcome-bubble p {
        font-size: 16px;
      }
    }
  </style>
</head>
<body>

  
  <div class="nav-top">
    <a href="profile.php">Profile</a>
    <a href="explore_recipes.php">Explore Recipes</a>
    <a href="upload_recipe.php">Upload Recipes</a>
    <a href="saved_recipes.php">Saved Recipes</a>
    <a href="logout.php">Logout</a>
  </div>

 
  <div class="main-content">
    <div class="welcome-bubble">
      <h2>Hi, Welcome <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h2>
      <p>
        Welcome to <strong>FoodHub</strong> — your recipe paradise.<br>
        Browse a variety of mouthwatering recipes,<br>
        share your culinary creations, and get inspired by fellow food lovers!
      </p>
    </div>
  </div>

</body>
</html>