<?php require_once './includes/session.php'; ?>
<?php require_once './includes/path.php'; ?>
<?php require_once '../config/autoload.php'; ?>
<?php
    // Connect to the database as admin
    $conn = connect_as_admin();
    if (!$conn) {
        die('Database connection failed');
    }

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Collect and sanitize input data
        $name = trim($_POST['name']);
        $icon = trim($_POST['icon']);
        $description = trim($_POST['description']);

        // Prepare and execute the SQL statement
        try {
            $sql = "INSERT INTO specialties (name, icon, description)
                    VALUES (:name, :icon, :description)";
            $stmt = $conn->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':icon', $icon);
            $stmt->bindParam(':description', $description);

            // Execute the statement
            if ($stmt->execute()) {
                header("Location: specialty-view.php?status=success");
                exit();
            } else {
                header("Location: specialty-view.php?status=fail");
                exit();
            }
        } catch (PDOException $e) {
            // Catch any errors that occur during execution and display an error message
            echo "Error: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once HEADER; ?>
    <style>
        .form-control {
            border-radius: 0.25rem;
        }
        .form-group {
            margin-bottom: 1rem; /* Adjust margin for better spacing */
        }
        .btn {
            border-radius: 0.25rem;
            padding: 0.5rem 1.5rem; /* Adjusted button padding */
        }
        .shadow-effect {
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            margin: 15px;
        }
    </style>
</head>

<body>
    <?php require_once SIDE_NAVBAR; ?>

    <div class="main-content">
        <?php require_once TOP_NAVBAR; ?>

        <main class="content mt-5">
            <!-- Display success or failure message for add operation -->
            <?php $url_status = isset($_GET['status']) ? $_GET['status'] : ''; ?>
            <?php if ($url_status == "success"): ?>
                <div id="status-message" class="alert alert-success">Specialty added successfully.</div>
            <?php elseif ($url_status == "fail"): ?>
                <div id="status-message" class="alert alert-danger">Failed to add specialty.</div>
            <?php endif; ?>

            <form method="POST" class="shadow-effect p-5">
                <div class="form-group">
                    <label for="name">Specialty Name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter specialty name" required>
                </div>
                <div class="form-group">
                    <label for="icon">Icon (optional)</label>
                    <input type="text" id="icon" name="icon" class="form-control" placeholder="Enter icon class (default: fas fa-stethoscope)">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter a brief description"></textarea>
                </div>
                <button type="reset" class="btn btn-secondary">Clear Info</button>
                <button type="submit" class="btn btn-primary">Add Specialty</button>
            </form>
        </main>
    </div>

</body>
</html>
