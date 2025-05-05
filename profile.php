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

$error_message = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'no_file_selected':
            $error_message = "No file selected. Please choose a file to upload.";
            break;
        case 'invalid_file_type':
            $error_message = "Invalid file type. Only jpg, jpeg, png, or gif images are allowed.";
            break;
        case 'upload_failed':
            $error_message = "Failed to upload the file. Please try again.";
            break;
        default:
            $error_message = '';
            break;
    }
}
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
        
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 150%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1; 
        }

        .profile-container {
            background: rgba(0, 0, 0, 0.6);
            border-radius: 16px;
            padding: 40px;
            max-width: 550px;
            width: 100%;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            text-align: center;
        }

        .profile-pic-container {
            position: relative;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 25px;
            border: 4px solid #f9c74f;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .profile-pic {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
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

        .upload-form input[type="file"],
        .upload-form input[type="password"] {
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
            color: rgb(219, 199, 18);
            font-weight: 600;
        }

        .profile-info p {
            margin-bottom: 10px;
        }

        .back-btn,
        .see-recipes-btn {
            display: inline-block;
            padding: 12px 24px;
            color: white;
            background-color: transparent;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: background-color 0.3s ease;
            font-size: 1em;
            margin-top: 15px;
        }

        .back-btn:hover,
        .see-recipes-btn:hover {
            background: var(--active-bg);
            box-shadow: 0 0 8px rgba(255,255,255,0.3);
            transform: scale(1.05);
        }

        .error-message {
            color: red;
            margin-bottom: 20px;
            font-size: 1.1em;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h2>Your Profile</h2>

    <?php if ($error_message): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

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

   
    <div class="profile-info">
        <h3 style="color: #f9c74f; text-align: center; margin-bottom: 10px;">Change Password</h3>

        <form action="send_verification_code.php" method="POST" class="upload-form">
            <label for="old_password">Old Password</label>
            <input type="password" id="old_password" name="old_password" required>

            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" required>

            <button type="submit">Send gmail Verification Code</button>
        </form>
    </div>

    <a href="posted_recipes.php" class="see-recipes-btn">See All Posted Recipes</a>
    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
</div>

</body>
</html>
