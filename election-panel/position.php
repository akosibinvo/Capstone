<?php include("includes/header.php");?>

    <div class="container-fluid pb-3 pe-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0 p-3 pt-2">
                        <div class="row">
                            <div class="col-lg-4 d-flex align-items-center">
                                <h3 class="mb-0 text-black">Positions List</h3>
                            </div>
                            <div class="col-lg-8">
                                <div class="row d-flex justify-content-end">
                                    <div class="col-auto d-flex align-items-center">
                                        <button class="disableButtonOnLaunch btn btn-primary text-white mb-0" data-bs-toggle="modal" data-bs-target="#add-position"><i class="fa-solid fa-plus fw-bold me-2"></i>Add Position</button>
                                    </div>
                                    <div class="col-auto text-end">
                                        <form action="" method="GET">
                                            <div class="input-group input-group-outline my-3">
                                                <label class="form-label">Search</label>
                                                <input type="search" name="search_pos" class="form-control">
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
                            <table class="table align-items-center mb-0 table-fixed">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder">Description</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder">Maximum Vote</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder">Maximum Candidate</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if(isset($_SESSION['election-id'])) {
                                            $election_id = $_SESSION['election-id'];
                                            $output = '';

                                            if(isset($_GET['search_pos'])) {
                                                $filtervalues = $_GET['search_pos'];
                                                $sql = "SELECT * FROM positionstable WHERE election_id = '$election_id' AND position_desc LIKE '%$filtervalues%'";
                                                $query = $con->query($sql);

                                                if(mysqli_num_rows($query) > 0){
                                                    while($row = $query->fetch_assoc()){
                                                        $output .= '
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0 text-sm">'.$row["position_desc"].'</h6>
                                                            </td>
                                                            <td>
                                                                <h6 class="mb-0 text-sm">'.$row["maximum_vote"].'</h6>
                                                            </td>
                                                            <td>
                                                                <h6 class="mb-0 text-sm">'.$row["maximum_candidate"].'</h6>
                                                            </td>
                                                            <td>
                                                                <div class="row m-0">
                                                                    <div class="col-auto p-0">
                                                                        <button id="view-edit-position-modal" data-position-id="'.$row["position_id"].'" class=" text-secondary font-weight-bold text-xs btn btn-primary text-white"><i class="fa-solid fa-pencil me-2"></i>EDIT</button>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <button id="view_delete_position_btn" data-position-id="'.$row["position_id"].'" class=" text-secondary font-weight-bold text-xs btn btn-primary text-white"><i class="fa-solid fa-trash me-2"></i>DELETE</button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr> 
                                                        ';
                                                    }
                                                    echo $output;
                                                }
                                                else {
                                                    ?>

                                                    <tr>
                                                        <td colspan="3">
                                                            <h6 class="mb-0 text-md ps-3 text-center pt-3">No Records Found.</h6>
                                                        </td>
                                                    </tr>

                                                    <?php
                                                }
                                            }
                                            else {
                                                $sql = "SELECT * FROM positionstable WHERE election_id = '$election_id' ORDER BY priority";
                                                $query = $con->query($sql);
                                                

                                                if(mysqli_num_rows($query) > 0){
                                                    while($row = $query->fetch_assoc()){
                                                        $output .= '
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0 text-sm">'.$row["position_desc"].'</h6>
                                                            </td>
                                                            <td>
                                                                <h6 class="mb-0 text-sm">'.$row["maximum_vote"].'</h6>
                                                            </td>
                                                            <td>
                                                                <h6 class="mb-0 text-sm">'.$row["maximum_candidate"].'</h6>
                                                            </td>
                                                            <td class="">
                                                                <div class="row m-0">
                                                                    <div class="col-auto p-0">
                                                                        <button id="view-edit-position-modal" data-position-id="'.$row["position_id"].'" class="text-secondary font-weight-bold text-xs btn btn-primary text-white"><i class="fa-solid fa-pencil me-2"></i>EDIT</button>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <button id="view_delete_position_btn" data-position-id="'.$row["position_id"].'" class="text-secondary font-weight-bold text-xs btn btn-primary text-white"><i class="fa-solid fa-trash me-2"></i>DELETE</button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr> 
                                                        ';
                                                    }
                                                    echo $output;
                                                }
                                                else {
                                                    ?>

                                                    <tr>
                                                        <td colspan="3">
                                                            <h6 class="mb-0 text-md ps-3 text-center pt-3">Click Add Position Button To Add Voter.</h6>
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