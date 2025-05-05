<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $verificationCode = rand(100000, 999999);

    $mail = new PHPMailer(true);

    try {
 
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'yourgmail@gmail.com';      
        $mail->Password   = 'your_app_password_here';   
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

       
        $mail->setFrom('yourgmail@gmail.com', 'FoodHub');
        $mail->addAddress($email);   

   
        $mail->isHTML(true);
        $mail->Subject = 'Your FoodHub Verification Code';
        $mail->Body    = "Your verification code is: <b>$verificationCode</b>";

        $mail->send();
        echo 'Verification code has been sent. Check your inbox.';
      
    } catch (Exception $e) {
        echo "Email could not be sent. Error: {$mail->ErrorInfo}";
    }
} else {
    echo 'No email provided.';
}
?>
