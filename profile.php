<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require 'db_connection.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email, contact_number, birth_date, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Profile | FoodHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('bgp.jpg') no-repeat center center / cover;
            color: #fff; /* Darker text for better readability on various backgrounds */
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            min-height: 100vh; /* Ensure the background covers the entire viewport */
            margin: 0; /* Reset default body margin */
            box-sizing: border-box; /* Include padding and border in element's total width and height */
            
        }

        .profile-container {
            background: rgba(255, 255, 255, 0.9); /* Lighter background with transparency */
            border-radius: 16px; /* Slightly less rounded for a modern feel */
            padding: 40px;
            max-width: 550px; /* Slightly wider for better content flow */
            width: 100%;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1); /* More subtle and modern shadow */
            backdrop-filter: blur(10px); /* Keep the blur effect */
            text-align: center;
        }

        .profile-container {
    background: transparent;
    border-radius: 16px; /* Keep your existing styles */
    padding: 40px;
    max-width: 550px;
    width: 100%;
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    text-align: center;
}

        .profile-pic-container {
            position: relative;
            width: 140px; /* Slightly larger profile picture */
            height: 140px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 25px; /* Center the image and add more bottom spacing */
            border: 4px solid #f9c74f;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .profile-pic {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block; /* Prevent extra space below the image */
        }

        .upload-form {
            margin-bottom: 30px;
        }

        .upload-form label {
            display: block;
            margin-bottom: 10px;
            color: #fff;
            font-weight: bold;
            
        }

        .upload-form input[type="file"] {
            display: block;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 100%;
            box-sizing: border-box;
        }

        .upload-form button {
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

        .upload-form button:hover {
            background: var(--active-bg);
            box-shadow: 0 0 8px rgba(255,255,255,0.3);
            transform: scale(1.05);
        }

        .profile-info {
            font-size: 1.2em;
            line-height: 1.7;
            text-align: left;
            margin-bottom: 30px;
            color: #fff;
            font-weight: 300;
        }

        .profile-info strong {
            color:rgb(219, 199, 18);
            font-weight: 600;
        }

        .profile-info p {
            margin-bottom: 10px;
        }

        .back-btn {
            display: inline-block;
            padding: 12px 24px;
            color: white;
            background-color:transparent;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: background-color 0.3s ease;
            font-size: 1em;
        }

        .back-btn:hover {
            background: var(--active-bg);
            box-shadow: 0 0 8px rgba(255,255,255,0.3);
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h2>Your Profile</h2>

    <div class="profile-pic-container">
        <?php
        $profile_picture = !empty($user['profile_picture']) && file_exists("uploads/" . $user['profile_picture'])
                            ? "uploads/" . $user['profile_picture']
                            : "uploads/default.png";
        ?>
        <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-pic">
    </div>

    <form class="upload-form" action="upload_picture.php" method="POST" enctype="multipart/form-data">
        <label for="profile_image">Change Profile Picture</label>
        <input type="file" id="profile_image" name="profile_image" accept="image/*">
        <button type="submit">Upload New Picture</button>
    </form>

    <div class="profile-info">
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Contact:</strong> <?php echo htmlspecialchars($user['contact_number']); ?></p>
        <p><strong>Birth Date:</strong> <?php echo htmlspecialchars($user['birth_date']); ?></p>
    </div>

    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
</div>

</body>
</html>