<?php require_once './includes/session.php'; ?>
<?php require_once './includes/path.php'; ?>
<?php require_once '../config/autoload.php'; ?>

<?php
    // Default records per page (limit)
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Connect to the database as admin
    $conn = connect_as_admin();
    if (!$conn) {
        die('Database connection failed');
    }

    try {
        // Query to search specialties with limit and offset
        $sql = "SELECT id, name, icon, description, created_at
                FROM specialties
                WHERE CONCAT(name, description) LIKE :search
                LIMIT :limit OFFSET :offset";
        
        $stmt = $conn->prepare($sql);
        $search_param = '%' . $search . '%';
        $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $specialties = $stmt->fetchAll();

        // Query to get total number of records for pagination
        $total_specialties = $conn->query("SELECT COUNT(*) FROM specialties")->fetchColumn();
        $total_pages = ceil($total_specialties / $limit);
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

            <!-- Success or failure message from URL parameter -->
            <?php $url_status = isset($_GET['status']) ? $_GET['status'] : ''; ?>
            <?php if ($url_status == "success"): ?>
                <div id="status-message" class="alert alert-success">Appointment deleted successfully!</div>
            <?php elseif ($url_status == "fail"): ?>
                <div id="status-message" class="alert alert-danger">Failed to delete appointment.</div>
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

                <!-- Search Form -->
                <form method="GET" class="search-form d-inline-block ml-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
                        <input type="hidden" name="limit" value="<?php echo $limit; ?>">
                        <input type="hidden" name="page" value="1">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-secondary btn-sm">
                                <i class="fas fa-search"></i> <!-- Font Awesome search icon -->
                            </button>
                        </div>
                    </div>
                </form>
                
                <!-- Table to display specialties information -->
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Specialty Name</th>
                            <th>Description</th>
                            <th>Icon</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($specialties) > 0) {
                            // Output data for each specialty
                            foreach ($specialties as $specialty) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($specialty['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($specialty['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($specialty['description']) . "</td>";
                                echo "<td><i class='" . htmlspecialchars($specialty['icon']) . "'></i></td>";
                                echo "<td>" . htmlspecialchars($specialty['created_at']) . "</td>";
                                echo "<td>
                                        <a href='specialty-edit.php?id=" . htmlspecialchars($specialty['id']) . "' class='btn btn-primary btn-sm'>Edit</a>
                                        <button class='btn btn-danger btn-sm' onclick='confirmDelete(" . htmlspecialchars($specialty['id']) . ")'>Delete</button>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No specialties found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <?php require_once PAGINATOR; ?>
            </div>

        </main>
    </div>
    <script>
        function confirmDelete(id) {
            const confirmation = confirm("Are you sure you want to delete this specialty?");
            if (confirmation) {
                window.location.href = "specialties-view.php?delete_id=" + id + "&page=<?php echo $page; ?>&limit=<?php echo $limit; ?>";
            }
        }
    </script>

</body>
</html>



