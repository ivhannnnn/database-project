<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$user_stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

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
    <title>Customer Service Form - FoodHub</title>
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
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: url('bgp.jpg') no-repeat center center / cover;
            height: 100vh;
            display: flex;
            flex-direction: column;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }
        .navbar {
            display: flex;
            justify-content: center;
            padding: 10px 20px;
            background: var(--nav-glass-bg);
            backdrop-filter: blur(3px);
            border-bottom: 1px solid var(--glass-border);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 10;
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
            padding-top: 80px;
        }
        .form-container {
            max-width: 600px;
            width: 90%;
            padding: 40px;
            border-radius: 30px;
            background: var(--glass-bg);
            backdrop-filter: blur(4px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.35);
            text-align: center;
            transition: opacity 0.5s ease, transform 0.5s ease;
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
        }
        .form-container input::placeholder,
        .form-container textarea::placeholder {
            color: rgba(255,255,255,0.6);
        }
        .form-container input[disabled] {
            opacity: 0.7;
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

     
        .alert {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            text-align: center;
            display: none;
        }
        .alert.error {
            background-color: #f44336;
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


<div class="navbar">
    <a href="dashboard.php" id="dashboardLink">Dashboard</a>
</div>

<div class="main-content">
    <div class="form-container" id="formContainer">
        <h2>Customer Service Form</h2>

        <?php if (isset($_SESSION['service_request_status'])): ?>
            <div class="alert <?php echo strpos($_SESSION['service_request_status'], 'success') !== false ? '' : 'error'; ?>" style="display: block;">
                <?php echo $_SESSION['service_request_status']; ?>
            </div>
            <?php unset($_SESSION['service_request_status']); ?>
        <?php endif; ?>

        <form action="submit_service_request.php" method="POST">
            <input type="text" value="<?= htmlspecialchars($user['username']) ?>" disabled>
            <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>

            <input type="hidden" name="username" value="<?= htmlspecialchars($user['username']) ?>">
            <input type="hidden" name="email" value="<?= htmlspecialchars($user['email']) ?>">

            <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
            <button type="submit">Submit</button>
        </form>

        <button onclick="window.location.href='admin_reply.php'" style="margin-top: 20px;">Admin Reply</button>
    </div>
</div>

<script>
 
    document.getElementById('dashboardLink').addEventListener('click', function(e) {
        e.preventDefault();
        const formContainer = document.getElementById('formContainer');
        formContainer.style.opacity = '0';
        formContainer.style.transform = 'scale(0.95)';
        setTimeout(() => {
            window.location.href = 'dashboard.php';
        }, 200); 
    });
</script>

</body>
</html>
