<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $newPassword = $_POST['new_password'];

    if (empty($fullname) || empty($username) || empty($newPassword)) {
        $error = "All fields are required!";
    } else {
        $file = 'users.xml';

        if (!file_exists($file)) {
            $error = "No user records found!";
        } else {
            $xml = simplexml_load_file($file);
            $found = false;

            foreach ($xml->user as $user) {
                if ((string)$user->fullname === $fullname && (string)$user->username === $username) {
                    $user->password = $newPassword;
                    $xml->asXML($file);
                    $success = "Password successfully updated! You can now log in.";
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $error = "No matching user found!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-container {
            max-width: 400px;
            width: 100%;
            background: #fff;
            padding: 25px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #373643;
        }

        .input-group {
            margin-bottom: 15px;
        }

        label {
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
            color: #373643;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background-color: #18cb96;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #15b789;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            color: red;
        }

        .success {
            color: green;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
        }

        .back-link a {
            text-decoration: none;
            color: #18cb96;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Reset Password</h2>

    <?php
    if (!empty($error)) echo "<div class='message'>$error</div>";
    if (!empty($success)) echo "<div class='message success'>$success</div>";
    ?>

    <form method="post">
        <div class="input-group">
            <label>Full Name</label>
            <input type="text" name="fullname" required>
        </div>
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>
        <div class="input-group">
            <label>New Password</label>
            <input type="password" name="new_password" required>
        </div>
        <button type="submit" class="btn">Reset Password</button>
    </form>

    <div class="back-link">
        <a href="index.php">Back to Login</a>
    </div>
</div>

</body>
</html>
