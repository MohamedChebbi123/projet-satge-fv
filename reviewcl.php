<?php
include "connection.php";

session_start();
if (!isset($_SESSION["client_id"])) {
    exit;
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: form.php");
    exit();
}

$client_id = $_SESSION["client_id"];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $review = $_POST["review"];
    $statement = $connection->prepare("INSERT INTO reviews (review, client_id ) VALUES (?, ?)");
    if ($statement) {
        $statement->bind_param("si", $review, $client_id);
        if($statement->execute()){
            $successMessage = "<div class='success-message'><i class='fas fa-check-circle mr-2'></i>Thank you for your review!</div>";
        }else{
            $errorMessage = "<div class='error-message'><i class='fas fa-exclamation-circle mr-2'></i>Error submitting review: " . $statement->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Review | HeberGest</title>
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
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        
        .review-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .review-textarea {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            min-height: 150px;
        }
        
        .review-textarea:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
        
        .btn-submit {
            background: var(--primary);
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            font-weight: 500;
        }
        
        .btn-submit:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }
        
        .btn-submit:active {
            transform: translateY(0);
        }
        
        .rating-stars {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        
        .rating-stars input {
            display: none;
        }
        
        .rating-stars label {
            font-size: 2rem;
            color: #cbd5e0;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .rating-stars input:checked ~ label,
        .rating-stars label:hover,
        .rating-stars label:hover ~ label {
            color: #f59e0b;
        }
        
        .rating-stars input:checked + label {
            color: #f59e0b;
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
                <a href="reviewcl.php" class="nav-link active px-4 py-2 text-sm">
                    <i class="fas fa-comment-alt mr-2"></i>Review Us
                </a>
                <a href="viewreviewscl.php" class="nav-link px-4 py-2 text-sm">
                    <i class="fas fa-comments mr-2"></i>View Reviews
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
                <a href="reviewcl.php" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-700 bg-indigo-50">
                    <i class="fas fa-comment-alt mr-2"></i>Review Us
                </a>
                <a href="viewreviewscl.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    <i class="fas fa-comments mr-2"></i>View Reviews
                </a>
                <form method="POST">
                    <button type="submit" name="logout" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center p-4">
        <div class="review-card w-full max-w-2xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Share Your Experience</h1>
                <p class="text-gray-600">We value your feedback to help us improve our services</p>
            </div>
            
            <?php if (isset($successMessage)): ?>
                <div class="success-message p-4 mb-6 rounded-md flex items-center">
                    <?php echo $successMessage; ?>
                </div>
            <?php elseif (isset($errorMessage)): ?>
                <div class="error-message p-4 mb-6 rounded-md flex items-center">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>
            
            <form action="" method="POST" class="space-y-6">
                <div class="rating-stars">
                    <input type="radio" id="star5" name="rating" value="5">
                    <label for="star5" title="Excellent">★</label>
                    <input type="radio" id="star4" name="rating" value="4">
                    <label for="star4" title="Good">★</label>
                    <input type="radio" id="star3" name="rating" value="3">
                    <label for="star3" title="Average">★</label>
                    <input type="radio" id="star2" name="rating" value="2">
                    <label for="star2" title="Poor">★</label>
                    <input type="radio" id="star1" name="rating" value="1">
                    <label for="star1" title="Terrible">★</label>
                </div>
                
                <div>
                    <label for="review" class="block text-sm font-medium text-gray-700 mb-2">Your Review</label>
                    <textarea 
                        name="review" 
                        id="review" 
                        required
                        placeholder="Tell us about your experience with our service..."
                        class="review-textarea w-full px-4 py-3 rounded-lg focus:outline-none"
                    ></textarea>
                    <p class="mt-1 text-xs text-gray-500">Your feedback helps us improve our services</p>
                </div>
                
                <div>
                    <button 
                        type="submit" 
                        class="btn-submit w-full py-3 px-6 rounded-lg text-white font-medium flex items-center justify-center"
                    >
                        <i class="fas fa-paper-plane mr-2"></i> Submit Review
                    </button>
                </div>
            </form>
        </div>
    </main>

    <footer class="bg-white py-6">
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
        
        const stars = document.querySelectorAll('.rating-stars input');
        stars.forEach(star => {
            star.addEventListener('change', function() {
                const rating = this.value;
            });
        });
    </script>
</body>
</html>