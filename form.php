<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow-lg grid grid-cols-1 md:grid-cols-2 gap-8">
        <form action="" method="post" class="space-y-6 border p-6 rounded-lg shadow-md">
            <h1 class="text-xl font-semibold text-center text-gray-700">Admin Registration</h1>
            <input type="hidden" name="form_type" value="admin">
            
            <div class="space-y-4">
                <div>
                    <label for="admin_username" class="block text-sm font-medium text-gray-700">Username:</label>
                    <input type="text" name="username" id="admin_username" required class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
    
                <div>
                    <label for="admin_password" class="block text-sm font-medium text-gray-700">Password:</label>
                    <input type="password" pattern=".{7,}" name="password" id="admin_password" required class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
    
                <div>
                    <label for="admin_email" class="block text-sm font-medium text-gray-700">Email:</label>
                    <input type="email" name="email" id="admin_email" required class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
    
            <div class="flex justify-between">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Account</button>
                <button type="button" onclick="window.location.href='loginadmin.php'" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Log In</button>
            </div>
        </form>
    
        
        <form action="" method="post" class="space-y-6 border p-6 rounded-lg shadow-md">
            <h1 class="text-xl font-semibold text-center text-gray-700">Client Registration</h1>
            <input type="hidden" name="form_type" value="client">
            
            <div class="space-y-4">
                <div>
                    <label for="client_username" class="block text-sm font-medium text-gray-700">Username:</label>
                    <input type="text" name="username" id="client_username" required class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
    
                <div>
                    <label for="client_password" class="block text-sm font-medium text-gray-700">Password:</label>
                    <input type="password"  pattern=".{7,}" name="password" id="client_password" required class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
    
                <div>
                    <label for="client_email" class="block text-sm font-medium text-gray-700">Email:</label>
                    <input type="email" name="email" id="client_email" required class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
            </div>
    
            <div class="flex justify-between">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Register</button>
                <button type="button" onclick="window.location.href='loginclient.php'" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Log In</button>
            </div>
        </form>
    </div>

    <?php
    include "connection.php";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formType = $_POST['form_type'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
       

        try {
            if ($formType === 'admin') {
                $sql = 'INSERT INTO admins (username, password_hash, email) VALUES (?, ?, ?)';
            } elseif ($formType === 'client') {
                $sql = 'INSERT INTO clients (username, password_hash, email) VALUES (?, ?, ?)';
            } else {
                die("error type");
            }

            $statement = $connection->prepare($sql);
            if ($statement === false) {
                die("error stat " . $connection->error);
            }
            $statement->bind_param("sss", $username, $password, $email);

            if ($statement->execute()) {
                echo ucfirst($formType) . " data saved successfully!";
            } else {
                echo "error exec stat $formType: " . $statement->error;
            }
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
        }
    }
    ?>
</body>
</html>
