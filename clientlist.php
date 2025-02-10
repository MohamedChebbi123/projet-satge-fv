<?php
session_start();
 if (!isset($_SESSION["admin_id"])) {
    echo "get out ";
    exit;
}?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HeberGest - Tableau de bord</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('https://img.freepik.com/premium-photo/top-view-business-desk-with-laptop_73344-5359.jpg');
            background-size: cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex-grow: 1;
        }
        
    </style>
</head>

<body class="bg-gray-100">
    
    <div id="navbar-container"></div>
    <main class="container mx-auto my-8 p-6 bg-transparent rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">Tableau de Bord</h2>
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
                    $sql = "SELECT id, username, email, profile, created_at FROM clients";
                    $result = $connection->prepare($sql);
                    $result->execute();
                    $result = $result->get_result();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='border-b hover:bg-gray-50'>";
                            echo "<td class='py-4 px-6'>" . $row["username"] . "</td>";
                            echo "<td class='py-4 px-6'>" . $row["email"] . "</td>";
                            echo "<td class='py-4 px-6'>" . $row["profile"] . "</td>";
                            echo "<td class='py-4 px-6'>" . $row["created_at"] . "</td>";
                            echo "<td class='py-4 px-6 text-red-500 hover:text-red-700'><a href='?delete_id=" . $row["id"] . "'>Delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='py-4 px-6 text-center text-gray-500'>No clients found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

</body>

</html>
