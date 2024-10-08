<?php require_once './includes/session.php'; ?>
<?php require_once './includes/path.php'; ?>
<?php require_once '../config/autoload.php'; ?>

<?php
// Check if invoice ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Invalid invoice ID');
}

$invoice_id = (int) $_GET['id'];

// Connect to the database as admin
$conn = connect_as_admin();
if (!$conn) {
    die('Database connection failed');
}

try {
    // Query to get the invoice details along with appointment duration and doctor fee
    $sql = "SELECT i.id, i.total_amount, i.status, i.payment_method, i.issued_at, i.paid_at, 
                   a.id AS appointment_id, a.duration AS appointment_duration, d.fee_per_hour, 
                   p.username AS patient_name, d.username AS doctor_name, 
                   er.symptoms, er.diagnosis, er.advice, er.additional_service, er.additional_fee
            FROM invoices i
            JOIN exam_results er ON i.exam_result_id = er.id
            JOIN appointments a ON er.appointment_id = a.id
            JOIN patients p ON a.patient_id = p.id
            JOIN doctors d ON a.doctor_id = d.id
            WHERE i.id = :invoice_id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':invoice_id', $invoice_id, PDO::PARAM_INT);
    $stmt->execute();
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$invoice) {
        die("Invoice not found");
    }

    // Fetch all services related to this invoice
    $sql_services = "SELECT s.name, s.price, es.notes 
                     FROM exam_services es
                     JOIN services s ON es.service_id = s.id
                     WHERE es.exam_result_id = :exam_result_id";
    $stmt_services = $conn->prepare($sql_services);
    $stmt_services->bindParam(':exam_result_id', $invoice['appointment_id'], PDO::PARAM_INT);
    $stmt_services->execute();
    $services = $stmt_services->fetchAll();

    // Calculate consultation fee
    if ($invoice['appointment_duration']) {
        $duration = new DateTime($invoice['appointment_duration']);
        $duration_hours = (int)$duration->format('H') + ((int)$duration->format('i') / 60); // Convert duration to hours
        $consultation_fee = $duration_hours * (float)$invoice['fee_per_hour'];
    } else {
        $consultation_fee = 0; // Default to 0 if no duration
    }

} catch (PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once HEADER; ?>
    <link rel="stylesheet" href="../assets/css/table.css">
    <style>
        .table-container {
            margin: 0 auto;
            padding: 20px;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            font-weight: 600;
            letter-spacing: 0.05em;
            color: #333;
        }

        td {
            background-color: #ffffff;
            border-bottom: 1px solid #e0e0e0;
        }

        .table-hover tr:hover td {
            background-color: #e9f5ff;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f7f7f7;
        }

        .btn{
            margin-left: 10px;
        }

    </style>
</head>
<body>
    <?php require_once SIDE_NAVBAR; ?>

    <div class="main-content">
        <?php require_once TOP_NAVBAR; ?>

        <main class="content mt-5">
            <div class="table-container">
                <!-- Display invoice information -->
                <table class="table table-hover">
                    <tr>
                        <th>Invoice ID</th>
                        <td><?php echo htmlspecialchars($invoice['id']); ?></td>
                    </tr>
                    <tr>
                        <th>Patient</th>
                        <td><?php echo htmlspecialchars($invoice['patient_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Doctor</th>
                        <td><?php echo htmlspecialchars($invoice['doctor_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Total Amount</th>
                        <td>$<?php echo number_format($invoice['total_amount'], 2); ?></td>
                    </tr>
                    <tr>
                        <th>Consultation Fee</th>
                        <td>$<?php echo number_format($consultation_fee, 2); ?></td> <!-- Display calculated consultation fee -->
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?php echo htmlspecialchars($invoice['status']); ?></td>
                    </tr>
                    <tr>
                        <th>Payment Method</th>
                        <td><?php echo htmlspecialchars($invoice['payment_method']); ?></td>
                    </tr>
                    <tr>
                        <th>Issued At</th>
                        <td><?php echo htmlspecialchars($invoice['issued_at']); ?></td>
                    </tr>
                    <tr>
                        <th>Paid At</th>
                        <td><?php echo $invoice['paid_at'] ? htmlspecialchars($invoice['paid_at']) : 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>Symptoms</th>
                        <td><?php echo htmlspecialchars($invoice['symptoms']); ?></td>
                    </tr>
                    <tr>
                        <th>Diagnosis</th>
                        <td><?php echo htmlspecialchars($invoice['diagnosis']); ?></td>
                    </tr>
                    <tr>
                        <th>Advice</th>
                        <td><?php echo htmlspecialchars($invoice['advice']); ?></td>
                    </tr>
                    <tr>
                        <th>Additional Services</th>
                        <td><?php echo htmlspecialchars($invoice['additional_service']); ?></td>
                    </tr>
                    <tr>
                        <th>Additional Fee</th>
                        <td><?php echo $invoice['additional_fee'] ? '$' . number_format($invoice['additional_fee'], 2) : 'N/A'; ?></td>
                    </tr>
                </table>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Service Name</th>
                            <th>Service Price</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($services) > 0) {
                            foreach ($services as $service) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($service['name']) . "</td>";
                                echo "<td>$" . number_format($service['price'], 2) . "</td>";
                                echo "<td>" . htmlspecialchars($service['notes']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No services found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                
                <!-- Back Button -->
                <a href="invoices-view.php" class="btn btn-secondary">Go Back</a>

                <!-- Update Invoice Button (Only visible if status is 'waiting') -->
                <?php if ($invoice['status'] == 'waiting'): ?>
                    <a href="invoice-update.php?id=<?php echo $invoice['id']; ?>" class="btn btn-primary">Update</a>
                    <a href="invoice-confirm.php?id=<?php echo $invoice['id']; ?>" class="btn btn-success">Confirm</a>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
