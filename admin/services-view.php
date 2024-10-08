<?php
// Include session check to ensure patient is logged in
require_once './includes/session.php';
require_once './includes/path.php';
require_once '../config/autoload.php';

// Default records per page
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5; // Default to 5 records per page if not set
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Variable to store delete status (if it comes from the URL)
$url_status = isset($_GET['status']) ? $_GET['status'] : '';

// Connect to the database as admin
$conn = connect_as_admin();
if (!$conn) {
    die('Database connection failed');
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Delete service from database
    try {
        $sql_delete = "DELETE FROM services WHERE id = :id";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bindParam(':id', $delete_id, PDO::PARAM_INT);
        $stmt_delete->execute();

        // Redirect with success message
        header("Location: services-view.php?status=success");
        exit();
    } catch (PDOException $e) {
        // Redirect with failure message
        header("Location: services-view.php?status=fail");
        exit();
    }
}

// Get search term if available
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Adjust SQL query to include search parameter
try {
    $sql = "SELECT id, name, description, price, created_at 
            FROM services
            WHERE CONCAT(name, description) LIKE :search
            LIMIT :limit OFFSET :offset";
    
    $stmt = $conn->prepare($sql);
    $search_param = '%' . $search . '%'; // Wrap search term with wildcards
    $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $services = $stmt->fetchAll();

    // Get total records for pagination based on the search term
    $total_services_sql = "SELECT COUNT(*) FROM services WHERE CONCAT(name, description) LIKE :search";
    $stmt_total = $conn->prepare($total_services_sql);
    $stmt_total->bindParam(':search', $search_param, PDO::PARAM_STR);
    $stmt_total->execute();
    $total_services = $stmt_total->fetchColumn();

    $total_pages = ceil($total_services / $limit);
} catch (PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once HEADER; ?>
    <link rel="stylesheet" href="../assets/css/table.css">
</head>

<body>
    <?php require_once SIDE_NAVBAR; ?>
    
    <div class="main-content">
        <?php require_once TOP_NAVBAR; ?>
        
        <main class="content mt-5">
            <!-- Display success or failure message for delete operation -->
            <?php if ($url_status == "success"): ?>
                <div id="status-message" class="alert alert-success">Service deleted successfully.</div>
            <?php elseif ($url_status == "fail"): ?>
                <div id="status-message" class="alert alert-danger">Failed to delete service.</div>
            <?php endif; ?>

            <!-- Table Wrapper for shadow and rounded corners -->
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
                <!-- Search form to search for services -->
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

                
                <!-- Table to display services information -->
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Service Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($services) > 0) {
                            // Output data for each service
                            foreach ($services as $service) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($service['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($service['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($service['description']) . "</td>";
                                echo "<td>$" . number_format($service['price'], 2) . "</td>";
                                echo "<td>" . htmlspecialchars($service['created_at']) . "</td>";
                                echo "<td>
                                        <a href='service-edit.php?id=" . htmlspecialchars($service['id']) . "' class='btn btn-primary btn-sm'>Edit</a>
                                        <a href='services-view.php?delete_id=" . htmlspecialchars($service['id']) . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this service?');\">Delete</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No services found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <?php require_once PAGINATOR; ?>

            </div>

        </main>
    </div>

    <script>
        // Show success/fail message if present and auto-hide it after 3 seconds
        window.onload = function() {
            const statusMessage = document.getElementById('status-message');
            if (statusMessage) {
                statusMessage.style.display = 'block'; // Show message
                setTimeout(function() {
                    // Delete the message after 3 seconds
                    statusMessage.remove();

                    // Remove the status from the URL after 3 seconds
                    const url = new URL(window.location.href);
                    url.searchParams.delete('status'); // Remove the 'status' parameter
                    window.history.replaceState({}, document.title, url); // Update the URL without reloading the page
                }, 3000);
            }
        };
    </script>
</body>
</html>
