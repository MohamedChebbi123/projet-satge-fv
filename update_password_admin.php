<?php
session_start(); 
include "connection.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST['code'];

    $statement = $connection->prepare("SELECT * FROM admins WHERE code = ?");
    $statement->bind_param("s", $code);
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['code'] = $code; 
        echo "<div class='notification success'>Code verified successfully. Redirecting...</div>";
        header("Refresh: 2; url=resetpassword.php");
        exit(); 
    } else {
        echo "<div class='notification error'>Invalid verification code. Please try again.</div>";
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
    <title>Verify Code | Account Security</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #f59e0b;
            --dark: #1e293b;
            --light: #f8fafc;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.18);
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
        }
        
        .input-field {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            color: white;
        }
        
        .input-field:focus {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
        }
        
        .input-field::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            animation: slideIn 0.5s forwards, fadeOut 0.5s 3s forwards;
        }
        
        .success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .code-input {
            letter-spacing: 0.5em;
            font-size: 1.5rem;
            text-align: center;
            padding-left: 0.5em;
        }
        
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            pointer-events: none;
            animation: float 15s linear infinite;
        }
        
        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); opacity: 1; }
            100% { transform: translate(calc(100vw * var(--x)), calc(-100vh * var(--y))) rotate(360deg); opacity: 0; }
        }
    </style>
</head>
<body class="relative overflow-hidden">
    <div id="particles"></div>
    
    <button onclick="window.history.back()" 
            class="fixed top-6 left-6 z-10 px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-full font-medium shadow-lg backdrop-blur-md transition-all duration-300 flex items-center gap-2 border border-white/20">
            <i class="fas fa-arrow-left"></i>
            <span>Back</span>
    </button>
    
    <div class="glass-card w-full max-w-md mx-4 p-8 relative overflow-hidden">
        <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-indigo-400/10 blur-xl"></div>
        <div class="absolute -bottom-20 -left-20 w-40 h-40 rounded-full bg-purple-400/10 blur-xl"></div>
        
        <div class="flex justify-center mb-6">
            <div class="bg-white/20 p-4 rounded-2xl shadow-lg floating">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
        </div>
        
        <h1 class="text-3xl font-bold text-center text-white mb-2">Verify Your Identity</h1>
        <p class="text-center text-white/80 mb-6">We've sent a 4-digit verification code to your email</p>
        
        <form action="" method="post" class="space-y-6">
            <div class="space-y-2">
                <label for="code" class="block text-white font-medium">Verification Code</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-shield-alt text-white/60"></i>
                    </div>
                    <input 
                        type="text" 
                        name="code" 
                        id="code" 
                        required 
                        maxlength="4"
                        pattern="\d{4}"
                        inputmode="numeric"
                        placeholder="••••"
                        class="w-full pl-10 pr-4 py-3 input-field code-input rounded-lg placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/30"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                </div>
                <p class="text-sm text-white/60 mt-1">Check your email for the 4-digit code</p>
            </div>
            
            <button 
                type="submit" 
                class="w-full btn-primary py-3 px-4 rounded-lg font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-check-circle mr-2"></i> Verify Code
            </button>
            
            
        </form>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('particles');
            const particleCount = 15;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                const size = Math.random() * 4 + 2;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                particle.style.left = `${Math.random() * 100}vw`;
                particle.style.top = `${Math.random() * 100}vh`;
                
                particle.style.setProperty('--x', Math.random());
                particle.style.setProperty('--y', Math.random());
                
                const duration = Math.random() * 10 + 10;
                particle.style.animationDuration = `${duration}s`;
                
                container.appendChild(particle);
            }
            
            document.getElementById('code').focus();
            
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
                setTimeout(() => {
                    notification.remove();
                }, 3500);
            });
            
            const inputs = document.querySelectorAll('.code-input');
            inputs.forEach((input, index) => {
                input.addEventListener('input', function() {
                    if (this.value.length === this.maxLength) {
                        if (inputs[index + 1]) {
                            inputs[index + 1].focus();
                        }
                    }
                });
                
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value.length === 0) {
                        if (inputs[index - 1]) {
                            inputs[index - 1].focus();
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>