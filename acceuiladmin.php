<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
                <a href="questions.php" class="hover:text-gray-300 transition-all">
                    <i class="fas fa-sync-alt"></i> qestions
                </a>
                <form action="" method="POST" >
                    <button type="submit" name="logout" class="hover:text-gray-300 transition-all flex items-center">
                        <i class="fas fa-sign-out-alt"></i> Se déconnecter
                    </button>
                </form>
            </nav>
        </div>
    </header>
    
    <div class="container mx-auto mt-8">
        <?php
            include "connection.php";
            
            session_start();
            
            if (isset($_POST['logout'])) {
                session_destroy();
                header("Location: form.php");
                exit;
            }

            if (!isset($_SESSION["admin_id"])) {
                echo "<div class='text-red-500 font-semibold text-center'>No admin ID found in session.</div>";
                exit;
            }
            $admin_id = $_SESSION["admin_id"];
            echo "<p class='text-lg font-semibold mb-4 text-center'>Admin ID: <span class='text-blue-500'>$admin_id</span></p>";
            
            $statement = $connection->prepare("SELECT * FROM admins WHERE id=?");
            if ($statement) {
                $statement->bind_param("i", $admin_id);
                $statement->execute();
                $result = $statement->get_result();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='bg-gray-50 rounded-lg shadow-sm p-6 mb-6'>";
                        echo "<p class='text-lg font-semibold'>Admin Name: <span class='text-blue-500'>" . htmlspecialchars($row['username']) . "</span></p>";
                        echo "<p class='text-lg font-semibold'>Email: <span class='text-blue-500'>" . htmlspecialchars($row['email']) . "</span></p>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='text-yellow-500 font-semibold text-center'>No admin data found for this ID.</div>";
                }
                $statement->close();
            } else {
                echo "<div class='text-red-500 font-semibold text-center'>Failed to prepare the SQL statement.</div>";
            }
            
            $query_users = "SELECT COUNT(*) AS total_users FROM clients";
            $result_users = $connection->query($query_users);
            $total_users = $result_users->fetch_assoc()['total_users'];
            
            
            $query_websites = "SELECT COUNT(*) AS total_websites FROM websites";
            $result_websites = $connection->query($query_websites);
            $total_websites = $result_websites->fetch_assoc()['total_websites'];

            $connection->close();
        ?>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-700">Total Users</h3>
                <p class="text-3xl font-bold text-blue-500"><?php echo $total_users; ?></p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-700">Total Websites</h3>
                <p class="text-3xl font-bold text-blue-500"><?php echo $total_websites; ?></p>
            </div>
        </div>
    </div>
</body>
</html>
