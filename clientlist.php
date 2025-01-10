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
    
    $sql = "SELECT username, email FROM clients";
    $result = $connection->prepare($sql);
    
    
    $result->execute();
    
    
    $result = $result->get_result();
    
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>Username: " . $row["username"] . "</p>";
            echo "<p>Email: " . $row["email"] . "</p>";
        }
    } else {
        echo "Data not found";
    }
    ?>