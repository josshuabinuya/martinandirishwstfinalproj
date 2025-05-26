<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Both fields are required!";
    } else {
        $file = 'users.xml';

        if (!file_exists($file)) {
            $error = "No users registered yet!";
        } else {
            $xml = simplexml_load_file($file);
            $found = false;

            foreach ($xml->user as $user) {
                if ((string)$user->username === $username && (string)$user->password === $password) {
                    $found = true;
                    $_SESSION['fullname'] = (string)$user->fullname;
                    header("Location: dashboard.php");
                    exit;
                }
            }

            if (!$found) {
                $error = "Invalid credentials!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign in / MIRA Account Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-container {
            width: 100%;
            max-width: 400px;
            background: #ffffff;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 400px;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #373643;
            text-align: center;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
            color: #373643;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .input-group input:focus {
            border-color: #18cb96;
            outline: none;
        }

        .signin-btn {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background: #18cb96;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .signin-btn:hover {
            background: #15b789;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #373643;
        }

        .footer a {
            color: #18cb96;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .forgot-password {
            text-align: center;
            margin-top: 10px;
        }

        .forgot-password a {
            color: #18cb96;
            text-decoration: none;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <div class="logo">
        <img src="mira.png" alt="Logo">
    </div>

    <h2>Sign in</h2>

    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="post">
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username">
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password">
        </div>
        <button type="submit" class="signin-btn">Login</button>
    </form>

    <div class="forgot-password">
        <a href="forgot_password.php">Forgot password?</a>
    </div>

    <div class="footer">
        Don't have an account? <a href="signup.php">Create one</a>
    </div>
</div>

</body>
</html>
