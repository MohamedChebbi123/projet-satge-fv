<?php
include "connection.php";
session_start();

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: form.php");
    exit;
}

if (!isset($_SESSION["admin_id"])) {
    header("location:loginadmin.php");
    exit;
}

if (isset($_GET['search'])) {
    $search = $_GET['search'];
} else {
    $search = '';
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM websites WHERE id = ?";
    $delete_stmt = $connection->prepare($delete_sql);
    $delete_stmt->bind_param("i", $delete_id);
    $delete_stmt->execute();
    $delete_stmt->close();

    header("Location: ".$_SERVER['PHP_SELF']);
    exit; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Websites Management | HeberGest Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --danger: #ef4444;
            --success: #10b981;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            min-height: 100vh;
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
        
        .website-table {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
        
        .website-table th {
            background: var(--primary);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.75rem;
        }
        
        .website-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .website-table tr:hover {
            background-color: #f3f4f6;
        }
        
        .btn-danger {
            transition: all 0.2s ease;
        }
        
        .btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }
        
        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }
        
        .status-active {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-pending {
            background-color: #fef9c3;
            color: #854d0e;
        }
        
        .status-expired {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .search-box {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }
        
        .search-box:focus {
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
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
                <a href="listwebsite.php" class="nav-link active px-4 py-2 text-sm">
                    <i class="fas fa-globe mr-2"></i>Websites
                </a>
                <a href="clientsreviews.php" class="nav-link px-4 py-2 text-sm">
                    <i class="fas fa-comments mr-2"></i>Reviews
                </a>
                <a href="acceuiladmin.php" class="nav-link px-4 py-2 text-sm">
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
                <a href="listwebsite.php" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-700 bg-indigo-50">
                    <i class="fas fa-globe mr-2"></i>Websites
                </a>
                <a href="clientsreviews.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    <i class="fas fa-comments mr-2"></i>Reviews
                </a>
                <a href="acceuiladmin.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
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

    <main class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Websites Management</h1>
                <p class="text-gray-600">View and manage all hosted websites</p>
            </div>
            
            <form method="GET" class="mt-4 md:mt-0 flex">
                <div class="relative flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search websites..." 
                        class="search-box pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-indigo-200"
                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                    >
                </div>
                <button 
                    type="submit" 
                    class="ml-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                    Search
                </button>
            </form>
        </div>

        <div class="bg-white rounded-xl overflow-hidden website-table">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="py-3 px-6 text-left">Website</th>
                        <th class="py-3 px-6 text-left">Plan</th>
                        <th class="py-3 px-6 text-left">Status</th>
                        <th class="py-3 px-6 text-left">Deployed</th>
                        <th class="py-3 px-6 text-left">Renewal</th>
                        <th class="py-3 px-6 text-left">Repository</th>
                        <th class="py-3 px-6 text-left">Actions</th>
                    </tr>
                </thead>
                
                <tbody class="divide-y divide-gray-200">
                    <?php
                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    $search_query = "%$search%";
                    $sql = "SELECT id, name, deployment_date, plan_duration, price, repo FROM websites WHERE name LIKE ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param("s", $search_query);
                    $stmt->execute();
                    $result = $stmt->get_result(); 
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $deployment_date = new DateTime($row['deployment_date']);
                            $today = new DateTime();
                            
                            if ($row['plan_duration'] === "6 months") {
                                $deployment_date->modify('+6 months');
                            } elseif ($row['plan_duration'] === "1 year") {
                                $deployment_date->modify('+1 year');
                            }
                            $renewal_date = $deployment_date->format('Y-m-d');
                            
                            $status_class = "status-active";
                            $status_text = "Active";
                            if ($deployment_date < $today) {
                                $status_class = "status-expired";
                                $status_text = "Expired";
                            }
                    ?>
                            <tr>
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                            <i class="fas fa-globe"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900"><?php echo htmlspecialchars($row["name"]); ?></div>
                                            <div class="text-gray-500">ID: <?php echo $row["id"]; ?></div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="py-4 px-6">
                                    <div class="text-gray-900 font-medium"><?php echo htmlspecialchars($row["plan_duration"]); ?></div>
                                    <div class="text-gray-500">$<?php echo $row["price"]; ?></div>
                                </td>
                                
                                <td class="py-4 px-6">
                                    <span class="<?php echo $status_class; ?> status-badge">
                                        <?php echo $status_text; ?>
                                    </span>
                                </td>
                                
                                <td class="py-4 px-6">
                                    <div class="text-gray-500"><?php echo date('M j, Y', strtotime($row["deployment_date"])); ?></div>
                                </td>
                                
                                <td class="py-4 px-6">
                                    <div class="text-gray-900 font-medium"><?php echo date('M j, Y', strtotime($renewal_date)); ?></div>
                                    <div class="text-xs text-gray-400">
                                        <?php 
                                            $diff = $today->diff($deployment_date);
                                            echo $diff->format('%a days');
                                        ?>
                                    </div>
                                </td>
                                
                                <td class="py-4 px-6">
                                    <a href="<?php echo htmlspecialchars($row["repo"]); ?>" target="_blank" class="text-blue-500 hover:text-blue-700 hover:underline truncate block max-w-xs">
                                        <i class="fas fa-external-link-alt mr-1"></i>
                                        <?php echo parse_url($row["repo"], PHP_URL_HOST); ?>
                                    </a>
                                </td>
                                
                                <td class="py-4 px-6">
                                    <div class="flex space-x-2">
                                        <a href="?delete_id=<?php echo $row["id"]; ?>" 
                                           class="btn-danger bg-red-100 text-red-600 px-3 py-1 rounded-lg text-sm font-medium hover:bg-red-200"
                                           onclick="return confirm('Are you sure you want to delete this website?');">
                                            <i class="fas fa-trash-alt mr-1"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                    <?php
                            $update_sql = "UPDATE websites SET renewal_date = ? WHERE id = ?";
                            $update_stmt = $connection->prepare($update_sql);
                            $update_stmt->bind_param("si", $renewal_date, $row['id']);
                            $update_stmt->execute();
                            $update_stmt->close();
                        }
                    } else {
                        echo '<tr>
                                <td colspan="7" class="py-8 px-6 text-center text-gray-500">
                                    <i class="fas fa-exclamation-circle text-3xl mb-2 text-gray-300"></i>
                                    <div>No websites found</div>
                                </td>
                              </tr>';
                    }
                    $stmt->close(); 
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
            document.querySelector('.navbar-menu').classList.toggle('active');
        });
    </script>
</body>
</html>

