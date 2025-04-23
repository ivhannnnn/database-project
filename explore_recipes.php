<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require 'db_connection.php';

// Fetch all recipes
$sql = "SELECT * FROM recipes ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Explore Recipes - FoodHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('bgp.jpg') no-repeat center center / cover;
            color: #fff;
            margin: 0;
            padding: 40px;
        }

        a.back-btn {
            display: inline-block;
            margin-bottom: 30px;
            padding: 12px 20px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.3s ease, transform 0.2s ease;
            margin-top: 20px;
            font-size: 18px;
            text-align: center;
        }

        a.back-btn:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: scale(1.05);
        }

        h1 {
            text-align: center;
            font-size: 36px;
            margin-bottom: 40px;
            text-shadow: 0 2px 6px rgba(0,0,0,0.6);
        }

        .recipe-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .card {
            background: rgba(255,255,255,0.1);
            border-radius: 18px;
            padding: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            text-decoration: none;
            color: #fff;
            transition: transform 0.25s ease, background 0.3s ease;
        }

        .card:hover {
            transform: scale(1.03);
            background: rgba(255,255,255,0.15);
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

<a href="dashboard.php" class="back-btn">← Back to Dashboard</a>

<h1>Explore Recipes 🍴</h1>

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

</body>
</html>