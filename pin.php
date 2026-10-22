<?php
include_once("layout/header.php");

if(@!$_SESSION['login']){
    header("Location:./login.php");
    exit;
}
if(@$_SESSION['acct_no']){
    header('Location:./user/dashboard.php');
    exit;
}
$viesConn="SELECT * FROM users WHERE acct_no = :acct_no";
$stmt = $conn->prepare($viesConn);

$stmt->execute([
    ':acct_no'=>$_SESSION['login']
]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    header("Location:./login.php");
    exit;
}

$user_id = $row['id'];
$fullName = $row['firstname']." ".$row['lastname'];
$acct_no = $row['acct_no'];


if(isset($_POST['pin_submit'])){
    $pinVerified = isset($_POST['input']) ? trim($_POST['input']) : '';
    $old_otp = $row['acct_pin'];

    if(empty($pinVerified)){
        notify_alert('Enter Your OTP','danger','2000','Close');
    } elseif($pinVerified !== $old_otp){
        notify_alert('Invalid OTP CODE','danger','2000','Close');
    } else {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['acct_no'] = $acct_no;
        setcookie('firstVisit', $acct_no, time() + 3600, '/');
        echo '<script>window.location.href = "./user/dashboard.php";</script>';
        header("Location:./user/dashboard.php");
        exit;
    }
}
?>
                <div class="form-container outer">
                    <div class="form-form">
                        <div class="form-form-wrap">
                            <div class="form-container">
                                <div class="form-content">

                                    <div class="d-flex user-meta">
                                        <img src="./assets/profile/<?= $row['image']?>" class="usr-profile" alt="avatar">
                                        <div class="">
                                            <p class=""><?= $fullName?></p>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 class="text-center">Welcome</h3>
                                            <p class="text-info">Enter PIN </p>

                                        </div>
                                    </div>

                                    <form class="text-left" method="post" >
                                        <div class="form">
                                            <div class="field-wrapper input mb-2">

                                                <div class="d-flex justify-content-between">

                                                    <label for="password">PINCODE</label>

                                                </div>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                                <input id="datepicker"  name="input" type="number" class="form-control input" placeholder="PINCODE"  autocomplete="off">


                                </div>

                            </div>
                                        <div class=" text-center">
                    <div id="container">
                        <div>
                            <button class="shuffle">1</button>
                            <button class="shuffle">2</button>
                            <button class="shuffle">3</button>
                        </div>
                        <div>
                            <button class="shuffle">4</button>
                            <button class="shuffle">5</button>
                            <button class="shuffle">6</button>
                        </div>
                        <div>
                            <button class="shuffle">7</button>
                            <button class="shuffle">8</button>
                            <button class="shuffle">9</button>
                        </div>
                        <div>
                            <button class="del">X</button>
                            <button class="shuffle">0</button>
                            <button class="faq">?</button>
                        </div>
                        <div class="text-center">
                            <input class="btn btn-primary mt-2" type="submit" value="Submit" name="pin_submit">

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
