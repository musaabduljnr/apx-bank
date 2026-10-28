<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = isset($_SESSION['impersonated_user_id']) ? intval($_SESSION['impersonated_user_id']) : 0;
$admin_email = isset($_SESSION['admin_impersonator']) ? $_SESSION['admin_impersonator'] : (isset($_SESSION['admin']) ? $_SESSION['admin'] : null);

// Clear user impersonation tokens
unset(
    $_SESSION['acct_no'],
    $_SESSION['login'],
    $_SESSION['wire_transfer'],
    $_SESSION['dom_transfer'],
    $_SESSION['dom-transfer'],
    $_SESSION['crypt'],
    $_SESSION['is_impersonating'],
    $_SESSION['impersonated_user_id'],
    $_SESSION['impersonated_user_name'],
    $_SESSION['impersonated_acct_no'],
    $_SESSION['admin_impersonator']
);

// Restore and ensure admin credentials remain valid
if (!empty($admin_email)) {
    $_SESSION['admin'] = $admin_email;
}

// Redirect back to admin console
if ($user_id > 0) {
    header("Location: ../admin/view_users.php?id=" . $user_id);
} else {
    header("Location: ../admin/users.php");
}
exit;
