
<?php require_once './includes/session.php'; ?>
<?php require_once './includes/path.php'; ?>
<?php require_once '../config/autoload.php'; ?>
<?php
    // Connect to the database as a user
    $conn = connect_as_admin();
    if (!$conn) {
        die('Database connection failed');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once HEADER; ?>
    <!-- Add your refined custom CSS here -->
    <style>
    .summary-card {
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        margin-bottom: 20px;
    }
    .card-doctors {
        color: #007bff; /* Custom blue color for text */
    }
    .card-patients {
        color: #28a745; /* Custom green color for text */
    }
    .card-appointments {
        color: #17a2b8; /* Custom light blue color for text */
    }
    </style>
</head>

<body>
    <?php require_once SIDE_NAVBAR; ?>

    <div class="main-content">
        <?php require_once TOP_NAVBAR; ?>
        <main class="content mt-5">
            <?php
            // Fetch the total number of doctors
            $sql = 'SELECT COUNT(*) AS doctors_count FROM doctors';
            $stmt = $conn->query($sql);
            $doctors_count = $stmt->fetchColumn();

            // Fetch the total number of patients
            $sql = 'SELECT COUNT(*) AS patients_count FROM patients';
            $stmt = $conn->query($sql);
            $patients_count = $stmt->fetchColumn();

            // Fetch the total number of appointments
            $sql = 'SELECT COUNT(*) AS appointments_count FROM appointments';
            $stmt = $conn->query($sql);
            $appointments_count = $stmt->fetchColumn();
            ?>
            <section class="container-fluid my-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="summary-card card-doctors">
                            <h2><?php echo htmlspecialchars($doctors_count); ?></h2>
                            <p>Total Doctors</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-card card-patients">
                            <h2><?php echo htmlspecialchars($patients_count); ?></h2>
                            <p>Total Patients</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-card card-appointments">
                            <h2><?php echo htmlspecialchars($appointments_count); ?></h2>
                            <p>Total Appointments</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

</body>
</html>