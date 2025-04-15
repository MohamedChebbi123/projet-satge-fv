<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'connection.php';
session_start();

if (!isset($_SESSION["admin_id"])) {
    echo "<div class='fixed top-0 left-0 right-0 bg-red-500 text-white p-4 text-center font-semibold'>No admin ID found in session. Please login.</div>";
    exit;
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: form.php");
    exit;
}

$currentDate = date('Y-m-d'); 
$query = "SELECT name, renewal_date FROM websites WHERE renewal_date = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $currentDate);
$stmt->execute();
$result = $stmt->get_result();

$message = ""; 

if ($result->num_rows > 0) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hunterxh972@gmail.com';
        $mail->Password = 'ikgx rmkd gpkj swkr'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('hunterxh972@gmail.com', 'Website Renewal Notifier');
        $mail->addAddress('abdallahaltozeri@gmail.com', 'Admin');

        $mail->isHTML(true);
        $mail->Subject = 'URGENT: Website Renewal Due Today!';

        $body = '<h1 style="color: #4f46e5; font-size: 1.5rem; margin-bottom: 1rem;">Renewal Reminder</h1>';
        $body .= '<p style="margin-bottom: 1rem;">The following websites have a renewal due <b style="color: #ef4444;">TODAY</b>:</p>';
        $body .= '<ul style="list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem;">';

        while ($row = $result->fetch_assoc()) {
            $body .= "<li style='margin-bottom: 0.5rem;'><strong>{$row['name']}</strong> (Renewal Date: {$row['renewal_date']}) - <b style='color: #ef4444;'>DUE TODAY!</b></li>";
        }

        $body .= '</ul>';
        $mail->Body = $body;

        $mail->send();
        $message = '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md mb-6">
                      <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span>Renewal email sent successfully to abdallahaltozeri@gmail.com</span>
                      </div>
                    </div>';
    } catch (Exception $e) {
        $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md mb-6">
                      <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                        <span>Email sending failed. Error: ' . htmlspecialchars($mail->ErrorInfo) . '</span>
                      </div>
                    </div>';
    }
} else {
    $message = '<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg shadow-md mb-6">
                  <div class="flex items-center">
                    <i class="fas fa-info-circle text-yellow-500 mr-2"></i>
                    <span>No websites need renewal alerts today (' . htmlspecialchars($currentDate) . ')</span>
                  </div>
                </div>';
}

$stmt->close();
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renewal Notifications | HeberGest Admin</title>
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
        
        .notification-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .notification-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
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
                <a href="acceuiladmin.php" class="nav-link px-4 py-2 text-sm">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                <a href="mail.php" class="nav-link active px-4 py-2 text-sm">
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
                <a href="acceuiladmin.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                <a href="mail.php" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-700 bg-indigo-50">
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
                <h1 class="text-2xl font-bold text-gray-800">Renewal Notifications</h1>
                <p class="text-gray-600">Automatic email alerts for website renewals</p>
            </div>
            
            <div class="mt-4 md:mt-0">
                <span class="text-sm text-gray-500">Today's Date: <?php echo date('F j, Y'); ?></span>
            </div>
        </div>

        <div class="mb-8">
            <?php echo $message; ?>
        </div>

        <div class="notification-card p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Notification Settings</h2>
            
            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notification Email</label>
                    <input type="email" value="abdallahaltozeri@gmail.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200" disabled>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notification Schedule</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option>Daily at 9:00 AM</option>
                        <option>Only on renewal dates</option>
                        <option>7 days before renewal</option>
                    </select>
                </div>
                
               
                
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-save mr-2"></i> Save Settings
                </button>
            </form>
        </div>

        <div class="notification-card p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Notifications</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Websites</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo date('F j, Y'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo ($result->num_rows > 0) ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                    <?php echo ($result->num_rows > 0) ? 'Sent' : 'No alerts'; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">abdallahaltozeri@gmail.com</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo ($result->num_rows > 0) ? $result->num_rows . ' websites' : 'None'; ?></td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo date('F j, Y', strtotime('-1 day')); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    No alerts
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">abdallahaltozeri@gmail.com</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">None</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
       
        document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
            document.querySelector('.navbar-menu').classList.toggle('active');
        });
    </script>
</body>
</html>