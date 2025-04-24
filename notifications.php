<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db_connection.php';


$user_id = $_SESSION['user_id'];


if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $dismiss_sql = "UPDATE recipes SET notification_dismissed = 1 WHERE id = ? AND user_id = ?";
    $dismiss_stmt = $conn->prepare($dismiss_sql);
    $dismiss_stmt->bind_param("ii", $delete_id, $user_id);
    $dismiss_stmt->execute();

    header("Location: notifications.php");
    exit();
}


$sql = "SELECT id, title, approval_status, created_at FROM recipes 
        WHERE user_id = ? AND notification_dismissed = 0 
        ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
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

        .status-pending {
            background-color: #ffc107;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            display: inline-block;
            text-decoration: none;
        }

        .delete-btn:hover {
            background-color: #c82333;
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
        <h2>Your Recipe Approval Status</h2>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="notification-card">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><strong>Submission Date:</strong> <?php echo htmlspecialchars($row['created_at']); ?></p>

                    <?php if ($row['approval_status'] == 'approved'): ?>
                        <div class="status-approved">Your recipe has been approved!</div>
                    <?php elseif ($row['approval_status'] == 'rejected'): ?>
                        <div class="status-rejected">Your recipe has been rejected.</div>
                    <?php else: ?>
                        <div class="status-pending">Your recipe is still under review.</div>
                    <?php endif; ?>

                    <a href="notifications.php?delete_id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this notification?')">Delete Notification</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>You have no recipes or pending approvals.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>