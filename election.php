<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>
<?php require_once "includes/header.php"; ?>
<?php require_once "includes/modals.php"; ?>
<?php require_once "includes/navbar.php"; ?>

    <div class="election-page">
        <div class="container mt-2">
          <div class="row pe-lg-3">
            <div class="col-3">
              <h1 class="fs-2 text-black">Elections</h1>
            </div>
            <div class="col-9">
              <div class="row d-flex justify-content-end">
                <div class="col-auto">
                  <button class="btn bg-primary" type="button" id="create-electionBtn"><i class="fa-solid fa-plus fw-bold me-2"></i>Create Election</button>  
                </div>
                <div class="col-auto">
                  <div class="input-group">
                    <label class="btn bg-primary mb-0" style="cursor: default;" disabled>filter</label>
                    <select class="form-select" id="status_select" style="cursor: pointer;">
                      <option selected value="">All</option>
                      <option value="1">Building</option>
                      <option value="2">Scheduled</option>
                      <option value="3">Running</option>
                      <option value="4">Completed</option>
                    </select>
                  </div>                 
                </div>
                <div class="col-auto">
                  <form action="" method="GET">
                    <div class="input-group input-group-outline">
                        <label class="form-label">Search Election</label>
                        <input type="text" name="search_elec" class="form-control" >
                        <button type="submit" class="btn bg-primary mb-0">SEARCH</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="container mt-3 pb-3" id="election-list">
          <?php
            require_once "config/session.php";

            if(isset($_SESSION['email']) && isset($_SESSION['password']) && $status == 'verified'){
              $email = $_SESSION['email'];
              $output = '';

              if(isset($_GET['search_elec'])) {
                $filtervalues = $_GET['search_elec'];
                $sql = "SELECT * FROM electiontable WHERE created_by = '$email' AND election_name LIKE '%$filtervalues%' ORDER BY election_name ASC";
                $query = $con->query($sql);

                if(mysqli_num_rows($query) > 0){
                  while($row = $query->fetch_assoc()){
                    $timezone = $row["timezone"];
  
                    $new_startdate_timezone = new DateTime($row["start_date"], new DateTimeZone($timezone));
                    $new_enddate_timezone = new DateTime($row["end_date"], new DateTimeZone($timezone));
  
                    $new_date_start = $new_startdate_timezone->format("Y-m-d H:i:s");
                    $new_date_end = $new_enddate_timezone->format("Y-m-d H:i:s");
  
                    $startdate = DateTime::createFromFormat("Y-m-d H:i:s", $new_date_start)->format('M j, Y h:i: A');
                    $enddate = DateTime::createFromFormat("Y-m-d H:i:s", $new_date_end)->format('M j, Y h:i: A');
  
                    $output .= '
                    <div class="row p-2 bg-light rounded-3 me-3 mb-4 election" data-election-id="'.$row["election_id"].'">
                      <div class="col-6">
                        <h1 class="fs-4 pt-2 text-black">'.$row["election_name"].'</h1>
                        <div class="row">
                          <div class="col-auto">
                            <p class="fs-6 p-1 border border-black rounded text-uppercase text-black">'.$row["status"].'</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-3 d-flex align-items-center">
                        <div class="row">
                          <p class="fs-6 mb-0 text-black"><i class="fa-solid fa-calendar-days me-2"></i>Start Date</p>
                          <p class="fs-6 mb-0 text-black">'.$startdate.'</p>
                        </div>                         
                      </div>
                      <div class="col-3 d-flex align-items-center">
                        <div class="row">
                          <p class="fs-6 mb-0 text-black"><i class="fa-solid fa-calendar-days me-2"></i>End Date</p>
                          <p class="fs-6 mb-0 text-black">'.$enddate.'</p>
                        </div>               
                      </div>
                    </div>
                    ';
                  }
                  echo $output;
                }
                else {
                  echo '
                    <h1 class="fs-5 text-center text-dark mt-5">No Records Found.</h1>
                  ';
                }
              }
              else {
                $sql = "SELECT * FROM electiontable WHERE created_by = '$email' ORDER BY date_created ASC";
                $query = $con->query($sql);
                
                if(mysqli_num_rows($query) > 0){
                  while($row = $query->fetch_assoc()){
                    $timezone = $row["timezone"];
  
                    $new_startdate_timezone = new DateTime($row["start_date"], new DateTimeZone($timezone));
                    $new_enddate_timezone = new DateTime($row["end_date"], new DateTimeZone($timezone));
  
                    $new_date_start = $new_startdate_timezone->format("Y-m-d H:i:s");
                    $new_date_end = $new_enddate_timezone->format("Y-m-d H:i:s");
  
                    $startdate = DateTime::createFromFormat("Y-m-d H:i:s", $new_date_start)->format('M j, Y h:i: A');
                    $enddate = DateTime::createFromFormat("Y-m-d H:i:s", $new_date_end)->format('M j, Y h:i: A');
  
                    $output .= '
                    <div class="row p-2 py-3 bg-light rounded-3 me-3 mb-4 election" data-election-id="'.$row["election_id"].'">
                      <div class="col-6">
                        <h1 class="fs-4 pt-2 text-black">'.$row["election_name"].'</h1>
                        <div class="row">
                          <div class="col-auto">
                            <p class="fs-6 p-1 border border-black rounded text-uppercase text-black">'.$row["status"].'</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-3 d-flex align-items-center">
                        <div class="row">
                          <p class="fs-6 mb-0 text-black"><i class="fa-solid fa-calendar-days me-2"></i>Start Date</p>
                          <p class="fs-6 mb-0 text-black">'.$startdate.'</p>
                        </div>                         
                      </div>
                      <div class="col-3 d-flex align-items-center">
                        <div class="row">
                          <p class="fs-6 mb-0 text-black"><i class="fa-solid fa-calendar-days me-2"></i>End Date</p>
                          <p class="fs-6 mb-0 text-black">'.$enddate.'</p>
                        </div>               
                      </div>
                    </div>
                    ';
                  }
                  echo $output;
                }
                else {
                  echo '
                    <div class="position-relative" style="height: 70vh;">
                      <h1 class="fs-3 text-center text-dark position-absolute top-50 start-50 translate-middle">Click <span style="color:#00008b;"><i class="fa-regular fa-square-plus ms-1 me-2"></i>Create Election</span> to Add</h1>
                    </div>
                  ';
                }
              }
            }
          ?>
        </div>
    </div>

<?php require_once "includes/footer.php"; ?>
<script src="assets/js/material-dashboard.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>