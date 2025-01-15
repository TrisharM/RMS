<?php
// Include your database connection file
include 'db_connection.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// If using Composer, include the autoload file
// require 'vendor/autoload.php';

// If using manual download, include these manually
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

// Function to send emails
function sendMail($to, $subject, $message) {
    $mail = new PHPMailer(true);  // Create a new PHPMailer instance

    try {
        // Server settings
        $mail->isSMTP();                                    // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                     // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                             // Enable SMTP authentication
        $mail->Username = 'trisharmahesh@gmail.com';       // SMTP username (your Gmail address)
        $mail->Password = 'jqok fcge obdg aylr';            // SMTP password (use App Password for Gmail)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587;                                  // TCP port to connect to

        // Recipients
        $mail->setFrom('Dhananjaya.r98@gmail.com', 'Restaurant Management'); // Set sender
        $mail->addAddress($to);                              // Add recipient

        // Content
        $mail->isHTML(true);                                 // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Send email
        $mail->send();
        return true; // Indicate success
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false; // Indicate failure
    }
}

// Process password reset request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handling the Forgot Password request
    if (isset($_POST['email'])) {
        $email = $_POST['email'];

        // Check if the email exists in the database
        $query = "SELECT * FROM customers WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            // Generate a unique token
            $token = bin2hex(random_bytes(50));
            $reset_link = "http://localhost/RMS/reset-password.php?token=$token";

            // Store the token in the database for this user
            $update_query = "UPDATE customers SET reset_token = '$token', token_expire = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = '$email'";
            mysqli_query($conn, $update_query);

            // Prepare the email content
            $subject = "Password Reset Request";
            $message = "Click <a href='$reset_link'>here</a> to reset your password.";

            // Send the reset email
            if (sendMail($email, $subject, $message)) {
                echo "Password reset link has been sent to your email.";
            } else {
                echo "Failed to send email. Please try again later.";
            }
        } else {
            echo "No account found with that email.";
        }
    }

    // Handling the Reset Password submission
    if (isset($_POST['token'])) {
        $token = $_POST['token'];
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        // Validate the token and update the password
        $query = "SELECT * FROM customers WHERE reset_token = '$token' AND token_expire > NOW()";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            // Fetch the user email from the result
            $user = mysqli_fetch_assoc($result);
            $email = $user['email'];

            // Update the password
            $update_query = "UPDATE customers SET password = '$new_password', reset_token = NULL, token_expire = NULL WHERE reset_token = '$token'";
            if (mysqli_query($conn, $update_query)) {
                // Prepare and send confirmation email
                $subject = "Password Reset Confirmation";
                $message = "Your password has been reset successfully. If you did not request this change, please contact support.";
                sendMail($email, $subject, $message);

                echo "Password has been reset successfully.";
                header("Location: login.html");
            } else {
                echo "Error updating password. Please try again.";
            }
        } else {
            echo "Invalid or expired token.";
        }
    }
}
?>
