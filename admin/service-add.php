<?php
require_once './includes/session.php';
require_once './includes/path.php'; 
require_once '../config/autoload.php'; 

// Connect to the database as admin
$conn = connect_as_admin();
if (!$conn) {
    die('Database connection failed');
}

// Initialize error and success messages
$success = '';
$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get posted data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);

    // Validate inputs
    if (empty($name)) {
        $error = "Service name is required.";
    } elseif ($price <= 0) {
        $error = "Price must be a positive number.";
    } else {
        // Prepare SQL to insert the new service
        try {
            $stmt = $conn->prepare("INSERT INTO services (name, description, price) VALUES (:name, :description, :price)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            
            // Execute the statement
            if ($stmt->execute()) {
                header('Location: service-add.php?status=success');
                exit();
            } else {
                header('Location: service-add.php?status=fail');
                exit();
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once HEADER; ?>
    <title>Add Service</title>
    <style>
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .form-container input,
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
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
                <div id="status-message" class="alert alert-success">Service added successfully.</div>
            <?php elseif ($url_status == "fail"): ?>
                <div id="status-message" class="alert alert-danger">Failed to add service.</div>
            <?php endif; ?>
            <div class="form-container">

                <!-- Service Add Form -->
                <form method="POST">
                    <div class="form-group">
                        <label for="name">Service Name</label>
                        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" required><?php echo htmlspecialchars($description ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" name="price" id="price" value="<?php echo htmlspecialchars($price ?? '100'); ?>" step="0.01" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Service</button>
                </form>
            </div>
        </main>
    </div>


</body>
</html>
