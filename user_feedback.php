<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply'], $_POST['feedback_id'])) {
    $reply = $conn->real_escape_string($_POST['reply']);
    $feedback_id = (int)$_POST['feedback_id'];
  
    $conn->query("UPDATE user_feedback SET reply='$reply' WHERE id=$feedback_id");
}

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
      --transition-speed: 0.3s;
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
      transition: background 0.5s ease;
      opacity: 1;
      transition: opacity 0.5s ease-in-out;
    }

    body.fade-out {
      opacity: 0;
    }

    body.fade-in {
      opacity: 1;
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
      padding: 10px 20px;
      background: var(--nav-glass-bg);
      backdrop-filter: blur(3px);
      -webkit-backdrop-filter: blur(3px);
      border-bottom: 1px solid var(--glass-border);
      transition: background 0.3s ease;
    }

    .navbar a {
      color: var(--white);
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 12px;
      font-weight: 600;
      background: transparent;
      transition: all var(--transition-speed) ease;
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
      transition: padding 0.5s ease;
    }

    .feedback-container {
      max-width: 1000px;
      width: 95%;
      padding: 40px 20px;
      border-radius: 30px;
      background: var(--glass-bg);
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      border: 1px solid var(--glass-border);
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.35);
      text-align: center;
      overflow-y: auto;
      max-height: 70vh; 
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
      vertical-align: top;
    }

    .feedback-container th {
      background-color: var(--nav-glass-bg);
    }

    .feedback-container tr:nth-child(even) {
      background-color: rgba(255, 255, 255, 0.1);
    }

    .feedback-container tr:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }

    textarea {
      width: 100%;
      padding: 8px;
      font-family: inherit;
      border-radius: 6px;
      resize: vertical;
    }

    button {
      padding: 6px 12px;
      margin-top: 5px;
      border: none;
      background-color: #28a745;
      color: white;
      border-radius: 6px;
      cursor: pointer;
    }

    button:hover {
      background-color: #218838;
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
    <a href="admin_dashboard.php" class="active" id="dashboardLink">Dashboard</a>
  </div>

  <div class="main-content">
    <div class="feedback-container">
      <h2>User Feedback</h2>
      <table>
        <thead>
          <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Message + Admin Reply</th>
            <th>Created At</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
              <td><?php echo htmlspecialchars($row['username']); ?></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td>
                <div><strong>User Message:</strong><br><?php echo nl2br(htmlspecialchars($row['message'])); ?></div>
                <?php if (!empty($row['reply'])): ?>
                  <div style="margin-top: 10px; color: lightgreen;"><strong>Admin Reply:</strong><br><?php echo nl2br(htmlspecialchars($row['reply'])); ?></div>
                <?php else: ?>
                  <form method="post" action="user_feedback.php" style="margin-top: 10px;">
                    <textarea name="reply" rows="2" placeholder="Type your reply..." required></textarea>
                    <input type="hidden" name="feedback_id" value="<?php echo $row['id']; ?>">
                    <button type="submit">Reply</button>
                  </form>
                <?php endif; ?>
              </td>
              <td><?php echo htmlspecialchars($row['created_at']); ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    document.getElementById('dashboardLink').addEventListener('click', function(event) {
      event.preventDefault(); 
      document.body.classList.add('fade-out');
      
      setTimeout(function() {
        window.location.href = 'admin_dashboard.php'; 
      }, 500);
    });
  </script>

</body>
</html>
