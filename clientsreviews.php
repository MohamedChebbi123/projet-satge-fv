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

$statement = $connection->prepare("SELECT reviews.review_id, 
reviews.review_date, reviews.review, 
reviews.client_id, clients.username FROM reviews
JOIN clients ON reviews.client_id = clients.id");
$statement->execute();
$result = $statement->get_result();

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["reply"])) {
    $review_id = $_POST["review_id"];
    $reply = $_POST["reply"];

    $edit_stmt = $connection->prepare("UPDATE reviews SET response = ?, admin_id = ? WHERE review_id = ?");
    $edit_stmt->bind_param("sii", $reply, $admin_id, $review_id);
    $edit_stmt->execute();
    $edit_stmt->close();

    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Messages</title>
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
            
            <nav class="navbar-nav space-x-6 hidden md:flex">
                <a href="clientlist.php" class="hover:text-gray-300 transition-all">
                    <i class="fas fa-users"></i> clients list 
                </a>
                <a href="listwebsite.php" class="hover:text-gray-300 transition-all">
                    <i class="fas fa-sync-alt"></i> websites list
                </a>
                
                <a href="clientsreviews.php" class="hover:text-gray-300 transition-all">
                    <i class="fas fa-sync-alt"></i> reviews
                </a>
                <a href="acceuiladmin.php" class="hover:text-gray-300 transition-all">
                    <i class="fas fa-sync-alt"></i> welcome
                </a>
                <form action="" method="POST" >
                    <button type="submit" name="logout" class="hover:text-gray-300 transition-all flex items-center">
                        <i class="fas fa-sign-out-alt"></i> logout
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <?php if ($result->num_rows > 0): ?>
        <h1 class="text-3xl font-bold text-white mb-6 text-center bg-gray-800 bg-opacity-80 rounded-lg py-2">
            reviews
        </h1>
        <div class="bg-white shadow-md rounded-lg p-4">
                
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="mb-4 border-b border-gray-300 pb-2">
                        <p class="text-lg font-semibold">Sender: <?php echo htmlspecialchars($row["username"]); ?></p>
                        <p class="text-lg font-semibold">Message: <?php echo htmlspecialchars($row["review"]); ?></p>
                        <p class="text-sm text-gray-600">Sent on: <?php echo htmlspecialchars($row["review_date"]); ?></p>

                        <?php if (!empty($row['response'])): ?>
                            <p class="mt-2 text-gray-800 font-semibold">Admin Reply: <?php echo htmlspecialchars($row['response']); ?></p>
                        <?php endif; ?>

                        <form method="POST" class="mt-2">
                            <input type="hidden" name="review_id" value="<?php echo $row["review_id"]; ?>">
                            <input type="text" name="reply" class="border p-2 rounded" required placeholder="Reply to this review">
                            <button type="submit" class="text-blue-500 hover:underline">Reply</button>
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
