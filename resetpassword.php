<?php
session_start();
include "connection.php";
if(!isset($_SESSION["code"])){
    header("location:form.php");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password=$_POST["new_password"];
    $confirm_password=$_POST["confirm_password"];
    if($new_password==$confirm_password){
        $code=$_SESSION["code"];
        $statement=$connection->prepare("UPDATE admins SET password_hash=? WHERE code=?");
        $statement->bind_param("ss",$confirm_password,$code);
        $statement->execute();
        if ($statement->affected_rows > 0) {
            echo "Password reset successfully.";
            session_destroy();  
            header("location:form.php");  
        } else {
            echo "Error resetting password. Please try again.";
        }
        $statement->close();
        $connection->close();
    }
}

    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center min-h-screen">
    <form action="" method="post" class="bg-white/30 backdrop-blur-lg p-6 rounded-xl shadow-2xl border border-white/20 w-full max-w-md">
        <h1 class="text-3xl font-extrabold text-center text-white mb-6">Change Password</h1>
        
        <div class="space-y-4">
            <label for="new_password" class="block text-white font-medium mb-2">Type your new password:</label>
            <input 
                type="password" 
                name="new_password" 
                id="new_password" 
                required 
                pattern=".{7,}" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-indigo-400 focus:outline-none">

            <label for="confirm_password" class="block text-white font-medium mb-2">Confirm your password:</label>
            <input 
                type="password" 
                name="confirm_password" 
                id="confirm_password" 
                required 
                placeholder="confirm your new password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-indigo-400 focus:outline-none">
        </div>
        
        <button 
            type="submit" 
            class="w-full mt-6 px-4 py-3 bg-indigo-600 text-white rounded-lg font-semibold shadow-lg hover:bg-indigo-700 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-300">
            Submit
        </button>
    </form>
</body>
</html>

