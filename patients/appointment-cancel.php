<?php
// Include session check to ensure patient is logged in
require_once './includes/session.php';
require_once '../config/autoload.php'; // Include autoload file

$conn = connect_as_patient(); // Connection to the database

// Check if appointment ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: appointments-view.php"); // Redirect if appointment ID not specified
    exit;
}

// Get the appointment ID from the URL
$appointment_id = $_GET['id'];

// Get the logged-in patient ID from session
$patient_id = $_SESSION['patient_id'];

// Check if the appointment belongs to the patient and is not already cancelled or completed
$sql_check = "SELECT status FROM appointments 
              WHERE id = :appointment_id AND patient_id = :patient_id";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bindParam(':appointment_id', $appointment_id);
$stmt_check->bindParam(':patient_id', $patient_id);
$stmt_check->execute();
$appointment = $stmt_check->fetch(PDO::FETCH_ASSOC);

if (!$appointment) {
    header("Location: appointments-view.php"); // Redirect if appointment not found
    exit;
} elseif ($appointment['status'] === 'cancelled' || $appointment['status'] === 'completed') {
    header("Location: appointments-view.php"); // Redirect if appointment can't be canceled
    exit;
}

// If valid, update the appointment status to 'cancelled'
// Adding 'cancelled_by' and 'cancel_reason'
$sql_update = "UPDATE appointments 
               SET status = 'cancelled', 
                   cancelled_by = 'patient', 
                   cancel_reason = 'cancelled by patient' 
               WHERE id = :appointment_id AND patient_id = :patient_id";

$stmt_update = $conn->prepare($sql_update);
$stmt_update->bindParam(':appointment_id', $appointment_id);
$stmt_update->bindParam(':patient_id', $patient_id);

// Execute the update and check if it was successful
if ($stmt_update->execute()) {
    header("Location: appointments-view.php"); // Redirect with success status
} else {
    header("Location: appointments-view.php"); // Redirect with fail status
}

// Close the connection
$conn = null;
exit;
?>
