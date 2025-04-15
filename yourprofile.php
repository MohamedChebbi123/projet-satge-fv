<?php
include 'connection.php';
session_start();

if (!isset($_SESSION["client_id"])) {
    header("location:loginclient.php");
    exit;
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: form.php");
    exit();
}

$client_id = $_SESSION["client_id"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | HeberGest</title>
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
        
        .profile-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
        }
        
        .avatar {
            width: 120px;
            height: 120px;
            border: 4px solid white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .detail-card {
            border-left: 4px solid var(--primary);
            transition: all 0.3s ease;
        }
        
        .detail-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .btn-edit {
            border: 1px solid var(--primary);
            transition: all 0.3s ease;
        }
        
        .btn-edit:hover {
            background: var(--primary);
            color: white;
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
                <a href="deployyourwebsite.php" class="nav-link px-4 py-2 text-sm">
                    <i class="fas fa-cloud-upload-alt mr-2"></i>Deploy
                </a>
                <a href="yourwebsite.php" class="nav-link px-4 py-2 text-sm">
                    <i class="fas fa-globe mr-2"></i>Your Websites
                </a>
                <a href="yourprofile.php" class="nav-link active px-4 py-2 text-sm">
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
                <a href="deployyourwebsite.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    <i class="fas fa-cloud-upload-alt mr-2"></i>Deploy
                </a>
                <a href="yourwebsite.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    <i class="fas fa-globe mr-2"></i>Your Websites
                </a>
                <a href="yourprofile.php" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-700 bg-indigo-50">
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
        <div class="max-w-6xl mx-auto">
            <div class="profile-card overflow-hidden mb-8">
                <div class="profile-header px-8 py-12 text-center">
                    <div class="flex justify-center mb-6">
                        <div class="avatar rounded-full bg-white flex items-center justify-center">
                            <i class="fas fa-user text-4xl text-indigo-600"></i>
                        </div>
                    </div>
                    <?php
                    $statement = $connection->prepare("SELECT username, email, created_at FROM clients WHERE id = ?");
                    $statement->bind_param("i", $client_id);
                    $statement->execute();
                    $result = $statement->get_result();
                    
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo '<h1 class="text-3xl font-bold mb-2">' . htmlspecialchars($row['username']) . '</h1>';
                        echo '<p class="text-indigo-100">' . htmlspecialchars($row['email']) . '</p>';
                    }
                    ?>
                </div>
                
                <div class="p-6 md:p-8">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-800">Profile Information</h2>
                        
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                        <div class="stat-card p-6 rounded-xl shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-lg bg-indigo-50 text-indigo-600 mr-4">
                                    <i class="fas fa-globe text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Active Websites</p>
                                    <h3 class="text-2xl font-bold">3</h3>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-card p-6 rounded-xl shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-lg bg-green-50 text-green-600 mr-4">
                                    <i class="fas fa-calendar-check text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Member Since</p>
                                    <h3 class="text-2xl font-bold">
                                        <?php 
                                        if (isset($row['created_at'])) {
                                            echo date('M Y', strtotime($row['created_at'])); 
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-card p-6 rounded-xl shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-lg bg-purple-50 text-purple-600 mr-4">
                                    <i class="fas fa-shield-alt text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Account Status</p>
                                    <h3 class="text-2xl font-bold">Active</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="detail-card bg-white p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 flex items-center">
                                <i class="fas fa-user-circle text-indigo-600 mr-2"></i>
                                Personal Information
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500">Username</p>
                                    <p class="font-medium"><?php echo htmlspecialchars($row['username'] ?? 'N/A'); ?></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Email Address</p>
                                    <p class="font-medium"><?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Account Type</p>
                                    <p class="font-medium">Premium Client</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-card bg-white p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 flex items-center">
                                <i class="fas fa-lock text-indigo-600 mr-2"></i>
                                Security
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500">Last Login</p>
                                    <p class="font-medium">2 hours ago</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Password</p>
                                    <p class="font-medium">••••••••</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Two-Factor Auth</p>
                                    <p class="font-medium text-green-600">Enabled</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-card bg-white p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 flex items-center">
                                <i class="fas fa-credit-card text-indigo-600 mr-2"></i>
                                Billing Information
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500">Current Plan</p>
                                    <p class="font-medium">Professional Hosting</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Next Billing Date</p>
                                    <p class="font-medium">May 15, 2023</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Payment Method</p>
                                    <p class="font-medium">VISA •••• 4242</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-card bg-white p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 flex items-center">
                                <i class="fas fa-headset text-indigo-600 mr-2"></i>
                                Support
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500">Support Tier</p>
                                    <p class="font-medium">Priority Support</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Open Tickets</p>
                                    <p class="font-medium">1 Active</p>
                                </div>
                                <div>
                                    <button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                                        <i class="fas fa-plus-circle mr-1"></i> Create New Ticket
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    </script>
</body>
</html>
<?php
if (isset($statement)) {
    $statement->close();
}
$connection->close();
?>