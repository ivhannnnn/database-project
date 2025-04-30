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
    <title>Customer Service Form</title>
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

        .notif-badge {
            background-color: red;
            color: white;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 50%;
            margin-left: 6px;
        }

        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .form-container {
            max-width: 600px;
            width: 90%;
            padding: 40px;
            border-radius: 30px;
            background: var(--glass-bg);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.35);
            text-align: center;
        }

        .form-container h2 {
            font-size: 34px;
            margin-bottom: 20px;
            color: #ffffff;
            text-shadow: 0 3px 8px rgba(0, 0, 0, 0.7);
        }

        .form-container input,
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background-color: transparent;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 10px;
            font-size: 16px;
            color: white;
            font-family: 'Poppins', sans-serif;
        }

        .form-container input::placeholder,
        .form-container textarea::placeholder {
            color: rgba(255,255,255,0.6);
        }

        .form-container button {
            background: transparent;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .form-container button:hover {
            background: var(--active-bg);
            box-shadow: 0 0 8px rgba(255,255,255,0.3);
            transform: scale(1.05);
        }

        @media (max-width: 600px) {
            .form-container {
                padding: 20px;
            }

            .form-container h2 {
                font-size: 24px;
            }

            .form-container input,
            .form-container textarea {
                font-size: 14px;
            }

            .form-container button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="nav-top">
    <a href="profile.php">Profile</a>
    <a href="explore_recipes.php">Explore Recipes</a>
    <a href="upload_recipe.php">Upload Recipes</a>
    <a href="customer_service_form.php" class="active">Customer Service</a>
    <a href="saved_recipes.php">Saved Recipes</a>
    <a href="notifications.php">Notifications
        <?php if ($unread_count > 0): ?>
            <span class="notif-badge"><?php echo $unread_count; ?></span>
        <?php endif; ?>
    </a>
    <a href="logout.php">Logout</a>
</div>

<div class="main-content">
    <div class="form-container">
        <h2>Customer Service Form</h2>
        <form action="submit_service_request.php" method="POST">
            <input type="text" name="username" placeholder="Your Username" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
            <button type="submit">Submit</button>
        </form>
    </div>
</div>

</body>
</html>
