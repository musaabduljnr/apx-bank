<?php
include_once("./layout/header.php");
//require_once("./include/adminloginFunction.php");
//include_once("../include/config.php");

$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id =:id";
$data = $conn->prepare($sql);
$data->execute(['id'=>$id]);

$row = $data->fetch(PDO::FETCH_ASSOC);

if($row['billing_code']=='0'){
    $billing = "DEACTIVATE";
}elseif($row['billing_code']=='1'){
    $billing = "ACTIVE";
}

if($row['transfer']=='0'){
    $transfer = "DEACTIVATE";
}elseif($row['transfer']=='1'){
    $transfer = "ACTIVE";
}

if(isset($_POST['upload_picture'])){
    if (isset($_FILES['image'])) {
        $file = $_FILES['image'];
        $name = $file['name'];

        $path = pathinfo($name, PATHINFO_EXTENSION);

        $allowed = array('jpg', 'png', 'jpeg');


        $folder = "../assets/profile/";
        $n = $row['acct_no'].$name;

        $destination = $folder . $n;
    }
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $sql = "UPDATE users SET image=:image WHERE id =:acct_id";
        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'image'=>$n,
            'acct_id'=>$id

        ]);

        if(true){
            toast_alert("success","Your Image Uploaded Successfully", "Thanks!");
        }else{
            echo "invalid";
        }
        
        // header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
        header("Location:./users.php");
die;


    }
}

if(isset($_POST['profile_save'])){
    $acct_no = $_POST['acct_no'];
    $acct_type = $_POST['acct_type'];
    $acct_email = $_POST['acct_email'];
    $acct_dob = $_POST['acct_dob'];
    $acct_occupation = $_POST['acct_occupation'];
    $acct_phone = $_POST['acct_phone'];
    $acct_gender = $_POST['acct_gender'];
    $marital_status = $_POST['marital_status'];
    $acct_limit = $_POST['acct_limit'];
    $acct_balance = $_POST['acct_balance'];
     $acct_cot = $_POST['acct_cot'];
      $acct_imf = $_POST['acct_imf'];
       $acct_tax = $_POST['acct_tax'];


//    if($acct_limit === '5000'){
//        $limit = 0;
//    }else{
//        $limit = $acct_limit;
//    }
    $limiBalance = ($acct_limit + $row['limit_remain']);
    $limit = ($row['acct_limit']+$acct_limit);

//    var_dump($limit);
//    exit();


    $sql = "UPDATE users SET acct_no=:acct_no, acct_type=:acct_type,acct_email=:acct_email,acct_dob=:acct_dob,acct_occupation=:acct_occupation,acct_phone=:acct_phone,acct_gender=:acct_gender,marital_status=:marital_status,acct_limit=:acct_limit,acct_cot=:acct_cot,acct_tax=:acct_tax,acct_imf=:acct_imf,acct_balance=:acct_balance,limit_remain=:limit_remain WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'acct_no'=>$acct_no,
        'acct_type'=>$acct_type,
        'acct_email'=>$acct_email,
        'acct_dob'=>$acct_dob,
        'acct_occupation'=>$acct_occupation,
        'acct_phone'=>$acct_phone,
        'acct_gender'=>$acct_gender,
        'acct_tax'=>$acct_tax,
        'acct_cot'=>$acct_cot,
        'acct_imf'=>$acct_imf,
        'marital_status'=>$marital_status,
        'acct_limit'=>$limit,
        'acct_balance'=>$acct_balance,
        'limit_remain'=>$limiBalance,
        'id'=>$id
    ]);

    if(true){
       // toast_alert('success','Account updated successfully','Approved');
        header("Location:./users.php");
        
        
    }else{
        toast_alert('error','Sorry something went wrong');
        
        
    }
    
    //header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
    

    

}


if(isset($_POST['status_delete'])){
    
    $sql = "DELETE FROM users WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'id'=>$id
    ]);


    if(true){
        toast_alert('success','Account Deleted Successfully');
        header("Location:./users.php");
    }else{
        toast_alert('error', 'Sorry Something Went Wrong');
    }
    

    
}



if(isset($_POST['change_pin'])){
    $current_pin = inputValidation($_POST['current_pin']);
    $new_pin = inputValidation($_POST['new_pin']);
    $confirm_pin = inputValidation($_POST['confirm_pin']);
    $verify_pin = $row['acct_pin'];

    if($current_pin !== $verify_pin){
        toast_alert('error','Invalid Old Pin');
    }else if($new_pin !== $confirm_pin){
        toast_alert('error','Confirm Pin not Matched');
    }else if($new_pin == $verify_pin){
        toast_alert('error','New Pin Matched with Old Pin');
    }else{
        $sql2 = "UPDATE users SET acct_pin=:acct_pin WHERE id =:acct_id";
        $passwordUpdate = $conn->prepare($sql2);
        $passwordUpdate->execute([
            'acct_pin' =>$new_pin,
            'acct_id' => $id
        ]);
        if(true){
            toast_alert('success','Account Pin Change Successfully','Approved');
            header("Location:./users.php");
        }else{
            toast_alert('error', 'Sorry Something Went Wrong');
        }
    }
    
    // header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
    


}

if(isset($_POST['change_password'])) {
    $old_password = inputValidation($_POST['old_password']);
    $new_password = inputValidation($_POST['new_password']);
    $confirm_password = inputValidation($_POST['confirm_password']);

    $new_password2 = password_hash((string)$new_password, PASSWORD_BCRYPT);
    $verification = password_verify($old_password, $row['acct_password']);

    if ($verification == false) {
        toast_alert("error", "Incorrect Old Password");

    } else if ($new_password !== $confirm_password) {
        toast_alert("error", "Confirm Password not matched");

    } else if($new_password == $old_password){
        toast_alert('error', 'New Password Matched with Old Password');
    }else {
        $sql2 = "UPDATE users SET acct_password=:acct_password WHERE id =:acct_id";
        $passwordUpdate = $conn->prepare($sql2);
        $passwordUpdate->execute([
            'acct_password' =>$new_password2,
            'acct_id' => $id
        ]);
        if (true) {
            toast_alert('success', 'Your Password Change Successfully !', 'Approved');
            //header("Location:./users.php");
        } else {
            toast_alert('error', 'Sorry Something Went Wrong');
        }
    }
    
   //  header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
  

}

if(isset($_POST['status_submit'])){
    $acct_status = $_POST['acct_status'];

    $sql = "UPDATE users SET acct_status=:acct_status WHERE id =:acct_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'acct_status'=>$acct_status,
        'acct_id'=>$id
    ]);

    if(true){
        //toast_alert('success','Account Status Sent Successfully to '.ucwords($acct_status),'Approved');
        header("Location:./users.php");
    }else{
        toast_alert('error', 'Sorry Something Went Wrong');
    }
    
    // header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
    

}

if(isset($_POST['billing_code'])){
    $billing_type = $_POST['billing_type'];

    if($billing_type=='0'){
        $type = "Deactivate";
    }else{
        $type = "Active";
    }

    $sql2 = "UPDATE users SET billing_code=:billing_code WHERE id=:acct_id";
    $stmt = $conn->prepare($sql2);
    $stmt->execute([
        'billing_code'=>$billing_type,
        'acct_id'=>$id
    ]);
    if(true){
        toast_alert('success','Billing Code Successfully '.$type,'success');
    }else{
        toast_alert('error', 'Sorry Something Went Wrong');
    }
    
    // header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
    header("Location:./users.php");
die;
}

if(isset($_POST['transfer'])){
    $transfer_type = $_POST['transfer_type'];

    if($transfer_type=='0'){
        $type = "Deactivate";
    }else{
        $type = "Active";
    }

    $sql2 = "UPDATE users SET transfer=:transfer WHERE id=:acct_id";
    $stmt = $conn->prepare($sql2);
    $stmt->execute([
        'transfer'=>$transfer_type,
        'acct_id'=>$id
    ]);
    if(true){
        toast_alert('success','Transfer Status Successfully Changed'.$type,'success');
    }else{
        toast_alert('error', 'Sorry Something Went Wrong');
    }
    
    // header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
    header("Location:./users.php");
die;
}

if(isset($_POST['lift_freeze'])){
    $stmt = $conn->prepare("UPDATE users SET tx_freeze_until = NULL, tx_freeze_reason = NULL WHERE id = :id");
    $stmt->execute(['id'=>$id]);
    toast_alert('success', '72-Hour Security Hold Lifted Successfully', 'Security Updated');
    echo "<script>window.location.href='./view_users.php?id=$id';</script>";
    exit;
}

if(isset($_POST['apply_freeze'])){
    $freezeUntil = date('Y-m-d H:i:s', time() + (72 * 3600));
    $stmt = $conn->prepare("UPDATE users SET tx_freeze_until = :until, tx_freeze_reason = 'Manual Security Hold applied by Administrator' WHERE id = :id");
    $stmt->execute(['until'=>$freezeUntil, 'id'=>$id]);
    toast_alert('warning', '72-Hour Security Freeze Applied Successfully', 'Account Frozen');
    echo "<script>window.location.href='./view_users.php?id=$id';</script>";
    exit;
}

?>
<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">

        <!-- Live User Interface View & Diagnostics Hero Card -->
        <div class="row layout-top-spacing mb-2">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="widget-content widget-content-area br-6" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: 1px solid #334155; border-radius: 10px; padding: 20px 24px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.35);">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <div style="background: rgba(56, 189, 248, 0.15); border: 1px solid rgba(56, 189, 248, 0.3); border-radius: 12px; width: 54px; height: 54px; display: flex; align-items: center; justify-content: center; margin-right: 18px; flex-shrink: 0;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <h5 style="margin: 0; color: #f8fafc; font-weight: 700; font-size: 18px;">
                                        User Interface View & Diagnostics
                                    </h5>
                                    <span class="badge badge-info ml-2" style="font-size: 11px; padding: 3px 8px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Live Impersonation</span>
                                    <?php if (!empty($row['acct_status'])): ?>
                                        <span class="badge badge-<?= $row['acct_status'] === 'active' ? 'success' : 'danger' ?> ml-1" style="font-size: 11px; text-transform: uppercase;">
                                            Status: <?= htmlspecialchars($row['acct_status']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p style="margin: 5px 0 0 0; color: #94a3b8; font-size: 13.5px; line-height: 1.4;">
                                    Directly access <strong><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></strong>'s interface (Acct: <code style="color: #38bdf8; background: rgba(56,189,248,0.1); padding: 1px 5px; border-radius: 3px;"><?= htmlspecialchars($row['acct_no']) ?></code>) to inspect balances, test transfer forms, and troubleshoot user issues in real-time.
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="./impersonate.php?id=<?= $row['id'] ?>" class="btn btn-primary" style="background: linear-gradient(90deg, #0284c7 0%, #2563eb 100%); border: none; font-weight: 600; padding: 11px 22px; border-radius: 6px; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4); display: inline-flex; align-items: center; gap: 8px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-in"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                                <span>Enter User Portal</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="account-settings-container layout-top-spacing">

            <div class="account-content">
                <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                            <form id="general-info" class="section general-info" enctype="multipart/form-data" method="POST">

                                <div class="info">
                                    <h6 class="">General Information</h6>
                                    <div class="row">
                                        <div class="col-lg-11 mx-auto">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-12 col-md-4 text-center">
                                                    <div class="upload mt-4 pr-md-4">
                                                        <center>
                                                            <input type="file" id="input-file-max-fs" class="dropify" data-default-file="../assets/profile/<?= $row['image']?>" name="image" data-max-file-size="2M" />
                                                        </center>
                                                        <p class="mt-2"><i class="flaticon-cloud-upload mr-1"></i> Upload Picture</p>
                                                        <div class="form-group text-center" >
                                                            <button class="btn btn-primary " name="upload_picture">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">
                                                    <div class="form">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="fullName">Account No</label>
<!--                                                                    --><?php
//                                                                    echo "<pre>";
//                                                                    print_r($_POST)
//                                                                    ?>
                                                                    <input type="text" class="form-control mb-4"  placeholder="Full Name" value="<?= $row['acct_no'] ?>" name="acct_no" >
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="profession">Account Type</label>
                                                                    <div class="input-group">
                                                                        <select name="acct_type" class="form-control  basic" required>
                                                                            <option value="<?= $row['acct_type']?>"><?= $row['acct_type']?></option>
                                                                            <option value="Savings">Savings Account</option>
                                                                            <option value="Current">Current Account</option>
                                                                            <option value="Checking">Checking Account</option>
                                                                            <option value="Fixed Deposit">Fixed Deposit</option>
                                                                            <option value="Non Resident">Non Resident Account</option>
                                                                            <option value="Online Banking">Online Banking</option>
                                                                            <option value="Domicilary Account">Domicilary Account</option>
                                                                            <option value="Joint Account">Joint Account</option>                                                        </select>

                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="fullName">Email</label>
                                                                    <input type="text" class="form-control mb-4" id="fullName" placeholder="Full Name" value="<?= $row['acct_email'] ?>" name="acct_email" >
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="profession">Date Of Birth</label>
                                                                    <input type="date" class="form-control mb-4" id="profession" placeholder="Date Of Birth" value="<?= $row['acct_dob'] ?>" name="acct_dob">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="fullName">Occupation</label>
                                                                    <input type="text" class="form-control mb-4"  placeholder="Ocuppation" value="<?= $row['acct_occupation'] ?>" name="acct_occupation">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="profession">Phone Number</label>
                                                                    <input type="text" class="form-control mb-4"  placeholder="Date Of Birth" value="<?= $row['acct_phone'] ?>" name="acct_phone">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        
                                                         <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="fullName">COT code</label>
                                                                    <input type="text" class="form-control mb-4"  placeholder="Ocuppation" value="<?= $row['acct_cot'] ?>" name="acct_cot">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="profession">IMF code</label>
                                                                    <input type="text" class="form-control mb-4" placeholder="Date Of Birth" value="<?= $row['acct_imf'] ?>" name="acct_imf">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        

                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="fullName">Gender</label>
                                                                    <select name="acct_gender" class="form-control  basic"  id="">
                                                                        <option value="<?= $row['acct_gender']?>"><?= $row['acct_gender']?></option>
                                                                        <option value="male">Male</option>
                                                                        <option value="female">Female</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="profession">Marital Status</label>
                                                                    <input type="text" class="form-control mb-4 text-capitalize" id="profession" placeholder="Date Of Birth" value="<?= $row['marital_status'] ?>" name="marital_status" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <label for="profession">Account Balance</label><br>

                                                                        <button class="btn btn-danger disabled col-md-12"><?= $row['acct_balance'] ?></button>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <div class="form-group">
                                                                            
                                                                            <label for="profession">Account Limit</label>
                                                                            <input type="text" class="form-control mb-4" name="acct_limit" placeholder="<?= $row['acct_limit'] ?>" value="<?= $row['acct_limit'] ?>">
                                                                            
                                                                            <input type="text" name="acct_balance" hidden value="<?= $row['acct_balance'] ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="profession">TAX code</label>
                                                                    <input type="text" class="form-control mb-4" value="<?= $row['acct_tax'] ?>" name="acct_tax">
                                                                </div>
                                                            </div>
                                                        </div>

                                                            <div class="col-md-12">
                                                                <button class="btn btn-primary text-center" name="profile_save">Save</button>
                                                            </div>







                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                            <form class="section about" method="post">
                                <div class="info">
                                    <h5 class="">ACTIVE / HOLD & BILLING CODE</h5>
                                    <div class="row">

                                        <div class="col-md-4 mx-auto">

                                            <div class="form-group">
                                                <button class="btn btn-danger mb-4 disabled">CURRENT STATUS: <b><?=ucwords($row['acct_status']) ?></b> </button><br>
                                                <label for="">SELECT TYPE IF HOLD OR ACTIVE</label>
                                                <select name="acct_status" id="" class="form-control  basic">
                                                    <option value="">Select</option>
                                                        <option value="active">ACTIVE</option>
                                                    <option value="hold">HOLD</option>
                                                </select>
                                            </div>
                                            <div class=" text-center mb-3">
                                                <button class="btn btn-primary" name="status_submit">Submit</button>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mx-auto">

                                            <div class="form-group">
                                                <button class="btn btn-danger mb-4 disabled">BILLING CODE STATUS: <b><?= $billing ?></b> </button><br>
                                                <label for="">SELECT BILLING CODE</label>
                                                <select name="billing_type"  class="form-control  basic">
                                                    <option value="<?= $row['billing_code']?>">Select</option>
                                                    <option value="1">ACTIVE</option>
                                                    <option value="0" >DEACTIVATE</option>
                                                </select>
                                            </div>
                                            <div class=" text-center">
                                                <button class="btn btn-primary" name="billing_code">Change Billing Code</button>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 mx-auto">

                                            <div class="form-group">
                                                <button class="btn btn-danger mb-4 disabled">TRANSFER CODE STATUS: <b><?= $transfer ?></b> </button><br>
                                                <label for="">SELECT TRANSFER STATUS</label>
                                                <select name="transfer_type" class="form-control  basic">
                                                    <option value="<?= $row['transfer']?>">Select</option>
                                                    <option value="1">ACTIVE</option>
                                                    <option value="0" >DEACTIVATE</option>
                                                </select>
                                            </div>
                                            <div class=" text-center">
                                                <button class="btn btn-primary" name="transfer">Change Trasfer Status</button>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- 72-Hour New Device Transaction Freeze Manager -->
                                    <div class="row mt-4 pt-3" style="border-top: 1px dashed #e0e6ed;">
                                        <div class="col-md-12">
                                            <?php
                                            $freezeInfo = function_exists('getTransactionFreezeStatus') ? getTransactionFreezeStatus($row) : ['is_frozen' => false];
                                            $isFrozen = !empty($freezeInfo['is_frozen']);
                                            ?>
                                            <div class="p-3" style="background: <?= $isFrozen ? '#fff7ed' : '#f0fdf4' ?>; border: 1px solid <?= $isFrozen ? '#f97316' : '#22c55e' ?>; border-radius: 8px;">
                                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                    <div>
                                                        <h6 style="margin: 0; font-weight: 700; color: <?= $isFrozen ? '#9a3412' : '#166534' ?>;">
                                                            🛡️ 72-Hour New Device Transaction Freeze: 
                                                            <span class="badge <?= $isFrozen ? 'badge-danger' : 'badge-success' ?>">
                                                                <?= $isFrozen ? 'ACTIVE (FROZEN)' : 'INACTIVE (NORMAL)' ?>
                                                            </span>
                                                        </h6>
                                                        <small style="color: #64748b;">
                                                            <?= $isFrozen ? 'Account transactions restricted until: <b>' . htmlspecialchars($freezeInfo['lifts_at']) . '</b> (' . $freezeInfo['formatted_remaining'] . ' remaining)' : 'No active security freeze. Outgoing transactions allowed.' ?>
                                                        </small>
                                                    </div>
                                                    <div class="mt-2 mt-sm-0">
                                                        <?php if ($isFrozen): ?>
                                                            <button type="submit" name="lift_freeze" class="btn btn-success btn-sm">Lift Security Hold</button>
                                                        <?php else: ?>
                                                            <button type="submit" name="apply_freeze" class="btn btn-warning btn-sm">Apply 72h Security Freeze</button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 layout-spacing">
                            <form class="section about" method="post">
                                <div class="info">
                                    <h5 class="">Change Password</h5>
                                    <div class="row">
                                        <div class="col-md-11 mx-auto">
                                            <div class="form-group">
                                                <label>Old Password</label>
                                                <input type="password" class="form-control mb-4" name="old_password" placeholder="Old Password" value="">
                                            </div>
                                            <div class="form-group">
                                                <label>New Password</label>
                                                <input type="password" class="form-control mb-4" name="new_password" placeholder="New Password" value="">
                                            </div>

                                            <div class="form-group">
                                                <label>Confirm Password</label>
                                                <input type="password" class="form-control mb-4" name="confirm_password" placeholder="Confirm Password">
                                            </div>

                                            <div class="form-group">
                                                <button class="btn btn-primary" name="change_password">Change Password</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 layout-spacing">
                            <form class="section about" method="post" autocomplete="off" autofocus="off">
                                <div class="info">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="">Change Pin</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <a class="btn btn-danger text-center col-md-12 disabled ">Current Pin : <?= $row['acct_pin']?></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-11 mx-auto">
                                            <div class="form-group">
<!--                                                <p class="text-danger"></p>-->
                                                <label>Current Pin</label>
                                                <input type="password" class="form-control mb-4" name="current_pin" placeholder="Current Pin" value="">
                                            </div>
                                            <div class="form-group">
                                                <label>New Pin</label>
                                                <input type="password" class="form-control mb-4" name="new_pin" placeholder="New Pin" value="">
                                            </div>

                                            <div class="form-group">
                                                <label>Confirm Pin</label>
                                                <input type="password" class="form-control mb-4" name="confirm_pin" placeholder="Confirm Pin">
                                            </div>
                                            <div class="form-group">
                                                <button class="btn btn-primary" name="change_pin" > Change Pin</button>
                                                
                                                <form class="section about" method="POST">
                        
                                                <button class="btn btn-danger" name="status_delete" >  Delete User</button>
                                           
                                                </form>
                                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            
                            
                        </div>
                        
                        


                    </div>
                </div>
            </div>
            
            
            <?php
include_once("./layout/footer.php");
?>
