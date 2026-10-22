<?php

// Display errors visibly so any failure is clear
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('log_errors', '1');

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

function autoInitializeDatabase($conn) {
    try {
        $check = $conn->query("SHOW TABLES LIKE 'settings'");
        if ($check && $check->rowCount() > 0) {
            return;
        }

        $sqlFile = __DIR__ . "/../SQL File/database.sql";
        if (!file_exists($sqlFile)) {
            return;
        }

        $sql = file_get_contents($sqlFile);
        if (!empty($sql)) {
            $conn->exec($sql);
            error_log("Database tables automatically imported from database.sql.");
        }
    } catch (\Throwable $e) {
        error_log("Auto DB Init notice: " . $e->getMessage());
    }
}

function dbConnect(){
    $servername = getenv("DB_HOST") ?: "localhost";
    $username   = getenv("DB_USER") ?: "root";
    $password   = getenv("DB_PASS") !== false ? getenv("DB_PASS") : "";
    $database   = getenv("DB_NAME") ?: "bankpro";
    $port       = getenv("DB_PORT") ?: "3306";
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
//return dbConnect();

function inputValidation($value): string
{
    return trim(htmlspecialchars(htmlentities($value)));
}