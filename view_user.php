<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

require 'db_connection.php';

// Get the user id from the URL parameter
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch user details from the database
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit();
    }
} else {
    echo "No user ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View User - Admin Panel</title>
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
            border-bottom: 1px solid var(--glass-border);
        }

        .navbar a {
            color: var(--white);
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
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

        .content {
            max-width: 950px;
            width: 90%;
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

        .content p {
            font-size: 18px;
            line-height: 1.8;
            color: #f2f2f2;
        }

        .content table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: white;
        }

        td {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: darkred;
        }

        .btn-blue {
            background-color: #007bff;
        }

        .btn-blue:hover {
            background-color: #0056b3;
        }

        @media (max-width: 600px) {
            .navbar {
                flex-direction: column;
                gap: 10px;
            }

            .content {
                padding: 20px;
            }

            .content h2 {
                font-size: 24px;
            }

            .button-container {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="posting_approval.php">Posting Approval</a>
    <a href="users.php">Manage Users</a>
    <a href="admin_logout.php">Logout</a>
</div>

<div class="main-content">
    <div class="content">
        <h2>User Details: <?php echo htmlspecialchars($user['username']); ?></h2>

        <table>
            <tr>
                <th>User ID</th>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
            </tr>
            <tr>
                <th>Username</th>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
            </tr>
            <?php if (isset($user['full_name'])): ?>
            <tr>
                <th>Full Name</th>
                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
            </tr>
            <?php endif; ?>
            <?php if (isset($user['address'])): ?>
            <tr>
                <th>Address</th>
                <td><?php echo htmlspecialchars($user['address']); ?></td>
            </tr>
            <?php endif; ?>
            <?php if (isset($user['phone'])): ?>
            <tr>
                <th>Phone</th>
                <td><?php echo htmlspecialchars($user['phone']); ?></td>
            </tr>
            <?php endif; ?>
        </table>

        <div class="button-container">
            <a href="users.php" class="btn btn-blue">Back to Users</a>
            <a href="delete_user.php?id=<?php echo $user['id']; ?>" 
               class="btn" 
               onclick="return confirm('Are you sure you want to delete this user?');">
               Delete User
            </a>
        </div>
    </div>
</div>

</body>
</html>
