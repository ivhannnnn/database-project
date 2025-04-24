<?php
session_start();
$conn = new mysqli("localhost", "root", "", "food_recipes");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['admin_username'];
    $password = $_POST['admin_password'];

    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result && $result->num_rows == 1) {
        $_SESSION['admin'] = $username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }
    body {
      background-image: url('photo.jpg'); 
      background-size: cover;
      background-position: center;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-container {
      background: rgba(0, 0, 0, 0.5);
      border-radius: 16px;
      padding: 40px;
      width: 350px;
      color: #fff;
      text-align: center;
      backdrop-filter: blur(5px);
      box-shadow: 0 0 20px rgba(0,0,0,0.4);
    }
    .login-container h2 {
      margin-bottom: 30px;
      font-weight: 600;
      font-size: 28px;
    }
    .login-container input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: none;
      border-radius: 25px;
      outline: none;
      text-align: center;
      font-size: 14px;
    }
    .login-btn {
      width: 100%;
      padding: 12px;
      margin-top: 20px;
      border: none;
      border-radius: 25px;
      background: white;
      color: #000;
      font-weight: bold;
      cursor: pointer;
      font-size: 16px;
      transition: background 0.3s;
    }
    .login-btn:hover {
      background: #ddd;
    }
    .error {
      color: #ff8080;
      margin-top: 10px;
    }
    .link-text {
      margin-top: 15px;
      font-size: 13px;
    }
    .link-text a {
      color: #fff;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Admin Login</h2>
    <form method="POST" action="">
      <input type="text" name="admin_username" placeholder="Admin Username" required>
      <input type="password" name="admin_password" placeholder="Password" required>
      <button type="submit" class="login-btn">Login</button>
      <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </form>
    <p class="link-text">Back to <a href="index.php">User Login</a></p>
  </div>
</body>
</html>