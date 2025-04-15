<?php
include "connection.php"; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = ($_POST['email']);
    
    $statement = $connection->prepare("SELECT * FROM clients WHERE email = ?");
    $statement->bind_param("s", $email);
    $statement->execute();
    $result = $statement->get_result();

    if (!$result) {
        die("Query failed: " . $connection->error);
    }

    if ($result->num_rows > 0) {
        $code = rand(1000, 9999);
        $reset_stmt = $connection->prepare("UPDATE clients SET code=? WHERE email=?");
        $reset_stmt->bind_param("ss", $code, $email);
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

            $mail->setFrom('hunterxh972@gmail.com', 'Account Support');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Verification Code';
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;'>
                    <div style='background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); padding: 20px; text-align: center;'>
                        <h1 style='color: white; margin: 0;'>Password Reset Request</h1>
                    </div>
                    <div style='padding: 30px; background: white;'>
                        <p style='font-size: 16px; color: #4a5568;'>We received a request to reset your password. Here's your verification code:</p>
                        <div style='background: #f8fafc; border: 1px dashed #cbd5e0; padding: 15px; text-align: center; margin: 20px 0; border-radius: 6px;'>
                            <span style='font-size: 24px; font-weight: bold; letter-spacing: 2px; color: #1e293b;'>$code</span>
                        </div>
                        <p style='font-size: 14px; color: #64748b;'>This code will expire in 15 minutes. If you didn't request this, please ignore this email.</p>
                    </div>
                    <div style='background: #f1f5f9; padding: 15px; text-align: center; font-size: 12px; color: #64748b;'>
                        © ".date('Y')." Your App Name. All rights reserved.
                    </div>
                </div>
            ";

            $mail->send();
            echo "<div class='notification success'>Verification code sent successfully!</div>";
            header("Refresh: 2; url=update_password_client.php");
          
        } catch (Exception $e) {
            echo "<div class='notification error'>Email sending failed: {$mail->ErrorInfo}</div>";
        }
    } else {
        echo "<div class='notification error'>Email not found in our system!</div>";
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
    <title>Reset Password | Client Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --secondary: #f59e0b;
            --dark: #1e293b;
            --light: #f8fafc;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .auth-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.18);
            transition: all 0.3s ease;
        }
        
        .auth-card:hover {
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
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
        }
        
        .btn-primary:active {
            transform: translateY(0);
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
    
    <div class="auth-card w-full max-w-md mx-4 p-8 relative overflow-hidden">
        <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-indigo-400/10 blur-xl"></div>
        <div class="absolute -bottom-20 -left-20 w-40 h-40 rounded-full bg-purple-400/10 blur-xl"></div>
        
        <div class="flex justify-center mb-6">
            <div class="bg-white/20 p-4 rounded-2xl shadow-lg floating">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                        placeholder="your@email.com"
                        class="w-full pl-10 pr-4 py-3 input-field rounded-lg placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/30">
                </div>
            </div>
            
            <button 
                type="submit" 
                class="w-full btn-primary py-3 px-4 rounded-lg font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-paper-plane mr-2"></i> Send Verification Code
            </button>
        </form>
        
        
    </div>
    
    <div class="absolute bottom-4 left-0 w-full text-center text-white/50 text-sm">
        &copy; <?php echo date('Y'); ?> Client Portal. All rights reserved.
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('particles');
            const particleCount = 20;
            
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
            
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
                setTimeout(() => {
                    notification.remove();
                }, 3500);
            });
        });
    </script>
</body>
</html>