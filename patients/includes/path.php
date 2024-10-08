<?php
// Clinic
$HOME_PAGE = "index.php";
$BRAND_NAME = "ClinxME";
$PATH = "/clinxme/patients";

switch ($_SERVER["SCRIPT_NAME"]) {
    case $PATH.'/login.php':
        $CURRENT_PAGE = "Login";
        $CURRENT_PATH = "Login";
        $PAGE_TITLE = "Login | $BRAND_NAME";
        break;

    case $PATH.'/doctors-view.php':
        $CURRENT_PAGE = "Doctors";
        $CURRENT_PATH = "Doctors";
        $PAGE_TITLE = "Doctors | $BRAND_NAME";
        break;

    case $PATH.'/services-view.php':
        $CURRENT_PAGE = "Services";
        $CURRENT_PATH = "Services";
        $PAGE_TITLE = "Services | $BRAND_NAME";
        break;

    case $PATH.'/appointments-view.php':
        $CURRENT_PAGE = "Appointments";
        $CURRENT_PATH = "Appointments";
        $PAGE_TITLE = "Appointments | $BRAND_NAME";
        break;

    default:
        $CURRENT_PAGE = "Home";
        $CURRENT_PATH = "Home";
        $PAGE_TITLE = "Home | $BRAND_NAME";
        break;
}

define('TOP_NAVBAR', './layouts/top-navbar.php');
define('HEADER', './layouts/header.php');
define('FOOTER', './layouts/footer.php');


?>

