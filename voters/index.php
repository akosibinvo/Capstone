<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>
<?php   
    include "../config/connection.php"; 
    require_once "../config/controllerUserData.php"; 
    require "../config/session.php";
    include "../election-panel/includes/slugify.php";
?>

<?php
    $code = '';
    $userRole = "";
    if(isset($user_role)){
        $userRole = $user_role;

    } else {
        $userRole = "voter";
    }

    if(isset($_GET['code'])){
        $election_code = $_GET['code'];
        $_SESSION['election_code'] = $election_code;
        
        $sql = "SELECT * FROM electiontable WHERE election_code = '$election_code'";
        $query = mysqli_query($con, $sql);
        if(mysqli_num_rows($query) > 0){
            $fetch = mysqli_fetch_assoc($query);
            $_SESSION['election-id'] = $fetch["election_id"];
            $election_id = $fetch["election_id"];
            $Sdate = $fetch["start_date"];
            $Edate = $fetch["end_date"];
            $status = $fetch["status"];
            $timezone = $fetch["timezone"];

            date_default_timezone_set($timezone);
            $currentDate = new DateTime();
            $currentDateFormat = $currentDate->format('Y-m-d H:i:s');

            if ($userRole === "voter") {
                if ($status === "building" || $status === "scheduled") {
                    if ($currentDateFormat < $Sdate || $status === "scheduled") {
                        $_SESSION['error'] = "Election is not yet started.";
                    } else if ($status === "building") {
                        $_SESSION['error'] = "Election is currently building.";
                    }
                    header('location: ../index.php');
                    exit();
                } else if (!isset($_SESSION['voter'])) {
                    header('location: login.php');
                    exit();
                } else {
                    $voter_id = $_SESSION['voter'];
                }

            } else if ($userRole === "election_creator") {
                if (!isset($_SESSION['email']) && !isset($_SESSION['password'])) {
                    header('location: ../index.php');
                    exit();
                } else {
                    $voter_id = 0;
                }
            } else {
                header('location: ../index.php');
                exit();
            }
        }
    } else {
        $_SESSION['error'] = "Invalid election link.";
        header('location: ../index.php');
        exit();
    }

    $path = 'index.php?code=' . $_SESSION['election_code'];

    $queryGetData = "SELECT
              positions.position_id,
              CASE
                  WHEN elections.status = 'completed' THEN candidates.name
                  WHEN elections.status = 'running' THEN '?'
                  ELSE NULL -- Handle other cases as needed
              END AS candidate_name,
              COUNT(votes.candidate_id) AS vote_count
            FROM
              candidates_table AS candidates
            JOIN
              votestable AS votes ON candidates.candidate_id = votes.candidate_id
            JOIN
              positionstable AS positions ON votes.position_id = positions.position_id
            JOIN
              electiontable AS elections ON positions.election_id = elections.election_id
            WHERE
              elections.status IN ('completed', 'running')
            GROUP BY
              positions.position_id,
              elections.status,
              candidates.name
            ORDER BY
              positions.priority ASC;
        ";

        $result = mysqli_query($con, $queryGetData);

        // Fetch the results and store them in an associative array
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        // Encode the array into JSON for use in Chart.js
        $jsonData = json_encode($data);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Chettan+2&family=Montserrat+Alternates:wght@500&family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-notify@0.5.5/dist/simple-notify.min.css" />
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.0.0" rel="stylesheet" />
    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <!-- html2canvas -->
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

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

        #platform-content-ballot {
            min-height: 500px;
        }
        #platform-content-ballot h1, h2, #platform-content-ballot h3, h4, h5, h6, p, ul, ol {
            color: #000 !important;
        }
    </style>

</head>

<body class="bg-light">

    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-8">
                <form action="<?= $path ?>" method="post">
                    <div class="row mt-3 rounded shadow-sm bg-white">                   
                        <h1 class="bg-blue rounded-top py-2">
                            <?php 
                                 $sql = "SELECT election_name FROM electiontable WHERE election_code = '$election_code'";
                                 $query = mysqli_query($con, $sql);
                                 if(mysqli_num_rows($query) > 0){
                                     $fetch = mysqli_fetch_assoc($query);
                                     $election_name = $fetch["election_name"];

                                     echo "$election_name";
                                 }
                            ?>
                        </h1>
                        <?php
                            $sql = "
                                SELECT
                                    CASE
                                        WHEN EXISTS (SELECT 1 FROM votestable WHERE voter_id = '$voter_id') AND
                                             EXISTS (SELECT 1 FROM sentiment_tb WHERE voter_id = '$voter_id') THEN 'both'
                                        WHEN EXISTS (SELECT 1 FROM votestable WHERE voter_id = '$voter_id') THEN 'ballot'
                                        WHEN EXISTS (SELECT 1 FROM sentiment_tb WHERE voter_id = '$voter_id') THEN 'sentiment'
                                        ELSE 'Notvoted'
                                    END AS voting_status;
                            ";
                            $vquery = $con->query($sql);
                            $row = $vquery->fetch_assoc();
                            if($row['voting_status'] !== 'Notvoted' || $status === "completed"){
                            ?>
                                <p class="mt-2">
                                    <?php
                                        if(isset($_SESSION["voted"])) {
                                            echo "Successfully voted. We will notify your email when the election ends.";
                                            unset($_SESSION['voted']);
                                        } else if ($status === "completed") {
                                            echo "The election has ended. Please click the view results button to view results.";
                                        } else {
                                            echo "You already voted in this election. We will notify your email when the election ends.";
                                        }
                                    ?>                                   
                                 </p>

                                <button type="button" id="view_ballot" data-voter-id="<?php echo $voter_id; ?>" class="btn btn-primary w-auto ms-3 mb-3" <?php echo ($row['voting_status'] === 'ballot' || $row['voting_status'] === 'both') ? '' : 'style="display:none;"'; ?>>VIEW BALLOT</button>
                                <button data-bs-toggle="modal" data-bs-target="#results_modal" type="button" id="view_result" class="btn btn-primary w-auto ms-3 mb-3" <?php echo ($status === "completed" || $status === "running") ? '' : 'style="display:none;"'; ?>>VIEW RESULTS</button>
                                <button data-bs-toggle="modal" data-bs-target="#feedback_modal" type="button" data-voter-id="<?php echo $voter_id; ?>" class="btn btn-primary w-auto ms-3 mb-3" <?php echo ($row['voting_status'] === 'sentiment' || $row['voting_status'] === 'both') ? 'style="display:none;"' : ''; ?>>GIVE FEEDBACK</button>
                                <button type="button" id="voter_signout" class="btn btn-primary w-auto ms-3 mb-3 text-uppercase">Sign out</button>
                            <?php
                            }else{
                        ?>
                        <p>Voter Email: 
                            <?php      
                                $sql = "SELECT email FROM voters_table WHERE voter_id = '$voter_id' AND election_id = '$election_id'";
                                $query = mysqli_query($con, $sql);
                                if(mysqli_num_rows($query) > 0){
                                    $fetch = mysqli_fetch_assoc($query);
                                    $voter_email = $fetch["email"];
                                    echo "$voter_email";
                                }                         
                            ?>
                        </p>
                        <p class="d-flex justify-content-between">
                            <span>Start Date: 
                                <?php 
                                    $sql = "SELECT * FROM electiontable WHERE election_code = '$election_code'";
                                    $query = mysqli_query($con, $sql);
                                    if(mysqli_num_rows($query) > 0){
                                        $fetch = mysqli_fetch_assoc($query);
                                        $timezone = $fetch["timezone"];
                                        $new_startdate_timezone = new DateTime($fetch["start_date"], new DateTimeZone($timezone));
                                        $new_date_start = $new_startdate_timezone->format("Y-m-d H:i:s");
                                        $startdate = DateTime::createFromFormat("Y-m-d H:i:s", $new_date_start)->format('M j, Y h:i: A');

                                        echo "$startdate";
                                    }
                                ?>
                            </span>
                            <span>End Date: 
                                <?php 
                                    $sql = "SELECT * FROM electiontable WHERE election_code = '$election_code'";
                                    $query = mysqli_query($con, $sql);
                                    if(mysqli_num_rows($query) > 0){
                                        $fetch = mysqli_fetch_assoc($query);
                                        $timezone = $fetch["timezone"];
                                        $new_enddate_timezone = new DateTime($fetch["end_date"], new DateTimeZone($timezone));
                                        $new_date_end = $new_enddate_timezone->format("Y-m-d H:i:s");
                                        $enddate = DateTime::createFromFormat("Y-m-d H:i:s", $new_date_end)->format('M j, Y h:i: A');

                                        echo "$enddate";
                                    }
                                ?>
                            </span>
                        </p>
                    </div> 
                    <?php
                        $get_position = "SELECT * FROM positionstable WHERE election_id = '$election_id' ORDER BY priority ASC";
                        $query = mysqli_query($con, $get_position);
                        if(mysqli_num_rows($query) > 0){
                            while($row = $query->fetch_assoc()){
                                $position_id = $row['position_id'];
                                $sql = "SELECT * FROM candidates_table WHERE position = '$position_id' AND election_id = '$election_id' AND isData_verified = 1";
								$cquery = $con->query($sql);
                                echo '
                                    <div class="row mt-3 rounded shadow-sm bg-white pb-3">
                                        <h3 class="bg-blue rounded-top py-2">'.$row["position_desc"].'</h3>
                                        <h6 class="ms-1 w-auto">Instruction</h6>
                                        <div class="col-12 px-3">
                                            <p class="border border-1 py-2 px-3  border-secondary rounded">Please select <span class="bg-blue rounded text-xs px-2 py-1">'.$row["maximum_vote"].'</span> candidate from the list below.</p>
                                        </div>
                                ';                                        
                                        if(mysqli_num_rows($cquery) > 0){
                                            while($crow = $cquery->fetch_assoc()){
                                                $input = ($row["maximum_vote"] > 1) ? '<input class="form-check-input" type="checkbox" name="'.$row["position_id"]."[]".'" id="'.$crow["candidate_id"].'" value="'.$crow['candidate_id'].'">' : '<input class="form-check-input" type="radio" name="'.$row["position_id"].'" id="'.$crow["candidate_id"].'" value="'.$crow['candidate_id'].'">';
                                                echo '
                                                    <div class="col-12 mb-3">
                                                        <div class="px-4 mx-1 border border-1 rounded d-flex justify-content-between align-items-center">
                                                            <div class="form-check my-4">
                                                                '.$input.'
                                                                <label class="form-check-label text-black ms-2 fs-6" for="'.$crow["candidate_id"].'">'.$crow["name"].'</label>
                                                            </div>
                                                            <button data-candidate-id="'.$crow["candidate_id"].'" id="candidate-info" type="button" class="my-4 btn btn-primary px-3"><i class="fa-solid fa-info"></i></button>
                                                        </div>
                                                    </div>
                                                ';
                                            }
                                            // Add the "abstain" option
                                            $abstainInput = ($row["maximum_vote"] > 1) ? '<input class="form-check-input" type="checkbox" name="'.$row["position_id"]."[]".'" id="abstain_' . $row["position_id"] . '" value="0">' : '<input class="form-check-input abstain-radio" type="radio" name="' . $row["position_id"] . '" id="abstain_' . $row["position_id"] . '" value="0">';
                                            
                                            echo '
                                                <div class="col-12">
                                                    <div class="px-4 mx-1 border border-1 rounded d-flex justify-content-between align-items-center">
                                                        <div class="form-check my-4">
                                                            '.$abstainInput.'
                                                            <label class="form-check-label ms-2 fs-6 text-black" for="abstain_'.$row["position_id"].'">ABSTAINED</label>
                                                        </div>
                                                        <button id="abstained-info" type="button" class="my-4 btn btn-primary px-3"><i class="fa-solid fa-info"></i></button>
                                                    </div>
                                                </div>
                                            ';
                                        }   

                                echo '
                                        
                                    </div>
                                ';
                            }
                        }
                    ?>     
                    <?php 
                        if ($userRole === "voter") {
                            echo '
                                <div class="row mt-3 rounded shadow-sm bg-white p-3">
                                    <input type="submit" name="submit-ballot" value="SUBMIT BALLOT" class="form-control btn btn-primary py-2">
                                </div>
                            ';
                        }
                    ?>          
                    <?php }?>
                </form>           
            </div>
            <p class="text-secondary text-center mt-3">This election is created by 
                <?php 
                    $sql = "SELECT * FROM electiontable WHERE election_code = '$election_code'";
                    $query = mysqli_query($con, $sql);
                    if(mysqli_num_rows($query) > 0){
                        $fetch = mysqli_fetch_assoc($query);
                        $election_id = $fetch["election_id"];
                        $created_by = $fetch["created_by"];
                        echo "$created_by";
                    }
                ?>
            </p> 
            <p class="fw-semibold text-secondary fs-4 text-center pb-5">Blockchain-Based EVS</p>  
        <!-- Platform Modal -->   
        </div>
        <div id="view-platform-ballot" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                <div class="modal-body position-relative">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 2;"></button>
                    <div class="container-fluid">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-5 position-relative pt-2">
                        <div class="row justify-content-center">
                            <div class="col-9">
                            <img id="platform-img-ballot" src="" class="img-fluid rounded-circle border border-3 p-2 border-primary" alt="">
                            </div>
                        </div>
                        <div class="row text-center pt-2">
                            <h1 class="fs-5 mb-0 text-black" id="platform-name-ballot"></h1>
                            <p class="fs-6" id="platform-position-ballot"></p>
                        </div>
                        </div>
                        <div class="col-lg-7 position-relative">
                        <h1 class="fs-3 text-black">Platforms:</h1>
                        <div class="border border-3 bg-light border-primary px-3 rounded " id="platform-content-ballot"></div>
                        </div>            
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <!-- Ballot Modal --> 
        <div id="view-ballot" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 d-flex pb-0">
                        <button type="button" class="btn-close align-self-start" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row d-flex justify-content-center">
                                <div class="col-lg-12 position-relative">
                                    <form>
                                        <div class="row justify-content-center">
                                            <div class="row p-0 rounded shadow-sm bg-white">                   
                                                <h3 class="bg-blue rounded-top py-2">
                                                    <?php 
                                                        $sql = "SELECT * FROM electiontable WHERE election_code = '$election_code'";
                                                        $query = mysqli_query($con, $sql);
                                                        if(mysqli_num_rows($query) > 0){
                                                            $fetch = mysqli_fetch_assoc($query);
                                                            $election_name = $fetch["election_name"];

                                                            echo "$election_name";
                                                        }
                                                    ?>
                                                </h3>
                                                <p>Voter Email: 
                                                    <?php      
                                                        $sql = "SELECT email FROM voters_table WHERE voter_id = '$voter_id' AND election_id = '$election_id'";
                                                        $query = mysqli_query($con, $sql);
                                                        if(mysqli_num_rows($query) > 0){
                                                            $fetch = mysqli_fetch_assoc($query);
                                                            $voter_email = $fetch["email"];
                                                            echo "$voter_email";
                                                        }                         
                                                    ?>
                                                </p>
                                                <p class="d-flex justify-content-between">
                                                    <span>Start Date: 
                                                        <?php 
                                                            $sql = "SELECT * FROM electiontable WHERE election_code = '$election_code'";
                                                            $query = mysqli_query($con, $sql);
                                                            if(mysqli_num_rows($query) > 0){
                                                                $fetch = mysqli_fetch_assoc($query);
                                                                $timezone = $fetch["timezone"];
                                                                $new_startdate_timezone = new DateTime($fetch["start_date"], new DateTimeZone($timezone));
                                                                $new_date_start = $new_startdate_timezone->format("Y-m-d H:i:s");
                                                                $startdate = DateTime::createFromFormat("Y-m-d H:i:s", $new_date_start)->format('M j, Y h:i: A');

                                                                echo "$startdate";
                                                            }
                                                        ?>
                                                    </span>
                                                    <span>End Date: 
                                                        <?php 
                                                            $sql = "SELECT * FROM electiontable WHERE election_code = '$election_code'";
                                                            $query = mysqli_query($con, $sql);
                                                            if(mysqli_num_rows($query) > 0){
                                                                $fetch = mysqli_fetch_assoc($query);
                                                                $timezone = $fetch["timezone"];
                                                                $new_enddate_timezone = new DateTime($fetch["end_date"], new DateTimeZone($timezone));
                                                                $new_date_end = $new_enddate_timezone->format("Y-m-d H:i:s");
                                                                $enddate = DateTime::createFromFormat("Y-m-d H:i:s", $new_date_end)->format('M j, Y h:i: A');

                                                                echo "$enddate";
                                                            }
                                                        ?>
                                                    </span>
                                                </p>
                                            </div>
                                            <?php
                                                $get_position = "SELECT * FROM positionstable WHERE election_id = '$election_id' ORDER BY priority ASC";
                                                $query = mysqli_query($con, $get_position);
                                                if(mysqli_num_rows($query) > 0){
                                                    while($row = $query->fetch_assoc()){
                                                        $position_id = $row['position_id'];
                                                        $sql = "SELECT * FROM candidates_table WHERE position = '$position_id' AND election_id = '$election_id' AND isData_verified = 1";
                                                        $cquery = $con->query($sql);

                                                        echo '
                                                            <div class="row mt-3 rounded shadow-sm bg-white pb-3 px-0">
                                                                <h3 class="bg-blue rounded-top py-2">'.$row["position_desc"].'</h3>
                                                                <h6 class="ms-1 w-auto">Instruction</h6>
                                                                <div class="col-12 px-3">
                                                                    <p class="border border-1 py-2 px-3  border-secondary rounded">Please select <span class="bg-blue rounded text-xs px-2 py-1">'.$row["maximum_vote"].'</span> candidate from the list below.</p>
                                                                </div>
                                                        ';                                   
                                                                if(mysqli_num_rows($cquery) > 0){
                                                                    while($crow = $cquery->fetch_assoc()){
                                                                        $candidate_id = $crow["candidate_id"];

                                                                        // Query to check if the candidate is already selected in the votestable
                                                                        $vote_query = "SELECT * FROM votestable WHERE candidate_id = '$candidate_id' AND election_id = '$election_id' AND voter_id = '$voter_id'";
                                                                        $vote_result = $con->query($vote_query);

                                                                        // Check if the candidate is already selected
                                                                        $isChecked = ($vote_result->num_rows > 0) ? 'checked' : '';
                                                                        // Determine whether to disable the input based on the checkbox or radio button
                                                                        $isDisabled = empty($isChecked) ? 'disabled' : '';

                                                                        // Add the class based on the conditions
                                                                        $divClass = ($isChecked ? ' border-3 border-primary' : 'border-1');

                                                                        $input = ($row["maximum_vote"] > 1) ? 
                                                                            '<input class="form-check-input" type="checkbox" 
                                                                                name="'.$row["position_id"]."[]".'" 
                                                                                id="'.$crow["candidate_id"].'" 
                                                                                value="'.$crow['candidate_id'].'" '.$isChecked.' '.$isDisabled.' 
                                                                                onclick="return false">' : 
                                                                            '<input class="form-check-input" type="radio" 
                                                                                name="'.$row["position_id"].'" 
                                                                                id="'.$crow["candidate_id"].'" 
                                                                                value="'.$crow['candidate_id'].'" '.$isChecked.' '.$isDisabled.'>';
                                                                        echo '
                                                                            <div class="col-12 mb-3">
                                                                                <div class="px-4 py-4 mx-1 border '.$divClass.' rounded d-flex justify-content-between align-items-center">
                                                                                    <div class="form-check">
                                                                                        '.$input.'
                                                                                        <label class="form-check-label text-black ms-2 fs-6" for="'.$crow["candidate_id"].'">'.$crow["name"].'</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        ';
                                                                    }
                                                                    // Query to check if the candidate is already selected in the votestable
                                                                    $vote_query = "SELECT * FROM votestable WHERE position_id = '$position_id' AND candidate_id = 0 AND election_id = '$election_id' AND voter_id = '$voter_id'";
                                                                    $vote_result = $con->query($vote_query);

                                                                    // Check if the candidate is already selected
                                                                    $isChecked = ($vote_result->num_rows > 0) ? 'checked' : '';
                                                                    // Determine whether to disable the input based on the checkbox or radio button
                                                                    $isDisabled = empty($isChecked) ? 'disabled' : '';

                                                                    // Add the class based on the conditions
                                                                    $divClass = ($isChecked ? ' border-3 border-primary' : 'border-1');
                                                                    // Add the "abstain" option
                                                                    $abstainInput = ($row["maximum_vote"] > 1) ? 
                                                                        '<input class="form-check-input" type="checkbox" 
                                                                            name="'.$row["position_id"]."[]".'" 
                                                                            id="abstain_' . $row["position_id"] . '" 
                                                                            value="0" '.$isChecked.' '.$isDisabled.' 
                                                                            onclick="return false">' : 
                                                                        '<input class="form-check-input abstain-radio" type="radio" 
                                                                            name="' . $row["position_id"] . '" 
                                                                            id="abstain_' . $row["position_id"] . '" 
                                                                            value="0" '.$isChecked.' '.$isDisabled.'>';
                                                                    echo '
                                                                        <div class="col-12 mb-3">
                                                                            <div class="px-4 py-4 mx-1 border '.$divClass.' rounded d-flex justify-content-between align-items-center">
                                                                                <div class="form-check">
                                                                                    '.$abstainInput.'
                                                                                    <label class="form-check-label text-black ms-2 fs-6" for="abstain_'.$row["position_id"].'">ABSTAINED</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    ';
                                                                }                              
                                                        echo '
                                                                
                                                            </div>
                                                        ';
                                                    }
                                                }
                                            
                                            ?>  
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Results Modal --> 
        <div id="results_modal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="results_modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header border-0 d-flex justify-content-between pb-0">
                        <h1 class="modal-title fs-2 text-black" id="results_modalLabel"><?php echo "$election_name "; ?>Results</h1>
                        <div class="d-flex">
                            <button type="button" id="downloadBtn" class="btn btn-primary align-self-center me-5" <?php echo ($status === "completed") ? '' : 'style="display:none;"'; ?>>DOWNLOAD</button>
                            <button type="button" class="btn-close align-self-start" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="modal-body pt-5" id="resultModalpdf">
                        <?php
                            if(isset($_SESSION['election-id'])) {
                                $election_id = $_SESSION['election-id'];
                                $sql_modal = "SELECT * FROM positionstable WHERE election_id = '$election_id' ORDER BY priority ASC";
                                $query = $con->query($sql_modal);
                                while($row = $query->fetch_assoc()){
                                    echo '
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card z-index-2 mb-5">
                                                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                                                    <div class="bg-dark-blue shadow-primary border-radius-lg py-3 pe-1">
                                                        <div class="chart">
                                                            <canvas id="voter'. $row["position_id"] .'" class="chart-canvas" height="350"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body pb-2 d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0">Results for '.$row["position_desc"].'</h6>
                                                </div>
                                            </div>
                                        </div>
                                    ';
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Feedback Modal --> 
        <div id="feedback_modal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="results_modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header border-0 d-flex pb-0">
                        <h3 class="modal-title fs-3 text-black" id="feedback_modalLabel">Voters Feedback</h3>
                        <button type="button" class="btn-close align-self-start" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 d-flex">
                                <img src="../assets/images/Feedback-pana.svg" alt="" class="img-fluid align-self-end">
                            </div>
                            <div class="col-lg-6">
                                <h6>Give Feedback</h6>
                                <p>What do you think of this election? Please share your thoughts.</p>
                                <form action="<?= $path ?>" method="post">
                                    <div class="input-group input-group-outline">
                                        <label class="form-label" for="feedback_voterName">Voters Name</label>
                                        <input 
                                            type="text" 
                                            id="feedback_voterName" 
                                            onfocus="focused(this)" 
                                            onfocusout="defocused(this)" 
                                            name="feedback-voter-name" 
                                            class="form-control mb-3" 
                                            required
                                        />                  
                                    </div>
                                    <div class="form-floating">
                                        <textarea name="feedback-voter" class="form-control px-2 text-black" id="feedback_voter" style="height: 300px"></textarea>
                                        <label for="feedback_voter">Comments</label>
                                    </div>
                                    <button type="submit" name="submit-feedback" value="<?php echo $voter_id; ?>" class="btn btn-primary text-white btn-block my-4 px-4 form-control">SUBMIT</button>
                                </form>
                            </div>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
        crossorigin="anonymous">
</script>
<script src="https://kit.fontawesome.com/0c03208a41.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-notify@0.5.5/dist/simple-notify.min.js"></script>
<script>
    $(document).ready(function(){
        $(document).on('shown.bs.modal','#results_modal', function () {
            <?php
              $sql = "SELECT * FROM positionstable WHERE election_id = '$election_id' ORDER BY priority ASC";
              $query = $con->query($sql);
              while($mrow = $query->fetch_assoc()){
              ?>
                var votesData = <?php echo $jsonData; ?>;
                var rowid = '<?php echo $mrow['position_id']; ?>';
                var ctx = document.getElementById('voter' + rowid).getContext("2d");
                
                new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: votesData
                        .filter(item => item.position_id === '<?php echo $mrow['position_id']; ?>')
                        .map(item => item.candidate_name),
                        datasets: [{
                        label: "Votes",
                        tension: 0.4,
                        borderWidth: 0,
                        borderRadius: 4,
                        borderSkipped: false,
                        backgroundColor: "rgba(255, 255, 255, .8)",
                        data: votesData
                        .filter(item => item.position_id === '<?php echo $mrow['position_id']; ?>')
                        .map(item => item.vote_count),
                        maxBarThickness: 8
                        }, ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                        legend: {
                            display: false,
                        }
                        },
                        interaction: {
                        intersect: false,
                        mode: 'index',
                        },
                        scales: {
                        y: {
                            grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                            color: 'rgba(255, 255, 255, .2)'
                            },
                            ticks: {
                            suggestedMin: 0,
                            suggestedMax: 500,
                            beginAtZero: true,
                            padding: 10,
                            font: {
                                size: 14,
                                weight: 300,
                                family: "Roboto",
                                style: 'normal',
                                lineHeight: 2
                            },
                            color: "#fff"
                            },
                        },
                        x: {
                            grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                            color: 'rgba(255, 255, 255, .2)'
                            },
                            ticks: {
                            display: true,
                            color: '#f8f9fa',
                            padding: 10,
                            font: {
                                size: 14,
                                weight: 300,
                                family: "Roboto",
                                style: 'normal',
                                lineHeight: 2
                            },
                            }
                        },
                        },
                    },
                });
                 <?php
              }
            ?>
        });
    })
</script>

    

<script src="../assets/js/material-dashboard.js"></script>
<!--
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
-->
<script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-html2canvas@latest/dist/jspdf-html2canvas.min.js"></script>
<script>
    let btn = document.getElementById('downloadBtn');
    let page = document.getElementById('resultModalpdf');
    var title = document.getElementById('results_modalLabel').textContent;
    
    btn.addEventListener('click', function(){
         html2PDF(page, {
            jsPDF: {
                unit: 'pt',
                format: 'a4',
                pagesplit: true,
            },
            imageType: 'image/jpeg',
            margin: {
                top: 50,
                bottom: 10
            },
            output: './pdf/generated.pdf',
            html2canvas: {
                logging: true,
                allowTaint: false,
                windowHeight: page.scrollHeight + 100
            },
            watermark({ pdf, pageNumber, totalPageNumber }) {
                // pdf: jsPDF instance
                pdf.setTextColor('#000');
                pdf.setFontSize(20);
                pdf.text(15, 30, `${title}`);
            }
         });
    });
</script>
<script>    
    var baseUrl = "<?php echo BASE_URL; ?>";
    
    $(document).ready(function(){
        $(document).on('click', '#candidate-info', function() {
            var dataId = $(this).attr("data-candidate-id");
            $.ajax({
                type: "POST",
                url: baseUrl + "config/controllerUserData.php",
                data: {candidateId:dataId},
                dataType: "json",
                success: function (response) {
                    $('#platform-name-ballot').html(response.name);
                    $('#platform-position-ballot').html("Running for " + response.position_desc);
                    $('#platform-content-ballot').html(response.platform);
                    $("#platform-img-ballot").attr("src", response.candidate_image_path);                
                },
                error: function (xhr, status, error) {
                    // Handle the error
                    console.error('Error: ' + status + ' - ' + error);
                }
            });     
            $('#view-platform-ballot').modal('show');   
        });

        $(document).on('click', '#view_ballot', function() {
            var dataId = $(this).attr("data-voter-id");
            $.ajax({
                type: "POST",
                url: baseUrl + "config/controllerUserData.php",
                data: {voterId:dataId},
                dataType: "json",
                success: function (response) {
                    
                } 
            });     
            $('#view-ballot').modal('show');   
        });

        $(document).on('click', '#voter_signout', function() {
            $.ajax({
                type: "POST",
                url: baseUrl + "config/controllerUserData.php",
                data: {action:"voter_signout"},
                success: function (response) {
                    window.location.href = "login.php";
                } 
            });     
        });
    })
</script>
<script>
    <?php
        if(count($errors) == 1){
            foreach($errors as $showerror){
                ?>
                pushNotify('error', '<?= $showerror; ?>');
                <?php
            }   
        }
        if(count($success) == 1){
            foreach($success as $showsuccess){
                ?>
                pushNotify('success', '<?= $showsuccess; ?>');
                <?php
            }   
        }
    ?>
    function pushNotify(status, title) {
        new Notify({
            status: status,
            title: title,
            effect: 'slide',
            speed: 300,
            customClass: null,
            customIcon: null,
            showIcon: true,
            showCloseButton: true,
            autoclose: true,
            autotimeout: 1000,
            gap: 20,
            distance: 20,
            type: 1,
            position: 'x-center top'
        });
    }
</script>
</body>
</html>