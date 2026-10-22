<?php
require_once __DIR__ . "/include/config.php";

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Setup & Diagnostic</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f0f2f5; padding: 40px; margin: 0; }
        .card { max-width: 700px; margin: 0 auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 14px rgba(0,0,0,0.08); padding: 30px; }
        h1 { margin-top: 0; font-size: 24px; color: #1e293b; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 13px; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-info { background: #e0f2fe; color: #075985; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px 14px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        th { background: #f8fafc; color: #475569; }
        .btn { display: inline-block; padding: 10px 20px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600; margin-top: 20px; border: none; cursor: pointer; }
        .btn:hover { background: #1d4ed8; }
        pre { background: #f8fafc; padding: 14px; border-radius: 6px; font-size: 12px; overflow-x: auto; }
    </style>
</head>
<body>
<div class="card">
    <h1>Database Status & Table Initialization</h1>

    <?php
    $conn = dbConnect();
    $expectedTables = [
        'admin', 'audit_logs', 'card', 'card_request', 'crypto_currency',
        'deposit', 'domestic_transfer', 'loan', 'messages', 'settings',
        'temp_trans', 'transactions', 'users', 'v_bank', 'wire_transfer', 'withdrawal'
    ];

    if (isset($_POST['init_now'])) {
        autoInitializeDatabase($conn);
        echo "<div style='padding:12px;background:#dcfce7;color:#166534;border-radius:6px;margin-bottom:20px;'>Initialization completed! Review the table statuses below.</div>";
    }

    $existingTables = [];
    try {
        $stmt = $conn->query("SHOW TABLES");
        if ($stmt) {
            $existingTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
    } catch (\Throwable $e) {
        echo "<div style='padding:12px;background:#fee2e2;color:#991b1b;border-radius:6px;'>SHOW TABLES error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }

    $dbName = getenv('DB_NAME') ?: 'bankpro';
    $dbHost = getenv('DB_HOST') ?: 'localhost';
    ?>

    <p>
        <strong>Connected Database:</strong> <code><?= htmlspecialchars($dbName) ?></code> on <code><?= htmlspecialchars($dbHost) ?></code>
    </p>

    <table>
        <thead>
            <tr>
                <th>Required Table</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($expectedTables as $tbl): ?>
            <tr>
                <td><code><?= htmlspecialchars($tbl) ?></code></td>
                <td>
                    <?php if (in_array($tbl, $existingTables)): ?>
                        <span class="badge badge-success">&#10003; Found</span>
                    <?php else: ?>
                        <span class="badge badge-danger">&#10007; Missing</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <form method="POST" style="margin-top:24px;">
        <button type="submit" name="init_now" value="1" class="btn">Import / Re-import All Tables Now</button>
        <a href="./login.php" class="btn" style="background:#64748b;margin-left:10px;">Go to Login</a>
    </form>
</div>
</body>
</html>
