<?php
session_start(); 

include "connection.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $code = $_POST['code'];

   
    $statement = $connection->prepare("SELECT * FROM clients WHERE   code = ?");
    $statement->bind_param("s", $code);
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows > 0) {
         
        $_SESSION['code'] = $code; 
        echo "Code verified successfully. You can now reset your password.";
        header("location: reset_password_client.php");
        exit(); 
    } else {
        echo "Invalid verification code.";
    }
    
    $statement->close();
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center min-h-screen">
    <form action="" method="post" class="bg-white/30 backdrop-blur-lg p-6 rounded-xl shadow-2xl border border-white/20 w-full max-w-md">
        <h1 class="text-3xl font-extrabold text-center text-white mb-6">Verify Code</h1>
        <p>a verification code has been sent to your email,check it</p>
        <div class="space-y-4">
            <label for="code" class="block text-white font-medium mb-2">Enter verification code:</label>
            <input 
                type="text" 
                name="code" 
                id="code" 
                required 
                placeholder="enter your verification code "
                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-indigo-400 focus:outline-none">
        </div>
        
        <button 
            type="submit" 
            class="w-full mt-6 px-4 py-3 bg-indigo-600 text-white rounded-lg font-semibold shadow-lg hover:bg-indigo-700 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-300">
            Verify
        </button>
    </form>
</body>
</html>

