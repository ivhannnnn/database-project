<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

require 'db_connection.php';

$sql = "SELECT id, username, email FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin Panel</title>
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
            opacity: 1;
            transition: opacity 0.5s ease;
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
            max-height: 500px;
            overflow-y: auto;
        }

        .content h2 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #ffffff;
            text-shadow: 0 3px 8px rgba(0, 0, 0, 0.7);
        }

        table {
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

        .view-btn {
            background-color: #28a745;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .view-btn:hover {
            background-color: #218838;
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
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="admin_dashboard.php" class="active">Dashboard</a> 
</div>

<div class="main-content" id="mainContent">
    <div class="content">
        <h2>All Registered Users</h2>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>
                                <a href="view_user.php?id=<?php echo $row['id']; ?>" class="view-btn">View</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    </div>
</div>

<script>
 
    const links = document.querySelectorAll('.navbar a');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetUrl = this.href;

           
            document.getElementById('mainContent').style.opacity = 0;


            setTimeout(function() {
                window.location.href = targetUrl;
            }, 200); 
        });
    });
</script>

</body>
</html>
