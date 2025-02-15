<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin & Client Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-5xl w-full bg-white/30 backdrop-blur-lg p-8 rounded-xl shadow-2xl border border-white/20 grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Admin Registration Form -->
        <form action="" method="post" class="space-y-6">
            <h1 class="text-3xl font-extrabold text-center text-white mb-6">Admin Registration</h1>
            <input type="hidden" name="form_type" value="admin">
            <div class="space-y-4">
                <div>
                    <label for="admin_username" class="block text-white font-medium mb-2">Username:</label>
                    <input 
                        type="text" 
                        name="username" 
                        id="admin_username" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                </div>
                <div>
                    <label for="admin_password" class="block text-white font-medium mb-2">Password:</label>
                    <input 
                        type="password" 
                        pattern=".{7,}" 
                        name="password" 
                        id="admin_password" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                </div>
                <div>
                    <label for="admin_email" class="block text-white font-medium mb-2">Email:</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="admin_email" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                </div>
            </div>
            <div class="flex justify-between">
                <button type="submit" class="px-4 py-3 bg-indigo-600 text-white rounded-lg font-semibold shadow-lg hover:bg-indigo-700 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-300">Create Account</button>
                <button type="button" onclick="window.location.href='loginadmin.php'" class="px-4 py-3 bg-gray-500 text-white rounded-lg font-semibold shadow-lg hover:bg-gray-600">Log In</button>
            </div>
        </form>

        <!-- Client Registration Form -->
        <form action="" method="post" class="space-y-6">
            <h1 class="text-3xl font-extrabold text-center text-white mb-6">Client Registration</h1>
            <input type="hidden" name="form_type" value="client">
            <div class="space-y-4">
                <div>
                    <label for="client_username" class="block text-white font-medium mb-2">Username:</label>
                    <input 
                        type="text" 
                        name="username" 
                        id="client_username" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                </div>
                <div>
                    <label for="client_password" class="block text-white font-medium mb-2">Password:</label>
                    <input 
                        type="password" 
                        pattern=".{7,}" 
                        name="password" 
                        id="client_password" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                </div>
                <div>
                    <label for="client_email" class="block text-white font-medium mb-2">Email:</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="client_email" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                </div>
            </div>
            <div class="flex justify-between">
                <button type="submit" class="px-4 py-3 bg-indigo-600 text-white rounded-lg font-semibold shadow-lg hover:bg-indigo-700 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-300">Register</button>
                <button type="button" onclick="window.location.href='loginclient.php'" class="px-4 py-3 bg-gray-500 text-white rounded-lg font-semibold shadow-lg hover:bg-gray-600">Log In</button>
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
