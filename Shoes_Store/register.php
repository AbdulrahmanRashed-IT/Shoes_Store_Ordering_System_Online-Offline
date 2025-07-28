<?php
include "config/constant.php"; // Make sure this path is correct

// session_start(); // Uncommented - required for sessions

$message = "";

// Fixed: Changed !isset to isset (logical error)
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    
    // Input validation
    if (empty($username) || empty($email) || empty($password)) {
        $message = "❌ All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "❌ Invalid email format!";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Use prepared statements to prevent SQL injection
        $check_query = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $message = "❌ Email already registered!";
        } else {
            // Insert new user with prepared statement
            $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
            
            if (mysqli_stmt_execute($stmt)) {
                $message = "✅ Registration successful!";
            } else {
                $message = "❌ Error: " . mysqli_error($conn);
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #FF4D67;
            --secondary: #FF8A5B;
            --accent: #25CED1;
            --light: #F8F8F8;
            --dark: #292929;
            --success: #2ECC71;
            --warning: #F39C12;
            --depth-1: 5px;
            --depth-2: 10px;
            --depth-3: 20px;
            --depth-4: 30px;
            --depth-5: 50px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--light);
            min-height: 100vh;
            color: var(--dark);
            perspective: 1200px;
            transform-style: preserve-3d;
            overflow-x: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            transform-style: preserve-3d;
        }

        /* Parallax Background Layers */
        .parallax-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            transform-style: preserve-3d;
        }

        .bg-layer-1 {
            background: radial-gradient(circle at 30% 50%, rgba(255, 77, 103, 0.05) 0%, transparent 40%);
            transform: translateZ(-1000px);
        }

        .bg-layer-2 {
            background: radial-gradient(circle at 70% 30%, rgba(37, 206, 209, 0.05) 0%, transparent 40%);
            transform: translateZ(-800px);
        }

        /* Main Container with 3D Perspective */
        .container {
            background: white;
            border-radius: 25px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
            margin: 3rem auto;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-style: preserve-3d;
            transform: translateZ(var(--depth-1));
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: flex;
            width: 900px;
            height: 600px;
            position: relative;
        }

        .container:hover {
            transform: translateY(-10px) translateZ(var(--depth-2)) rotateX(1deg) rotateY(1deg);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2);
        }

        .form-container,
        .welcome-container {
            width: 50%;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transform-style: preserve-3d;
        }

        .form-container {
            background: white;
            position: relative;
            overflow: hidden;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to right,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.15) 50%,
                    rgba(255, 255, 255, 0) 100%);
            transform: rotate(30deg) translateZ(var(--depth-1));
            animation: shine 3s infinite;
        }

        .form-container h2 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 30px;
            color: var(--dark);
            position: relative;
            transform: translateZ(var(--depth-2));
        }

        .form-container h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 4px;
            transform: translateZ(var(--depth-1));
            box-shadow: 0 2px 10px rgba(255, 77, 103, 0.3);
        }

        /* 3D Input Groups */
        .input-group {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            border-radius: 30px;
            padding: 15px 25px;
            background: white;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-style: preserve-3d;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .input-group::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg,
                    rgba(255, 255, 255, 0.4) 0%,
                    rgba(255, 255, 255, 0) 60%);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 1;
            pointer-events: none;
            transform: translateZ(10px);
        }

        .input-group:hover {
            transform: translateY(-5px) translateZ(var(--depth-2)) rotateX(2deg);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .input-group:hover::before {
            opacity: 1;
        }

        .input-group input {
            border: none;
            background: transparent;
            flex: 1;
            padding-left: 15px;
            font-size: 16px;
            outline: none;
            color: var(--dark);
            transform: translateZ(var(--depth-1));
        }

        .input-group i {
            color: var(--primary);
            font-size: 18px;
            transform: translateZ(var(--depth-1));
        }

        /* Advanced 3D Button */
        .btn-register {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            color: white;
            padding: 16px 0;
            border-radius: 50px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 700;
            width: 100%;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-style: preserve-3d;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(255, 77, 103, 0.4);
            letter-spacing: 0.8px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            margin-top: 10px;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 200%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, 0.3),
                    transparent);
            transition: all 0.8s ease;
            transform: rotate(20deg);
        }

        .btn-register:hover {
            transform: translateY(-5px) translateZ(var(--depth-3)) rotateX(5deg);
            box-shadow: 0 15px 35px rgba(255, 77, 103, 0.5);
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-register:active {
            transform: translateY(2px) translateZ(var(--depth-1));
        }

        /* Social Login Section */
        .social-login {
            margin-top: 30px;
            text-align: center;
            color: #777;
            position: relative;
            transform: translateZ(var(--depth-1));
        }

        .social-login::before {
            content: '';
            position: absolute;
            top: -15px;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.1), transparent);
            transform: translateZ(var(--depth-1));
        }

        .social-icons {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 15px;
            transform-style: preserve-3d;
        }

        .social-icons a {
            color: white;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            transform-style: preserve-3d;
            position: relative;
            overflow: hidden;
        }

        .social-icons a::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to bottom right,
                    rgba(255, 255, 255, 0.3) 0%,
                    rgba(255, 255, 255, 0) 60%);
            transform: rotate(30deg);
            transition: all 0.5s ease;
        }

        .social-icons a:hover {
            transform: translateY(-8px) rotate(10deg) translateZ(var(--depth-4));
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        .social-icons a:hover::before {
            transform: rotate(45deg) translate(15px, 15px);
        }

        /* Welcome Container */
        .welcome-container {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
        }

        .welcome-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to right,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.15) 50%,
                    rgba(255, 255, 255, 0) 100%);
            transform: rotate(30deg) translateZ(var(--depth-1));
            animation: shine 3s infinite;
        }

        .welcome-container h2 {
            font-size: 36px;
            margin-bottom: 15px;
            transform: translateZ(var(--depth-3));
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .welcome-container p {
            font-size: 16px;
            margin-bottom: 30px;
            opacity: 0.9;
            transform: translateZ(var(--depth-1));
        }

        .btn-login {
            background: transparent;
            border: 2px solid white;
            color: white;
            padding: 14px 32px;
            border-radius: 30px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-style: preserve-3d;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.8px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 200%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, 0.3),
                    transparent);
            transition: all 0.8s ease;
            transform: rotate(20deg);
        }

        .btn-login:hover {
            background: white;
            color: var(--primary);
            transform: translateY(-5px) translateZ(var(--depth-3)) rotateX(5deg);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .login-options {
            display: none;
            margin-top: 20px;
            flex-direction: column;
            align-items: center;
            transform-style: preserve-3d;
            animation: fadeIn 0.5s ease forwards;
        }

        .login-options button {
            background: white;
            color: var(--primary);
            border: none;
            margin: 10px 0;
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            width: 80%;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transform-style: preserve-3d;
            position: relative;
            overflow: hidden;
        }

        .login-options button:hover {
            transform: translateY(-5px) translateZ(var(--depth-3));
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .close-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background: transparent;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-style: preserve-3d;
        }

        .close-button:hover {
            transform: scale(1.2) rotate(90deg) translateZ(var(--depth-2));
        }

        /* Message Styling */
        .message {
            color: var(--primary);
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
            padding: 10px;
            border-radius: 8px;
            background: rgba(255, 77, 103, 0.1);
            transform: translateZ(var(--depth-1));
            animation: fadeIn 0.5s ease forwards;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px) translateZ(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0) translateZ(0);
            }
        }

        @keyframes shine {
            0% {
                transform: rotate(30deg) translate(-100%, -100%) translateZ(var(--depth-1));
            }
            100% {
                transform: rotate(30deg) translate(100%, 100%) translateZ(var(--depth-1));
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1) translateZ(var(--depth-2));
            }
            50% {
                transform: scale(1.05) translateZ(var(--depth-3));
            }
            100% {
                transform: scale(1) translateZ(var(--depth-2));
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .container {
                flex-direction: column;
                height: auto;
                width: 90%;
            }

            .form-container,
            .welcome-container {
                width: 100%;
                padding: 40px;
            }

            .welcome-container {
                border-radius: 0 0 25px 25px;
            }
        }

        @media (max-width: 576px) {
            .container {
                width: 95%;
            }

            .form-container,
            .welcome-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Registration</h2>
            <?php if (!empty($message)): ?>
            <p style="color: red; margin-bottom: 15px;"><?php echo $message; ?></p>
            <?php endif; ?>

            <form name="formal" action="" method="post" autocomplete="off">
                <div class="input-group"><i class="fas fa-user"></i><input type="text" name="username" placeholder="Username" autocomplete="off"></div>
                <div class="input-group"><i class="fas fa-envelope"></i><input type="email" name="email" placeholder="Email" autocomplete="off"></div>
                <div class="input-group"><i class="fas fa-lock"></i><input type="password" name="password" placeholder="Password" autocomplete="new-password"></div>
                <button class="btn-register" type="submit" name="submit">Register</button>
            </form>
            <div class="social-login">
                or register with social platforms
                <div class="social-icons">
                    <a href="#"><i class="fab fa-google"></i></a>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-github"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        <div class="welcome-container">
            <button class="close-button" onclick="document.querySelector('.welcome-container').style.display='none'">&times;</button>
            <h2>Welcome Back!</h2>
            <p>Already have an account?</p>
            <button class="btn-login" onclick="document.querySelector('.login-options').style.display='flex'">Login</button>
            <div class="login-options">
                <button onclick="window.location.href='admin/login_admin.php'">Login as Admin</button>
                <button onclick="window.location.href='login_user.php'">Login as User</button>
            </div>
        </div>
    </div>
</body>

</html>