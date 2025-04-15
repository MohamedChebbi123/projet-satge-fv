<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Registration | CodeConnect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        gradient: {
                            start: '#6366f1',
                            end: '#8b5cf6',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .form-container {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.125);
        }
        .input-field {
            transition: all 0.3s ease;
        }
        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
        }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.6);
        }
        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
        }
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        .floating {
            animation: floating 6s ease-in-out infinite;
        }
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        .notification {
            animation: slideIn 0.5s forwards, fadeOut 0.5s forwards 3s;
        }
        @keyframes slideIn {
            from { transform: translateY(-100px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gradient-start to-gradient-end min-h-screen font-poppins flex items-center justify-center p-4">

    <div id="notification" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-xs"></div>

    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-20 left-10 w-16 h-16 rounded-full bg-purple-400 opacity-20 blur-xl"></div>
        <div class="absolute bottom-20 right-10 w-24 h-24 rounded-full bg-indigo-400 opacity-20 blur-xl"></div>
        <div class="absolute top-1/3 right-1/4 w-32 h-32 rounded-full bg-blue-400 opacity-15 blur-xl"></div>
        <div class="absolute bottom-1/4 left-1/3 w-40 h-40 rounded-full bg-indigo-500 opacity-10 blur-xl"></div>
    </div>

    <div class="relative z-10 w-full max-w-md">
        <button onclick="window.history.back()" 
            class="absolute -top-16 left-0 px-5 py-3 btn-secondary text-white rounded-full font-medium shadow-lg transition-all duration-300 flex items-center gap-2 hover:shadow-xl">
            <i class="fas fa-arrow-left"></i>
            Back
        </button>

        <div class="form-container p-8 space-y-6 relative overflow-hidden">
            <div class="absolute -top-1 -left-1 w-16 h-16 border-t-2 border-l-2 border-white opacity-20"></div>
            <div class="absolute -bottom-1 -right-1 w-16 h-16 border-b-2 border-r-2 border-white opacity-20"></div>
            
            <div class="text-center">
                <div class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4 border border-white/20 floating">
                    <i class="fas fa-user-plus text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Join CodeConnect</h1>
                <p class="text-white/80">Create your developer profile and connect with peers</p>
            </div>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="space-y-5">
                <input type="hidden" name="form_type" value="client">
                
                <div class="space-y-4">
                    <div class="relative">
                        <label for="client_username" class="block text-sm font-medium text-white/80 mb-1">Username</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="username" 
                                id="client_username" 
                                pattern=".{4,}" 
                                placeholder="coder123" 
                                required 
                                class="w-full px-4 py-3 pl-11 input-field border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 focus:outline-none focus:border-white/40">
                            <i class="fas fa-user absolute left-3 top-3.5 text-white/50"></i>
                        </div>
                        <p class="mt-1 text-xs text-white/50">Minimum 4 characters</p>
                    </div>
                    
                    <div class="relative">
                        <label for="client_password" class="block text-sm font-medium text-white/80 mb-1">Password</label>
                        <div class="relative">
                            <input 
                                type="password" 
                                pattern=".{7,}" 
                                name="password" 
                                id="client_password" 
                                required 
                                placeholder="••••••••" 
                                class="w-full px-4 py-3 pl-11 input-field border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 focus:outline-none focus:border-white/40">
                            <i class="fas fa-lock absolute left-3 top-3.5 text-white/50"></i>
                        </div>
                        <p class="mt-1 text-xs text-white/50">Minimum 7 characters</p>
                    </div>
                    
                    <div class="relative">
                        <label for="client_email" class="block text-sm font-medium text-white/80 mb-1">Email</label>
                        <div class="relative">
                            <input 
                                type="email" 
                                name="email" 
                                id="client_email" 
                                required 
                                placeholder="your@email.com" 
                                class="w-full px-4 py-3 pl-11 input-field border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 focus:outline-none focus:border-white/40">
                            <i class="fas fa-envelope absolute left-3 top-3.5 text-white/50"></i>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <label for="client_profile" class="block text-sm font-medium text-white/80 mb-1">GitHub Profile</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="profile" 
                                id="client_profile" 
                                required 
                                placeholder="github.com/username" 
                                class="w-full px-4 py-3 pl-11 input-field border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 focus:outline-none focus:border-white/40">
                            <i class="fab fa-github absolute left-3 top-3.5 text-white/50"></i>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="w-full btn-primary text-white py-3.5 rounded-xl font-semibold transition-all duration-300 focus:outline-none">
                    Create Account
                </button>
                
                <div class="text-center text-sm text-white/80">
                    <p>Already have an account? 
                        <a href="loginclient.php" class="text-white font-medium hover:underline">Sign in</a>
                    </p>
                    <p class="mt-1">Are you an admin? 
                        <a href="loginadmin.php" class="text-white font-medium hover:underline">Admin portal</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <?php
include "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); 
    $email = trim($_POST['email']);
    $profile = trim($_POST['profile']);

    if (!$connection) {
        echo "<script>showNotification('Database connection failed.', 'error');</script>";
    } else {
        $checkSql = "SELECT * FROM clients WHERE username = ? OR email = ?";
        $checkStmt = $connection->prepare($checkSql);
        $checkStmt->bind_param("ss", $username, $email);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "<script>showNotification('Username or email already exists.', 'error');</script>";
        } else {
            $sql = "INSERT INTO clients (username, password_hash, email, profile) VALUES (?, ?, ?, ?)";
            $statement = $connection->prepare($sql);

            if ($statement === false) {
                echo "<script>showNotification('SQL prepare failed.', 'error');</script>";
            } else {
                $statement->bind_param("ssss", $username, $password, $email, $profile);

                if ($statement->execute()) {
                    echo "<script>
                        showNotification('Registration successful! Welcome aboard!', 'success');
                        setTimeout(() => { window.location.href = 'loginclient.php'; }, 2000);
                    </script>";
                } else {
                    echo "<script>showNotification('Error: " . addslashes($statement->error) . "', 'error');</script>";
                }

                $statement->close();
            }
        }
        $checkStmt->close();
        $connection->close();
    }
}
?>

    <script>
        function showNotification(message, type) {
            const notification = document.getElementById('notification');
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };
            
            notification.innerHTML = `
                <div class="notification ${colors[type] || 'bg-gray-500'} text-white px-6 py-3 rounded-lg shadow-lg font-medium text-center">
                    ${message}
                </div>
            `;
            
            setTimeout(() => {
                notification.innerHTML = '';
            }, 3500);
        }

        document.querySelectorAll('.input-field').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('i').style.color = 'rgba(255, 255, 255, 0.8)';
                this.style.backgroundColor = 'rgba(255, 255, 255, 0.15)';
            });
            input.addEventListener('blur', function() {
                this.parentElement.querySelector('i').style.color = 'rgba(255, 255, 255, 0.5)';
                this.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
            });
        });
    </script>
</body>
</html>