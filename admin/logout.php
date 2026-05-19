<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (isset($_SESSION['admin_logged_in']) && isset($_SESSION['admin_id'])) {
    log_aktivitas($pdo, $_SESSION['admin_id'], "Logout dari panel admin");
    session_unset();
    session_destroy();
}
header("Location: login.php");
exit;
?>
