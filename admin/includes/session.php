<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    // Redirect to login if the user is not logged in
    header('Location: login.php');
    exit;
}
?>