<?php

    require "connection.php";

    if(isset($_SESSION['email'])){
        $email = $_SESSION['email'];
        $check_email = "SELECT * FROM usertable WHERE email = '$email'";
        $res = mysqli_query($con, $check_email);
        if(mysqli_num_rows($res) > 0){
            $fetch = mysqli_fetch_assoc($res);
            $status = $fetch['status'];
            $user_photo = $fetch['user_photo'];
            $user_id = $fetch['id'];
            $user_role = $fetch['role'];
        }
    }

?>