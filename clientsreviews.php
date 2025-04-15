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

$admin_id = $_SESSION["admin_id"];

$statement = $connection->prepare("SELECT reviews.review_id, reviews.review_date, reviews.review, reviews.client_id, clients.username, reviews.response FROM reviews JOIN clients ON reviews.client_id = clients.id");
$statement->execute();
$result = $statement->get_result();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST["reply"])) {
        $review_id = $_POST["review_id"];
        $reply = $_POST["reply"];
        
        $edit_stmt = $connection->prepare("UPDATE reviews SET response = ?, admin_id = ? WHERE review_id = ?");
        $edit_stmt->bind_param("sii", $reply, $admin_id, $review_id);
        $edit_stmt->execute();
        $edit_stmt->close();
    }
    
    if (isset($_POST["delete"])) {
        $review_id = $_POST["delete"];
        $delete_stmt = $connection->prepare("DELETE FROM reviews WHERE review_id = ?");
        $delete_stmt->bind_param("i", $review_id);
        $delete_stmt->execute();
        $delete_stmt->close();
    }
    
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Reviews | HeberGest Admin</title>
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
        
        .review-card {
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--primary);
        }
        
        .btn-action {
            transition: all 0.2s ease;
        }
        
        .btn-action:hover {
            transform: translateY(-1px);
        }
        
        .btn-reply:hover {
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
        }
        
        .btn-delete:hover {
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }
        
        .search-box {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }
        
        .search-box:focus {
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }
        
        .response-bubble {
            background-color: #f5f7ff;
            border-radius: 8px;
            border-top-left-radius: 0;
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
                <a href="clientsreviews.php" class="nav-link active px-4 py-2 text-sm">
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
                <a href="listwebsite.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    <i class="fas fa-globe mr-2"></i>Websites
                </a>
                <a href="clientsreviews.php" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-700 bg-indigo-50">
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
                <h1 class="text-2xl font-bold text-gray-800">Client Reviews</h1>
                <p class="text-gray-600">View and respond to client feedback</p>
            </div>
            
            <form method="GET" class="mt-4 md:mt-0 flex">
                <div class="relative flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search reviews..." 
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

        <div class="space-y-6">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="review-card rounded-lg p-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                <?php echo strtoupper(substr($row["username"], 0, 1)); ?>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold text-gray-900"><?php echo htmlspecialchars($row["username"]); ?></h3>
                                        <p class="text-sm text-gray-500"><?php echo htmlspecialchars($row["review_date"]); ?></p>
                                    </div>
                                    <form method="POST">
                                        <input type="hidden" name="delete" value="<?php echo $row["review_id"]; ?>">
                                        <button type="submit" class="btn-action btn-delete bg-red-100 text-red-600 px-3 py-1 rounded-lg text-sm font-medium hover:bg-red-200">
                                            <i class="fas fa-trash-alt mr-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                                
                                <p class="mt-3 text-gray-700"><?php echo htmlspecialchars($row["review"]); ?></p>
                                
                                <?php if (!empty($row['response'])): ?>
                                    <div class="mt-4 response-bubble p-4">
                                        <div class="flex items-center space-x-2 text-indigo-600 mb-2">
                                            <i class="fas fa-reply"></i>
                                            <span class="font-semibold">Admin Response</span>
                                        </div>
                                        <p><?php echo htmlspecialchars($row['response']); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="POST" class="mt-4">
                                    <input type="hidden" name="review_id" value="<?php echo $row["review_id"]; ?>">
                                    <div class="mb-2">
                                        <label for="reply-<?php echo $row["review_id"]; ?>" class="block text-sm font-medium text-gray-700">Your Response</label>
                                        <textarea 
                                            id="reply-<?php echo $row["review_id"]; ?>" 
                                            name="reply" 
                                            rows="3" 
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-3"
                                            placeholder="Type your response here..."
                                        ><?php echo !empty($row['response']) ? htmlspecialchars($row['response']) : ''; ?></textarea>
                                    </div>
                                    <button 
                                        type="submit" 
                                        class="btn-action btn-reply bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                                        <i class="fas fa-paper-plane mr-2"></i> <?php echo !empty($row['response']) ? 'Update Response' : 'Send Response'; ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="bg-white rounded-lg p-8 text-center">
                    <i class="fas fa-comment-slash text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900">No reviews yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Client reviews will appear here when submitted.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
            document.querySelector('.navbar-menu').classList.toggle('active');
        });
    </script>
</body>
</html>