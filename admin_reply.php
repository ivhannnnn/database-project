<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, username, email, message, reply, created_at FROM user_feedback WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Feedback & Admin Replies</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('bgp.jpg') no-repeat center center / cover;
            color: #fff;
            padding: 40px;
            margin: 0;
        }
        .reply-container {
            background: rgba(0,0,0,0.6);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 20px;
            border-radius: 12px;
            max-width: 800px;
            margin: auto;
        }
        .reply-block {
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .reply-block h3 {
            margin: 0 0 10px;
        }
        .reply {
            color: lightgreen;
            margin-top: 10px;
        }

       
        .back-btn {
            background-color: transparent;
            color: #fff;
            padding: 10px 20px;
            border: 1px solid #fff;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            position: absolute;
            top: 20px;
            left: 20px;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .back-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: scale(1.05);
        }

      
        .reply-container {
            max-height: 600px; 
            overflow-y: auto; 
            padding-bottom: 50px; 
        }
    </style>
</head>
<body>


<a href="javascript:history.back()" class="back-btn">Back</a>

<div class="reply-container">
    <h2>Your Feedback & Admin Replies</h2>
    
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="reply-block">
                <h3><?php echo htmlspecialchars($row['username']); ?> (<?php echo htmlspecialchars($row['email']); ?>)</h3>
                <p><strong>Your Message:</strong><br><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>

                <?php if (!empty($row['reply'])): ?>
                    <p class="reply"><strong>Admin Reply:</strong><br><?php echo nl2br(htmlspecialchars($row['reply'])); ?></p>
                <?php else: ?>
                    <p><em>No reply yet.</em></p>
                <?php endif; ?>

                <small>Sent on: <?php echo htmlspecialchars($row['created_at']); ?></small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No feedback submitted yet.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
