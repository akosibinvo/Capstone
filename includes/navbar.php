<nav class="navbar navbar-expand-lg bg-white sticky-top shadow-none" id="myNavbar">
    <div class="container-fluid">
        <a class="navbar-brand ps-lg-1 fs-3 d-flex align-items-center" href="index.php">
          <img class="mx-lg-2" src="assets/images/logo4.png" alt="logo" width="40">
          <span class="fw-semibold text-black">Blockchain-Based EVS</span>         
        </a>
        <button class="navbar-toggler  navbar-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $page == "index.php"? 'active-menu':''; ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page == "election.php"? 'active-menu':''; ?>" href="election.php">Elections</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page == "about.php"? 'active-menu':''; ?>" href="about.php">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page == "contact.php"? 'active-menu':''; ?>" href="contact.php">Contact</a>
                </li>
            </ul>
            <div class="d-flex pe-lg-4 ms-lg-2 ps-lg-3 login-signup-btn">
            <?php         
              if(isset($_SESSION['email']) && isset($_SESSION['password']) && $status == 'verified'){
                ?>
                  <div class="row px-2 d-flex justify-content-start profile">
                      <div class="col-auto d-flex align-items-center">
                      <img src="uploads/<?php echo $user_photo; ?>" alt="" width="33" class="rounded-circle dropdown-toggle" role="button" data-bs-toggle="dropdown" data-bs-display="static" data-bs-auto-close="true" aria-expanded="false">
                      <ul class="dropdown-menu dropdown-menu-end me-3 px-2 shadow mt-0 border">
                          <li>
                            <a class="dropdown-item border-radius-md py-2 account_settings text-black" data-user-id="<?php if(isset($_SESSION['email'])){echo $_SESSION['email'];} ?>">
                            <i class="fa-solid fa-gear me-2"></i>Account Settings</a>
                          </li>
                          <li>
                            <a id="signout" class="dropdown-item border-radius-md py-2 text-black" href="<?= $page ?>">
                            <i class="fa-solid fa-right-from-bracket me-2"></i>Sign out</a>
                          </li>
                      </ul>
                      </div>
                  </div>
                <?php
              } else {
                ?>
                  <button class="btn btn-primary text-white me-3" data-bs-toggle="modal" data-bs-target="#login">LOG IN</button>              
                  <button class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#signUp">SIGN UP</button>   
                <?php
              }
              ?>
            </div>
        </div>
    </div>
</nav>