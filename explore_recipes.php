<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require 'db_connection.php';

$searchQuery = '';
if (isset($_GET['search'])) {
    $searchQuery = htmlspecialchars($_GET['search']);
}

$sql = "SELECT * FROM recipes WHERE status = 'approved' AND (title LIKE '%$searchQuery%' OR description LIKE '%$searchQuery%') ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Explore Recipes - FoodHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('bgp.jpg') no-repeat center center / cover;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #fff;
            overflow-y: auto;
            min-height: 100vh;
        }

        .fade-out {
            animation: fadeOut 0.5s forwards;
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: scale(0.70);
            }
        }

        .page-container {
            transition: all 0.2s ease;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 130%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        .navbar {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            padding: 10px 20px;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            width: 100%;
            position: absolute;
            top: 0;
            z-index: 10;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: 600;
            background: transparent;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .navbar a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        .navbar i {
            margin-right: 8px;
        }

        h1 {
            text-align: center;
            font-size: 36px;
            margin-top: 100px;
            margin-bottom: 40px;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.6);
        }

        .search-bar {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .search-bar input {
            padding: 10px;
            font-size: 16px;
            width: 300px;
            border-radius: 25px;
            border: 2px solid #fff;
            outline: none;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            transition: 0.3s ease;
        }

        .search-bar input:focus {
            border-color: rgb(42, 177, 102);
        }

        .search-bar input::placeholder {
            color: #fff;
        }

        .recipe-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            max-width: 1200px;
            width: 90%;
            margin: 0 auto;
            padding-bottom: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 18px;
            padding: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            text-decoration: none;
            color: #fff;
            transition: transform 0.25s ease, background 0.3s ease;
        }

        .card:hover {
            transform: scale(1.03);
            background: rgba(255, 255, 255, 0.15);
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 12px;
        }

        .card h3 {
            margin-top: 15px;
            font-size: 22px;
        }

        .card p {
            font-size: 15px;
            margin-top: 10px;
            color: #eee;
        }

        .no-recipes {
            text-align: center;
            font-size: 20px;
            margin-top: 80px;
            color: #eee;
        }
    </style>
</head>
<body>

<div class="page-container" id="pageContainer">
    <div class="navbar">
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    </div>

    <h1>Explore Recipes 🍴</h1>

    <div class="search-bar">
        <form action="explore_recipes.php" method="get">
            <input type="text" name="search" value="<?php echo $searchQuery; ?>" placeholder="Search recipes..." />
        </form>
    </div>

    <div class="recipe-container">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <a href="recipe.php?id=<?php echo $row['id']; ?>" class="card">
                    <img src="<?php echo !empty($row['image_path']) ? htmlspecialchars($row['image_path']) : 'https://via.placeholder.com/400x200?text=No+Image'; ?>" alt="Recipe Image">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><?php echo substr(htmlspecialchars($row['description']), 0, 100) . '...'; ?></p>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-recipes">No recipes found. Be the first to upload one!</div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.querySelector('.navbar a').addEventListener('click', function(e) {
        e.preventDefault();
        const container = document.getElementById('pageContainer');
        container.classList.add('fade-out');
        setTimeout(() => {
            window.location.href = 'dashboard.php';
        }, 200);
    });
</script>

</body>
</html>
