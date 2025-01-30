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

if (isset($_POST['delete_website'])) {
    $delete_id = $_POST['delete_id'];
    $delete_stmt = $connection->prepare("DELETE FROM websites WHERE id = ? AND client_id = ?");
    if ($delete_stmt) {
        $delete_stmt->bind_param("ii", $delete_id, $client_id);
        if ($delete_stmt->execute()) {
            echo "<script>alert('Website deleted successfully!'); window.location.href = 'yourwebsite.php';</script>";
        } else {
            echo "<script>alert('Failed to delete website.');</script>";
        }
        $delete_stmt->close();
    }
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
<body class="bg-gray-50 text-gray-800 font-sans">

<header class="navbar p-4">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">HeberGest</h1>
        <h3>Client Dashboard</h3>
        <nav class="navbar-nav space-x-6 hidden md:flex">
            <a href="deployyourwebsite.php"><i class="fas fa-users"></i> Deploy Your Website</a>
            <a href="yourwebsite.php"><i class="fas fa-sync-alt"></i> Your Website</a>
            <a href="yourprofile.php"><i class="fas fa-user"></i> Profile</a>
            <form action="" method="POST">
                <button type="submit" name="logout"><i class="fas fa-sign-out-alt"></i> Se déconnecter</button>
            </form>
        </nav>
    </div>
</header>

<div class="content container mx-auto p-8">
    <h1 class="text-3xl font-bold text-center">Websites Information</h1>
    <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="table-auto w-full border-collapse border-gray-200">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-200">
                    <th>ID</th><th>Name</th><th>Deployment Date</th><th>Plan Duration</th>
                    <th>Price</th><th>Repository URL</th><th>Renewal Date</th><th>Action</th>
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
                            $deployment_date = new DateTime($row['deployment_date']);
                            if ($row['plan_duration'] === "6 months") {
                                $deployment_date->modify('+6 months');
                            } elseif ($row['plan_duration'] === "1 year") {
                                $deployment_date->modify('+1 year');
                            }

                            // Calculate the renewal date
                            $renewal_date = $deployment_date->format('Y-m-d');

                            // Update renewal date in the database
                            $update_stmt = $connection->prepare("UPDATE websites SET renewal_date = ? WHERE id = ?");
                            if ($update_stmt) {
                                $update_stmt->bind_param("si", $renewal_date, $row['id']);
                                $update_stmt->execute();
                                $update_stmt->close();
                            }
                            
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['deployment_date']}</td>
                                <td>{$row['plan_duration']}</td>
                                <td>{$row['price']}</td>
                                <td><a href='{$row['repo']}' target='_blank'>{$row['repo']}</a></td>
                                <td>{$renewal_date}</td>
                                <td>
                                    <form action='' method='POST' onsubmit='return confirm(\"Are you sure you want to delete this website?\")'>
                                        <input type='hidden' name='delete_id' value='{$row['id']}'>
                                        <button type='submit' name='delete_website' class='text-red-600 hover:text-red-800'>
                                            <i class='fas fa-trash-alt'></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>No websites found.</td></tr>";
                    }
                    $statement->close();
                } else {
                    echo "<tr><td colspan='8' class='text-center'>Failed to prepare the SQL statement.</td></tr>";
                }
                $connection->close();
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
