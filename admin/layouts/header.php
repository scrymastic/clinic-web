<!-- header.php -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($PAGE_TITLE); ?></title>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/all.min.css">
<link rel="stylesheet" href="../assets/css/side-navbar.css">
<script src="../assets/js/jquery-3.5.1.slim.min.js"></script>
<script src="../assets/js/popper.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/alert-message.js"></script>
<style>
    body {
        display: flex;
        margin: 0; /* Remove default margin */
        height: 100vh; /* Full height */
    }
    .main-content {
        padding: 20px; /* Add padding for main content */
        flex-grow: 1; /* Allow main content to grow and fill available space */
        overflow: auto; /* Enable scrolling if content overflows */
    }
    .sidebar {
        padding: 20px; /* Padding inside the sidebar */
    }
</style>

