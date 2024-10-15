<?php
    include_once 'config.php';

    // Create connection
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Check connection
    if (!$con) {
        // Log the error instead of displaying it directly
        error_log("Connection failed: " . mysqli_connect_error());
        die("Oops! Something went wrong. Please try again later.");
    }
?>