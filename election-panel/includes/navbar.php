<nav class="navbar navbar-main navbar-expand-lg px-0 ms-0 me-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
    <div class="container-fluid py-1 px-3 d-flex justify-content-between">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
                    <?= $page == "votes.php"? 'Votes':''; ?>
                    <?= $page == "index.php"? 'Overview':''; ?>
                    <?= $page == "candidates.php"? 'Candidates':''; ?>
                    <?= $page == "position.php"? 'Positions':''; ?>
                    <?= $page == "results.php"? 'Results':''; ?>
                    <?= $page == "settings.php"? 'Settings':''; ?>
                    <?= $page == "voters.php"? 'Voters':''; ?>
                </li>
            </ol>
            <div class="row">
                <div class="col-lg-auto">
                    <h4 class="font-weight-bolder text-black mb-0" id="election-name"></h4>
                </div>
                <div class="col-lg-auto">
                    <p class="text-xs p-1 px-2 border bg-primary rounded text-uppercase text-white mb-0 text-bold" id="election-status">Building</p>
                </div>
            </div>  
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 justify-content-end" id="navbar">
            <ul class="navbar-nav justify-content-end">
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                    </a>
                </li>
                <li class="nav-item dropdown d-flex align-items-center">
                    <a class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../assets/images/<?php echo $user_photo; ?>" class="avatar avatar-sm" role="button" id="user_profile_pic">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-2 me-sm-n3" aria-labelledby="dropdownMenuButton">
                        <li>
                            <a class="dropdown-item border-radius-md account_settings" data-user-id="<?php if(isset($_SESSION['email'])){echo $_SESSION['email'];} ?>">
                                <div class="d-flex py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm text-black font-weight-normal mb-1"><i class="fa-solid fa-gear me-2"></i>Acccount Settings</h6>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item border-radius-md" id="signout_panel">
                                <div class="d-flex py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm text-black font-weight-normal mb-1"><i class="fa-solid fa-right-from-bracket me-2"></i>Sign out</h6>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
            </ul>
        </div>
    </div>
</nav>