<?php

include "connection.php"; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = ($_POST['email']);
    

    
    $statement = $connection->prepare("SELECT * FROM clients WHERE email = ?");
    $statement->bind_param("s", $email);
    $statement->execute();
    $result = $statement->get_result();

    if (!$result) {
        die("Query failed: " . $connection->error);
    }

    if ($result->num_rows > 0) {
        
            $code = rand(1000, 9999);
            $reset_stmt=$connection->prepare("UPDATE clients SET code=? WHERE email=?");
            $reset_stmt->bind_param("ss",$code,$email);
            $reset_stmt->execute();
            $reset_stmt->close();
        
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'hunterxh972@gmail.com'; 
            $mail->Password = 'ikgx rmkd gpkj swkr'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('hunterxh972@gmail.com', 'افسح الطريق');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Verification Code';
            $mail->Body = "<h1>Your Verification Code</h1><p>$code</p>";

            $mail->send();
            echo "Verification code sent successfully!";
            header("location:update_password_client.php");
          
        } catch (Exception $e) {
            echo "Email sending failed: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email not found!";
    }

    $statement->close();
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center min-h-screen p-6">
    <form action="" method="post" class="w-full max-w-md bg-white/30 backdrop-blur-lg p-8 rounded-xl shadow-2xl border border-white/20">
        <h1 class="text-3xl font-extrabold text-center text-white mb-6">Reset Password</h1>
        <div class="space-y-4">
            <div>
                <label for="email" class="block text-white font-medium mb-2">Enter your email:</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    required 
                    placeholder="exemple@gmail.com"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>
        </div>
        <br>
        <button 
            type="submit" 
            class="w-full px-4 py-3 bg-indigo-600 text-white rounded-lg font-semibold shadow-lg hover:bg-indigo-700 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-300">
            Submit
        </button>
    </form>
</body>
</html>


