<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../config/constant.php');

// Check if user is logged in
if(!isset($_SESSION['admin_id'])) {
    // User is not logged in
    // Redirect to login page with error message
    $_SESSION['login-message'] = "<div class='error'>Please login to access Admin Panel.</div>";
    header('location: login_admin.php');
    exit();
}

// Get the section parameter
$section = isset($_GET['section']) ? $_GET['section'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : 'manage';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$image_name = isset($_GET['image_name']) ? $_GET['image_name'] : '';

// Debug: Show what we're processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<!-- DEBUG: POST request received for section: $section, action: $action -->";
}

// Handle all form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Admin Management Forms
    if (isset($_POST['add_admin'])) {
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = md5($_POST['password']);

        $sql = "INSERT INTO tbl_admin SET full_name = '$full_name', username = '$username', password = '$password'";
        $res = mysqli_query($conn, $sql);

        if ($res == TRUE) {
            $_SESSION['add'] = "<div class='success'>Admin Added Successfully</div>";
        } else {
            $_SESSION['add'] = "<div class='error'>Failed to add Admin: " . mysqli_error($conn) . "</div>";
        }
        header("location: dashboard.php?section=admin");
        exit();
    }

    if (isset($_POST['update_admin'])) {
        $id = (int)$_POST['id'];
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);

        $sql = "UPDATE tbl_admin SET full_name='$full_name', username='$username' WHERE id='$id'";
        $res = mysqli_query($conn, $sql);

        if ($res == true) {
            $_SESSION['update'] = "<div class='success'>Admin Updated Successfully.</div>";
        } else {
            $_SESSION['update'] = "<div class='error'>Failed to Update Admin: " . mysqli_error($conn) . "</div>";
        }
        header('location: dashboard.php?section=admin');
        exit();
    }

    if (isset($_POST['change_password'])) {
        $id = (int) $_POST['id'];
        $current_password_input = md5($_POST['current_password']);
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            $_SESSION['pwd-not-match'] = "<div class='error'>Passwords did not match.</div>";
            header('location: dashboard.php?section=admin');
            exit();
        }

        $sql = "SELECT password FROM tbl_admin WHERE id = $id";
        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) === 1) {
            $row = mysqli_fetch_assoc($res);
            $hashed_password_in_db = $row['password'];

            if ($current_password_input === $hashed_password_in_db) {
                $new_hashed_password = md5($new_password);
                $update_sql = "UPDATE tbl_admin SET password = '$new_hashed_password' WHERE id = $id";

                if (mysqli_query($conn, $update_sql)) {
                    $_SESSION['change-pwd'] = "<div class='success'>Password changed successfully.</div>";
                } else {
                    $_SESSION['change-pwd'] = "<div class='error'>Failed to change password: " . mysqli_error($conn) . "</div>";
                }
            } else {
                $_SESSION['pwd-not-match'] = "<div class='error'>Current password is incorrect.</div>";
            }
        } else {
            $_SESSION['user-not-found'] = "<div class='error'>User not found.</div>";
        }

        header('location: dashboard.php?section=admin');
        exit();
    }

    // Category Management Forms
    if (isset($_POST['add_category'])) {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $featured = isset($_POST['featured']) ? $_POST['featured'] : "No";
        $active = isset($_POST['active']) ? $_POST['active'] : "No";

        $image_name = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $image_name = $_FILES['image']['name'];
            $ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $image_name = "Shoes_Category_" . rand(000, 999) . '.' . $ext;

            $source_path = $_FILES['image']['tmp_name'];
            $destination_path = "../images/category/" . $image_name;

            if (!move_uploaded_file($source_path, $destination_path)) {
                $_SESSION['upload'] = "<div class='error'>Failed to Upload Image.</div>";
                header('location: dashboard.php?section=category&action=add');
                exit();
            }
        }

        $sql = "INSERT INTO tbl_category SET title='$title', image_name='$image_name', featured='$featured', active='$active'";
        $res = mysqli_query($conn, $sql);

        if ($res == true) {
            $_SESSION['add'] = "<div class='success'>Category Added Successfully.</div>";
        } else {
            $_SESSION['add'] = "<div class='error'>Failed to Add Category: " . mysqli_error($conn) . "</div>";
        }
        header('location: dashboard.php?section=category');
        exit();
    }

    if (isset($_POST['update_category'])) {
        $id = (int)$_POST['id'];
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $current_image = $_POST['current_image'];
        $featured = $_POST['featured'];
        $active = $_POST['active'];

        $image_name = $current_image;
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image_name = "Shoes_category_" . rand(000, 999) . '.' . $ext;

            $source_path = $_FILES['image']['tmp_name'];
            $destination_path = "../images/category/" . $image_name;

            if (move_uploaded_file($source_path, $destination_path)) {
                if ($current_image != "" && file_exists("../images/category/" . $current_image)) {
                    unlink("../images/category/" . $current_image);
                }
            } else {
                $_SESSION['upload'] = "<div class='error'>Failed to Upload Image.</div>";
                header('location: dashboard.php?section=category');
                exit();
            }
        }

        $sql2 = "UPDATE tbl_category SET title = '$title', image_name = '$image_name', featured = '$featured', active = '$active' WHERE id = $id";
        $res2 = mysqli_query($conn, $sql2);

        if ($res2 == true) {
            $_SESSION['update'] = "<div class='success'>Category Updated Successfully.</div>";
        } else {
            $_SESSION['update'] = "<div class='error'>Failed to Update Category: " . mysqli_error($conn) . "</div>";
        }
        header('location: dashboard.php?section=category');
        exit();
    }

    // Products Management Forms
    if (isset($_POST['add_shoes'])) {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $price = (float)$_POST['price'];
        $category = (int)$_POST['category'];
        $featured = isset($_POST['featured']) ? $_POST['featured'] : "No";
        $active = isset($_POST['active']) ? $_POST['active'] : "No";

        $image_name = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image_name = "Shoes-Name-" . rand(0000, 9999) . "." . $ext;

            $src = $_FILES['image']['tmp_name'];
            $dst = "../images/shoes/" . $image_name;

            if (!move_uploaded_file($src, $dst)) {
                $_SESSION['upload'] = "<div class='error'>Failed to Upload Image.</div>";
                header('Location: dashboard.php?section=products&action=add');
                exit();
            }
        }

        $sql2 = "INSERT INTO tbl_shoes SET title='$title', description='$description', price='$price', image_name='$image_name', category_id='$category', featured='$featured', active='$active'";
        $res2 = mysqli_query($conn, $sql2);

        if ($res2 == true) {
            $_SESSION['add'] = "<div class='success'>Shoes Added Successfully.</div>";
        } else {
            $_SESSION['add'] = "<div class='error'>Failed to add shoes: " . mysqli_error($conn) . "</div>";
        }
        header('location: dashboard.php?section=products');
        exit();
    }

    if (isset($_POST['update_shoes'])) {
        $id = (int)$_POST['id'];
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $price = (float)$_POST['price'];
        $category = (int)$_POST['category'];
        $featured = $_POST['featured'];
        $active = $_POST['active'];
        $current_image = $_POST['current_image'];

        $image_name = $current_image;
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image_name = "Shoes-Update-" . rand(0000, 9999) . "." . $ext;

            $source_path = $_FILES['image']['tmp_name'];
            $destination_path = "../images/shoes/" . $image_name;

            if (move_uploaded_file($source_path, $destination_path)) {
                if ($current_image != "" && file_exists("../images/shoes/" . $current_image)) {
                    unlink("../images/shoes/" . $current_image);
                }
            } else {
                $_SESSION['upload'] = "<div class='error'>Failed to Upload Image.</div>";
                header('location: dashboard.php?section=products');
                exit();
            }
        }

        $sql3 = "UPDATE tbl_shoes SET title = '$title', description = '$description', price = $price, image_name = '$image_name', category_id = $category, featured = '$featured', active = '$active' WHERE id = $id";
        $res3 = mysqli_query($conn, $sql3);

        if ($res3) {
            $_SESSION['update'] = "<div class='success'>Shoes Updated Successfully.</div>";
        } else {
            $_SESSION['update'] = "<div class='error'>Failed to Update Shoes: " . mysqli_error($conn) . "</div>";
        }
        header('location: dashboard.php?section=products');
        exit();
    }

    // Order Management Forms
    if (isset($_POST['update_order'])) {
        $id = (int)$_POST['id'];
        $price = (float)$_POST['price'];
        $qty = (int)$_POST['qty'];
        $total = $price * $qty;

        $status = mysqli_real_escape_string($conn, $_POST['status']);
        $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
        $customer_contact = mysqli_real_escape_string($conn, $_POST['customer_contact']);
        $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
        $customer_address = mysqli_real_escape_string($conn, $_POST['customer_address']);

        $size = mysqli_real_escape_string($conn, $_POST['size']);
        $sql2 = "UPDATE tbl_order SET qty = '$qty', size = '$size', total = '$total', status = '$status', customer_name = '$customer_name', customer_contact = '$customer_contact', customer_email = '$customer_email', customer_address = '$customer_address' WHERE id = $id";

        $res2 = mysqli_query($conn, $sql2);

        if ($res2) {
            $_SESSION['update'] = "<div class='success'>Order Updated Successfully.</div>";
        } else {
            $_SESSION['update'] = "<div class='error'>Failed to Update Order: " . mysqli_error($conn) . "</div>";
        }
        header('Location: dashboard.php?section=orders');
        exit();
    }
}

// Handle delete actions
if ($action === 'delete' && $id > 0) {
    if ($section === 'admin') {
        $sql = "DELETE FROM tbl_admin WHERE id = $id";
        $res = mysqli_query($conn, $sql);
        if ($res == TRUE) {
            $_SESSION['delete'] = "<div class='success'>Admin Deleted Successfully.</div>";
        } else {
            $_SESSION['delete'] = "<div class='error'>Failed to Delete Admin: " . mysqli_error($conn) . "</div>";
        }
        header('location: dashboard.php?section=admin');
    } elseif ($section === 'category') {
        if ($image_name != "" && file_exists("../images/category/" . $image_name)) {
            unlink("../images/category/" . $image_name);
        }
        $sql = "DELETE FROM tbl_category WHERE id=$id";
        $res = mysqli_query($conn, $sql);
        if ($res == true) {
            $_SESSION['delete'] = "<div class='success'>Category Deleted Successfully.</div>";
        } else {
            $_SESSION['delete'] = "<div class='error'>Failed to Delete Category: " . mysqli_error($conn) . "</div>";
        }
        header('location: dashboard.php?section=category');
    } elseif ($section === 'products') {
        if ($image_name !== "" && file_exists("../images/shoes/" . $image_name)) {
            unlink("../images/shoes/" . $image_name);
        }
        $sql = "DELETE FROM tbl_shoes WHERE id = $id";
        $res = mysqli_query($conn, $sql);
        if ($res === true) {
            $_SESSION['delete'] = "<div class='success'>Shoe deleted successfully.</div>";
        } else {
            $_SESSION['delete'] = "<div class='error'>Failed to delete shoe: " . mysqli_error($conn) . "</div>";
        }
        header('Location: dashboard.php?section=products');
    } elseif ($section === 'orders') {
        $sql = "DELETE FROM tbl_order WHERE id = $id";
        $res = mysqli_query($conn, $sql);
        if ($res === true) {
            $_SESSION['delete'] = "<div class='success'>Order deleted successfully.</div>";
        } else {
            $_SESSION['delete'] = "<div class='error'>Failed to delete order: " . mysqli_error($conn) . "</div>";
        }
        header('Location: dashboard.php?section=orders');
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Sandal Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Modern Admin Dashboard CSS */
        :root {
            --primary: #4361ee;
            --primary-dark: #3a0ca3;
            --secondary: #3f37c9;
            --dark: #2b2d42;
            --light: #f8f9fa;
            --gray: #e9ecef;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --info: #4895ef;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            line-height: 1.6;
            color: #333;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar - Enhanced */
        .sidebar {
            width: 280px;
            background: var(--dark);
            color: white;
            padding: 1.5rem 0;
            position: fixed;
            height: 100vh;
            transition: var(--transition);
            z-index: 100;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 1rem;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .sidebar ul {
            list-style: none;
            padding: 1.5rem 0;
        }

        .sidebar ul li {
            margin: 0.5rem 0;
        }

        .sidebar ul li a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            transition: var(--transition);
            border-left: 3px solid transparent;
            font-weight: 500;
        }

        .sidebar ul li a i {
            margin-right: 12px;
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }

        .sidebar ul li a:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            border-left: 3px solid var(--primary);
        }

        .sidebar ul li a.active {
            background: rgba(67, 97, 238, 0.1);
            color: white;
            border-left: 3px solid var(--primary);
        }

        /* Main Content - Enhanced */
        .main-content {
            flex: 1;
            padding: 2rem;
            margin-left: 280px;
            transition: var(--transition);
        }

        .header {
            background: white;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            color: var(--dark);
            font-size: 1.75rem;
            font-weight: 600;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .content {
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 1.5rem;
        }

        /* Buttons - Enhanced */
        .btn {
            padding: 0.6rem 1.2rem;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: var(--border-radius);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 500;
            transition: var(--transition);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn i {
            font-size: 1rem;
        }

        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }

        .btn-danger {
            background: var(--danger);
        }

        .btn-danger:hover {
            background: #d11450;
        }

        .btn-warning {
            background: var(--warning);
            color: #333;
        }

        .btn-warning:hover {
            background: #e68a19;
        }

        .btn-success {
            background: var(--success);
        }

        .btn-success:hover {
            background: #3ab7db;
        }

        /* Tables - Enhanced */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            font-size: 0.95rem;
        }

        table th,
        table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--gray);
        }

        table th {
            background: #f8f9fa;
            font-weight: 600;
            color: var(--dark);
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        table tr:hover {
            background-color: rgba(67, 97, 238, 0.02);
        }

        /* Forms - Enhanced */
        .form-group {
            margin: 1.2rem 0;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            outline: none;
        }

        /* Alerts - Enhanced */
        .alert {
            padding: 1rem;
            border-radius: var(--border-radius);
            margin: 1rem 0;
            border: 1px solid transparent;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        /* Stats Cards - Enhanced */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin: 1.5rem 0;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            text-align: center;
            transition: var(--transition);
            border-top: 4px solid var(--primary);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-card h3 {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .stat-card p {
            color: #6c757d;
            font-size: 1rem;
        }

        /* Form Actions */
        .form-actions {
            margin-top: 2rem;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        /* Radio Group */
        .radio-group {
            display: flex;
            gap: 1.5rem;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: normal;
            cursor: pointer;
        }

        /* Back Button */
        .back-btn {
            margin-bottom: 1.5rem;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                width: 240px;
            }

            .main-content {
                margin-left: 240px;
            }
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .stats {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <h2><i class="fas fa-shoe-prints"></i> Shoes Store</h2>
            <ul>
                <li><a href="?section=dashboard" class="<?php echo $section === 'dashboard' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i> Dashboard</a></li>

                <li><a href="?section=admin" class="<?php echo $section === 'admin' ? 'active' : ''; ?>">
                        <i class="fas fa-user-shield"></i> Admin Management</a></li>

                <li><a href="?section=category" class="<?php echo $section === 'category' ? 'active' : ''; ?>">
                        <i class="fas fa-list"></i> Categories</a></li>

                <li><a href="?section=products" class="<?php echo $section === 'products' ? 'active' : ''; ?>">
                        <i class="fas fa-box-open"></i> Products</a></li>

                <li><a href="?section=orders" class="<?php echo $section === 'orders' ? 'active' : ''; ?>">
                        <i class="fas fa-shopping-cart"></i> Orders</a></li>

                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>


        <!-- Main Content -->
        <main class="main-content">
            <div class="header">
                <h1><?php
                    switch ($section) {
                        case 'admin':
                            echo 'Admin Management';
                            break;
                        case 'category':
                            echo 'Category Management';
                            break;
                        case 'products':
                            echo 'Product Management';
                            break;
                        case 'orders':
                            echo 'Order Management';
                            break;
                        default:
                            echo 'Dashboard';
                            break;
                    }
                    ?></h1>
            </div>

            <div class="content">
                <?php if ($section === 'dashboard'): ?>
                    <!-- Dashboard Section -->
                    <div class="stats">
                        <div class="stat-card">
                            <?php
                            $sql = "SELECT COUNT(*) as count FROM tbl_category";
                            $res = mysqli_query($conn, $sql);
                            $count_categories = $res ? mysqli_fetch_assoc($res)['count'] : 0;
                            ?>
                            <h3><?php echo $count_categories; ?></h3>
                            <p>Categories</p>
                        </div>

                        <div class="stat-card">
                            <?php
                            $sql2 = "SELECT COUNT(*) as count FROM tbl_shoes";
                            $res2 = mysqli_query($conn, $sql2);
                            $count_products = $res2 ? mysqli_fetch_assoc($res2)['count'] : 0;
                            ?>
                            <h3><?php echo $count_products; ?></h3>
                            <p>Products</p>
                        </div>

                        <div class="stat-card">
                            <?php
                            $sql3 = "SELECT COUNT(*) as count FROM tbl_order";
                            $res3 = mysqli_query($conn, $sql3);
                            $count_orders = $res3 ? mysqli_fetch_assoc($res3)['count'] : 0;
                            ?>
                            <h3><?php echo $count_orders; ?></h3>
                            <p>Total Orders</p>
                        </div>

                        <div class="stat-card">
                            <?php
                            $sql4 = "SELECT SUM(total) AS Total FROM tbl_order WHERE status='Delivered'";
                            $res4 = mysqli_query($conn, $sql4);
                            $row4 = $res4 ? mysqli_fetch_assoc($res4) : null;
                            $total_revenue = $row4 ? ($row4['Total'] ?? 0) : 0;
                            ?>
                            <h3>Rp<?php echo number_format($total_revenue); ?></h3>
                            <p>Total Revenue</p>
                        </div>
                    </div>

                    <h3>Recent Orders</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM tbl_order ORDER BY id DESC LIMIT 5";
                            $res = mysqli_query($conn, $sql);
                            if ($res && mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_assoc($res)) {
                                    echo "<tr>";
                                    echo "<td>#" . $row['id'] . "</td>";
                                    echo "<td>" . htmlspecialchars($row['shoes']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                    echo "<td>Rp" . number_format($row['total']) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No orders found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                <?php elseif ($section === 'admin'): ?>
                    <!-- Admin Management Section -->
                    <?php if ($action === 'add'): ?>
                        <div class="back-btn">
                            <a href="?section=admin" class="btn">← Back to Admin List</a>
                        </div>
                        <h3>Add New Admin</h3>
                        <form method="POST" autocomplete="off">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="full_name"  autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" autocomplete="new-password" required>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="add_admin" class="btn">Add Admin</button>
                                <a href="?section=admin" class="btn btn-warning">Cancel</a>
                            </div>
                        </form>

                    <?php elseif ($action === 'update' && $id > 0): ?>
                        <?php
                        $sql = "SELECT * FROM tbl_admin WHERE id=$id";
                        $res = mysqli_query($conn, $sql);
                        if ($res == TRUE && mysqli_num_rows($res) == 1) {
                            $row = mysqli_fetch_assoc($res);
                            $full_name = $row['full_name'];
                            $username = $row['username'];
                        ?>
                            <div class="back-btn">
                                <a href="?section=admin" class="btn">← Back to Admin List</a>
                            </div>
                            <h3>Update Admin</h3>
                            <form method="POST">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                                </div>
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <div class="form-actions">
                                    <button type="submit" name="update_admin" class="btn">Update Admin</button>
                                    <a href="?section=admin" class="btn btn-warning">Cancel</a>
                                </div>
                            </form>
                        <?php } else { ?>
                            <p class="error">Admin not found.</p>
                            <a href="?section=admin" class="btn">← Back to Admin List</a>
                        <?php } ?>

                    <?php elseif ($action === 'change_password' && $id > 0): ?>
                        <div class="back-btn">
                            <a href="?section=admin" class="btn">← Back to Admin List</a>
                        </div>
                        <h3>Change Password</h3>
                        <form method="POST">
                            <div class="form-group">
                                <label>Current Password</label>
                                <input type="password" name="current_password" required>
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" required>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" required>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <div class="form-actions">
                                <button type="submit" name="change_password" class="btn">Change Password</button>
                                <a href="?section=admin" class="btn btn-warning">Cancel</a>
                            </div>
                        </form>

                    <?php else: ?>
                        <a href="?section=admin&action=add" class="btn">Add Admin</a>

                        <?php
                        // Display session messages
                        if (isset($_SESSION['add'])) {
                            echo $_SESSION['add'];
                            unset($_SESSION['add']);
                        }
                        if (isset($_SESSION['delete'])) {
                            echo $_SESSION['delete'];
                            unset($_SESSION['delete']);
                        }
                        if (isset($_SESSION['update'])) {
                            echo $_SESSION['update'];
                            unset($_SESSION['update']);
                        }
                        if (isset($_SESSION['user-not-found'])) {
                            echo $_SESSION['user-not-found'];
                            unset($_SESSION['user-not-found']);
                        }
                        if (isset($_SESSION['pwd-not-match'])) {
                            echo $_SESSION['pwd-not-match'];
                            unset($_SESSION['pwd-not-match']);
                        }
                        if (isset($_SESSION['change-pwd'])) {
                            echo $_SESSION['change-pwd'];
                            unset($_SESSION['change-pwd']);
                        }
                        ?>

                        <table>
                            <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>Full Name</th>
                                    <th>Username</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM tbl_admin";
                                $res = mysqli_query($conn, $sql);
                                if ($res == TRUE) {
                                    $count = mysqli_num_rows($res);
                                    $sn = 1;
                                    if ($count > 0) {
                                        while ($row = mysqli_fetch_assoc($res)) {
                                            $admin_id = $row['id'];
                                            $full_name = $row['full_name'];
                                            $username = $row['username'];
                                ?>
                                            <tr>
                                                <td><?php echo $sn++; ?></td>
                                                <td><?php echo htmlspecialchars($full_name); ?></td>
                                                <td><?php echo htmlspecialchars($username); ?></td>
                                                <td>
                                                    <a href="?section=admin&action=change_password&id=<?php echo $admin_id; ?>" class="btn">Change Password</a>
                                                    <a href="?section=admin&action=update&id=<?php echo $admin_id; ?>" class="btn btn-warning">Update</a>
                                                    <a href="?section=admin&action=delete&id=<?php echo $admin_id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                                </td>
                                            </tr>
                                <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No admins found</td></tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php endif; ?>

                <?php elseif ($section === 'category'): ?>
                    <!-- Category Management Section -->
                    <?php if ($action === 'add'): ?>
                        <div class="back-btn">
                            <a href="?section=category" class="btn">← Back to Category List</a>
                        </div>
                        <h3>Add New Category</h3>
                        <?php
                        if (isset($_SESSION['upload'])) {
                            echo $_SESSION['upload'];
                            unset($_SESSION['upload']);
                        }
                        ?>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" required>
                            </div>
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" name="image" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label>Featured</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="featured" value="Yes"> Yes</label>
                                    <label><input type="radio" name="featured" value="No" checked> No</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Active</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="active" value="Yes"> Yes</label>
                                    <label><input type="radio" name="active" value="No" checked> No</label>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="add_category" class="btn">Add Category</button>
                                <a href="?section=category" class="btn btn-warning">Cancel</a>
                            </div>
                        </form>

                    <?php elseif ($action === 'update' && $id > 0): ?>
                        <?php
                        $sql = "SELECT * FROM tbl_category WHERE id=$id";
                        $res = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($res) == 1) {
                            $row = mysqli_fetch_assoc($res);
                            $title = $row['title'];
                            $current_image = $row['image_name'];
                            $featured = $row['featured'];
                            $active = $row['active'];
                        ?>
                            <div class="back-btn">
                                <a href="?section=category" class="btn">← Back to Category List</a>
                            </div>
                            <h3>Update Category</h3>
                            <form method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
                                </div>
                                <?php if ($current_image != ""): ?>
                                    <div class="form-group">
                                        <label>Current Image</label>
                                        <img src="<?php echo SITEURL; ?>images/category/<?php echo $current_image; ?>" width="150px" style="border-radius: 5px;">
                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label>New Image</label>
                                    <input type="file" name="image" accept="image/*">
                                </div>
                                <div class="form-group">
                                    <label>Featured</label>
                                    <div class="radio-group">
                                        <label><input type="radio" name="featured" value="Yes" <?php if ($featured == "Yes") echo "checked"; ?>> Yes</label>
                                        <label><input type="radio" name="featured" value="No" <?php if ($featured == "No") echo "checked"; ?>> No</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Active</label>
                                    <div class="radio-group">
                                        <label><input type="radio" name="active" value="Yes" <?php if ($active == "Yes") echo "checked"; ?>> Yes</label>
                                        <label><input type="radio" name="active" value="No" <?php if ($active == "No") echo "checked"; ?>> No</label>
                                    </div>
                                </div>
                                <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <div class="form-actions">
                                    <button type="submit" name="update_category" class="btn">Update Category</button>
                                    <a href="?section=category" class="btn btn-warning">Cancel</a>
                                </div>
                            </form>
                        <?php } else { ?>
                            <p class="error">Category not found.</p>
                            <a href="?section=category" class="btn">← Back to Category List</a>
                        <?php } ?>

                    <?php else: ?>
                        <a href="?section=category&action=add" class="btn">Add Category</a>

                        <?php
                        // Display session messages
                        if (isset($_SESSION['add'])) {
                            echo $_SESSION['add'];
                            unset($_SESSION['add']);
                        }
                        if (isset($_SESSION['remove'])) {
                            echo $_SESSION['remove'];
                            unset($_SESSION['remove']);
                        }
                        if (isset($_SESSION['delete'])) {
                            echo $_SESSION['delete'];
                            unset($_SESSION['delete']);
                        }
                        if (isset($_SESSION['update'])) {
                            echo $_SESSION['update'];
                            unset($_SESSION['update']);
                        }
                        if (isset($_SESSION['upload'])) {
                            echo $_SESSION['upload'];
                            unset($_SESSION['upload']);
                        }
                        ?>

                        <table>
                            <thead>
                                <tr>
                                    <th>S.N.</th>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Featured</th>
                                    <th>Active</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM tbl_category";
                                $res = mysqli_query($conn, $sql);
                                $count = mysqli_num_rows($res);
                                $sn = 1;

                                if ($count > 0) {
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        $cat_id = $row['id'];
                                        $title = $row['title'];
                                        $image_name = $row['image_name'];
                                        $featured = $row['featured'];
                                        $active = $row['active'];
                                ?>
                                        <tr>
                                            <td><?php echo $sn++; ?></td>
                                            <td><?php echo htmlspecialchars($title); ?></td>
                                            <td>
                                                <?php if ($image_name != ""): ?>
                                                    <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>" width="60px" style="border-radius: 5px;">
                                                <?php else: ?>
                                                    No Image
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $featured; ?></td>
                                            <td><?php echo $active; ?></td>
                                            <td>
                                                <a href="?section=category&action=update&id=<?php echo $cat_id; ?>&image_name=<?php echo $image_name; ?>" class="btn btn-warning">Update</a>
                                                <a href="?section=category&action=delete&id=<?php echo $cat_id; ?>&image_name=<?php echo $image_name; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>No Category Added.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php endif; ?>

                <?php elseif ($section === 'products'): ?>
                    <!-- Products Management Section -->
                    <?php if ($action === 'add'): ?>
                        <div class="back-btn">
                            <a href="?section=products" class="btn">← Back to Product List</a>
                        </div>
                        <h3>Add New Product</h3>
                        <?php
                        if (isset($_SESSION['upload'])) {
                            echo $_SESSION['upload'];
                            unset($_SESSION['upload']);
                        }
                        ?>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" required>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" name="price" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select name="category" required>
                                    <?php
                                    $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                                    $res = mysqli_query($conn, $sql);
                                    if (mysqli_num_rows($res) > 0) {
                                        while ($row = mysqli_fetch_assoc($res)) {
                                            echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['title']) . "</option>";
                                        }
                                    } else {
                                        echo "<option value='0'>No Category Found</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" name="image" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label>Featured</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="featured" value="Yes"> Yes</label>
                                    <label><input type="radio" name="featured" value="No" checked> No</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Active</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="active" value="Yes"> Yes</label>
                                    <label><input type="radio" name="active" value="No" checked> No</label>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="add_shoes" class="btn">Add Product</button>
                                <a href="?section=products" class="btn btn-warning">Cancel</a>
                            </div>
                        </form>

                    <?php elseif ($action === 'update' && $id > 0): ?>
                        <?php
                        $sql2 = "SELECT * FROM tbl_shoes WHERE id=$id";
                        $res2 = mysqli_query($conn, $sql2);
                        if ($res2 && mysqli_num_rows($res2) > 0) {
                            $row2 = mysqli_fetch_assoc($res2);
                            $title = $row2['title'];
                            $description = $row2['description'];
                            $price = $row2['price'];
                            $current_image = $row2['image_name'];
                            $current_category = $row2['category_id'];
                            $featured = $row2['featured'];
                            $active = $row2['active'];
                        ?>
                            <div class="back-btn">
                                <a href="?section=products" class="btn">← Back to Product List</a>
                            </div>
                            <h3>Update Product</h3>
                            <form method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" rows="4"><?php echo htmlspecialchars($description); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="number" name="price" value="<?php echo $price; ?>" step="0.01" required>
                                </div>
                                <div class="form-group">
                                    <label>Category</label>
                                    <select name="category" required>
                                        <?php
                                        $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                                        $res = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($res) > 0) {
                                            while ($row = mysqli_fetch_assoc($res)) {
                                                $selected = ($current_category == $row['id']) ? 'selected' : '';
                                                echo "<option value='" . $row['id'] . "' $selected>" . htmlspecialchars($row['title']) . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <?php if ($current_image != ""): ?>
                                    <div class="form-group">
                                        <label>Current Image</label>
                                        <img src="<?php echo SITEURL; ?>images/shoes/<?php echo $current_image; ?>" width="150px" style="border-radius: 5px;">
                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label>New Image</label>
                                    <input type="file" name="image" accept="image/*">
                                </div>
                                <div class="form-group">
                                    <label>Featured</label>
                                    <div class="radio-group">
                                        <label><input type="radio" name="featured" value="Yes" <?php if ($featured == "Yes") echo "checked"; ?>> Yes</label>
                                        <label><input type="radio" name="featured" value="No" <?php if ($featured == "No") echo "checked"; ?>> No</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Active</label>
                                    <div class="radio-group">
                                        <label><input type="radio" name="active" value="Yes" <?php if ($active == "Yes") echo "checked"; ?>> Yes</label>
                                        <label><input type="radio" name="active" value="No" <?php if ($active == "No") echo "checked"; ?>> No</label>
                                    </div>
                                </div>
                                <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <div class="form-actions">
                                    <button type="submit" name="update_shoes" class="btn">Update Product</button>
                                    <a href="?section=products" class="btn btn-warning">Cancel</a>
                                </div>
                            </form>
                        <?php } else { ?>
                            <p class="error">Product not found.</p>
                            <a href="?section=products" class="btn">← Back to Product List</a>
                        <?php } ?>

                    <?php else: ?>
                        <a href="?section=products&action=add" class="btn">Add Product</a>

                        <?php
                        // Display session messages
                        if (isset($_SESSION['add'])) {
                            echo $_SESSION['add'];
                            unset($_SESSION['add']);
                        }
                        if (isset($_SESSION['upload'])) {
                            echo $_SESSION['upload'];
                            unset($_SESSION['upload']);
                        }
                        if (isset($_SESSION['delete'])) {
                            echo $_SESSION['delete'];
                            unset($_SESSION['delete']);
                        }
                        if (isset($_SESSION['update'])) {
                            echo $_SESSION['update'];
                            unset($_SESSION['update']);
                        }
                        ?>

                        <table>
                            <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Image</th>
                                    <th>Featured</th>
                                    <th>Active</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM tbl_shoes";
                                $res = mysqli_query($conn, $sql);
                                $count = mysqli_num_rows($res);
                                $sn = 1;

                                if ($count > 0) {
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        $prod_id = $row['id'];
                                        $title = $row['title'];
                                        $price = $row['price'];
                                        $image_name = $row['image_name'];
                                        $featured = $row['featured'];
                                        $active = $row['active'];
                                ?>
                                        <tr>
                                            <td><?php echo $sn++; ?></td>
                                            <td><?php echo htmlspecialchars($title); ?></td>
                                            <td>Rp <?php echo number_format($price, 0, ',', '.'); ?></td>
                                            <td>
                                                <?php if ($image_name != ""): ?>
                                                    <img src="<?php echo SITEURL; ?>images/shoes/<?php echo $image_name; ?>" width="60px" style="border-radius: 5px;">
                                                <?php else: ?>
                                                    No Image
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $featured; ?></td>
                                            <td><?php echo $active; ?></td>
                                            <td>
                                                <a href="?section=products&action=update&id=<?php echo $prod_id; ?>&image_name=<?php echo $image_name; ?>" class="btn btn-warning">Update</a>
                                                <a href="?section=products&action=delete&id=<?php echo $prod_id; ?>&image_name=<?php echo $image_name; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>No Products Added Yet.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php endif; ?>

                <?php elseif ($section === 'orders'): ?>
                    <!-- Orders Management Section -->
                    <?php if ($action === 'update' && $id > 0): ?>
                        <?php
                        $sql = "SELECT * FROM tbl_order WHERE id = $id";
                        $res = mysqli_query($conn, $sql);
                        if ($res && mysqli_num_rows($res) == 1) {
                            $row = mysqli_fetch_assoc($res);
                            $shoes = $row['shoes'];
                            $price = $row['price'];
                            $qty = $row['qty'];
                            $total = $row['total'];
                            $order_date = $row['order_date'];
                            $status = $row['status'];
                            $customer_name = $row['customer_name'];
                            $customer_contact = $row['customer_contact'];
                            $customer_email = $row['customer_email'];
                            $customer_address = $row['customer_address'];
                        ?>
                            <div class="back-btn">
                                <a href="?section=orders" class="btn">← Back to Order List</a>
                            </div>
                            <h3>Update Order</h3>
                            <form method="POST">
                                <div class="form-group">
                                    <label>Product Name</label>
                                    <input type="text" value="<?php echo htmlspecialchars($shoes); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="text" value="Rp<?php echo htmlspecialchars($price); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input type="number" name="qty" value="<?php echo htmlspecialchars($qty); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Size</label>
                                    <input type="text" name="size" value="<?php echo htmlspecialchars($row['size'] ?? ''); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" required>
                                        <option value="Ordered" <?php if ($status == "Ordered") echo "selected"; ?>>Ordered</option>
                                        <option value="On Delivery" <?php if ($status == "On Delivery") echo "selected"; ?>>On Delivery</option>
                                        <option value="Delivered" <?php if ($status == "Delivered") echo "selected"; ?>>Delivered</option>
                                        <option value="Cancelled" <?php if ($status == "Cancelled") echo "selected"; ?>>Cancelled</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Customer Name</label>
                                    <input type="text" name="customer_name" value="<?php echo htmlspecialchars($customer_name); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Customer Contact</label>
                                    <input type="text" name="customer_contact" value="<?php echo htmlspecialchars($customer_contact); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Customer Email</label>
                                    <input type="email" name="customer_email" value="<?php echo htmlspecialchars($customer_email); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Customer Address</label>
                                    <textarea name="customer_address" rows="4" required><?php echo htmlspecialchars($customer_address); ?></textarea>
                                </div>
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <input type="hidden" name="price" value="<?php echo $price; ?>">
                                <div class="form-actions">
                                    <button type="submit" name="update_order" class="btn">Update Order</button>
                                    <a href="?section=orders" class="btn btn-warning">Cancel</a>
                                </div>
                            </form>
                        <?php } else { ?>
                            <p class="error">Order not found.</p>
                            <a href="?section=orders" class="btn">← Back to Order List</a>
                        <?php } ?>

                    <?php else: ?>
                        <?php
                        // Display session messages
                        if (isset($_SESSION['update'])) {
                            echo $_SESSION['update'];
                            unset($_SESSION['update']);
                        }
                        if (isset($_SESSION['delete'])) {
                            echo $_SESSION['delete'];
                            unset($_SESSION['delete']);
                        }
                        ?>

                        <table>
                            <thead>
                                <tr>
                                    <th>S.N.</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty.</th>
                                    <th>Size</th>
                                    <th>Total</th>
                                    <th>Order Date</th>
                                    <th>Status</th>
                                    <th>Customer</th>
                                    <th>Contact</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM tbl_order ORDER BY id DESC";
                                $res = mysqli_query($conn, $sql);
                                $count = mysqli_num_rows($res);
                                $sn = 1;
                                if ($count > 0) {
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        $order_id = $row['id'];
                                        $shoes = $row['shoes'];
                                        $price = $row['price'];
                                        $qty = $row['qty'];
                                        $total = $row['total'];
                                        $order_date = $row['order_date'];
                                        $status = $row['status'];
                                        $customer_name = $row['customer_name'];
                                        $customer_contact = $row['customer_contact'];
                                        $customer_email = $row['customer_email'];
                                        $customer_address = $row['customer_address'];
                                ?>
                                        <tr>
                                            <td><?php echo $sn++; ?></td>
                                            <td><?php echo htmlspecialchars($shoes); ?></td>
                                            <td>Rp<?php echo number_format($price); ?></td>
                                            <td><?php echo $qty; ?></td>
                                            <td><?php echo htmlspecialchars($row['size'] ?? 'N/A'); ?></td>
                                            <td>Rp<?php echo number_format($total); ?></td>
                                            <td><?php echo $order_date; ?></td>
                                            <td><?php echo htmlspecialchars($status); ?></td>
                                            <td><?php echo htmlspecialchars($customer_name); ?></td>
                                            <td><?php echo htmlspecialchars($customer_contact); ?></td>
                                            <td><?php echo htmlspecialchars($customer_email); ?></td>
                                            <td>
                                                <a href="?section=orders&action=update&id=<?php echo $order_id; ?>" class="btn btn-warning">Update</a>
                                                <a href="?section=orders&action=delete&id=<?php echo $order_id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='11'>Orders not Available</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        // Simple JavaScript for basic functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide success/error messages after 5 seconds
            const messages = document.querySelectorAll('.success, .error');
            messages.forEach(function(message) {
                setTimeout(function() {
                    message.style.opacity = '0';
                    setTimeout(function() {
                        message.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
</body>

</html>
