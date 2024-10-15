<div id="user_account" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title text-black">Account Settings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?= $page ?>" method="post" enctype = "multipart/form-data">
          <div class="row">
            <div class="col-6">
              <div class="row d-flex justify-content-center py-3">
                <div class="col-8">
                  <img id="user_image_edit_preview" src="../assets/images/profile.jpg" class="img-fluid rounded-circle border border-2 p-1 border-primary" alt="">
                </div>
              </div>
              <div class="row d-flex justify-content-center">
                <div class="col-8">
                  <input class="form-control" type="file" id="edit-image-user" name="user_image_edit" onchange="previewUserImage(this)">
                </div>
              </div>           
            </div>
            <div class="col-6">
              <h6 class="text-black text-sm pb-3">Account Informations</h6>
              <form action="<?= $page ?>" method="post">
                <div class="input-group input-group-outline mb-3 is-focused">
                  <label class="form-label">Name</label>
                  <input id="user_Name_edit" type="text" class="form-control" name="user_name" value="" required>
                </div>
                <div class="input-group input-group-outline mb-4 is-focused">
                  <label class="form-label">Email Address</label>
                  <input id="user_emailadd_edit" type="email" class="form-control" name="user_email" value="" required>
                </div>  
                <h6 class="text-black text-sm mb-4">Change Password</h6>
                <div class="input-group input-group-outline mb-3">
                  <label class="form-label">Current Password</label>
                  <input id="user_cpass_edit" type="password" class="form-control" name="user_cpass">
                </div> 
                <div class="input-group input-group-outline mb-3">
                  <label class="form-label">New Password</label>
                  <input id="user_npass_edit" type="password" class="form-control" name="user_npass">
                </div> 
                <div class="input-group input-group-outline mb-4">
                  <label class="form-label">Confirm Password</label>
                  <input id="user_npass_confirm_edit" type="password" class="form-control" name="user_npass_confirm">
                </div> 
                <button value="<?php echo $user_id; ?>" name="save_user_info" type="submit" class="btn btn-primary w-100">Save changes</button>
              </form>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- modal for unverified voters -->
<div id="unverified_voter" class="modal fade"  data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="unverified_voterLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h1 class="modal-title fs-3 text-black" id="unverified_voterLabel">List of Unverified Voters</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0 table-fixed" id="unverifiedVotersTable">
                      <thead>
                          <tr>
                              <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Name</th>
                              <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Section</th>
                              <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder" colspan="2">Email</th>
                              <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">ID Number</th>
                              <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Registration Date</th>
                              <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Action</th>
                          <!--    <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Voters ID</th> 
                              <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Action</th> -->
                          </tr>
                      </thead>
                      <tbody>
                          <?php
                              if(isset($_SESSION['election-id'])) {
                                  $election_id = $_SESSION['election-id'];                               
                                  $output = '';
                                  $sql = "SELECT * FROM voters_table WHERE election_id = '$election_id' AND isData_verified = '0' AND isEmail_verified = '1'";
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
                                                  <td>
                                                    <a role="button" voterid="'.$row["voter_id"].'" class="text-xs text-primary text-bold"
                                                       data-bs-target="#verify_voter" data-bs-toggle="modal" data-bs-dismiss="modal">
                                                      VERIFY
                                                    </a>
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
                                              <h6 class="mb-0 text-md text-center pt-3">No Unverified Voter.</h6>
                                          </td>
                                      </tr>

                                      <?php
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

<!-- modal for verifying voter -->
<div class="modal fade" id="verify_voter" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="verify_voterLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h1 class="modal-title fs-4 text-black" id="verify_voterLabel">Verify Voter Data</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-0">
        <div class="row">
          <div class="col-lg-5">
              <img id="verify_voterImage" alt="" class="img-fluid rounded">
          </div>
          <div class="col-lg-7">
            <h1 class="fs-5 text-black mb-2">Voter Data</h1>
            <p class="fs-6 text-black mb-5">Please manually verify the data from the voters registration form from the image of the voters ID.</p>
            <form action="">
              <div class="col-lg-8">
                <div class="input-group input-group-outline mb-4 is-focused">                 
                  <label class="form-label">Full Name</label>
                  <input type="text" id="verify_voterName" name="verify-voter-name" class="form-control" readonly/>
                </div>
                <div class="input-group input-group-outline mb-4 is-focused">                 
                  <label class="form-label">Year & Section</label>
                  <input type="text" id="verify_voterSection" name="verify-voter-section" class="form-control" readonly/>
                </div>
                <div class="input-group input-group-outline is-focused">                 
                  <label class="form-label">ID Number</label>
                  <input type="text" id="verify_voterIdNumber" name="verify-voter-idNumber" class="form-control" readonly/>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button id="verify_voterBtn" type="button" class="btn btn-primary w-100 disableButtonOnLaunch">Verify Data</button>
      </div>
    </div>
  </div>
</div>

<!-- modal for unverified candidate -->
<div id="unverified_candidate" class="modal fade"  data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="unverified_candidateLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h1 class="modal-title fs-3 text-black" id="unverified_candidateLabel">List of Unverified Candidate</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive p-0">
          <table class="table align-items-center mb-0 table-fixed" id="unverifiedCandidatesTable">
            <thead>
                <tr>
                    <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Candidate</th>
                    <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Section</th>
                    <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">ID Number</th>
                    <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Position</th>
                    <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Registration Date</th>
                    <th scope="col" class="text-uppercase text-secondary text-xs font-weight-bolder ">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(isset($_SESSION['election-id'])) {
                      $election_id = $_SESSION['election-id'];
                      $output = '';
                      $sql = "SELECT candidates_table.*, positionstable.position_desc, positionstable.priority
                              FROM candidates_table
                              JOIN positionstable ON candidates_table.position = positionstable.position_id
                              WHERE candidates_table.election_id = '$election_id' AND isData_verified = '0' AND isEmail_verified = '1'
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
                                      <h6 class="mb-0 text-sm">'.$row["registration_timestamp"].'</h6>
                                  </td>
                                  <td>
                                    <a role="button" candidateid="'.$row["candidate_id"].'" class="text-xs text-primary text-bold" 
                                        data-bs-target="#verify_candidate" data-bs-toggle="modal" data-bs-dismiss="modal">
                                      VERIFY
                                    </a>
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
                ?>                        
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- modal for verifying candidate -->
<div class="modal fade" id="verify_candidate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="verify_candidateLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h1 class="modal-title fs-4 text-black" id="verify_candidateLabel">Verify Candidate Data</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-0">
        <div class="row">
          <div class="col-lg-5">
              <img id="verify_candidateImage" alt="" class="img-fluid rounded">
          </div>
          <div class="col-lg-7">
            <h1 class="fs-5 text-black mb-2">Candidate Data</h1>
            <p class="fs-6 text-black mb-5">Please manually verify the data from the candidates registration form from the image of the candidates ID.</p>
            <form action="">
              <div class="col-lg-8">
                <div class="input-group input-group-outline mb-4 is-focused">                 
                  <label class="form-label">Full Name</label>
                  <input type="text" id="verify_candidateName" name="verify-candidate-name" class="form-control" readonly/>
                </div>
                <div class="input-group input-group-outline mb-4 is-focused">                 
                  <label class="form-label">Year & Section</label>
                  <input type="text" id="verify_candidateSection" name="verify-candidate-section" class="form-control" readonly/>
                </div>
                <div class="input-group input-group-outline is-focused">                 
                  <label class="form-label">ID Number</label>
                  <input type="text" id="verify_candidateIdNumber" name="verify-candidate-idNumber" class="form-control" readonly/>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button id="verify_candidateBtn" type="button" class="btn btn-primary w-100 disableButtonOnLaunch">Verify Data</button>
      </div>
    </div>
  </div>
</div>

<div id="add-position" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 2;"></button>
            <div class="container-fluid">
              <div class="row d-flex justify-content-center">
                <div class="col-lg-10 position-relative pb-3">
                  <div class="row text-center">
                  <h1 class="fs-5">Add Position</h1>
                    <p class="fs-6">Please enter position details.</p>
                  </div>
                  <form action="<?= $page ?>" method="post">
                    <div class="input-group input-group-outline my-3">
                        <label class="form-label">Description</label>
                        <input type="text" class="form-control" name="position-name" onfocus="focused(this)" onfocusout="defocused(this)" required>
                    </div>
                    <div class="input-group input-group-outline">
                        <label class="form-label">Maximum Votes</label>
                        <input type="number" class="form-control" name="max-vote" onfocus="focused(this)" onfocusout="defocused(this)" min="1" required>
                    </div>
                    <div class="input-group input-group-outline mt-3">
                        <label class="form-label">Maximum Candidates</label>
                        <input type="number" class="form-control" name="max-candidate" onfocus="focused(this)" onfocusout="defocused(this)" min="1" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="add-position" class="btn btn-round bg-primary btn-lg w-100 mt-4 mb-0">ADD POSITION</button>
                    </div>
                </form>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>

<div id="view-platform" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-body position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 2;"></button>
            <div class="container-fluid">
              <div class="row d-flex justify-content-center">
                <div class="col-lg-5 position-relative pt-2">
                  <div class="row justify-content-center">
                    <div class="col-9">
                      <img id="platform-img" src="" class="img-fluid rounded-circle border border-3 p-2 border-primary" alt="">
                    </div>
                  </div>
                  <div class="row text-center pt-2">
                    <h1 class="fs-5 mb-0 text-black" id="platform-name"></h1>
                    <p class="fs-6" id="platform-position"></p>
                  </div>
                </div>
                <div class="col-lg-7 position-relative">
                  <h1 class="fs-3 text-black">Platforms:</h1>
                  <div class="border border-3 bg-light border-primary px-3 rounded " id="platform-content"></div>
                </div>            
              </div>
            </div>
          </div>
        </div>
    </div>
</div>

<div id="edit-position" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 2;"></button>
            <div class="container-fluid">
              <div class="row d-flex justify-content-center">
                <div class="col-lg-10 position-relative pb-3">
                  <div class="row text-center">
                  <h1 class="fs-5">Edit Position</h1>
                    <p class="fs-6">Edit position details then click save.</p>
                  </div>
                     <form action="<?= $page ?>" method="post">
                        <div class="input-group input-group-outline my-3 is-focused">
                            <label class="form-label">Description</label>
                            <input id="positions-edit-input" type="text" class="form-control" name="position-name-edit" required>
                        </div>
                        <div class="input-group input-group-outline is-focused">
                            <label class="form-label">Maximum Votes</label>
                            <input id="positions-max-vote" type="number" class="form-control" name="max-vote-edit" min="1" required>
                        </div>
                        <div class="input-group input-group-outline mt-3 is-focused">
                            <label class="form-label">Maximum Candidate</label>
                            <input id="positions-max-can" type="number" class="form-control" name="max-can-edit" min="1" required>
                        </div>
                        <div class="text-center">
                            <button value="" id="positions-edit-save" type="submit" name="save-position" class="disableButtonOnLaunch btn btn-round bg-primary btn-lg w-100 mt-4 mb-0">SAVE</button>
                        </div>
                    </form>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-delete-modal-position" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header border-0 text-center">
        <h1 class="modal-title fs-5 text-black w-100" id="exampleModalLabel">Delete Voter?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <h6 id="position_name_dialog" class="text-black fs-6 mb-1"></h6>
        <h6 class="text-sm text-black">You can't undo this action.</h6>
      </div>
      <div class="modal-footer border-0 pb-0 d-flex justify-content-center">
        <form action="<?= $page ?>" method="post">
          <button type="button" class="btn btn-primary me-2" data-bs-dismiss="modal">Cancel</button>
          <button id="delete_position_btn" name="delete_position_btn_name" value="" type="submit" class="disableButtonOnLaunch btn btn-primary">Confirm</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="otp_panel" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 2;"></button>
            <div class="container-fluid">
              <div class="row d-flex justify-content-center">
                <div class="col-lg-10 position-relative pb-2">
                  <div class="row justify-content-center pt-5">
                    <div class="col-10">
                      <img src="../assets/images/Enter OTP-bro.svg" class="img-fluid" alt="">
                    </div>
                  </div>
                  <div class="row text-center pt-1">
                    <h1 class="fs-5">OTP Verification</h1>
                    <p class="fs-6"><?php echo $_SESSION['info']; ?></p>
                  </div>
                  <form action="<?= $page ?>" method="post" autocomplete="off">
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Enter Verification Code</label>
                      <input type="text" class="form-control" name="otp" required> 
                    </div>                           
                    <button name="check" type="submit" class="btn btn-primary w-100 mb-0">VERIFY</button>
                    <a id="resend-otp" class="ms-1 mt-1 link-primary" style="font-size: small;text-decoration: none;">Resend Code</a>  
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-delete-modal-election" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0 text-center">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-header border-0 text-center">
        <h1 class="modal-title fs-4 text-black w-100" id="exampleModalLabel">Delete Election?</h1>
      </div>
      <div class="modal-body text-center">
        <h6 id="election_name_dialog" class="text-black fs-6 mb-1"></h6>
        <h6 class="text-sm text-black">Are you sure you want to delete this election?<br>You can't undo this action.</h6>
      </div>
      <div class="modal-footer border-0 pb-3 d-flex justify-content-center">
        <form action="<?= $page ?>" method="post">
          <button type="button" class="btn btn-primary me-2" data-bs-dismiss="modal">Cancel</button>
          <button id="confirm_delete_election_btn" type="button" class="btn btn-primary">Confirm</button>
        </form>
      </div>
    </div>
  </div>
</div>