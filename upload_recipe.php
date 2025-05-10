<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Recipe - FoodHub</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root {
        --white: #ffffff;
        --glass-bg: rgba(255, 255, 255, 0.05);
        --glass-border: rgba(255, 255, 255, 0.1);
        --nav-glass-bg: rgba(0, 0, 0, 0.2);
        --nav-hover-bg: rgba(255, 255, 255, 0.1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: url('bgp.jpg') no-repeat center center / cover;
        background-attachment: fixed;
        color: white;
        overflow-y: auto;
        min-height: 100vh;
    }

    body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 150%;
        background: rgba(3, 3, 3, 0.5);
        z-index: -1;
    }

    #pageContainer {
        display: flex;
        flex-direction: column;
        min-height: 100%;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    #pageContainer.fade-in {
        opacity: 1;
    }

    #pageContainer.fade-out {
        opacity: 0;
        pointer-events: none;
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
        color: var(--white);
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
        background: var(--nav-hover-bg);
        transform: scale(1.05);
    }

    .navbar i {
        margin-right: 8px;
    }

    .main-content {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 100px 20px 40px;
    }

    .content {
        max-width: 950px;
        width: 90%;
        padding: 40px 30px;
        border-radius: 20px;
        background: var(--glass-bg);
        backdrop-filter: blur(2px);
        border: 1px solid var(--glass-border);
        box-shadow: 0 10px 40px rgba(163, 154, 154, 0.3);
    }

    .content h2 {
        font-size: 34px;
        margin-bottom: 20px;
        text-align: center;
        text-shadow: 0 3px 8px rgba(12, 12, 12, 0.7);
    }

    .form-container {
        max-width: 420px;
        width: 100%;
        margin: 0 auto;
        background: rgba(0, 0, 0, 0.5);
        padding: 25px 20px;
        border-radius: 16px;
        text-align: center;
    }

    label {
        font-weight: 600;
        display: block;
        margin-bottom: 8px;
        text-align: left;
    }

    input, textarea, button {
        width: 100%;
        padding: 12px;
        margin-top: 15px;
        font-size: 16px;
        background-color: #fff;
        border: 1px solid rgb(230, 230, 230);
        border-radius: 10px;
    }

    textarea {
        resize: vertical;
    }

    button {
        background-color: transparent;
        border: 2px solid #fff;
        color: #fff;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s, color 0.3s, border-color 0.3s;
    }

    button:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
        border-color: transparent;
    }
  </style>
</head>
<body>
<div id="pageContainer">
    <div class="navbar">
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    </div>

    <div class="main-content">
        <div class="content">
            <h2>Upload a New Recipe</h2>
            <div class="form-container">
                <form action="handle_upload.php" method="POST" enctype="multipart/form-data">
                    <label for="title">Recipe Title</label>
                    <input type="text" name="title" id="title" required>

                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="4" required></textarea>

                    <label for="ingredients">Ingredients</label>
                    <textarea name="ingredients" id="ingredients" rows="4" required></textarea>

                    <label for="steps">Steps</label>
                    <textarea name="steps" id="steps" rows="4" required></textarea>

                    <label for="image">Recipe Image</label>
                    <input type="file" name="image" id="image" accept="image/*">

                    <button type="submit">Post Recipe</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const pageContainer = document.getElementById('pageContainer');
    const dashboardLink = document.querySelector('.navbar a');

    window.addEventListener('load', () => {
        pageContainer.classList.add('fade-in');
    });

    dashboardLink.addEventListener('click', function (e) {
        e.preventDefault();
        pageContainer.classList.remove('fade-in');
        pageContainer.classList.add('fade-out');
        setTimeout(() => {
            window.location.href = this.getAttribute('href');
        }, 200);
    });
</script>
</body>
</html>
