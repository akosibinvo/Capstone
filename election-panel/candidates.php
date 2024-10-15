<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>
<?php include("includes/header.php");?>

    <div class="container-fluid pb-3 pe-4">
        <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 p-3 pt-2">
                    <div class="row">
                        <div class="col-lg-4 d-flex align-items-center">
                            <h3 class="mb-0 text-black">Candidates List</h3>
                        </div>
                        <div class="col-lg-8">
                            <div class="row d-flex justify-content-end">
                            <div class="col-auto d-flex align-items-center">
                                <button class="btn bg-primary mb-0 px-3" data-bs-toggle="modal" data-bs-target="#unverified_candidate">
                                    <span class="me-2">Unverified Candidates</span>                                   
                                    <?php
                                        $election_id = $_SESSION['election-id'];
                                        $sql = "SELECT COUNT(*) AS row_count FROM candidates_table WHERE election_id = '$election_id' AND isData_verified = '0' AND isEmail_verified = '1'";
                                        $result = $con->query($sql);

                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();
                                            $rowCount = $row['row_count'];
                                            if ($rowCount > 0) {
                                                echo "<span class='badge text-bold text-xs text-bg-light' id='unverifiedCandidateBadge'>$rowCount</span>";
                                            }
                                        }
                                    ?>                                    
                                </button>
                            </div>
                            <div class="col-auto text-end">
                                <form action="" method="GET">
                                    <div class="input-group input-group-outline my-3">
                                        <label class="form-label">Search</label>
                                        <input type="search" name="search_can" class="form-control" >
                                        <button type="submit" class="btn bg-primary mb-0">SEARCH</button>
                                    </div>
                                </form>
                            </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            <div class="card-body px-0 pb-1">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0 table-fixed" id="candidates_table">
                        <thead>
                            <tr>
                                <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Candidate</th>
                                <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Section</th>
                                <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">ID Number</th>
                                <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Position</th>
                                <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Platform</th>
                                <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Registration Date</th>
                                <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($_SESSION['election-id'])) {
                                    $election_id = $_SESSION['election-id'];
                                    $output = '';

                                    if(isset($_GET['search_can'])) {
                                        $filtervalues = $_GET['search_can'];
                                    //  $sql = "SELECT *, candidatestable.candidate_id AS canid FROM candidatestable LEFT JOIN positionstable ON positionstable.position_id=candidatestable.position_id WHERE candidatestable.election_id = '$election_id' AND CONCAT(candidatestable.candidate_name, positionstable.position_desc) LIKE '%$filtervalues%' ORDER BY positionstable.priority ASC";
                                        $sql = "SELECT candidates_table.*, positionstable.position_desc, positionstable.priority
                                        FROM candidates_table
                                        JOIN positionstable ON candidates_table.position = positionstable.position_id
                                        WHERE candidates_table.election_id = '$election_id' AND isData_verified = '1' AND isEmail_verified = '1' AND CONCAT(candidates_table.name, candidates_table.section, positionstable.position_desc) LIKE '%$filtervalues%'
                                        ORDER BY positionstable.priority 
                                        ASC";
                                        $query = $con->query($sql);

                                        if(mysqli_num_rows($query) > 0){
                                            while($row = $query->fetch_assoc()){
                                                $output .= '
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex py-1">
                                                                <div>
                                                                    <img src="'.$row['candidate_image_path'].'" class="avatar-sm me-3 rounded">
                                                                </div>
                                                                <div class="d-flex flex-column justify-content-center">
                                                                    <h6 class="mb-0 text-sm">'.$row["name"].'</h6>
                                                                    <p class="text-xs text-black mb-0">'.$row["email"].'</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <h6 class="mb-0 text-sm">'.$row["section"].'</h6>
                                                        </td>
                                                        <td>
                                                            <h6 class="mb-0 text-sm">'.$row["id_number"].'</h6>
                                                        </td>
                                                        <td>
                                                            <h6 class="mb-0 text-sm">'.$row["position_desc"].'</h6>
                                                        </td>
                                                        <td>
                                                            <a role="button" data-candidate-id="'.$row["candidate_id"].'" class="view_platform_btn text-xs text-primary text-bold">
                                                                <i class="fa-solid fa-eye me-1"></i>VIEW
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <h6 class="mb-0 text-sm">'.$row["registration_timestamp"].'</h6>
                                                        </td>
                                                        <td class="text-sm">
                                                            <span class="badge bg-primary text-light text-bold"><i class="fa-solid fa-circle-check me-1"></i>Verified</span>
                                                        </td>
                                                    </tr>
                                                ';
                                            }
                                            echo $output;
                                        }
                                        else {
                                            ?>

                                            <tr>
                                                <td colspan="6">
                                                    <h6 class="mb-0 text-md ps-3 text-center pt-3">No Records Found.</h6>
                                                </td>
                                            </tr>

                                            <?php
                                        }
                                    }
                                    else {
                                    //    $sql = "SELECT *, candidatestable.candidate_id AS canid FROM candidatestable LEFT JOIN positionstable ON positionstable.position_id=candidatestable.position_id WHERE candidatestable.election_id = '$election_id' ORDER BY positionstable.priority ASC";
                                        $sql = "SELECT candidates_table.*, positionstable.position_desc, positionstable.priority
                                                FROM candidates_table
                                                JOIN positionstable ON candidates_table.position = positionstable.position_id
                                                WHERE candidates_table.election_id = '$election_id' AND isData_verified = '1' AND isEmail_verified = '1'
                                                ORDER BY positionstable.priority 
                                                ASC";
                                        $query = $con->query($sql);
                                        
                                        if(mysqli_num_rows($query) > 0){
                                            while($row = $query->fetch_assoc()){
                                                $output .= '
                                                <tr>
                                                    <td>
                                                        <div class="d-flex py-1">
                                                            <div>
                                                                <img src="'.$row['candidate_image_path'].'" class="avatar-sm me-3 rounded">
                                                            </div>
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">'.$row["name"].'</h6>
                                                                <p class="text-xs text-black mb-0">'.$row["email"].'</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 text-sm">'.$row["section"].'</h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 text-sm">'.$row["id_number"].'</h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 text-sm">'.$row["position_desc"].'</h6>
                                                    </td>
                                                    <td>
                                                        <a role="button" data-candidate-id="'.$row["candidate_id"].'" class="view_platform_btn text-xs text-primary text-bold">
                                                        <i class="fa-solid fa-eye me-1"></i>VIEW</a>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 text-sm">'.$row["registration_timestamp"].'</h6>
                                                    </td>
                                                    <td class="text-sm">
                                                        <span class="badge bg-primary text-light text-bold"><i class="fa-solid fa-circle-check me-1"></i>Verified</span>
                                                    </td>
                                                </tr>
                                                ';
                                            }
                                            echo $output;
                                        }
                                        else {
                                            ?>

                                            <tr>
                                                <td colspan="6">
                                                    <h6 class="mb-0 text-md ps-3 text-center pt-3">No Registered Candidate.</h6>
                                                </td>
                                            </tr>

                                            <?php
                                        }
                                    }
                                }
                            ?>                        
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>

<?php include("../includes/footer.php");?>
<script src="../assets/js/material-dashboard.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>