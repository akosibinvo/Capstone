<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>
<?php require_once "includes/header.php"; ?>
<?php require_once "includes/modals.php"; ?>
<?php require_once "includes/navbar.php"; ?>

    <div>
        <section>
          <div class="container">
            <div class="row d-flex justify-content-center">
              <div class="col-lg-5 thumbnail mb-4 mb-lg-0">
                <div class="p-5 pb-2 rounded">
                  <h1 class="fs-1" style="color: #00008b;">Have Some Question?</h1>    
                  <p class="fs-6 mb-3 text-black">Thank you for using our website if you have any question or suggestion please message us below.</p>   

                  <form action="<?= $page ?>" method="post" autocomplete="">
                    <div class="input-group input-group-outline mb-4">                 
                      <label class="form-label">Name</label>
                      <input type="text" id="name-contact" name="name" class="form-control" required/>
                    </div>
                    <div class="input-group input-group-outline mb-4">
                      <label class="form-label" for="form4Example2">Email address</label>
                      <input type="email" id="email-contact" name="email" class="form-control" required/>                  
                    </div>
                    <div class="mb-4">
                      <label class="form-label">Message:</label>
                      <div class="input-group input-group-outline">
                        <textarea class="form-control" rows="3" placeholder="Enter your message here" name="message" required></textarea> 
                      </div>              
                    </div>
                    <button type="submit" name="send-message" class="btn btn-primary text-white btn-block mb-4 px-4 form-control">SEND MESSAGE</button>
                  </form>

                </div>
              </div>
              <div class="col-lg-6">                
                  <img src="assets/images/Contact us-rafiki.svg" class="img-fluid">               
              </div>
            </div>
          </div>   
          <div class="container">
            <div class="row align-items-center pb-5">
              <h2 class="display-6 text-center text-black">Get in touch</h2>
              <p class="fw-5 text-center text-black">Want to get in touch? We'd love to hear from you. Here's how to reach us.</p>
              <div class="col-lg-5 col-md-10 col-sm-10 mx-auto">
                <div class="d-flex justify-content-between">
                  <a href="https://www.facebook.com/profile.php?id=100088715889541">
                    <i class="bi-facebook" style="font-size: 2.5rem; color: black;"></i>  
                  </a>
                  <a href="https://twitter.com/BlockchainEVS">
                    <i class="bi-twitter" style="font-size: 2.5rem; color: black;"></i>  
                  </a>
                  <a href="https://www.instagram.com/blockchainbasedevs/">
                    <i class="bi-instagram" style="font-size: 2.5rem; color: black;"></i> 
                  </a>
                  <a href="https://www.linkedin.com/in/blockchain-based-evs-a5b71425a/">
                    <i class="bi-linkedin" style="font-size: 2.5rem; color: black;"></i>  
                  </a>
                </div>
              </div>
            </div>
          </div>
        </section>  
        <section data-scroll-section class="text-center text-lg-start" style="background-color: #000;">
          <div class="py-2 fs-6 bg-black text-white">
            <div class="container">
              <div class="row ">
                <div class="col-lg-6 text-center">
                  Website created by for Capstone II
                </div>
                <div class="col-lg-6 text-center">
                  All rights reserved:
                <a class="text-reset fs-6" href="">Blockchain-Based EVS</a>
                </div>
              </div>
            </div>         
          </div>
        </section>
    </div>

<?php require_once "includes/footer.php"; ?>
<script src="assets/js/material-dashboard.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>