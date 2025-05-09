<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$count_sql = "SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = ? AND dismissed = 0";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param("i", $user_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$count_row = $count_result->fetch_assoc();
$unread_count = $count_row['unread_count'];
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
      color: #fff;
      position: relative;
      transition: opacity 0.5s ease-in-out;
    }

    body.fade-out {
      opacity: 0;
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
      position: relative;
    }

    .nav-top a {
      color: var(--white);
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 12px;
      font-weight: 600;
      background: transparent;
      transition: all 0.3s ease;
      position: relative;
      z-index: 1;
    }

    .nav-top a:hover {
      background: var(--nav-hover-bg);
      transform: scale(1.05);
    }

    .nav-top .active-indicator {
      position: absolute;
      height: 70%;
      border-radius: 12px;
      background: var(--active-bg);
      transition: all 0.4s ease;
      z-index: 0;
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

    .notif-badge {
      background: red;
      color: white;
      font-size: 12px;
      padding: 2px 8px;
      border-radius: 12px;
      margin-left: 8px;
      font-weight: 600;
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
        position: relative;
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

  <div class="nav-top" id="nav">
    <div class="active-indicator" id="activeIndicator"></div>
    <a href="profile.php">Profile</a>
    <a href="explore_recipes.php">Explore Recipes</a>
    <a href="upload_recipe.php">Upload Recipes</a>
    <a href="customer_service_form.php">Customer Service</a>
    <a href="saved_recipes.php">Saved Recipes</a>
    <a href="notifications.php">Notifications
      <?php if ($unread_count > 0): ?>
        <span class="notif-badge"><?php echo $unread_count; ?></span>
      <?php endif; ?>
    </a>
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

  <script>
    const links = document.querySelectorAll('.nav-top a');
    const indicator = document.getElementById('activeIndicator');

    function moveIndicator(element) {
      const rect = element.getBoundingClientRect();
      const parentRect = element.parentElement.getBoundingClientRect();
      indicator.style.width = rect.width + 'px';
      indicator.style.left = (rect.left - parentRect.left) + 'px';
    }

    links.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        moveIndicator(this);

        document.body.classList.add('fade-out');

        const targetUrl = this.getAttribute('href');
        setTimeout(() => {
          window.location.href = targetUrl;
        }, 500);
      });
    });

 
    window.addEventListener('load', () => {
      moveIndicator(links[0]);
    });
  </script>

</body>
</html>
