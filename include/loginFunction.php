<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/userClass.php";
require_once __DIR__ . "/userFunction.php";

$message = new message();
$conn = dbConnect();
$page = getSiteSettings($conn);
$pageTitle = $page['url_name'];
$BANK_PHONE = $page['url_tel'];




