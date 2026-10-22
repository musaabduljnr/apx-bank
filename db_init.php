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

    // Show available databases
    $databases = [];
    try {
        $dbStmt = $conn->query("SHOW DATABASES");
        if ($dbStmt) {
            $databases = $dbStmt->fetchAll(PDO::FETCH_COLUMN);
        }
    } catch (\Throwable $e) {
        $databases = ['Error: ' . $e->getMessage()];
    }

    if (isset($_POST['create_db']) && !empty($_POST['target_db'])) {
        $target = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['target_db']);
        try {
            $conn->exec("CREATE DATABASE IF NOT EXISTS `$target`");
            $conn->exec("USE `$target`");
            echo "<div style='padding:12px;background:#dcfce7;color:#166534;border-radius:6px;margin-bottom:20px;'>Database <b>$target</b> ready.</div>";
        } catch (\Throwable $e) {
            echo "<div style='padding:12px;background:#fee2e2;color:#991b1b;border-radius:6px;margin-bottom:20px;'>Create DB Error: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }

    $errors = [];
    if (isset($_POST['init_now'])) {
        $sqlFile = __DIR__ . "/database.sql";
        if (file_exists($sqlFile)) {
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
            $successCount = 0;
            foreach ($statements as $stmt) {
                $stmt = trim($stmt);
                if (!empty($stmt)) {
                    try {
                        $conn->exec($stmt);
                        $successCount++;
                    } catch (\Throwable $ex) {
                        $errors[] = $ex->getMessage() . " [in: " . htmlspecialchars(substr($stmt, 0, 70)) . "]";
                    }
                }
            }
            echo "<div style='padding:12px;background:#dcfce7;color:#166534;border-radius:6px;margin-bottom:20px;'>Executed $successCount statements.</div>";
        }
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
    <p>
        <strong>Available Databases on Server:</strong> <code><?= htmlspecialchars(implode(', ', $databases)) ?></code>
    </p>

    <?php if (!empty($errors)): ?>
        <div style="background:#fff5f5;border:1px solid #fed7d7;padding:15px;border-radius:6px;margin-bottom:20px;">
            <strong style="color:#c53030;">Import Errors:</strong>
            <ul style="color:#742a2a;font-size:13px;margin:8px 0 0;padding-left:20px;">
                <?php foreach (array_slice($errors, 0, 5) as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php
    if (isset($_POST['rebuild_schema'])) {
        $tables = [
            'withdrawal', 'wire_transfer', 'v_bank', 'transactions', 'temp_trans',
            'settings', 'messages', 'loan', 'domestic_transfer', 'deposit',
            'crypto_currency', 'card_request', 'card', 'audit_logs', 'admin', 'users'
        ];
        foreach ($tables as $tbl) {
            try { $conn->exec("DROP TABLE IF EXISTS `$tbl`"); } catch (\Throwable $e) {}
        }
        autoInitializeDatabase($conn);
        echo "<div style='padding:12px;background:#dcfce7;color:#166534;border-radius:6px;margin-bottom:20px;'>Dropped and rebuilt schema with native AUTO_INCREMENT!</div>";
    }
    ?>

    <table>
        <thead>
            <tr>
                <th>Required Table</th>
                <th>Status</th>
                <th>Auto-Increment</th>
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
                <td>
                    <?php
                    $isAuto = false;
                    try {
                        $ai = $conn->query("SELECT EXTRA FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$tbl' AND EXTRA LIKE '%auto_increment%' LIMIT 1");
                        if ($ai && $ai->fetchColumn()) {
                            $isAuto = true;
                        }
                    } catch (\Throwable $e) {}
                    ?>
                    <?php if ($isAuto): ?>
                        <span class="badge badge-success">&#10003; Enabled</span>
                    <?php else: ?>
                        <span class="badge badge-danger">&#10007; Disabled</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <form method="POST" style="margin-top:24px;">
        <button type="submit" name="rebuild_schema" value="1" class="btn" style="background:#dc2626;">Rebuild Schema with AUTO_INCREMENT</button>
        <button type="submit" name="init_now" value="1" class="btn">Import / Re-import Missing Tables</button>
        <a href="./login.php" class="btn" style="background:#64748b;margin-left:10px;">Go to Login</a>
    </form>
</div>
</body>
</html>
