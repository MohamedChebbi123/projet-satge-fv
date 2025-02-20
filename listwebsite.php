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

if (isset($_GET['search'])) {
    $search = $_GET['search'];
} else {
    $search = '';
}

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
<body class="bg-gray-100">
    <header class="navbar p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">HeberGest</h1>
            
            <nav class="navbar-nav space-x-6 hidden md:flex">
                <a href="clientlist.php" class="hover:text-gray-300 transition-all">
                    <i class="fas fa-users"></i> Liste des clients 
                </a>
                <a href="listwebsite.php" class="hover:text-gray-300 transition-all">
                    <i class="fas fa-sync-alt"></i> Listes des websites
                </a>
                
                <a href="clientsreviews.php" class="hover:text-gray-300 transition-all">
                    <i class="fas fa-sync-alt"></i> reviews
                </a>
                <a href="acceuiladmin.php" class="hover:text-gray-300 transition-all">
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

    <div class="content container mx-auto p-8">
        <h1 class="text-3xl font-bold text-white mb-6 text-center bg-gray-800 bg-opacity-80 rounded-lg py-2">
            Websites Information
        </h1>

        <form method="GET" class="mb-4">
            <input 
                type="text" name="search" placeholder="Search for a website..." class="border border-gray-300 rounded px-3 py-2 w-full md:w-1/2 lg:w-1/3"
            >
            <button 
                type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 ml-2">Search
            </button>
        </form>

        <div class="overflow-x-auto bg-white shadow-lg rounded-lg bg-opacity-90">
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
                    if (isset($_GET['delete_id'])) {
                        $delete_id = $_GET['delete_id'];
                        $delete_sql = "DELETE FROM websites WHERE id = ?";
                        $delete_stmt = $connection->prepare($delete_sql);
                        $delete_stmt->bind_param("i", $delete_id);
                        $delete_stmt->execute();
                        $delete_stmt->close();
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    }

                    $search_query = "%$search%";
                    $sql = "SELECT id, name, deployment_date, plan_duration, price, repo FROM websites WHERE name LIKE ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param("s", $search_query);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $deployment_date = new DateTime($row['deployment_date']);
                            if ($row['plan_duration'] === "6 months") {
                                $deployment_date->modify('+6 months');
                            } elseif ($row['plan_duration'] === "1 year") {
                                $deployment_date->modify('+1 year');
                            }
                            $renewal_date = $deployment_date->format('Y-m-d');
                    ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border"><?php echo $row["id"]; ?></td>
                                <td class="px-4 py-2 border"><?php echo $row["name"]; ?></td>
                                <td class="px-4 py-2 border"><?php echo $row["deployment_date"]; ?></td>
                                <td class="px-4 py-2 border"><?php echo $row["plan_duration"]; ?></td>
                                <td class="px-4 py-2 border"><?php echo $row["price"]; ?></td>
                                <td class="px-4 py-2 border">
                                    <a href="<?php echo $row["repo"]; ?>" target="_blank" class="text-blue-500 hover:underline">
                                        <?php echo $row["repo"]; ?>
                                    </a>
                                </td>
                                <td class="px-4 py-2 border"><?php echo $renewal_date; ?></td>
                                <td class="px-4 py-2 border">
                                    <a href="?delete_id=<?php echo $row["id"]; ?>" class="text-red-500 font-bold hover:underline">Delete</a>
                                </td>
                            </tr>
                    <?php
                            $update_sql = "UPDATE websites SET renewal_date = ? WHERE id = ?";
                            $update_stmt = $connection->prepare($update_sql);
                            $update_stmt->bind_param("si", $renewal_date, $row['id']);
                            $update_stmt->execute();
                            $update_stmt->close();
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="8" class="px-4 py-2 text-center text-gray-500">Data not found</td>
                        </tr>
                    <?php 
                    }
                    $connection->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
