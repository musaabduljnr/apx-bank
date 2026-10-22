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

    try {
        $conn = new PDO($dns, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        error_log("Database Connection failed: " . $e->getMessage());
        die("Database Connection failed. Please check your database configuration.");
    }
}
//return dbConnect();

function inputValidation($value): string
{
    return trim(htmlspecialchars(htmlentities($value)));
}