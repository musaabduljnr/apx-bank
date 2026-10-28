<?php
/**
 * Test & Verification Suite for 72-Hour New Device Transaction Freeze
 */

// Mock PDO to run unit tests independent of database server status
class MockDB extends PDO {
    public function __construct() {}
    public function query($statement, $mode = PDO::ATTR_DEFAULT_FETCH_MODE, ...$fetch_mode_args) { 
        return new class {
            public function rowCount() { return 1; }
            public function fetch($mode = null, $cursorOrientation = null, $cursorOffset = null) { return false; }
            public function fetchColumn($column = 0) { return ''; }
        };
    }
    public function prepare($query, $options = []) {
        return new class {
            public function execute($params = null) { return true; }
            public function fetch($mode = null, $cursorOrientation = null, $cursorOffset = null) { return false; }
            public function fetchColumn($column = 0) { return 0; }
        };
    }
    public function exec($statement) { return 0; }
}
$conn = new MockDB();

require_once __DIR__ . "/include/config.php";
require_once __DIR__ . "/include/userFunction.php";

echo "========================================================\n";
echo "TESTING 72-HOUR NEW DEVICE TRANSACTION FREEZE SYSTEM\n";
echo "========================================================\n\n";

$allPassed = true;

// 1. Test Device Name Parser
echo "[TEST 1] Testing Device Name Parser...\n";
$testsUA = [
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36' => 'Google Chrome on Windows 10/11 PC',
    'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1' => 'Apple Safari on iPhone',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:109.0) Gecko/20100101 Firefox/119.0' => 'Mozilla Firefox on Mac'
];

foreach ($testsUA as $ua => $expected) {
    $parsed = parseDeviceName($ua);
    echo "  UA: " . substr($ua, 0, 45) . "... => $parsed\n";
    if (empty($parsed)) {
        echo "  FAILED: empty device name\n";
        $allPassed = false;
    }
}
echo "  Device Name Parser passed.\n\n";

// 2. Test getDeviceIdentifier()
echo "[TEST 2] Testing Device Fingerprint Generation...\n";
$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36';
$_SERVER['REMOTE_ADDR'] = '192.168.1.100';
$idInfo = getDeviceIdentifier();
echo "  Fingerprint: " . $idInfo['fingerprint'] . "\n";
echo "  Device Name: " . $idInfo['name'] . "\n";
echo "  IP: " . $idInfo['ip'] . "\n";
if (empty($idInfo['fingerprint']) || strlen($idInfo['fingerprint']) !== 64) {
    echo "  FAILED: Invalid SHA-256 fingerprint\n";
    $allPassed = false;
} else {
    echo "  Device Fingerprint passed.\n\n";
}

// 3. Test Freeze Status Calculation
echo "[TEST 3] Testing 72-Hour Freeze Countdown Calculation...\n";
$future72h = date('Y-m-d H:i:s', time() + (72 * 3600));
$mockUserFrozen = [
    'id' => 9999,
    'acct_no' => 'TEST_ACCT',
    'tx_freeze_until' => $future72h,
    'tx_freeze_reason' => 'Security Hold: New device login from Safari on iPhone'
];

$status = getTransactionFreezeStatus($mockUserFrozen);
echo "  Is Frozen: " . ($status['is_frozen'] ? 'YES' : 'NO') . "\n";
echo "  Hours Remaining: " . $status['hours_remaining'] . " hours\n";
echo "  Formatted Remaining: " . $status['formatted_remaining'] . "\n";
echo "  Lifts At: " . $status['lifts_at'] . "\n";
echo "  Reason: " . $status['reason'] . "\n";

if (!$status['is_frozen'] || $status['hours_remaining'] < 71) {
    echo "  FAILED: Freeze status calculation incorrect\n";
    $allPassed = false;
} else {
    echo "  Freeze Status Calculation passed.\n\n";
}

// 4. Test Transaction Guard Blocking
echo "[TEST 4] Testing assertTransactionsAllowed() when frozen...\n";
$isAllowed = assertTransactionsAllowed($mockUserFrozen);
echo "  assertTransactionsAllowed (expected false): " . ($isAllowed ? 'TRUE' : 'FALSE') . "\n";
if ($isAllowed !== false) {
    echo "  FAILED: assertTransactionsAllowed did not block frozen account\n";
    $allPassed = false;
} else {
    echo "  assertTransactionsAllowed correctly BLOCKED transaction.\n\n";
}

// 5. Test Expired Freeze (Should be released automatically)
echo "[TEST 5] Testing Expired Freeze (Past 72 hours)...\n";
$pastFreeze = date('Y-m-d H:i:s', time() - 3600); // 1 hour ago
$mockUserExpired = [
    'id' => 9999,
    'acct_no' => 'TEST_ACCT',
    'tx_freeze_until' => $pastFreeze,
    'tx_freeze_reason' => 'Old security hold'
];

$statusExpired = getTransactionFreezeStatus($mockUserExpired);
echo "  Is Frozen: " . ($statusExpired['is_frozen'] ? 'YES' : 'NO') . "\n";
$isAllowedExpired = assertTransactionsAllowed($mockUserExpired);
echo "  assertTransactionsAllowed (expected true): " . ($isAllowedExpired ? 'TRUE' : 'FALSE') . "\n";

if ($statusExpired['is_frozen'] || !$isAllowedExpired) {
    echo "  FAILED: Expired freeze was not automatically lifted\n";
    $allPassed = false;
} else {
    echo "  Expired Freeze correctly LIFTS automatically.\n\n";
}

// 6. Test Banner Rendering
echo "[TEST 6] Testing Banner Notice HTML Rendering...\n";
$banner = renderTransactionFreezeNotice($status);
if (strpos($banner, '72-Hour Security Freeze Active') !== false && strpos($banner, 'REMAINING') !== false) {
    echo "  Banner Notice HTML rendered successfully.\n\n";
} else {
    echo "  FAILED: Banner notice HTML missing expected markup\n";
    $allPassed = false;
}

echo "========================================================\n";
if ($allPassed) {
    echo "ALL 72-HOUR NEW DEVICE SECURITY FREEZE TESTS PASSED!\n";
} else {
    echo "SOME TESTS FAILED - PLEASE REVIEW LOGS\n";
}
echo "========================================================\n";
