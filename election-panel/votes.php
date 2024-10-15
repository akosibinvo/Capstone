<?php include("includes/header.php");?>

    <div class="container-fluid pb-3 pe-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0 p-3 pt-2">
                        <div class="row">
                            <div class="col-lg-4 d-flex align-items-center">
                                <h3 class="mb-0 text-black">List of Votes Receive</h3>
                            </div>
                            <div class="col-lg-8">
                                <div class="row d-flex justify-content-end">
                                <div class="col-auto text-end">
                                    <form action="">
                                        <div class="input-group input-group-outline my-3">
                                            <label class="form-label">Search</label>
                                        <input type="text" class="form-control">
                                        <button class="btn bg-primary mb-0">SEARCH</button>
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
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder ">Voter ID</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder ">Position</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder ">Candidate</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(isset($_SESSION['election-id'])) {
                                        $election_id = $_SESSION['election-id'];
                                        $sql = "SELECT
                                                    votestable.*,
                                                    CASE
                                                        WHEN votestable.candidate_id = 0 THEN 'ABSTAINED'
                                                        ELSE candidates_table.name
                                                    END AS candidate_name,
                                                    voters_table.voter_id,
                                                    positionstable.position_desc
                                                FROM
                                                    votestable
                                                JOIN
                                                    positionstable ON positionstable.position_id = votestable.position_id
                                                LEFT JOIN
                                                    candidates_table ON candidates_table.candidate_id = votestable.candidate_id
                                                JOIN
                                                    voters_table ON voters_table.voter_id = votestable.voter_id
                                                WHERE
                                                    votestable.election_id = '$election_id';";

                                        $query = $con->query($sql);
                                        if(mysqli_num_rows($query) > 0){
                                            $output = '';
                                            while($row = $query->fetch_assoc()){
                                                $output .= '
                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-0 text-sm ">'.$row["voter_id"].'</h6>
                                                        </td>                   
                                                        <td>
                                                            <h6 class="mb-0 text-sm ">'.$row["position_desc"].'</h6>
                                                        </td>
                                                        <td>
                                                            <h6 class="mb-0 text-sm">'.$row["candidate_name"].'</h6>
                                                        </td>
                                                    </tr> 
                                                ';
                                            }
                                            echo $output;
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