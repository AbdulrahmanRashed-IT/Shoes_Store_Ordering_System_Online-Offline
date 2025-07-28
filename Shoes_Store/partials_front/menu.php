<?php include('config/constant.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- Important to make website responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sandal Store</title>

    <!-- Link our CSS files -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="enhanced-header-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <!-- Navbar Section Starts Here -->
    <section class="navbar">
        <div class="container">
            <div class="site-title">
                <h1 data-text="Sandal Store">Sandal Store</h1>
            </div>

            <div class="main-menu">
                <ul>
                    <li><a href="<?php echo SITEURL; ?>index.php">
                        <i class="fas fa-home"></i> Home
                    </a></li>
                    <li><a href="<?php echo SITEURL; ?>categories.php">
                        <i class="fas fa-th-large"></i> Categories
                    </a></li>
                    <li><a href="<?php echo SITEURL; ?>products.php">
                        <i class="fas fa-shopping-bag"></i> Products
                    </a></li>
                    <li><a href="<?php echo SITEURL; ?>contact.php">
                        <i class="fas fa-envelope"></i> Contact
                    </a></li>
                </ul>
            </div>

            <div class="user-menu">
                <ul>
                    <li><a href="register.php">
                        <i class="fas fa-user-plus"></i> Register
                    </a></li>
                    <li><a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a></li>
                </ul>
            </div>

            <div class="clearfix"></div>
        </div>

        <div class="delivery-banner">
            <p><i class="fas fa-truck"></i> Get Free Delivery on Orders Over $50!</p>
        </div>
    </section>
    <!-- Navbar Section Ends Here -->
