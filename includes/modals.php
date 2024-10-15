<div id="login" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 2;"></button>
            <div class="container-fluid">
              <div class="row">
                <div class="col-lg-6 d-flex align-items-center flex-column justify-content-evenly">
                  <div class="row">
                    <h1 class="fs-1 text-start text-black">Welcome Back :)</h1>
                    <p class="fs-6 text-black">It's nice to see you again!<br>Login to continue to your account.</p>
                  </div>   
                  <div class="row">
                    <img src="assets/images/Login-rafiki.svg" alt="" class="img-fluid">                    
                  </div>                 
                </div>
                <div class="col-lg-6 position-relative pb-5">
                  <div class="position-absolute bottom-0 start-0 ms-2">
                    <a href="" class="text-black me-1" style="font-size: small;text-decoration: none;">Terms & Conditions</a>
                    <a href="" class="text-black" style="font-size: small;text-decoration: none;">Privacy Policy</a>
                  </div>
                  <div class="row justify-content-center pt-5">
                    <img src="assets/images/logo4.png" class="img-fluid" style="width: 5rem;" alt="">
                  </div>
                  <div class="row text-center pt-1">
                    <h1 class="fs-5 footer-title text-black">Blockchain-Based EVS</h1>
                  </div>
                  <div class="row text-center pt-4">
                    <p class="fs-6 text-black">Login to Continue</p>
                  </div>

                  <form action="<?= $page ?>" method="post" autocomplete="">
                    <div class="input-group input-group-outline my-3">
                      <label class="form-label">Email</label>
                      <input type="email" id="email_login" name="email" class="form-control" required/> 
                    </div>
                    <div class="input-group input-group-outline">
                      <label class="form-label">Password</label>
                      <input type="password" id="password_login" name="password" class="form-control" required/> 
                    </div>
                    
                    <a href="" class="ms-1 mt-2 link-primary" style="font-size: small;text-decoration: none;" data-bs-toggle="modal" data-bs-dismiss="modal" data-bs-target="#forgot-pass">Forgot Password?</a>                 
                    <button type="submit" name="login" class="btn text-white btn-primary form-control my-3">LOGIN</button>
                  </form>

                  <div class="row text-center mb-5 mt-2">
                    <p class="text-black">Don't have an account yet?
                      <a href="" class="link-primary fs-6" data-bs-toggle="modal" data-bs-dismiss="modal" data-bs-target="#signUp">Sign up</a>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>

<div id="signUp" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body position-relative">
              <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 2;"></button>
              <div class="container-fluid">
                  <div class="row">
                  <div class="col-lg-6">
                      <h3 class="fs-1 pt-2 text-black">Sign up Now!</h3>
                      <p class="fs-6 pt-2 text-black">Sign up now to start creating election and voting in a secure manner where your vote cannot be altered and your identity is anonymous.</p>
                      <img src="assets/images/Mobile login-rafiki.svg" alt="" class="img-fluid mt-5">
                  </div>
                    <div class="col-lg-6 position-relative">
                      <div class="position-absolute bottom-0 start-0 ms-2">
                        <a href="" class="text-black me-1" style="font-size: small;text-decoration: none;">Terms & Conditions</a>
                        <a href="" class="text-black" style="font-size: small;text-decoration: none;">Privacy Policy</a>
                      </div>
                      <div class="row justify-content-center pt-5">
                        <img src="assets/images/logo4.png" class="img-fluid" style="width: 5rem;" alt="">
                      </div>
                      <div class="row text-center pt-1">
                        <h1 class="fs-5 footer-title text-black">Blockchain-Based EVS</h1>
                      </div>
                      <div class="row text-center pt-4">
                        <p class="fs-6 text-black">Sign up to Continue</p>
                      </div>

                      <form action="<?= $page ?>" method="post" autocomplete="">
                        <div class="input-group input-group-outline mt-3">
                          <label class="form-label">Full Name</label>
                          <input type="text" name="name" id="fullname" class="form-control" required>
                        </div>
                        <div class="input-group input-group-outline mt-3">
                          <label class="form-label">Email</label>
                          <input type="email" name="email" id="email" class="form-control" required> 
                        </div>
                          
                        <div class="row mt-3">
                          <div class="col">                           
                            <div class="input-group input-group-outline">
                              <label class="form-label">Password</label>
                              <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                          </div>
                          <div class="col">
                            <div class="input-group input-group-outline">
                              <label class="form-label">Confirm Password</label>
                              <input type="password" name="cpassword" id="cpassword" class="form-control" required>
                            </div>               
                          </div>
                        </div>              
                        <input type="submit" name="signup" value="SIGN UP" class="btn text-white form-control my-3 btn-primary">
                      </form>

                      <div class="row text-center">
                        <p class="text-black">Already have an account?
                            <a href="" class="text-primary fs-6" data-bs-toggle="modal" data-bs-dismiss="modal" data-bs-target="#login">Login</a>
                        </p>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
        </div>
    </div>
</div>

<div id="otp" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 2;"></button>
            <div class="container-fluid">
              <div class="row d-flex justify-content-center">
                <div class="col-lg-10 position-relative pb-2">
                  <div class="row justify-content-center pt-5">
                    <div class="col-10">
                      <img src="assets/images/Enter OTP-bro.svg" class="img-fluid" alt="">
                    </div>
                  </div>
                  <div class="row text-center pt-1">
                    <h1 class="fs-5">OTP Verification</h1>
                    <p class="fs-6"><?php echo $_SESSION['info']; ?></p>
                  </div>
                  <form action="<?= $page ?>" method="post" autocomplete="off">
                    <div class="input-group input-group-outline my-3">
                      <label class="form-label">Enter Verification Code</label>
                      <input type="text" class="form-control" name="otp" required>   
                    </div>                                             
                    <button name="check" style="background-color: #00008b" type="submit" class="btn text-light form-control">VERIFY</button>
                    <a id="resend-otp" href="" class="ms-1 mt-2" style="font-size: small;text-decoration: none;">Resend Code</a>  
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>

<div id="forgot-pass" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 2;"></button>
            <div class="container-fluid">
              <div class="row d-flex justify-content-center">
                <div class="col-lg-10 position-relative pb-5">
                  <div class="row justify-content-center">
                    <div class="col-10">
                      <img src="assets/images/Forgot password-bro.svg" class="img-fluid" alt="">
                    </div>
                  </div>
                  <div class="row text-center">
                  <h1 class="fs-5">Forgot Password?</h1>
                    <p class="fs-6">Please enter your email address to send the password reset code.</p>
                  </div>
                  <form action="<?= $page ?>" method="post" autocomplete="off">
                    <div class="input-group input-group-outline my-3">
                      <label class="form-label">Enter Email Address</label>
                      <input type="email" class="form-control" name="email" id="pass-code" required>  
                    </div>                            
                    <button name="send-code-pass" style="background-color: #00008b" type="submit" class="btn text-light form-control">SEND CODE</button>
                    <a id="resend-pass-otp" href="" class="ms-1 mt-2" style="font-size: small;text-decoration: none;">Resend Code</a>  
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>

<div id="otp-forgot" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 2;"></button>
            <div class="container-fluid">
              <div class="row d-flex justify-content-center">
                <div class="col-lg-10 position-relative pb-5">
                  <div class="row justify-content-center pt-5">
                    <div class="col-10">
                      <img src="assets/images/Enter OTP-bro.svg" class="img-fluid" alt="">
                    </div>
                  </div>
                  <div class="row text-center pt-1">
                    <h1 class="fs-5">OTP Verification</h1>
                    <p class="fs-6"><?php echo $_SESSION['info']; ?></p>
                  </div>
                  <form action="<?= $page ?>" method="post" autocomplete="off">
                    <input type="text" class="form-control my-3" name="otp" id="otp-forgot" placeholder="Enter Verification Code" required>                            
                    <button name="check-reset-otp" style="background-color: #00008b" type="submit" class="btn text-light form-control">VERIFY</button>
                    <a id="resend-otp-forgot" href="" class="ms-1 mt-2" style="font-size: small;text-decoration: none;">Resend Code</a>  
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>

<div id="change-pass" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 2;"></button>
            <div class="container-fluid">
              <div class="row d-flex justify-content-center">
                <div class="col-lg-10 position-relative pb-5">
                  <div class="row justify-content-center">
                    <div class="col-10">
                      <img src="assets/images/Reset password.svg" class="img-fluid" alt="">
                    </div>
                  </div>
                  <div class="row text-center">
                  <h1 class="fs-5">Reset Password</h1>
                    <p class="fs-6">Please enter your new password.</p>
                  </div>
                  <form action="<?= $page ?>" method="post" autocomplete="off">
                    <div class="row my-3">
                      <div class="col">
                        <input type="password" name="password" id="password-change" class="form-control" placeholder="New Password" required>
                      </div>
                      <div class="col">
                        <input type="password" name="cpassword" id="cpassword-change" class="form-control" placeholder="Confirm Password" required>
                      </div>
                    </div>                             
                    <button name="change-password" style="background-color: #00008b" type="submit" class="btn text-light form-control">CHANGE PASSWORD</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>

<div id="create-election" class="modal fade" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 d-flex pb-0">
                <h5 class="modal-title fs-3 text-black">Create Election</h5>
                <button type="button" class="btn-close align-self-start" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
            <div class="modal-body">
              <div class="container-fluid">
                  <div class="row d-flex justify-content-center">
                    <div class="col-lg-12">
                      <form action="<?= $page ?>" method="post" autocomplete="">
                        <div class="row mt-3">
                            <label for="electionname" class="form-label text-black">Election Name</label>
                            <div class="input-group input-group-outline">                         
                              <input type="text" name="election-name" id="electionname" placeholder="eg. Pinagbuhatan SK Election, Com Soc Election" class="form-control" required>
                            </div>
                        </div>                        
                        <div class="row mt-3">
                          <div class="col">
                            <div>
                              <label for="startdate" class="form-label text-black">Start Date</label>
                              <div class="input-group input-group-outline">
                                <?php
                                  date_default_timezone_set('Asia/Manila');
                                  $date = new DateTime(); // Date object using current date and time
                                  $dt= $date->format('Y-m-d\TH:i'); 
                                  echo "<input type='datetime-local' id='startdate' name='start-date' value='$dt' class='form-control' required>";
                                ?>
                              </div>
                            </div>                         
                          </div>
                          <div class="col">
                            <div>
                              <label for="enddate" class="form-label text-black">End Date</label>
                              <div class="input-group input-group-outline">
                                <?php
                                  date_default_timezone_set('Asia/Manila');
                                  $date = new DateTime(); // Date object using current date and time
                                  $date->modify('+1 day');
                                  $dt= $date->format('Y-m-d\TH:i'); 
                                  echo "<input type='datetime-local' id='enddate' name='end-date' value='$dt' class='form-control' required>";
                                ?>
                              </div>                               
                            </div>
                          </div>
                        </div>   
                        <div class="mt-3">
                          <label for="timezone" class="form-label text-black">Timezone</label>
                          <select name="timezone" id="timezone" class="form-select">
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
                        <button type="submit" name="continue-create" class="btn text-white btn-primary form-control my-3">CREATE ELECTION</button>
                      </form>
                    </div>
                  </div>
              </div>
            </div>
        </div>
    </div>
</div>

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
                  <img id="user_image_edit_preview" src="assets/images/profile.jpg" class="img-fluid rounded-circle border border-2 p-1 border-primary" alt="">
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