<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-white" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-black opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="../election.php">
        <img src="../assets/images/logo4.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-black">Blockchain-Based EVS</span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-black <?= $page == "index.php"? 'active':''; ?>" href="index.php">
            <div class="text-black text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-house-chimney opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Overview</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-semibold opacity-8">Configure Election</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link text-black <?= $page == "voters.php"? 'active':''; ?>" href="voters.php">
            <div class="text-black text-center me-2 d-flex align-items-center justify-content-center">
              <i class=" opacity-10 fa-solid fa-people-group"></i>
            </div>
            <span class="nav-link-text ms-1">Voters</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-black <?= $page == "candidates.php"? 'active':''; ?>" href="candidates.php">
            <div class="text-black text-center me-2 d-flex align-items-center justify-content-center">
              <i class=" opacity-10 fa-brands fa-black-tie"></i>
            </div>
            <span class="nav-link-text ms-1">Candidates</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-black <?= $page == "position.php"? 'active':''; ?>" href="position.php">
            <div class="text-black text-center me-2 d-flex align-items-center justify-content-center">
                <i class=" fa-solid fa-ranking-star opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Positions</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-black <?= $page == "settings.php"? 'active':''; ?>" href="settings.php">
            <div class="text-black text-center me-2 d-flex align-items-center justify-content-center">
              <i class=" opacity-10 fa-solid fa-gear"></i>
            </div>
            <span class="nav-link-text ms-1">Settings</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-semibold opacity-8">election reports</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link text-black <?= $page == "votes.php"? 'active':''; ?>" href="votes.php">
            <div class="text-black text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-check-to-slot  opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Votes</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-black <?= $page == "results.php"? 'active':''; ?>" href="results.php">
            <div class="text-black text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-square-poll-vertical  opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Results</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-black <?= $page == "feedback.php"? 'active':''; ?>" href="feedback.php">
            <div class="text-black text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-thumbs-up opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Feedback</span>
          </a>
        </li>
      </ul>
    </div>
</aside>