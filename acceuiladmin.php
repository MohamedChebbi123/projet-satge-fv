<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="clientlist.php">clientlist</a>
    <a href="listwebsite.php">listwebsite</a>
    
</body>
</html>
<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_name'])) {
    header("Location: loginadmin.php"); // Redirect to login if not logged in
    exit();
}

$admin_name = $_SESSION['admin_name']; // Get admin name from session
echo "$admin_name";

?>