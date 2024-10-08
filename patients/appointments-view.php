<?php
// Include session check to ensure patient is logged in
require_once './includes/session.php';
require_once '../config/autoload.php'; // Include autoload file

$conn = connect_as_patient(); // Connection to the database

// Get the logged-in patient ID from session
$patient_id = $_SESSION['patient_id'];

// Query to get the patient's appointments
$sql = "SELECT a.id, a.appointment_time, a.duration, a.status, a.notes, a.cancel_reason,
               d.username as doctor_name, s.name as specialty
        FROM appointments a
        JOIN doctors d ON a.doctor_id = d.id
        JOIN specialties s ON d.specialty_id = s.id
        WHERE a.patient_id = :patient_id
        ORDER BY a.appointment_time DESC";
        
$stmt = $conn->prepare($sql);
$stmt->bindParam(':patient_id', $patient_id);
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once './includes/path.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once HEADER; ?>
    <link rel="stylesheet" href="../assets/css/table.css">
    <link rel="stylesheet" href="../assets/css/status.css">

    <style>
        .book-appointment-btn {
            margin: 20px 0;
        }
    </style>

</head>

<!-- body.php -->
<body>
    <?php require_once TOP_NAVBAR; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Your Appointments</h1>

        <!-- Add a button to book new appointments -->
        <div class="text-center book-appointment-btn">
            <a href="appointment-book.php" class="btn btn-primary">Book New Appointment</a>
        </div>

        <?php if (empty($appointments)): ?>
            <p>You have no appointments scheduled at this time.</p>
        <?php else: ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Appointment Time</th>
                        <th>Doctor</th>
                        <th>Specialty</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($appointment['appointment_time']))); ?></td>
                            <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['specialty']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['duration']); ?></td>
                            <td>
                                <?php
                                $status = htmlspecialchars($appointment['status']);
                                $statusClass = '';
                            
                                switch (strtolower($appointment['status'])) {
                                    case 'pending':
                                        $statusClass = 'status-pending';
                                        break;
                                    case 'scheduled':
                                        $statusClass = 'status-scheduled';
                                        break;
                                    case 'completed':
                                        $statusClass = 'status-completed';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'status-cancelled';
                                        break;
                                }
                                ?>
                                <span class="status-box <?php echo $statusClass; ?>"><?php echo $status; ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($appointment['notes']); ?></td>
                            <td>
                                <?php if ($appointment['status'] !== 'cancelled' && $appointment['status'] !== 'completed'): ?>
                                    <a href="appointment-update.php?id=<?php echo $appointment['id']; ?>" class="btn btn-sm btn-warning action-btn">Update</a>
                                    <a href="appointment-cancel.php?id=<?php echo $appointment['id']; ?>" class="btn btn-sm btn-danger action-btn" onclick="return confirm('Are you sure you want to cancel this appointment?');">Cancel</a>
                                <?php elseif ($appointment['status'] === 'cancelled'): ?>
                                    <span class="text-muted"><?php echo htmlspecialchars($appointment['cancel_reason']); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">No actions available</span>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <?php require_once FOOTER; ?>
</body>

</html>


<?php
    // Close the connection
    $conn = null;
?>
