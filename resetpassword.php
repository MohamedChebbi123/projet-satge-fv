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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.2);
        }
        .input-field {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
        }
        .input-field:focus {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
        }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.4);
        }
        .password-toggle {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .password-toggle:hover {
            color: #a5b4fc;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-gradient-to-br from-indigo-900/30 to-purple-900/30 backdrop-blur-sm"></div>
    
    <button onclick="window.history.back()" 
        class="fixed top-6 left-6 px-5 py-3 bg-gray-800/90 hover:bg-gray-900/90 text-white rounded-xl font-medium shadow-lg transition-all duration-300 flex items-center gap-2 z-10">
        <i class="fas fa-chevron-left text-sm"></i>
        <span>Back</span>
    </button>

    <div class="glass-card w-full max-w-md p-8 mx-4">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-5">
                <i class="fas fa-key text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Reset Your Password</h1>
            <p class="text-white/80 text-sm">Create a new secure password for your account</p>
        </div>

        <form action="" method="post" class="space-y-5">
            <div>
                <label for="new_password" class="block text-sm font-medium text-white/90 mb-2">New Password</label>
                <div class="relative">
                    <input 
                        type="password" 
                        name="new_password" 
                        id="new_password" 
                        required
                        placeholder="Enter at least 7 characters"
                        pattern=".{7,}"
                        class="input-field w-full px-5 py-3.5 text-white placeholder-white/60 rounded-xl focus:outline-none focus:ring-0 pr-12">
                    <i class="fas fa-eye-slash password-toggle absolute right-4 top-3.5 text-white/50" id="toggleNewPassword"></i>
                </div>
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-medium text-white/90 mb-2">Confirm Password</label>
                <div class="relative">
                    <input 
                        type="password" 
                        name="confirm_password" 
                        id="confirm_password" 
                        required
                        placeholder="Re-enter your new password"
                        class="input-field w-full px-5 py-3.5 text-white placeholder-white/60 rounded-xl focus:outline-none focus:ring-0 pr-12">
                    <i class="fas fa-eye-slash password-toggle absolute right-4 top-3.5 text-white/50" id="toggleConfirmPassword"></i>
                </div>
            </div>

            <button 
                type="submit" 
                class="btn-primary w-full px-5 py-3.5 text-white rounded-xl font-semibold mt-6 focus:outline-none">
                Update Password
                <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-white/70 text-sm">Remember your password? 
                <a href="form.php" class="text-white font-medium hover:underline">Sign in here</a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleNewPassword = document.getElementById('toggleNewPassword');
            const newPassword = document.getElementById('new_password');
            
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const confirmPassword = document.getElementById('confirm_password');
            
            [toggleNewPassword, toggleConfirmPassword].forEach((toggle, index) => {
                toggle.addEventListener('click', function() {
                    const passwordField = index === 0 ? newPassword : confirmPassword;
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);
                    
                    this.classList.toggle('fa-eye-slash');
                    this.classList.toggle('fa-eye');
                });
            });
        });
    </script>
</body>
</html>