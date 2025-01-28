<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-sm">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Welcome Back</h1>
        <form action="" method="post" class="space-y-4">
            <div>
                <label for="client_username" class="block text-gray-700 font-medium mb-2">Username:</label>
                <input 
                    type="text" 
                    name="username" 
                    id="client_username" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                    placeholder="Enter your username" 
                    required>
            </div>
            <div>
                <label for="client_password" class="block text-gray-700 font-medium mb-2">Password:</label>
                <input 
                    type="password" 
                    name="password" 
                    id="client_password" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                    placeholder="Enter your password" 
                    required>
            </div>
            <button 
                type="submit" 
                class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                Log In
            </button>
        </form>
        <p class="text-center text-gray-500 mt-4">
            Don't have an account? <a href="form.php" class="text-blue-500 hover:underline">Register here</a>
        </p>
    </div>
</body>
</html>

<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $statement = $connection->prepare("SELECT * FROM clients WHERE username = ? AND password_hash = ?");
    $statement->bind_param("ss", $username, $password);
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows > 0) {
        $client = $result->fetch_assoc();
        $_SESSION["client_id"] = $client["id"];
        $_SESSION["client_name"] = $client["username"];
        header("location:welcome.php");
        exit();
    } else {
        echo "<div class='mt-4 text-center text-red-500'>Error: Invalid username or password.</div>";
    }

    $statement->close();
    $connection->close();
}
?>
