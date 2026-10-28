<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/include/session.php";
require_once __DIR__ . "/../include/config.php";

// 1. Verify admin authorization
if (empty($_SESSION['admin'])) {
    header("Location: ./login.php");
    exit;
}

$conn = dbConnect();

// 2. Identify target user
$user_id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);
$acct_no = isset($_GET['acct_no']) ? trim($_GET['acct_no']) : (isset($_POST['acct_no']) ? trim($_POST['acct_no']) : '');

if ($user_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
} elseif (!empty($acct_no)) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE acct_no = :acct_no");
    $stmt->execute(['acct_no' => $acct_no]);
} else {
    header("Location: ./users.php");
    exit;
}

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: ./users.php");
    exit;
}

// 3. Set up Impersonation Session
// Keep admin session alive and record impersonation metadata
$adminEmail = $_SESSION['admin'];
$_SESSION['admin_impersonator'] = $adminEmail;
$_SESSION['is_impersonating'] = true;
$_SESSION['impersonated_user_id'] = $user['id'];
$_SESSION['impersonated_user_name'] = $user['firstname'] . ' ' . $user['lastname'];
$_SESSION['impersonated_acct_no'] = $user['acct_no'];

// Populate active user session tokens
$_SESSION['acct_no'] = $user['acct_no'];
$_SESSION['login'] = $user['acct_no'];
$_SESSION['LAST_ACTIVITY'] = time();

// Optional audit log for admin accountability
try {
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
    $device = 'Admin Support Session (' . (isset($_SERVER['HTTP_USER_AGENT']) ? substr($_SERVER['HTTP_USER_AGENT'], 0, 80) : 'Browser') . ')';
    $logStmt = $conn->prepare("INSERT INTO audit_logs (user_id, device, ipAddress, datenow) VALUES (:uid, :dev, :ip, NOW())");
    $logStmt->execute([
        'uid' => $user['id'],
        'dev' => $device,
        'ip' => $ip
    ]);
} catch (\Throwable $e) {
    // Non-blocking
}

// 4. Redirect into user portal dashboard
header("Location: ../user/dashboard.php");
exit;
