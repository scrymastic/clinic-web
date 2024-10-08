<?php
// Include session check to ensure patient is logged in
require_once './includes/session.php';
require_once '../config/autoload.php'; // Include autoload file

$conn = connect_as_patient(); // Connection to the database

// Get the logged-in patient ID from session
$patient_id = $_SESSION['patient_id'];

// Query to get the patient's invoices where status is not 'waiting'
$sql = "SELECT i.id, i.total_amount, i.status, i.payment_method, i.issued_at, i.paid_at,
               a.appointment_time, d.username as doctor_name, s.name as specialty
        FROM invoices i
        JOIN exam_results er ON i.exam_result_id = er.id
        JOIN appointments a ON er.appointment_id = a.id
        JOIN doctors d ON a.doctor_id = d.id
        JOIN specialties s ON d.specialty_id = s.id
        WHERE a.patient_id = :patient_id
        AND i.status != 'waiting'
        ORDER BY i.issued_at DESC";
        
$stmt = $conn->prepare($sql);
$stmt->bindParam(':patient_id', $patient_id);
$stmt->execute();
$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once './includes/path.php'; ?>

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
    <?php require_once TOP_NAVBAR; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Your Invoices</h1>

        <?php if (empty($invoices)): ?>
            <p>You have no invoices available at this time.</p>
        <?php else: ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Issued At</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Payment Method</th>
                        <th>Doctor</th>
                        <th>Specialty</th>
                        <th>Appointment Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoices as $invoice): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($invoice['issued_at']))); ?></td>
                            <td><?php echo htmlspecialchars(number_format($invoice['total_amount'], 2)); ?></td>
                            <td>
                                <?php
                                $status = htmlspecialchars($invoice['status']);
                                $statusClass = '';
                            
                                switch (strtolower($invoice['status'])) {
                                    case 'pending':
                                        $statusClass = 'status-pending';
                                        break;
                                    case 'paid':
                                        $statusClass = 'status-paid';
                                        break;
                                    case 'overdue':
                                        $statusClass = 'status-overdue';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'status-cancelled';
                                        break;
                                }
                                ?>
                                <span class="status-box <?php echo $statusClass; ?>"><?php echo $status; ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($invoice['payment_method']); ?></td>
                            <td><?php echo htmlspecialchars($invoice['doctor_name']); ?></td>
                            <td><?php echo htmlspecialchars($invoice['specialty']); ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($invoice['appointment_time']))); ?></td>
                            <td>
                                <?php if ($invoice['status'] === 'pending'): ?>
                                    <a href="invoice-pay.php?id=<?php echo $invoice['id']; ?>" class="btn btn-sm btn-success action-btn">Pay</a>
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
