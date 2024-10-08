
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">CLINX<strong>ME</strong></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="services-view.php">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="doctors-view.php">Doctors</a>
                </li>
                <?php if (isset($_SESSION['patient_id']) && $_SESSION['patient_id']): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="appointments-view.php">Appointments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="invoices-view.php">Invoices</a>
                    </li>
                    </li class="nav-item">
                        <a class="nav-link" href="patient-view.php">My Profile</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <?php if (isset($_SESSION['patient_id']) && $_SESSION['patient_id']): ?>
                        <a class="nav-link" href="logout.php">Logout</a>
                    <?php else: ?>
                        <a class="nav-link" href="register.php">Login/Signup</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>
