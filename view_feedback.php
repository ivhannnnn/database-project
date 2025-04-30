<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

require 'db_connection.php';

if (isset($_GET['id'])) {
    $feedback_id = $_GET['id'];

  
    $sql = "SELECT * FROM customer_service_conversations WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $feedback_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $feedback = $result->fetch_assoc();
    } else {
        echo "Feedback not found!";
        exit();
    }
} else {
    echo "No feedback ID specified!";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Feedback</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
  
  </style>
</head>
<body>

  <div class="navbar">
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="posting_approval.php">Posting Approval</a>
    <a href="users.php">Users</a>
    <a href="user_feedback.php">User Feedback</a>
    <a href="admin_logout.php">Logout</a>
  </div>

  <div class="main-content">
    <h2>Feedback Details</h2>

    <h3><?php echo htmlspecialchars($feedback['subject']); ?></h3>
    <p><strong>User:</strong> <?php echo htmlspecialchars($feedback['username']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($feedback['email']); ?></p>
    <p><strong>Message:</strong></p>
    <p><?php echo nl2br(htmlspecialchars($feedback['message'])); ?></p>
    <p><strong>Status:</strong> <?php echo ucfirst($feedback['status']); ?></p>

 
  </div>

</body>
</html>
