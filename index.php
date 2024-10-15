<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>
<?php require_once "includes/header.php"; ?>
<?php require_once "includes/modals.php"; ?>
<?php require_once "includes/navbar.php"; ?>

    <div>
        <section id="home">
            <div class="container-fluid mt-lg-5">
            <div class="row pt-3">
                <div class="col-lg-7 col-md-12">
                    <h1 class="headline ps-lg-5 pt-3 text-black">A Blockchain-Based Electronic Voting System<br> <br class="d-lg-none">That Provides
                        <span class="auto-type fw-bold"></span>
                    </h1>
                    <p class="message ps-lg-5 text-black">
                        Secure voting system that is available for everyone.
                        Get started by creating an election or entering an election code.
                    </p>      
                    <div class="row d-grid d-md-flex justify-content-md-start ps-lg-5 pt-3">
                        <div class="col-lg-8">
                            <div class="row d-flex justify-content-start">
                                <div class="col-auto d-flex align-items-center">
                                    <button class="btn bg-primary mb-0" type="button" id="homecreateElection"><i class="fa-solid fa-plus fw-bold me-2"></i>Create Election</button>
                                </div>
                                <div class="col-auto text-end">
                                    <form action="" method="GET">
                                        <div class="input-group input-group-outline my-3">
                                            <label class="form-label">Election code</label>
                                            <input type="text" id="code_input" class="form-control" >
                                            <button type="button" class="btn bg-primary mb-0" id="home_vote">Vote</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>                 
                    </div>
                    <div class="row ps-lg-5 pt-5">
                        <div class="col-lg-8 border-top pt-3">
                        <p class="fs-6 text-black"><a class="link-primary" href="about.php">Learn More</a> about Blockchain-Based EVS</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 user-select-none">                                                   
                    <div class="owl-carousel owl-theme">
                    <div class="item">
                        <div class="container">
                        <div class="row d-flex justify-content-center">    
                            <div class="col-1 me-2 d-flex align-items-center">
                            <i class="fa-solid fa-chevron-left customPrevBtn" style="font-size: 1rem; color: black;cursor: pointer;"></i> 
                            </div>   
                            <div class="col-lg-7 border rounded-circle ">
                            <img src="assets/images/Voting-amico.svg" class="img-fluid rounded-circle">   
                            </div>   
                            <div class="col-1 ms-2 d-flex justify-content-end align-items-center">
                            <i class="fa-solid fa-chevron-right customNextBtn" style="font-size: 1rem; color: black;cursor: pointer;"></i> 
                            </div>                
                            <div class="col-lg-9 text-center mt-3">
                            <h1 class="fs-5 text-black">Get a link you can share</h1>
                            <p class="text-center text-black">Click <span class="fw-bold">Create Election</span> to create an election that will generate a link that you can share to the participants or voters in an election you created.</p> 
                            </div>                                            
                        </div>
                        </div>        
                    </div>
                    <div class="item">
                        <div class="container">
                        <div class="row d-flex justify-content-center">    
                            <div class="col-1 me-2 d-flex align-items-center">
                            <i class="fa-solid fa-chevron-left customPrevBtn" style="font-size: 1rem; color: black;cursor: pointer;"></i> 
                            </div>   
                            <div class="col-lg-7 border rounded-circle">
                            <img src="assets/images/Secure data-pana.svg" class="img-fluid rounded-circle">   
                            </div>   
                            <div class="col-1 ms-2 d-flex justify-content-end align-items-center">
                            <i class="fa-solid fa-chevron-right customNextBtn" style="font-size: 1rem; color: black;cursor: pointer;"></i> 
                            </div>                
                            <div class="col-lg-9 text-center mt-3">
                            <h1 class="fs-5 text-black">Your vote is secure</h1>
                            <p class="text-center text-black">Blockchain technology consist of a chain of blocks containing  cryptographic connections making sure the votes can't be altered.</p> 
                            </div>                                            
                        </div>
                        </div>        
                    </div>
                    <div class="item">
                        <div class="container">
                        <div class="row d-flex justify-content-center">    
                            <div class="col-1 me-2 d-flex align-items-center">
                            <i class="fa-solid fa-chevron-left customPrevBtn" style="font-size: 1rem; color: black;cursor: pointer;"></i> 
                            </div>   
                            <div class="col-lg-7 border rounded-circle ">
                            <img src="assets/images/Hidden-cuate.svg" class="img-fluid rounded-circle">   
                            </div>   
                            <div class="col-1 ms-2 d-flex justify-content-end align-items-center">
                            <i class="fa-solid fa-chevron-right customNextBtn" style="font-size: 1rem; color: black;cursor: pointer;"></i> 
                            </div>                
                            <div class="col-lg-9 text-center mt-3">
                            <h1 class="fs-5 text-black">Your identity is anonymous</h1>
                            <p class="text-center text-black">A blockchain network participant has generated an address rather than 
                                a user identification. It maintains anonymity, especially in a blockchain public system.</p> 
                            </div>                                            
                        </div>
                        </div>        
                    </div>
                    </div>         
                </div>
            </div>
            </div>  
        </section>  
    </div>
    <script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>
    <script>
        var typed = new Typed(".auto-type", {
            strings: ["Security", "Anonymity", "Transparency "],
            typeSpeed: 100,
            backSpeed: 100,
            loop: true
        })
    </script>
<?php require_once "includes/footer.php"; ?>
<script src="assets/js/material-dashboard.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>