<?php

// Suppress warning/notice leaks on frontend while preserving execution and error logs
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('log_errors', '1');

// Exception handler to surface any uncaught exceptions visibly
set_exception_handler(function($e) {
    while (ob_get_level() > 0) {
        @ob_end_clean();
    }
    http_response_code(500);
    echo "<div style='font-family:sans-serif;padding:30px;background:#fff5f5;color:#b00;border:2px solid #e00;margin:20px;border-radius:8px;'>";
    echo "<h2>Application Exception</h2>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " on line <strong>" . $e->getLine() . "</strong></p>";
    echo "<pre style='background:#fdf2f2;padding:12px;border-radius:4px;overflow:auto;font-size:12px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
});

// Fatal error handler to surface unexpected fatal errors cleanly
register_shutdown_function(function() {
    $error = error_get_last();
    $fatalTypes = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR];
    if ($error !== null && in_array($error['type'], $fatalTypes)) {
        while (ob_get_level() > 0) {
            @ob_end_clean();
        }
        http_response_code(500);
        header('Content-Type: text/html');
        echo "<div style='font-family:sans-serif;padding:30px;background:#fff5f5;color:#b00;border:2px solid #e00;margin:20px;border-radius:8px;'>";
        echo "<h2>Application Error</h2>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($error['message']) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($error['file']) . " on line <strong>" . $error['line'] . "</strong></p>";
        echo "</div>";
    }
});

define("WEB_TITLE","APX Bank"); // Change Bank Name

// Properly detect HTTPS behind reverse proxies (Render, Cloudflare, etc.)
$isHttps = (
    (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] == 1)) ||
    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') ||
    (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) === 'on') ||
    (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||
    (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'onrender.com') !== false)
);
$protocol = $isHttps ? "https" : "http";
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "localhost:8000";
define("WEB_URL", "$protocol://$host"); // Dynamically matches PC, Phone IP, or Cloud Domain
if (!defined("APP_URL")) {
    define("APP_URL", WEB_URL . "/");
}
define("WEB_EMAIL","contact@apxbank.com"); // Change Your Website Email

$web_url = WEB_URL;

if (!function_exists('getSiteSettings')) {
    function getSiteSettings($conn = null) {
        static $cached = null;
        if ($cached !== null) {
            return $cached;
        }

        $defaults = [
            'id' => 1,
            'image' => 'logo.png',
            'about_us' => 'APX Bank provides modern, secure, and intuitive digital banking solutions for individuals and enterprises worldwide.',
            'url_name' => defined('WEB_TITLE') ? WEB_TITLE : 'APX Bank',
            'url_tel' => '18002792265',
            'url_email' => defined('WEB_EMAIL') ? WEB_EMAIL : 'contact@apxbank.com',
            'trans_limit_min' => 500,
            'trans_limit_max' => 500000,
            'livechat' => '',
            'twillio_status' => 0,
            'billing_code' => 1,
            'transfer' => 1,
            'bank_deposit' => 0,
        ];

        if ($conn instanceof PDO) {
            try {
                $stmt = $conn->query("SELECT * FROM settings WHERE id = '1' LIMIT 1");
                if ($stmt) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (is_array($row) && !empty($row)) {
                        $cached = array_merge($defaults, array_filter($row, function($val) {
                            return $val !== null && $val !== '';
                        }));
                        return $cached;
                    }
                }
            } catch (\Throwable $e) {
                error_log("getSiteSettings query notice: " . $e->getMessage());
            }
        }

        $cached = $defaults;
        return $cached;
    }
}

if (!function_exists('autoInitializeDatabase')) {
    function autoInitializeDatabase($conn) {
    if (!($conn instanceof PDO)) {
        return;
    }

    // Check if the 'users' table exists and has AUTO_INCREMENT on 'id'
    $needsRebuild = false;
    try {
        $check = $conn->query("SELECT EXTRA FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'id'");
        $extra = $check ? $check->fetchColumn() : '';
        if (empty($extra) || stripos($extra, 'auto_increment') === false) {
            $needsRebuild = true;
        }
    } catch (\Throwable $e) {
        $needsRebuild = true;
    }

    if (!$needsRebuild) {
        return; // Schema is already correct with native AUTO_INCREMENT
    }

    // If tables already exist without AUTO_INCREMENT, drop them cleanly so they can be recreated properly
    $tables = [
        'withdrawal', 'wire_transfer', 'v_bank', 'transactions', 'temp_trans',
        'settings', 'messages', 'loan', 'domestic_transfer', 'deposit',
        'crypto_currency', 'card_request', 'card', 'audit_logs', 'admin', 'user_devices', 'users'
    ];
    foreach ($tables as $tbl) {
        try {
            $conn->exec("DROP TABLE IF EXISTS `$tbl`");
        } catch (\Throwable $e) {}
    }

    $candidatePaths = [
        __DIR__ . "/../database.sql",
        __DIR__ . "/../SQL File/database.sql",
        dirname(__DIR__) . "/database.sql",
        dirname(__DIR__) . "/SQL File/database.sql",
        "/var/www/html/database.sql",
        "/var/www/html/SQL File/database.sql",
    ];

    $sqlFile = null;
    foreach ($candidatePaths as $path) {
        if (file_exists($path)) {
            $sqlFile = $path;
            break;
        }
    }

    if (!$sqlFile) {
        error_log("autoInitializeDatabase: database.sql not found in candidate paths.");
        return;
    }

    $sql = file_get_contents($sqlFile);
    if (empty($sql)) {
        return;
    }

    // Strip comments
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

    // Split statements on semicolon followed by newline
    $statements = preg_split('/;\s*[\r\n]+/', $cleanSql);
    $executed = 0;
    foreach ($statements as $stmt) {
        $stmt = trim($stmt);
        if (!empty($stmt)) {
            try {
                $conn->exec($stmt);
                $executed++;
            } catch (\Throwable $e) {
                // Ignore harmless notice
                error_log("SQL import notice: " . $e->getMessage());
            }
        }
    }
        error_log("autoInitializeDatabase: Successfully built schema with AUTO_INCREMENT ($executed statements).");
    }
}

if (!function_exists('dbConnect')) {
    function dbConnect(){
        $servername = getenv("DB_HOST") ?: "localhost";
        $username   = getenv("DB_USER") ?: "root";
        $password   = getenv("DB_PASS") !== false ? getenv("DB_PASS") : "";
        $database   = getenv("DB_NAME") ?: "bankpro";
        $port       = getenv("DB_PORT") ?: "3306";

        // System schemas (sys, mysql, information_schema, performance_schema) deny user CREATE TABLE.
        // Automatically switch to 'test' (standard TiDB Cloud writeable database) or user database.
        $systemSchemas = ['sys', 'mysql', 'information_schema', 'performance_schema'];
        if (in_array(strtolower(trim($database)), $systemSchemas) || empty($database)) {
            $database = 'test';
        }

        $dns = "mysql:host=$servername;port=$port;dbname=$database;charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
        ];

        // Enable SSL for cloud databases (TiDB, Aiven, Railway, etc.)
        if ($servername !== "localhost" && $servername !== "127.0.0.1") {
            if (defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT')) {
                $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
            }
            if (defined('PDO::MYSQL_ATTR_SSL_CA') && file_exists('/etc/ssl/certs/ca-certificates.crt')) {
                $options[PDO::MYSQL_ATTR_SSL_CA] = '/etc/ssl/certs/ca-certificates.crt';
            }
        }

        try {
            $conn = new PDO($dns, $username, $password, $options);
            // Relax strict SQL mode to prevent 'doesn't have a default value' errors on non-critical omitted fields
            $conn->exec("SET sql_mode = 'NO_ENGINE_SUBSTITUTION'");
            autoInitializeDatabase($conn);
            return $conn;
        } catch(PDOException $e) {
            error_log("Database Connection failed: " . $e->getMessage());
            die("<div style='font-family:Segoe UI,Tahoma,sans-serif;max-width:550px;margin:60px auto;padding:24px;border:1px solid #f5c6cb;background:#f8d7da;color:#721c24;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.08);'>"
                . "<h2 style='margin-top:0;font-size:20px;'>Database Connection Failed</h2>"
                . "<p style='font-size:14px;line-height:1.5;'><b>Error:</b> " . htmlspecialchars($e->getMessage()) . "</p>"
                . "<hr style='border:0;border-top:1px solid #f1b0b7;margin:16px 0;'>"
                . "<div style='font-size:13px;color:#491217;line-height:1.8;'>"
                . "<b>Target Host:</b> " . htmlspecialchars($servername) . "<br>"
                . "<b>User:</b> " . htmlspecialchars($username) . "<br>"
                . "<b>Database:</b> " . htmlspecialchars($database) . "<br>"
                . "<b>Port:</b> " . htmlspecialchars($port) . "<br>"
                . "</div>"
                . "<p style='font-size:12px;color:#666;margin-bottom:0;margin-top:16px;'>Please check the <b>DB_HOST</b>, <b>DB_USER</b>, <b>DB_PASS</b>, and <b>DB_NAME</b> environment variables in your Render Dashboard.</p>"
                . "</div>");
        }
    }
}

if (!function_exists('inputValidation')) {
    function inputValidation($value): string
    {
        return trim(htmlspecialchars(htmlentities($value)));
    }
}