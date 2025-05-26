<?php
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($fullname) || empty($username) || empty($password)) {
        $error = "All fields are required!";
    } else {
        $file = 'users.xml';

        if (!file_exists($file)) {
            $xml = new SimpleXMLElement('<users></users>');
        } else {
            $xml = simplexml_load_file($file);
        }

        // Check if username exists
        $taken = false;
        foreach ($xml->user as $user) {
            if ((string)$user->username === $username) {
                $taken = true;
                break;
            }
        }

        if ($taken) {
            $error = "Username already taken!";
        } else {
            $user = $xml->addChild('user');
            $user->addChild('fullname', $fullname);
            $user->addChild('username', $username);
            $user->addChild('password', $password); 

            $xml->asXML($file);
            $success = "Account created successfully. <a href='index.php'>Login here</a>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign up / MIRA Account Management</title>
    <meta charset="UTF-8">
    <title>Sign Up</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #373643;
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
        background: #373643;
        padding: 30px;
        border: 1px solid #4a4a5a;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
    }

    .logo {
        text-align: center;
        margin-bottom: 20px;
    }

    .logo img {
        max-width: 100%;
    }

    h2 {
        font-size: 24px;
        margin-bottom: 15px;
        color: #ffffff;
        text-align: center;
    }

    .input-group {
        margin-bottom: 15px;
    }

    .input-group label {
        display: block;
        font-size: 14px;
        margin-bottom: 5px;
        color: #d0d0d0;
    }

    .input-group input {
        width: 100%;
        padding: 10px;
        font-size: 14px;
        background-color: #2d2c36;
        color: #ffffff;
        border: 1px solid #555;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .input-group input:focus {
        border-color: #18cb96;
        outline: none;
        background-color: #2a2a33;
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
        color: #c0c0c0;
    }

    .footer a {
        color: #18cb96;
        text-decoration: none;
    }

    .footer a:hover {
        text-decoration: underline;
    }

    .error, .success {
        text-align: center;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .error {
        color: #ff6b6b;
    }

    .success {
        color: #66ff99;
    }
</style>

</head>
<body>

<div class="form-container">
    <div class="logo">
        <img src="mirablk.png" alt="Logo">
    </div>

    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>

    <?php if (!$success): ?>
    <form method="post">
        <h2>Sign Up</h2>

        <div class="input-group">
            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname">
        </div>

        <div class="input-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username">
        </div>

        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
        </div>

        <button type="submit" class="signin-btn">Sign Up</button>

        <div class="footer">
            Already have an account? <a href="index.php">Login here</a>
        </div>
    </form>
    <?php endif; ?>
</div>

</body>
</html>
