<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>
<?php   
    include '../config/connection.php'; 
    require_once "../config/controllerUserData.php"; 
    if(!isset($_SESSION['election_code'])){
        header('location: ../index.php');
        exit();
    }
    $election_code = $_SESSION['election_code'];
    $path = 'index.php?code=' . $_SESSION['election_code'];

    if(isset($_SESSION['voter'])){
        header('location: '.$path.'');
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

    <style>
        .bg-blue {
            background-color: #00008b;
            color: #fff;
        }
        input[type=radio]{
            transform:scale(1.5);
        }
        input[type=checkbox]{
            transform:scale(1.5);
        }
        textarea {
            background-color: #fff !important;
        }
        .border-primary {
            border-color: #00008b !important;
        }
        .btn:hover{
            color: #fff;
        }
        ::-webkit-scrollbar-track {
            border-radius: 10px;
            background: rgba( 255, 255, 255, 0.1 );
            backdrop-filter: blur( 10px );
            -webkit-backdrop-filter: blur( 10px );
            border-radius: 10px;
        }

        ::-webkit-scrollbar {
            width: 6px;
            background: rgba( 255, 255, 255, 0.1 );
        backdrop-filter: blur( 10px );
        -webkit-backdrop-filter: blur( 10px );
        border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            border-radius: 8px;
            box-shadow: inset 0 0 6px rgba(0,0,0,.2);
            background-color: rgba(0, 0, 139, 0.9);

        }
    </style>

</head>

<body class="bg-light">

    <div class="container-fluid">
        <div class="row d-flex justify-content-center align-items-center min-vh-100">
            <div class="col-lg-6 col-sm-10">
                <div class="row rounded shadow bg-white d-flex justify-content-center pb-3">
                    <h2 class="bg-blue rounded-top py-2 text-center">
                        <?php 
                            $sql = "SELECT * FROM electiontable WHERE election_code = '$election_code'";
                            $query = mysqli_query($con, $sql);
                            if(mysqli_num_rows($query) > 0){
                                $fetch = mysqli_fetch_assoc($query);
                                $election_name = $fetch["election_name"];
                                $_SESSION['election-id'] = $fetch["election_id"];

                                echo "$election_name";
                            }
                        ?>
                    </h2>
                    <div class="col-lg-6 py-3">
                        <h6 class="text-center mb-5 fs-6 text-black">Please enter your credentials you register in voters registration.</h6>
                        <form action="" method="post" autocomplete="">
                            <div class="input-group input-group-outline mb-4">
                                <label class="form-label" for="voter_email">Email Address</label>
                                <input 
                                    type="email" 
                                    pattern="[a-zA-Z0-9._%+-]+@plpasig\.edu\.ph"
                                    id="voter_email" 
                                    name="voter_email" 
                                    class="form-control" 
                                    required/>                  
                            </div>
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label" for="voter_password">Password</label>
                                <input type="password" id="voter_password" name="voter_password" class="form-control" required/>                   
                            </div>
                            
                            <button type="submit" name="login_voter" class="btn btn-primary text-white form-control my-3">LOGIN</button>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

<?php require_once "../includes/footer.php"; ?>
<script src="../assets/js/material-dashboard.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>