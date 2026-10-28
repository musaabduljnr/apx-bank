<?php
session_start();
include_once("../include/userFunction.php");

if (!empty($_SESSION['is_impersonating']) || !empty($_SESSION['admin_impersonator'])) {
    $user_id = isset($_SESSION['impersonated_user_id']) ? intval($_SESSION['impersonated_user_id']) : 0;
    $admin_email = isset($_SESSION['admin_impersonator']) ? $_SESSION['admin_impersonator'] : (isset($_SESSION['admin']) ? $_SESSION['admin'] : null);
    unset(
        $_SESSION['acct_no'],
        $_SESSION['wire_transfer'],
        $_SESSION['dom-transfer'],
        $_SESSION['login'],
        $_SESSION['is_impersonating'],
        $_SESSION['admin_impersonator'],
        $_SESSION['impersonated_user_id'],
        $_SESSION['impersonated_user_name'],
        $_SESSION['impersonated_acct_no']
    );
    if (!empty($admin_email)) {
        $_SESSION['admin'] = $admin_email;
    }
    if ($user_id > 0) {
        header("Location: ../admin/view_users.php?id=" . $user_id);
    } else {
        header("Location: ../admin/users.php");
    }
    exit;
}

unset($_SESSION['acct_no'], $_SESSION['wire_transfer'],$_SESSION['dom-transfer'],$_SESSION['login']);
setcookie('firstVisit');
notify_alert('Logout','danger','2000','Close');

header("Location:../login.php");
exit();

