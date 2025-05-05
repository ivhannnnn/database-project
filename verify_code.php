<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
if (isset($_GET['error'])) {
    $error = $_GET['error'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Verify Code | FoodHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('bgp.jpg') no-repeat center center / cover;
            background-attachment: fixed;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            min-height: 100vh;
            margin: 0;
            box-sizing: border-box;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 120%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1; 
        }

        .verify-container {
            background: rgba(0, 0, 0, 0.6);
            border-radius: 16px;
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        h2 {
            color: #f9c74f;
            margin-bottom: 25px;
        }

        form input[type="text"],
        form input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-sizing: border-box;
        }

        form button {
            background-color: transparent;
            color: white;
            border: none;
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 1em;
        }

        form button:hover {
            background: var(--active-bg);
            box-shadow: 0 0 8px rgba(255,255,255,0.3);
            transform: scale(1.05);
        }

        .error {
            color: red;
            margin-bottom: 20px;
            font-size: 1.1em;
        }

        a.back-link {
            display: inline-block;
            margin-top: 20px;
            color: white;
            text-decoration: underline;
            font-weight: 400;
        }

        a.back-link:hover {
            color: #f9c74f;
        }
    </style>
</head>
<body>

<div class="verify-container">
    <h2>Enter Verification Code</h2>

    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="verify_code_handler.php" method="POST">
        <input type="text" name="verification_code" placeholder="Enter code" required>
        <input type="password" name="new_password" placeholder="Enter new password again" required>
        <button type="submit">Verify & Change Password</button>
    </form>

    <a href="profile.php" class="back-link">Back to Profile</a>
</div>

</body>
</html>
