
<?php require_once './includes/session.php'; ?>
<?php require_once './includes/path.php'; ?>
<?php require_once '../config/autoload.php'; ?>
<?php
    // Connect to the database as a user
    $conn = connect_as_doctor();
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
    .card-patients {
        color: #007bff; /* Custom blue color for text */
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
                // Assuming you store the logged-in doctor's ID in a session
                $doctor_id = $_SESSION['doctor_id'];

                // Fetch the total number of patients for this doctor
                $sql = 'SELECT COUNT(DISTINCT patients.id) AS patients_count
                        FROM patients
                        JOIN appointments ON patients.id = appointments.patient_id
                        WHERE appointments.doctor_id = :doctor_id';
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':doctor_id', $doctor_id);
                $stmt->execute();
                $patients_count = $stmt->fetchColumn();

                // Fetch the total number of appointments for this doctor
                $sql = 'SELECT COUNT(*) AS appointments_count
                        FROM appointments
                        WHERE doctor_id = :doctor_id';
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':doctor_id', $doctor_id);
                $stmt->execute();
                $appointments_count = $stmt->fetchColumn();
            ?>
            <section class="container-fluid my-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="summary-card card-patients">
                            <h2><?php echo htmlspecialchars($patients_count); ?></h2>
                            <p>Your Patients</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="summary-card card-appointments">
                            <h2><?php echo htmlspecialchars($appointments_count); ?></h2>
                            <p>Your Appointments</p>
                        </div>
                    </div>
                </div>
            </section>

        </main>
    </div>

</body>
</html>