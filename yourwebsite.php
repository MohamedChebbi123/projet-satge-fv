<?php
include "connection.php";
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

if (isset($_POST['delete_website'])) {
    $delete_id = $_POST['delete_id'];
    $delete_stmt = $connection->prepare("DELETE FROM websites WHERE id = ? AND client_id = ?");
    if ($delete_stmt) {
        $delete_stmt->bind_param("ii", $delete_id, $client_id);
        if ($delete_stmt->execute()) {
            echo "<script>alert('Website deleted successfully!'); window.location.href = 'yourwebsite.php';</script>";
        } else {
            echo "<script>alert('Failed to delete website.');</script>";
        }
        $delete_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Websites | HeberGest</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
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
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        },
                        accent: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                    },
                }
            }
        }
    </script>
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.08);
            --glass-border: rgba(255, 255, 255, 0.2);
            --glass-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f4f8 0%, #dfe7ef 100%);
            min-height: 100vh;
            color: #0f172a;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }
        
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #0ea5e9;
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .nav-link.active {
            color: #0ea5e9;
        }
        
        .nav-link.active::after {
            width: 100%;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            transition: all 0.3s ease;
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
        
        .feature-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(2, 132, 199, 0.1) 100%);
            color: #0ea5e9;
        }
        
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            opacity: 0.2;
            z-index: -1;
        }
        
        .blob-1 {
            width: 300px;
            height: 300px;
            background: #0ea5e9;
            top: -100px;
            right: -100px;
        }
        
        .blob-2 {
            width: 400px;
            height: 400px;
            background: #22c55e;
            bottom: -150px;
            left: -150px;
        }
        
        .table-row-hover:hover {
            background-color: rgba(14, 165, 233, 0.05);
        }
        
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .status-active {
            background-color: rgba(34, 197, 94, 0.1);
            color: #16a34a;
        }
        
        .status-pending {
            background-color: rgba(234, 179, 8, 0.1);
            color: #ca8a04;
        }
        
        .status-expired {
            background-color: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }
    </style>
</head>
<body class="antialiased">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    
    <nav class="glass-card fixed w-full z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <svg class="h-8 w-8 text-primary-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 8V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8 12H16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="ml-2 text-xl font-bold text-primary-700 font-heading">HeberGest</span>
                    </div>
                </div>
                <div class="hidden md:ml-6 md:flex md:items-center md:space-x-8">
                    <a href="deployyourwebsite.php" class="nav-link text-secondary-700 hover:text-primary-600 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-rocket mr-2"></i>Deploy Website
                    </a>
                    <a href="yourwebsite.php" class="nav-link active text-primary-600 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-globe mr-2"></i>Your Websites
                    </a>
                    <a href="yourprofile.php" class="nav-link text-secondary-700 hover:text-primary-600 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-user-circle mr-2"></i>Profile
                    </a>
                    <a href="welcome.php" class="nav-link text-secondary-700 hover:text-primary-600 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-home mr-2"></i>Welcome
                    </a>
                    <a href="reviewcl.php" class="nav-link text-secondary-700 hover:text-primary-600 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-comment-dots mr-2"></i>Review Us
                    </a>
                    <a href="viewreviewscl.php" class="nav-link text-secondary-700 hover:text-primary-600 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-comments mr-2"></i>View Reviews
                    </a>
                    <form action="" method="POST">
                        <button type="submit" name="logout" class="flex items-center text-secondary-700 hover:text-red-500 px-3 py-2 text-sm font-medium transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
                <div class="-mr-2 flex items-center md:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-secondary-400 hover:text-secondary-500 hover:bg-secondary-100 focus:outline-none" aria-controls="mobile-menu" aria-expanded="false" id="mobile-menu-button">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="hidden md:hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1 px-4">
                <a href="deployyourwebsite.php" class="block px-3 py-2 rounded-md text-base font-medium text-secondary-700 hover:text-primary-600 hover:bg-secondary-50">
                    <i class="fas fa-rocket mr-2"></i>Deploy Website
                </a>
                <a href="yourwebsite.php" class="block px-3 py-2 rounded-md text-base font-medium text-primary-600 bg-primary-50">
                    <i class="fas fa-globe mr-2"></i>Your Websites
                </a>
                <a href="yourprofile.php" class="block px-3 py-2 rounded-md text-base font-medium text-secondary-700 hover:text-primary-600 hover:bg-secondary-50">
                    <i class="fas fa-user-circle mr-2"></i>Profile
                </a>
                <a href="welcome.php" class="block px-3 py-2 rounded-md text-base font-medium text-secondary-700 hover:text-primary-600 hover:bg-secondary-50">
                    <i class="fas fa-home mr-2"></i>Welcome
                </a>
                <a href="reviewcl.php" class="block px-3 py-2 rounded-md text-base font-medium text-secondary-700 hover:text-primary-600 hover:bg-secondary-50">
                    <i class="fas fa-comment-dots mr-2"></i>Review Us
                </a>
                <a href="viewreviewscl.php" class="block px-3 py-2 rounded-md text-base font-medium text-secondary-700 hover:text-primary-600 hover:bg-secondary-50">
                    <i class="fas fa-comments mr-2"></i>View Reviews
                </a>
                <form action="" method="POST" class="block">
                    <button type="submit" name="logout" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-secondary-700 hover:text-red-500 hover:bg-secondary-50">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>
    
    <main class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="glass-card p-6 mb-8 rounded-xl overflow-hidden relative">
            <div class="absolute inset-0 bg-gradient-to-r from-primary-500/10 to-accent-500/10"></div>
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-secondary-900 font-heading">Your Websites</h1>
                        <p class="mt-2 text-secondary-600">Manage all your deployed websites in one place</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="deployyourwebsite.php" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                            <i class="fas fa-plus mr-2"></i> Deploy New Website
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="glass-card overflow-hidden rounded-xl shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Deployment Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Plan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Price</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Repository</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Renewal Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        $statement = $connection->prepare("SELECT * FROM websites WHERE client_id=?");
                        if ($statement) {
                            $statement->bind_param("i", $client_id);
                            $statement->execute();
                            $result = $statement->get_result();
                            
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
                                    $status_class = ($today < $deployment_date) ? 'status-active' : 'status-expired';
                                    $status_text = ($today < $deployment_date) ? 'Active' : 'Expired';
                                    
                                    $update_stmt = $connection->prepare("UPDATE websites SET renewal_date = ? WHERE id = ?");
                                    if ($update_stmt) {
                                        $update_stmt->bind_param("si", $renewal_date, $row['id']);
                                        $update_stmt->execute();
                                        $update_stmt->close();
                                    }
                                    
                                    echo "<tr class='table-row-hover'>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-secondary-900'>{$row['id']}</td>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm text-secondary-900 font-medium'>{$row['name']}</td>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm text-secondary-500'>{$row['deployment_date']}</td>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm text-secondary-500'>{$row['plan_duration']}</td>
                                        <td class='px-6 py-4 whitespace-nowrap'>
                                            <span class='status-badge {$status_class}'>{$status_text}</span>
                                        </td>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm text-secondary-500 font-medium'>{$row['price']}</td>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm text-secondary-500'>
                                            <a href='{$row['repo']}' target='_blank' class='text-primary-600 hover:text-primary-800 hover:underline'>
                                                <i class='fas fa-external-link-alt mr-1'></i> View Repo
                                            </a>
                                        </td>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm text-secondary-500'>{$renewal_date}</td>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm text-secondary-500'>
                                            <form action='' method='POST' onsubmit='return confirm(\"Are you sure you want to delete this website?\")'>
                                                <input type='hidden' name='delete_id' value='{$row['id']}'>
                                                <button type='submit' name='delete_website' class='text-red-600 hover:text-red-800 flex items-center'>
                                                    <i class='fas fa-trash-alt mr-1'></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr>
                                    <td colspan='9' class='px-6 py-4 whitespace-nowrap text-sm text-center text-secondary-500'>
                                        No websites found. <a href='deployyourwebsite.php' class='text-primary-600 hover:underline'>Deploy your first website</a>
                                    </td>
                                </tr>";
                            }
                            $statement->close();
                        } else {
                            echo "<tr>
                                <td colspan='9' class='px-6 py-4 whitespace-nowrap text-sm text-center text-secondary-500'>
                                    Failed to retrieve website data. Please try again later.
                                </td>
                            </tr>";
                        }
                        $connection->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    
    <footer class="glass-card mt-12">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex justify-center md:order-2 space-x-6">
                    <a href="#" class="text-secondary-400 hover:text-primary-500">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-secondary-400 hover:text-primary-500">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="text-secondary-400 hover:text-primary-500">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-secondary-400 hover:text-primary-500">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
                <div class="mt-8 md:mt-0 md:order-1">
                    <p class="text-center text-base text-secondary-500">
                        &copy; <?php echo date('Y'); ?> HeberGest. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            const isHidden = menu.classList.contains('hidden');
            
            if (isHidden) {
                menu.classList.remove('hidden');
                this.setAttribute('aria-expanded', 'true');
                this.querySelector('svg:first-child').classList.add('hidden');
                this.querySelector('svg:last-child').classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
                this.setAttribute('aria-expanded', 'false');
                this.querySelector('svg:first-child').classList.remove('hidden');
                this.querySelector('svg:last-child').classList.add('hidden');
            }
        });
    </script>
</body>
</html>