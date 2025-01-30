<?php
include 'connection.php';
session_start();

if (!isset($_SESSION["client_id"])) {
    echo "No client ID found in session.";
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
    <title>Client Profile</title>
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
<body class="bg-gray-50 text-gray-800">

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
                    <i class="fas fa-sync-alt"></i> profile
                </a>
                <a href="welcome.php" class="hover:text-gray-300 transition-all">
                    <i class="fas fa-sync-alt"></i> welcome
                </a>
                <form action="" method="POST" >
                    <button type="submit" name="logout" class="hover:text-gray-300 transition-all flex items-center">
                        <i class="fas fa-sign-out-alt"></i> Se déconnecter
                    </button>
                </form>
            </nav>
        </div>
    </header>

    
    <div class="container mx-auto p-8">

       
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Welcome, Client!</h1>
            <p class="text-xl text-gray-600">Here are your profile details:</p>
        </div>

        
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-lg space-y-4">
            <?php
            $statement = $connection->prepare("SELECT username, email, profile FROM clients WHERE id = ?");
            $statement->bind_param("i", $client_id);
            $statement->execute();
            $result = $statement->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div><strong class='text-lg font-semibold'>Username:</strong> <span class='text-gray-700'>" . htmlspecialchars($row['username']) . "</span></div>";
                    echo "<div><strong class='text-lg font-semibold'>Email:</strong> <span class='text-gray-700'>" . htmlspecialchars($row['email']) . "</span></div>";
                   
                }
            } else {
                echo "<div class='text-red-500'>No records found for this client ID.</div>";
            }

            $statement->close();
            $connection->close();
            ?>
        </div>

    </div>

</body>
</html>
