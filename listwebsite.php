<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Websites Information</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('https://img.freepik.com/premium-photo/top-view-business-desk-with-laptop_73344-5359.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh; /* Ensure footer stays at the bottom */
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1; /* Ensure this takes up available space, pushing footer down */
        }
    </style>
</head>
<body>

    <div class="content container mx-auto p-8">
        <h1 class="text-3xl font-bold text-white mb-6 text-center bg-gray-800 bg-opacity-80 rounded-lg py-2">
            Websites Information
        </h1>

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
                    include "connection.php";
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
                    $sql = "SELECT id, name, deployment_date, plan_duration, price, repo FROM websites";
                    $result = $connection->prepare($sql);
                    $result->execute();
                    $result = $result->get_result();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $deployment_date = new DateTime($row['deployment_date']);
                            if ($row['plan_duration'] === "6 months") {
                                $deployment_date->modify('+6 months');
                            } elseif ($row['plan_duration'] === "1 year") {
                                $deployment_date->modify('+1 year');
                            }
                            $renewal_date = $deployment_date->format('Y-m-d');

                            echo "<tr class='hover:bg-gray-50'>";
                            echo "<td class='px-4 py-2 border'>" . $row["id"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $row["name"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $row["deployment_date"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $row["plan_duration"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $row["price"] . "</td>";
                            echo "<td class='px-4 py-2 border'><a href='" . $row["repo"] . "' target='_blank' class='text-blue-500 hover:underline'>" . $row["repo"] . "</a></td>";
                            echo "<td class='px-4 py-2 border'>" . $renewal_date . "</td>";
                            echo "<td class='px-4 py-2 border'><a href='?delete_id=" . $row["id"] . "' class='text-red-500 font-bold hover:underline'>Delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='px-4 py-2 text-center text-gray-500'>Data not found</td></tr>";
                    }
                    $update_sql = ("UPDATE websites SET renewal_date =? WHERE id=?");
                    $update_stmt = $connection->prepare($update_sql);
                    $update_stmt->bind_param("si", $renewal_date, $row['id']);
                    $update_stmt->execute();
                    $update_stmt->close();

                    $connection->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
