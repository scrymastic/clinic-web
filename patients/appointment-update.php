<?php
// Include session check to ensure patient is logged in
require_once './includes/session.php';
require_once '../config/autoload.php'; // Include autoload file

$conn = connect_as_patient(); // Connection to the database

// Check if appointment ID is provided in the URL
if (!isset($_GET['id'])) {
    die("Appointment ID not specified.");
}

// Get the appointment ID from the URL
$appointment_id = $_GET['id'];

// Get the logged-in patient ID from session
$patient_id = $_SESSION['patient_id'];

// Check if form is submitted for updating
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_time = $_POST['appointment_time'];
    $notes = $_POST['notes'];

    // Convert appointment_time to a DateTime object for comparison
    $appointmentDateTime = new DateTime($appointment_time);
    $currentDateTime = new DateTime(); // Current time

    // Set the minimum appointment time to be one hour from now
    $minAppointmentDateTime = (clone $currentDateTime)->modify('+1 hour');

    // Check if the appointment time is at least 1 hour in the future
    if ($appointmentDateTime < $minAppointmentDateTime) {
        // Redirect with an error status if the appointment time is invalid
        header("Location: appointment-update.php?id=$appointment_id&status=fail");
        exit();
    }

    // Update the appointment and set status to 'pending'
    $sql_update = "UPDATE appointments 
                   SET appointment_time = :appointment_time, notes = :notes, status = 'pending' 
                   WHERE id = :appointment_id AND patient_id = :patient_id";

    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':appointment_time', $appointment_time);
    $stmt_update->bindParam(':notes', $notes);
    $stmt_update->bindParam(':appointment_id', $appointment_id);
    $stmt_update->bindParam(':patient_id', $patient_id);

    // Check if the update was successful
    if ($stmt_update->execute()) {
        header("Location: appointment-update.php?id=$appointment_id&status=success"); // Redirect with success status
        exit();
    } else {
        header("Location: appointment-update.php?id=$appointment_id&status=fail"); // Redirect with fail status
        exit();
    }
}


// Fetch the current appointment details to display in the form
$sql = "SELECT id, appointment_time, notes FROM appointments 
        WHERE id = :appointment_id AND patient_id = :patient_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':appointment_id', $appointment_id);
$stmt->bindParam(':patient_id', $patient_id);
$stmt->execute();
$appointment = $stmt->fetch(PDO::FETCH_ASSOC);

// If appointment not found, show error
if (!$appointment) {
    die("Appointment not found.");
}

?>

<?php require_once './includes/path.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once HEADER; ?>
    <style>
        .update-form {
            max-width: 600px;
            margin: 50px auto;
        }
    </style>
</head>

<body>
    <?php require_once TOP_NAVBAR; ?>

    <div class="container update-form">
        <h2 class="text-center mb-4">Update Appointment</h2>

        <!-- Success or failure message from URL parameter -->
        <?php $url_status = isset($_GET['status']) ? $_GET['status'] : ''; ?>
        <?php if ($url_status == "success"): ?>
            <div id="status-message" class="alert alert-success">Appointment updated successfully!</div>
        <?php elseif ($url_status == "fail"): ?>
            <div id="status-message" class="alert alert-danger">Failed to update appointment. Please try again.</div>
        <?php endif; ?>

        <!-- Appointment update form -->
        <form method="post">
            <div class="form-group">
                <label for="appointment_time">Appointment Time</label>
                <input type="datetime-local" class="form-control" id="appointment_time" name="appointment_time"
                       value="<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($appointment['appointment_time']))); ?>" required>
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3"><?php echo htmlspecialchars($appointment['notes']); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Appointment</button>
            <a href="appointments-view.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>


    <?php require_once FOOTER; ?>


</body>

</html>

<?php
// Close the connection
$conn = null;
?>
