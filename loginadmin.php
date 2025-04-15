<?php
session_start();
include "connection.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $statement = $connection->prepare("SELECT * FROM admins WHERE username = ? AND password_hash = ?");
    $statement->bind_param("ss", $username, $password);
    $statement->execute();
    $result = $statement->get_result();

    
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $_SESSION["admin_name"] = $admin["username"];
        $_SESSION["admin_id"] = $admin["id"];
        header("Location: acceuiladmin.php");
        exit();
    } else {
        echo "<div class='error-message fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-red-500 text-white py-3 px-6 rounded-lg shadow-lg flex items-center'>
                <i class='fas fa-exclamation-circle mr-2'></i>
                Error: Admin not found
              </div>";
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
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }
        
        .input-field {
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
        }
        
        .btn-login {
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .password-toggle {
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        
        .error-message {
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="login-container max-w-md w-full p-8 rounded-2xl mx-4">
        <div class="flex justify-center mb-8">
            <div class="w-20 h-20 bg-white rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-shield-alt text-4xl text-indigo-600"></i>
            </div>
        </div>
        
        <h1 class="text-3xl font-bold text-center text-white mb-2">Admin Portal</h1>
        <p class="text-center text-white/80 mb-8">Secure administration dashboard</p>
        
        <form action="" method="post" class="space-y-6">
            <div>
                <label for="admin_username" class="block text-sm font-medium text-white/90 mb-2">Username</label>
                <div class="relative">
                    <input 
                        type="text" 
                        name="username" 
                        id="admin_username" 
                        class="input-field w-full px-4 py-3 pl-10 border border-white/20 rounded-lg text-white placeholder-white/70 focus:outline-none" 
                        placeholder="Enter admin username" 
                        required>
                    <div class="absolute left-3 top-3 text-white/50">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>
            
            <div>
                <label for="admin_password" class="block text-sm font-medium text-white/90 mb-2">Password</label>
                <div class="relative">
                    <input 
                        type="password" 
                        name="password" 
                        id="admin_password" 
                        class="input-field w-full px-4 py-3 pl-10 border border-white/20 rounded-lg text-white placeholder-white/70 focus:outline-none pr-10" 
                        placeholder="Enter password" 
                        required>
                    <div class="absolute left-3 top-3 text-white/50">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="password-toggle absolute text-white/70 hover:text-white" onclick="togglePassword()">
                        <i class="far fa-eye" id="toggleIcon"></i>
                    </div>
                </div>
            </div>
            
            <button 
                type="submit" 
                class="btn-login w-full bg-white text-indigo-600 py-3 px-4 rounded-lg font-semibold hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600">
                <i class="fas fa-sign-in-alt mr-2"></i> Login
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="verifyadmin.php" class="text-sm text-indigo-200 hover:text-white hover:underline">Forgot password?</a>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('admin_password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>

