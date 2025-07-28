<?php
include "config/constant.php";
// session_start(); // Required for sessions

$message = "";

if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $row["password"])) {
            $_SESSION["login"] = true;
            $_SESSION["id"] = $row["id"];
            header("Location: index.php");
            exit();
        } else {
            $message = "❌ Wrong password.";
        }
    } else {
        $message = "❌ User not registered.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Nunito', sans-serif;
        }

        body {
            background: #dde1e7;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            perspective: 2000px;
            transform-style: preserve-3d;
            overflow: hidden;
        }

        /* 3D Background Elements */
        .bg-element {
            position: fixed;
            border-radius: 50%;
            filter: blur(60px);
            transform-style: preserve-3d;
            opacity: 0.6;
            z-index: -1;
        }

        .bg-1 {
            width: 300px;
            height: 300px;
            background: #FF4D67;
            top: 20%;
            left: 10%;
            transform: translateZ(-800px);
            animation: float 12s ease-in-out infinite;
        }

        .bg-2 {
            width: 400px;
            height: 400px;
            background: #FF8A5B;
            bottom: 15%;
            right: 10%;
            transform: translateZ(-500px);
            animation: float 15s ease-in-out infinite reverse;
        }

        .bg-3 {
            width: 200px;
            height: 200px;
            background: #25CED1;
            top: 60%;
            left: 30%;
            transform: translateZ(-300px);
            animation: float 10s ease-in-out infinite 2s;
        }

        /* Login Container */
        .login-container {
            background: #dde1e7;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 
                8px 8px 16px #babecc, 
                -8px -8px 16px #ffffff,
                0 0 0 4px rgba(255, 255, 255, 0.1);
            width: 500px;
            text-align: center;
            transform-style: preserve-3d;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.8s ease-out forwards;
        }

        .login-container:hover {
            transform: 
                translateY(-8px) 
                rotateX(3deg) 
                rotateY(2deg);
            box-shadow: 
                12px 12px 24px #babecc, 
                -12px -12px 24px #ffffff,
                0 0 0 4px rgba(255, 255, 255, 0.2);
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.15) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: rotate(30deg);
            animation: shine 5s infinite;
        }

        .login-container h2 {
            margin-bottom: 30px;
            font-size: 24px;
            color: #333;
            position: relative;
            transform: translateZ(20px);
        }

        .login-container h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%) translateZ(10px);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #FF4D67, #FF8A5B);
            border-radius: 3px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Input Fields */
        .input-field {
            background: #dde1e7;
            box-shadow: 
                inset 6px 6px 10px #babecc, 
                inset -6px -6px 10px #ffffff;
            border: none;
            border-radius: 30px;
            padding: 15px 20px;
            margin: 23px 0;
            width: 100%;
            font-size: 16px;
            outline: none;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-style: preserve-3d;
        }

        .input-field:focus {
            box-shadow: 
                inset 4px 4px 8px #babecc, 
                inset -4px -4px 8px #ffffff;
            transform: translateZ(15px);
        }

        /* Login Button */
        .login-btn {
            background: linear-gradient(135deg, #FF4D67, #FF8A5B);
            color: white;
            border: none;
            padding: 15px 30px;
            margin-top: 30px;
            border-radius: 30px;
            box-shadow: 
                6px 6px 12px #babecc, 
                -6px -6px 12px #ffffff,
                0 0 0 2px rgba(255, 255, 255, 0.3);
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 200%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.4),
                transparent
            );
            transition: all 0.8s ease;
            transform: rotate(25deg);
        }

        .login-btn:hover {
            transform: 
                translateY(-5px) 
                translateZ(20px);
            box-shadow: 
                8px 8px 16px #babecc, 
                -8px -8px 16px #ffffff,
                0 0 0 2px rgba(255, 255, 255, 0.4);
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:active {
            transform: translateY(2px) translateZ(10px);
        }

        /* Footer */
        .auth-footer {
            margin-top: 25px;
            color: #666;
            font-size: 14px;
            transform: translateZ(10px);
        }

        .auth-link {
            color: #FF4D67;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .auth-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #FF4D67;
            transition: width 0.3s ease;
        }

        .auth-link:hover::after {
            width: 100%;
        }

        /* Message */
        .message {
            color: #FF4D67;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 8px;
            background: rgba(255, 77, 103, 0.1);
            animation: pulse 2s infinite;
            transform: translateZ(15px);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px) translateZ(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0) translateZ(0);
            }
        }

        @keyframes float {
            0% {
                transform: translateY(0) translateZ(-800px);
            }
            50% {
                transform: translateY(-20px) translateZ(-850px);
            }
            100% {
                transform: translateY(0) translateZ(-800px);
            }
        }

        @keyframes shine {
            0% {
                transform: rotate(30deg) translate(-100%, -100%);
            }
            100% {
                transform: rotate(30deg) translate(100%, 100%);
            }
        }

        @keyframes pulse {
            0% {
                transform: translateZ(15px);
                box-shadow: 0 0 0 0 rgba(255, 77, 103, 0.2);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(255, 77, 103, 0);
            }
            100% {
                transform: translateZ(15px);
                box-shadow: 0 0 0 0 rgba(255, 77, 103, 0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                width: 90%;
                padding: 30px;
            }
            
            .login-container h2 {
                font-size: 20px;
                margin-bottom: 20px;
            }
            
            .input-field {
                padding: 12px 18px;
                margin: 18px 0;
            }
            
            .login-btn {
                padding: 12px 25px;
                margin-top: 25px;
            }

            .bg-element {
                display: none;
            }
        }
    </style>
</head>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login User</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700&display=swap" rel="stylesheet">
    <style>
       
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login User</h2>

    
        <?php if (!empty($message)): ?>
            <p style="color: red; margin-bottom: 15px;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form name="formal" action="" method="post" autocomplete="off">
            <input type="text" placeholder="Username" name="username" class="input-field" autocomplete="off" required>
            <input type="password" name="password" placeholder="Password" class="input-field" autocomplete="new-password" required>
            <button type="submit" name="submit" class="login-btn">Sign In</button>
        </form>
    </div>
</body>
</html>

</html>