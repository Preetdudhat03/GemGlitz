<?php
require_once __DIR__ . '/../includes/functions.php';

session_unset();
session_destroy();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

set_flash_message('info', 'Logged out from Admin Session');
header('Location: login.php');
exit;
?>
