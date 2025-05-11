<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require 'db_connection.php';

$user_id = $_SESSION['user_id'];


$sql = "SELECT * FROM recipes WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>See All Posted Recipes | FoodHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('bgp.jpg') no-repeat center center / cover;
            background-attachment: fixed;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            min-height: 100vh;
            margin: 0;
            box-sizing: border-box;
        }

        .recipes-container {
            background: rgba(0, 0, 0, 0.6);
            border-radius: 16px;
            padding: 40px;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            text-align: center;
        }
      .view-recipe {
        color: #fff;
      }
.view-recipe:hover {
         transform: scale(2.05);
            background: rgba(255, 255, 255, 0.15);
      }
        h2 {
            font-size: 2em;
            margin-bottom: 30px;
            color: #fff;
        }

        .recipe-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
            margin-bottom: 20px;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .recipe-card:hover {
            transform: scale(1.05);
            background: rgba(255, 255, 255, 0.15);
        }

        .recipe-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
        }

        .recipe-card h3 {
            font-size: 1.5em;
            margin-top: 15px;
        }

        .recipe-card p {
            color: #eee;
            font-size: 1.1em;
        }

        .delete-btn {
            background-color: red;
            color: white;
            padding: 8px 16px;
            font-size: 1em;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .delete-btn:hover {
            background-color: darkred;
            transform: scale(1.05);
        }

        .back-btn {
            display: inline-block;
            padding: 12px 24px;
            color: white;
            background-color: transparent;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: background-color 0.3s ease;
            font-size: 1em;
            margin-top: 20px;
        }

        .back-btn:hover {
            background: var(--active-bg);
            box-shadow: 0 0 8px rgba(255,255,255,0.3);
            transform: scale(1.05);
        }

        .no-recipes {
            font-size: 1.5em;
            color: #fff;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="recipes-container">
    <h2>See All Posted Recipes</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="recipe-card">
                <img src="<?php echo !empty($row['image_path']) ? htmlspecialchars($row['image_path']) : 'https://via.placeholder.com/400x200?text=No+Image'; ?>" alt="Recipe Image">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo substr(htmlspecialchars($row['description']), 0, 100) . '...'; ?></p>
                
                <a href="recipe.php?id=<?php echo $row['id']; ?>" class="view-recipe">View Full Recipe</a>
                
           
                <form action="delete_recipe.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this recipe?');">
                    <input type="hidden" name="recipe_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="delete-btn">Delete Recipe</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-recipes">You haven't posted any recipes yet.</div>
    <?php endif; ?>

    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
</div>

</body>
</html>
