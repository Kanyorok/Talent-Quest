<?php
// Include Composer's autoloader
ini_set('max_execution_time', 300);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Update the path to the actual location of PHPMailer files
require 'PHPMailer/src/Exception.php'; // Adjust path accordingly
require 'PHPMailer/src/PHPMailer.php'; // Adjust path accordingly
require 'PHPMailer/src/SMTP.php';      // Adjust path accordingly

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the form data
    $name = $_POST['w3lName'];
    $email = $_POST['w3lSender'];
    $phone = $_POST['w3lPhone'];
    $message = $_POST['w3lMessage'];

    // Create an instance of PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                                      
        $mail->Host = 'mail.talentquestsports.co.ke'; // Specify your SMTP server
        $mail->SMTPAuth = true;                               
        $mail->Username = 'enquiries@talentquestsports.co.ke'; // SMTP email
        $mail->Password = 'Admin@.2024'; // SMTP email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   
        $mail->Port = 587;

        // Email settings
        $mail->setFrom($email, $name);
        $mail->addAddress('enquiries@talentquestsports.co.ke'); // Recipient's email

        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'New Enquiry From CLient Through Site';
        $mail->Body    = "
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Phone:</strong> $phone</p>
            <p><strong>Message:</strong><br>$message</p>
        ";
        $mail->AltBody = "Name: $name\nEmail: $email\nPhone: $phone\nMessage: $message"; // Plain text version

        $mail->send();
       // Redirect to the website after successful email
       header('Location: https://talentquestsports.co.ke');
       exit(); // Ensure no further processing occurs after the redirect
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
