<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Welcome back</h1>
    <form action="" method="post">
        <label for="client_username">Username:
            <input type="text" name="username" id="client_username" required>
        </label>
        <label for="client_password">Password:
            <input type="password" name="password" id="client_password" required>
        </label>
        <button type="submit">Log in</button>
    </form>
</body>
</html>

<?php
include 'connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $statement = $connection->prepare("SELECT * FROM CLIENTS WHERE username = ? AND password_hash = ?");
    $statement->bind_param("ss", $username, $password);  
    $statement->execute();

    $result = $statement->get_result();
    
    if ($result->num_rows > 0) {
        echo "Successful login!";
    } else {
        echo "Error: Invalid username or password.";
    }

    
    $statement->close();
    $connection->close();
}
?>
