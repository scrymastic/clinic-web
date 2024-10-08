<?php require_once './includes/session.php'; ?>
<?php require_once './includes/path.php'; ?>
<?php require_once '../config/autoload.php'; ?>

<?php
    // Default records per page
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5; // Default to 5 records per page if not set
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Connect to the database as admin
    $conn = connect_as_admin();
    if (!$conn) {
        die('Database connection failed');
    }

    try {
        // Query to get invoices with limit and offset
        $sql = "SELECT i.id, i.total_amount, i.status, i.payment_method, i.issued_at, i.paid_at, 
                       a.id AS appointment_id, p.username AS patient_name, d.username AS doctor_name
                FROM invoices i
                JOIN exam_results er ON i.exam_result_id = er.id
                JOIN appointments a ON er.appointment_id = a.id
                JOIN patients p ON a.patient_id = p.id
                JOIN doctors d ON a.doctor_id = d.id
                WHERE CONCAT(p.username, d.username, i.status, i.payment_method) LIKE :search
                LIMIT :limit OFFSET :offset";

        $stmt = $conn->prepare($sql);
        $search_param = '%' . $search . '%';
        $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $invoices = $stmt->fetchAll();

        // Query to get the total number of invoices for pagination
        $total_invoices = $conn->query("SELECT COUNT(*) FROM invoices WHERE status != 'waiting'")->fetchColumn();
        $total_pages = ceil($total_invoices / $limit);
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
</head>

<body>
    <?php require_once SIDE_NAVBAR; ?>
    
    <div class="main-content">
        <?php require_once TOP_NAVBAR; ?>
        
        <main class="content mt-5">

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
                <!-- Search form to search for invoices -->
                <form method="GET" class="search-form d-inline-block ml-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
                        <input type="hidden" name="limit" value="<?php echo $limit; ?>"> <!-- Preserve limit -->
                        <input type="hidden" name="page" value="1"> <!-- Reset page to 1 when searching -->
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-secondary btn-sm">
                                <i class="fas fa-search"></i> <!-- Font Awesome magnifying glass icon -->
                            </button>
                        </div>
                    </div>
                </form>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient Name</th>
                            <th>Doctor Name</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                            <th>Issued At</th>
                            <th>Paid At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Assuming $invoices is an array of invoice data
                        foreach ($invoices as $invoice) {
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
                                case 'waiting':
                                    $statusClass = 'status-waiting';
                                    break;
                            }
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($invoice['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($invoice['patient_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($invoice['doctor_name']) . "</td>";
                            echo "<td>$" . number_format($invoice['total_amount'], 2) . "</td>";
                            echo "<td><span class='status-box $statusClass'>$status</span></td>";
                            echo "<td>" . htmlspecialchars($invoice['payment_method']) . "</td>";
                            echo "<td>" . htmlspecialchars($invoice['issued_at']) . "</td>";
                            echo "<td>" . ($invoice['paid_at'] ? htmlspecialchars($invoice['paid_at']) : 'N/A') . "</td>";
                            echo "<td><a href='invoice-view.php?id=" . htmlspecialchars($invoice['id']) . "' class='btn btn-primary btn-sm'>View</a></td>";
                            echo "</tr>";
                        }
                        ?>
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
