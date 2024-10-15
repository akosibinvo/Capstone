    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
            crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/ca3839150d.js" 
            crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/simple-notify@0.5.5/dist/simple-notify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" 
            integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" 
            crossorigin="anonymous" 
            referrerpolicy="no-referrer">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.5/dist/perfect-scrollbar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/smooth-scrollbar/8.8.4/smooth-scrollbar.js" integrity="sha512-3f8C6NAQZv0YfwHFnjF231IIVcfZPnzL8EcHhNlzcoSRoEvC/iKDbtC+oYvL+0fRmEUMUYSvrZSE08TcRZYpgA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://kit.fontawesome.com/0c03208a41.js" 
            crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-notify@0.5.5/dist/simple-notify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.js" integrity="sha512-Zt7blzhYHCLHjU0c+e4ldn5kGAbwLKTSOTERgqSNyTB50wWSI21z0q6bn/dEIuqf6HiFzKJ6cfj2osRhklb4Og==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/js/jquery.smartWizard.min.js" type="text/javascript"></script>
    <script>
        <?php
            if(count($errors) == 1){
                foreach($errors as $showerror){
                    ?>
                    pushNotify('error', '<?= $showerror; ?>');
                    <?php
                }   
            }
            if(count($success) == 1){
                foreach($success as $showsuccess){
                    ?>
                    pushNotify('success', '<?= $showsuccess; ?>');
                    <?php
                }   
            }
            if(isset($_SESSION['success'])){
                    ?>
                    pushNotify('success', '<?= $_SESSION['success']; ?>');
                    <?php
                    unset($_SESSION['success']);  
            }
            if(isset($_SESSION['error-otp'])){
                ?>
                pushNotify('error', '<?= $_SESSION['error-otp']; ?>');
                <?php
                unset($_SESSION['error-otp']);  
            }
            if(isset($_SESSION['error'])){
                ?>
                pushNotify('error', '<?= $_SESSION['error']; ?>');
                <?php
                unset($_SESSION['error']);  
            }   
        ?>
        function pushNotify(status, title) {
            new Notify({
                status: status,
                title: title,
                effect: 'slide',
                speed: 400,
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
    </script>
    <script>
        var baseUrl = "<?php echo BASE_URL; ?>";
    </script>
