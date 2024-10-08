<?php require_once './includes/session.php'; ?>
<?php require_once './includes/path.php'; ?>
<?php require_once '../config/autoload.php'; ?>

<?php
    // Default records per page
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5; // Default to 5 records per page if not set
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Connect to the database as a doctor
    $conn = connect_as_doctor();
    if (!$conn) {
        die('Database connection failed');
    }

    // Get the logged-in doctor's ID from session
    $doctor_id = $_SESSION['doctor_id'];

    try {
        // Query to get the doctor's appointments
        $sql = "SELECT a.id, a.appointment_time, a.duration, a.status, a.notes, 
                       p.username as patient_name
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                WHERE a.doctor_id = :doctor_id
                ORDER BY a.appointment_time DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':doctor_id', $doctor_id);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $total_appointments = $conn->query("SELECT COUNT(*) FROM appointments WHERE doctor_id = $doctor_id")->fetchColumn();
        $total_pages = ceil($total_appointments / $limit);

    } catch (PDOException $e) {
        die("Error fetching data: " . $e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once HEADER; ?>
    <link rel="stylesheet" href="../assets/css/table.css">
    <link rel="stylesheet" href="../assets/css/status.css">

    <style>
        .action-btn {
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <?php require_once SIDE_NAVBAR; ?>
    
    <div class="main-content">
        <?php require_once TOP_NAVBAR; ?>
        
        <main class="content mt-5">
            <!-- Display success or failure message for delete operation -->
            <?php $url_status = isset($_GET['status']) ? $_GET['status'] : ''; ?>
            <?php if ($url_status == "success"): ?>
                <div id="status-message" class="alert alert-success">Service updated successfully.</div>
            <?php elseif ($url_status == "fail"): ?>
                <div id="status-message" class="alert alert-danger">Failed to update service.</div>
            <?php endif; ?>
            <div class="table-container">
                
                <!-- Dropdown for selecting number of records per page -->
                <form method="GET" class="records-dropdown">
                    <label for="limit">Showing</label>
                    <select name="limit" id="limit" onchange="this.form.submit()">
                        <option value="5" <?php if ($limit == 5) echo 'selected'; ?>>5</option>
                        <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
                        <option value="25" <?php if ($limit == 25) echo 'selected'; ?>>25</option>
                        <option value="50" <?php if ($limit == 50) echo 'selected'; ?>>50</option>
                    </select>
                    <input type="hidden" name="page" value="<?php echo $page; ?>"> <!-- Preserve current page -->
                    <label for="limit">entries</label>
                </form>
                
                <!-- Table to display doctor appointments -->
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Appointment Time</th>
                            <th>Patient</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($appointments) > 0): ?>
                            <?php foreach ($appointments as $appointment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($appointment['appointment_time']))); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
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
                                        <?php if ($appointment['status'] === 'pending'): ?>
                                            <!-- Accept Appointment -->
                                            <a href="appointment-accept.php?id=<?php echo $appointment['id']; ?>" class="btn btn-sm btn-primary action-btn">Accept</a>
                                        <?php endif; ?>

                                        <?php if ($appointment['status'] === 'scheduled' && date('Y-m-d H:i:s') > $appointment['appointment_time']): ?>
                                            <!-- Write Examination Result -->
                                            <a href="exam-result-add.php?id=<?php echo $appointment['id']; ?>" class="btn btn-sm btn-info action-btn">Write Exam Result</a>
                                        <?php endif; ?>
                                        <?php if ($appointment['status'] !== 'cancelled' && $appointment['status'] !== 'completed'): ?>
                                            <!-- Cancel Appointment -->
                                            <a href="appointment-cancel.php?id=<?php echo $appointment['id']; ?>" class="btn btn-sm btn-danger action-btn">Cancel</a>
                                        <?php else: ?>
                                            <span class="text-muted">No actions available</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6">No appointments found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <?php require_once PAGINATOR; ?>
                
            </div>

        </main>
    </div>

</body>
</html>

<?php
    // Close the connection
    $conn = null;
?>
