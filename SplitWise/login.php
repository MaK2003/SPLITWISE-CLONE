<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        $xml = simplexml_load_file('users.xml');

        foreach ($xml->user as $user) {
            if ($user->username == $username && $user->password == $password) {
                setcookie('username', $username, time() + 3600, '/');
                
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit;
            }
        }

        header("Location: login.php?msg=Invalid username or password");
        exit;
    } elseif (isset($_POST['register'])) {
        $newUsername = htmlspecialchars($_POST['newUsername']);
        $newPassword = htmlspecialchars($_POST['newPassword']);

        if (isUsernameUnique($newUsername, $xml)) {
            $xml = simplexml_load_file('users.xml');
            $newUser = $xml->addChild('user');
            $newUser->addChild('username', $newUsername);
            $newUser->addChild('password', $newPassword);
            $xml->asXML('users.xml');

            header("Location: login.php?msg=Account created successfully. Please login.");
            exit;
        } else {
            header("Location: login.php?msg=Username already exists. Please choose another.");
            exit;
        }
    }
}

function isUsernameUnique($username, $xml)
{
    foreach ($xml->user as $user) {
        if ($user->username == $username) {
            return false;
        }
    }
    return true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SplitWise - Login</title>

    <style>
        body {
    font-family: 'Arial', sans-serif;
    background-image: url('https://i.imgur.com/7oplOuH.png');
    background-size: cover; 
    background-position: center; 
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    margin: 0;
}


        h2 {
            color: white;
            margin-bottom: 20px;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.4);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 80%;
            max-width: 400px;
            text-align: center;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: white;
        }

        input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        p {
            margin-top: 20px;
            font-size: 18px;
            color: white;
            cursor: pointer;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2 id="form-title">Login</h2>

    <div id="login-form" class="form-container">
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" name="login" value="Login">
        </form>
    </div>

    <div id="register-form" class="form-container" style="display: none;">
        <form action="login.php" method="post">
            <label for="newUsername">New Username:</label>
            <input type="text" id="newUsername" name="newUsername" required>

            <label for="newPassword">New Password:</label>
            <input type="password" id="newPassword" name="newPassword" required>

            <input type="submit" name="register" value="Register">
        </form>
    </div>

    <p id="toggle-form">Don't have an account? Register Now.</p>

    <script>
        document.getElementById('toggle-form').addEventListener('click', function () {
            var loginForm = document.getElementById('login-form');
            var registerForm = document.getElementById('register-form');
            var formTitle = document.getElementById('form-title');
            var toggleText = document.getElementById('toggle-form');

            if (loginForm.style.display === 'block') {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                formTitle.innerText = 'Register';
                toggleText.innerText = 'Switch back to Login';
            } else {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
                formTitle.innerText = 'Login';
                toggleText.innerText = 'Don\'t have an account? Register Now.';
            }
        });
    </script>
</body>
</html>

