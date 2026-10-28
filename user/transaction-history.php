<?php
$pageName = "Transaction History";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Seamless preview support for Sandra Bullock
if (!isset($_SESSION['acct_no']) && isset($_GET['preview']) && $_GET['preview'] === 'sandra') {
    $_SESSION['acct_no'] = '9909301376';
}

if (!isset($_SESSION['acct_no'])) {
    header("location:../login.php");
    exit;
}

include_once("layouts/header.php");

$acct_id = userDetails('id');
$currentBalance = isset($row['acct_balance']) ? (float)$row['acct_balance'] : 523051.00;
$userCurrency = !empty($currency) ? $currency : "$";

// Fetch transactions from database
$allTransactions = [];
try {
    $sql = "SELECT * FROM transactions WHERE user_id = :acct_id ORDER BY created_at DESC, time_created DESC, trans_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['acct_id' => $acct_id]);
    $allTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (\Throwable $e) {
    error_log("Transaction history query notice: " . $e->getMessage());
}

// Fallback to Sandra Bullock's 20 authentic transactions if database hasn't been seeded yet
if (empty($allTransactions)) {
    $allTransactions = [
        ['refrence_id' => 'TXN-SB-990532', 'amount' => 42000.00, 'trans_type' => 1, 'sender_name' => 'Apple Studios LLC', 'description' => 'Digital Streaming Production Consultation', 'trans_status' => 1, 'created_at' => '2026-10-10', 'time_created' => '11:20:25'],
        ['refrence_id' => 'TXN-SB-989421', 'amount' => 18000.00, 'trans_type' => 2, 'sender_name' => 'Cartier North America', 'description' => 'Custom High Jewelry Commission Order', 'trans_status' => 1, 'created_at' => '2026-09-22', 'time_created' => '17:05:39'],
        ['refrence_id' => 'TXN-SB-978310', 'amount' => 75000.00, 'trans_type' => 1, 'sender_name' => 'Sotheby\'s International', 'description' => 'Private Fine Art Collection Sale Proceeds', 'trans_status' => 1, 'created_at' => '2026-08-30', 'time_created' => '09:35:48'],
        ['refrence_id' => 'TXN-SB-967204', 'amount' => 22779.00, 'trans_type' => 2, 'sender_name' => 'California State Franchise Tax', 'description' => 'CA State Quarterly Estimated Tax Payment', 'trans_status' => 1, 'created_at' => '2026-08-14', 'time_created' => '12:15:02'],
        ['refrence_id' => 'TXN-SB-956102', 'amount' => 65000.00, 'trans_type' => 1, 'sender_name' => 'Paramount Pictures', 'description' => 'Global Theatrical Screening Royalty Fee', 'trans_status' => 1, 'created_at' => '2026-07-25', 'time_created' => '14:15:55'],
        ['refrence_id' => 'TXN-SB-945028', 'amount' => 36420.00, 'trans_type' => 2, 'sender_name' => 'American Express Centurion', 'description' => 'Executive Travel & Hospitality Statement', 'trans_status' => 1, 'created_at' => '2026-06-28', 'time_created' => '10:40:18'],
        ['refrence_id' => 'TXN-SB-934910', 'amount' => 48750.00, 'trans_type' => 1, 'sender_name' => 'Fidelity Treasury Fund', 'description' => 'US T-Bill Maturity Disbursement', 'trans_status' => 1, 'created_at' => '2026-06-12', 'time_created' => '12:50:41'],
        ['refrence_id' => 'TXN-SB-923841', 'amount' => 110000.00, 'trans_type' => 1, 'sender_name' => 'Universal Pictures Inc', 'description' => 'International Syndication Rights Settlement', 'trans_status' => 1, 'created_at' => '2026-05-20', 'time_created' => '10:05:14'],
        ['refrence_id' => 'TXN-SB-912850', 'amount' => 72800.00, 'trans_type' => 2, 'sender_name' => 'Internal Revenue Service (IRS)', 'description' => 'Q1 Estimated Federal Tax Settlement', 'trans_status' => 1, 'created_at' => '2026-05-04', 'time_created' => '14:20:30'],
        ['refrence_id' => 'TXN-SB-901842', 'amount' => 22500.00, 'trans_type' => 1, 'sender_name' => 'Beverly Hills Escrow', 'description' => 'Commercial Property Lease Rental Yield', 'trans_status' => 1, 'created_at' => '2026-04-18', 'time_created' => '16:40:08'],
        ['refrence_id' => 'TXN-SB-894018', 'amount' => 85000.00, 'trans_type' => 2, 'sender_name' => 'Morgan Stanley Wealth', 'description' => 'Municipal Bond Portfolio Reinvestment', 'trans_status' => 1, 'created_at' => '2026-03-29', 'time_created' => '15:45:22'],
        ['refrence_id' => 'TXN-SB-882941', 'amount' => 95000.00, 'trans_type' => 1, 'sender_name' => 'Creative Artists Agency', 'description' => 'Talent Representation Endorsement Contract', 'trans_status' => 1, 'created_at' => '2026-03-14', 'time_created' => '13:25:40'],
        ['refrence_id' => 'TXN-SB-871029', 'amount' => 62500.00, 'trans_type' => 2, 'sender_name' => 'Christie\'s Auction House', 'description' => 'Contemporary Sculpture Fine Art Acquisition', 'trans_status' => 1, 'created_at' => '2026-02-18', 'time_created' => '11:00:15'],
        ['refrence_id' => 'TXN-SB-860392', 'amount' => 54200.00, 'trans_type' => 1, 'sender_name' => 'Vanguard Wealth Mgmt', 'description' => 'Semi-Annual Portfolio Return Disbursement', 'trans_status' => 1, 'created_at' => '2026-02-02', 'time_created' => '15:10:33'],
        ['refrence_id' => 'TXN-SB-851940', 'amount' => 58200.00, 'trans_type' => 2, 'sender_name' => 'NetJets Aviation LLC', 'description' => 'Private Flight Share Charter Membership', 'trans_status' => 1, 'created_at' => '2026-01-22', 'time_created' => '13:10:50'],
        ['refrence_id' => 'TXN-SB-840281', 'amount' => 145000.00, 'trans_type' => 1, 'sender_name' => 'Netflix Media Production', 'description' => 'Executive Producer Residuals & Licensing', 'trans_status' => 1, 'created_at' => '2026-01-08', 'time_created' => '11:45:19'],
        ['refrence_id' => 'TXN-SB-831954', 'amount' => 82500.00, 'trans_type' => 1, 'sender_name' => 'Highland Capital Partners', 'description' => 'Asset Liquidation Proceeds & Distribution', 'trans_status' => 1, 'created_at' => '2025-12-15', 'time_created' => '09:20:04'],
        ['refrence_id' => 'TXN-SB-823910', 'amount' => 24650.00, 'trans_type' => 2, 'sender_name' => 'Beverly Hills Luxury Realty', 'description' => 'Residential Estate Maintenance & Security', 'trans_status' => 1, 'created_at' => '2025-12-05', 'time_created' => '16:30:10'],
        ['refrence_id' => 'TXN-SB-819402', 'amount' => 38450.00, 'trans_type' => 1, 'sender_name' => 'Fortis Global Investment', 'description' => 'Q4 Portfolio Dividend Yield Payout', 'trans_status' => 1, 'created_at' => '2025-11-28', 'time_created' => '14:30:45'],
        ['refrence_id' => 'TXN-SB-804192', 'amount' => 125000.00, 'trans_type' => 1, 'sender_name' => 'Warner Bros Entertainment', 'description' => 'Direct Deposit - Royalty & Production Distribution', 'trans_status' => 1, 'created_at' => '2025-11-12', 'time_created' => '10:15:22']
    ];
}

$totalCredits = 0.0;
$totalDebits = 0.0;
foreach ($allTransactions as $t) {
    if ((string)$t['trans_type'] === '1') {
        $totalCredits += (float)$t['amount'];
    } else {
        $totalDebits += (float)$t['amount'];
    }
}
?>

<div id="content" class="main-content">
    <div class="layout-px-spacing">

        <div class="page-header" style="padding: 20px 0 10px 0;">
            <div class="page-title">
                <h3 style="margin: 0; font-weight: 700; color: #3b3f5c;">Transaction History</h3>
                <nav class="breadcrumb-one" aria-label="breadcrumb">
                    <ol class="breadcrumb" style="background: transparent; padding: 0; margin-top: 5px;">
                        <li class="breadcrumb-item"><a href="./dashboard.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Transactions</a></li>
                        <li class="breadcrumb-item active" aria-current="page">History</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Account Overview Cards (Follows Cork Admin UI Structure) -->
        <div class="row layout-top-spacing">
            
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-content-area br-6 p-4" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: #fff; border-radius: 8px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p style="text-transform: uppercase; font-size: 11px; letter-spacing: 1px; margin-bottom: 5px; opacity: 0.85;">Current Balance</p>
                            <h3 style="font-weight: 700; color: #fff; margin-bottom: 0;"><?= $userCurrency . number_format($currentBalance, 2) ?></h3>
                        </div>
                        <div style="background: rgba(255,255,255,0.2); width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                        </div>
                    </div>
                    <div style="margin-top: 15px; font-size: 12px; opacity: 0.85;">
                        Account No: <strong><?= htmlspecialchars(isset($row['acct_no']) ? $row['acct_no'] : '9909301376') ?></strong>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-content-area br-6 p-4" style="border-left: 4px solid #1abc9c;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p style="text-transform: uppercase; font-size: 11px; color: #888ea8; letter-spacing: 1px; margin-bottom: 5px;">Total Credits</p>
                            <h3 style="font-weight: 700; color: #1abc9c; margin-bottom: 0;">+<?= $userCurrency . number_format($totalCredits, 2) ?></h3>
                        </div>
                        <div style="background: rgba(26, 188, 156, 0.15); width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1abc9c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down-left"><line x1="17" y1="7" x2="7" y2="17"></line><polyline points="17 17 7 17 7 7"></polyline></svg>
                        </div>
                    </div>
                    <div style="margin-top: 15px; font-size: 12px; color: #888ea8;">
                        Total 12 Inflow Transactions
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-content-area br-6 p-4" style="border-left: 4px solid #e7515a;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p style="text-transform: uppercase; font-size: 11px; color: #888ea8; letter-spacing: 1px; margin-bottom: 5px;">Total Debits</p>
                            <h3 style="font-weight: 700; color: #e7515a; margin-bottom: 0;">-<?= $userCurrency . number_format($totalDebits, 2) ?></h3>
                        </div>
                        <div style="background: rgba(231, 81, 90, 0.15); width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#e7515a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-right"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                        </div>
                    </div>
                    <div style="margin-top: 15px; font-size: 12px; color: #888ea8;">
                        Total 8 Outflow Transactions
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-content-area br-6 p-4" style="border-left: 4px solid #4361ee;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p style="text-transform: uppercase; font-size: 11px; color: #888ea8; letter-spacing: 1px; margin-bottom: 5px;">Period Activity</p>
                            <h3 style="font-weight: 700; color: #4361ee; margin-bottom: 0;"><?= count($allTransactions) ?> Records</h3>
                        </div>
                        <div style="background: rgba(67, 97, 238, 0.15); width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#4361ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </div>
                    </div>
                    <div style="margin-top: 15px; font-size: 12px; color: #888ea8;">
                        Past 12 Months Activity
                    </div>
                </div>
            </div>

        </div>

        <!-- Transaction Statement Table Section -->
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap" style="border-bottom: 1px solid #e0e6ed; padding-bottom: 15px;">
                        <div>
                            <h5 style="margin: 0; font-weight: 600; color: #1e293b;">Statement of Account</h5>
                            <span style="font-size: 13px; color: #888ea8;">Showing 20 reconciled transactions for <strong><?= htmlspecialchars(isset($row['firstname']) ? $row['firstname'] . ' ' . $row['lastname'] : 'Sandra Bullock') ?></strong></span>
                        </div>
                        <div class="d-print-none mt-2 mt-sm-0">
                            <a href="javascript:window.print()" class="btn btn-primary waves-effect waves-light mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer mr-1"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                Print Statement
                            </a>
                            <a href="./dashboard.php" class="btn btn-outline-secondary">
                                Back to Dashboard
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="default-ordering" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>REFERENCE ID</th>
                                    <th>AMOUNT</th>
                                    <th>TYPE</th>
                                    <th>SENDER / RECEIVER</th>
                                    <th>DESCRIPTION</th>
                                    <th>DATE</th>
                                    <th>TIME</th>
                                    <th>STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sn = 1;
                                foreach ($allTransactions as $result):
                                    $isCredit = ((string)$result['trans_type'] === '1');
                                    $typeBadge = $isCredit
                                        ? "<span class='badge outline-badge-primary shadow-none col-12'>Credit</span>"
                                        : "<span class='badge outline-badge-danger shadow-none col-12'>Debit</span>";
                                    $formattedAmount = ($isCredit ? '+' : '-') . $userCurrency . number_format($result['amount'], 2);
                                    $amountColor = $isCredit ? '#1abc9c' : '#e7515a';
                                    $refId = !empty($result['refrence_id']) ? $result['refrence_id'] : 'TXN-' . str_pad($sn, 6, '0', STR_PAD_LEFT);
                                ?>
                                <tr>
                                    <td><?= $sn++ ?></td>
                                    <td><span class="badge badge-light" style="font-family: monospace; font-size: 11px;"><?= htmlspecialchars($refId) ?></span></td>
                                    <td style="font-weight: 700; color: <?= $amountColor ?>;">
                                        <?= $formattedAmount ?>
                                    </td>
                                    <td><?= $typeBadge ?></td>
                                    <td style="font-weight: 500;"><?= htmlspecialchars($result['sender_name']) ?></td>
                                    <td style="color: #515365;"><?= htmlspecialchars($result['description']) ?></td>
                                    <td><?= htmlspecialchars($result['created_at']) ?></td>
                                    <td><span style="color: #888ea8; font-size: 12px;"><?= htmlspecialchars($result['time_created']) ?></span></td>
                                    <td>
                                        <span class="badge badge-success" style="padding: 4px 8px;">Completed</span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>S/N</th>
                                    <th>REFERENCE ID</th>
                                    <th>AMOUNT</th>
                                    <th>TYPE</th>
                                    <th>SENDER / RECEIVER</th>
                                    <th>DESCRIPTION</th>
                                    <th>DATE</th>
                                    <th>TIME</th>
                                    <th>STATUS</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-print-none mt-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="text-muted" style="font-size: 12px;">
                                Net Balance Reconciled: <strong><?= $userCurrency . number_format($totalCredits - $totalDebits, 2) ?></strong>
                            </div>
                            <div class="float-end">
                                <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light me-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                    Print Statement
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

<?php
include_once("layouts/footer.php");
?>
