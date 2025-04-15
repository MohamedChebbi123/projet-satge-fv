<?php
include "connection.php";
session_start();
if (isset($_SESSION["client_id"])) {
    $client_id = $_SESSION["client_id"];
} else {
    header("location:loginclient.php");
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: form.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST["name"];
    $plan_duration = $_POST['plan_duration'];
    $repo = $_POST['repo'];
    if ($plan_duration === '6 months') {
        $price = 500;
    } else {
        $price = 1000;
    }

    $sql = "INSERT INTO websites (name, client_id, plan_duration,  price, repo) VALUES (?, ?, ?,  ?, ?)";
    if ($statement = $connection->prepare($sql)) {
        $statement->bind_param("sssss", $name, $client_id, $plan_duration,  $price, $repo);
        if ($statement->execute()) {
            $successMessage = "<div class='success-message'>Website deployed successfully!</div>";
        } else {
            $errorMessage = "<div class='error-message'>Error deploying website: " . $statement->error . "</div>";
        }
        $statement->close();
    } else {
        $errorMessage = "<div class='error-message'>Error preparing statement: " . $connection->error . "</div>";
    }
}
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deploy Website | HeberGest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-light: #818cf8;
            --dark: #1e293b;
            --light: #f8fafc;
            --success: #10b981;
            --error: #ef4444;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--primary);
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: all 0.3s ease;
        }
        
        .nav-link.active {
            color: var(--primary);
            font-weight: 600;
        }
        
        .nav-link.active::after {
            width: 100%;
        }
        
        .deploy-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        
        .deploy-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .input-field {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
        
        .btn-primary {
            background: var(--primary);
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .plan-card {
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .plan-card:hover {
            border-color: var(--primary-light);
            transform: translateY(-5px);
        }
        
        .plan-card.selected {
            border: 2px solid var(--primary);
            background: rgba(99, 102, 241, 0.05);
        }
        
        .success-message {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border-left: 4px solid var(--success);
        }
        
        .error-message {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error);
            border-left: 4px solid var(--error);
        }
        
        .tooltip {
            position: relative;
        }
        
        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
        
        .tooltip-text {
            visibility: hidden;
            width: 120px;
            background-color: var(--dark);
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .tooltip-text::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: var(--dark) transparent transparent transparent;
        }
        
        @media (max-width: 768px) {
            .navbar-menu {
                position: fixed;
                top: 80px;
                left: -100%;
                width: 80%;
                height: calc(100vh - 80px);
                background: white;
                transition: all 0.3s ease;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            }
            
            .navbar-menu.active {
                left: 0;
            }
            
            .mobile-menu-btn {
                display: block;
            }
        }
    </style>
</head>
<body class="bg-gray-50">

    <header class="navbar py-4 px-6 lg:px-12">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-rocket text-white text-sm"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-800">HeberGest</h1>
            </div>
            
            <div class="hidden md:flex items-center space-x-1">
                <a href="deployyourwebsite.php" class="nav-link active px-4 py-2 text-sm">
                    <i class="fas fa-cloud-upload-alt mr-2"></i>Deploy
                </a>
                <a href="yourwebsite.php" class="nav-link px-4 py-2 text-sm">
                    <i class="fas fa-globe mr-2"></i>Your Websites
                </a>
                <a href="yourprofile.php" class="nav-link px-4 py-2 text-sm">
                    <i class="fas fa-user mr-2"></i>Profile
                </a>
                <a href="welcome.php" class="nav-link px-4 py-2 text-sm">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                <a href="reviewcl.php" class="nav-link px-4 py-2 text-sm">
                    <i class="fas fa-comment-alt mr-2"></i>Review
                </a>
                <div class="relative group">
                    <button class="nav-link px-4 py-2 text-sm flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>
                    <form method="POST" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden group-hover:block">
                        <button type="submit" name="logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">
                            Confirm Logout
                        </button>
                    </form>
                </div>
            </div>
            
            <button class="md:hidden text-gray-600 focus:outline-none mobile-menu-btn">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
        
        <div class="navbar-menu md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="deployyourwebsite.php" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-700 bg-indigo-50">
                    <i class="fas fa-cloud-upload-alt mr-2"></i>Deploy
                </a>
                <a href="yourwebsite.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    <i class="fas fa-globe mr-2"></i>Your Websites
                </a>
                <a href="yourprofile.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    <i class="fas fa-user mr-2"></i>Profile
                </a>
                <a href="welcome.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                <a href="reviewcl.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    <i class="fas fa-comment-alt mr-2"></i>Review
                </a>
                <form method="POST">
                    <button type="submit" name="logout" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="flex-grow container mx-auto px-4 py-8 md:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Deploy Your Website</h1>
                <p class="text-gray-600">Get your website online in minutes with our powerful hosting platform</p>
            </div>
            
            <div class="deploy-card p-6 md:p-8">
                <?php if (isset($successMessage)): ?>
                    <div class="success-message p-4 mb-6 rounded-md">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <?php echo $successMessage; ?>
                        </div>
                    </div>
                <?php elseif (isset($errorMessage)): ?>
                    <div class="error-message p-4 mb-6 rounded-md">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <?php echo $errorMessage; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <form action="" method="POST" class="space-y-6">
                    <div>
                        <label for="website_name" class="block text-sm font-medium text-gray-700 mb-1">Website Name</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="name" 
                                id="website_name" 
                                required 
                                placeholder="e.g. my-awesome-site" 
                                class="input-field w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-globe text-gray-400"></i>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">This will be your website's display name</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hosting Plan</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="plan-card p-4 rounded-lg" onclick="selectPlan(this, '6 months')">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="font-medium text-gray-800">Starter</h3>
                                    <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full">Popular</span>
                                </div>
                                <div class="flex items-end mb-2">
                                    <span class="text-2xl font-bold text-gray-800">$500</span>
                                    <span class="text-gray-500 ml-1">/6 months</span>
                                </div>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2 text-xs"></i>
                                        50GB SSD Storage
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2 text-xs"></i>
                                        Unlimited Bandwidth
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2 text-xs"></i>
                                        24/7 Support
                                    </li>
                                </ul>
                                <input type="radio" name="plan_duration" value="6 months" class="hidden" checked>
                            </div>
                            
                            <div class="plan-card p-4 rounded-lg" onclick="selectPlan(this, '1 year')">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="font-medium text-gray-800">Professional</h3>
                                    <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">Best Value</span>
                                </div>
                                <div class="flex items-end mb-2">
                                    <span class="text-2xl font-bold text-gray-800">$1000</span>
                                    <span class="text-gray-500 ml-1">/year</span>
                                </div>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2 text-xs"></i>
                                        100GB SSD Storage
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2 text-xs"></i>
                                        Unlimited Bandwidth
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2 text-xs"></i>
                                        Priority Support
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2 text-xs"></i>
                                        Free SSL Certificate
                                    </li>
                                </ul>
                                <input type="radio" name="plan_duration" value="1 year" class="hidden">
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="repo" class="block text-sm font-medium text-gray-700 mb-1">Repository URL</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="repo" 
                                id="repo" 
                                required
                                placeholder="https://github.com/yourusername/yourrepo" 
                                class="input-field w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fab fa-github text-gray-400"></i>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">We support GitHub, GitLab, and Bitbucket repositories</p>
                    </div>
                    
                    <div class="pt-2">
                        <button 
                            type="submit" 
                            class="btn-primary w-full py-3 px-6 rounded-lg text-white font-medium flex items-center justify-center"
                        >
                            <i class="fas fa-paper-plane mr-2"></i> Deploy Website Now
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="mt-8 text-center text-sm text-gray-500">
                <p>Need help with deployment? <a href="#" class="text-indigo-600 hover:text-indigo-800">Contact our support team</a></p>
            </div>
        </div>
    </main>

    <footer class="bg-white py-6 mt-12">
        <div class="container mx-auto px-4 md:px-6 text-center">
            <div class="flex justify-center space-x-6 mb-4">
                <a href="#" class="text-gray-400 hover:text-gray-500">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-gray-500">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-gray-500">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-gray-500">
                    <i class="fab fa-linkedin"></i>
                </a>
            </div>
            <p class="text-gray-500 text-sm">&copy; 2023 HeberGest. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
            document.querySelector('.navbar-menu').classList.toggle('active');
        });
        
        function selectPlan(element, value) {
            document.querySelectorAll('.plan-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            element.classList.add('selected');
            
            
            const radioInput = element.querySelector('input[type="radio"]');
            radioInput.checked = true;
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.plan-card').classList.add('selected');
        });
    </script>
</body>
</html>