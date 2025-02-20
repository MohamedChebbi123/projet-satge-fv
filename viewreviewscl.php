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
    <title>Client Messages</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('https://img.freepik.com/premium-photo/top-view-business-desk-with-laptop_73344-5359.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh; 
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1; 
        }
    
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f4f8; 
        }
        .navbar {
            background-color: rgba(255, 255, 255, 0.7); 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #4A5568;
        }
        .navbar-nav a {
            font-size: 1rem;
            color: #4A5568; 
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
            display: inline-flex;
            align-items: center;
        }
        .navbar-nav a:hover {
            background-color: #CBD5E0; 
            color: #2D3748; 
            transform: translateY(-2px); 
        }
        .navbar-nav a.active {
            background-color: #63B3ED; 
            color: white;
        }
        .navbar-nav a i {
            margin-right: 8px;
            transition: all 0.3s ease;
        }
        .navbar-nav a:hover i {
            transform: translateX(5px); 
        }
        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
            }

            .navbar-nav {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                width: 100%;
            }

            .navbar-nav a {
                padding: 1rem 2rem;
                width: 100%;
                text-align: left;
            }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    <header class="navbar p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">HeberGest</h1>
            <h3>client dashboard</h3>
            <nav class="navbar-nav space-x-6 hidden md:flex">
                <a href="deployyourwebsite.php" class="hover:text-gray-300 transition-all">
                    <i class="fas fa-users"></i> deploy your website 
                </a>
                <a href="yourwebsite.php" class="hover:text-gray-300 transition-all">
                    <i class="fas fa-sync-alt"></i> your website
                </a>
                <a href="yourprofile.php" class="hover:text-gray-300 transition-all">
                    <i class="fas fa-sync-alt"></i> your profile
                </a>
                
                <a href="reviewcl.php" class="hover:text-gray-300 transition-all">
                    <i class="fas fa-sync-alt"></i> review us
                </a>
                <a href="viewreviewscl.php" class="hover:text-gray-300 transition-all">
                    <i class="fas fa-sync-alt"></i> view reviews
                </a>
                <form action="" method="POST" >
                    <button type="submit" name="logout" class="hover:text-gray-300 transition-all flex items-center">
                        <i class="fas fa-sign-out-alt"></i> Se déconnecter
                    </button>
                </form>
            </nav>
        </div>
    </header>
    <h1 class="text-3xl font-bold mb-6">Your reviews</h1>
    <?php if ($result->num_rows > 0): ?>
        <div class="bg-white shadow-md rounded-lg p-4">
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="mb-4 border-b border-gray-300 pb-2">
                        <p class="text-lg font-semibold">Message: <?php echo htmlspecialchars($row["review"]); ?></p>
                        <p class="text-sm text-gray-600">Sent on: <?php echo htmlspecialchars($row["review_date"]); ?></p>
                        
                        <?php if (!empty($row["response"])): ?>
                            <p class="mt-2 text-blue-600">Admin Reply: <?php echo htmlspecialchars($row["response"]); ?></p>
                        <?php endif; ?>

                        <form method="POST" class="mt-2">
                            <input type="hidden" name="delete" value="<?php echo $row["review_id"]; ?>">
                            <button type="submit" class="text-red-500 hover:underline">Delete</button>
                        </form>

                        <form method="POST" class="mt-2">
                            <input type="hidden" name="edit" value="<?php echo $row["review_id"]; ?>">
                            <input type="text" name="new_review" class="border p-2 rounded" required placeholder="Enter new review">
                            <button type="submit" class="text-blue-500 hover:underline">Edit</button>
                        </form>

                        
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php else: ?>
        <p class="text-gray-600">No messages found.</p>
    <?php endif; ?>

    <?php
    $statement->close();
    $connection->close();
    ?>
</body>
</html>
