<?php 
    require_once "../config/controllerUserData.php"; 
    require "../config/session.php";
?>

<?php
    $election_id = $_SESSION['election-id'];
    if (!isset($_SESSION['candidate-otp'])) {
        header('location: index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="../assets/css/owl.theme.default.css">
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.0.0" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Chettan+2&family=Montserrat+Alternates:wght@500&family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-notify@0.5.5/dist/simple-notify.min.css" />

    <title>Blockchain-Based EVS</title>
    <link rel="icon" href="../assets/images/logo4.png" type="image/icon type">

</head>
<body class="bg-light">
    <div>
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-6 thumbnail my-3">
                    <form action="" method="post" autocomplete="">
                        <div class="bg-white rounded shadow pb-4">
                            <h2 class="bg-primary text-white rounded-top p-3 mb-0">
                                <?php 
                                    $sql = "SELECT * FROM electiontable WHERE election_id = '$election_id'";
                                    $query = mysqli_query($con, $sql);
                                    if(mysqli_num_rows($query) > 0){
                                        $fetch = mysqli_fetch_assoc($query);
                                        $election_name = $fetch["election_name"];
                                        $election_id = $fetch["election_id"];

                                        echo "$election_name";
                                    }
                                ?>
                            </h2>    
                            <h6 class="text-black p-3">
                                Please enter the OTP code sent to your email.
                            </h6>  
                            <div class="px-3 row align-items-center">
                                <div class="col-lg-6">
                                    <div class="input-group input-group-outline">                 
                                        <label class="form-label">OTP Code</label>
                                        <input type="text" maxlength="6" id="regis_candidateOTP" name="regis-candidate-OTP" class="form-control" required/>
                                    </div>
                                </div>  
                                <div class="col-auto">
                                    <a id="resend_candidateOtp" class="ms-1 mt-1 link-primary" style="font-size: small;text-decoration: none;cursor:pointer;">Resend Code</a>  
                                </div>  
                            </div> 
                        </div>
                        <button type="submit" name="verify_candidateOTPBtn" class="btn btn-primary text-white btn-block my-4 px-4 form-control">VERIFY</button>
                    </form>
                </div>
                <p class="text-secondary text-center">This election is created by 
                    <?php 
                        $sql = "SELECT * FROM electiontable WHERE election_id = '$election_id'";
                        $query = mysqli_query($con, $sql);
                        if(mysqli_num_rows($query) > 0){
                            $fetch = mysqli_fetch_assoc($query);
                            $election_id = $fetch["election_id"];
                            $created_by = $fetch["created_by"];
                            echo "$created_by";
                        }
                    ?>
                </p>
                <p class="fw-semibold text-secondary fs-4 text-center">Blockchain-Based EVS</p>   
            </div>
        </div>   
    </div>

<?php require_once "../includes/footer.php"; ?>
<script src="../assets/js/material-dashboard.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>