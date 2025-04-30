<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}


require 'db_connection.php';


$query = "SELECT * FROM user_feedback ORDER BY created_at DESC"; 
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Feedback - Admin Dashboard</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght=400;600&display=swap" rel="stylesheet">
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

    .feedback-container {
      max-width: 950px;
      width: 90%;
      padding: 40px 20px;
      border-radius: 30px;
      background: var(--glass-bg);
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      border: 1px solid var(--glass-border);
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.35);
      text-align: center;
    }

    .feedback-container h2 {
      font-size: 34px;
      margin-bottom: 20px;
      color: #ffffff;
      text-shadow: 0 3px 8px rgba(0, 0, 0, 0.7);
    }

    .feedback-container table {
      width: 100%;
      margin-top: 20px;
      border-collapse: collapse;
    }

    .feedback-container table, th, td {
      border: 1px solid var(--glass-border);
    }

    .feedback-container th, td {
      padding: 12px;
      text-align: left;
      color: #fff;
    }

    .feedback-container th {
      background-color: var(--nav-glass-bg);
    }

    .feedback-container tr:nth-child(e.feedback-container tr:nth-child(even) {
      background-color: rgba(255, 255, 255, 0.1);
    }

    .feedback-container tr:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }

    @media (max-width: 600px) {
      .feedback-container {
        padding: 20px;
      }

      .feedback-container h2 {
        font-size: 24px;
      }

      .feedback-container table {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>

  <div class="navbar">
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="posting_approval.php">Posting Approval</a>
    <a href="users.php">Users</a>
    <a href="user_feedback.php" class="active">User  Feedback</a>
    <a href="admin_logout.php">Logout</a>
  </div>

  <div class="main-content">
    <div class="feedback-container">
      <h2>User Feedback</h2>
      <table>
        <thead>
          <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Message</th>
            <th>Created At</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
              <td><?php echo htmlspecialchars($row['username']); ?></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
              <td><?php echo htmlspecialchars($row['created_at']); ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>