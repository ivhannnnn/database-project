<?php
session_start();
require 'db_connection.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    $stmt = $conn->prepare("SELECT password, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password, $email);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($old_password, $hashed_password)) {
        // Show an alert and then go back to the previous page
        echo "<script>
                alert('Old password is incorrect.');
                window.history.back();
              </script>";
        exit;
    }

    $code = rand(100000, 999999);
    $_SESSION['verification_code'] = $code;
    $_SESSION['new_password'] = password_hash($new_password, PASSWORD_DEFAULT);

    $subject = "FoodHub Password Change Verification";
    $message = "Your verification code is: $code";
    $headers = "From: no-reply@foodhub.com";

    mail($email, $subject, $message, $headers);

    header("Location: verify_code.php");
    exit;
}
?>
