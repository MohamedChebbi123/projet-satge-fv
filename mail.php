<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'connection.php'; // Ensure this file connects to your database properly

// Get today's date
$currentDate = date('Y-m-d'); 

// Fetch websites with renewal date matching today
$query = "SELECT name, renewal_date FROM websites WHERE DATE(renewal_date) = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $currentDate);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hunterxh972@gmail.com';
        $mail->Password = 'ikgx rmkd gpkj swkr'; // Use environment variables instead for security
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('hunterxh972@gmail.com', 'Website Renewal Notifier');
        $mail->addAddress('abdallahaltozeri@gmail.com', 'Boutah');

        $mail->isHTML(true);
        $mail->Subject = 'Website Renewal Reminder';

        // Construct email body
        $body = '<h1>Renewal Reminder</h1>';
        $body .= '<p>The following websites have reached their renewal date:</p>';
        $body .= '<ul>';

        while ($row = $result->fetch_assoc()) {
            $body .= "<li><strong>{$row['name']}</strong> (Renewal Date: {$row['renewal_date']})</li>";
        }

        $body .= '</ul>';
        $mail->Body = $body;

        $mail->send();
        echo 'Renewal email sent successfully!';
    } catch (Exception $e) {
        echo "Email sending failed. Error: {$mail->ErrorInfo}";
    }
} else {
    echo 'No websites need renewal today.';
}

// Close the database connection
$stmt->close();
$connection->close();
?>
