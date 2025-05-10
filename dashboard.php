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
$unread_count = $count_result->fetch_assoc()['unread_count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FoodHub Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --white: #fff;
      --glass-bg: rgba(255, 255, 255, 0.05);
      --glass-border: rgba(255, 255, 255, 0.1);
      --nav-bg: rgba(0, 0, 0, 0.3);
      --nav-hover: rgba(255, 255, 255, 0.1);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: url('bgp.jpg') no-repeat center center / cover;
      color: var(--white);
      height: 100vh;
      display: flex;
      flex-direction: column;
      transition: opacity 0.5s ease-in-out;
      position: relative;
    }

    body::before {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: -1;
    }

    body.fade-out {
      opacity: 0;
    }

    .nav-top {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 14px;
      padding: 12px 24px;
      background: var(--nav-bg);
      backdrop-filter: blur(5px);
      border-bottom: 1px solid var(--glass-border);
    }

    .nav-top a,
    .dropdown-btn {
      color: var(--white);
      text-decoration: none;
      font-weight: 600;
      padding: 10px 20px;
      border-radius: 12px;
      transition: all 0.2s ease;
      background: transparent;
      border: none;
      cursor: pointer;
      font-family: inherit;
    }

    .nav-top a:hover,
    .dropdown-btn:hover {
      background: var(--nav-hover);
      transform: scale(1.05);
    }

    .notif-badge {
      background: red;
      color: #fff;
      padding: 2px 7px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: bold;
      margin-left: 6px;
    }

    .dropdown {
      position: relative;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      top: 110%;
      right: 0;
      min-width: 160px;
      background: transparent;
      border-radius: 10px;
      overflow: hidden;
      z-index: 10;
    }

    .dropdown-content a {
      display: block;
      padding: 12px 16px;
      color: #fff;
      text-decoration: none;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .dropdown-content a:hover {
      background: var(--nav-hover);
    }

    .dropdown-content.show {
      display: block;
    }

    .main-content {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }

    .welcome-bubble {
      background: var(--glass-bg);
      backdrop-filter: blur(6px);
      border: 1px solid var(--glass-border);
      border-radius: 25px;
      padding: 60px 40px;
      max-width: 900px;
      width: 90%;
      box-shadow: 0 8px 30px rgba(0,0,0,0.3);
      text-align: center;
    }

    .welcome-bubble h2 {
      font-size: 32px;
      margin-bottom: 20px;
      color: #fff;
      text-shadow: 0 2px 6px rgba(0,0,0,0.6);
    }

    .welcome-bubble p {
      font-size: 18px;
      color: #f2f2f2;
      line-height: 1.6;
    }

    @media (max-width: 600px) {
      .nav-top {
        flex-direction: column;
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
    <div class="dropdown">
      <button class="dropdown-btn" id="profileDropdownBtn">
        <?php echo htmlspecialchars($_SESSION['username']); ?> ▼
      </button>
      <div class="dropdown-content" id="profileDropdown">
        <a href="profile.php">View Profile</a>
        <a href="logout.php">Logout</a>
      </div>
    </div>

    <a href="explore_recipes.php">Explore Recipes</a>
    <a href="upload_recipe.php">Upload Recipes</a>
    <a href="customer_service_form.php">Customer Service</a>
    <a href="saved_recipes.php">Saved Recipes</a>
    <a href="notifications.php">
      Notifications
      <?php if ($unread_count > 0): ?>
        <span class="notif-badge"><?php echo $unread_count; ?></span>
      <?php endif; ?>
    </a>
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
    const dropdownBtn = document.getElementById("profileDropdownBtn");
    const dropdownContent = document.getElementById("profileDropdown");

    dropdownBtn.addEventListener("click", function (e) {
      e.stopPropagation();
      dropdownContent.classList.toggle("show");
    });

    window.addEventListener("click", function () {
      dropdownContent.classList.remove("show");
    });

    document.querySelectorAll(".nav-top a").forEach(link => {
      link.addEventListener("click", function(e) {
        e.preventDefault();
        const href = this.getAttribute("href");
        document.body.classList.add("fade-out");
        setTimeout(() => {
          window.location.href = href;
        }, 250);
      });
    });

    window.addEventListener("pageshow", function () {
      document.body.classList.remove("fade-out");
    });
  </script>

</body>
</html>
