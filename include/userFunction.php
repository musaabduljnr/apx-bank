<?php
//session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/userClass.php';
if (!isset($conn) || !($conn instanceof PDO)) {
    $conn = dbConnect();
}
$message = new USER();

if (!function_exists('active')) {
    function active($currect_page){
        $url_array =  explode('/', $_SERVER['REQUEST_URI']) ;
        $url = end($url_array);
        if($currect_page === $url){
            echo 'active';
        }
    }
}

//USERS CURRENCY
if (!function_exists('currency')) {
    function currency($row){
        if (!is_array($row) || !isset($row['acct_currency'])) {
            return "$";
        }
        if($row['acct_currency'] === 'USD'){
            return "$";
        }elseif($row['acct_currency'] === 'EUR'){
            return "&euro;";
        }
        return "$";
    }
}

//USER STATUS
if (!function_exists('userStatus')) {
    function userStatus($row){
        if (!is_array($row) || !isset($row['acct_status'])) {
            return '';
        }
        if ($row['acct_status'] === 'active') {
            return '<button class="btn btn-success btn-sm">ACTIVE</button>';
        }

        if($row['acct_status'] === 'hold') {
            return '<button class="btn btn-danger btn-sm">HOLD</button>';
        }
        return '';
    }
}

if (!function_exists('toast_alert')) {
    function toast_alert($type,$msg, $title = false){
        if ($title === false){
            $alert_title = "Ops!!";
        }else{
            $alert_title = $title;
        }

        $toast = '<script type="text/javascript">
            $(document).ready(function(){
            
              swal({
                type: "'.$type.'",
                title: "'.$alert_title.'",
                text: "'.$msg.'",
                padding: "2em",
              });
            });
        </script>';
        echo $toast;
    }
}

if (!function_exists('notify_alert')) {
    function notify_alert($msg,$colorType,$duration,$action = false){
        if($colorType === 'success'){
            $color = "#1abc9c";
        }elseif ($colorType  === 'danger'){
            $color = "#e7515a";
        }elseif ($colorType === 'warning'){
            $color = "#e2a03f";
        }elseif ($colorType === 'info'){
            $color = "#2196f3";
        }else{
            $color = "#4361ee";
        }

        if($action === false){
            $actionMsg = "DISMISS";
        }else{
            $actionMsg = $action;
        }

        $toast = '<script type="text/javascript">
            $(document).ready(function(){
            Snackbar.show({
                    text: "'.$msg.'",
                    actionTextColor: "#fff",
                    backgroundColor: "'.$color.'",
                    pos: "top-right",
                    duration: "'.$duration.'",
                    actionText: "'.$actionMsg.'"
                });
            });
        </script>';
        echo $toast;
    }
}

//DEPOSIT TRANSACTION STATUS
if (!function_exists('depositStatus')) {
    function depositStatus($result){
        if ($result['crypto_status'] === '0') {
            return '<span class="badge outline-badge-secondary shadow-none col-12">In Progress</span>';
        }
        if($result['crypto_status'] === '2'){
            return  '<span class="badge outline-badge-danger shadow-none col-12">Hold</span>';
        }

        if($result['crypto_status'] === '3') {
            return '<span class="badge outline-badge-danger shadow-none col-12">Cancelled</span>';
        }

        if($result['crypto_status'] === '1') {
            return '<span class="badge outline-badge-primary shadow-none col-12">Completed</span>';
        }
    }
}

//LOAN MODAL TRANSACTION STATUS
if (!function_exists('loanModalStatus')) {
    function loanModalStatus($result){
        if ($result['loan_status'] === '0') {
            return '<span class="badge outline-badge-secondary shadow-none col-12">In Progress</span>';
        }
        if($result['loan_status'] === '2'){
            return  '<span class="badge outline-badge-danger shadow-none col-12">Hold</span>';
        }

        if($result['loan_status'] === '3') {
            return '<span class="badge outline-badge-danger shadow-none col-12">Cancelled</span>';
        }

        if($result['loan_status'] === '1') {
            return '<span class="badge outline-badge-primary shadow-none col-12">Completed</span>';
        }
    }
}

//LOAN TRANSACTION
if (!function_exists('loanStatus')) {
    function loanStatus($result){
        if ($result['loan_status'] === '0') {
            return '<span class="text-primary">LOAN In Progress!</span>';
        }
        if($result['loan_status'] === '2'){
            return  '<span class="text-danger">LOAN On Hold!</span>';
        }

        if($result['loan_status'] === '3') {
            return '<span class="text-danger">LOAN Cancelled!</span>';
        }

        if($result['loan_status'] === '1') {
            return '<span class="text-success">LOAN Completed!</span>';
        }
    }
}

//DOMESTIC TRANSACTION STATUS
if (!function_exists('domestic')) {
    function domestic($result){
        if ($result['dom_status'] === '0') {
            return '<span class="badge outline-badge-secondary shadow-none col-12">In Progress</span>';
        }
        if($result['dom_status'] === '2'){
            return  '<span class="badge outline-badge-danger shadow-none col-12">Hold</span>';
        }

        if($result['dom_status'] === '3') {
            return '<span class="badge outline-badge-danger shadow-none col-12">Cancelled</span>';
        }

        if($result['dom_status'] === '1') {
            return '<span class="badge outline-badge-primary shadow-none col-12">Completed</span>';
        }
    }
}

if (!function_exists('transStatus')) {
    function transStatus($result){
        if ($result['trans_status'] === '0') {
            return '<span class="badge outline-badge-secondary shadow-none col-md-12">In Progress</span>';
        }
        if($result['trans_status'] === '2'){
            return  '<span class="badge outline-badge-danger shadow-none col-md-12">Hold</span>';
        }

        if($result['trans_status'] === '3') {
            return '<span class="badge outline-badge-danger shadow-none col-md-12">Cancelled</span>';
        }

        if($result['trans_status'] === '1') {
            return '<span class="badge outline-badge-primary shadow-none col-md-12">Completed</span>';
        }
    }
}

//WIRE TRANSACTION STATUS
if (!function_exists('wireStatus')) {
    function wireStatus($result){
        if ($result['wire_status'] === '0') {
            return '<span class="badge outline-badge-secondary shadow-none col-md-12">In Progress</span>';
        }
        if($result['wire_status'] === '2'){
            return  '<span class="badge outline-badge-danger shadow-none col-md-12">Hold</span>';
        }

        if($result['wire_status'] === '3') {
            return '<span class="badge outline-badge-danger shadow-none col-md-12">Cancelled</span>';
        }

        if($result['wire_status'] === '1') {
            return '<span class="badge outline-badge-primary shadow-none col-md-12">Completed</span>';
        }
    }
}

//USERS DETAILS WITH ACCOUNT NUM
if (!function_exists('userDetails')) {
    function userDetails($value){
        $conn = dbConnect();
        $acct_no = isset($_SESSION['acct_no']) ? $_SESSION['acct_no'] : '';
        $sql ="SELECT * FROM users WHERE acct_no = :acct_no";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'acct_no'=>$acct_no
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($row[$value]) ? $row[$value] : null;
    }
}

//Crypto Name
if (!function_exists('cryptoName')) {
    function cryptoName($value){
        $conn = dbConnect();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $crypto_id = isset($_SESSION['crypt']) ? $_SESSION['crypt'] : '';
        $sql ="SELECT * FROM crypto_currency WHERE id = :crypto_name";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'crypto_name'=>$crypto_id
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($row[$value]) ? $row[$value] : null;
    }
}

// =========================================================================
// 72-HOUR NEW DEVICE TRANSACTION FREEZE SECURITY SYSTEM
// =========================================================================

/**
 * Ensures user_devices table and freeze columns on users table exist.
 */
function initDeviceSecuritySystem($conn) {
    static $ready = false;
    if ($ready || !($conn instanceof PDO)) {
        return;
    }

    try {
        // Create user_devices table
        $conn->exec("CREATE TABLE IF NOT EXISTS `user_devices` (
            `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `user_id` int(11) NOT NULL DEFAULT 0,
            `device_fingerprint` varchar(64) NOT NULL,
            `device_name` text DEFAULT NULL,
            `ip_address` varchar(200) NOT NULL DEFAULT '127.0.0.1',
            `first_login` datetime NOT NULL DEFAULT current_timestamp(),
            `last_login` datetime NOT NULL DEFAULT current_timestamp(),
            `is_trusted` tinyint(1) NOT NULL DEFAULT 1,
            INDEX (`user_id`),
            INDEX (`device_fingerprint`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Ensure tx_freeze_until column exists on users
        $checkCol = $conn->query("SHOW COLUMNS FROM `users` LIKE 'tx_freeze_until'");
        if (!$checkCol || $checkCol->rowCount() === 0) {
            $conn->exec("ALTER TABLE `users` ADD COLUMN `tx_freeze_until` datetime DEFAULT NULL");
        }

        // Ensure tx_freeze_reason column exists on users
        $checkCol2 = $conn->query("SHOW COLUMNS FROM `users` LIKE 'tx_freeze_reason'");
        if (!$checkCol2 || $checkCol2->rowCount() === 0) {
            $conn->exec("ALTER TABLE `users` ADD COLUMN `tx_freeze_reason` text DEFAULT NULL");
        }

        $ready = true;
    } catch (\Throwable $e) {
        error_log("initDeviceSecuritySystem notice: " . $e->getMessage());
    }
}

/**
 * Parses user agent string into a readable device/browser name.
 */
function parseDeviceName($ua) {
    $platform = 'Computer';
    if (stripos($ua, 'windows nt 10') !== false) $platform = 'Windows 10/11 PC';
    elseif (stripos($ua, 'windows') !== false) $platform = 'Windows PC';
    elseif (stripos($ua, 'iphone') !== false) $platform = 'iPhone';
    elseif (stripos($ua, 'ipad') !== false) $platform = 'iPad';
    elseif (stripos($ua, 'android') !== false) $platform = 'Android Device';
    elseif (stripos($ua, 'macintosh') !== false || stripos($ua, 'mac os') !== false) $platform = 'Mac';
    elseif (stripos($ua, 'linux') !== false) $platform = 'Linux PC';

    $browser = 'Browser';
    if (stripos($ua, 'edg') !== false) $browser = 'Microsoft Edge';
    elseif (stripos($ua, 'chrome') !== false) $browser = 'Google Chrome';
    elseif (stripos($ua, 'firefox') !== false) $browser = 'Mozilla Firefox';
    elseif (stripos($ua, 'safari') !== false && stripos($ua, 'chrome') === false) $browser = 'Apple Safari';
    elseif (stripos($ua, 'opera') !== false || stripos($ua, 'opr') !== false) $browser = 'Opera';

    return "$browser on $platform";
}

/**
 * Generates unique fingerprint for current client.
 */
function getDeviceIdentifier() {
    $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : 'Unknown Browser';
    $ip = isset($_SERVER['REMOTE_ADDR']) ? trim($_SERVER['REMOTE_ADDR']) : '127.0.0.1';
    $deviceName = parseDeviceName($userAgent);

    $token = '';
    if (!empty($_COOKIE['apx_dev_token'])) {
        $token = $_COOKIE['apx_dev_token'];
    } else {
        $token = bin2hex(random_bytes(16));
        @setcookie('apx_dev_token', $token, time() + (365 * 86400), '/', '', false, true);
    }

    $fingerprint = hash('sha256', $userAgent . '|' . $token);

    return [
        'fingerprint' => $fingerprint,
        'user_agent' => $userAgent,
        'name' => $deviceName,
        'ip' => $ip,
        'token' => $token
    ];
}

/**
 * Handles new device check upon user authentication.
 * If login is from an unrecognized device, applies a strict 72-hour freeze on all transactions.
 */
function handleNewDeviceCheckOnLogin($conn, $user) {
    if (!($conn instanceof PDO) || empty($user) || empty($user['id'])) {
        return;
    }

    initDeviceSecuritySystem($conn);
    $user_id = (int)$user['id'];
    $deviceInfo = getDeviceIdentifier();
    $now = time();

    try {
        // Count previous registered devices
        $devStmt = $conn->prepare("SELECT COUNT(*) FROM user_devices WHERE user_id = :uid");
        $devStmt->execute(['uid' => $user_id]);
        $deviceCount = (int)$devStmt->fetchColumn();

        // Also check audit_logs
        $auditStmt = $conn->prepare("SELECT COUNT(*) FROM audit_logs WHERE user_id = :uid");
        $auditStmt->execute(['uid' => $user_id]);
        $auditCount = (int)$auditStmt->fetchColumn();

        $isNewDevice = false;

        if ($deviceCount > 0) {
            // Check if current fingerprint is recognized
            $matchStmt = $conn->prepare("SELECT id FROM user_devices WHERE user_id = :uid AND device_fingerprint = :fp LIMIT 1");
            $matchStmt->execute(['uid' => $user_id, 'fp' => $deviceInfo['fingerprint']]);
            $found = $matchStmt->fetch(PDO::FETCH_ASSOC);

            if (!$found) {
                // Secondary check: has this user logged in from this exact User-Agent before?
                $uaCheck = $conn->prepare("SELECT id FROM audit_logs WHERE user_id = :uid AND device = :dev LIMIT 1");
                $uaCheck->execute(['uid' => $user_id, 'dev' => $deviceInfo['user_agent']]);
                if (!$uaCheck->fetch()) {
                    $isNewDevice = true;
                }
            }
        } elseif ($auditCount > 0) {
            // User had prior logins in audit_logs: check if this user-agent matches any prior login
            $uaCheck = $conn->prepare("SELECT id FROM audit_logs WHERE user_id = :uid AND device = :dev LIMIT 1");
            $uaCheck->execute(['uid' => $user_id, 'dev' => $deviceInfo['user_agent']]);
            if (!$uaCheck->fetch()) {
                $isNewDevice = true;
            }
        } else {
            // First login ever for this account: establish initial trusted device
            $isNewDevice = false;
        }

        if ($isNewDevice) {
            // 72-hour transaction security freeze
            $freezeUntil = date('Y-m-d H:i:s', $now + (72 * 3600));
            $freezeReason = "Security Hold: Login from new device (" . $deviceInfo['name'] . ", IP: " . $deviceInfo['ip'] . ")";

            // Apply freeze to user account
            $upd = $conn->prepare("UPDATE users SET tx_freeze_until = :freeze_until, tx_freeze_reason = :reason WHERE id = :uid");
            $upd->execute([
                'freeze_until' => $freezeUntil,
                'reason' => $freezeReason,
                'uid' => $user_id
            ]);

            // Save new device record
            $insDev = $conn->prepare("INSERT INTO user_devices (user_id, device_fingerprint, device_name, ip_address, first_login, last_login, is_trusted) VALUES (:uid, :fp, :name, :ip, NOW(), NOW(), 0)");
            $insDev->execute([
                'uid' => $user_id,
                'fp' => $deviceInfo['fingerprint'],
                'name' => $deviceInfo['name'],
                'ip' => $deviceInfo['ip']
            ]);

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['tx_freeze_until'] = $freezeUntil;
            $_SESSION['tx_freeze_reason'] = $freezeReason;
            $_SESSION['new_device_freeze_triggered'] = true;

            // Send Security Notice Email
            try {
                if (class_exists('emailMessage')) {
                    global $sendMail, $email_message;
                    $fullName = $user['firstname'] . ' ' . $user['lastname'];
                    $userEmail = $user['acct_email'];
                    $appName = defined('WEB_TITLE') ? WEB_TITLE : 'APX Bank';
                    $subject = "[SECURITY ALERT] 72-Hour Security Freeze Activated - $appName";
                    $body = "<div style='font-family:sans-serif;max-width:600px;margin:auto;padding:20px;border:1px solid #e0e0e0;border-radius:8px;'>
                        <h2 style='color:#e7515a;'>New Device Login Detected</h2>
                        <p>Hello <strong>" . htmlspecialchars($fullName) . "</strong>,</p>
                        <p>We detected a sign-in to your online banking account from an unrecognized device:</p>
                        <ul style='background:#f8f9fa;padding:15px;border-radius:6px;list-style:none;'>
                            <li><strong>Device:</strong> " . htmlspecialchars($deviceInfo['name']) . "</li>
                            <li><strong>IP Address:</strong> " . htmlspecialchars($deviceInfo['ip']) . "</li>
                            <li><strong>Time:</strong> " . date('Y-m-d H:i:s') . "</li>
                        </ul>
                        <div style='background:#fff3cd;padding:15px;border-left:4px solid #ffc107;margin:20px 0;'>
                            <strong>72-Hour Transaction Security Hold in Effect:</strong><br>
                            To prevent unauthorized movement of your funds, all outgoing transactions (Wire transfers, Domestic transfers, Withdrawals) have been temporarily restricted for <strong>72 hours</strong> until <strong>" . date('F j, Y, g:i a', $now + (72 * 3600)) . "</strong>.
                        </div>
                        <p>If you authorized this login, no action is required; the security restriction will automatically expire after 72 hours.</p>
                        <p>If you did <strong>NOT</strong> initiate this login, please contact support immediately.</p>
                        <hr style='border:none;border-top:1px solid #eee;margin:20px 0;'>
                        <small style='color:#888;'>This is an automated security advisory from $appName Security Center.</small>
                    </div>";
                    if (isset($email_message) && method_exists($email_message, 'send_mail')) {
                        @$email_message->send_mail($userEmail, $body, $subject);
                    }
                }
            } catch (\Throwable $ex) {
                error_log("Device freeze email warning: " . $ex->getMessage());
            }

        } else {
            // Recognized or initial device: upsert device record
            $checkRec = $conn->prepare("SELECT id FROM user_devices WHERE user_id = :uid AND device_fingerprint = :fp LIMIT 1");
            $checkRec->execute(['uid' => $user_id, 'fp' => $deviceInfo['fingerprint']]);
            $existingDev = $checkRec->fetch(PDO::FETCH_ASSOC);

            if ($existingDev) {
                $updDev = $conn->prepare("UPDATE user_devices SET last_login = NOW(), ip_address = :ip WHERE id = :id");
                $updDev->execute(['ip' => $deviceInfo['ip'], 'id' => $existingDev['id']]);
            } else {
                $insDev = $conn->prepare("INSERT INTO user_devices (user_id, device_fingerprint, device_name, ip_address, first_login, last_login, is_trusted) VALUES (:uid, :fp, :name, :ip, NOW(), NOW(), 1)");
                $insDev->execute([
                    'uid' => $user_id,
                    'fp' => $deviceInfo['fingerprint'],
                    'name' => $deviceInfo['name'],
                    'ip' => $deviceInfo['ip']
                ]);
            }
        }
    } catch (\Throwable $e) {
        error_log("handleNewDeviceCheckOnLogin error: " . $e->getMessage());
    }
}

/**
 * Returns complete transaction freeze status for a given user or session.
 */
function getTransactionFreezeStatus($user = null) {
    global $conn;
    if (!($conn instanceof PDO) && empty($user)) {
        try {
            $conn = dbConnect();
        } catch (\Throwable $e) {}
    }

    if (empty($user)) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!empty($_SESSION['acct_no'])) {
            try {
                $stmt = $conn->prepare("SELECT * FROM users WHERE acct_no = :acct_no LIMIT 1");
                $stmt->execute(['acct_no' => $_SESSION['acct_no']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (\Throwable $e) {}
        }
    }

    if (empty($user)) {
        return ['is_frozen' => false];
    }

    // Check tx_freeze_until from DB
    $freezeUntil = !empty($user['tx_freeze_until']) ? $user['tx_freeze_until'] : null;

    // Fallback to session if present
    if (empty($freezeUntil) && !empty($_SESSION['tx_freeze_until'])) {
        $freezeUntil = $_SESSION['tx_freeze_until'];
    }

    if (!empty($freezeUntil)) {
        $freezeTimestamp = strtotime($freezeUntil);
        $now = time();

        if ($freezeTimestamp > $now) {
            $diff = $freezeTimestamp - $now;
            $hours = floor($diff / 3600);
            $minutes = floor(($diff % 3600) / 60);
            $seconds = $diff % 60;

            return [
                'is_frozen' => true,
                'freeze_until' => $freezeUntil,
                'seconds_remaining' => $diff,
                'hours_remaining' => $hours,
                'minutes_remaining' => $minutes,
                'formatted_remaining' => "{$hours}h {$minutes}m",
                'reason' => !empty($user['tx_freeze_reason']) ? $user['tx_freeze_reason'] : (!empty($_SESSION['tx_freeze_reason']) ? $_SESSION['tx_freeze_reason'] : "New device login security hold"),
                'lifts_at' => date('M j, Y \a\t g:i A', $freezeTimestamp)
            ];
        } else {
            // Freeze has expired! Clear it cleanly from DB and session
            try {
                if (!empty($user['id'])) {
                    $clr = $conn->prepare("UPDATE users SET tx_freeze_until = NULL, tx_freeze_reason = NULL WHERE id = :uid");
                    $clr->execute(['uid' => $user['id']]);
                }
            } catch (\Throwable $e) {}
            unset($_SESSION['tx_freeze_until']);
            unset($_SESSION['tx_freeze_reason']);
        }
    }

    return ['is_frozen' => false];
}

/**
 * Renders prominent alert banner for 72-hour transaction security freeze.
 */
function renderTransactionFreezeNotice($freezeStatus) {
    if (empty($freezeStatus['is_frozen'])) {
        return '';
    }

    $hours = $freezeStatus['hours_remaining'];
    $minutes = $freezeStatus['minutes_remaining'];
    $liftsAt = htmlspecialchars($freezeStatus['lifts_at']);
    $reason = htmlspecialchars($freezeStatus['reason']);

    return '
    <div class="row">
        <div class="col-md-12">
            <div class="alert custom-alert-1 mb-4" role="alert" style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border: 2px solid #f97316; border-radius: 10px; color: #7c2d12; padding: 20px; box-shadow: 0 4px 15px rgba(249, 115, 22, 0.15);">
                <div class="media align-items-center">
                    <div class="alert-icon mr-3" style="width: 52px; height: 52px; background: #ea580c; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shield"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    </div>
                    <div class="media-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-1">
                            <h5 style="color: #9a3412; font-weight: 700; margin: 0; font-size: 17px;">
                                🛡️ 72-Hour Security Freeze Active (New Device Login)
                            </h5>
                            <span class="badge badge-danger" style="font-size: 12px; padding: 6px 12px; font-weight: 700; letter-spacing: 0.5px;">
                                ' . $hours . 'h ' . $minutes . 'm REMAINING
                            </span>
                        </div>
                        <p style="margin: 6px 0 10px 0; font-size: 13.5px; line-height: 1.55; color: #431407;">
                            A recent login from an unrecognized device was detected. To safeguard your account against unauthorized withdrawals, all outgoing transactions (Domestic Transfers, Wire Transfers, and Withdrawals) are currently frozen for <strong>72 hours</strong>.
                        </p>
                        <div style="font-size: 12px; color: #7c2d12; padding-top: 8px; border-top: 1px dashed rgba(249, 115, 22, 0.4);">
                            <strong>Automatic Release:</strong> ' . $liftsAt . ' &nbsp;|&nbsp; <strong>Reason:</strong> ' . $reason . '
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}

/**
 * Backend Transaction Assertion Guard.
 * Call before executing or processing any transfer or withdrawal.
 * Returns true if allowed, false if blocked by 72-hour freeze.
 */
function assertTransactionsAllowed($row = null) {
    $status = getTransactionFreezeStatus($row);
    if ($status['is_frozen']) {
        $msg = "Transaction Blocked: A 72-hour security hold is active due to a new device login. Remaining time: " . $status['formatted_remaining'] . " (Expires: " . $status['lifts_at'] . ").";
        if (function_exists('toast_alert')) {
            toast_alert('error', $msg, "Security Hold Active");
        }
        return false;
    }
    return true;
}

/**
 * Render sticky diagnostic bar when an Administrator is viewing/impersonating a user profile.
 */
function renderAdminImpersonationBanner($user, $freezeStatus = null) {
    if (empty($_SESSION['is_impersonating']) && empty($_SESSION['admin_impersonator'])) {
        return '';
    }

    $name = htmlspecialchars((isset($user['firstname']) ? $user['firstname'] : '') . ' ' . (isset($user['lastname']) ? $user['lastname'] : ''));
    $acct = htmlspecialchars(isset($user['acct_no']) ? $user['acct_no'] : '');
    $uid = isset($user['id']) ? intval($user['id']) : 0;
    $balance = isset($user['acct_balance']) ? number_format((float)$user['acct_balance'], 2) : '0.00';
    $currency = isset($user['acct_currency']) ? $user['acct_currency'] : '$';
    if ($currency === 'USD') $currency = '$';
    elseif ($currency === 'Euro') $currency = '€';
    elseif ($currency === 'GBP') $currency = '£';

    $isFrozen = !empty($freezeStatus['is_frozen']);
    $remaining = !empty($freezeStatus['formatted_remaining']) ? $freezeStatus['formatted_remaining'] : '';

    $freezeHtml = '';
    if ($isFrozen) {
        $freezeHtml = '<span class="d-none d-md-inline-flex" style="background: rgba(239, 68, 68, 0.25); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.45); font-size: 11px; padding: 2px 8px; border-radius: 4px; font-weight: 600; align-items: center; gap: 4px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            72h Hold (' . htmlspecialchars($remaining) . ')
        </span>';
    }

    return '
    <div id="admin-impersonation-bar" style="position: fixed; top: 0; left: 0; right: 0; height: 42px; z-index: 999999; background: linear-gradient(90deg, #091a2f 0%, #0f2744 50%, #091a2f 100%); border-bottom: 2px solid #38bdf8; color: #fff; padding: 0 16px; font-size: 12px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, sans-serif; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 15px rgba(0,0,0,0.35);">
        <div style="display: flex; align-items: center; gap: 10px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
            <span style="background: linear-gradient(90deg, #0284c7 0%, #2563eb 100%); color: #fff; font-weight: 700; font-size: 10px; padding: 3px 8px; border-radius: 4px; letter-spacing: 0.5px; text-transform: uppercase; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 5px rgba(2,132,199,0.4);">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                ADMIN USER VIEW
            </span>
            <span style="color: #cbd5e1;">
                Viewing as: <strong style="color: #38bdf8; font-weight: 600;">' . $name . '</strong>
                <span style="color: #64748b; margin: 0 4px;">|</span>
                Acct: <code style="color: #fde047; background: rgba(0,0,0,0.3); padding: 1px 5px; border-radius: 3px; font-size: 11px;">' . $acct . '</code>
                <span class="d-none d-sm-inline" style="color: #64748b; margin: 0 4px;">|</span>
                <span class="d-none d-sm-inline" style="color: #a7f3d0;">Bal: <strong>' . $currency . $balance . '</strong></span>
            </span>
            ' . $freezeHtml . '
        </div>
        <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
            <a href="../admin/view_users.php?id=' . $uid . '" style="color: #e2e8f0; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 4px; text-decoration: none; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;" onmouseover="this.style.background=\'rgba(255,255,255,0.18)\'" onmouseout="this.style.background=\'rgba(255,255,255,0.08)\'">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                <span class="d-none d-sm-inline">Admin Settings</span>
                <span class="d-inline d-sm-none">Settings</span>
            </a>
            <a href="./exit-impersonation.php" style="color: #fff; background: #dc2626; border: 1px solid #b91c1c; padding: 4px 12px; border-radius: 4px; text-decoration: none; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 6px rgba(220,38,38,0.4); transition: all 0.2s;" onmouseover="this.style.background=\'#ef4444\'" onmouseout="this.style.background=\'#dc2626\'">
                ✕ <span class="d-none d-sm-inline">Exit Portal</span>
                <span class="d-inline d-sm-none">Exit</span>
            </a>
        </div>
    </div>
    <style>
        body.is-impersonating-active .header-container.fixed-top {
            top: 42px !important;
        }
        body.is-impersonating-active .sidebar-wrapper {
            top: 122px !important;
        }
        body.is-impersonating-active #content {
            margin-top: 42px !important;
        }
        @media (max-width: 768px) {
            #admin-impersonation-bar {
                height: auto !important;
                padding: 6px 12px !important;
            }
            body.is-impersonating-active .header-container.fixed-top {
                top: 42px !important;
            }
            body.is-impersonating-active .sidebar-wrapper {
                top: 122px !important;
            }
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.body.classList.add("is-impersonating-active");
        });
    </script>
    ';
}













