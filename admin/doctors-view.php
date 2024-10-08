<?php require_once './includes/session.php'; ?>
<?php require_once './includes/path.php'; ?>
<?php require_once '../config/autoload.php'; ?>

<?php
    // Default records per page
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5; // Default to 10 records per page if not set
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Connect to the database as admin
    $conn = connect_as_admin();
    if (!$conn) {
        die('Database connection failed');
    }

    try {
        // Query to get doctors with limit and offset
        $sql = "SELECT d.id, d.username, d.email, d.phone, s.name AS specialty, d.qualification, d.fee_per_hour, d.created_at
            FROM doctors d
            LEFT JOIN specialties s ON d.specialty_id = s.id
            WHERE CONCAT(d.username, d.email, d.phone, s.name) LIKE :search
            LIMIT :limit OFFSET :offset";
        
        $stmt = $conn->prepare($sql);
        $search_param = '%' . $search . '%';
        $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $doctors = $stmt->fetchAll();

        // Query to get total number of doctors for pagination
        $total_doctors = $conn->query("SELECT COUNT(*) FROM doctors")->fetchColumn();
        $total_pages = ceil($total_doctors / $limit);
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
                <!-- Search form to search for doctors -->
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

                <!-- Table to display doctor information -->
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Specialty</th>
                            <th>Qualification</th>
                            <th>Fee per Hour</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($doctors) > 0) {
                            // Output data for each doctor
                            foreach ($doctors as $doctor) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($doctor['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($doctor['username']) . "</td>";
                                echo "<td>" . htmlspecialchars($doctor['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($doctor['phone']) . "</td>";
                                echo "<td>" . htmlspecialchars($doctor['specialty']) . "</td>";
                                echo "<td>" . htmlspecialchars($doctor['qualification']) . "</td>";
                                echo "<td>$" . number_format($doctor['fee_per_hour'], 2) . "</td>";
                                echo "<td>" . htmlspecialchars($doctor['created_at']) . "</td>";
                                echo "<td><a href='doctor-edit.php?id=" . htmlspecialchars($doctor['id']) . "' class='btn btn-primary btn-sm'>Edit</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9'>No doctors found</td></tr>";
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
