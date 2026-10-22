<?php
include_once("layout/header.php");
require_once("include/userClass.php");
require_once("include/loginFunction.php");


if(@$_SESSION['acct_no']){
    header("Location:./user/dashboard.php");
    exit;
}
  
 if(isset($_POST["sub"]))  
 {  
      $_SESSION["name"] = $_POST["name"];  
      $_SESSION['last_login_timestamp'] = time();  
      header("location:index.php");       
      exit;
 } 



if(isset($_POST['login'])){
    try {
        require_once __DIR__ . "/include/userFunction.php";
        
        $login_input = isset($_POST['acct_no']) ? inputValidation($_POST['acct_no']) : '';
        $acct_password = isset($_POST['acct_password']) ? $_POST['acct_password'] : '';

        $log = "SELECT * FROM users WHERE acct_no =:login_input OR acct_email =:login_input";
        $stmt = $conn->prepare($log);
        $stmt->execute([
            'login_input'=>$login_input
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($stmt->rowCount() === 0){
            if (function_exists('toast_alert')) {
                toast_alert("error","Invalid login details");
            } else {
                echo "<script>alert('Invalid login details');</script>";
            }
        }else{
            $validPassword = password_verify($acct_password, $user['acct_password']);

            if ($validPassword === false){
                if (function_exists('toast_alert')) {
                    toast_alert("error","Invalid login details");
                } else {
                    echo "<script>alert('Invalid login details');</script>";
                }
            }else{

                if($user['acct_status'] === 'hold'){
                    if (function_exists('toast_alert')) {
                        toast_alert("error","Account on Hold, Kindly contact support to activate your account");
                    } else {
                        echo "<script>alert('Account on Hold, Kindly contact support to activate your account');</script>";
                    }
                }else {
                    // IP LOGIN DETAILS
                    try {
                        $device = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown';
                        $ipAddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
                        $nowDate = date('Y-m-d H:i:s');
                        $user_id = $user['id'];
                      
                        $stmt = $conn->prepare("INSERT INTO audit_logs (user_id,device,ipAddress,datenow) VALUES(:user_id,:device,:ipAddress,:datenow)");
                        $stmt->execute([
                            'user_id'=>$user_id,
                            'device'=>$device,
                            'ipAddress'=>$ipAddress,
                            'datenow'=>$nowDate
                        ]);

                        $full_name = $user['firstname']. " ". $user['lastname'];
                        $APP_NAME = WEB_TITLE;
                        $APP_URL = WEB_URL;
                        $user_email = $user['acct_email'];

                        $message = $sendMail->LoginMsg($full_name, $device, $ipAddress, $nowDate, $APP_NAME, $APP_URL, $BANK_PHONE);

                        // User & Admin Emails
                        $subject = "Login Notification". "-". $APP_NAME;
                        $email_message->send_mail($user_email, $message, $subject);
                        $subjectAdmin = "User Login Notification". "-". $APP_NAME;
                        $email_message->send_mail(WEB_EMAIL, $message, $subjectAdmin);
                    } catch (\Throwable $e) {
                        error_log("Login log/email error: " . $e->getMessage());
                    }

                    $_SESSION['login'] = $user['acct_no'];
                    if (session_status() === PHP_SESSION_ACTIVE) {
                        session_write_close();
                    }
                    echo '<script>window.location.href = "./pin.php";</script>';
                    header("Location:./pin.php");
                    exit;
                }
            }
        }
    } catch (\Throwable $e) {
        error_log("Login processing error: " . $e->getMessage());
        if (function_exists('toast_alert')) {
            toast_alert("error", "Unable to complete login. Please try again: " . htmlspecialchars($e->getMessage()));
        } else {
            echo "<script>alert('Login error: " . addslashes($e->getMessage()) . "');</script>";
        }
    }
}
?>

<div class="form-container outer">
    <div class="form-form">
        <div class="form-form-wrap">
            <div class="form-container">
                <div class="form-content">

                    <h1 class="">Sign In</h1>
                   
                    <p class="">Log in to your account to continue.</p>
                 <!--   <img src="./assets/settings/<?=$page['image']?>" class="navbar-logo" alt="logo" width="20%"> -->

                    <form class="text-left" method="POST">
                        <div class="form">

                            <div id="username-field" class="field-wrapper input">
                                <label for="username">Account ID or Email</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                <input id="username" name="acct_no" type="text" class="form-control" placeholder="Account ID or Email Address" value="<?= isset($_GET['acct']) ? htmlspecialchars($_GET['acct']) : '' ?>" required>
                            </div>

                            <div id="password-field" class="field-wrapper input mb-2">
                                <div class="d-flex justify-content-between">
                                    <label for="password">PASSWORD</label>
                                    <a href="./signup" class="forgot-pass-link">Create New Account</a>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                <input id="password" name="acct_password" type="password" class="form-control" placeholder="Password">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </div>
                            <div class="d-sm-flex justify-content-between">
                                <div class="field-wrapper">
                                    <button type="submit" class="btn btn-primary" name="login" value="">Log In</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php

include_once("layout/footer.php");

?>

