<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


if (isset($_GET['mark_as_read_id'])) {
    $mark_as_read_id = intval($_GET['mark_as_read_id']);
    $update_sql = "UPDATE notifications SET dismissed = 1 WHERE id = ? AND user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $mark_as_read_id, $user_id);
    $update_stmt->execute();
    header("Location: notifications.php");
    exit();
}


if (isset($_GET['mark_as_unread_id'])) {
    $mark_as_unread_id = intval($_GET['mark_as_unread_id']);
    $update_sql = "UPDATE notifications SET dismissed = 0 WHERE id = ? AND user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $mark_as_unread_id, $user_id);
    $update_stmt->execute();
    header("Location: notifications.php");
    exit();
}


if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM notifications WHERE id = ? AND user_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $delete_id, $user_id);
    $delete_stmt->execute();
    header("Location: notifications.php");
    exit();
}


if (isset($_GET['delete_all'])) {
    $delete_all_sql = "DELETE FROM notifications WHERE user_id = ?";
    $delete_all_stmt = $conn->prepare($delete_all_sql);
    $delete_all_stmt->bind_param("i", $user_id);
    $delete_all_stmt->execute();
    header("Location: notifications.php");
    exit();
}


$sql = "SELECT id, message, type, created_at, dismissed FROM notifications 
        WHERE user_id = ? 
        ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


$has_notifications = ($result && $result->num_rows > 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Recipe Notifications</title>
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

        .nav-top a.active {
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
            width: 90%;
            max-width: 950px;
            background: var(--glass-bg);
            padding: 30px;
            border-radius: 20px;
            backdrop-filter: blur(4px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.35);
            margin-top: 20px;
            max-height: 500px;
            overflow-y: auto;
        }

        .content h2 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #ffffff;
            text-shadow: 0 3px 8px rgba(0, 0, 0, 0.7);
        }

        .notification-card {
            background: rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .notification-card h3 {
            font-size: 24px;
        }

        .notification-card p {
            font-size: 16px;
        }

        .status-approved {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .status-rejected {
            background-color: #dc3545;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .status-review {
            background-color: #ffc107;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .action-btns {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .action-btns a {
            padding: 6px 12px;
            background-color: #008CBA;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .action-btns a:hover {
            background-color: #005f73;
            transform: scale(1.05);
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-all-btn {
            background-color: transparent;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
        }

        .delete-all-btn:hover {
            background: var(--nav-hover-bg);
            transform: scale(1.05);
        }

        @media (max-width: 600px) {
            .nav-top {
                flex-direction: column;
                gap: 10px;
            }

            .content {
                padding: 20px;
            }

            .content h2 {
                font-size: 24px;
            }

            .notification-card h3 {
                font-size: 20px;
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
    <a href="notifications.php" class="active">Notifications</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main-content">
    <div class="content">
        <h2>Your Recipe Notifications</h2>

        <?php if ($has_notifications): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="notification-card">
                    <h3><?php echo htmlspecialchars($row['message']); ?></h3>
                    <p><strong>Received on:</strong> <?php echo htmlspecialchars($row['created_at']); ?></p>

                    <?php if ($row['type'] == 'approval'): ?>
                        <div class="status-approved">Your recipe has been approved by the admin!</div>
                    <?php elseif ($row['type'] == 'rejected'): ?>
                        <div class="status-rejected">Your recipe has been rejected by the admin.</div>
                    <?php elseif ($row['type'] == 'review'): ?>
                        <div class="status-review">You have a new review on your recipe!</div>
                    <?php endif; ?>

                    <div class="action-btns">
                        <?php if ($row['dismissed'] == 0): ?>
                            <a href="notifications.php?mark_as_read_id=<?php echo $row['id']; ?>">Mark as Read</a>
                            <a href="notifications.php?mark_as_unread_id=<?php echo $row['id']; ?>">Mark as Unread</a>
                        <?php endif; ?>
                        <a href="notifications.php?delete_id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this notification?')">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
            <a href="notifications.php?delete_all=true" class="delete-all-btn" onclick="return confirm('Are you sure you want to delete all notifications?')">Delete All Notifications</a>
        <?php else: ?>
            <p>No new notifications.</p>
        <?php endif; ?>

    </div>
</div>

</body>
</html>
