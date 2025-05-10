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
$unread_count = $count_stmt->get_result()->fetch_assoc()['unread_count'] ?? 0;

$user_sql = "SELECT username, profile_picture FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

$profile_picture = (!empty($user['profile_picture']) && file_exists("uploads/" . $user['profile_picture']))
    ? "uploads/" . $user['profile_picture']
    : "uploads/default.png";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>FoodHub Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <style>
    :root {
      --white: #fff;
      --highlight: #f9c74f;
      --nav-bg: rgba(0, 0, 0, 0.4);
      --hover-bg: rgba(255, 255, 255, 0.1);
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
      min-height: 100vh;
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
      gap: 12px;
      padding: 12px 20px;
      background: var(--nav-bg);
      backdrop-filter: blur(5px);
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .nav-top a,
    .dropdown-btn {
      color: var(--white);
      font-weight: 600;
      padding: 10px 16px;
      text-decoration: none;
      border-radius: 10px;
      transition: 0.3s;
      position: relative;
      display: flex;
      align-items: center;
      gap: 8px;
      background: transparent;
    }

    .nav-top a:hover,
    .dropdown-btn:hover {
      background: var(--hover-bg);
      transform: scale(1.05);
    }

    .nav-top .active {
      border-bottom: 2px solid var(--highlight);
      color: var(--highlight);
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
      background: var(--nav-bg);
      border-radius: 10px;
      overflow: hidden;
      z-index: 20;
    }

    .dropdown-content a {
      padding: 12px 16px;
      display: block;
      color: #fff;
      text-decoration: none;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .dropdown-content a:hover {
      background: var(--hover-bg);
    }

    .dropdown-content.show {
      display: block;
    }

    .avatar {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #fff;
    }

    .notif-badge {
      background: red;
      color: #fff;
      padding: 3px 7px;
      border-radius: 10px;
      font-size: 11px;
      margin-left: 4px;
    }

    .main-content {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 60px 20px;
    }

    .welcome-bubble {
      background: rgba(0,0,0,0.5);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.1);
      padding: 50px;
      border-radius: 25px;
      max-width: 900px;
      width: 90%;
      text-align: center;
      box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    }

    .welcome-bubble h2 {
      font-size: 30px;
      margin-bottom: 20px;
    }

    .welcome-bubble p {
      font-size: 18px;
      color: #eee;
      line-height: 1.5;
    }

    @media (max-width: 600px) {
      .nav-top {
        flex-direction: column;
        align-items: center;
      }

      .welcome-bubble {
        padding: 30px 20px;
      }

      .welcome-bubble h2 {
        font-size: 22px;
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
        <img src="<?php echo htmlspecialchars($profile_picture); ?>" class="avatar" alt="Avatar" />
        <?php echo htmlspecialchars($user['username']); ?> ▼
      </button>
      <div class="dropdown-content" id="profileDropdown">
        <a href="profile.php"><i class="fas fa-id-badge"></i> View Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>

    <a href="explore_recipes.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'explore_recipes.php' ? 'active' : ''; ?>"><i class="fas fa-utensils"></i> Explore Recipes</a>
    <a href="upload_recipe.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'upload_recipe.php' ? 'active' : ''; ?>"><i class="fas fa-upload"></i> Upload Recipes</a>
    <a href="customer_service_form.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'customer_service_form.php' ? 'active' : ''; ?>"><i class="fas fa-headset"></i> Customer Service</a>
    <a href="saved_recipes.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'saved_recipes.php' ? 'active' : ''; ?>"><i class="fas fa-bookmark"></i> Saved Recipes</a>
    <a href="notifications.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'notifications.php' ? 'active' : ''; ?>"><i class="fas fa-bell"></i> Notifications
      <?php if ($unread_count > 0): ?>
        <span class="notif-badge"><?php echo $unread_count; ?></span>
      <?php endif; ?>
    </a>
  </div>

  <div class="main-content">
    <div class="welcome-bubble">
      <h2>Hi, Welcome <?php echo htmlspecialchars($user['username']); ?> 👋</h2>
      <p>
        Welcome to <strong>FoodHub</strong> — your recipe paradise.<br>
        Browse a variety of mouthwatering recipes, share your culinary creations,<br>
        and get inspired by fellow food lovers!
      </p>
    </div>
  </div>

  <script>
 
    document.getElementById("profileDropdownBtn").addEventListener("click", function (e) {
      e.stopPropagation();
      document.getElementById("profileDropdown").classList.toggle("show");
    });

    
    window.addEventListener("click", function () {
      document.getElementById("profileDropdown").classList.remove("show");
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

    window.addEventListener("pageshow", () => {
      document.body.classList.remove("fade-out");
    });
  </script>
</body>
</html>
