<?php

include('../config/constant.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(145deg, #f0f8ff, #e0ffff);
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
        box-sizing: border-box;
    }

    .login-card {
        background-color: #ffffff;
        padding: 3rem;
        border-radius: 25px;
        box-shadow:
            0 0 30px rgba(0, 255, 255, 0.3),
            0 0 60px rgba(255, 105, 180, 0.25),
            inset 0 0 15px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        position: relative;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .login-card::before,
    .login-card::after {
        content: '';
        position: absolute;
        background: linear-gradient(90deg, #00f9ff, #ff66cc);
        border-radius: 10px;
    }

    .login-card::before {
        width: 100%;
        height: 8px;
        top: -8px;
        left: 0;
    }

    .login-card::after {
        height: 100%;
        width: 8px;
        right: -8px;
        top: 0;
    }

    .login-card h2 {
        color: #333;
        text-align: center;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        letter-spacing: 1px;
        text-shadow: 0 0 5px #ff66cc80;
    }

    .login-card h2::before,
    .login-card h2::after {
        content: '✨';
        margin: 0 0.3rem;
        color: #ff66cc;
    }

    .input-group {
        margin-bottom: 1.2rem;
    }

    .input-group input {
        width: 100%;
        padding: 0.8rem;
        border-radius: 30px;
        border: none;
        background: #f2f9ff;
        color: #333;
        font-size: 0.9rem;
        box-shadow:
            inset 2px 2px 6px #cce7f5,
            inset -2px -2px 6px #ffffff;
        outline: none;
        transition: 0.3s ease;
        box-sizing: border-box;
    }

    .input-group input::placeholder {
        color: #999;
    }

    .input-group input:focus {
        box-shadow:
            inset 1px 1px 8px #b3e5fc,
            inset -1px -1px 8px #ffffff;
    }

    button {
        width: 100%;
        padding: 0.8rem;
        background: linear-gradient(to right, #00f9ff, #ff66cc);
        color: white;
        border: none;
        border-radius: 30px;
        font-weight: bold;
        font-size: 0.9rem;
        cursor: pointer;
        box-shadow:
            0 5px 15px rgba(0, 255, 255, 0.4),
            0 0 20px rgba(255, 105, 180, 0.4);
        transition: all 0.3s ease;
    }

    button:hover {
        background: linear-gradient(to right, #00d4d4, #ff3399);
        transform: translateY(-2px);
    }

    .alert-danger {
        color: #ff0000;
        background-color: #ffe6e6;
        padding: 10px;
        border-radius: 10px;
        text-align: center;
        margin-top: 15px;
        font-weight: bold;
        box-shadow: 0 0 10px #ffcccc;
        font-size: 0.9rem;
    }

    /* Mobile responsiveness */
    @media (max-width: 480px) {
        body {
            padding: 15px;
        }

        .login-card {
            padding: 2rem;
            border-radius: 20px;
        }

        .login-card h2 {
            font-size: 1.3rem;
            margin-bottom: 1.2rem;
        }

        .login-card::before {
            height: 6px;
            top: -6px;
        }

        .login-card::after {
            width: 6px;
            right: -6px;
        }

        .input-group input {
            padding: 0.7rem;
        }

        button {
            padding: 0.7rem;
        }
    }

    @media (max-width: 360px) {
        .login-card {
            padding: 1.5rem;
        }

        .login-card h2 {
            font-size: 1.2rem;
        }
    }
</style>
</head>
<body>

<div class="login-card">
    <h2>LOGIN ADMIN</h2>
    <form action="" method="post" autocomplete="off">
        <div class="input-group">
            <input type="text" name="username" placeholder="Username" autocomplete="off" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" autocomplete="new-password" required>
        </div>
        <button type="submit" name="submit">Login</button>

        <?php
        if(isset($_SESSION['login-message'])) {
            echo $_SESSION['login-message'];
            unset($_SESSION['login-message']);
        }
        ?>

        <?php
        if (isset($_POST['submit'])) {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $password = md5($_POST['password']); // MD5 hash of entered password

            $sql = "SELECT * FROM tbl_admin WHERE username='$username' AND password='$password'";
            $res = mysqli_query($conn, $sql);

            if (mysqli_num_rows($res) == 1) {
                $admin = mysqli_fetch_assoc($res);
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['username'] = $admin['username'];

                echo '<script>window.location.href = "dashboard.php";</script>';
                exit;
            } else {
                echo '<div class="alert-danger">Invalid username or password</div>';
            }
        }
        ?>
    </form>
</div>

</body>
</html>