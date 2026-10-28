<?php
/**
 * Standalone Account & Transaction Population Script
 * User: Sandra Bullock
 * Account Number: 9909301376
 * Net Balance: $523,051.00 USD
 * 20 Transactions from the last 12 months
 */

require_once __DIR__ . "/include/config.php";

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Populate Sandra Bullock - APX Bank</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #0e1726; color: #e0e6ed; padding: 40px; margin: 0; }
        .card { max-width: 850px; margin: 0 auto; background: #1b2e4b; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); padding: 30px; }
        h1 { margin-top: 0; font-size: 24px; color: #00d0f1; border-bottom: 1px solid #2e4a74; padding-bottom: 15px; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 13px; }
        .badge-success { background: rgba(0, 230, 153, 0.2); color: #00e699; }
        .badge-danger { background: rgba(231, 81, 90, 0.2); color: #e7515a; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin: 20px 0; }
        .stat-box { background: #0e1726; border-radius: 8px; padding: 15px; text-align: center; }
        .stat-val { font-size: 18px; font-weight: bold; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 13px; }
        th, td { padding: 9px 12px; text-align: left; border-bottom: 1px solid #2e4a74; }
        th { background: #132238; color: #888ea8; text-transform: uppercase; font-size: 11px; }
        .btn { display: inline-block; padding: 10px 20px; background: #4361ee; color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600; margin-top: 20px; border: none; cursor: pointer; }
        .btn:hover { background: #2544c4; }
        .btn-history { background: #00d0f1; color: #0e1726; margin-left: 10px; }
        .btn-history:hover { background: #00b4d2; }
    </style>
</head>
<body>
<div class="card">
    <h1>Sandra Bullock Account Seeder (9909301376)</h1>

    <?php
    $conn = dbConnect();
    $sqlFile = __DIR__ . "/populate_sandra_bullock.sql";

    if (!file_exists($sqlFile)) {
        die("<p style='color:#e7515a;'>Error: populate_sandra_bullock.sql not found.</p></div></body></html>");
    }

    $sql = file_get_contents($sqlFile);
    $lines = explode("\n", $sql);
    $cleanLines = [];
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || strpos($trimmed, '--') === 0 || strpos($trimmed, '/*') === 0) {
            continue;
        }
        $cleanLines[] = $line;
    }
    $cleanSql = implode("\n", $cleanLines);
    $statements = preg_split('/;\s*[\r\n]+/', $cleanSql);

    $executed = 0;
    $errors = [];
    foreach ($statements as $stmt) {
        $stmt = trim($stmt);
        if (!empty($stmt)) {
            try {
                $conn->exec($stmt);
                $executed++;
            } catch (\Throwable $ex) {
                $errors[] = $ex->getMessage();
            }
        }
    }

    // Verify User and Transactions
    $userStmt = $conn->prepare("SELECT * FROM users WHERE acct_no = '9909301376' LIMIT 1");
    $userStmt->execute();
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $transStmt = $conn->prepare("SELECT * FROM transactions WHERE user_id = :uid ORDER BY created_at DESC, time_created DESC");
        $transStmt->execute(['uid' => $user['id']]);
        $transactions = $transStmt->fetchAll(PDO::FETCH_ASSOC);

        $totalCredits = 0;
        $totalDebits = 0;
        foreach ($transactions as $t) {
            if ($t['trans_type'] == 1) {
                $totalCredits += (float)$t['amount'];
            } else {
                $totalDebits += (float)$t['amount'];
            }
        }
        $netCalculated = $totalCredits - $totalDebits;
        ?>

        <div style="padding:12px;background:rgba(0, 230, 153, 0.15);border:1px solid #00e699;border-radius:6px;margin-bottom:20px;color:#00e699;">
            <b>Seeding Successful!</b> Account <b><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></b> (<?= htmlspecialchars($user['acct_no']) ?>) is ready.
        </div>

        <div class="stats-grid">
            <div class="stat-box">
                <div style="color:#888ea8;font-size:12px;">ACCOUNT BALANCE</div>
                <div class="stat-val" style="color:#00d0f1;">$<?= number_format($user['acct_balance'], 2) ?></div>
            </div>
            <div class="stat-box">
                <div style="color:#888ea8;font-size:12px;">TOTAL CREDITS</div>
                <div class="stat-val" style="color:#00e699;">+$<?= number_format($totalCredits, 2) ?></div>
            </div>
            <div class="stat-box">
                <div style="color:#888ea8;font-size:12px;">TOTAL DEBITS</div>
                <div class="stat-val" style="color:#e7515a;">-$<?= number_format($totalDebits, 2) ?></div>
            </div>
            <div class="stat-box">
                <div style="color:#888ea8;font-size:12px;">TRANSACTIONS</div>
                <div class="stat-val"><?= count($transactions) ?> Loaded</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ref ID</th>
                    <th>Date & Time</th>
                    <th>Type</th>
                    <th>Sender / Receiver</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sn = 1;
                foreach ($transactions as $t): 
                    $isCredit = ($t['trans_type'] == 1);
                ?>
                <tr>
                    <td><?= $sn++ ?></td>
                    <td><code><?= htmlspecialchars($t['refrence_id']) ?></code></td>
                    <td><?= htmlspecialchars($t['created_at'] . ' ' . $t['time_created']) ?></td>
                    <td>
                        <span class="badge <?= $isCredit ? 'badge-success' : 'badge-danger' ?>">
                            <?= $isCredit ? 'Credit' : 'Debit' ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($t['sender_name']) ?></td>
                    <td style="font-weight:600;color:<?= $isCredit ? '#00e699' : '#e7515a' ?>">
                        <?= $isCredit ? '+' : '-' ?>$<?= number_format($t['amount'], 2) ?>
                    </td>
                    <td><span class="badge badge-success">Completed</span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-top:25px;">
            <a href="login.php" class="btn">Go to Login (Acct: 9909301376 / Pass: test)</a>
            <a href="user/transaction-history.php?preview=sandra" class="btn btn-history">View Transaction History Page</a>
        </div>

        <?php
    } else {
        echo "<p style='color:#e7515a;'>Error inserting user into database.</p>";
    }
    ?>
</div>
</body>
</html>
