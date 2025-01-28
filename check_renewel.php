<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
<?php
include "connection.php";

$date_now = new DateTime('now');
$current_date = $date_now->format('Y-m-d');

$sql = "SELECT name, renewal_date, repo FROM websites WHERE renewal_date < ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $current_date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $website_name = $row['name'];
        $renewal_date = $row['renewal_date'];
        $repo_url = $row['repo'];

        
        $message = "Website '$website_name' is overdue for renewal since $renewal_date. Repository: $repo_url";
        
        $to = "hunterxh972@gmail.com";
        $subject = "Renewal Overdue Notification";
        $headers = "From: no-reply@example.com";
        mail($to, $subject, $message, $headers);

        error_log($message, 3, "overdue_logs.txt");
    }
} else {
    error_log("No overdue renewals as of $current_date\n", 3, "overdue_logs.txt");
}

$stmt->close();
$connection->close();
?>
