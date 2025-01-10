<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>welcome back</h1>
    <form action="" method="post">
        <label for="admin_username">username : 
            <input type="text" name="username" id="admin_username">     
        </label>
        <label for="admin_password">password : 
            <input type="text" name="password" id="admin_password">
        </label>
        <button type="submit">log in</button>
    </form>
</body>
</html>
<?php
session_start();
include "connection.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Get the form data
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare and execute the query
    $statement = $connection->prepare("SELECT * FROM admins WHERE username = ? AND password_hash = ?");
    $statement->bind_param("ss", $username, $password);  // Use password_hash column
    $statement->execute();
    $result = $statement->get_result();

    // Check if admin is found
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc(); 
        $_SESSION["admin_name"] = $admin["username"]; // Store admin name in session
        header("Location: acceuiladmin.php"); // Redirect to the admin home page
        exit();
    } else {
        echo "Admin not found";
    }
}
?>