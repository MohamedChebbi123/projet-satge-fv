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
        $statement=$connection->prepare("UPDATE clients SET password_hash=? WHERE code=?");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .form-container {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        .input-field {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
        }
        .input-field:focus {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
        }
        .btn-submit {
            transition: all 0.3s ease;
            background: linear-gradient(to right, #4f46e5, #7c3aed);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
        }
        .btn-return {
            transition: all 0.3s ease;
            background: rgba(31, 41, 55, 0.8);
        }
        .btn-return:hover {
            background: rgba(17, 24, 39, 0.9);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <button onclick="window.history.back()" 
        class="btn-return fixed top-6 left-6 px-5 py-3 text-white rounded-xl font-semibold shadow-lg flex items-center gap-2 z-10">
        <i class="fas fa-arrow-left"></i>
        <span>Back</span>
    </button>

    <div class="form-container w-full max-w-md p-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-lock-open text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Reset Password</h1>
            <p class="text-white/80">Create a new secure password</p>
        </div>

        <form action="" method="post" class="space-y-6">
            <div>
                <label for="new_password" class="block text-sm font-medium text-white/90 mb-2">New Password</label>
                <div class="relative">
                    <input 
                        type="password" 
                        name="new_password" 
                        id="new_password" 
                        placeholder="Enter your new password"
                        pattern=".{7,}" 
                        required 
                        class="input-field w-full px-5 py-3 text-white placeholder-white/70 rounded-xl focus:outline-none focus:ring-0">
                    <i class="fas fa-key absolute right-4 top-3.5 text-white/50"></i>
                </div>
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-medium text-white/90 mb-2">Confirm Password</label>
                <div class="relative">
                    <input 
                        type="password" 
                        name="confirm_password" 
                        id="confirm_password" 
                        placeholder="Confirm your new password"
                        required 
                        class="input-field w-full px-5 py-3 text-white placeholder-white/70 rounded-xl focus:outline-none focus:ring-0">
                    <i class="fas fa-key absolute right-4 top-3.5 text-white/50"></i>
                </div>
            </div>

            <button 
                type="submit" 
                class="btn-submit w-full px-5 py-3.5 text-white rounded-xl font-semibold mt-8 focus:outline-none">
                Update Password
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-white/70 text-sm">Remember your password? 
                <a href="form.php" class="text-white font-medium hover:underline">Sign in</a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordFields = document.querySelectorAll('input[type="password"]');
            passwordFields.forEach(field => {
                const icon = field.nextElementSibling;
                icon.addEventListener('click', function() {
                    if (field.type === 'password') {
                        field.type = 'text';
                        icon.classList.remove('fa-key');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        field.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-key');
                    }
                });
            });
        });
    </script>
</body>
</html>