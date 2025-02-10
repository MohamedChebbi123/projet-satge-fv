<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'connection.php';

$currentDate = date('Y-m-d'); 
$sixMonthsBefore = date('Y-m-d', strtotime('+6 months'));
$oneWeekBefore = date('Y-m-d', strtotime('+1 week'));
$oneDayBefore = date('Y-m-d', strtotime('+1 day'));

$query = "SELECT name, renewal_date FROM websites WHERE renewal_date IN (?, ?, ?, ?)";
$stmt = $connection->prepare($query);
$stmt->bind_param("ssss", $sixMonthsBefore, $oneWeekBefore, $oneDayBefore, $currentDate);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hunterxh972@gmail.com';
        $mail->Password = 'ikgx rmkd gpkj swkr'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('hunterxh972@gmail.com', 'Website Renewal Notifier');
        $mail->addAddress('abdallahaltozeri@gmail.com', 'h');

        $mail->isHTML(true);
        $mail->Subject = 'Upcoming Website Renewal Reminder';

        $body = '<h1>Renewal Reminder</h1>';
        $body .= '<p>The following websites have upcoming or due renewal dates:</p>';
        $body .= '<ul>';

        while ($row = $result->fetch_assoc()) {
            $daysLeft = (strtotime($row['renewal_date']) - strtotime($currentDate)) / 86400;
            if ($daysLeft == 180) {
                $message = 'in 6 months';
            } elseif ($daysLeft == 7) {
                $message = 'in 1 week';
            } elseif ($daysLeft == 1) {
                $message = 'tomorrow';
            } else {
                $message = '<b style="color:red;">DUE TODAY!</b>';
            }

            $body .= "<li><strong>{$row['name']}</strong> (Renewal Date: {$row['renewal_date']}) - <b>$message</b></li>";
        }

        $body .= '</ul>';
        $mail->Body = $body;

        $mail->send();
        echo 'Renewal email sent successfully!';
    } catch (Exception $e) {
        echo "Email sending failed. Error: {$mail->ErrorInfo}";
    }
} else {
    echo 'No websites need renewal alerts today.';
}

$stmt->close();
$connection->close();
?>
