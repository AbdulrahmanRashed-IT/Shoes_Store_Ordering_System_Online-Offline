<?php
    include('../config/constant.php');

    // Destroy session
    session_destroy();

    // Redirect to registration page (outside the admin folder)
    header('Location: ../register.php');
    exit();
?>
