<?php

// Suppress raw PHP warning/notice leaks on frontend while preserving execution
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

define("WEB_TITLE","APX Bank"); // Change Bank Name
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "localhost:8000";
define("WEB_URL", "$protocol://$host"); // Dynamically matches PC or Phone IP
if (!defined("APP_URL")) {
    define("APP_URL", WEB_URL . "/");
}
define("WEB_EMAIL","contact@apxbank.com"); // Change Your Website Email

$web_url = WEB_URL;

function dbConnect(){
    $servername = getenv("DB_HOST") ?: "localhost";
    $username   = getenv("DB_USER") ?: "root";
    $password   = getenv("DB_PASS") !== false ? getenv("DB_PASS") : "";
    $database   = getenv("DB_NAME") ?: "bankpro";
    $port       = getenv("DB_PORT") ?: "3306";
    $dns = "mysql:host=$servername;port=$port;dbname=$database;charset=utf8mb4";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
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
        return $conn;
    } catch(PDOException $e) {
        error_log("Database Connection failed: " . $e->getMessage());
        die("<b>Database Connection failed:</b> " . htmlspecialchars($e->getMessage()) . "<br><br><small style='color:#777;'>Connecting to Host: <b>" . htmlspecialchars($servername) . "</b> | User: <b>" . htmlspecialchars($username) . "</b> | Database: <b>" . htmlspecialchars($database) . "</b> | Port: <b>" . htmlspecialchars($port) . "</b></small>");
    }
}
//return dbConnect();

function inputValidation($value): string
{
    return trim(htmlspecialchars(htmlentities($value)));
}