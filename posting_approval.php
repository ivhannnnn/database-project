<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

require 'db_connection.php';

$sql = "SELECT * FROM recipes WHERE status = 'pending' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Posting Approval - Admin Panel</title>
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
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            flex-direction: column;
            color: white;
        }

        .navbar {
            display: flex;
            justify-content: center;
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
            padding: 40px 30px;
            border-radius: 20px;
            background: var(--glass-bg);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .content h2 {
            font-size: 34px;
            margin-bottom: 20px;
            text-align: center;
            color: #ffffff;
            text-shadow: 0 3px 8px rgba(0, 0, 0, 0.7);
        }

        .recipe-card {
            background: rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        .recipe-card h3 {
            font-size: 24px;
        }

        .recipe-card p {
            font-size: 16px;
        }

        .actions {
            display: flex;
            gap: 15px;
        }

        .approve-btn, .reject-btn {
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            width: 48%;
        }

        .approve-btn {
            background-color: #28a745;
            color: white;
        }

        .approve-btn:hover {
            background-color: #218838;
        }

        .reject-btn {
            background-color: #dc3545;
            color: white;
        }

        .reject-btn:hover {
            background-color: #c82333;
        }

        .notification {
            background-color: #28a745;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="admin_dashboard.php" class="active" id="dashboardLink">Dashboard</a>
</div>

<div class="main-content" id="mainContent">
    <div class="content">
        <h2>Pending Approval Recipes</h2>

        <?php if (isset($_SESSION['notification'])): ?>
            <div class="notification">
                <?php echo $_SESSION['notification']; ?>
                <?php unset($_SESSION['notification']); ?>
            </div>
        <?php endif; ?>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="recipe-card">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                    <p><strong>Submitted by User ID:</strong> <?php echo htmlspecialchars($row['user_id']); ?></p>
                    <p><strong>Submission Date:</strong> <?php echo htmlspecialchars($row['created_at']); ?></p>
                    <img src="<?php echo !empty($row['image_path']) ? htmlspecialchars($row['image_path']) : 'https://via.placeholder.com/400x200?text=No+Image'; ?>" alt="Recipe Image" width="100%">
                    <div class="actions">
                        <form action="approve_or_reject.php" method="POST">
                            <input type="hidden" name="recipe_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="action" value="approve" class="approve-btn">Approve</button>
                            <button type="submit" name="action" value="reject" class="reject-btn">Reject</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No pending recipes for approval.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    document.getElementById('dashboardLink').addEventListener('click', function(e) {
        e.preventDefault();
        
      
        document.getElementById('mainContent').style.opacity = 0;

     
        setTimeout(function() {
            window.location.href = 'admin_dashboard.php';
        }, 500); 
    });
</script>

</body>
</html>
