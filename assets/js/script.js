$(document).ready(function(){
    //owl carousel
    $('.owl-carousel').owlCarousel({
        loop:true,
        touchDrag  : false,
        mouseDrag  : false,
        responsive:{
        0:{
            items:1
        },
        600:{
            items:1
        },
        1000:{
            items:1
        }
        }
    });

    var owl = $('.owl-carousel');
    owl.owlCarousel();

    // Go to the next item
    $('.customNextBtn').click(function() {
        owl.trigger('next.owl.carousel');
    });
    // Go to the previous item
    $('.customPrevBtn').click(function() {
        owl.trigger('prev.owl.carousel', [300]);
    });

    $(document).on('click', '#signout', function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {action:"logout"},
            success: function () {     
                $( "#navbarSupportedContent" ).load(window.location.href + " #navbarSupportedContent" );      
                $( "#election-list" ).load(window.location.href + " #election-list" );   
                pushNotify("success", "Successfully signout. We will miss you.");
            }
        });
    });

    $(document).on('click', '#resend-otp', function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {action:"resend-otp"},
            success: function (response) {
                if(response == "success"){
                    pushNotify("success", "Successfully sent a new verification code.");
                }else if(response == "failedtoupdate"){
                    pushNotify("error", "Failed to update data.");
                }else if(response == "failedtosend"){
                    pushNotify("error", "Failed to send a new otp.");
                }
            }
        });
    });

    $(document).on('click', '#homecreateElection', function() {
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {action:"createElection"},
            success: function (response) {
                if(response == "signout"){
                    pushNotify('error', 'Please login your account to continue.');
                    $(document).ready(function(){
                        $('#login').modal('show');
                    });
                }else if(response == "verified"){
                    $(document).ready(function(){
                        $('#create-election').modal('show');
                    });
                } else if(response == "unverified"){
                    pushNotify('error', 'Please verify your account to continue.');
                    $(document).ready(function(){
                        $('#otp').modal('show');
                    });
                } else {
                    pushNotify('error', 'Something went wrong.');
                }
            }
        });
    });

    $(document).on('click', '#create-electionBtn', function() {
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {action:"createElection"},
            success: function (response) {
                if(response == "signout"){
                    pushNotify("error", "Please login your account to continue.");
                    $(document).ready(function(){
                        $('#login').modal('show');
                    });
                }else if(response == "verified"){
                    $(document).ready(function(){
                        $('#create-election').modal('show');
                    });
                } else if(response == "unverified"){
                    pushNotify("error", "Please verify your account to continue.");
                    $(document).ready(function(){
                        $('#otp').modal('show');
                    });
                } else {
                    pushNotify("error", "Something went wrong.");
                }
            }
        });
    });
    
    $(document).on('click', '.election', function() {
        var dataId = $(this).attr("data-election-id");
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {dataId:dataId},
            success: function (response) {
                if(response == "success") {
                    window.location.href = baseUrl + "election-panel/index.php";
                }else {
                    pushNotify("error", "Something went wrong.");
                }                
            }
        });
    });

    $(document).on('click', '#home_vote', function() {
        var code = $('#code_input' ).val(); 
        if(code.trim() == '' ) {
            pushNotify("error", "Please enter the code.");
        }else{
            $.ajax({
                type: "POST",
                url: baseUrl + "config/controllerUserData.php",
                data: {election_code:code},
                success: function (response) {
                    if(response == 'success') {
                        window.open(baseUrl + "voters/index.php?code=" + code, "_blank");
                    }else{
                        pushNotify("error", "Election code does not exist.");
                    }
                }
            });
        }
    });

    $(document).on('change', '#status_select', function() {
        var data_val = $(this).find(":selected").text().toLowerCase();

        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {status_val:data_val},
            success: function (response) {
                $('#election-list').html(response);
            }
        });
    });

    $(document).on('click', '#signout_panel', function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {action:"logout"},
            success: function () {      
                window.location.href = baseUrl + "election-panel/index.php";
            }
        });
    });

    $(document).on('click', '#copy_link', function() {
        var clipboardText = "";
        clipboardText = $( '#election_link' ).val(); 
        copyToClipboard( clipboardText );
        pushNotify();
                  function pushNotify() {
                      new Notify({
                          status: 'success',
                          title: 'Copied to Clipboard',
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
    });

    $(document).on('click', '#copy_code', function() {
        var clipboardText = "";
        clipboardText = $('#election_code' ).val(); 
        copyToClipboard( clipboardText );
        pushNotify();
                  function pushNotify() {
                      new Notify({
                          status: 'success',
                          title: 'Copied to Clipboard',
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
    });
    
    load_data();
    load_launchData();

    function load_data() {
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {action:"load-data"},
            dataType: 'json',
            success: function (response) {
                $("#election-name").html(response.electionTable.name);
                $("#election-status").html(response.electionTable.status);
                $("#start-date").html(response.electionTable.start_date);
                $("#end-date").html(response.electionTable.end_date);
                $("#election_link").val(baseUrl + "voters/index.php?code=" + response.electionTable.code);
                $("#election_code").val(response.electionTable.code);
                $("#election_name_settings").val(response.electionTable.name);
                $("#startdate_settings").val(moment(new Date(response.electionTable.start_date)).format('YYYY-MM-DDTHH:mm'));
                $("#enddate_settings").val(moment(new Date(response.electionTable.end_date)).format('YYYY-MM-DDTHH:mm'));
                $("#timezone_settings").val(response.electionTable.timezone);
                $("#launch_elecName").html(response.electionTable.name);
                $("#launch_startDate").html(response.electionTable.start_date);
                $("#launch_endDate").html(response.electionTable.end_date);
                $("#launch_timezone").html(response.electionTable.timezone);
                $("#voterRegis_link").val(baseUrl + "voter-registration/index.php?code=" + response.electionTable.code);
                $("#startdate_voterRegis").val(moment(new Date(response.voterRegistration.voterRegis_startDate)).format('YYYY-MM-DDTHH:mm'));
                $("#enddate_voterRegis").val(moment(new Date(response.voterRegistration.voterRegis_endDate)).format('YYYY-MM-DDTHH:mm'));
                $("#timezone_voterRegis").val(response.electionTable.timezone);
                $("#candidateRegis_link").val(baseUrl + "candidate-registration/index.php?code=" + response.electionTable.code);
                $("#startdate_candidateRegis").val(moment(new Date(response.candidateRegistration.candidateRegis_startDate)).format('YYYY-MM-DDTHH:mm'));
                $("#enddate_candidateRegis").val(moment(new Date(response.candidateRegistration.candidateRegis_endDate)).format('YYYY-MM-DDTHH:mm'));
                $("#timezone_candidateRegis").val(response.electionTable.timezone);
                $("#ballotSet_link").val(baseUrl + "voters/index.php?code=" + response.electionTable.code);

                if (response.electionTable.status !== "building") {
                    $('.disableButtonOnLaunch').prop('disabled', true);
                    $('.disableButtonOnLaunch_delete').prop('disabled', true);
                    
                    var wizard = document.getElementById('smartwizard');
                    
                    if(wizard) {
                        $('#smartwizard').smartWizard("setState", [0,1], "disable");
                        $('#smartwizard').smartWizard("goToStep", 2, true);
                        var options = {
                          toolbar: {
                            showNextButton: false, // show/hide a Next button
                            showPreviousButton: false, // show/hide a Previous button
                            position: 'none', // none|top|bottom|both
                            extraHtml: ``
                          }
                        };
                        $('#smartwizard').smartWizard("setOptions", options);
                    }
                } 
                
            },
            error: function(error) {
              console.error(error);
            }
        });
    }

    function load_launchData() {
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {action: "load_launchData"},
            dataType: "json",
            success: function(response) {
                // Now you can access the counts like this:
                $("#launch_verifiedCan").html(response.verified_candidates_count + " ");
                $("#launch_unverifiedCan").html(response.unverified_candidates_count + " ");
                $("#launch_verifiedVoter").html(response.verified_voters_count + " ");
                $("#launch_unverifiedVoter").html(response.unverified_voters_count + " ");
                $("#launch_positions").html(response.position_count + " ");
                // Use the counts as needed in your application
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    }
    
    $(document).on('click', '#view_moreBtn', function() {
        window.location.href = 'results.php';
    });

    $(document).on('click', '.account_settings', function(e) {
        $('#user_account').modal('show'); 
    });

    $(document).on('shown.bs.modal','#user_account', function () {
        var dataId = $(".account_settings").attr("data-user-id");

        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {userEmail:dataId},
            dataType: "json",
            success: function (response) {
                $("#user_Name_edit").val(response.name);
                $("#user_emailadd_edit").val(response.email);
                $("#user_image_edit_preview").attr('src', baseUrl + 'uploads/' + response.user_photo);
            }
        });
    });

    $(document).on('shown.bs.modal', '#verify_voter', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var voterId = button.attr('voterid'); 
        updateModalVoter(voterId);
    });
    
    $(document).on('click', '#verify_voterBtn', function() {
        var voterId = $('#verify_voterBtn').val();
        
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {verify_data: voterId },
            success: function (response) {
                updateVerifiedVoterTableData();
                updateUnverifiedVoterTableData();
                updateBadgeCountVoter();
                $('#verify_voter').modal('hide');
                pushNotify("success", "Voter successfully verified.");
            }, error: function (xhr, status, error) {
                // Handle the error if needed
                console.error('Error: ' + status + ' - ' + error);
            }
        
        });
    });
    
    function updateModalVoter(voterId) {
        $.ajax({
            type: "GET",
            url: baseUrl + "config/controllerUserData.php",
            data: {verify_voterId: voterId },
            dataType: "json",
            success: function (response) {
                $('#verify_voterName').val(response.name);
                $('#verify_voterSection').val(response.section);
                $('#verify_voterIdNumber').val(response.id_number);
                $('#verify_voterBtn').val(response.voter_id);
                $('#verify_voterImage').attr('src', response.voter_image);
            }
        });
    }

    $(document).on('shown.bs.modal', '#verify_candidate', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var candidateId = button.attr('candidateid'); 
        
        $.ajax({
            type: "GET",
            url: baseUrl + "config/controllerUserData.php",
            data: {verify_candidateId: candidateId },
            dataType: "json",
            success: function (response) {
                $('#verify_candidateName').val(response.name);
                $('#verify_candidateSection').val(response.section);
                $('#verify_candidateIdNumber').val(response.id_number);
                $('#verify_candidateBtn').val(response.candidate_id);
                $('#verify_candidateImage').attr('src', response.candidate_image);
            }
        });
    });

    $(document).on('click', '#verify_candidateBtn', function() {
        var candidateId = $('#verify_candidateBtn').val();

        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {verify_data_candidate: candidateId },
            success: function (response) {
                $('#verify_candidate').modal('hide');
                pushNotify("success", "Candidate successfully verified.");
                updateVerifiedCandidateTableData();
                updateUnverifiedCandidateTableData();
                updateBadgeCountCandidate();
            }, error: function (xhr, status, error) {
                // Handle the error if needed
                console.error('Error: ' + status + ' - ' + error);
            }
        
        });
    });

    function updateVerifiedVoterTableData() {
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {action: 'updateVoterTableData'},
            dataType: "json",
            success: function(response) {
                // Handle the response data
            if (response.length > 0) {
                // Build the HTML for the updated table
                var updatedTableData = '';
                for (var i = 0; i < response.length; i++) {
                    updatedTableData += `
                        <tr>
                            <td><h6 class="mb-0 text-sm">${response[i].name}</h6></td>
                            <td><h6 class="mb-0 text-sm">${response[i].section}</h6></td>
                            <td colspan="2"><h6 class="mb-0 text-sm">${response[i].email}</h6></td>
                            <td><h6 class="mb-0 text-sm">${response[i].id_number}</h6></td>
                            <td><h6 class="mb-0 text-sm">${response[i].registration_timestamp}</h6></td>
                            <td class="text-sm"><span class="badge bg-primary text-light text-bold"><i class="fa-solid fa-circle-check me-1"></i>Verified</span></td>
                        </tr>
                    `;
                }

                // Update the content of the table dynamically
                $('#voters_table tbody').html(updatedTableData);
            } else {
                // Handle the case when there is no data
                alert("No data received from the server");
            }
            },
            error: function(xhr, status, error) {
                // Handle the error if needed
                console.error('Error: ' + status + ' - ' + error);
                alert("Failed to update table. Please try again.");
            }
        });
    }

    function updateUnverifiedVoterTableData() {
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {action: 'updateUnverifiedVoterTableData'},
            dataType: "json",
            success: function(response) {
                // Handle the response data
            if (response.length > 0) {
                // Build the HTML for the updated table
                var updatedTableData = '';
                for (var i = 0; i < response.length; i++) {
                    updatedTableData += `
                        <tr>
                            <td><h6 class="mb-0 text-sm">${response[i].name}</h6></td>
                            <td><h6 class="mb-0 text-sm">${response[i].section}</h6></td>
                            <td colspan="2"><h6 class="mb-0 text-sm">${response[i].email}</h6></td>
                            <td><h6 class="mb-0 text-sm">${response[i].id_number}</h6></td>
                            <td><h6 class="mb-0 text-sm">${response[i].registration_timestamp}</h6></td>
                            <td><a role="button" voterid="${response[i].voter_id}" class="text-xs text-primary text-bold" data-bs-target="#verify_voter" data-bs-toggle="modal" data-bs-dismiss="modal">VERIFY</a></td>
                        </tr>
                    `;
                }

                // Update the content of the table dynamically
                $('#unverifiedVotersTable tbody').html(updatedTableData);
            } else {
                // Handle the case when there is no data
                $('#unverifiedVotersTable tbody').empty();
                // Add a row indicating "No Unverified Voter"
                var noDataHtml = '<tr>' +
                                    '<td colspan="7">' +
                                        '<h6 class="mb-0 text-md text-center pt-3">No Unverified Voter.</h6>' +
                                    '</td>' +
                                '</tr>';
                $('#unverifiedVotersTable tbody').append(noDataHtml);
            }
            },
            error: function(xhr, status, error) {
                // Handle the error if needed
                console.error('Error: ' + status + ' - ' + error);
                alert("Failed to update table. Please try again.");
            }
        });
    }

    function updateBadgeCountVoter() {
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php", // Replace with the actual path
            data: { action: "getUnverifiedVoterCount" }, // Include any additional data needed for the server-side script
            dataType: "json",
            success: function(response) {
                // Update the badge count
                $('#unverifiedVoterBadge').text(response.rowCount);
                // Show or hide the badge based on the count
                if (response.rowCount > 0) {
                    $('#unverifiedVoterBadge').show();
                } else {
                    $('#unverifiedVoterBadge').hide();
                }
            },
            error: function(xhr, status, error) {
                // Handle the error if needed
                console.error('Error: ' + status + ' - ' + error);
                console.log('Response Text:', xhr.responseText);
            }
        });
    }

    function updateVerifiedCandidateTableData() {
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {action: 'updateCandidateTableData'},
            dataType: "json",
            success: function(response) {
                // Handle the response data
            if (response.length > 0) {
                // Build the HTML for the updated table
                var updatedTableData = '';
                for (var i = 0; i < response.length; i++) {
                    updatedTableData += `
                        <tr>
                            <td>
                                <div class="d-flex py-1">
                                    <div>
                                        <img src="${response[i].image}" class="avatar-sm me-3 rounded">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">${response[i].name}</h6>
                                        <p class="text-xs text-black mb-0">${response[i].email}</p>
                                    </div>
                                </div>
                            </td>
                            <td><h6 class="mb-0 text-sm">${response[i].section}</h6></td>
                            <td><h6 class="mb-0 text-sm">${response[i].id_number}</h6></td>
                            <td><h6 class="mb-0 text-sm">${response[i].position_desc}</h6></td>
                            <td>
                                <a role="button" data-candidate-id="${response[i].candidate_id}" class="view_platform_btn text-xs text-primary text-bold">
                                    <i class="fa-solid fa-eye me-1"></i>VIEW
                                </a>
                            </td>
                            <td><h6 class="mb-0 text-sm">${response[i].registration_timestamp}</h6></td>
                            <td class="text-sm">
                                <span class="badge bg-primary text-light text-bold"><i class="fa-solid fa-circle-check me-1"></i>Verified</span>
                            </td>
                        </tr>
                    `;
                }

                // Update the content of the table dynamically
                $('#candidates_table tbody').html(updatedTableData);
            } else {
                // Handle the case when there is no data
                alert("No data received from the server");
            }
            },
            error: function(xhr, status, error) {
                // Handle the error if needed
                console.error('Error: ' + status + ' - ' + error);
                alert("Failed to update table. Please try again.");
            }
        });
    }

    function updateUnverifiedCandidateTableData() {
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {action: 'updateUnverifiedCandidateTableData'},
            dataType: "json",
            success: function(response) {
                // Handle the response data
            if (response.length > 0) {
                // Build the HTML for the updated table
                var updatedTableData = '';
                for (var i = 0; i < response.length; i++) {
                    updatedTableData += `
                        <tr>
                            <td>
                                <div class="d-flex py-1">
                                    <div>
                                        <img src="${response[i].image}" class="avatar-sm me-3 rounded">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">${response[i].name}</h6>
                                        <p class="text-xs text-black mb-0">${response[i].email}</p>
                                    </div>
                                </div>
                            </td>
                            <td><h6 class="mb-0 text-sm">${response[i].section}</h6></td>
                            <td><h6 class="mb-0 text-sm">${response[i].id_number}</h6></td>
                            <td><h6 class="mb-0 text-sm">${response[i].position_desc}</h6></td>
                            <td>
                                <a role="button" data-candidate-id="${response[i].candidate_id}" class="view_platform_btn text-xs text-primary text-bold">
                                    <i class="fa-solid fa-eye me-1"></i>VIEW
                                </a>
                            </td>
                            <td><h6 class="mb-0 text-sm">${response[i].registration_timestamp}</h6></td>
                            <td>
                                <a role="button" candidateid="${response[i].candidate_id}" class="text-xs text-primary text-bold" data-bs-target="#verify_candidate" data-bs-toggle="modal" data-bs-dismiss="modal">
                                  VERIFY
                                </a>
                            </td>
                        </tr>
                    `;
                }

                // Update the content of the table dynamically
                $('#unverifiedCandidatesTable tbody').html(updatedTableData);
            } else {
                // Handle the case when there is no data
                // Handle the case when there is no data
                $('#unverifiedCandidatesTable tbody').empty();
                // Add a row indicating "No Unverified Voter"
                var noDataHtml = '<tr>' +
                                    '<td colspan="6">' +
                                        '<h6 class="mb-0 text-md text-center pt-3">No Unverified Candidate.</h6>' +
                                    '</td>' +
                                '</tr>';
                $('#unverifiedCandidatesTable tbody').append(noDataHtml);
            }
            },
            error: function(xhr, status, error) {
                // Handle the error if needed
                console.error('Error: ' + status + ' - ' + error);
                alert("Failed to update table. Please try again.");
            }
        });
    }

    function updateBadgeCountCandidate() {
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php", // Replace with the actual path
            data: { action: "getUnverifiedCandidateCount" }, // Include any additional data needed for the server-side script
            dataType: "json",
            success: function(response) {
                // Update the badge count
                $('#unverifiedCandidateBadge').text(response.rowCount);
                // Show or hide the badge based on the count
                if (response.rowCount > 0) {
                    $('#unverifiedCandidateBadge').show();
                } else {
                    $('#unverifiedCandidateBadge').hide();
                }
            },
            error: function(xhr, status, error) {
                // Handle the error if needed
                console.error('Error: ' + status + ' - ' + error);
            }
        });
    }

    $(document).on('click', '.view_platform_btn', function() {
        var dataId = $(this).attr("data-candidate-id");
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {candidateId:dataId},
            dataType: "json",
            success: function (response) {
                $('#platform-name').html(response.name);
                $('#platform-position').html("Running for " + response.position_desc);
                $('#platform-content').html(response.platform);
                $("#platform-img").attr("src", response.candidate_image_path);               
            }
            
        });     
        $('#view-platform').modal('show');   
    });

    $(document).on('click', '#view_editvoters_modal', function() {
        var dataId = $(this).attr("data-voters-id");
        
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {votersId_edit:dataId},
            dataType: "json",
            success: function (response) {
                $('#voters-name-edit').val(response.voter_name);
                $('#voters-email-edit').val(response.voter_email);   
                $('#voters-save-btn').val(response.voter_userid);
            }            
        });     

        $('#edit-voters').modal('show');   
    });

    $(document).on('click', '#view_confirm_modal', function() {
        var dataId = $(this).attr("data-voters-id");

        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {show_modal_delete:dataId},
            dataType: "json",
            success: function (response) {
                $('#voter_name_dialog').html("Are you sure you want to delete " + response.voter_name + "?");
                $('#delete_voter_btn').val(response.voter_userid);
            }
        });

        $('#confirm-delete-modal').modal('show'); 
    });
    $(document).on('click', '#view-edit-position-modal', function() {
        var dataId = $(this).attr("data-position-id");

        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {show_modal_position:dataId},
            dataType: "json",
            success: function (response) {
                $('#positions-edit-input').val(response.position_desc);
                $('#positions-max-vote').val(response.maximum_vote);   
                $('#positions-max-can').val(response.maximum_candidate);  
                $('#positions-edit-save').val(response.position_id);
            }
        });

        $('#edit-position').modal('show'); 
    });

    $(document).on('click', '#view_delete_position_btn', function() {
        var dataId = $(this).attr("data-position-id");

        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {show_modal_delete_position:dataId},
            dataType: "json",
            success: function (response) {
                $('#position_name_dialog').html("Are you sure you want to delete " + response.position_desc + "?");
                $('#delete_position_btn').val(response.position_id);
            }
        });
        $('#confirm-delete-modal-position').modal('show'); 
    });

    $(document).on('hidden.bs.modal','#edit-candidate', function () {
        $('#edit-image-candidate').val(null);
    });

    $(document).on('hidden.bs.modal','#user_account', function () {
        $('#edit-image-user').val(null);
    });

    $(document).on('click', '#delete_election_confirm', function() {
        $('#confirm-delete-modal-election').modal('show'); 
    });

    $(document).on('click', '#confirm_delete_election_btn', function() {
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {action:"delete_election"},
            success: function () {
                window.location.href = baseUrl + "election.php";
            }
        });
    });

    $(document).on('click', '.launch_editGeneral', function() {
        var triggerEl = document.querySelector('#list-general-list')
        var tab = new bootstrap.Tab(triggerEl);
        tab.show()
    });

    $(document).on('click', '.launch_editCandidate', function() {
        window.location.href = baseUrl + "election-panel/candidates.php";
    });

    $(document).on('click', '.launch_editVoter', function() {
        window.location.href = baseUrl + "election-panel/voters.php";
    });

    $(document).on('click', '.launch_editPosition', function() {
        window.location.href = baseUrl + "election-panel/position.php";
    });

    $(document).on('click', '#view_voterRegis', function() {
        var startdateInput = document.getElementById("startdate_voterRegis");
        var enddateInput = document.getElementById("enddate_voterRegis");

        // Get the value of the date input
        var selectedStartDate = startdateInput.value;
        var selectedEndDate = enddateInput.value;

        // Check if the date is empty
        if (selectedStartDate === "" && selectedEndDate === "") {
            pushNotify("error", "Please provide the registration period");
        } else {
            $.ajax({
                type: "POST",
                url: baseUrl + "config/controllerUserData.php",
                data: {action:"view_voterRegis"},
                success: function (response) {
                    if (response == "recordExist") {
                        var voteRegis_element = document.getElementById("voterRegis_link");
                        var voteRegis_val = voteRegis_element.value;
                        window.open(voteRegis_val, "_blank");
                    } else {
                        pushNotify("error", "Please save the registration period.");
                    }
                }
            });
        }
    });

    $(document).on('click', '#copy_voterRegis_link', function() {
        var clipboardText = "";
        clipboardText = $('#voterRegis_link' ).val(); 
        copyToClipboard( clipboardText );
        pushNotify("success", "Copied to Clipboard");
    });

    $(document).on('click', '#view_CandidateRegis', function() {
        var startdateInput = document.getElementById("startdate_candidateRegis");
        var enddateInput = document.getElementById("enddate_candidateRegis");

        // Get the value of the date input
        var selectedStartDate = startdateInput.value;
        var selectedEndDate = enddateInput.value;

        // Check if the date is empty
        if (selectedStartDate === "" && selectedEndDate === "") {
            pushNotify("error", "Please provide the registration period");
        } else {
            $.ajax({
                type: "POST",
                url: baseUrl + "config/controllerUserData.php",
                data: {action:"view_candidateRegis"},
                success: function (response) {
                    if (response == "recordExist") {
                        var voteRegis_element = document.getElementById("candidateRegis_link");
                        var voteRegis_val = voteRegis_element.value;
                        window.open(voteRegis_val, "_blank");
                    } else {
                        pushNotify("error", "Please save the registration period.");
                    }
                }
            });
        }
    });

    $(document).on('click', '#copy_candidateRegis_link', function() {
        var clipboardText = "";
        clipboardText = $('#candidateRegis_link' ).val(); 
        copyToClipboard( clipboardText );
        pushNotify("success", "Copied to Clipboard");
    });

    $(document).on('click', '#view_ballotSet', function() {
        var nallotSet_element = document.getElementById("ballotSet_link");
        var ballotSetval = nallotSet_element.value;
        window.open(ballotSetval, "_blank");
    });

    $(document).on('click', '#copy_ballotSet_link', function() {
        var clipboardText = "";
        clipboardText = $('#ballotSet_link' ).val(); 
        copyToClipboard( clipboardText );
        pushNotify("success", "Copied to Clipboard");
    });

    $(document).on('click', '#resend_voterOtp', function() {
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {action:"resend_voterOtp"},
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    pushNotify("success", response.message);
                } else {
                    pushNotify("error", "Failed to resend OTP. Please try again.");
                }
            },
            error: function() {
                pushNotify("error", "An error occurred during the request.");
            }
        });
    });

    $(document).on('click', '#resend_candidateOtp', function() {
        $.ajax({
            type: "POST",
            url: baseUrl + "config/controllerUserData.php",
            data: {action:"resend_candidateOtp"},
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    pushNotify("success", response.message);
                } else {
                    pushNotify("error", "Failed to resend OTP. Please try again.");
                }
            },
            error: function() {
                pushNotify("error", "An error occurred during the request.");
            }
        });
    });
});

function copyToClipboard(text) {
    var textArea = document.createElement( "textarea" );
    textArea.value = text;
    document.body.appendChild( textArea );       
    textArea.select();

    try {
      var successful = document.execCommand( 'copy' );
      var msg = successful ? 'successful' : 'unsuccessful';
      console.log('Copying text command was ' + msg);
    } catch (err) {
      console.log('Oops, unable to copy',err);
    }    
    document.body.removeChild( textArea );
}

function previewFile(input){
    var file = $("#add-candidate-image").get(0).files[0];

    if(file){
        var reader = new FileReader();

        reader.onload = function(){
            $("#imgPreview").attr("src", reader.result);
            $("#imgPreview").attr("width", "100%");
        }

        reader.readAsDataURL(file);
    }
}

function previewFileEdit(input){
    var file = $("#edit-image-candidate").get(0).files[0];
    

    if(file){
        var reader = new FileReader();
        reader.onload = function(){
            $("#imgPreview_edit").attr("src", reader.result);
        }

        reader.readAsDataURL(file);
    }
}

function previewUserImage(input){
    var file = $("#edit-image-user").get(0).files[0];
    

    if(file){
        var reader = new FileReader();
        reader.onload = function(){
            $("#user_image_edit_preview").attr("src", reader.result);
        }

        reader.readAsDataURL(file);
    }
}

function previewVoterIDFront(file, e){
    let img_container = e.currentTarget.parentNode.querySelector(".image_containerFront");
    if(file){
        let img = e.currentTarget.parentNode.querySelector("img");
        let img_filename = e.currentTarget.parentNode.querySelector("p");
        img.src = URL.createObjectURL(file);
        // Shorten filename for display
        const maxLength = 15;
        let shortenedFilename = file.name;
        if (file.name.length > maxLength) {
            shortenedFilename = file.name.substring(0, maxLength) + '...';
        }
        img_filename.innerHTML = shortenedFilename;
        img_container.classList.remove('d-none');
    }
    else{
        img_container.classList.add('d-none');
    }
}

function resetFileInputFront_Voter() {
    // Traverse up the DOM to find the file input
    const fileInput = document.querySelector('#regis_voterImg_Front');
    let img_container = document.querySelector('.image_containerFront');
    img_container.classList.add('d-none');
    // Reset the value of the file input
    fileInput.value = "";
}

function previewVoterIDBack(file, e){
    let img_container = e.currentTarget.parentNode.querySelector(".image_containerBack");
    if(file){
        let img = e.currentTarget.parentNode.querySelector("img");
        let img_filename = e.currentTarget.parentNode.querySelector("p");
        img.src = URL.createObjectURL(file);
        // Shorten filename for display
        const maxLength = 15;
        let shortenedFilename = file.name;
        if (file.name.length > maxLength) {
            shortenedFilename = file.name.substring(0, maxLength) + '...';
        }
        img_filename.innerHTML = shortenedFilename;
        img_container.classList.remove('d-none');
    }
    else{
        img_container.classList.add('d-none');
    }
}

function resetFileInputBack_Voter() {
    // Traverse up the DOM to find the file input
    const fileInput = document.querySelector('#regis_voterImg_Back');
    let img_container = document.querySelector('.image_containerBack');
    img_container.classList.add('d-none');
    // Reset the value of the file input
    fileInput.value = "";
}

function previewCandidateIDFront(file, e){
    let img_container = e.currentTarget.parentNode.querySelector(".image_containerFront");
    if(file){
        let img = e.currentTarget.parentNode.querySelector("img");
        let img_filename = e.currentTarget.parentNode.querySelector("p");
        img.src = URL.createObjectURL(file);
        // Shorten filename for display
        const maxLength = 15;
        let shortenedFilename = file.name;
        if (file.name.length > maxLength) {
            shortenedFilename = file.name.substring(0, maxLength) + '...';
        }
        img_filename.innerHTML = shortenedFilename;
        img_container.classList.remove('d-none');
    }
    else{
        img_container.classList.add('d-none');
    }
}

function resetFileInputFront_Candidate() {
    // Traverse up the DOM to find the file input
    const fileInput = document.querySelector('#regis_CandidateImg_Front');
    let img_container = document.querySelector('.image_containerFront');
    img_container.classList.add('d-none');
    // Reset the value of the file input
    fileInput.value = "";
}

function previewCandidateIDBack(file, e){
    let img_container = e.currentTarget.parentNode.querySelector(".image_containerBack");
    if(file){
        let img = e.currentTarget.parentNode.querySelector("img");
        let img_filename = e.currentTarget.parentNode.querySelector("p");
        img.src = URL.createObjectURL(file);
        // Shorten filename for display
        const maxLength = 15;
        let shortenedFilename = file.name;
        if (file.name.length > maxLength) {
            shortenedFilename = file.name.substring(0, maxLength) + '...';
        }
        img_filename.innerHTML = shortenedFilename;
        img_container.classList.remove('d-none');
    }
    else{
        img_container.classList.add('d-none');
    }
}

function resetFileInputBack_Candidate() {
    // Traverse up the DOM to find the file input
    const fileInput = document.querySelector('#regis_CandidateImg_Back');
    let img_container = document.querySelector('.image_containerBack');
    img_container.classList.add('d-none');
    // Reset the value of the file input
    fileInput.value = "";
}

let cropper;
let originalFileName0fCandidatePhoto;

function loadImage() {
    const input = document.getElementById('regis_candidateImg_profileInput');
    const image = document.getElementById('regis_candidateImg_profile');

    const file = input.files[0];

    if (file) {
    const reader = new FileReader();
    originalFileName0fCandidatePhoto = file.name;
    reader.onload = function (e) {
        if (cropper) {
            cropper.destroy(); // Destroy the previous instance
        }
        var myModal = new bootstrap.Modal(document.getElementById('modal_cropCandidatePhoto'));
        myModal.show();
        image.src = e.target.result;

        // Initialize Cropper.js when the image is loaded
        cropper = new Cropper(image, {
        aspectRatio: 1, // You can set the aspect ratio based on your requirements
        viewMode: 2, // Set the view mode for better user experience
        dragMode: 'move',
        });
    };
    reader.readAsDataURL(file);
    }
}

function cropImage() {
    // Get the cropped data as a base64-encoded string
    const croppedDataUrl = cropper.getCroppedCanvas().toDataURL();
    let img_container = document.querySelector(".image_container_CropImage");

    if(croppedDataUrl){
        let img = document.querySelector("#regis_candidateImg_profile_preview");
        let img_filename = document.querySelector("#regis_candidateImg_filename");
        img.src = croppedDataUrl;

        // Shorten filename for display
        const maxLength = 15;
        let shortenedFilename = originalFileName0fCandidatePhoto;
        if (originalFileName0fCandidatePhoto.length > maxLength) {
            shortenedFilename = originalFileName0fCandidatePhoto.substring(0, maxLength) + '...';
        }
        img_filename.innerHTML = shortenedFilename;
        
        img_container.classList.remove('d-none');
    }
    else{
        img_container.classList.add('d-none');
    }
}

function resetFileInput_CandidatePhoto() {
    // Traverse up the DOM to find the file input
    const fileInput = document.querySelector('#regis_candidateImg_profileInput');
    let img_container = document.querySelector('.image_container_CropImage');
    img_container.classList.add('d-none');
    // Reset the value of the file input
    fileInput.value = "";
}

function submitCandidateRegisForm() {
    var quillContent = quill.root.innerHTML; // Get Quill.js content
    var croppedImage = cropper.getCroppedCanvas().toDataURL(); // Get Cropper.js image data URL

    // Set values into hidden inputs
    document.getElementById('quillContentInput').value = quillContent;
    document.getElementById('candidate_croppedImageInput').value = croppedImage;

    // Now submit the form
    document.getElementById('candidateRegisForm').submit();
} 

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

function movePosition(positionId, direction) {
    $.get(baseUrl + "config/controllerUserData.php", { position_id: positionId, direction: direction })
        .done(function (response) {
            if (response === 'Success') {
                updatePositionTable({ action: "updatePositionTable" });
            } else {
                console.error(response);
            }
        });
}

function updatePositionTable(data) {
    // Fetch updated data and replace the entire table body
    $.get(baseUrl + "config/controllerUserData.php", data, function (newTableBody) {
        $('#positionsTable tbody').html(newTableBody);
    });
}
