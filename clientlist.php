<?php
include "connection.php";
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("location:loginadmin.php");
    exit;
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: form.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HeberGest - Tableau de bord</title>
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
    
    <div id="navbar-container"></div>
    <main class="container mx-auto my-8 p-6 bg-transparent rounded-lg shadow-lg">
    <h1 class="text-3xl font-bold text-white mb-6 text-center bg-gray-800 bg-opacity-80 rounded-lg py-2">
            clients list
        </h1>
        
        <?php if (!empty($admin_data)): ?>
            <div class="bg-gray-50 rounded-lg shadow-sm p-6 mb-6">
                <p class="text-lg font-semibold">Admin Name: <span class="text-blue-500"><?php echo $admin_data['username']; ?></span></p>
                <p class="text-lg font-semibold">Email: <span class="text-blue-500"><?php echo $admin_data['email']; ?></span></p>
                <p class="text-lg font-semibold">Created At: <span class="text-blue-500"><?php echo $admin_data['created_at']; ?></span></p>
            </div>
        <?php endif; ?>
        <form method="GET" class="mb-4">
            <input 
                type="text" name="search" placeholder="Search for a website..." class="border border-gray-300 rounded px-3 py-2 w-full md:w-1/2 lg:w-1/3"
            >
            <button 
                type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 ml-2">Search
            </button>
        </form>
        <?php
            if (isset($_GET['search'])) {
                $search = $_GET['search'];
            } else {
                $search = '';
            }
        ?>

        <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="py-3 px-6 text-left">Username</th>
                        <th class="py-3 px-6 text-left">Email</th>
                        <th class="py-3 px-6 text-left">Profile</th>
                        <th class="py-3 px-6 text-left">Created At</th>
                        <th class="py-3 px-6 text-left">Action</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php
                    include "connection.php";
                    
                    if (isset($_GET['delete_id'])) {
                        $delete_id = $_GET['delete_id'];
                        $delete_sql = "DELETE FROM clients WHERE id = ?";
                        $delete_stmt = $connection->prepare($delete_sql);
                        $delete_stmt->bind_param("i", $delete_id);
                        $delete_stmt->execute();
                        $delete_stmt->close();
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit;
                    }

                    $search_query = "%$search%";
                    $sql = "SELECT id, username, email, profile, created_at FROM clients WHERE username LIKE ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param("s", $search_query);
                    $stmt->execute();
                    $result = $stmt->get_result(); // Corrected line
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='border-b hover:bg-gray-50'>";
                            echo "<td class='py-4 px-6'><p class='text-lg font-semibold'>" . $row["username"] . "</p></td>";
                            echo "<td class='py-4 px-6'><p class='text-lg font-semibold'>" . $row["email"] . "</p></td>";
                            echo "<td class='py-4 px-6'><p class='text-lg font-semibold'>" . $row["profile"] . "</p></td>";
                            echo "<td class='py-4 px-6'><p class='text-lg font-semibold'>" . $row["created_at"] . "</p></td>";
                            echo "<td class='py-4 px-6 text-red-500 hover:text-red-700'><a href='?delete_id=" . $row["id"] . "'>Delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='py-4 px-6 text-center text-gray-500'>No clients found</td></tr>";
                    }
                    $stmt->close(); // Ensure statement is closed
                    ?>
                </tbody>
            </table>
        </div>
    </main>

</body>

</html>
