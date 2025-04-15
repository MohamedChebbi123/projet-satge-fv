<?php
include "connection.php";
session_start();

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: form.php");
    exit;
}

if (!isset($_SESSION["admin_id"])) {
    echo "<div class='text-red-500 font-semibold text-center'>No admin ID found in session.</div>";
    exit;
}
$admin_id = $_SESSION["admin_id"];
$statement = $connection->prepare("SELECT * FROM admins WHERE id=?");
if ($statement) {
    $statement->bind_param("i", $admin_id);
    $statement->execute();
    $result = $statement->get_result();
    $admin_data = $result->fetch_assoc(); 
    $statement->close();
} else {
    echo "<div class='text-red-500 font-semibold text-center'>Failed to prepare the SQL statement.</div>";
}

$query_users = "SELECT COUNT(*) AS total_users FROM clients";
$result_users = $connection->query($query_users);
$total_users = $result_users->fetch_assoc()['total_users'];

$query_websites = "SELECT COUNT(*) AS total_websites FROM websites";
$result_websites = $connection->query($query_websites);
$total_websites = $result_websites->fetch_assoc()['total_websites'];

$connection->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --dark: #1e293b;
            --light: #f8fafc;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
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
        
        .stat-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .admin-profile {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
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
                    <i class="fas fa-shield-alt text-white text-sm"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-800">HeberGest Admin</h1>
            </div>
            
            <div class="hidden md:flex items-center space-x-1">
                <a href="clientlist.php" class="nav-link px-4 py-2 text-sm">
                    <i class="fas fa-users mr-2"></i>Clients
                </a>
                <a href="listwebsite.php" class="nav-link px-4 py-2 text-sm">
                    <i class="fas fa-globe mr-2"></i>Websites
                </a>
                <a href="clientsreviews.php" class="nav-link px-4 py-2 text-sm">
                    <i class="fas fa-comments mr-2"></i>Reviews
                </a>
                <a href="acceuiladmin.php" class="nav-link active px-4 py-2 text-sm">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                <a href="mail.php" class="nav-link px-4 py-2 text-sm">
                    <i class="fas fa-bell mr-2"></i>Notifications
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
                <a href="clientlist.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    <i class="fas fa-users mr-2"></i>Clients
                </a>
                <a href="listwebsite.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    <i class="fas fa-globe mr-2"></i>Websites
                </a>
                <a href="clientsreviews.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    <i class="fas fa-comments mr-2"></i>Reviews
                </a>
                <a href="acceuiladmin.php" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-700 bg-indigo-50">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                <a href="mail.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    <i class="fas fa-bell mr-2"></i>Notifications
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
        <div class="max-w-7xl mx-auto">
            <?php if (!empty($admin_data)): ?>
                <div class="admin-profile text-white rounded-xl p-6 mb-8 shadow-lg">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="mb-4 md:mb-0">
                            <h2 class="text-2xl font-bold">Welcome back, <?php echo $admin_data['username']; ?></h2>
                            <p class="text-indigo-100"><?php echo $admin_data['email']; ?></p>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-white/20 p-3 rounded-lg">
                                <i class="fas fa-user-shield text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                No admin data found for this ID.
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="stat-card p-6">
                    <div class="flex items-center">
                        <div class="stat-icon bg-blue-100 text-blue-600 mr-4">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Users</p>
                            <h3 class="text-2xl font-bold text-gray-800"><?php echo $total_users; ?></h3>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card p-6">
                    <div class="flex items-center">
                        <div class="stat-icon bg-green-100 text-green-600 mr-4">
                            <i class="fas fa-globe text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Websites</p>
                            <h3 class="text-2xl font-bold text-gray-800"><?php echo $total_websites; ?></h3>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card p-6">
                    <div class="flex items-center">
                        <div class="stat-icon bg-purple-100 text-purple-600 mr-4">
                            <i class="fas fa-comments text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Recent Activity</p>
                            <h3 class="text-2xl font-bold text-gray-800">View Logs</h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="clientlist.php" class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 hover:border-indigo-300 transition-all flex items-center">
                        <div class="bg-indigo-100 text-indigo-600 p-3 rounded-lg mr-4">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <span>view clients</span>
                    </a>
                    <a href="listwebsite.php" class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 hover:border-indigo-300 transition-all flex items-center">
                        <div class="bg-blue-100 text-blue-600 p-3 rounded-lg mr-4">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <span>view websites</span>
                    </a>
                    <a href="clientsreviews.php" class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 hover:border-indigo-300 transition-all flex items-center">
                        <div class="bg-green-100 text-green-600 p-3 rounded-lg mr-4">
                            <i class="fas fa-comment-medical"></i>
                        </div>
                        <span>Respond to Reviews</span>
                    </a>
                    
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

