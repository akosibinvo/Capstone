<?php include("includes/header.php");?>

<div class="container-fluid pb-3 pe-4">
    <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-0 p-3 pt-2">
                <div class="row">
                    <div class="col-lg-4 d-flex align-items-center">
                        <h3 class="mb-0 text-black">Voters List</h3>
                    </div>
                    <div class="col-lg-8">
                        <div class="row d-flex justify-content-end">
                        <div class="col-auto d-flex align-items-center">
                            <button class="btn bg-primary mb-0" data-bs-toggle="modal" data-bs-target="#unverified_voter">
                                <span class="me-2">Unverified Voters</span>
                                <?php
                                        $election_id = $_SESSION['election-id'];
                                        $sql = "SELECT COUNT(*) AS row_count FROM voters_table WHERE election_id = '$election_id' AND isData_verified = '0' AND isEmail_verified = '1'";
                                        $result = $con->query($sql);

                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();
                                            $rowCount = $row['row_count'];
                                            if ($rowCount > 0) {
                                                echo "<span class='badge text-bold text-xs text-bg-light' id='unverifiedVoterBadge'>$rowCount</span>";
                                            }
                                        }
                                    ?>  
                            </button>
                        </div>
                        <div class="col-auto text-end">
                            <form action="" method="GET">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Search</label>
                                    <input type="search" name="search_voter" class="form-control" >
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
                <table class="table align-items-center mb-0 table-fixed" id="voters_table">
                    <thead>
                        <tr>
                            <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Name</th>
                            <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Section</th>
                            <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder" colspan="2">Email</th>
                            <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">ID Number</th>
                            <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Registration Date</th>
                            <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Status</th>
                        <!--    <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Voters ID</th> 
                            <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($_SESSION['election-id'])) {
                                $election_id = $_SESSION['election-id'];                               
                                $output = '';

                                if(isset($_GET['search_voter'])) {
                                    $filtervalues = $_GET['search_voter'];
                                    $sql = "SELECT * FROM voters_table 
                                            WHERE election_id = '$election_id' AND isData_verified = '1' AND isEmail_verified = '1' AND CONCAT(name, email, section) LIKE '%$filtervalues%'";
                                    $query = $con->query($sql);

                                    if(mysqli_num_rows($query) > 0){
                                        while($row = $query->fetch_assoc()){
                                            $output .= '
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 text-sm">'.$row["name"].'</h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 text-sm">'.$row["section"].'</h6>
                                                    </td>
                                                    <td colspan="2">
                                                        <h6 class="mb-0 text-sm">'.$row["email"].'</h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 text-sm">'.$row["id_number"].'</h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 text-sm">'.$row["registration_timestamp"].'</h6>
                                                    </td>
                                                    <td class="text-sm">
                                                        <span class="badge bg-primary text-light"><i class="fa-solid fa-circle-check me-1"></i>Verified</span>
                                                    </td>
                                                </tr>    
                                            ';
                                        }
                                        echo $output;
                                    }
                                    else {
                                        ?>

                                        <tr>
                                            <td colspan="7">
                                                <h6 class="mb-0 text-md ps-3 text-center pt-3">No Records Found.</h6>
                                            </td>
                                        </tr>

                                        <?php
                                    }
                                }
                                else {
                                    $sql = "SELECT * FROM voters_table WHERE election_id = '$election_id' AND isData_verified = '1' AND isEmail_verified = '1'";
                                    $query = $con->query($sql);

                                    if(mysqli_num_rows($query) > 0){
                                        while($row = $query->fetch_assoc()){
                                            $output .= '
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 text-sm">'.$row["name"].'</h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 text-sm">'.$row["section"].'</h6>
                                                    </td>
                                                    <td colspan="2">
                                                        <h6 class="mb-0 text-sm">'.$row["email"].'</h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 text-sm">'.$row["id_number"].'</h6>
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
                                            <td colspan="7">
                                                <h6 class="mb-0 text-md text-center pt-3">No Registered Voter.</h6>
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