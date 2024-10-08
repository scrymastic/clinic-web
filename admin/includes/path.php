<?php
// Clinic
$HOME_PAGE = "index.php";
$BRAND_NAME = "ClinxME";
$PATH = "/clinxme/admin";

switch ($_SERVER["SCRIPT_NAME"]) {
    case $PATH.'/login.php':
        $CURRENT_PAGE = "Login";
        $CURRENT_PATH = "Login";
        $PAGE_TITLE = "Login | $BRAND_NAME";
        break;
    
    case $PATH.'/patients-view.php':
        $CURRENT_PAGE = "View Patient";
        $CURRENT_PATH = "View Patient";
        $PAGE_TITLE = "Patient | $BRAND_NAME";
        break;
    
    // Doctor
    case $PATH.'/doctor-add.php':
        $CURRENT_PAGE = "Add Doctor";
        $CURRENT_PATH = "";
        $PAGE_TITLE = "Doctor | $BRAND_NAME";
        break;
    
        case $PATH.'/doctors-view.php':
        $CURRENT_PAGE = "Doctors";
        $CURRENT_PATH = "";
        $PAGE_TITLE = "Doctor | $BRAND_NAME";
        break;

    case $PATH.'/specialties-view.php':
        $CURRENT_PAGE = "Specialties";
        $CURRENT_PATH = "Specialties";
        $PAGE_TITLE = "Specialties | $BRAND_NAME";
        break;

    case $PATH.'/specialty-add.php':
        $CURRENT_PAGE = "Add Specialty";
        $CURRENT_PATH = "Add Specialty";
        $PAGE_TITLE = "Add Specialty | $BRAND_NAME";
        break;


    case $PATH.'/appointments-view.php':
        $CURRENT_PAGE = "Appointments";
        $CURRENT_PATH = "Appointments";
        $PAGE_TITLE = "Appointments | $BRAND_NAME";
        break;  


    case $PATH.'/invoices-view.php':
        $CURRENT_PAGE = "Invoices";
        $CURRENT_PATH = "Invoices";
        $PAGE_TITLE = "Invoices | $BRAND_NAME";
        break;

    case $PATH.'/invoice-view.php':
        $CURRENT_PAGE = "Invoice";
        $CURRENT_PATH = "Invoice";
        $PAGE_TITLE = "Invoice | $BRAND_NAME";
        break;
    

    case $PATH.'/settings.php':
        $CURRENT_PAGE = "Settings";
        $CURRENT_PATH = "Settings";
        $PAGE_TITLE = "Settings | $BRAND_NAME";
        break;

    case $PATH.'/services-view.php':
        $CURRENT_PAGE = "Services";
        $CURRENT_PATH = "Services";
        $PAGE_TITLE = "Services | $BRAND_NAME";
        break;

    case $PATH.'/service-add.php':
        $CURRENT_PAGE = "Add Service";
        $CURRENT_PATH = "Add Service";
        $PAGE_TITLE = "Add Service | $BRAND_NAME";
        break;

    case $PATH.'/service-edit.php':
        $CURRENT_PAGE = "Edit Service";
        $CURRENT_PATH = "Edit Service";
        $PAGE_TITLE = "Edit Service | $BRAND_NAME";
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

