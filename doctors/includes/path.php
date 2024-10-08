<?php
// Clinic
$HOME_PAGE = "index.php";
$BRAND_NAME = "ClinxME";
$PATH = "/clinxme/doctors";

switch ($_SERVER["SCRIPT_NAME"]) {
    case $PATH.'/login.php':
        $CURRENT_PAGE = "Login";
        $CURRENT_PATH = "Login";
        $PAGE_TITLE = "Login | $BRAND_NAME";
        break;

    case $PATH.'/appointments-view.php':
        $CURRENT_PAGE = "Appointments";
        $CURRENT_PATH = "Appointments";
        $PAGE_TITLE = "Appointments | $BRAND_NAME";
        break;

    case $PATH.'/exam-result-add.php':
        $CURRENT_PAGE = "Exam Result";
        $CURRENT_PATH = "Exam Result";
        $PAGE_TITLE = "Exam Result | $BRAND_NAME";
        break;

    // Index Page
    default:
        $CURRENT_PAGE = "Dashboard";
        $PAGE_TITLE = "Home | $BRAND_NAME";
        break;
}

define('SIDE_NAVBAR', './layouts/side-navbar.php');
define('TOP_NAVBAR', './layouts/top-navbar.php');
define('HEADER', './layouts/header.php');
define('FOOTER', './layouts/footer.php');
define('PAGINATOR', './layouts/table-paginator.php');
?>

