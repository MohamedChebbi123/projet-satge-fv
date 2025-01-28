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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Websites Information</title>
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

    <!-- Content -->
    <div class="content container mx-auto p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Websites Information</h1>

        <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
            <table class="table-auto w-full border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200">
                        <th class="px-4 py-2 text-left text-gray-600">ID</th>
                        <th class="px-4 py-2 text-left text-gray-600">Name</th>
                        <th class="px-4 py-2 text-left text-gray-600">Deployment Date</th>
                        <th class="px-4 py-2 text-left text-gray-600">Plan Duration</th>
                        <th class="px-4 py-2 text-left text-gray-600">Price</th>
                        <th class="px-4 py-2 text-left text-gray-600">Repository URL</th>
                        <th class="px-4 py-2 text-left text-gray-600">Renewal Date</th>
                        <th class="px-4 py-2 text-left text-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $statement = $connection->prepare("SELECT * FROM websites WHERE client_id=?");
                    if ($statement) {
                        $statement->bind_param("i", $client_id);
                        $statement->execute();
                        $result = $statement->get_result();
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='px-4 py-2'>" . $row['id'] . "</td>";
                                echo "<td class='px-4 py-2'>" . $row['name'] . "</td>";
                                echo "<td class='px-4 py-2'>" . $row['deployment_date'] . "</td>";
                                echo "<td class='px-4 py-2'>" . $row['plan_duration'] . "</td>";
                                echo "<td class='px-4 py-2'>" . $row['price'] . "</td>";
                                echo "<td class='px-4 py-2'><a href='" . $row['repo'] . "' target='_blank' class='text-blue-600 hover:underline'>" . $row['repo'] . "</a></td>";
                                echo "<td class='px-4 py-2'>" . $row['renewal_date'] . "</td>";
                                echo "<td class='px-4 py-2'><a href='editwebsite.php?id=" . $row['id'] . "' class='text-blue-600 hover:underline'>Edit</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='px-4 py-2 text-center'>No websites found for this client.</td></tr>";
                        }
                        $statement->close();
                    } else {
                        echo "<tr><td colspan='8' class='px-4 py-2 text-center'>Failed to prepare the SQL statement.</td></tr>";
                    }
                    $connection->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
