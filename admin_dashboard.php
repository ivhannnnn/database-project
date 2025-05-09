<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
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
      background: url('photo.jpg') no-repeat center center / cover;
      height: 100vh;
      display: flex;
      flex-direction: column;
      color: #fff;
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
      .navbar {
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

  <div class="navbar">
    <a href="admin_dashboard.php" class="active">Dashboard</a>
    <a href="posting_approval.php">Posting Approval</a>
    <a href="users.php">Users</a>
    <a href="user_feedback.php">User Feedback</a>
    <a href="admin_logout.php">Logout</a>
  </div>

  <div class="main-content">
    <div class="welcome-bubble">
      <h2>Welcome, Admin <?php echo htmlspecialchars($_SESSION['admin']); ?> 👋</h2>
      <p>
        Welcome to the <strong>Admin Panel</strong>. From here, you can manage user submissions, approve or reject recipes, and maintain the site effectively.
      </p>
    </div>
  </div>

  <script>
    document.querySelectorAll('.navbar a').forEach(function(link) {
      link.addEventListener('click', function(e) {
        e.preventDefault(); 
        const targetUrl = this.getAttribute('href'); 

        document.body.classList.add('fade-out');

       
        setTimeout(function() {
          window.location.href = targetUrl;
        }, 200);
      });
    });
  </script>

</body>
</html>
