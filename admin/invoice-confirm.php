
<?php require_once './includes/session.php'; ?>
<?php require_once './includes/path.php'; ?>
<?php require_once '../config/autoload.php'; ?>

<?php
// Check if the ID is provided
if (!isset($_GET['id'])) {
    die('ID not provided');
}

$invoice_id = $_GET['id'];

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the invoice by ID
    $stmt = $conn->prepare("SELECT status FROM invoices WHERE id = :id");
    $stmt->execute(['id' => $invoice_id]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$invoice) {
        die('Invoice not found');
    }

    // Check if the status is 'waiting'
    if ($invoice['status'] === 'waiting') {
        // Update the status to 'pending'
        $stmt = $conn->prepare("UPDATE invoices SET status = 'pending' WHERE id = :id");
        $stmt->execute(['id' => $invoice_id]);

        // Set a success message in the session
        $_SESSION['message'] = 'Invoice status updated to pending.';
    } else {
        // Set a message indicating no change was made
        $_SESSION['message'] = 'Invoice status is not waiting, no changes made.';
    }

    // Redirect back to the invoice view page
    header('Location: invoice-view.php?id=' . $invoice_id . '&status=success');
    exit();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>