
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
        echo "<div class='fixed top-4 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg animate__animated animate__fadeInDown animate__faster'>Error: Invalid username or password.</div>";
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
    <title>Client Login | Secure Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
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
                            start: '#4f46e5',
                            end: '#7c3aed',
                        }
                    },
                    boxShadow: {
                        'glass': '0 4px 30px rgba(0, 0, 0, 0.1)',
                        'neumorphic': '8px 8px 16px #d1d9e6, -8px -8px 16px #ffffff',
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .input-field {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
        }
        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        }
        .btn-primary:active {
            transform: translateY(0);
        }
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute top-0 left-0 w-64 h-64 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
    </div>

    <button onclick="window.history.back()" 
        class="fixed top-6 left-6 px-5 py-2.5 bg-white/20 backdrop-blur-md text-white rounded-full font-medium shadow-lg hover:bg-white/30 transition-all duration-300 flex items-center gap-2 z-50 border border-white/10 hover:shadow-xl">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Back
    </button>

    <div class="glass-card w-full max-w-md p-8 animate__animated animate__fadeInUp relative overflow-hidden">
        <div class="absolute -top-20 -right-20 w-40 h-40 bg-purple-400/20 rounded-full"></div>
        <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-blue-400/20 rounded-full"></div>
        
        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
        </div>
        
        <h1 class="text-3xl font-bold text-center text-white mb-2">Welcome Back</h1>
        <p class="text-center text-white/80 mb-8">Sign in to access your account</p>
        
        <form action="" method="post" class="space-y-6 relative z-10">
            <div>
                <label for="client_username" class="block text-sm font-medium text-white/80 mb-2">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/70" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        name="username" 
                        id="client_username" 
                        class="input-field w-full pl-10 pr-4 py-3 rounded-xl text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/30" 
                        placeholder="john.doe" 
                        required>
                </div>
            </div>
            
            <div>
                <label for="client_password" class="block text-sm font-medium text-white/80 mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/70" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input 
                        type="password" 
                        name="password" 
                        id="client_password" 
                        class="input-field w-full pl-10 pr-4 py-3 rounded-xl text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/30" 
                        placeholder="••••••••" 
                        required>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                
                <div class="text-sm">
                    <a href="verifyclient.php" class="font-medium text-white hover:text-indigo-200 transition-colors">Forgot password?</a>
                </div>
            </div>
            
            <button 
                type="submit" 
                class="btn-primary w-full py-3 px-4 rounded-xl font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Sign In
            </button>
           
        </form>
        
        <div class="mt-6 relative z-10">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-white/20"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-transparent text-white/70">New to our platform?</span>
                </div>
            </div>
            
            <div class="mt-4 text-center">
                <a href="#" class="inline-flex items-center px-4 py-2 border border-white/20 rounded-full text-sm font-medium text-white bg-white/10 hover:bg-white/20 transition-colors">
                    Create account
                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="loginadmin.php" class="inline-flex items-center px-4 py-2 border border-white/20 rounded-full text-sm font-medium text-white bg-white/10 hover:bg-white/20 transition-colors">
                    log in as admin
                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <div class="fixed bottom-10 right-10 w-16 h-16 bg-purple-400/30 rounded-full filter blur-xl animate-pulse"></div>
    <div class="fixed top-1/4 left-10 w-8 h-8 bg-blue-400/30 rounded-full filter blur-lg floating"></div>
</body>
</html>

