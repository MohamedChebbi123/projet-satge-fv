<?php
include "connection.php";
session_start();
if (isset($_SESSION["client_id"])) {
    $client_id = $_SESSION["client_id"];
} else {
    die("Client ID is not available in session.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST["name"];
    $plan_duration = $_POST['plan_duration'];
    $renewal_date = $_POST['renewal_date'];
    $repo = $_POST['repo'];
    if ($plan_duration === '6 months') {
        $price = 500;
    } else {
        $price = 1000;
    }

    $sql = "INSERT INTO websites (name, client_id, plan_duration, renewal_date, price, repo) VALUES (?, ?, ?, ?, ?, ?)";
    if ($statement = $connection->prepare($sql)) {
        $statement->bind_param("ssssds", $name, $client_id, $plan_duration, $renewal_date, $price, $repo);
        if ($statement->execute()) {
            $successMessage = "<div class='text-green-500 text-center mt-4'>Website deployed successfully!</div>";
        } else {
            $errorMessage = "<div class='text-red-500 text-center mt-4'>Error deploying website: " . $statement->error . "</div>";
        }
        $statement->close();
    } else {
        $errorMessage = "<div class='text-red-500 text-center mt-4'>Error preparing statement: " . $connection->error . "</div>";
    }
}
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deploy Website</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
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
<body class="bg-gray-100">

    
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
                <form action="" method="POST">
                    <button type="submit" name="logout" class="hover:text-gray-300 transition-all flex items-center">
                        <i class="fas fa-sign-out-alt"></i> Se déconnecter
                    </button>
                </form>
            </nav>
        </div>
    </header>

    
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Deploy Website</h1>
        <form action="" method="POST" class="space-y-4">
            <div>
                <label for="website_name" class="block text-sm font-medium text-gray-700">Website Name</label>
                <input 
                    type="text" 
                    name="name" 
                    id="website_name" 
                    required 
                    placeholder="Enter your website name" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                >
            </div>
            
            <div>
                <label for="plan_duration" class="block text-sm font-medium text-gray-700">Plan Duration</label>
                <select 
                    id="plan_duration" 
                    name="plan_duration" 
                    required 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="6 months" title="6 month subscription price = 500 dollars">6 months</option>
                    <option value="1 year" title="1 year subscription price = 1000 dollars">1 year</option>
                </select>
            </div>

            <div>
                <label for="renewal_date" class="block text-sm font-medium text-gray-700">Renewal Date</label>
                <input 
                    type="datetime-local" 
                    id="renewal_date" 
                    name="renewal_date" 
                    required 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                >
            </div>

            <div>
                <label for="repo" class="block text-sm font-medium text-gray-700">Repository URL</label>
                <input 
                    type="text" 
                    name="repo" 
                    id="repo" 
                    placeholder="Enter repository URL" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                >
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Deploy
            </button>
        </form>

        
        <?php
        if (isset($successMessage)) {
            echo $successMessage;
        } elseif (isset($errorMessage)) {
            echo $errorMessage;
        }
        ?>
    </div>
</body>
</html>
