<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$error = "";

// === Validation ===
if (!isset($_POST['name']) || trim($_POST['name']) === "") {
    $error .= "Name is required<br>";
} elseif (!preg_match('/^[a-zA-Z\s]+$/', $_POST['name'])) {
    $error .= "Enter a valid name (letters and spaces only)<br>";
}

if (!isset($_POST['mail']) || trim($_POST['mail']) === "") {
    $error .= "Email is required<br>";
} elseif (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
    $error .= "Enter a valid email address<br>";
}

if (!isset($_POST['phone']) || trim($_POST['phone']) === "") {
    $error .= "Phone number is required<br>";
} elseif (!preg_match('/^[0-9+\-\s]{7,15}$/', $_POST['phone'])) {
    $error .= "Enter a valid phone number<br>";
}

if (!isset($_POST['purpose']) || trim($_POST['purpose']) === "") {
    $error .= "Purpose of contact is required<br>";
}

if (!isset($_POST['category']) || trim($_POST['category']) === "") {
    $error .= "Product category is required<br>";
}

if (!isset($_POST['fav_language'])) {
    $error .= "Preferred contact method is required<br>";
}

if (!isset($_POST['message']) || trim($_POST['message']) === "") {
    $error .= "Message is required<br>";
}

if ($error === "") {
    // === Sanitize input ===
    $name        = htmlspecialchars(trim($_POST['name']));
    $email       = htmlspecialchars(trim($_POST['mail']));
    $phone       = htmlspecialchars(trim($_POST['phone']));
    $purpose     = htmlspecialchars(trim($_POST['purpose']));
    $category    = htmlspecialchars(trim($_POST['category']));
    $contactType = htmlspecialchars(trim($_POST['fav_language']));
    $message     = htmlspecialchars(trim($_POST['message']));

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
     $mail->Username   = 'netcomenquiry@gmail.com'; 
  $mail->Password   = 'nxhcgknjdzgthqpa';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        $mail->setFrom('info@vinsarkfoods.com', 'Vinsark Food Pvt Ltd');
        $mail->addReplyTo($email, $name);
        $mail->addAddress('info@vinsarkfoods.com');

        $mail->isHTML(true);
        $mail->Subject = 'Vinsark Food Pvt Ltd - New Contact Request';

        $mail->Body = "
            <html><body>
            <table border='1' cellpadding='10'>
                <tr><td colspan='2' style='color:#C50B33; font-size:18px;'><strong>New Contact Form Submission</strong></td></tr>
                <tr><td><strong>Name:</strong></td><td>$name</td></tr>
                <tr><td><strong>Email:</strong></td><td>$email</td></tr>
                <tr><td><strong>Phone:</strong></td><td>$phone</td></tr>
                <tr><td><strong>Purpose of Contact:</strong></td><td>$purpose</td></tr>
                <tr><td><strong>Product Category:</strong></td><td>$category</td></tr>
                <tr><td><strong>Preferred Contact Method:</strong></td><td>$contactType</td></tr>
                <tr><td><strong>Message:</strong></td><td>$message</td></tr>
            </table>
              <style>
           .channel{
            background-color: #c9ded1 !important;
        }
    </style>

</body></html>
        ";

        if ($mail->send()) {
            // === Confirmation email to user ===
            $mail->clearAllRecipients();
            $mail->addAddress($email);
            $mail->Subject = 'Vinsark Food Pvt Ltd - Thank You';
            $mail->Body = "
                <html><body>
                <h2>Thank you, $name!</h2>
                <p>We’ve received your inquiry regarding <strong>$purpose</strong> in <strong>$category</strong>.</p>
                <p>Our team will get in touch with you via your preferred method: <strong>$contactType</strong>.</p>
                <br><p><strong>– Vinsark Food Pvt Ltd</strong></p>
                  <style>
           .channel{
            background-color: #c9ded1 !important;
        }
    </style>

</body></html>
            ";
            $mail->send();

            echo "sent";
        } else {
            echo "Sorry, an error occurred while sending the form details.";
        }
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
} else {
    echo $error;
}
?>
