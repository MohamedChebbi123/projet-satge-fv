<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full bg-white/30 backdrop-blur-lg p-8 rounded-xl shadow-2xl border border-white/20">
        <h1 class="text-3xl font-extrabold text-center text-white mb-6">Welcome Back</h1>
        <form action="" method="post" class="space-y-6">
            <div>
                <label for="client_username" class="block text-white font-medium mb-2">Username:</label>
                <input 
                    type="text" 
                    name="username" 
                    id="client_username" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-indigo-400 focus:outline-none" 
                    placeholder="Enter your username" 
                    required>
            </div>
            <div>
                <label for="client_password" class="block text-white font-medium mb-2">Password:</label>
                <input 
                    type="password" 
                    name="password" 
                    id="client_password" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-indigo-400 focus:outline-none" 
                    placeholder="Enter your password" 
                    required>
            </div>
            <button 
                type="submit" 
                class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg font-semibold shadow-lg hover:bg-indigo-700 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                Log In
            </button>
            
        </form>
        <p class="text-center text-white mt-4">
            Forgot your password? <a href="verifyclient.php" class="text-indigo-300 hover:underline">Reset here</a>
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
