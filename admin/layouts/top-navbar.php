<?php
$admin_name = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'admin';
?>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><?php echo htmlspecialchars($CURRENT_PAGE); ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="nav-link" style="color: #007bff;">Hello, Admin <?php echo htmlspecialchars($admin_name); ?></span>
                </li>
            </ul>
        </div>
    </div>
</nav>