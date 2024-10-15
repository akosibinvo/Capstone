<?php include("includes/header.php");?>

<?php
    $election_id = $_SESSION['election-id'];
    $voter_recordId = "";
    $candidate_recordId = "";
    $election_code = "";
    $query = "SELECT * FROM electiontable WHERE election_id = '$election_id'";
    $result = mysqli_query($con, $query);
    
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $minDate = $row['start_date'];
        $maxDate = $row['end_date'];
        $election_code = $row['election_code'];
    }

    $get_voter_regisidQuery = "SELECT * FROM voter_registration WHERE election_code = '$election_code'";
    $res = mysqli_query($con, $get_voter_regisidQuery);

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $voter_recordId = $row['voter_registration_id'];
    }

    $get_candidate_regisidQuery = "SELECT * FROM candidate_registration WHERE election_code = '$election_code'";
    $res_candidate = mysqli_query($con, $get_candidate_regisidQuery);

    if (mysqli_num_rows($res_candidate) > 0) {
        $row = mysqli_fetch_assoc($res_candidate);
        $candidate_recordId = $row['candidate_registration_id'];
    }

    if(isset($_SESSION['election-id'])) {
        $election_id = $_SESSION['election-id'];
        $output = '';
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
                        <td class="">
                            <div class="row m-0">
                                <div class="col-auto p-0">
                                    <button onclick="movePosition(' . $row['position_id'] . ', \'up\')" class="disableButtonOnLaunch text-secondary font-weight-bold text-xs btn btn-primary text-white"><i class="fa-solid fa-chevron-up"></i></button>
                                </div>
                                <div class="col-auto">
                                    <button onclick="movePosition(' . $row['position_id'] . ', \'down\')" class="disableButtonOnLaunch text-secondary font-weight-bold text-xs btn btn-primary text-white"><i class="fa-solid fa-chevron-down"></i></button>
                                </div>
                            </div>
                        </td>
                    </tr> 
                ';
            }
        }
    }
    
    // Close the database connection
    mysqli_close($con);
?>

    <div class="container-fluid pb-3 pe-4">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-2">
                        <div class="list-group" id="list-tab" role="tablist">
                            <a class="list-group-item list-group-item-action active" id="list-general-list" data-bs-toggle="list" href="#list-general" role="tab" aria-controls="list-general">General</a>
                            <a class="list-group-item list-group-item-action my-2" id="list-voters-list" data-bs-toggle="list" href="#list-voters" role="tab" aria-controls="list-voters">Voters</a>
                            <a class="list-group-item list-group-item-action" id="list-candidate-list" data-bs-toggle="list" href="#list-candidate" role="tab" aria-controls="list-candidate">Candidate</a>
                            <a class="list-group-item list-group-item-action my-2" id="list-ballot-list" data-bs-toggle="list" href="#list-ballot" role="tab" aria-controls="list-ballot">Ballot</a>
                            <a class="list-group-item list-group-item-action" id="list-launch-list" data-bs-toggle="list" href="#list-launch" role="tab" aria-controls="list-launch">Launch</a>
                            <a class="list-group-item list-group-item-action mt-2" id="list-delete-list" data-bs-toggle="list" href="#list-delete" role="tab" aria-controls="list-delete">Delete</a>
                        </div>
                    </div>
                    <div class="col-10">
                        <div class="card" style="position: relative">
                            <div id="loading-spinner" class="rounded">
                                <img src="../assets/images/loading.svg" alt="Loading..." />
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="list-general" role="tabpanel" aria-labelledby="list-general-list">
                                        <h3 class="text-black mb-5">General Settings</h3>
                                        <form action="<?= $page ?>" method="post" autocomplete="">
                                            <div class="row mb-3">
                                                <label for="election_name_settings" class="form-label text-black">Election Name</label>
                                                <div class="input-group input-group-outline">                         
                                                    <input id="election_name_settings" type="text" class="form-control" name="election_name" value="Philippine Election 2028" required>
                                                </div>
                                            </div>                                 
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <div>
                                                        <label for="startdate_settings" class="form-label text-black">Start Date</label>
                                                        <div class="input-group input-group-outline">   
                                                            <?php
                                                                date_default_timezone_set('Asia/Manila');
                                                                $date = new DateTime(); // Date object using current date and time
                                                                $dt= $date->format('Y-m-d\TH:i'); 
                                                                echo "<input type='datetime-local' id='startdate_settings' name='start_date' value='$dt' class='form-control' required>";
                                                            ?>
                                                        </div>
                                                    </div>                         
                                                </div>
                                                <div class="col">
                                                    <div>
                                                        <label for="enddate_settings" class="form-label text-black">End Date</label>
                                                        <div class="input-group input-group-outline"> 
                                                            <?php
                                                                date_default_timezone_set('Asia/Manila');
                                                                $date = new DateTime(); // Date object using current date and time
                                                                $date->modify('+1 day');
                                                                $dt= $date->format('Y-m-d\TH:i'); 
                                                                echo "<input type='datetime-local' id='enddate_settings' name='end_date' value='$dt' class='form-control' required>";
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>   
                                            <div class="mb-3">
                                                <label for="timezone_settings" class="form-label text-black">Timezone</label>
                                                <select name="timezone" id="timezone_settings" class="form-select">
                                                    <option value="Asia/Manila" selected>(GMT+8:00) Metro Manila</option>
                                                    <option value="Etc/GMT+12">(GMT-12:00) International Date Line West</option>
                                                    <option value="Pacific/Midway">(GMT-11:00) Midway Island, Samoa</option>
                                                    <option value="Pacific/Honolulu">(GMT-10:00) Hawaii</option>
                                                    <option value="US/Alaska">(GMT-09:00) Alaska</option>
                                                    <option value="America/Los_Angeles">(GMT-08:00) Pacific Time (US & Canada)</option>
                                                    <option value="America/Tijuana">(GMT-08:00) Tijuana, Baja California</option>
                                                    <option value="US/Arizona">(GMT-07:00) Arizona</option>
                                                    <option value="America/Chihuahua">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                                                    <option value="US/Mountain">(GMT-07:00) Mountain Time (US & Canada)</option>
                                                    <option value="America/Managua">(GMT-06:00) Central America</option>
                                                    <option value="US/Central">(GMT-06:00) Central Time (US & Canada)</option>
                                                    <option value="America/Mexico_City">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
                                                    <option value="Canada/Saskatchewan">(GMT-06:00) Saskatchewan</option>
                                                    <option value="America/Bogota">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
                                                    <option value="US/Eastern">(GMT-05:00) Eastern Time (US & Canada)</option>
                                                    <option value="US/East-Indiana">(GMT-05:00) Indiana (East)</option>
                                                    <option value="Canada/Atlantic">(GMT-04:00) Atlantic Time (Canada)</option>
                                                    <option value="America/Caracas">(GMT-04:00) Caracas, La Paz</option>
                                                    <option value="America/Manaus">(GMT-04:00) Manaus</option>
                                                    <option value="America/Santiago">(GMT-04:00) Santiago</option>
                                                    <option value="Canada/Newfoundland">(GMT-03:30) Newfoundland</option>
                                                    <option value="America/Sao_Paulo">(GMT-03:00) Brasilia</option>
                                                    <option value="America/Argentina/Buenos_Aires">(GMT-03:00) Buenos Aires, Georgetown</option>
                                                    <option value="America/Godthab">(GMT-03:00) Greenland</option>
                                                    <option value="America/Montevideo">(GMT-03:00) Montevideo</option>
                                                    <option value="America/Noronha">(GMT-02:00) Mid-Atlantic</option>
                                                    <option value="Atlantic/Cape_Verde">(GMT-01:00) Cape Verde Is.</option>
                                                    <option value="Atlantic/Azores">(GMT-01:00) Azores</option>
                                                    <option value="Africa/Casablanca">(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>
                                                    <option value="Etc/Greenwich">(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>
                                                    <option value="Europe/Amsterdam">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                                                    <option value="Europe/Belgrade">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                                                    <option value="Europe/Brussels">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                                                    <option value="Europe/Sarajevo">(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
                                                    <option value="Africa/Lagos">(GMT+01:00) West Central Africa</option>
                                                    <option value="Asia/Amman">(GMT+02:00) Amman</option>
                                                    <option value="Europe/Athens">(GMT+02:00) Athens, Bucharest, Istanbul</option>
                                                    <option value="Asia/Beirut">(GMT+02:00) Beirut</option>
                                                    <option value="Africa/Cairo">(GMT+02:00) Cairo</option>
                                                    <option value="Africa/Harare">(GMT+02:00) Harare, Pretoria</option>
                                                    <option value="Europe/Helsinki">(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
                                                    <option value="Asia/Jerusalem">(GMT+02:00) Jerusalem</option>
                                                    <option value="Europe/Minsk">(GMT+02:00) Minsk</option>
                                                    <option value="Africa/Windhoek">(GMT+02:00) Windhoek</option>
                                                    <option value="Asia/Kuwait">(GMT+03:00) Kuwait, Riyadh, Baghdad</option>
                                                    <option value="Europe/Moscow">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
                                                    <option value="Africa/Nairobi">(GMT+03:00) Nairobi</option>
                                                    <option value="Asia/Tbilisi">(GMT+03:00) Tbilisi</option>
                                                    <option value="Asia/Tehran">(GMT+03:30) Tehran</option>
                                                    <option value="Asia/Muscat">(GMT+04:00) Abu Dhabi, Muscat</option>
                                                    <option value="Asia/Baku">(GMT+04:00) Baku</option>
                                                    <option value="Asia/Yerevan">(GMT+04:00) Yerevan</option>
                                                    <option value="Asia/Kabul">(GMT+04:30) Kabul</option>
                                                    <option value="Asia/Yekaterinburg">(GMT+05:00) Yekaterinburg</option>
                                                    <option value="Asia/Karachi">(GMT+05:00) Islamabad, Karachi, Tashkent</option>
                                                    <option value="Asia/Calcutta">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                                                    <option value="Asia/Calcutta">(GMT+05:30) Sri Jayawardenapura</option>
                                                    <option value="Asia/Katmandu">(GMT+05:45) Kathmandu</option>
                                                    <option value="Asia/Almaty">(GMT+06:00) Almaty, Novosibirsk</option>
                                                    <option value="Asia/Dhaka">(GMT+06:00) Astana, Dhaka</option>
                                                    <option value="Asia/Rangoon">(GMT+06:30) Yangon (Rangoon)</option>
                                                    <option value="Asia/Bangkok">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                                                    <option value="Asia/Krasnoyarsk">(GMT+07:00) Krasnoyarsk</option>
                                                    <option value="Asia/Hong_Kong">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                                                    <option value="Asia/Kuala_Lumpur">(GMT+08:00) Kuala Lumpur, Singapore</option>
                                                    <option value="Asia/Irkutsk">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
                                                    <option value="Australia/Perth">(GMT+08:00) Perth</option>
                                                    <option value="Asia/Taipei">(GMT+08:00) Taipei</option>
                                                    <option value="Asia/Tokyo">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
                                                    <option value="Asia/Seoul">(GMT+09:00) Seoul</option>
                                                    <option value="Asia/Yakutsk">(GMT+09:00) Yakutsk</option>
                                                    <option value="Australia/Adelaide">(GMT+09:30) Adelaide</option>
                                                    <option value="Australia/Darwin">(GMT+09:30) Darwin</option>
                                                    <option value="Australia/Brisbane">(GMT+10:00) Brisbane</option>
                                                    <option value="Australia/Canberra">(GMT+10:00) Canberra, Melbourne, Sydney</option>
                                                    <option value="Australia/Hobart">(GMT+10:00) Hobart</option>
                                                    <option value="Pacific/Guam">(GMT+10:00) Guam, Port Moresby</option>
                                                    <option value="Asia/Vladivostok">(GMT+10:00) Vladivostok</option>
                                                    <option value="Asia/Magadan">(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
                                                    <option value="Pacific/Auckland">(GMT+12:00) Auckland, Wellington</option>
                                                    <option value="Pacific/Fiji">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
                                                    <option value="Pacific/Tongatapu">(GMT+13:00) Nuku'alofa</option>
                                                </select> 
                                            </div>          
                                            <button type="submit" class="btn btn-primary w-100 disableButtonOnLaunch" name="save_changes_btn_set">Save Changes</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="list-voters" role="tabpanel" aria-labelledby="list-voters-list">
                                        <h3 class="text-black mb-5">Voters Registration Settings</h3>
                                        <h6 class="text-black mb-2">Registration Period</h6>
                                        <form action="<?= $page ?>" method="post" autocomplete="">
                                            <div class="row mt-3">
                                                <div class="col">
                                                    <div>
                                                        <label for="startdate_voterRegis" class="form-label text-black">Start Date</label>
                                                        <div class="input-group input-group-outline">
                                                            <input 
                                                                type='datetime-local' 
                                                                id='startdate_voterRegis' 
                                                                name='start-date_voterRegis' 
                                                                class='form-control' 
                                                                required
                                                                max='<?php echo $minDate; ?>'
                                                            >
                                                        </div>
                                                    </div>                         
                                                </div>
                                                <div class="col">
                                                    <div>
                                                        <label for="enddate_voterRegis" class="form-label text-black">End Date</label>
                                                        <div class="input-group input-group-outline">
                                                            <input 
                                                                type='datetime-local' 
                                                                id='enddate_voterRegis' 
                                                                name='end-date_voterRegis' 
                                                                class='form-control' 
                                                                required
                                                                max='<?php echo $minDate; ?>'
                                                            >
                                                        </div>                               
                                                    </div>
                                                </div>
                                            </div>   
                                            <div class="my-3">
                                                <label for="timezone_voterRegis" class="form-label text-black">Timezone</label>
                                                <div class="input-group input-group-outline">  
                                                    <input type="text" name="timezone-voterRegis" id="timezone_voterRegis" class="form-control" readonly>
                                                    <input type="hidden" name="voter_regisId" value="<?php echo isset($voter_recordId) ? $voter_recordId : ''; ?>">
                                                </div>
                                            </div>   
                                            <button type="submit" class="btn btn-primary w-100 mt-2 disableButtonOnLaunch" name="save_voterRegis_set">Save Settings</button>
                                        </form>
                                        <h6 class="text-black mt-3">Registration Link</h6>
                                        <div class="row d-flex justify-content-start">
                                            <div class="col-auto d-flex align-items-center">
                                                <button class="btn bg-primary mb-0" type="button" id="view_voterRegis"><i class="fa-solid fa-eye fw-bold me-2"></i>View Page</button>
                                            </div>
                                            <div class="col-8 text-end">
                                                <form action="" method="GET">
                                                    <div class="input-group input-group-outline my-3">
                                                        <input type="url" class="form-control" id="voterRegis_link" readonly>
                                                        <button type="button" id="copy_voterRegis_link" class="btn bg-primary mb-0">COPY</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="list-candidate" role="tabpanel" aria-labelledby="list-candidate-list">
                                        <h3 class="text-black mb-5">Candidate Registration Settings</h3>
                                        <h6 class="text-black mb-2">Registration Period</h6>
                                        <form action="<?= $page ?>" method="post" autocomplete="">
                                            <div class="row mt-3">
                                                <div class="col">
                                                    <div>
                                                        <label for="startdate_candidateRegis" class="form-label text-black">Start Date</label>
                                                        <div class="input-group input-group-outline">
                                                            <input 
                                                                type='datetime-local' 
                                                                id='startdate_candidateRegis' 
                                                                name='start-date_candidateRegis' 
                                                                class='form-control' 
                                                                required
                                                                max='<?php echo $minDate; ?>'
                                                            >
                                                        </div>
                                                    </div>                          
                                                </div>
                                                <div class="col">
                                                    <div>
                                                        <label for="enddate_candidateRegis" class="form-label text-black">End Date</label>
                                                        <div class="input-group input-group-outline">
                                                            <input 
                                                                type='datetime-local' 
                                                                id='enddate_candidateRegis' 
                                                                name='end-date_candidateRegis' 
                                                                class='form-control' 
                                                                required
                                                                max='<?php echo $minDate; ?>'
                                                            >
                                                        </div>                               
                                                    </div>
                                                </div>
                                            </div>   
                                            <div class="my-3">
                                                <label for="timezone_candidateRegis" class="form-label text-black">Timezone</label>
                                                <div class="input-group input-group-outline">  
                                                    <input type="text" name="timezone-candidateRegis" id="timezone_candidateRegis" class="form-control" readonly>
                                                    <input type="hidden" name="candidate_regisId" value="<?php echo isset($candidate_recordId) ? $candidate_recordId : ''; ?>">
                                                </div>
                                            </div>   
                                            <button type="submit" class="btn btn-primary w-100 mt-2 disableButtonOnLaunch" name="save_candidateRegis_set">Save Settings</button>
                                        </form>
                                        <h6 class="text-black mt-3">Registration Link</h6>
                                        <div class="row d-flex justify-content-start">
                                            <div class="col-auto d-flex align-items-center">
                                                <button class="btn bg-primary mb-0" type="button" id="view_CandidateRegis"><i class="fa-solid fa-eye fw-bold me-2"></i>View Page</button>
                                            </div>
                                            <div class="col-8 text-end">
                                                <form action="" method="GET">
                                                    <div class="input-group input-group-outline my-3">
                                                        <input type="url" class="form-control" id="candidateRegis_link" readonly>
                                                        <button type="button" id="copy_candidateRegis_link" class="btn bg-primary mb-0">COPY</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="list-ballot" role="tabpanel" aria-labelledby="list-ballot-list">
                                        <h3 class="text-black mb-3">Ballot Settings</h3>
                                        <div class="table-responsive p-0" style="overflow: auto !important; height: 50vh !important;">
                                            <table class="table align-items-center mb-0 table-fixed" id="positionsTable">
                                                <thead>
                                                    <tr>
                                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder">Description</th>
                                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder">Maximum Vote</th>
                                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        echo $output;
                                                    ?>                    
                                                </tbody>
                                            </table>
                                        </div>
                                        <h6 class="text-black mt-3">Ballot Link</h6>
                                        <div class="row d-flex justify-content-start">
                                            <div class="col-auto d-flex align-items-center">
                                                <button class="btn bg-primary mb-0" type="button" id="view_ballotSet"><i class="fa-solid fa-eye fw-bold me-2"></i>View Page</button>
                                            </div>
                                            <div class="col-8 text-end">
                                                <form action="" method="GET">
                                                    <div class="input-group input-group-outline my-3">
                                                        <input type="url" class="form-control" id="ballotSet_link" readonly>
                                                        <button type="button" id="copy_ballotSet_link" class="btn bg-primary mb-0">COPY</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="list-launch" role="tabpanel" aria-labelledby="list-launch-list">
                                        <h3 class="text-black mb-4">Launch Election</h3>
                                        <!--<div class="step-app" id="launch_election">
                                            <ul class="step-steps">
                                                <li data-step-target="step1">Confirm Details</li>
                                                <li data-step-target="step2">Check Ballot</li>
                                                <li data-step-target="step3">Launch Election</li>
                                            </ul>
                                            <div class="step-content">
                                                <div class="step-tab-panel" data-step="step1">
                                                    <table class="table table-hover table-bordered text-black" style="table-layout:fixed;">
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">Election Name</th>
                                                                <td><span id="launch_elecName"></span><i class="fa-regular fa-pen-to-square ms-2 link-primary launch_editGeneral" style="cursor:pointer"></i></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Start Date</th>
                                                                <td><span id="launch_startDate"></span><i class="fa-regular fa-pen-to-square ms-2 link-primary launch_editGeneral" style="cursor:pointer"></i></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">End Date</th>
                                                                <td><span id="launch_endDate"></span> <i class="fa-regular fa-pen-to-square ms-2 link-primary launch_editGeneral" style="cursor:pointer"></i></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Timezone</th>
                                                                <td><span id="launch_timezone"></span> <i class="fa-regular fa-pen-to-square ms-2 link-primary launch_editGeneral" style="cursor:pointer"></i></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="step-tab-panel" data-step="step2">
                                                    <table class="table table-hover table-bordered text-black" style="table-layout:fixed;">
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">Verified Candidates</th>
                                                                <td><span id="launch_verifiedCan"></span><i class="fa-regular fa-pen-to-square ms-2 link-primary launch_editCandidate" style="cursor:pointer"></i></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Unverified Candidates</th>
                                                                <td><span id="launch_unverifiedCan"></span><i class="fa-regular fa-pen-to-square ms-2 link-primary launch_editCandidate" style="cursor:pointer"></i></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Verified Voters</th>
                                                                <td><span id="launch_verifiedVoter"></span> <i class="fa-regular fa-pen-to-square ms-2 link-primary launch_editVoter" style="cursor:pointer"></i></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Unverified Voters</th>
                                                                <td><span id="launch_unverifiedVoter"></span> <i class="fa-regular fa-pen-to-square ms-2 link-primary launch_editVoter" style="cursor:pointer"></i></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Positions</th>
                                                                <td><span id="launch_positions"></span> <i class="fa-regular fa-pen-to-square ms-2 link-primary launch_editPosition" style="cursor:pointer"></i></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="step-tab-panel" data-step="step3">
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <img src="../assets/images/Launching-cuate.svg" alt="" class="img-fluid">
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <h1 class="text-primary mt-2">Launch Election</h1>
                                                            <p class="text-black">When this election is lauch all editing of candidates, voters, and positions will be disable. Please finalize all details to avoid complication.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="step-footer">
                                                <button data-step-action="prev" class="step-btn">Previous</button>
                                                <button data-step-action="next" class="step-btn">Next</button>
                                                <button data-step-action="finish" class="disableButtonOnLaunch step-btn" id="launch_btn">Launch</button>
                                            </div>
                                        </div>-->
                                        <!-- SmartWizard html -->
                                        <div id="smartwizard" class="rounded-bottom">
                                            <ul class="nav">
                                                <li class="nav-item">
                                                  <a class="nav-link" href="#step-1">
                                                    <div class="num">1</div>
                                                    Confirm Details
                                                  </a>
                                                </li>
                                                <li class="nav-item">
                                                  <a class="nav-link" href="#step-2">
                                                    <span class="num">2</span>
                                                    Check Ballot
                                                  </a>
                                                </li>
                                                <li class="nav-item">
                                                  <a class="nav-link" href="#step-3">
                                                    <span class="num">3</span>
                                                    Launch Election
                                                  </a>
                                                </li>
                                            </ul>
                                         
                                            <div class="tab-content">
                                                <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
                                                    <table class="table table-hover table-bordered text-black mt-3" style="table-layout:fixed;">
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">Election Name</th>
                                                                <td><span id="launch_elecName"></span><i class="fa-regular fa-pen-to-square ms-2 link-primary launch_editGeneral" style="cursor:pointer"></i></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Start Date</th>
                                                                <td><span id="launch_startDate"></span><i class="fa-regular fa-pen-to-square ms-2 link-primary launch_editGeneral" style="cursor:pointer"></i></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">End Date</th>
                                                                <td><span id="launch_endDate"></span> <i class="fa-regular fa-pen-to-square ms-2 link-primary launch_editGeneral" style="cursor:pointer"></i></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Timezone</th>
                                                                <td><span id="launch_timezone"></span> <i class="fa-regular fa-pen-to-square ms-2 link-primary launch_editGeneral" style="cursor:pointer"></i></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                                                    <table class="table table-hover table-bordered text-black mt-3" style="table-layout:fixed;">
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">Verified Candidates</th>
                                                                <td><span id="launch_verifiedCan">Candidates</span><i class="fa-regular fa-pen-to-square ms-2 link-primary launch_editCandidate" style="cursor:pointer"></i></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Unverified Candidates</th>
                                                                <td><span id="launch_unverifiedCan">Candidates</span><i class="fa-regular fa-pen-to-square ms-2 link-primary launch_editCandidate" style="cursor:pointer"></i></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Verified Voters</th>
                                                                <td><span id="launch_verifiedVoter">Voters</span> <i class="fa-regular fa-pen-to-square ms-2 link-primary launch_editVoter" style="cursor:pointer"></i></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Unverified Voters</th>
                                                                <td><span id="launch_unverifiedVoter">Voters</span> <i class="fa-regular fa-pen-to-square ms-2 link-primary launch_editVoter" style="cursor:pointer"></i></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Positions</th>
                                                                <td><span id="launch_positions">Positions</span> <i class="fa-regular fa-pen-to-square ms-2 link-primary launch_editPosition" style="cursor:pointer"></i></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
                                                    <div class="row mt-3">
                                                        <div class="col-lg-4">
                                                            <img src="../assets/images/Launching-cuate.svg" alt="" class="img-fluid">
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <h1 class="text-primary mt-2">Launch Election</h1>
                                                            <p class="text-black">When this election is lauch all editing of candidates, voters, and positions will be disable. Please finalize all details to avoid complication.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                        
                                    </div>
                                    <div class="tab-pane fade" id="list-delete" role="tabpanel" aria-labelledby="list-delete-list">
                                        <h3 class="text-black mb-5">Delete Election</h3>
                                        <p class="text-black fs-5">
                                            This election is part of a content pack. If you delete this election, you will delete the
                                            entire content pack including all data in this dashboard and election reports. 
                                        </p>
                                        <button type="button" id="delete_election_confirm" class="disableButtonOnLaunch_delete btn btn-primary mt-2">DELETE ELECTION</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Display the list group after the page has loaded
            document.querySelector('.list-group').style.display = 'block';

            // Retrieve the last active tab from localStorage
            var lastActiveTab = localStorage.getItem('lastActiveTab');
            
            // Activate the last active tab, if available
            if (lastActiveTab) {
                var tabElement = document.getElementById('list-' + lastActiveTab + '-list');
                if (tabElement) {
                    var tab = new bootstrap.Tab(tabElement);
                    tab.show();
                }
            }

            // Save the active tab to localStorage when a tab is shown
            var tabs = document.querySelectorAll('.list-group-item');
            tabs.forEach(function (tab) {
                tab.addEventListener('shown.bs.tab', function (event) {
                    var tabId = event.target.getAttribute('id').replace('list-', '').replace('-list', '');
                    localStorage.setItem('lastActiveTab', tabId);
                });
            });
        });
    </script>    
<?php include("../includes/footer.php");?>
<script src="../assets/js/material-dashboard.js"></script>
<script src="../assets/js/jquery-steps.js"></script>
<script src="../assets/js/script.js"></script>
<script>
    $(document).ready(function() {
        /* $("#launch_election").steps({
            onFinish: function () {
                launchElection();
            },
            onChange: function (event, currentIndex, newIndex) {
                // Check date validation before proceeding to the next step
                if (currentIndex > 0) { // Assuming date validation is required before moving from step 1 to step 2
                    if (!validateDate()) {
                        pushNotify("error", "Please enter a start date in the future.")
                        return false; // Prevent moving to the next step
                    }
                }
                return true; // Allow moving to the next step
            }
        });*/
        
        $('#smartwizard').smartWizard({
              selected: 0, // Initial selected step, 0 = first step
              theme: 'arrows', // theme for the wizard, related css need to include for other than default theme
              justified: true, // Nav menu justification. true/false
              autoAdjustHeight: true, // Automatically adjust content height
              backButtonSupport: true, // Enable the back button support
              enableUrlHash: true, // Enable selection of the step based on url hash
              transition: {
                  animation: 'slideHorizontal', // Animation effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
                  speed: '500', // Animation speed. Not used if animation is 'css'
                  easing: '', // Animation easing. Not supported without a jQuery easing plugin. Not used if animation is 'css'
                  prefixCss: '', // Only used if animation is 'css'. Animation CSS prefix
                  fwdShowCss: '', // Only used if animation is 'css'. Step show Animation CSS on forward direction
                  fwdHideCss: '', // Only used if animation is 'css'. Step hide Animation CSS on forward direction
                  bckShowCss: '', // Only used if animation is 'css'. Step show Animation CSS on backward direction
                  bckHideCss: '', // Only used if animation is 'css'. Step hide Animation CSS on backward direction
              },
              toolbar: {
                  position: 'bottom', // none|top|bottom|both
                  showNextButton: true, // show/hide a Next button
                  showPreviousButton: false, // show/hide a Previous button
                  extraHtml: '' // Extra html to show on toolbar
              },
              anchor: {
                  enableNavigation: true, // Enable/Disable anchor navigation 
                  enableNavigationAlways: false, // Activates all anchors clickable always
                  enableDoneState: true, // Add done state on visited steps
                  markPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
                  unDoneOnBackNavigation: true, // While navigate back, done state will be cleared
                  enableDoneStateNavigation: true // Enable/Disable the done state navigation
              },
              keyboard: {
                  keyNavigation: true, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
                  keyLeft: [37], // Left key code
                  keyRight: [39] // Right key code
              },
              lang: { // Language variables for button
                  next: 'NEXT',
                  previous: 'PREVIOUS'
              },
              disabledSteps: [], // Array Steps disabled
              errorSteps: [], // Array Steps error
              warningSteps: [], // Array Steps warning
              hiddenSteps: [], // Hidden steps
              getContent: null // Callback function for content loading
        });
        
        // Initialize the leaveStep event
        $("#smartwizard").on("leaveStep", function(e, anchorObject, currentStepIndex, nextStepIndex, stepDirection) {
            var status = $.trim($("#election-status").text());
            if (stepDirection == 'forward' && status === 'building') {
                if (currentStepIndex === 0) { // Assuming date validation is required before moving from step 1 to step 2
                    if (!validateDate()) {
                        pushNotify("error", "Please enter a start date in the future.")
                        return false; // Prevent moving to the next step
                    }
                } else if (currentStepIndex === 1) { // Assuming date validation is required before moving from step 1 to step 2
                    if (!validateBallot()) {
                        pushNotify("error", "Please provide the ballot details")
                        return false; // Prevent moving to the next step
                    }
                }
            }
            
            return true; // Allow moving to the next step
        });
        
        $('#smartwizard').on('showStep', function (e, anchorObject, stepIndex, stepDirection) {
            if (stepIndex === 0) {
                if (stepDirection == 'backward') {
                    var options = {
                        toolbar: {
                            showPreviousButton: false, // show/hide a Previous button
                            showNextButton: true,
                            extraHtml: ``
                        }
                    };
                }
                $('#smartwizard').smartWizard("setOptions", options);
                $('.toolbar').css('justify-content', 'flex-end');
            } else if (stepIndex === 1) {
                var options = {
                    toolbar: {
                        showPreviousButton: true, // show/hide a Previous button
                        showNextButton: true,
                        extraHtml: ``
                    }
                };   
                $('#smartwizard').smartWizard("setOptions", options);
                $('.toolbar').css('justify-content', 'space-between');
            } else if (stepIndex === 2) {
                var options = {
                    toolbar: {
                        showPreviousButton: true, // show/hide a Previous button
                        showNextButton: false, // show/hide a Next button
                        extraHtml: `<p class="sw-btn-finish" id="launch_election">LAUNCH</p>`
                    }
                };
                $('#smartwizard').smartWizard("setOptions", options);
                $('.toolbar').css('justify-content', 'space-between');
            }
        });
        
        $(document).on('click', '#launch_election', function() {
            launchElection();
        });
        
        function launchElection() {
            $('#smartwizard').smartWizard("loader", "show");
            $.ajax({
                type: "POST",
                url: baseUrl + "config/controllerUserData.php",
                data: {action:"launch-election"},
                dataType: 'json',
                success: function(response) {
                    // Assuming the response contains the updated status
                    pushNotify("success", "Election successfully launched.")
                    $('#election-status').text(response.status);
                    $('#launch_btn').prop('disabled', true);
                    
                    $('#smartwizard').smartWizard("goToStep", 2, true);
                    $('#smartwizard').smartWizard("setState", [0,1], "disable");
                    $('#smartwizard').smartWizard("loader", "hide");
                    
                    var options = {
                      toolbar: {
                        showNextButton: false, // show/hide a Next button
                        showPreviousButton: false, // show/hide a Previous button
                        position: 'none', // none|top|bottom|both
                        extraHtml: ``
                      }
                    };
                    $('#smartwizard').smartWizard("setOptions", options);
    
                    var startDate = new Date(response.start_date).getTime();
                    var endDate = new Date(response.end_date).getTime();
                    var currentDate = new Date().getTime();
                    var runningTimeDif = startDate - currentDate;
                    var completedTimeDif = endDate - currentDate;
                    var election_id = response.election_id;
    
                    // Check if the current time is within the specified range
                    if (runningTimeDif > 0) {
                        // Schedule the function to run after the calculated time difference
                        setTimeout(function() {
                            updateRunning(election_id);
                        }, runningTimeDif);
                    } else {
                        updateRunning(election_id);
                    }
                    if (completedTimeDif > 0) {
                        // Schedule the function to run after the calculated time difference
                        setTimeout(function() {
                            updateCompleted(election_id);
                        }, completedTimeDif);
                    } else {
                        updateCompleted(election_id);
                    }               
                },
                error: function(error) {
                    console.error(error);
                    $('#smartwizard').smartWizard("loader", "hide");
                }
            })
        }
        
        function validateBallot() {
            var voters = $.trim($("#launch_verifiedVoter").text());
            var candidate = $.trim($("#launch_verifiedCan").text());
            var position = $.trim($("#launch_positions").text());
            
            if (voters == '0' || candidate == '0' || position == '0') {
                return false;
            } else {
                return true;
            }
        }
    
        function validateDate() {
             // Get the value of datetime-local input
            var inputDateValue = $("#startdate_settings").val();
            
            // Parse the input date string to a Date object
            var inputDate = new Date(inputDateValue).getTime();
    
            // Get the current date
            var currentDate = new Date().getTime();
    
            // Compare the input date to the current date
            if (inputDate < currentDate) {
                return false; // Date validation failed
            }
    
            return true; // Date validation passed
        }

    
        function updateRunning(election_id) {
            $('#smartwizard').smartWizard("loader", "show");
            $.ajax({
                type: "POST",
                url: baseUrl + "config/controllerUserData.php",
                data: {action:"update-status-running", election_id: election_id},
                dataType: "json",
                success: function (response) {
                    pushNotify("success", "Election successfully running.")
                    $('#smartwizard').smartWizard("loader", "hide");
                    $('#election-status').text(response.status);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);
                    $('#smartwizard').smartWizard("loader", "hide");
                }
            });
        }
    
        function updateCompleted(election_id ) {
            $.ajax({
                type: "POST",
                url: baseUrl + "config/controllerUserData.php",
                data: {action:"update-status-completed", election_id: election_id},
                dataType: "json",
                success: function (response) {
                    pushNotify("success", "Election successfully completed.")
                    $('#election-status').text(response.status);
                }
            });
        }
    });
</script>
</body>
</html>