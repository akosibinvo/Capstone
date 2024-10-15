<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use chillerlan\QRCode\{QRCode, QROptions};

require_once "config.php";
require ROOT_PATH ."PHPMailer/src/Exception.php";
require ROOT_PATH ."PHPMailer/src/PHPMailer.php";
require ROOT_PATH ."PHPMailer/src/SMTP.php";
require_once ROOT_PATH ."php-qrcode-main/vendor/autoload.php";

require_once ROOT_PATH ."vendor/autoload.php";

use Web3\Web3;
use Web3\Utils;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager; 
use Web3\Contract;

use Sop\CryptoTypes\Asymmetric\EC\ECPrivateKey;
use Sop\CryptoEncoding\PEM;
use kornrunner\Keccak;

require "connection.php";

session_start();

$email = "";
$name = "";
$errors = array();
$success = array();

$page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/")+1);

//if user signup button
if(isset($_POST['signup'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    if($password !== $cpassword){
        $errors['password'] = "Confirm password not matched!";
        echo "
            <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
            <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'
                    integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM'
                    crossorigin='anonymous'>
            </script>
            <Script>
                $(document).ready(function(){
                    $('#signUp').modal('show');
                });
            </Script>
            ";
    }
    $email_check = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $email_check);
    if(mysqli_num_rows($res) > 0){
        $errors['email'] = "Email that you have entered is already exist!";
        echo "
            <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
            <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'
                    integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM'
                    crossorigin='anonymous'>
            </script>
            <Script>
                $(document).ready(function(){
                    $('#signUp').modal('show');
                });
            </Script>
            ";
    }
    if(count($errors) === 0){
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $code = rand(999999, 111111);
        $status = "notverified";
        $profile_pic = "profile.jpg";
        $insert_data = "INSERT INTO usertable (name, email, password, code, status, user_photo)
                        values('$name', '$email', '$encpass', '$code', '$status', '$profile_pic')";
        $data_check = mysqli_query($con, $insert_data);
        if($data_check){
            $subject = "Email Verification Code";
            $message = "Your verification code is <b>$code</b>";

            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'blockchainbased.evs@gmail.com';
            $mail->Password = 'xnvxfrxgmcirirjm';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('blockchainbased.evs@gmail.com');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            if($mail->send()){
                $info = "We sent a verification code to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                $success['signup'] = "Registered successfully. Please verify your account.";
                echo "
                    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
                    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'
                            integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM'
                            crossorigin='anonymous'>
                    </script>
                    <Script>
                        $(document).ready(function(){
                            $('#otp').modal('show');
                        });
                    </Script>
                ";
            }else{
                $errors['otp-error'] = "Failed while sending code!";
            }
        }else{
            $errors['db-error'] = "Failed while inserting data into database!";
        }
    }

}
//if user click verification code submit button
if(isset($_POST['check'])){
    $_SESSION['info'] = "";
    $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
    $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
    $code_res = mysqli_query($con, $check_code);
    if(mysqli_num_rows($code_res) > 0){
        $fetch_data = mysqli_fetch_assoc($code_res);
        $fetch_code = $fetch_data['code'];
        $email = $fetch_data['email'];
        $code = 0;
        $status = 'verified';
        $update_otp = "UPDATE usertable SET code = $code, status = '$status' WHERE code = $fetch_code";
        $update_res = mysqli_query($con, $update_otp);
        if($update_res){     
            $success['verified'] = "Account successfully verified";
        }else{
            $errors['otp-error'] = "Failed while updating code!";
        }
    }else{
        $email = $_SESSION['email'];
        $info = "It's look like you haven't still verify your email - $email";
        $_SESSION['info'] = $info;
        $errors['otp-error'] = "You entered an incorrect code!";
        echo "
            <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
            <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'
                    integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM'
                    crossorigin='anonymous'>
            </script>
            <Script>
                $(document).ready(function(){
                    $('#otp').modal('show');
                });
            </Script>
            ";
    }
}

//if user click login button
if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $check_email = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $check_email);
    if(mysqli_num_rows($res) > 0){
        $fetch = mysqli_fetch_assoc($res);
        $fetch_pass = $fetch['password'];
        if(password_verify($password, $fetch_pass)){
            $_SESSION['email'] = $email;
            $status = $fetch['status'];
            if($status == 'verified'){
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                $success['login'] = "Successfully login. Welcome back!";
            }else{
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;    
                $info = "It's look like you haven't still verify your email - $email";
                $_SESSION['info'] = $info;
                $errors['otp-verify'] = "Please verify your account to continue!";
                echo "
                    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
                    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'
                            integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM'
                            crossorigin='anonymous'>
                    </script>
                    <Script>
                        $(document).ready(function(){
                            $('#otp').modal('show');
                        });
                    </Script>
                    ";
            }
        }else{
            $errors['email'] = "Incorrect email or password!";
            echo "
                    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
                    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'
                            integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM'
                            crossorigin='anonymous'>
                    </script>
                    <Script>
                        $(document).ready(function(){
                            $('#login').modal('show');
                        });
                    </Script>
                    ";
        }
    }
    else {
        $errors['email'] = "Looks like you dont have account. Sign up now!";
        echo "
                    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
                    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'
                            integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM'
                            crossorigin='anonymous'>
                    </script>
                    <Script>
                        $(document).ready(function(){
                            $('#signUp').modal('show');
                        });
                    </Script>
                    ";
    }
}

//if user click continue button in forgot password form
if(isset($_POST['check-email'])){
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $check_email = "SELECT * FROM usertable WHERE email='$email'";
    $run_sql = mysqli_query($con, $check_email);
    if(mysqli_num_rows($run_sql) > 0){
        $code = rand(999999, 111111);
        $insert_code = "UPDATE usertable SET code = $code WHERE email = '$email'";
        $run_query =  mysqli_query($con, $insert_code);
        if($run_query){
            $subject = "Password Reset Code";
            $message = "Your password reset code is $code";
            $sender = "From: shahiprem7890@gmail.com";
            if(mail($email, $subject, $message, $sender)){
                $info = "We've sent a passwrod reset otp to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                header('location: reset-code.php');
                exit();
            }else{
                $errors['otp-error'] = "Failed while sending code!";
            }
        }else{
            $errors['db-error'] = "Something went wrong!";
        }
    }else{
        $errors['email'] = "This email address does not exist!";
    }
}

// if send code for forgot password
if(isset($_POST['send-code-pass'])){
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $check_email = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $check_email);
    if(mysqli_num_rows($res) > 0){
        $code = rand(999999, 111111);
        $insert_code = "UPDATE usertable SET code = $code WHERE email = '$email'";
        $run_query =  mysqli_query($con, $insert_code);
        if($run_query){
            $subject = "Password Reset Code";
            $message = "Your password reset code is <b>$code</b>";

            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'blockchainbased.evs@gmail.com';
            $mail->Password = 'xnvxfrxgmcirirjm';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('blockchainbased.evs@gmail.com');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            if($mail->send()){
                $info = "We've sent a password reset code to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                $success['change-success'] = "Password reset code successfully sent.";
                echo "
                    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
                    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'
                            integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM'
                            crossorigin='anonymous'>
                    </script>
                    <Script>
                        $(document).ready(function(){
                            $('#otp-forgot').modal('show');
                        });
                    </Script>
                    ";
            }else{
                $errors['otp-error-change'] = "Failed while sending code!";
            }
        }else{
            $errors['db-error'] = "Something went wrong!";
        }
    }else{
        $errors['email-notexist'] = "Email address not exist! Please signup to create an account.";
        echo "
                <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
                <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'
                        integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM'
                        crossorigin='anonymous'>
                </script>
                <Script>
                    $(document).ready(function(){
                        $('#signUp').modal('show');
                    });
                </Script>
                ";
    }
}

//if user click check reset otp button
if(isset($_POST['check-reset-otp'])){
    $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
    $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
    $code_res = mysqli_query($con, $check_code);
    if(mysqli_num_rows($code_res) > 0){
        $fetch_data = mysqli_fetch_assoc($code_res);
        $email = $fetch_data['email'];
        $_SESSION['email'] = $email;
        $success['change-success-verified'] = "Code successfully verified. Please change your password";
        echo "
                <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
                <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'
                        integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM'
                        crossorigin='anonymous'>
                </script>
                <Script>
                    $(document).ready(function(){
                        $('#change-pass').modal('show');
                    });
                </Script>
                ";
    }else{
        $errors['otp-error'] = "You entered incorrect code!";
    }
}

//if user click change password button
if(isset($_POST['change-password'])){
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    if($password !== $cpassword){
        $errors['password'] = "Confirm password not matched!";
        echo "
            <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
            <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'
                    integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM'
                    crossorigin='anonymous'>
            </script>
            <Script>
                $(document).ready(function(){
                    $('#change-pass').modal('show');
                });
            </Script>
            ";
    }else{
        $code = 0;
        $email = $_SESSION['email']; //getting this email using session
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $update_pass = "UPDATE usertable SET code = $code, password = '$encpass' WHERE email = '$email'";
        $run_query = mysqli_query($con, $update_pass);
        if($run_query){
            $success['change-success'] = "Your password changed. Please login with your new password.";
            echo "
            <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
            <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'
                    integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM'
                    crossorigin='anonymous'>
            </script>
            <Script>
                $(document).ready(function(){
                    $('#login').modal('show');
                });
            </Script>
            ";
        }else{
            $errors['db-error'] = "Failed to change your password!";
        }
    }
}

//if send message button click
if(isset($_POST['send-message'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $feedback = mysqli_real_escape_string($con, $_POST['message']);

    $subject = "Blockchain-Based EVS - Feedback - $name";

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'blockchainbased.evs@gmail.com';
    $mail->Password = 'xnvxfrxgmcirirjm';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('blockchainbased.evs@gmail.com');
    $mail->addAddress('blockchainbased.evs@gmail.com');
    $mail->addReplyTo($email, $name);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $feedback;

    if($mail->send()){
        $success['feedback'] = "Message sent successfully. Thank for your feedback.";
    }else{
        $errors['feedback-error'] = "Failed while sending message!";
    }
}

//if continue button click
if(isset($_POST['continue-create'])){
    $electionname = mysqli_real_escape_string($con, $_POST['election-name']);
    $startdate = mysqli_real_escape_string($con, $_POST['start-date']);
    $enddate = mysqli_real_escape_string($con, $_POST['end-date']);
    $timezone = mysqli_real_escape_string($con, $_POST['timezone']);

    if($startdate == $enddate){
        $errors['date'] = "End Date must be after Start Date!";
        echo "
            <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
            <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'
                    integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM'
                    crossorigin='anonymous'>
            </script>
            <Script>
                $(document).ready(function(){
                    $('#create-election').modal('show');
                });
            </Script>
            ";
    }

    if(count($errors) === 0){
        $status = "building";
        $email = $_SESSION['email'];
        $set = 'abcdefghijklmnopqrstuvwxyz';
		$election_code = substr(str_shuffle($set), 0, 10);

        $insert_data = "INSERT INTO electiontable (election_name, start_date, end_date, timezone, status, created_by, election_code)
                        values('$electionname', '$startdate', '$enddate', '$timezone', '$status', '$email', '$election_code')";
        $data_check = mysqli_query($con, $insert_data);
        if($data_check){
            $get_id = "SELECT * FROM electiontable ORDER BY election_id DESC LIMIT 1";
            $res = mysqli_query($con, $get_id);
            if(mysqli_num_rows($res) > 0){
                $fetch = mysqli_fetch_assoc($res);
                $_SESSION['election-id'] = $fetch['election_id'];
                if(isset( $_SESSION['election-id'])){
                    header("location: election-panel/index.php"); 
                }
                else{
                    header("location: election.php"); 
                }
            }           
        }
    }  
}

//get the election id that has been clickrd
if(isset($_POST['dataId'])){
    $dataId = $_POST['dataId'];
    $_SESSION['election-id'] = $dataId;
    if(isset($_SESSION['election-id'])) {
        echo "success";
    }    
}

//add position 
if (isset($_POST['add-position'])) {
    $description = mysqli_real_escape_string($con, $_POST['position-name']);
    $max_vote = mysqli_real_escape_string($con, $_POST['max-vote']);
    $max_can = mysqli_real_escape_string($con, $_POST['max-candidate']);
    $election_id = $_SESSION['election-id'];
    $priority = 0;

    // Check if the position name already exists
    $check_query = "SELECT * FROM positionstable WHERE position_desc = '$description' AND election_id = '$election_id'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Position name already exists
        $errors['add-position'] = "Position name already exists.";
    } else {
        // Position name doesn't exist, proceed with insertion
        $get_priority = "SELECT * FROM positionstable ORDER BY priority DESC LIMIT 1";
        $res = mysqli_query($con, $get_priority);

        if (mysqli_num_rows($res) > 0) {
            $fetch = mysqli_fetch_assoc($res);
            $priority = $fetch["priority"] + 1;
        } else {
            $priority = 1;
        }

        $insert_data = "INSERT INTO positionstable (position_desc, maximum_vote, maximum_candidate, election_id, priority)
                        VALUES ('$description', '$max_vote', '$max_can', '$election_id', '$priority')";

        $data_check = mysqli_query($con, $insert_data);

        if ($data_check) {
            $success['add-position'] = "Position successfully added.";
        } else {
            $errors['add-position'] = "Something went wrong inserting data.";
        }
    }
}

//show edit position modal
if(isset($_POST['show_modal_position'])){
    $positionId = $_POST['show_modal_position'];
    $election_id = $_SESSION['election-id'];
    $sql = "SELECT * FROM positionstable WHERE position_id = '$positionId' AND election_id = '$election_id'";
    $query = $con->query($sql);
	$row = $query->fetch_assoc();
    
    echo json_encode($row);  
}

//edit modal position save button click
if(isset($_POST['save-position'])){
    $edit_position_name = $_POST['position-name-edit'];
    $edit_max_vote = $_POST['max-vote-edit'];
    $edit_max_can = $_POST['max-can-edit'];
    $edit_positionid = $_POST['save-position'];
    $election_id = $_SESSION['election-id'];
    $sql = "UPDATE positionstable SET position_desc = '$edit_position_name', maximum_vote = '$edit_max_vote',  maximum_candidate = '$edit_max_can' WHERE position_id = '$edit_positionid' AND election_id = '$election_id'";
    if($query = $con->query($sql)) {
        $success['edit-position'] = "Position successfully updated.";
    } else {
        $errors['db-error'] = "Failed while updating data into database!";
    }
}

//show confirmation delete dialog for position
if(isset($_POST['show_modal_delete_position'])){
    $positionId = $_POST['show_modal_delete_position'];
    $election_id = $_SESSION['election-id'];
    $sql = "SELECT * FROM positionstable WHERE position_id ='$positionId' AND election_id = '$election_id'"; 
    $query = $con->query($sql);
	$row = $query->fetch_assoc();
    
    echo json_encode($row);  
}

//delete dialog confirm button clicked in position
if(isset($_POST['delete_position_btn_name'])){
    $positionId = $_POST['delete_position_btn_name'];
    $election_id = $_SESSION['election-id'];
    $sql = "DELETE FROM positionstable WHERE position_id ='$positionId' AND election_id = '$election_id'"; 
    if($query = $con->query($sql)) {
        $success['delete-position'] = "Position successfully deleted.";
    } else {
        $errors['db-error'] = "Failed while deleting data into database!";
    }
}

//click verify in unverified modal
if(isset($_GET['verify_voterId'])){
    $voterId = $_GET['verify_voterId'];
    $election_id = $_SESSION['election-id'];
    // Prepare and execute the SQL query to fetch voter data
    $sql = "SELECT voter_id, name, section, id_number, front_id_image_path FROM voters_table WHERE voter_id = ? AND election_id = ?";
    $stmt = $con->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ii", $voterId, $election_id);

    // Execute the statement
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($voter_id, $voter_name, $section, $idNumber, $voter_IdFront);

    // Fetch the result
    if ($stmt->fetch()) {
        $voterData = array(
            'name' => $voter_name,
            'section' => $section,
            'id_number' => $idNumber,
            'voter_id' => $voter_id,
            'voter_image' => $voter_IdFront
        );

        // Encode the array as JSON and echo it
        echo json_encode($voterData);
    } else {
        // If no data is found, return an empty JSON object
        echo json_encode(array());
    }

    // Close the statement and connection
    $stmt->close();
}

//click the verify button
if(isset($_POST['verify_data'])){
    $voterId = $_POST['verify_data'];
    $election_id = $_SESSION['election-id'];

    $getEmail = "SELECT email FROM voters_table WHERE voter_id = '$voterId' AND election_id = '$election_id'";
    $result = $con->query($getEmail);

    if ($result->num_rows > 0) {
        // Fetch the email from the result set
        $row = $result->fetch_assoc();
        $email = $row["email"];
    }

    // Update query with prepared statement
    $updateQuery = "UPDATE voters_table
    SET isData_verified = ?
    WHERE voter_id = ? AND election_id = ?";

    // Prepare the statement
    $stmt = $con->prepare($updateQuery);

    // Bind parameters
    $verified = 1; // replace with your actual new value
    $stmt->bind_param("iii", $verified, $voterId, $election_id);

    // Execute the prepared statement
    $successUpdate = $stmt->execute();
    if ($successUpdate) {
        $subject = "Voters Registration";
        $message = "<p><b>Congratulations</b><br>
                    We are pleased to inform you that your voter registration information has been successfully verified!
                    Thank you for taking the time to ensure the accuracy of your details.
                    Your commitment to providing accurate information is crucial in maintaining the integrity of the electoral
                    process. With your registration now verified, you're all set to participate in upcoming elections and make
                    your voice heard.</p>";
        sendEmail($subject, $message, $email);
    }
    // Close the statement and connection
    $stmt->close();
}

if(isset($_GET['verify_candidateId'])){
    $candidateId = $_GET['verify_candidateId'];
    $election_id = $_SESSION['election-id'];
    // Prepare and execute the SQL query to fetch voter data
    $sql = "SELECT candidate_id, name, section, id_number, front_id_image_path FROM candidates_table WHERE candidate_id = ? AND election_id = ?";
    $stmt = $con->prepare($sql);

    // Bind candidateId
    $stmt->bind_param("ii", $candidateId, $election_id);

    // Execute the statement
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($candidate_id, $candidate_name, $section, $idNumber, $candidate_IdFront);

    // Fetch the result
    if ($stmt->fetch()) {
        $candidateData = array(
            'name' => $candidate_name,
            'section' => $section,
            'id_number' => $idNumber,
            'candidate_id' => $candidate_id,
            'candidate_image' => $candidate_IdFront
        );

        // Encode the array as JSON and echo it
        echo json_encode($candidateData);
    } else {
        // If no data is found, return an empty JSON object
        echo json_encode(array());
    }

    // Close the statement and connection
    $stmt->close();
}

if(isset($_POST['verify_data_candidate'])){
    $candidateId = $_POST['verify_data_candidate'];
    $election_id = $_SESSION['election-id'];
    // Update query with prepared statement
    $updateQuery = "UPDATE candidates_table
    SET isData_verified = ?
    WHERE candidate_id = ? AND election_id = ?";

    // Prepare the statement
    $stmt = $con->prepare($updateQuery);

    // Bind parameters
    $verified = 1; // replace with your actual new value
    $stmt->bind_param("iii", $verified, $candidateId, $election_id);
    
    // Execute the prepared statement
    $successUpdate = $stmt->execute();
    if ($successUpdate) {
        $subject = "Candidate Registration";
        $message = "<p><b>Congratulations</b><br>
                    We are pleased to inform you that your candidate registration information has been successfully verified!
                    Thank you for taking the time to ensure the accuracy of your details.
                    Your commitment to providing accurate information is crucial in maintaining the integrity of the electoral
                    process. With your registration now verified, you're all set to participate in upcoming elections and make
                    your voice heard.</p>";
        sendEmail($subject, $message, $email);
    }
    // Close the statement and connection
    $stmt->close();
}

//show candidate platform
if(isset($_POST['candidateId'])){
    $candidateId = $_POST['candidateId'];
    $election_id = $_SESSION['election-id'];

    $sql = "SELECT candidates_table.*, positionstable.position_desc
            FROM candidates_table
            JOIN positionstable ON candidates_table.position = positionstable.position_id
            WHERE candidates_table.election_id = ? AND candidates_table.candidate_id = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $election_id, $candidateId);
    $stmt->execute();

    if ($stmt->error) {
        echo json_encode(['error' => 'Error executing the query: ' . $stmt->error]);
    } else {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        echo json_encode($row);
    }
}

//when view-ballot click
if(isset($_POST['voterId'])){
    $dataId = $_POST['voterId'];
    $election_id = $_SESSION['election-id'];
    $sql = "SELECT * FROM votestable WHERE voter_id = '$dataId' AND election_id = '$election_id'";
    $query = $con->query($sql);
	$row = $query->fetch_assoc();
    
    echo json_encode($row);      
}

//login-voter
if(isset($_POST['login_voter'])){
    $voterEmail = $_POST['voter_email'];
    $password = $_POST['voter_password'];

    $sql = "SELECT * FROM voters_table WHERE email = '$voterEmail' AND isData_verified = 1 AND isEmail_verified = 1";
    $query = $con->query($sql);
    if($query->num_rows < 1){
        $errors['voter-login'] = 'Voter email is not registered.';
    }
    else{
        $row = $query->fetch_assoc();
        if(password_verify($password, $row['voter_password'])){
            $_SESSION['voter'] = $row['voter_id'];
        }
        else{
            $errors['voter-login'] = 'Incorrect Password';
        }
    }
}

//check code 
if(isset($_POST["election_code"])){
    $code = $_POST["election_code"];
    $path = 'index.php?code=' . $_POST['election_code'];

    $email_check = "SELECT * FROM electiontable WHERE election_code = '$code'";
    $res = mysqli_query($con, $email_check);
    if(mysqli_num_rows($res) > 0){
        echo 'success';
    }
    else{
       echo 'failed';
    }
}

//show confirmation delete dialog
if(isset($_POST['show_modal_delete'])){
    $voterId = $_POST['show_modal_delete'];
    $election_id = $_SESSION['election-id'];
    $sql = "SELECT * FROM voterstable WHERE voter_userid ='$voterId' AND election_id = '$election_id'"; 
    $query = $con->query($sql);
	$row = $query->fetch_assoc();
    
    echo json_encode($row);  
}

//ajax request in the user page
if(isset($_POST["action"])){
    if($_POST["action"] == "logout"){
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        $_SESSION['success'] = 'Successfully signout. We will miss you.';
    }

    if ($_POST["action"] == "updateVoterTableData") {
        $election_id = $_SESSION['election-id'];
        $output = array(); // Initialize an array to hold the data
    
        $sql = "SELECT * FROM voters_table WHERE election_id = '$election_id' AND isData_verified = '1' AND isEmail_verified = '1'";
        $query = $con->query($sql);
    
        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $output[] = array(
                    'name' => $row["name"],
                    'section' => $row["section"],
                    'email' => $row["email"],
                    'id_number' => $row["id_number"],
                    'registration_timestamp' => $row["registration_timestamp"]
                );
            }
    
            // Convert the data to JSON format
            $jsonResponse = json_encode($output);
    
            // Set the Content-Type header to indicate JSON response
            header('Content-Type: application/json');
    
            // Output the JSON response
            echo $jsonResponse;
        }
    }

    if ($_POST["action"] == "updateUnverifiedVoterTableData") {
        $election_id = $_SESSION['election-id'];
        $output = array(); // Initialize an array to hold the data
    
        $sql = "SELECT * FROM voters_table WHERE election_id = '$election_id' AND isData_verified = '0' AND isEmail_verified = '1'";
        $query = $con->query($sql);
    
        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $output[] = array(
                    'voter_id' => $row["voter_id"],
                    'name' => $row["name"],
                    'section' => $row["section"],
                    'email' => $row["email"],
                    'id_number' => $row["id_number"],
                    'registration_timestamp' => $row["registration_timestamp"]
                );
            }
    
            // Convert the data to JSON format
            $jsonResponse = json_encode($output);
    
            // Set the Content-Type header to indicate JSON response
            header('Content-Type: application/json');
    
            // Output the JSON response
            echo $jsonResponse;
        } else {
            // If there is no data, return an empty JSON array
            echo json_encode([]);
        }
    }

    if ($_POST["action"] == "getUnverifiedVoterCount") {
        $election_id = $_SESSION['election-id'];
    
        $sql = "SELECT COUNT(*) AS row_count FROM voters_table WHERE election_id = '$election_id' AND isData_verified = '0' AND isEmail_verified = '1'";
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $rowCount = $row['row_count'];
    
            // Return the count as JSON
            echo json_encode(['rowCount' => $rowCount]);
        } else {
            // Return an error or default value
            echo json_encode(['rowCount' => 0]);
        }
    }

    if ($_POST["action"] == "updateCandidateTableData") {
        $election_id = $_SESSION['election-id'];
        $output = array(); // Initialize an array to hold the data
    
        $sql = "SELECT * FROM candidates_table WHERE election_id = '$election_id' AND isData_verified = '1' AND isEmail_verified = '1'";
        $query = $con->query($sql);
    
        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $output[] = array(
                    'name' => $row["name"],
                    'section' => $row["section"],
                    'email' => $row["email"],
                    'id_number' => $row["id_number"],
                    'registration_timestamp' => $row["registration_timestamp"],
                    'image' => $row["candidate_image_path"],
                    'position' => $row["position"]
                );
            }
    
            // Convert the data to JSON format
            $jsonResponse = json_encode($output);
    
            // Set the Content-Type header to indicate JSON response
            header('Content-Type: application/json');
    
            // Output the JSON response
            echo $jsonResponse;
        }
    }

    if ($_POST["action"] == "updateUnverifiedCandidateTableData") {
        $election_id = $_SESSION['election-id'];
        $output = array(); // Initialize an array to hold the data
    
        $sql = "SELECT * FROM candidates_table WHERE election_id = '$election_id' AND isData_verified = '0' AND isEmail_verified = '1'";
        $query = $con->query($sql);
    
        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $output[] = array(
                    'candidate_id' => $row["candidate_id"],
                    'name' => $row["name"],
                    'section' => $row["section"],
                    'email' => $row["email"],
                    'id_number' => $row["id_number"],
                    'registration_timestamp' => $row["registration_timestamp"],
                    'image' => $row["candidate_image_path"],
                    'position' => $row["position"]
                );
            }
    
            // Convert the data to JSON format
            $jsonResponse = json_encode($output);
    
            // Set the Content-Type header to indicate JSON response
            header('Content-Type: application/json');
    
            // Output the JSON response
            echo $jsonResponse;
        } else {
            // If there is no data, return an empty JSON array
            echo json_encode([]);
        }
    }

    if ($_POST["action"] == "getUnverifiedCandidateCount") {
        $election_id = $_SESSION['election-id'];
    
        $sql = "SELECT COUNT(*) AS row_count FROM candidates_table WHERE election_id = '$election_id' AND isData_verified = '0' AND isEmail_verified = '1'";
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $rowCount = $row['row_count'];
    
            // Return the count as JSON
            echo json_encode(['rowCount' => $rowCount]);
        } else {
            // Return an error or default value
            echo json_encode(['rowCount' => 0]);
        }
    }

    if ($_POST["action"] == "getUnverifiedCandidateCount") {
        $election_id = $_SESSION['election-id'];
    
        $sql = "SELECT COUNT(*) AS row_count FROM voters_table WHERE election_id = '$election_id' AND isData_verified = '0' AND isEmail_verified = '1'";
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $rowCount = $row['row_count'];
    
            // Return the count as JSON
            echo json_encode(['rowCount' => $rowCount]);
        } else {
            // Return an error or default value
            echo json_encode(['rowCount' => 0]);
        }
    }

    if($_POST["action"] == "otp_modal_closed"){
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        $_SESSION['error-otp'] = 'Please verify your new email to continue.';
    }

    if($_POST["action"] == "voter_signout"){
        unset($_SESSION['voter']);
    }

    if($_POST["action"] == "resend-otp"){
        if(isset($_SESSION['email'])){
            $code = rand(999999, 111111);
            $email = $_SESSION['email'];
            $check_email = "SELECT * FROM usertable WHERE email = '$email'";
            $res = mysqli_query($con, $check_email);
            if(mysqli_num_rows($res) > 0){
                $fetch = mysqli_fetch_assoc($res);
                $subject = "Email Verification Code";
                $message = "Your verification code is <b>$code</b>";

                $mail = new PHPMailer(true);

                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'blockchainbased.evs@gmail.com';
                $mail->Password = 'xnvxfrxgmcirirjm';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                $mail->setFrom('blockchainbased.evs@gmail.com');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $message;

                if($mail->send()){
                    $update_otp = "UPDATE usertable SET code = $code WHERE email = '$email'";
                    $update_res = mysqli_query($con, $update_otp);
                    if($update_res){
                        echo "success";
                    }else{
                        echo "failedtoupdate";
                    }
                }else{
                    echo "failedtosend";
                }
            }
        }
    }

    if($_POST["action"] == "createElection"){
        if(isset($_SESSION['email']) && isset($_SESSION['password'])){
            $email = $_SESSION['email'];
            $check_email = "SELECT * FROM usertable WHERE email = '$email'";
            $res = mysqli_query($con, $check_email);
            if(mysqli_num_rows($res) > 0){
                $fetch = mysqli_fetch_assoc($res);
                $status = $fetch['status'];
                if($status == 'verified') {
                    echo "verified";
                }
                else{
                    echo "unverified";
                }
            }
        }else{
            echo "signout";
        }
    }

    if($_POST["action"] == "load-data"){
        $election_id = $_SESSION['election-id'];
        $election_code = "";
        $sql = "SELECT * FROM electiontable WHERE election_id = '$election_id'";
        $query = mysqli_query($con, $sql);

        $dataElectionTable = array();
        if(mysqli_num_rows($query) > 0){
            $fetch = mysqli_fetch_assoc($query);
            $timezone = $fetch["timezone"];
            $election_code = $fetch["election_code"];

            $new_startdate_timezone = new DateTime($fetch["start_date"], new DateTimeZone($timezone));
            $new_enddate_timezone = new DateTime($fetch["end_date"], new DateTimeZone($timezone));
            $new_date_start = $new_startdate_timezone->format("Y-m-d H:i:s");
            $new_date_end = $new_enddate_timezone->format("Y-m-d H:i:s");
            $startdate = DateTime::createFromFormat("Y-m-d H:i:s", $new_date_start)->format('M j, Y h:i: A');
            $enddate = DateTime::createFromFormat("Y-m-d H:i:s", $new_date_end)->format('M j, Y h:i: A');

            $dataElectionTable = array(
                "timezone" => $timezone,
                "start_date" => $startdate,
                "end_date" => $enddate,
                "name" => $fetch["election_name"],
                "status" => $fetch["status"],
                "code" => $fetch["election_code"]
            );
        }

        $queryVoterRegistration = "SELECT * FROM voter_registration WHERE election_code = '$election_code'";
        $resultVoterRegistration = mysqli_query($con, $queryVoterRegistration);

        $dataVoterRegistration = array();
        if (mysqli_num_rows($resultVoterRegistration) > 0) {
            // Fetch and process the data
            $fetch = mysqli_fetch_assoc($resultVoterRegistration);
            $timezone = $fetch["voter_regis_timezone"];
            $new_startdate_timezone = new DateTime($fetch["voter_regis_startdate"], new DateTimeZone($timezone));
            $new_enddate_timezone = new DateTime($fetch["voter_regis_enddate"], new DateTimeZone($timezone));
            $new_date_start = $new_startdate_timezone->format("Y-m-d H:i:s");
            $new_date_end = $new_enddate_timezone->format("Y-m-d H:i:s");
            $startdate = DateTime::createFromFormat("Y-m-d H:i:s", $new_date_start)->format('M j, Y h:i: A');
            $enddate = DateTime::createFromFormat("Y-m-d H:i:s", $new_date_end)->format('M j, Y h:i: A');

            $dataVoterRegistration = array(
                "voterRegis_startDate" => $startdate,
                "voterRegis_endDate" => $enddate,
            );
        } else {
            $dataVoterRegistration = array(
                "voterRegis_startDate" => "",
                "voterRegis_endDate" => "",
            );
        }

        $queryCandidateRegistration = "SELECT * FROM candidate_registration WHERE election_code = '$election_code'";
        $resultCandidateRegistration = mysqli_query($con, $queryCandidateRegistration);

        $dataCandidateRegistration = array();
        if (mysqli_num_rows($resultCandidateRegistration) > 0) {
            // Fetch and process the data
            $fetch = mysqli_fetch_assoc($resultCandidateRegistration);
            $timezone = $fetch["candidate_regis_timezone"];
            $new_startdate_timezone = new DateTime($fetch["candidate_regis_startdate"], new DateTimeZone($timezone));
            $new_enddate_timezone = new DateTime($fetch["candidate_regis_enddate"], new DateTimeZone($timezone));
            $new_date_start = $new_startdate_timezone->format("Y-m-d H:i:s");
            $new_date_end = $new_enddate_timezone->format("Y-m-d H:i:s");
            $startdate = DateTime::createFromFormat("Y-m-d H:i:s", $new_date_start)->format('M j, Y h:i: A');
            $enddate = DateTime::createFromFormat("Y-m-d H:i:s", $new_date_end)->format('M j, Y h:i: A');

            $dataCandidateRegistration = array(
                "candidateRegis_startDate" => $startdate,
                "candidateRegis_endDate" => $enddate,
            );
        } else {
            $dataVoterRegistration = array(
                "candidateRegis_startDate" => "",
                "candidateRegis_endDate" => "",
            );
        }

        $combinedData = array("electionTable" => $dataElectionTable, "voterRegistration" => $dataVoterRegistration, "candidateRegistration" => $dataCandidateRegistration);
        echo json_encode($combinedData);
    }
    
    if($_POST["action"] == "load_launchData"){
        $election_id = $_SESSION['election-id'];
        $query = "SELECT 
            (SELECT COUNT(*) FROM positionstable WHERE election_id = '$election_id') AS position_count,
            (SELECT COUNT(*) FROM candidates_table WHERE isData_verified = 1 AND election_id = '$election_id') AS verified_candidates_count,
            (SELECT COUNT(*) FROM candidates_table WHERE isData_verified = 0 AND election_id = '$election_id') AS unverified_candidates_count,
            (SELECT COUNT(*) FROM voters_table WHERE isData_verified = 1 AND election_id = '$election_id') AS verified_voters_count,
            (SELECT COUNT(*) FROM voters_table WHERE isData_verified = 0 AND election_id = '$election_id') AS unverified_voters_count
            ";

        $result = mysqli_query($con, $query);

        if ($result) {
            $counts = mysqli_fetch_assoc($result);
            echo json_encode($counts);
        } else {
            echo json_encode(['error' => mysqli_error($con)]);
        }
    }

    if($_POST["action"] == "delete_election"){
        $election_id = $_SESSION['election-id'];
        $delete_election =" DELETE FROM electiontable WHERE election_id = '$election_id'";
        $data_delete = mysqli_query($con, $delete_election);
        if($data_delete){
            unset($_SESSION['election-id']);
            $_SESSION['success'] = "Election successfully deleted.";   
        }
        else{
            $_SESSION['error'] = "Error in deleting data in database!";
        }
    }

    if($_POST["action"] == "view_voterRegis"){
        $election_id = $_SESSION['election-id'];
        $election_code = "";
        $selectQuery = "SELECT election_code FROM electiontable WHERE election_id = '$election_id'";
        $result = mysqli_query($con, $selectQuery);
        if(mysqli_num_rows($result) > 0){
            $fetch = mysqli_fetch_assoc($result);
            $election_code = $fetch['election_code'];
        }

        $check_voterRecordQuery = "SELECT * FROM voter_registration WHERE election_code = '$election_code'";
        $res = mysqli_query($con, $check_voterRecordQuery);
        if(mysqli_num_rows($res) > 0){
            echo "recordExist";
        } 
    }

    if($_POST["action"] == "view_candidateRegis"){
        $election_id = $_SESSION['election-id'];
        $election_code = "";
        $selectQuery = "SELECT election_code FROM electiontable WHERE election_id = '$election_id'";
        $result = mysqli_query($con, $selectQuery);
        if(mysqli_num_rows($result) > 0){
            $fetch = mysqli_fetch_assoc($result);
            $election_code = $fetch['election_code'];
        }

        $check_candidateRecordQuery = "SELECT * FROM candidate_registration WHERE election_code = '$election_code'";
        $res = mysqli_query($con, $check_candidateRecordQuery);
        if(mysqli_num_rows($res) > 0){
            echo "recordExist";
        } 
    }

    if($_POST["action"] == "resend_voterOtp"){
        $otp = generateOTP();
        $_SESSION['voter-otp'] = $otp;
        $voter_emailAdd = $_SESSION['voter_email'];
        $subject = "Email Verification Code";
        $message = "Your new verification code is <b>$otp</b>";
        if (sendEmail($subject, $message, $voter_emailAdd)) {
            echo json_encode(['success' => true, 'message' => 'OTP resent successfully.']);
        }       
    }

    if($_POST["action"] == "resend_candidateOtp"){
        $otp = generateOTP();
        $_SESSION['candidate-otp'] = $otp;
        $candidate_emailAdd = $_SESSION['candidate_email'];
        $subject = "Email Verification Code";
        $message = "Your new verification code is <b>$otp</b>";
        if (sendEmail($subject, $message, $candidate_emailAdd)) {
            echo json_encode(['success' => true, 'message' => 'OTP resent successfully.']);
        }       
    }

    if($_POST["action"] == "launch-election"){
        $election_id = $_SESSION['election-id'];
        $getDate = "SELECT start_date, end_date FROM electiontable WHERE election_id = '$election_id'";
        $result = $con->query($getDate);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $start_date = $row['start_date'];
            $end_date = $row['end_date'];

            // Compare start_date with current date and time
            $current_datetime = date("Y-m-d H:i:s");
            
            if ($start_date > $current_datetime) {
                // Set the status variable to 'scheduled'
                $status = 'scheduled';
            }

            $sql = "UPDATE electiontable
                    SET status = '$status'
                    WHERE election_id = '$election_id'";
            if ($con->query($sql) === TRUE) {
                sendEmailtoVoters($con);
                $response = array('status' => $status, 'start_date' => $start_date, 'end_date' => $end_date, 'election_id' => $election_id);
                echo json_encode($response);
            } else {
                echo "Error updating status: " . $con->error;
            }
        }
    }

    if($_POST["action"] == "update-status-running"){        
        $election_id = $_POST['election_id'];
        $status = "running";
       
        $sqlUpdate = "UPDATE electiontable SET status = '$status' WHERE election_id = '$election_id'";

        if ($con->query($sqlUpdate) === TRUE) {
            $response = array('status' => $status);
            echo json_encode($response);
        } else {
            echo json_encode(array('error' => 'Error updating status: ' . $con->error));
        }
    }

    if($_POST["action"] == "update-status-completed"){    
        $election_id = $_POST['election_id'];
        $status = "completed";
        $sqlUpdate = "UPDATE electiontable SET status = '$status' WHERE election_id = '$election_id'";

        if ($con->query($sqlUpdate) === TRUE) {
            sendElectionEnded($con);
            $response = array('status' => $status);
            echo json_encode($response);
        } else {
            echo "Error updating status: " . $con->error;
        }
    }
}

function sendElectionEnded($con) {
    $election_id = $_SESSION['election-id'];
    $getData = "SELECT * FROM electiontable WHERE election_id = '$election_id'";
    $result = $con->query($getData);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $election_name = $row['election_name'];
        $election_code = $row['election_code'];

        $sql = "SELECT email FROM voters_table WHERE election_id = '$election_id'";
        $resultVoters = $con->query($sql);
        
        if ($resultVoters->num_rows > 0) {
            // Fetch all rows as an associative array
            while ($row = $resultVoters->fetch_assoc()) {
                $baseUrl = BASE_URL;
                $election_link_home = $baseUrl . "index.php";
                $election_link = $baseUrl . "voters/index.php";
                $election_linkCode = $baseUrl . "voters/index.php?code=" . $election_code;
                
                $to = $row['email'];
                $subject = "$election_name";
                $message = "<h1>The $election_name has been ended.</h1>
                            <h3>Please go to the link provided so view the election results.</h3>
        
                            <h3>Please login using the credentials you registered.</h3>
        
                            <h3>To view results you can click the link below or go to <a href='$election_link_home'>Blockchain-Based EVS</a> website to insert the election code below.</h3>
                            <p>Election Link: <a href='$election_linkCode'>$election_link</a></p>
                            <p>Election Code: <b>$election_code</b></p>
                            ";

                sendEmail($subject, $message, $to);
            }
        }
    }
}

//filter election page using dropdown
if(isset($_POST["status_val"])){
    $status_election = $_POST["status_val"];
    $output = "";
    $sql = "";

    require_once ROOT_PATH ."config/session.php";

    if(isset($_SESSION['email']) && isset($_SESSION['password']) && $status == 'verified'){
        $email = $_SESSION['email'];

        if($status_election == "all") {
            $sql = "SELECT * FROM electiontable WHERE created_by = '$email' ORDER BY date_created DESC";
        }
        elseif($status_election == "building") {
            $sql = "SELECT * FROM electiontable WHERE created_by = '$email' AND status = 'building' ORDER BY date_created DESC"; 
        }
        elseif($status_election == "scheduled") {
            $sql = "SELECT * FROM electiontable WHERE created_by = '$email' AND status = 'scheduled' ORDER BY date_created DESC";  
        }
        elseif($status_election == "running") {
            $sql = "SELECT * FROM electiontable WHERE created_by = '$email' AND status = 'running' ORDER BY date_created DESC"; 
        }
        elseif($status_election == "completed") {
            $sql = "SELECT * FROM electiontable WHERE created_by = '$email' AND status = 'completed' ORDER BY date_created DESC"; 
        }
        $query = mysqli_query($con, $sql);
        if(mysqli_num_rows($query) > 0){
            while($row = $query->fetch_assoc()){
                $timezone = $row["timezone"];

                $new_startdate_timezone = new DateTime($row["start_date"], new DateTimeZone($timezone));
                $new_enddate_timezone = new DateTime($row["end_date"], new DateTimeZone($timezone));

                $new_date_start = $new_startdate_timezone->format("Y-m-d H:i:s");
                $new_date_end = $new_enddate_timezone->format("Y-m-d H:i:s");

                $startdate = DateTime::createFromFormat("Y-m-d H:i:s", $new_date_start)->format('M j, Y h:i: A');
                $enddate = DateTime::createFromFormat("Y-m-d H:i:s", $new_date_end)->format('M j, Y h:i: A');

                $output .= '
                <div class="row p-2 bg-light rounded-3 me-3 mb-4 election" data-election-id="'.$row["election_id"].'">
                    <div class="col-6">
                    <h1 class="fs-4 pt-2 text-black">'.$row["election_name"].'</h1>
                    <div class="row">
                        <div class="col-auto">
                        <p class="fs-6 p-1 border border-black rounded text-uppercase text-black">'.$row["status"].'</p>
                        </div>
                    </div>
                    </div>
                    <div class="col-3 d-flex align-items-center">
                    <div class="row">
                        <p class="fs-6 mb-0 text-black"><i class="fa-solid fa-calendar-days me-2"></i>Start Date</p>
                        <p class="fs-6 mb-0 text-black">'.$startdate.'</p>
                    </div>                         
                    </div>
                    <div class="col-3 d-flex align-items-center">
                    <div class="row">
                        <p class="fs-6 mb-0 text-black"><i class="fa-solid fa-calendar-days me-2"></i>End Date</p>
                        <p class="fs-6 mb-0 text-black">'.$enddate.'</p>
                    </div>               
                    </div>
                </div>
                ';
            }
            echo $output;
        }
        else {
            echo '
                <h1 class="fs-5 text-center text-dark mt-5">No Records Found.</h1>
            ';
        }
    }
}

//get the user data
if(isset($_POST["userEmail"])){
    $email = $_POST["userEmail"];
    $sql = "SELECT * FROM usertable WHERE email = '$email'";
    $query = $con->query($sql);
	$row = $query->fetch_assoc();
    
    echo json_encode($row);  
}

//if save changes clicked in user settings modal
if(isset($_POST["save_user_info"])){
    $userId = $_POST["save_user_info"];
    $userName = mysqli_real_escape_string($con, $_POST['user_name']);
    $userEmail = mysqli_real_escape_string($con, $_POST['user_email']);
    $currentPassword = mysqli_real_escape_string($con, $_POST['user_cpass']);
    $newPassword = mysqli_real_escape_string($con, $_POST['user_npass']);
    $confirmNewPassword = mysqli_real_escape_string($con, $_POST['user_npass_confirm']);

    $email_isEdited = true;
    $password_isEdited = false;

    //Get user info from db
    $sql = "SELECT * FROM usertable WHERE id = '$userId'";
    $res = mysqli_query($con, $sql);
    if(mysqli_num_rows($res) > 0){
        $fetch = mysqli_fetch_assoc($res);
        $status = $fetch['status'];
        $user_photo = $fetch['user_photo'];
        $user_id = $fetch['id'];
        $user_passFromDb = $fetch['password'];
        $user_emailFromDb = $fetch['email'];

        //check the email if edited
        if($userEmail !== $user_emailFromDb){
            $check_newEmail = "SELECT email FROM usertable WHERE email = '$userEmail'";
            $result = mysqli_query($con, $check_newEmail);
            if(mysqli_num_rows($result) > 0){
                $errors['newEmail'] = "Email Address already exist!";
                echo "
                    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
                    <Script>
                        $(document).ready(function(){
                            $('#user_account').modal('show');
                        });
                    </Script>
                ";
            }
        }
        else{
            $email_isEdited = false;
        }

        //check the password if similar to db
        if(!empty($_POST['user_cpass'])){
            $verify = password_verify($currentPassword, $user_passFromDb);
            if(!$verify){
                $errors['password'] = "Current Password Incorrect!";
                echo "
                    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
                    <Script>
                        $(document).ready(function(){
                            $('#user_account').modal('show');
                        });
                    </Script>
                ";
            }
        }        
    }

    //check if new password is not empty
    if(!empty($_POST['user_npass']) || !empty($_POST['user_npass_confirm'])){
        if($newPassword !== $confirmNewPassword){
            $errors['password'] = "Confirm password not matched!";
            echo "
                <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
                <Script>
                    $(document).ready(function(){
                        $('#user_account').modal('show');
                    });
                </Script>
            ";
        }
        else {
            $password_isEdited = true;
        }
    }
    else{
        $password_isEdited = false;
    }

    //if all input is valid and no error
    if(count($errors) === 0){
        $encpass = password_hash($newPassword, PASSWORD_BCRYPT);

        if($email_isEdited === false && $password_isEdited === false) {
            if($_FILES['user_image_edit']['size'] == 0) {
                $sql = "UPDATE usertable SET name = '$userName' WHERE id = '$userId'";
                
                if($query = $con->query($sql)) {
                    $success['edit-user'] = "Your account successfully updated.";
                } else {
                    $errors['db-error'] = "Failed while updating data into database!";
                }
            } else {
                if(isset($_FILES['user_image_edit'])){
                    $file_name = $_FILES['user_image_edit']['name'];
                    $file_tmp = $_FILES['user_image_edit']['tmp_name'];
                    $file_type = $_FILES['user_image_edit']['type'];
                    $txt = explode('.',$_FILES['user_image_edit']['name']);
                    $text = end($txt);
                    $file_ext = strtolower($text);
                    
                    $extensions= array("jpeg","jpg","png");
                    
                    if(in_array($file_ext,$extensions)=== false){
                        $errors['edit-user'] = "extension not allowed, please choose a JPEG or PNG file.";
                    }
                    
                    if(empty($errors)==true) {
                        if(move_uploaded_file($file_tmp, ROOT_PATH ."uploads/".$file_name)) {
                            $sql = "UPDATE usertable SET name = '$userName', user_photo = '$file_name' WHERE id = '$userId'";
                
                            if($query = $con->query($sql)) {
                                $success['edit-user'] = "Your account successfully updated.";
                            } else {
                                $errors['db-error'] = "Failed while updating data into database!";
                            }
                        }
                    } else {
                        $errors['upload-image'] = "Error uploading image.";
                    }
                } 
            }
        }
        else if($email_isEdited === false && $password_isEdited === true) {
            if($_FILES['user_image_edit']['size'] == 0) {
                $sql = "UPDATE usertable SET name = '$userName', password = '$encpass' WHERE id = '$userId'";
                
                if($query = $con->query($sql)) {
                    $success['edit-user'] = "Your account successfully updated.";
                } else {
                    $errors['db-error'] = "Failed while updating data into database!";
                }
            } else {
                if(isset($_FILES['user_image_edit'])){
                    $file_name = $_FILES['user_image_edit']['name'];
                    $file_tmp = $_FILES['user_image_edit']['tmp_name'];
                    $file_type = $_FILES['user_image_edit']['type'];
                    $txt = explode('.',$_FILES['user_image_edit']['name']);
                    $text = end($txt);
                    $file_ext = strtolower($text);
                    
                    $extensions= array("jpeg","jpg","png");
                    
                    if(in_array($file_ext,$extensions)=== false){
                        $errors['edit-user'] = "extension not allowed, please choose a JPEG or PNG file.";
                    }
                    
                    if(empty($errors)==true) {
                        if(move_uploaded_file($file_tmp, ROOT_PATH ."uploads/".$file_name)) {
                            $sql = "UPDATE usertable SET name = '$userName', user_photo = '$file_name', password = '$encpass' WHERE id = '$userId'";
                
                            if($query = $con->query($sql)) {
                                $success['edit-user'] = "Your account successfully updated.";
                            } else {
                                $errors['db-error'] = "Failed while updating data into database!";
                            }
                        }
                    } else {
                        $errors['upload-image'] = "Error uploading image.";
                    }
                } 
            }
        }
        else if($email_isEdited === true && $password_isEdited === false) {
            $code = rand(999999, 111111);
            $status = "notverified";
    
            $subject = "Email Verification Code";
            $message = "Your verification code is <b>$code</b>";
    
            $mail = new PHPMailer(true);
    
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'blockchainbased.evs@gmail.com';
            $mail->Password = 'xnvxfrxgmcirirjm';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
    
            $mail->setFrom('blockchainbased.evs@gmail.com');
            $mail->addAddress($userEmail);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;
    
            if($mail->send()){
                $info = "We sent a verification code to your email - $userEmail";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $userEmail;
    
                if($_FILES['user_image_edit']['size'] == 0) {
                    $sql = "UPDATE usertable SET name = '$userName', email = '$userEmail', code = '$code', status = '$status' WHERE id = '$userId'";
                    
                    if($query = $con->query($sql)) {
                        $success['edit-user'] = "Your account successfully updated. Please verify your new email.";
                        echo "
                            <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
                            <Script>
                                $(document).ready(function(){
                                    $('#otp_panel').modal('show');

                                    $(document).on('hidden.bs.modal','#otp_panel', function () {
                                        $.ajax({
                                            type: 'POST',
                                            url: '/Blockchain-Based_EVS/config/controllerUserData.php',
                                            data: {action:'otp_modal_closed'},
                                            success: function () {
                                                window.location.href = '/Blockchain-Based_EVS/index.php';
                                            }
                                        });
                                    });
                                });
                            </Script>
                        ";
                    } else {
                        $errors['db-error'] = "Failed while updating data into database!";
                    }
                } else {
                    if(isset($_FILES['user_image_edit'])){
                        $file_name = $_FILES['user_image_edit']['name'];
                        $file_tmp = $_FILES['user_image_edit']['tmp_name'];
                        $file_type = $_FILES['user_image_edit']['type'];
                        $txt = explode('.',$_FILES['user_image_edit']['name']);
                        $text = end($txt);
                        $file_ext = strtolower($text);
                        
                        $extensions= array("jpeg","jpg","png");
                        
                        if(in_array($file_ext,$extensions)=== false){
                            $errors['edit-user'] = "extension not allowed, please choose a JPEG or PNG file.";
                        }
                        
                        if(empty($errors)==true) {
                            if(move_uploaded_file($file_tmp, ROOT_PATH ."uploads/".$file_name)) {
                                $sql = "UPDATE usertable SET name = '$userName', email = '$userEmail', code = '$code', status = '$status', user_photo = '$file_name' WHERE id = '$userId'";
                    
                                if($query = $con->query($sql)) {
                                    $success['edit-user'] = "Your account successfully updated. Please verify your new email.";
                                    echo "
                                        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
                                        <Script>
                                            $(document).ready(function(){
                                                $('#otp_panel').modal('show');
                                            });
                                        </Script>
                                    ";
                                } else {
                                    $errors['db-error'] = "Failed while updating data into database!";
                                }
                            }
                        } else {
                            $errors['upload-image'] = "Error uploading image.";
                        }
                    } 
                }
            } else {
                $errors['otp-error'] = "Failed while sending code!";
            }
        }
        else if($email_isEdited === true && $password_isEdited === true) {
            $code = rand(999999, 111111);
            $status = "notverified";
    
            $subject = "Email Verification Code";
            $message = "Your verification code is <b>$code</b>";
    
            $mail = new PHPMailer(true);
    
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'blockchainbased.evs@gmail.com';
            $mail->Password = 'xnvxfrxgmcirirjm';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
    
            $mail->setFrom('blockchainbased.evs@gmail.com');
            $mail->addAddress($userEmail);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;
    
            if($mail->send()){
                $info = "We sent a verification code to your email - $userEmail";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $userEmail;
    
                if($_FILES['user_image_edit']['size'] == 0) {
                    $sql = "UPDATE usertable SET name = '$userName', email = '$userEmail', code = '$code', status = '$status', password = '$encpass' WHERE id = '$userId'";
                    
                    if($query = $con->query($sql)) {
                        $success['edit-user'] = "Your account successfully updated. Please verify your new email.";
                        echo "
                            <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
                            <Script>
                                $(document).ready(function(){
                                    $('#otp_panel').modal('show');
                                });
                            </Script>
                        ";
                    } else {
                        $errors['db-error'] = "Failed while updating data into database!";
                    }
                } else {
                    if(isset($_FILES['user_image_edit'])){
                        $file_name = $_FILES['user_image_edit']['name'];
                        $file_tmp = $_FILES['user_image_edit']['tmp_name'];
                        $file_type = $_FILES['user_image_edit']['type'];
                        $txt = explode('.',$_FILES['user_image_edit']['name']);
                        $text = end($txt);
                        $file_ext = strtolower($text);
                        
                        $extensions= array("jpeg","jpg","png");
                        
                        if(in_array($file_ext,$extensions)=== false){
                            $errors['edit-user'] = "extension not allowed, please choose a JPEG or PNG file.";
                        }
                        
                        if(empty($errors)==true) {
                            if(move_uploaded_file($file_tmp, ROOT_PATH ."uploads/".$file_name)) {
                                $sql = "UPDATE usertable SET name = '$userName', email = '$userEmail', code = '$code', status = '$status', user_photo = '$file_name', password = '$encpass' WHERE id = '$userId'";
                    
                                if($query = $con->query($sql)) {
                                    $success['edit-user'] = "Your account successfully updated. Please verify your new email.";
                                    echo "
                                        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
                                        <Script>
                                            $(document).ready(function(){
                                                $('#otp_panel').modal('show');
                                            });
                                        </Script>
                                    ";
                                } else {
                                    $errors['db-error'] = "Failed while updating data into database!";
                                }
                            }
                        } else {
                            $errors['upload-image'] = "Error uploading image.";
                        }
                    } 
                }
            } else {
                $errors['otp-error'] = "Failed while sending code!";
            }
        }
    }
}

//edit general settings
if(isset($_POST["save_changes_btn_set"])){
    $election_id = $_SESSION['election-id'];
    $electionname = mysqli_real_escape_string($con, $_POST['election_name']);
    $startdate = mysqli_real_escape_string($con, $_POST['start_date']);
    $enddate = mysqli_real_escape_string($con, $_POST['end_date']);
    $timezone = mysqli_real_escape_string($con, $_POST['timezone']);

    if($startdate >= $enddate){
        $errors['date'] = "End Date must be after Start Date!";
    }


    if(count($errors) === 0){
        $update_data = "UPDATE electiontable 
                        SET election_name = '$electionname', start_date = '$startdate', end_date = '$enddate', timezone = '$timezone' 
                        WHERE election_id = '$election_id' ";
        $data_check = mysqli_query($con, $update_data);
        if($data_check){
            $success['update'] = "Election successfully updated.";   
        }
        else{
            $errors['update-election'] = "Error in updating data in database!";
        }
    } 
}

//Voter Registration settings
if(isset($_POST["save_voterRegis_set"])){
    $election_id = $_SESSION['election-id'];
    $startdate = mysqli_real_escape_string($con, $_POST['start-date_voterRegis']);
    $enddate = mysqli_real_escape_string($con, $_POST['end-date_voterRegis']);
    $timezone = mysqli_real_escape_string($con, $_POST['timezone-voterRegis']);
    
    if($startdate >= $enddate){
        $errors['date'] = "End Date must be after Start Date!";
    }

    if(count($errors) === 0){
        $selectQuery = "SELECT election_code FROM electiontable WHERE election_id = '$election_id'";
        $result = mysqli_query($con, $selectQuery);
        if(mysqli_num_rows($result) > 0){
            $fetch = mysqli_fetch_assoc($result);
            $election_code = $fetch['election_code'];
        }

        if (isset($_POST['voter_regisId']) && !empty($_POST['voter_regisId'])) {
            // Update operation
            $recordId = $_POST['voter_regisId'];
            $updateQuery = "UPDATE voter_registration SET voter_regis_startdate = ?, voter_regis_enddate = ?, voter_regis_timezone = ? WHERE voter_registration_id = ?";
            $updateStmt = mysqli_prepare($con, $updateQuery);

            // Bind parameters
            mysqli_stmt_bind_param($updateStmt, 'sssi', $startdate, $enddate, $timezone, $recordId);

            // Execute the update
            $updateResult = mysqli_stmt_execute($updateStmt);
            if ($updateResult) {
                $success['update'] = "Registration period successfully updated.";  
            } else {
                $errors['update-data'] = "Error in inserting data in database!" . mysqli_error($con);
            }

            mysqli_stmt_close($updateStmt);
            mysqli_close($con);
        } else {
            $insert_data = "INSERT INTO voter_registration (election_code, voter_regis_startdate, voter_regis_enddate, voter_regis_timezone)
            VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $insert_data);
            mysqli_stmt_bind_param($stmt, 'ssss', $election_code, $startdate, $enddate, $timezone);
            $data_check = mysqli_stmt_execute($stmt);
            
            if($data_check) {
                $success['insert'] = "Registration period successfully saved.";  
            } else {
                $errors['insert-data'] = "Error in inserting data in database!" . mysqli_error($con);
            }

            mysqli_stmt_close($stmt);
            mysqli_close($con);
        }
    }
}

//Candidate Registration settings
if(isset($_POST["save_candidateRegis_set"])){
    $election_id = $_SESSION['election-id'];
    $startdate = mysqli_real_escape_string($con, $_POST['start-date_candidateRegis']);
    $enddate = mysqli_real_escape_string($con, $_POST['end-date_candidateRegis']);
    $timezone = mysqli_real_escape_string($con, $_POST['timezone-candidateRegis']);
    
    if($startdate >= $enddate){
        $errors['date'] = "End Date must be after Start Date!";
    }

    if(count($errors) === 0){
        $selectQuery = "SELECT election_code FROM electiontable WHERE election_id = '$election_id'";
        $result = mysqli_query($con, $selectQuery);
        if(mysqli_num_rows($result) > 0){
            $fetch = mysqli_fetch_assoc($result);
            $election_code = $fetch['election_code'];
        }

        if (isset($_POST['candidate_regisId']) && !empty($_POST['candidate_regisId'])) {
            // Update operation
            $recordId = $_POST['candidate_regisId'];
            $updateQuery = "UPDATE candidate_registration SET candidate_regis_startdate = ?, candidate_regis_enddate = ?, candidate_regis_timezone = ? WHERE candidate_registration_id = ?";
            $updateStmt = mysqli_prepare($con, $updateQuery);

            // Bind parameters
            mysqli_stmt_bind_param($updateStmt, 'sssi', $startdate, $enddate, $timezone, $recordId);

            // Execute the update
            $updateResult = mysqli_stmt_execute($updateStmt);
            if ($updateResult) {
                $success['update'] = "Registration period successfully updated.";  
            } else {
                $errors['update-data'] = "Error in upadating data in database!" . mysqli_error($con);
            }

            mysqli_stmt_close($updateStmt);
            mysqli_close($con);
        } else {
            $insert_data = "INSERT INTO candidate_registration (election_code, candidate_regis_startdate, candidate_regis_enddate, candidate_regis_timezone)
            VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $insert_data);
            mysqli_stmt_bind_param($stmt, 'ssss', $election_code, $startdate, $enddate, $timezone);
            $data_check = mysqli_stmt_execute($stmt);
            
            if($data_check) {
                $success['insert'] = "Registration period successfully saved.";  
            } else {
                $errors['insert-data'] = "Error in inserting data in database!" . mysqli_error($con);
            }

            mysqli_stmt_close($stmt);
            mysqli_close($con);
        }
    }
}

//Delete Election
if(isset($_POST["delete_election_btn_click"])){
    $election_id = $_SESSION['election-id'];
    $delete_election =" DELETE FROM electiontable WHERE election_id = '$election_id'";
    $data_delete = mysqli_query($con, $delete_election);
    if($data_delete){
        unset($_SESSION['election-id']);
        $success['delete'] = "Election successfully deleted.";   
    }
    else{
        $errors['delete-election'] = "Error in deleting data in database!";
    }
}

//When the voter click the register button
if(isset($_POST["register_voterBtn"])){
    $election_id = $_SESSION['election-id'];
    
    $name_lower = $_POST["regis-voter-name"];
    $voter_name = strtoupper($name_lower);
    
    $section_lower =  $_POST["regis-voter-section"];
    $voter_section = strtoupper($section_lower);
    
    $voter_emailAdd = $_POST["regis-voter-email"];
    $voter_idNumber = $_POST["regis-voter-idNumber"];
    $voter_password = $_POST["regis-voter-password"];
    $confirm_voter_password = $_POST["confirm-voter-password"];
    $hashedPassword = password_hash($voter_password, PASSWORD_DEFAULT);
    $isDataVerified = 1;
    $ethereumKeys = generateEthereumKeys();
    $address = $ethereumKeys['address'];
    $privateKey = $ethereumKeys['privateKey'];

    // Define the pattern for the allowed email domain
    $allowedDomain = 'plpasig.edu.ph';

    //Check the confirm password if the same
    if ($voter_password !== $confirm_voter_password) {
        // Passwords do not match
        $errors['registration'] = 'Passwords do not match!';
    }

    // Perform server-side validation
    if (filter_var($voter_emailAdd, FILTER_VALIDATE_EMAIL)) {
        $emailParts = explode('@', $voter_emailAdd);
        $domain = end($emailParts);

        if ($domain !== $allowedDomain) {
            // Valid email address
            $errors['registration'] = 'Please use your PLP email address.';
        }
    } else {
        // Invalid email format
        $errors['registration'] = 'Invalid email format.';
    }

    // Check if email and ID number already exist
    $emailCheckQuery = "SELECT COUNT(*) FROM voters_table WHERE email = ? AND election_id = ?";
    $idNumberCheckQuery = "SELECT COUNT(*) FROM voters_table WHERE id_number = ? AND election_id = ?";

    $stmtEmailCheck = mysqli_prepare($con, $emailCheckQuery);
    $stmtIdNumberCheck = mysqli_prepare($con, $idNumberCheckQuery);

    mysqli_stmt_bind_param($stmtEmailCheck, 'si', $voter_emailAdd, $election_id);
    mysqli_stmt_bind_param($stmtIdNumberCheck, 'si', $voter_idNumber, $election_id);

    mysqli_stmt_execute($stmtEmailCheck);
    mysqli_stmt_bind_result($stmtEmailCheck, $emailCount);
    mysqli_stmt_fetch($stmtEmailCheck);
    mysqli_stmt_close($stmtEmailCheck);
    
    mysqli_stmt_execute($stmtIdNumberCheck);
    mysqli_stmt_bind_result($stmtIdNumberCheck, $idNumberCount);
    mysqli_stmt_fetch($stmtIdNumberCheck);
    mysqli_stmt_close($stmtIdNumberCheck);

    // Check if email or ID number already exists
    if ($emailCount > 0) {
        //if email exist check if it is verified
        $isEmailVerified = "SELECT isEmail_verified FROM voters_table WHERE email = ? AND election_id = ?";
        $stmtEmailVerified = mysqli_prepare($con, $isEmailVerified);
        mysqli_stmt_bind_param($stmtEmailVerified, "si", $voter_emailAdd, $election_id);
        mysqli_stmt_execute($stmtEmailVerified);
        mysqli_stmt_bind_result($stmtEmailVerified, $isEmailVerifiedResult);
        mysqli_stmt_fetch($stmtEmailVerified);
        // Check the result
        if ($isEmailVerifiedResult == 1) {
            $_SESSION['voter_email_verified'] = true;
            header('location: voter-success.php');
            exit();
        } else {
            $otp = generateOTP();
            $_SESSION['voter-otp'] = $otp;
            $_SESSION['voter_email'] = $voter_emailAdd;
            $subject = "Email Verification Code";
            $message = "Your verification code is <b>$otp</b>";
            if (sendEmail($subject, $message, $voter_emailAdd)) {
                header('location: voter-otp.php');   
                exit();
            } else {
                $errors['registration'] = "Error sending OTP code."; 
            } 
        }
        // Close the statement
        mysqli_stmt_close($stmtEmailVerified);
    } 
    if ($idNumberCount > 0) {
        $errors['registration'] = "ID number already registered. Please check your ID number.";
    }

    // Check if there are no errors
    if (empty($errors)) {
        // Continue with the registration process

        if (isset($_FILES['regis-voter-image-front']) && isset($_FILES['regis-voter-image-back'])) {
            // Process Image 
            $imageFront = processImage($_FILES['regis-voter-image-front']);
            $imageBack = processImage($_FILES['regis-voter-image-back']);
    
            // Decode the image
            try{
                $result = (new QRCode)->readFromFile($imageBack['targetFile']); // -> DecoderResult
                $qrCodeData = $result->data;
    
                // Determine the verification status
                $isDataVerified = verifyData($qrCodeData, $voter_name, $voter_section, $voter_idNumber) ? 1 : 0;
            }
            catch(Throwable $exception){
                $isDataVerified = 0;
            }
            
            //insert to database
            $sql = "INSERT INTO voters_table (name, section, email, id_number, front_id_image_path, back_id_image_path, voter_password, isData_verified, election_id, privateKey, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $sql);

            // Bind parameters
            mysqli_stmt_bind_param($stmt, 'sssssssiiss', $voter_name, $voter_section, $voter_emailAdd, $voter_idNumber, $imageFront['imageUrl'], $imageBack['imageUrl'], $hashedPassword, $isDataVerified, $election_id, $privateKey, $address);

            // Execute the statement
            $result = mysqli_stmt_execute($stmt);
            $stmt->close();

            if ($result) {
                $otp = generateOTP();
                $_SESSION['voter-otp'] = $otp;
                $_SESSION['voter_email'] = $voter_emailAdd;
                $subject = "Email Verification Code";
                $message = "Your verification code is <b>$otp</b>";
                if (sendEmail($subject, $message, $voter_emailAdd)) {
                    header('location: voter-otp.php');   
                    exit();
                } else {
                    $errors['registration'] = "Error sending OTP code."; 
                } 
            } else {
                echo 'Error: ' . $sql . '<br>' . mysqli_error($con);
            }
            mysqli_close($con);
        } else {
            echo 'Invalid file uploads.';
        }
    }
}

//when click verify otp of voter
if(isset($_POST["verify_voterOTPBtn"])){
    $election_id = $_SESSION['election-id'];
    $voter_email = $_SESSION['voter_email'];
    // Validate OTP
    $enteredOTP = $_POST['regis-voter-OTP'];  // Get OTP from the submitted form
    $storedOTP = $_SESSION['voter-otp'];
    $verified = 1;

    if ($enteredOTP === $storedOTP) {
        $get_isDataVerified = "SELECT isData_verified FROM voters_table WHERE email = ? AND election_id = ?";
        $stmtCheckData = $con->prepare($get_isDataVerified);
        
        if ($stmtCheckData) {
            $stmtCheckData->bind_param("si", $voter_email, $election_id);    
            $stmtCheckData->execute();    
            $stmtCheckData->bind_result($isDataVerified);    
            $stmtCheckData->fetch();    
            if ($isDataVerified == 1) {
                $subject = "Voters Registration";
                $message = "<p><b>Congratulations</b> on successfully registering to vote! Your commitment to participate is commendable, 
                            and we're delighted to welcome you to the voter community. Stay tuned for important updates, election 
                            information, and exclusive insights that will keep you informed. As elections approach, we'll provide 
                            you with all the necessary details to ensure your voting experience is smooth and impactful. 
                            Remember, every vote counts!</p>";
                sendEmail($subject, $message, $voter_email);
            } else {
                $subject = "Voters Registration";
                $message = "<p>We hope this message finds you well. We regret to inform you that the data provided during your voter 
                            registration process is currently unverified. It's crucial that we ensure the accuracy of voter information 
                            to maintain the integrity of the electoral process. Please wait for the election creator to manually verify 
                            your data. Stay tuned for updates.</p>";
                sendEmail($subject, $message, $voter_email);
            }
            // Close the statement
            $stmtCheckData->close();
        } else {
            // Handle the case where the statement preparation fails
            $errors['check-data'] = "Error preparing statement.";
        }

        $sql = "UPDATE voters_table SET isEmail_verified = ? WHERE email = ? AND election_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("isi", $verified, $voter_email, $election_id);
        if ($stmt->execute()) {
            unset($_SESSION['voter-otp']);
            unset($_SESSION['voter_email']);
            $_SESSION['voter_email_verified'] = true;
            header('location: voter-success.php');
            exit();
        } else {
            $errors['update'] = "Error updating column: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Invalid OTP
        $errors['invalid-otp'] = "Invalid OTP. Please try again.";
    }
}

//When the candidate click the register button
if(isset($_POST["regis-candidate-name"])){
    $election_id = $_SESSION['election-id'];
    
    $candidate_name_lower = $_POST["regis-candidate-name"];
    $candidate_name = strtoupper($candidate_name_lower);
    
    $candidate_section_lower = $_POST["regis-candidate-section"];
    $candidate_section = strtoupper($candidate_section_lower);
    
    $candidate_emailAdd = $_POST["regis-candidate-email"];
    $candidate_idNumber = $_POST["regis-candidate-idNumber"];
    $candidate_position = $_POST["regis-candidate-position"];
    $candidate_platform = $_POST["quillContent"];
    $candidate_Image = $_POST["croppedImage"];
    $candidate_password = $_POST["regis-candidate-password"];
    $confirm_candidate_password = $_POST["confirm-candidate-password"];
    $hashedPassword = password_hash($candidate_password, PASSWORD_DEFAULT);

    $isDataVerified = 1;
    
    $ethereumKeys = generateEthereumKeys();
    $address = $ethereumKeys['address'];
    $privateKey = $ethereumKeys['privateKey'];

    $getNumberofcandidate_pos = "SELECT COUNT(*) AS candidate_count
                  FROM candidates_table
                  WHERE position = '$candidate_position' AND election_id = '$election_id'";

    $check_pos = "SELECT maximum_candidate AS max_candidate
                  FROM positionstable 
                  WHERE position_id = '$candidate_position' AND election_id = '$election_id'";

    // Execute the first query to get the number of candidates for the specified position and election
    $resultCount = mysqli_query($con, $getNumberofcandidate_pos);
    $countRow = mysqli_fetch_assoc($resultCount);
    $candidateCount = $countRow['candidate_count'];

    // Execute the second query to get the maximum allowed candidates for the specified position and election
    $resultMax = mysqli_query($con, $check_pos);
    if ($resultMax->num_rows > 0) {
        $maxRow = mysqli_fetch_assoc($resultMax);
        $maxCandidates = $maxRow['max_candidate'];
    }

    // Compare the results
    if ($candidateCount == $maxCandidates) {
        $errors['registration'] = 'The amount of candidate in the position reach its maximum.';
    }

    // Define the pattern for the allowed email domain
    $allowedDomain = 'plpasig.edu.ph';
    
    //Check the confirm password if the same
    if ($candidate_password !== $confirm_candidate_password) {
        // Passwords do not match
        $errors['registration'] = 'Passwords do not match!';
    }

    // Perform server-side validation
    if (filter_var($candidate_emailAdd, FILTER_VALIDATE_EMAIL)) {
        $emailParts = explode('@', $candidate_emailAdd);
        $domain = end($emailParts);

        if ($domain !== $allowedDomain) {
            // Valid email address
            $errors['registration'] = 'Please use your PLP email address.';
        }
    } else {
        // Invalid email format
        $errors['registration'] = 'Invalid email format.';
    }

    // Check if email and ID number already exist
    $emailCheckQuery = "SELECT COUNT(*) FROM candidates_table WHERE email = ? AND election_id = ?";
    $idNumberCheckQuery = "SELECT COUNT(*) FROM candidates_table WHERE id_number = ? AND election_id = ?";

    $stmtEmailCheck = mysqli_prepare($con, $emailCheckQuery);
    $stmtIdNumberCheck = mysqli_prepare($con, $idNumberCheckQuery);

    mysqli_stmt_bind_param($stmtEmailCheck, 'si', $candidate_emailAdd, $election_id);
    mysqli_stmt_bind_param($stmtIdNumberCheck, 'si', $candidate_idNumber, $election_id);

    mysqli_stmt_execute($stmtEmailCheck);
    mysqli_stmt_bind_result($stmtEmailCheck, $emailCount);
    mysqli_stmt_fetch($stmtEmailCheck);
    mysqli_stmt_close($stmtEmailCheck);
    
    mysqli_stmt_execute($stmtIdNumberCheck);
    mysqli_stmt_bind_result($stmtIdNumberCheck, $idNumberCount);
    mysqli_stmt_fetch($stmtIdNumberCheck);
    mysqli_stmt_close($stmtIdNumberCheck);

    // Check if email or ID number already exists
    if ($emailCount > 0) {
        //if email exist check if it is verified
        $isEmailVerified = "SELECT isEmail_verified FROM candidates_table WHERE email = ? AND election_id = ?";
        $stmtEmailVerified = mysqli_prepare($con, $isEmailVerified);
        mysqli_stmt_bind_param($stmtEmailVerified, "si", $candidate_emailAdd, $election_id);
        mysqli_stmt_execute($stmtEmailVerified);
        mysqli_stmt_bind_result($stmtEmailVerified, $isEmailVerifiedResult);
        mysqli_stmt_fetch($stmtEmailVerified);
        // Check the result
        if ($isEmailVerifiedResult == 1) {
            $_SESSION['candidate_email_verified'] = true;
            header('location: candidate-success.php');
            exit();
        } else {
            echo "$isEmailVerifiedResult";
            $otp = generateOTP();
            $_SESSION['candidate-otp'] = $otp;
            $_SESSION['candidate_email'] = $candidate_emailAdd;
            $subject = "Email Verification Code";
            $message = "Your verification code is <b>$otp</b>";
            if (sendEmail($subject, $message, $candidate_emailAdd)) {
                header('location: candidate-otp.php');   
                exit();
            } else {
                $errors['registration'] = "Error sending OTP code."; 
            } 
        }
        // Close the statement
        mysqli_stmt_close($stmtEmailVerified);
    } elseif ($idNumberCount > 0) {
        $errors['registration'] = "ID number already registered. Please check your ID number.";
    }

    // Check if there are no errors
    if (empty($errors)) {
        // Continue with the registration process

        if (isset($_FILES['regis-candidate-image-front']) && isset($_FILES['regis-candidate-image-back'])) {
            // Process Image 
            $candidateImage = moveImagefromcropper($candidate_Image);
            $imageFront = processImage($_FILES['regis-candidate-image-front']);
            $imageBack = processImage($_FILES['regis-candidate-image-back']);
    
            // Decode the image
            try{
                $result = (new QRCode)->readFromFile($imageBack['targetFile']); // -> DecoderResult
                $qrCodeData = $result->data;
                
                // Determine the verification status
                $isDataVerified = verifyData($qrCodeData, $candidate_name, $candidate_section, $candidate_idNumber) ? 1 : 0;
            }
            catch(Throwable $exception){
                $isDataVerified = 0;
            }
            
            //insert candidate
            $sql1 = "INSERT INTO candidates_table (name, section, email, id_number, position, platform, candidate_image_path, front_id_image_path, back_id_image_path, isData_verified, election_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt1 = mysqli_prepare($con, $sql1);
            mysqli_stmt_bind_param($stmt1, 'sssssssssii', $candidate_name, $candidate_section, $candidate_emailAdd, $candidate_idNumber, $candidate_position, $candidate_platform, $candidateImage, $imageFront['imageUrl'], $imageBack['imageUrl'], $isDataVerified, $election_id);
            $result1 = mysqli_stmt_execute($stmt1);
            $stmt1->close();
            
            //check is candidate is a voter
            $checkEmailisVoter = "SELECT COUNT(*) as count FROM voters_table WHERE email = '$candidate_emailAdd'";
            $result_checkEmailisVoter = $con->query($checkEmailisVoter);
            
            // Fetch the result
            $rowEmail = $result_checkEmailisVoter->fetch_assoc();
            
            // Check if the email exists
            if ($rowEmail['count'] == 0) {
                //insert candidate as voter
                $sql2 = "INSERT INTO voters_table (name, section, email, id_number, front_id_image_path, back_id_image_path, voter_password, isData_verified, election_id, privateKey, address) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt2 = mysqli_prepare($con, $sql2);
                mysqli_stmt_bind_param($stmt2, 'sssssssiiss', $candidate_name, $candidate_section, $candidate_emailAdd, $candidate_idNumber, $imageFront['imageUrl'], $imageBack['imageUrl'], $hashedPassword, $isDataVerified, $election_id, $privateKey, $address);
                $result2 = mysqli_stmt_execute($stmt2);
                $stmt2->close();
            }
            
            //send otp to email
            if ($result1 && $result2) {
                $otp = generateOTP();
                $_SESSION['candidate-otp'] = $otp;
                $_SESSION['cnadidate_email'] = $candidate_emailAdd;
                $subject = "Email Verification Code";
                $message = "Your verification code is <b>$otp</b>";
                if (sendEmail($subject, $message, $candidate_emailAdd)) {
                    header('location: candidate-otp.php');   
                    exit();
                } else {
                    $errors['registration'] = "Error sending OTP code."; 
                } 
            } else {
                echo 'Error: ' . $sql . '<br>' . mysqli_error($con);
            }

            mysqli_close($con);        
            
        } else {
            echo 'Invalid file uploads.';
        }
    }
}

//when click verify otp of candidate
if(isset($_POST["verify_candidateOTPBtn"])){
    $election_id = $_SESSION['election-id'];
    $candidate_email = $_SESSION['candidate_email'];
    // Validate OTP
    $enteredOTP = $_POST['regis-candidate-OTP'];  // Get OTP from the submitted form
    $storedOTP = $_SESSION['candidate-otp'];
    $verified = 1;

    if ($enteredOTP === $storedOTP) {
        $get_isDataVerified = "SELECT isData_verified FROM candidates_table WHERE email = ? AND election_id = ?";
        $stmtCheckDataCan = $con->prepare($get_isDataVerified);
        
        if ($stmtCheckDataCan) {
            $stmtCheckDataCan->bind_param("si", $candidate_email, $election_id);    
            $stmtCheckDataCan->execute();    
            $stmtCheckDataCan->bind_result($isDataVerified);    
            $stmtCheckDataCan->fetch();    
            if ($isDataVerified == 1) {
                $subject = "Candidates Registration";
                $message = "<pre><b>Congratulations</b> on successfully registering to vote! Your commitment to participate is commendable, 
                            and we're delighted to welcome you to the voter community. Stay tuned for important updates, election 
                            information, and exclusive insights that will keep you informed. As elections approach, we'll provide 
                            you with all the necessary details to ensure your fillig  experience is smooth and impactful.</pre>";
                sendEmail($subject, $message, $candidate_email);
            } else {
                $subject = "Candidates Registration";
                $message = "<pre>We hope this message finds you well. We regret to inform you that the data provided during your voter 
                            registration process is currently unverified. It's crucial that we ensure the accuracy of voter information 
                            to maintain the integrity of the electoral process. Please wait for the election creator to manually verify
                            your data. Stay tuned for updates.</pre>";
                sendEmail($subject, $message, $candidate_email);
            }
            // Close the statement
            $stmtCheckDataCan->close();
        } else {
            // Handle the case where the statement preparation fails
            $errors['check-data'] = "Error preparing statement.";
        }

        $sql1 = "UPDATE candidates_table SET isEmail_verified = ? WHERE email = ? AND election_id = ?";
        $sql2 = "UPDATE voters_table SET isEmail_verified = ? WHERE email = ? AND election_id = ?";
        
        $stmt1 = $con->prepare($sql1);
        $stmt1->bind_param("isi", $verified, $candidate_email, $election_id);
    
        $stmt2 = $con->prepare($sql2);
        $stmt2->bind_param("isi", $verified, $candidate_email, $election_id);
        
        if ($stmt1->execute() && $stmt2->execute()) {
            unset($_SESSION['candidate-otp']);
            unset($_SESSION['candidate_email']);
            $_SESSION['candidate_email_verified'] = true;
            header('location: candidate-success.php');
            exit();
        } else {
            $errors['update'] = "Error updating column: " . $stmt->error;
        }
        $stmt1->close();
        $stmt2->close();
    } else {
        // Invalid OTP
        $errors['invalid-otp'] = "Invalid OTP. Please try again.";
    }
}

function processImage($file)
{
    $uploadDir = UPLOAD_DIR;
    $allowedExtensions = ALLOWED_EXTENSIONS;
    $baseUrl = BASE_URL;

    $fileName = basename($file['name']);
    $uniqueIdentifier = uniqid();
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileType, $allowedExtensions)) {
        echo 'Invalid file type. Only JPG and PNG are allowed.';
        exit();
    }

    $targetFile = $uploadDir . $uniqueIdentifier . '_' . $fileName;
    $imageUrl = $baseUrl . "uploads/" . $uniqueIdentifier . '_' . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return ['imageUrl' => $imageUrl, 'targetFile' => $targetFile];
    } else {
        return ['error' => 'Error uploading file.'];
    }
}

function sendEmailtoVoters($con) {
    $election_id = $_SESSION['election-id'];
    
    $getData = "SELECT * FROM electiontable WHERE election_id = '$election_id'";
    $result = $con->query($getData);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $election_name = $row['election_name'];
        $startdate = $row['start_date'];
        $enddate = $row['end_date'];
        $election_code = $row['election_code'];

        $sql = "SELECT email FROM voters_table WHERE election_id = '$election_id'";
        $resultVoters = $con->query($sql);
        
        if ($resultVoters->num_rows > 0) {
            // Fetch all rows as an associative array
            while ($row = $resultVoters->fetch_assoc()) {
                $baseUrl = BASE_URL;
                $election_link_home = $baseUrl . "index.php";
                $election_link = $baseUrl . "voters/index.php";

                $to = $row['email'];
                $subject = "$election_name";
                $message = "<h1>You are invited on the upcoming $election_name.</h1>
                            <h3>Please participate and cast your vote on the date specified.</h3>
                            <p>Start Date: $startdate.</p>
                            <p>End Date: $enddate</p>
        
                            <h3>Please login using the credentials you registered.</h3>
        
                            <h3>To vote you can click the link below or go to <a href='$election_link_home'>Blockchain-Based EVS</a> website to insert the election code below.</h3>
                            <p>Election Link: <a href='$election_link?code=$election_code'>$election_link</a></p>
                            <p>Election Code: <b>$election_code</b></p>
                            ";
                sendEmail($subject, $message, $to);
            }
        }
    }
}

function moveImagefromcropper($croppedImage) {
    // Generate a unique identifier (you can use other methods if needed)
    $uniqueIdentifier = uniqid();
    $uploadDir = UPLOAD_DIR;
    $baseUrl = BASE_URL;

    // Retrieve cropped image data
    $croppedImageData = $croppedImage;

    if (empty($croppedImageData)) {
        throw new Exception('Empty or null image data.');
    }

    // Decode the base64 data URL to get the binary image data
    $croppedImageBinary = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImageData));

    // Choose the desired file extension (replace 'jpg' with 'png' if needed)
    $fileExtension = 'jpg'; // Updated to JPG
    $filename = "voter_image_$uniqueIdentifier.$fileExtension";
    $filepath = $baseUrl . "uploads/" . $filename;
    $target = $uploadDir . $filename;
    if (file_put_contents($target, $croppedImageBinary)) {
        return $filepath;
    } else {
        echo 'Error uploading file.';
        exit();
    }
}

//verify data function
function verifyData($qrCodeData, $Name, $Section, $IdNumber)
{
    // Extracting name
    $parts = explode('[', $qrCodeData);
    if (isset($parts[0])) {
        $name = trim($parts[0]);
    }

    // Extracting student ID and academic program
    preg_match('/\[([^]]+)\](.*?)\[(.*?)\]/', $qrCodeData, $matches);
    $studentID = $matches[1];
    
    // Extracting the part before the hyphen in academic program and section
    $academicProgram = strtok($matches[2], '-');
    
    // Trimming $Section to only include the part before the hyphen
    $Section = strtok($Section, '-');

    if ($name == $Name && $studentID == $IdNumber && $academicProgram == $Section) {
        return true;
    }

    // Verification failed
    return false;
}

function generateOTP($length = 6) {
    $characters = '0123456789';
    $otp = '';

    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $otp;
}

//send email function
function sendEmail($subject, $message, $email) {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'blockchainbased.evs@gmail.com';
        $mail->Password = 'xnvxfrxgmcirirjm';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
    
        $mail->setFrom('blockchainbased.evs@gmail.com');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

//submit ballot
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit-ballot'])) {
    $election_id = $_SESSION['election-id'];
    $voter_id = $_SESSION['voter'];
    unset($_POST['submit-ballot']);

    $check_query = "SELECT * FROM votestable WHERE voter_id = '$voter_id' AND election_id = '$election_id'";
    $result = mysqli_query($con, $check_query);

    if ($result) {
        // Check the number of rows returned
        $num_rows = mysqli_num_rows($result);

        if ($num_rows > 0) {
            // Voter_id exists
            $errors['submit-vote'] = 'You already voted in this election.';
        } else {
            // Loop through each position in the form
            foreach ($_POST as $position_id => $selected_candidates) {
                if (isset($position_id)) {
                    // Validate that at least one candidate is selected for each position
                    if (empty($selected_candidates)) {
                        $errors['submit-vote'] = 'Please select a candidate for every position.';
                        break; // Exit the loop if there is an error
                    }
            
                    // If multiple candidates can be selected for the position, $selected_candidates will be an array
                    // If only one candidate can be selected, $selected_candidates will be a single value
                    if (is_array($selected_candidates)) {
                        foreach ($selected_candidates as $candidate_id) {
                            saveVote($con, $voter_id, $candidate_id, $position_id, $election_id);
                        }
                    } else {
                        saveVote($con, $voter_id, $selected_candidates, $position_id, $election_id);
                    }
                }
            }
        }
    } else {
        // Error in the query
        $errors['submit-vote'] = "Error: " . mysqli_error($con);
    }
    
    // Check if there are any errors before interacting with the blockchain
    if (empty($errors)) {
        // Call the function to interact with the blockchain
        interactWithBlockchain();
        //sendTransactionToContract($voter_id, $con);
        $success['submit-vote'] = "Vote submitted successfully!";
    }
} 

// Function to save vote into the database
function saveVote($con, $voter_id, $candidate_id, $position_id, $election_id) {
    // Insert vote into votestable
    $insert_query = "INSERT INTO votestable (voter_id, candidate_id, position_id, election_id) VALUES ('$voter_id', '$candidate_id', '$position_id', '$election_id')";

    if (mysqli_query($con, $insert_query)) {
        // Data to be sent to the blockchain
        $_SESSION["voted"] = true;
    } else {
        echo "Error: " . $insert_query . "<br>" . mysqli_error($con);
    }
}

// Function to get privateKey and address from voters_table
function getVoterKeys($con, $voter_id) {
    $query = "SELECT privateKey, address FROM voters_table WHERE voter_id = '$voter_id'";
    $result = mysqli_query($con, $query);

    if ($result) {
        // Fetch the result as an associative array
        $row = mysqli_fetch_assoc($result);

        // Check if the voter was found
        if ($row) {
            // Return the privateKey and address
            return [
                'privateKey' => $row['privateKey'],
                'address' => $row['address'],
            ];
        } else {
            // Voter not found
            return null;
        }
    } else {
        // Error in the query
        echo "Error: " . mysqli_error($con);
        return null;
    }
}

//send data to blockchain
function sendTransactionToContract($voter_id, $con) {
    // Ethereum node endpoint for Sepolia
    $infuraEndpoint = 'https://sepolia.infura.io/v3/4769a31a6da24c3f9aff5ec77580718b';
    
    // Smart contract address and ABI
    $contractAddress = '0x3b375d1bb1c2011e44566d423c73cf3d3c3e8f8b';
    $contractAbi = '[
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"internalType": "address",
				"name": "voter",
				"type": "address"
			}
		],
		"name": "Voted",
		"type": "event"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "",
				"type": "address"
			}
		],
		"name": "hasVoted",
		"outputs": [
			{
				"internalType": "bool",
				"name": "",
				"type": "bool"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [],
		"name": "vote",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "",
				"type": "address"
			}
		],
		"name": "voters",
		"outputs": [
			{
				"internalType": "bool",
				"name": "",
				"type": "bool"
			}
		],
		"stateMutability": "view",
		"type": "function"
	}
]';

    // Initialize Web3 with the Infura endpoint
    $web3 = new Web3(new HttpProvider(new HttpRequestManager($infuraEndpoint)));
    
    // Create a contract object
    $contract = new Contract($web3->provider, json_decode($contractAbi, true));
    $contract->at($contractAddress);
    
    //get voterkey from database
    $voterKeys = getVoterKeys($con, $voter_id);

    if ($voterKeys) {
        $voterPrivateKey = $voterKeys['privateKey'];
        $voterAddress = $voterKeys['address'];
        
        // Transaction data
        $transactionData = [
            'from' => $voterAddress,
            'gas' => '50000',
            'gasPrice' => '3000000000', // Example gas price (you may need to adjust this)
        ];
    
        // Send the transaction to the vote function
        $contract->call('vote', $transactionData, function ($err, $result) {
            if ($err !== null) {
                echo 'Error: ' . $err->getMessage();
            } else {
                echo 'Transaction hash: ' . $result;
            }
        });
    }
}

function interactWithBlockchain() {
    $web3 = new Web3(new HttpProvider(new HttpRequestManager("https://boldest-chaotic-spree.ethereum-goerli.quiknode.pro/41b68869722fb9a46e4f6ae8768d96d6a06a5327/")));

    $eth = $web3->eth;
    
    $eth->blockNumber(function ($err, $data) {
        //    echo "Latest block number is: ". $data . " \n";
    });
}

function generateEthereumKeys() {
    $config = [
        'private_key_type' => OPENSSL_KEYTYPE_EC,
        'curve_name' => 'secp256k1'
    ];

    $res = openssl_pkey_new($config);

    if (!$res) {
        echo 'ERROR: Fail to generate private key. -> ' . openssl_error_string();
        exit;
    }

    openssl_pkey_export($res, $priv_key);

    $key_detail = openssl_pkey_get_details($res);
    $pub_key = $key_detail["key"];

    $priv_pem = PEM::fromString($priv_key);

    $ec_priv_key = ECPrivateKey::fromPEM($priv_pem);

    $ec_priv_seq = $ec_priv_key->toASN1();

    $priv_key_hex = bin2hex($ec_priv_seq->at(1)->asOctetString()->string());
    $pub_key_hex = bin2hex($ec_priv_seq->at(3)->asTagged()->asExplicit()->asBitString()->string());

    $pub_key_hex_2 = substr($pub_key_hex, 2);

    $hash = Keccak::hash(hex2bin($pub_key_hex_2), 256);

    $wallet_address = '0x' . substr($hash, -40);
    $wallet_private_key = '0x' . $priv_key_hex;

    return ['privateKey' => $wallet_private_key, 'address' => $wallet_address];
}

// Function to update voters with Ethereum address and private key
function updateVotersWithKeys($con) {
    $election_id = $_SESSION['election-id'];
    // Fetch existing voters from the table
    $result = mysqli_query($con, "SELECT * FROM voters_table WHERE election_id = '$election_id'");

    // Loop through each voter
    while ($row = mysqli_fetch_assoc($result)) {
        // Generate Ethereum address and private key
        $ethereumKeys = generateEthereumKeys();

        // Update the voter record with Ethereum address and private key
        $voterId = $row['voter_id'];
        $address = $ethereumKeys['address'];
        $privateKey = $ethereumKeys['privateKey'];

        // Update the voters_table with Ethereum address and private key
        $updateQuery = "UPDATE voters_table SET address = '$address', privateKey = '$privateKey' WHERE voter_id = '$voterId'";
        $updateResult = mysqli_query($con, $updateQuery);

        if (!$updateResult) {
            echo "Error updating voter: " . mysqli_error($con);
            continue; // Continue to the next voter even if there's an error
        }
    }
}

// Call the function to update voters with Ethereum address and private key
//updateVotersWithKeys($con);

//position up and down
if (isset($_GET['position_id']) && isset($_GET['direction'])) {
    $positionId = $_GET['position_id'];
    $direction = $_GET['direction'];
    $election_id = $_SESSION['election-id'];

    // Validate and sanitize input values if needed

   // Fetch the current priority and the total number of rows
   $fetchInfoSql = "SELECT priority FROM positionstable WHERE position_id = $positionId AND election_id = '$election_id'";
   $fetchInfoResult = $con->query($fetchInfoSql);

   if ($fetchInfoResult && $fetchInfoResult->num_rows > 0) {
       $row = $fetchInfoResult->fetch_assoc();
       $currentPriority = $row['priority'];

       // Fetch the total number of rows
       $totalRowsSql = "SELECT COUNT(*) AS total_rows FROM positionstable WHERE election_id = '$election_id'";
       $totalRowsResult = $con->query($totalRowsSql);

       if ($totalRowsResult && $totalRowsResult->num_rows > 0) {
           $totalRows = $totalRowsResult->fetch_assoc()['total_rows'];

           // Determine the target priority
           $targetPriority = ($direction == 'up') ? $currentPriority - 1 : $currentPriority + 1;

           // Perform the update based on the direction and min/max conditions
           if (($direction == 'up' && $currentPriority > 1) || ($direction == 'down' && $currentPriority < $totalRows)) {
               // Update the current position
               $updateCurrentSql = "UPDATE positionstable SET priority = $targetPriority WHERE position_id = $positionId AND election_id = '$election_id'";
               $updateCurrentResult = $con->query($updateCurrentSql);

               if ($updateCurrentResult) {
                   // Update the adjacent position
                   $updateAdjacentSql = "UPDATE positionstable SET priority = $currentPriority WHERE election_id = '$election_id' AND priority = $targetPriority AND position_id != $positionId";
                   $updateAdjacentResult = $con->query($updateAdjacentSql);

                   // Check for errors and send a response
                   if ($updateAdjacentResult) {
                       echo "Success";
                   } else {
                       echo "Error updating adjacent position: " . $con->error;
                   }
               } else {
                   echo "Error updating current position: " . $con->error;
               }
           } else {
               echo "No action needed (min/max conditions)";
           }
       } else {
           echo "Error fetching total rows: " . $con->error;
       }
   } else {
       echo "Error fetching current priority: " . $con->error;
   }
} 

//update position table
if (isset($_GET['action']) && $_GET['action'] == "updatePositionTable") {
    $election_id = $_SESSION['election-id'];
    $sql = "SELECT * FROM positionstable WHERE election_id = '$election_id' ORDER BY priority ASC";
    $result = $con->query($sql);

    if ($result) {
        // Build the new table body
        $newTableBody = '';
        while ($row = $result->fetch_assoc()) {
            $newTableBody .= '<tr>';
            $newTableBody .= '<td><h6 class="mb-0 text-sm">' . $row['position_desc'] . '</h6></td>';
            $newTableBody .= '<td><h6 class="mb-0 text-sm">' . $row['maximum_vote'] . '</h6></td>';
            $newTableBody .= '<td class="">
                                <div class="row m-0">
                                    <div class="col-auto p-0">
                                        <button onclick="movePosition(' . $row['position_id'] . ', \'up\')" class="text-secondary font-weight-bold text-xs btn btn-primary text-white"><i class="fa-solid fa-chevron-up"></i></button>
                                    </div>
                                    <div class="col-auto">
                                        <button onclick="movePosition(' . $row['position_id'] . ', \'down\')" class="text-secondary font-weight-bold text-xs btn btn-primary text-white"><i class="fa-solid fa-chevron-down"></i></button>
                                    </div>
                                </div>
                            </td>';
            $newTableBody .= '</tr>';
        }

        echo $newTableBody;
    } else {
        echo "Error: " . $con->error;
    }
}

// Check if the request is an AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['launchStartDate'])) {
   // Get the submitted start date
   $submittedStartDate = $_POST['launchStartDate'];

   // Get the current date and time
   $currentDate = new DateTime();
   $currentDateFormat = $currentDate->format('Y-m-d H:i:s');

   // Check if the start date is greater than the current date
   $isValidStartDate = ($submittedStartDate > $currentDateFormat);

   // Return a JSON response
   header('Content-Type: application/json');
   echo json_encode(['valid' => $isValidStartDate]);
}

//sentiment
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit-feedback'])) {

    $name = $_POST['feedback-voter-name'];
    $sentiment = $_POST['feedback-voter'];
    $voter_id = $_POST['submit-feedback'];
    $election_id = $_SESSION['election-id'];
    
    $sentiment = translateToEn($sentiment);

    // Fetch data from PythonAnywhere API
    $BASE_URL = 'https://blockchainvotingsystem.pythonanywhere.com';
    $input = $sentiment;
    $queryParams = ['input' => $input];
    $queryString = http_build_query($queryParams);
    $url = $BASE_URL . '?' . $queryString;
    $responseJson = file_get_contents($url);
    $jsonValues = json_decode($responseJson, true);

    // Extract values from the JSON response
    $rq_input = $jsonValues['input'];
    $timestamp = $jsonValues['timestamp'];
    $sentimentDetails = $jsonValues['sentiment_details'] ?? [];
    $overallSentiment = $jsonValues['overall_sentiment'] ?? [];

    $negativePercentage = floatval($sentimentDetails['negative_percent'] ?? 0);
    $neutralPercentage = floatval($sentimentDetails['neutral_percent'] ?? 0);
    $positivePercentage = floatval($sentimentDetails['positive_percent'] ?? 0);

    $tableName = 'sentiment_tb';
    $data = [
        'name' => $name,
        'voter_id' => $voter_id,
        'election_id' => $election_id,
        'positive' => $positivePercentage,
        'neutral' => $neutralPercentage,
        'negative' => $negativePercentage,
        'sentiment' => $_POST['feedback-voter'],
        'overall' => $overallSentiment
    ];
    
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));
    $sql = "INSERT INTO $tableName ($columns) VALUES ($placeholders)";

    $stmt = $con->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($data)), ...array_values($data));
    $stmt->execute();
}

function translateToEn($text){
    $endpointLanguage = 'https://ws.detectlanguage.com/0.2/detect';
    $endpointTranslation = 'https://api.mymemory.translated.net/get';
    $sourceLang = 'tl';
    $targetLang = 'en';

    $data = array(
        'q' => $text,
        'key' => 'b9348e010e2ecbef65b5aec98c18db10'
    );

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options);
    $responseLanguage = file_get_contents($endpointLanguage, false, $context);
    $resultLanguage = json_decode($responseLanguage, true);

    if ($resultLanguage && isset($resultLanguage['data']) && isset($resultLanguage['data']['detections']) && !empty($resultLanguage['data']['detections'])) {
        $detections = $resultLanguage['data']['detections'];
        $isTagalog = false;
        foreach ($detections as $detection) {
            if ($detection['language'] === 'tl') {
                $isTagalog = true;
                break;
            }
        }
        
        if ($isTagalog) {
            $langpair = $sourceLang . '|' . $targetLang;
        
            $queryString = $endpointTranslation . '?q=' . urlencode($text) . '&langpair=' . urlencode($langpair);
        
            $responseTranslation = file_get_contents($queryString);
            $dataTranslation = json_decode($responseTranslation, true);
        
            if ($dataTranslation && isset($dataTranslation['responseStatus']) && $dataTranslation['responseStatus'] === 200) {
                $translatedText = $dataTranslation['responseData']['translatedText'];
                return $translatedText;
            } else {
                echo "Translation failed.";
            }
        } else {
            return $text;
        }
    } else {
        echo "Language detection failed.";
    }
}

?>