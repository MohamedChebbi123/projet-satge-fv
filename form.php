<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin & Client Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center min-h-screen p-6">
    <button onclick="window.history.back()" 
        class="fixed top-4 left-4 px-4 py-3 bg-gray-500 text-white rounded-lg font-semibold shadow-lg hover:bg-gray-600 transition-all duration-300 flex items-center gap-2">
        <i class="fas fa-undo-alt"></i>
        Return
    </button>

    <div class="max-w-5xl w-full bg-white/30 backdrop-blur-lg p-8 rounded-xl shadow-2xl border border-white/20 grid grid-cols-1 md:grid-cols-2 gap-8">
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
                        pattern=".{4,}" 
                        required 
                        placeholder="enter your username (at least  4 characters)"
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
                        placeholder="enter your password (at least 7 characters)"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                </div>
                <div>
                    <label for="admin_email" class="block text-white font-medium mb-2">Email:</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="admin_email" 
                        required 
                        placeholder="enter your email (example@gmail.com)"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                </div>
            </div>
            <div class="flex justify-between">
                <button type="submit" class="px-4 py-3 bg-indigo-600 text-white rounded-lg font-semibold shadow-lg hover:bg-indigo-700 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-300">Create Account</button>
                <button type="button" onclick="window.location.href='loginadmin.php'" class="px-4 py-3 bg-gray-500 text-white rounded-lg font-semibold shadow-lg hover:bg-gray-600">Log In</button>
            </div>
        </form>

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
                        pattern=".{4,}"
                        placeholder="enter your username (at least  4 characters)"
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
                        placeholder="enter your password (at least 7 characters)"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                </div>
                <div>
                    <label for="client_email" class="block text-white font-medium mb-2">Email:</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="client_email" 
                        required 
                        placeholder="enter your email (example@gmail.com)"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                </div>
                <div>
                    <label for="client_profile" class="block text-white font-medium mb-2">Profile:</label>
                    <input 
                        type="text" 
                        name="profile" 
                        id="client_profile" 
                        required 
                        placeholder="enter your GitHub profile"
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
            if ($formType === 'admin') {
                
                $sql = 'INSERT INTO admins (username, password_hash, email) VALUES (?, ?, ?)';
                $statement = $connection->prepare($sql);
                $statement->bind_param("sss", $username, $password, $email);
            } elseif ($formType === 'client') {
                
                $profile = $_POST['profile']; 
                $sql = 'INSERT INTO clients (username, password_hash, email, profile) VALUES (?, ?, ?, ?)';
                $statement = $connection->prepare($sql);
                $statement->bind_param("ssss", $username, $password, $email, $profile);
            } else {
                die("Invalid form type");
            }

            if ($statement->execute()) {
                echo ucfirst($formType) . " data saved successfully!";
            } else {
                echo "Error executing statement for $formType: " . $statement->error;
            }
    }
    ?>
</body>
</html>
