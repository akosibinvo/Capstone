<?php 
    require_once "../config/controllerUserData.php"; 
    require "../config/session.php";
?>

<?php
    $code = '';
    $userRole = "";
    if(isset($user_role)){
        $userRole = $user_role;
    } else {
        $userRole = "voter";
    }

    if (isset($_GET['code'])) {
        // Get the value of the "code" parameter
        $code = $_GET['code'];
        $sql = "SELECT voter_registration.*, electiontable.election_id, electiontable.timezone, electiontable.status
                FROM voter_registration 
                JOIN electiontable ON voter_registration.election_code = electiontable.election_code
                WHERE voter_registration.election_code = '$code'";
        $res = mysqli_query($con, $sql);

        if(mysqli_num_rows($res) > 0){
            $fetch = mysqli_fetch_assoc($res);
            $_SESSION['election-id'] = $fetch["election_id"];
            $voterRegis_Sdate = $fetch["voter_regis_startdate"];
            $voterRegis_Edate = $fetch["voter_regis_enddate"];
            $timezone = $fetch["timezone"];
            $electionStatus = $fetch["status"];

            date_default_timezone_set($timezone);
            $currentDate = new DateTime();
            $currentDateFormat = $currentDate->format('Y-m-d H:i:s');

            if ($userRole === "voter") {
                // Redirect voters outside the registration period to the access denied page
                if ($currentDateFormat < $voterRegis_Sdate) {
                    $_SESSION['error'] = "Voter registration is not yet started.";
                    header('location: ../index.php');
                    exit();
                } else if ($currentDateFormat > $voterRegis_Edate) {
                    $_SESSION['error'] = "Voter registration has been ended.";
                    header('location: ../index.php');
                    exit();
                } else if ($electionStatus !== 'building') {
                    $_SESSION['error'] = "Voter registration has been ended.";
                    header('location: ../index.php');
                    exit();
                }
            } else if ($userRole === "election_creator" && !isset($_SESSION['email']) && !isset($_SESSION['password'])) {
                header('location: ../index.php');
                exit();
            } 
        } else {
            $_SESSION['error'] = "Election does not exist.";
            header('location: ../index.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid election link.";
        header('location: ../index.php');
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
                <div class="col-lg-7 thumbnail my-3">
                    <div class="bg-white rounded shadow">
                        <h2 class="bg-primary text-white rounded-top p-3 mb-0">
                            <?php 
                                 $sql = "SELECT * FROM electiontable WHERE election_code = '$code'";
                                 $query = mysqli_query($con, $sql);
                                 if(mysqli_num_rows($query) > 0){
                                     $fetch = mysqli_fetch_assoc($query);
                                     $election_name = $fetch["election_name"];
                                     $election_id = $fetch["election_id"];

                                     echo "$election_name";
                                 }
                            ?>
                        </h2>    
                        <h6 class="mb-3 text-black p-3">
                            Exciting times are ahead! The elections are just around the corner, and we want YOU to be a part of this important process.
                            To participate in the upcoming elections, we invite you to register as a voter.
                        </h6>  
                    </div>
                    <div class="bg-white rounded shadow">
                        <h5 class="bg-primary text-white rounded-top px-3 py-2 mb-0">Registration Details</h5>    
                        <p class="d-flex justify-content-between p-3 text-black fw-normal">
                            <span>
                                Start Date:
                                <?php 
                                    $sql = "SELECT * FROM voter_registration WHERE election_code = '$code'";
                                    $query = mysqli_query($con, $sql);
                                    if(mysqli_num_rows($query) > 0){
                                        $fetch = mysqli_fetch_assoc($query);
                                        $timezone = $fetch["voter_regis_timezone"];
                                        $new_startdate_timezone = new DateTime($fetch["voter_regis_startdate"], new DateTimeZone($timezone));
                                        $new_date_start = $new_startdate_timezone->format("Y-m-d H:i:s");
                                        $startdate = DateTime::createFromFormat("Y-m-d H:i:s", $new_date_start)->format('M j, Y h:i: A');

                                        echo "$startdate";
                                    }
                                ?>
                            </span>
                            <span>
                                End Date: 
                                <?php 
                                    $sql = "SELECT * FROM voter_registration WHERE election_code = '$code'";
                                    $query = mysqli_query($con, $sql);
                                    if(mysqli_num_rows($query) > 0){
                                        $fetch = mysqli_fetch_assoc($query);
                                        $timezone = $fetch["voter_regis_timezone"];
                                        $new_enddate_timezone = new DateTime($fetch["voter_regis_enddate"], new DateTimeZone($timezone));
                                        $new_date_end = $new_enddate_timezone->format("Y-m-d H:i:s");
                                        $enddate = DateTime::createFromFormat("Y-m-d H:i:s", $new_date_end)->format('M j, Y h:i: A');

                                        echo "$enddate";
                                    }
                                ?>
                            </span>
                        </p> 
                    </div>
                    <form action="" method="post" autocomplete="" enctype="multipart/form-data">
                        <div class="bg-white rounded shadow mb-3">
                            <h6 class="bg-primary text-white rounded-top px-3 py-2 mb-0">Complete Name</h6>  
                            <div class=" p-3 row align-items-center">
                                <p class="text-black">Please enter your complete name in the given textfield.(SURNAME, FIRST NAME, MI)</p>
                                <div class="col-lg-5">
                                    <div class="input-group input-group-outline">                 
                                        <label class="form-label">Full Name</label>
                                        <input 
                                            type="text" 
                                            id="regis_voterName" 
                                            name="regis-voter-name" 
                                            oninput="this.value = this.value.toUpperCase()"
                                            class="form-control" 
                                            required/>
                                    </div>
                                </div>  
                                <div class="col-auto">
                                    <span id="regis_voterNameHelpInline" class="form-text">
                                    (e.g., MIRANDA, JUAN A.)
                                    </span>
                                </div>  
                            </div>                      
                        </div>
                        <div class="bg-white rounded shadow mb-3">
                            <h6 class="bg-primary text-white rounded-top px-3 py-2 mb-0">Section</h6>  
                            <div class=" p-3 row align-items-center">
                                <p class="text-black">Please enter your section in the given textfield.</p>
                                <div class="col-lg-5">
                                    <div class="input-group input-group-outline">                 
                                        <label class="form-label">Year and Section</label>
                                        <input 
                                            type="text" 
                                            pattern="[A-Z]+-\d+[A-Z]+" 
                                            oninput="this.value = this.value.toUpperCase()"
                                            id="regis_voterSection" 
                                            name="regis-voter-section" 
                                            class="form-control" 
                                            required/>
                                    </div>
                                </div>  
                                <div class="col-auto">
                                    <span id="regis_voterNameHelpInline" class="form-text">
                                    (e.g., BSIT-4C)
                                    </span>
                                </div>  
                            </div>                      
                        </div>
                        <div class="bg-white rounded shadow mb-3">
                            <h6 class="bg-primary text-white rounded-top px-3 py-2 mb-0">Email Address</h6>  
                            <div class=" p-3 row align-items-center">
                                <p class="text-black">Please enter your PLP email address in the given textfield.</p>
                                <div class="col-lg-6">
                                    <div class="input-group input-group-outline">
                                        <label class="form-label" for="form4Example2">Email address</label>
                                        <input 
                                            type="email" 
                                            pattern="[a-zA-Z0-9._%+-]+@plpasig\.edu\.ph"
                                            id="regis_voterEmail" 
                                            name="regis-voter-email" 
                                            class="form-control" 
                                            required/>                  
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <span id="regis_voterEmailHelpInline" class="form-text">
                                    (e.g., miranda_juan@plpasig.edu.ph)
                                    </span>
                                </div> 
                            </div>
                        </div>
                        <div class="bg-white rounded shadow mb-3">
                            <h6 class="bg-primary text-white rounded-top px-3 py-2 mb-0">Voter Password</h6>  
                            <div class=" p-3 row align-items-start">
                                <p class="text-black">Please enter your password. This password will be used to access the ballot.</p>
                                <div class="col-lg-6">
                                    <div class="input-group input-group-outline">
                                        <label for="regis_voter_password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="regis_voter_password" name="regis-voter-password" required>                 
                                    </div>  
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-group input-group-outline">
                                        <label for="confirm_voter_password" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirm_voter_password" name="confirm-voter-password" required>                 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded shadow mb-3">
                            <h6 class="bg-primary text-white rounded-top px-3 py-2 mb-0">ID Number</h6>  
                            <div class=" p-3 row align-items-center">
                            <p class="text-black">Please enter your ID number in the given textfield.</p>
                                <div class="col-lg-4">
                                    <div class="input-group input-group-outline">
                                        <label class="form-label" for="form4Example2">ID Number</label>
                                        <input 
                                            type="text" 
                                            id="regis_voterIdNumber" 
                                            onfocus="focused(this)" 
                                            onfocusout="defocused(this)" 
                                            name="regis-voter-idNumber" 
                                            class="form-control" 
                                            pattern="\d{2}-\d{5}"
                                            required
                                        />                  
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <span id="regis_voterIdNumberHelpInline" class="form-text">
                                    (e.g., 20-00360)
                                    </span>
                                 </div> 
                            </div>                           
                        </div>
                        <div class="bg-white rounded shadow mb-3">
                            <h6 class="bg-primary text-white rounded-top px-3 py-2 mb-0">Upload Your ID</h6> 
                            <div class="row p-3">
                                <p class="text-black">Please upload the front and back of your ID.</p>
                                <div class="col">
                                    <label for="formFile" class="form-label text-black">Front Side</label>
                                    <input 
                                        onchange="previewVoterIDFront(this.files[0], event)" 
                                        class="form-control" 
                                        type="file" 
                                        accept="image/*"
                                        id="regis_voterImg_Front" 
                                        name="regis-voter-image-front" 
                                        required
                                    >
                                    <div class="bg-light rounded mt-3 d-none image_containerFront">
                                        <div class="row p-0 m-0 d-flex">
                                            <div class="col-3 p-2">
                                                <img class="img-fluid rounded">
                                            </div>
                                            <div class="col-auto p-2 flex-grow-1">
                                                <p class="text-black fw-semibold">me.png</p>
                                            </div>
                                            <div class="col-auto p-2">
                                                 <button onclick="resetFileInputFront_Voter()" type="button" class="btn-close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="formFile" class="form-label text-black">Back Side</label>
                                    <input 
                                        onchange="previewVoterIDBack(this.files[0], event)" 
                                        class="form-control" 
                                        type="file" 
                                        accept="image/*"
                                        id="regis_voterImg_Back" 
                                        name="regis-voter-image-back" 
                                        required
                                    >
                                    <div class="bg-light rounded mt-3 d-none image_containerBack">
                                        <div class="row p-0 m-0 d-flex">
                                            <div class="col-3 p-2">
                                                <img class="img-fluid rounded">
                                            </div>
                                            <div class="col-auto p-2 flex-grow-1">
                                                <p class="text-black fw-semibold">me.png</p>
                                            </div>
                                            <div class="col-auto p-2">
                                                 <button onclick="resetFileInputBack_Voter()" type="button" class="btn-close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <button 
                            type="submit" 
                            name="register_voterBtn" 
                            class="btn btn-primary text-white btn-block mb-4 px-4 form-control"
                            <?php //echo ($userRole === "election_creator") ? 'disabled' : ''; ?>
                        >REGISTER</button>
                    </form>
                </div>
                <p class="text-secondary text-center">This election is created by 
                <?php 
                    $sql = "SELECT * FROM electiontable WHERE election_code = '$code'";
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