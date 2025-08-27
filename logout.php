<?php 

session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if (isset($_SESSION['role']) && isset($_SESSION['id']) ) {
    if ($_SESSION['role'] === 'admin') {
        $redirectPage = 'admin_login.php';
    } elseif ($_SESSION['role'] === 'user') {
        $redirectPage = 'login.php';
    } else {
        $redirectPage = 'login.php';
    }
} else {
    $redirectPage = 'login.php';
}
session_unset();
session_destroy();

header("Location: login.php");
exit();
