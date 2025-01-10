<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <h1>Admin Registration</h1>
        <input type="hidden" name="form_type" value="admin">
        <label for="admin_username">Username:</label>
        <input type="text" name="username" id="admin_username" required><br>
    
        <label for="admin_password">Password:</label>
        <input type="password" name="password" id="admin_password" required><br>
    
        <label for="admin_email">Email:</label>
        <input type="email" name="email" id="admin_email" required><br>
    
        <button type="submit">Create Account</button>
        <button onclick="window.location.href='loginadmin.php'">log in</button>
    </form>
    
    <form action="" method="post">
        <h1>Client Registration</h1>
        <input type="hidden" name="form_type" value="client">
        <label for="client_username">Username:</label>
        <input type="text" name="username" id="client_username" required><br>
    
        <label for="client_password">Password:</label>
        <input type="password" name="password" id="client_password" required><br>
    
        <label for="client_email">Email:</label>
        <input type="email" name="email" id="client_email" required><br>
    
        <button type="submit">Register</button>
        <button onclick="window.location.href='loginclient.php';">loginn</button>
    </form>
    
</body>
</html>

<?php
include "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formType = $_POST['form_type'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        if ($formType === 'admin') {
            $sql = 'INSERT INTO admins (username, password_hash, email) VALUES (?, ?, ?)';
        } elseif ($formType === 'client') {
            $sql = 'INSERT INTO clients (username, password_hash, email) VALUES (?, ?, ?)';
        } else {
            die("error type");
        }

        $statement = $connection->prepare($sql);
        if ($statement === false) {
            die("error stat " . $connection->error);
        }

        $statement->bind_param("sss", $username, $password, $email);

        if ($statement->execute()) {
            echo ucfirst($formType) . " data saved successfully!";
        } else {
            echo "error exec stat $formType: " . $statement->error;
        }
    } catch (Exception $e) {
        echo "error: " . $e->getMessage();
    }
}
?>

