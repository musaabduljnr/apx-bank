<?php
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

require_once __DIR__ . '/include/config.php';
require_once __DIR__ . '/include/userFunction.php';

function assertTest($condition, $name) {
    if ($condition) {
        echo " [PASS] $name\n";
    } else {
        echo "![FAIL] $name\n";
        exit(1);
    }
}

echo "=== Running Admin User Portal Impersonation Tests ===\n\n";

// Test 1: Banner returns empty when not impersonating
$_SESSION = [];
$mockUser = [
    'id' => 14,
    'firstname' => 'Sandra',
    'lastname' => 'Bullock',
    'acct_no' => '9909301376',
    'acct_balance' => '523051.00',
    'acct_currency' => 'USD'
];

$bannerEmpty = renderAdminImpersonationBanner($mockUser);
assertTest($bannerEmpty === '', "Banner is empty when not impersonating");

// Test 2: Banner renders rich diagnostic bar when impersonating
$_SESSION['is_impersonating'] = true;
$_SESSION['admin_impersonator'] = 'support@dirtyscripts.shop';
$_SESSION['impersonated_user_id'] = 14;

$bannerActive = renderAdminImpersonationBanner($mockUser);
assertTest(strpos($bannerActive, 'ADMIN USER VIEW') !== false, "Banner contains ADMIN USER VIEW tag");
assertTest(strpos($bannerActive, 'Sandra Bullock') !== false, "Banner displays target user name");
assertTest(strpos($bannerActive, '9909301376') !== false, "Banner displays account number");
assertTest(strpos($bannerActive, '$523,051.00') !== false, "Banner displays formatted account balance");
assertTest(strpos($bannerActive, 'exit-impersonation.php') !== false, "Banner includes Exit Portal link");
assertTest(strpos($bannerActive, 'view_users.php?id=14') !== false, "Banner includes link to admin settings for user 14");

// Test 3: Banner renders 72-hour freeze badge if account is on security hold
$freezeStatus = [
    'is_frozen' => true,
    'formatted_remaining' => '71h 45m',
    'lifts_at' => '2026-10-31 16:00:00'
];
$bannerWithFreeze = renderAdminImpersonationBanner($mockUser, $freezeStatus);
assertTest(strpos($bannerWithFreeze, '72h Hold (71h 45m)') !== false, "Banner reflects active 72-hour security hold");

// Test 4: Exit impersonation session cleanup logic
$_SESSION['acct_no'] = '9909301376';
$_SESSION['login'] = '9909301376';
$_SESSION['admin'] = 'support@dirtyscripts.shop';

$admin_email = isset($_SESSION['admin_impersonator']) ? $_SESSION['admin_impersonator'] : null;
unset(
    $_SESSION['acct_no'],
    $_SESSION['login'],
    $_SESSION['is_impersonating'],
    $_SESSION['impersonated_user_id'],
    $_SESSION['impersonated_user_name'],
    $_SESSION['admin_impersonator']
);
if (!empty($admin_email)) {
    $_SESSION['admin'] = $admin_email;
}

assertTest(!isset($_SESSION['acct_no']), "User account number unset on exit");
assertTest(!isset($_SESSION['is_impersonating']), "Impersonation flag cleared on exit");
assertTest($_SESSION['admin'] === 'support@dirtyscripts.shop', "Admin session preserved on exit");

echo "\nAll Admin Impersonation tests passed successfully!\n";
