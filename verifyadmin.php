<?php
include "connection.php"; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = ($_POST['email']);
    
    $statement = $connection->prepare("SELECT * FROM admins WHERE email = ?");
    $statement->bind_param("s", $email);
    $statement->execute();
    $result = $statement->get_result();

    if (!$result) {
        die("Query failed: " . $connection->error);
    }

    if ($result->num_rows > 0) {
        
            $code = rand(1000, 9999);
            $reset_stmt=$connection->prepare("UPDATE admins SET code=? WHERE email=?");
            $reset_stmt->bind_param("ss",$code,$email);
            $reset_stmt->execute();
            $reset_stmt->close();
        
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'hunterxh972@gmail.com'; 
            $mail->Password = 'ikgx rmkd gpkj swkr'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('hunterxh972@gmail.com', 'افسح الطريق');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Verification Code';
            $mail->Body = "<h1>Your Verification Code</h1><p>$code</p>";

            $mail->send();
            echo "<div class='flash-message success'>Verification code sent successfully!</div>";
            header("location:update_password_admin.php");
          
        } catch (Exception $e) {
            echo "<div class='flash-message error'>Email sending failed: {$mail->ErrorInfo}</div>";
        }
    } else {
        echo "<div class='flash-message error'>Email not found!</div>";
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
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
        }
        
        .input-field {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(99, 102, 241, 0.6);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .flash-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            animation: slideInRight 0.5s, fadeOut 0.5s 2.5s forwards;
        }
        
        .success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        
        .logo {
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
        }
        
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            pointer-events: none;
        }
    </style>
</head>
<body class="flex items-center justify-center p-6 relative overflow-hidden">
    <div id="particles-js"></div>
    
    <button onclick="window.history.back()" 
            class="fixed top-6 left-6 px-5 py-3 bg-white/10 hover:bg-white/20 text-white rounded-full font-semibold shadow-lg backdrop-blur-md transition-all duration-300 flex items-center gap-2 z-10 border border-white/20">
            <i class="fas fa-arrow-left"></i>
            <span>Return</span>
    </button>
    
    <div class="glass-card w-full max-w-md p-8 animate__animated animate__fadeInUp">
        <div class="flex justify-center mb-8">
            <div class="logo bg-white p-4 rounded-2xl shadow-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
        </div>
        
        <h1 class="text-3xl font-bold text-center text-white mb-2">Reset Password</h1>
        <p class="text-center text-white/80 mb-8">Enter your email to receive a verification code</p>
        
        <form action="" method="post" class="space-y-6">
            <div class="space-y-2">
                <label for="email" class="block text-white font-medium">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-white/60"></i>
                    </div>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        required 
                        placeholder="example@gmail.com"
                        class="w-full pl-10 pr-4 py-3 input-field rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/30">
                </div>
            </div>
            
            <button 
                type="submit" 
                class="w-full btn-primary py-3 px-4 rounded-lg font-semibold text-white shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Send Verification Code
                <i class="fas fa-paper-plane ml-2"></i>
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <p class="text-white/70">Remember your password? 
                <a href="login.php" class="text-white font-semibold hover:underline">Sign in</a>
            </p>
        </div>
    </div>
    
    <div class="absolute bottom-4 left-0 w-full text-center text-white/50 text-sm">
        &copy; 2025  All rights reserved.
    </div>
    
    <script>
        function createParticles() {
            const container = document.body;
            const particleCount = 30;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                const size = Math.random() * 4 + 2;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                particle.style.left = `${Math.random() * 100}vw`;
                particle.style.top = `${Math.random() * 100}vh`;
                
                const duration = Math.random() * 20 + 10;
                particle.style.animation = `float ${duration}s linear infinite`;
                
                container.appendChild(particle);
                
                const keyframes = `
                    @keyframes float {
                        0% {
                            transform: translate(0, 0) rotate(0deg);
                            opacity: 1;
                        }
                        100% {
                            transform: translate(${Math.random() * 200 - 100}px, ${Math.random() * 200 - 100}px) rotate(360deg);
                            opacity: 0;
                        }
                    }
                `;
                
                const style = document.createElement('style');
                style.innerHTML = keyframes;
                document.head.appendChild(style);
            }
        }
        
        window.addEventListener('load', createParticles);
        
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(msg => {
                setTimeout(() => {
                    msg.remove();
                }, 3000);
            });
        });
    </script>
</body>
</html>