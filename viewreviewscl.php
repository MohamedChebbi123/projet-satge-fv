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

if (($_SERVER['REQUEST_METHOD'] === "POST") && (isset($_POST["delete"]))) {
    $review_id = $_POST["delete"];
    $delete_stmt = $connection->prepare("DELETE FROM reviews WHERE review_id = ? AND client_id = ?");
    $delete_stmt->bind_param("ii", $review_id, $client_id);
    $delete_stmt->execute();
    $delete_stmt->close();
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] === "POST") && (isset($_POST["edit"]))) {
    $review_id = $_POST["edit"];
    $new_review = $_POST["new_review"];
    $edit_stmt = $connection->prepare("UPDATE reviews SET review = ? WHERE review_id = ? AND client_id = ?");
    $edit_stmt->bind_param("sii", $new_review, $review_id, $client_id);
    $edit_stmt->execute();
    $edit_stmt->close();
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}

$statement = $connection->prepare("SELECT reviews.review_id, reviews.review, reviews.review_date, reviews.response, clients.username 
                                FROM reviews 
                                LEFT JOIN clients ON reviews.client_id = clients.id 
                                WHERE reviews.client_id = ?");
$statement->bind_param("i", $client_id);
$statement->execute();
$result = $statement->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Reviews | HeberGest</title>
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
        
        .review-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .review-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .response-card {
            border-left: 4px solid var(--primary);
            background: rgba(99, 102, 241, 0.05);
        }
        
        .btn-edit {
            border: 1px solid var(--primary);
            transition: all 0.3s ease;
        }
        
        .btn-edit:hover {
            background: var(--primary);
            color: white;
        }
        
        .btn-delete {
            border: 1px solid var(--error);
            transition: all 0.3s ease;
        }
        
        .btn-delete:hover {
            background: var(--error);
            color: white;
        }
        
        .edit-form {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        
        .edit-form.active {
            max-height: 200px;
            margin-top: 1rem;
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
                <a href="yourprofile.php" class="nav-link px-4 py-2 text-sm">
                    <i class="fas fa-user mr-2"></i>Profile
                </a>
                <a href="reviewcl.php" class="nav-link px-4 py-2 text-sm">
                    <i class="fas fa-comment-alt mr-2"></i>Review Us
                </a>
                <a href="viewreviewscl.php" class="nav-link active px-4 py-2 text-sm">
                    <i class="fas fa-comments mr-2"></i>Your Reviews
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
                <a href="yourprofile.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    <i class="fas fa-user mr-2"></i>Profile
                </a>
                <a href="reviewcl.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    <i class="fas fa-comment-alt mr-2"></i>Review Us
                </a>
                <a href="viewreviewscl.php" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-700 bg-indigo-50">
                    <i class="fas fa-comments mr-2"></i>Your Reviews
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
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Your Reviews</h1>
                    <p class="text-gray-600">View and manage all your submitted reviews</p>
                </div>
                <a href="reviewcl.php" class="btn-edit px-4 py-2 rounded-lg text-indigo-600 font-medium flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i> Add New Review
                </a>
            </div>
            
            <?php if ($result->num_rows > 0): ?>
                <div class="space-y-6">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="review-card p-6">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($row["username"]); ?></h3>
                                    <p class="text-sm text-gray-500">
                                        <?php echo date('F j, Y \a\t g:i a', strtotime($row["review_date"])); ?>
                                    </p>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="toggleEditForm('edit-form-<?php echo $row['review_id']; ?>')" 
                                            class="btn-edit px-3 py-1 rounded text-sm">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </button>
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                        <input type="hidden" name="delete" value="<?php echo $row["review_id"]; ?>">
                                        <button type="submit" class="btn-delete px-3 py-1 rounded text-sm">
                                            <i class="fas fa-trash-alt mr-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="prose max-w-none mb-4">
                                <p><?php echo htmlspecialchars($row["review"]); ?></p>
                            </div>
                            
                            <div id="edit-form-<?php echo $row['review_id']; ?>" class="edit-form">
                                <form method="POST" class="bg-gray-50 p-4 rounded-lg">
                                    <input type="hidden" name="edit" value="<?php echo $row["review_id"]; ?>">
                                    <textarea name="new_review" class="w-full p-3 border rounded mb-2" 
                                              rows="3" required><?php echo htmlspecialchars($row["review"]); ?></textarea>
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" onclick="toggleEditForm('edit-form-<?php echo $row['review_id']; ?>')" 
                                                class="text-gray-600 hover:text-gray-800 px-3 py-1 rounded">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn-edit px-3 py-1 rounded">
                                            <i class="fas fa-save mr-1"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <?php if (!empty($row["response"])): ?>
                                <div class="response-card p-4 mt-4 rounded-lg">
                                    <div class="flex items-center mb-2">
                                        <div class="bg-indigo-100 text-indigo-800 p-2 rounded-full mr-3">
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                        <h4 class="font-semibold">Admin Response</h4>
                                    </div>
                                    <p class="text-gray-700 pl-14"><?php echo htmlspecialchars($row["response"]); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-comment-slash text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">No reviews yet</h3>
                    <p class="text-gray-500 mb-6">You haven't submitted any reviews yet.</p>
                    <a href="reviewcl.php" class="btn-edit px-6 py-2 rounded-lg text-indigo-600 font-medium inline-flex items-center">
                        <i class="fas fa-plus-circle mr-2"></i> Submit Your First Review
                    </a>
                </div>
            <?php endif; ?>
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
        
        function toggleEditForm(formId) {
            const form = document.getElementById(formId);
            form.classList.toggle('active');
        }
        
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to delete this review?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
<?php
$statement->close();
$connection->close();
?>