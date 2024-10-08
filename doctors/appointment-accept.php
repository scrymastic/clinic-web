<?php
    require_once './includes/session.php'; 
    require_once './includes/path.php'; 
    require_once '../config/autoload.php'; 

    // Check if the ID is set in the query string
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        die("Appointment ID is missing");
    }

    // Sanitize the appointment ID
    $appointment_id = (int) $_GET['id'];

    // Connect to the database as a doctor
    $conn = connect_as_doctor();
    if (!$conn) {
        die('Database connection failed');
    }

    try {
        // Check if the appointment exists and is in 'pending' status
        $sql = "SELECT status FROM appointments WHERE id = :appointment_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);
        $stmt->execute();
        $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$appointment) {
            // If appointment doesn't exist, show an error message
            die("Appointment not found");
        }

        // Check if the appointment is in pending status
        if ($appointment['status'] !== 'pending') {
            die("This appointment is not in pending status");
        }

        // Update the appointment status to 'scheduled'
        $update_sql = "UPDATE appointments SET status = 'scheduled' WHERE id = :appointment_id";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);
        $update_stmt->execute();

        // Redirect back to the appointments list with a success message
        header("Location: appointments-view.php?status=success");
        exit();

    } catch (PDOException $e) {
        die("Error accepting appointment: " . $e->getMessage());
    }

    // Close the connection
    $conn = null;
?>
