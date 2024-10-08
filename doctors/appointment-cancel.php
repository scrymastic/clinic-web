<?php
require_once './includes/session.php';
require_once './includes/path.php';
require_once '../config/autoload.php';

// Check if appointment ID is passed
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Appointment ID is required');
}

$appointment_id = $_GET['id'];

// Connect to the database
$conn = connect_as_doctor();
if (!$conn) {
    die('Database connection failed');
}

// Get logged-in doctor ID
$doctor_id = $_SESSION['doctor_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission for cancellation
    $cancel_reason = $_POST['cancel_reason'];
    $cancelled_by = 'doctor'; // Since the doctor is canceling

    try {
        // Update the appointment status to 'cancelled'
        $sql = "UPDATE appointments 
                SET status = 'cancelled', cancelled_by = :cancelled_by, cancel_reason = :cancel_reason, updated_at = NOW()
                WHERE id = :appointment_id AND doctor_id = :doctor_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':cancelled_by', $cancelled_by);
        $stmt->bindParam(':cancel_reason', $cancel_reason);
        $stmt->bindParam(':appointment_id', $appointment_id);
        $stmt->bindParam(':doctor_id', $doctor_id);

        $stmt->execute();

        // Redirect to appointments page with success message
        $_SESSION['success'] = 'Appointment canceled successfully.';
        header('Location: appointments-view.php');
        exit();

    } catch (PDOException $e) {
        die("Error canceling appointment: " . $e->getMessage());
    }
}

// Fetch appointment details
try {
    $sql = "SELECT * FROM appointments WHERE id = :appointment_id AND doctor_id = :doctor_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':appointment_id', $appointment_id);
    $stmt->bindParam(':doctor_id', $doctor_id);
    $stmt->execute();
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$appointment) {
        die('Appointment not found or you are not authorized to cancel this appointment.');
    }

} catch (PDOException $e) {
    die("Error fetching appointment: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once HEADER; ?>
    <style>
        /* Additional styling for form */
        .form-container {
            margin: 20px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php require_once SIDE_NAVBAR; ?>

    <div class="main-content">
        <?php require_once TOP_NAVBAR; ?>

        <main class="container my-5">
            <div class="form-container">

                <!-- Display success or error messages -->
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success" id="status-message"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger" id="status-message">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Appointment cancellation form -->
                <form method="POST" action="" id="appointmentCancelForm">
                    <div class="form-group">
                        <label for="appointment_time">Appointment Time</label>
                        <input type="text" id="appointment_time" class="form-control" value="<?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($appointment['appointment_time']))); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="notes">Appointment Notes</label>
                        <textarea id="notes" class="form-control" rows="3" readonly><?php echo htmlspecialchars($appointment['notes']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="cancel_reason">Reason for Cancellation</label>
                        <textarea name="cancel_reason" id="cancel_reason" class="form-control" rows="3" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this appointment?');">Confirm Cancellation</button>
                    <a href="appointments-view.php" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </main>

    </div>
</body>
</html>
