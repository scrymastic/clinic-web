<?php
require_once './includes/session.php';
require_once './includes/path.php'; 
require_once '../config/autoload.php'; 

// Connect to the database as admin
$conn = connect_as_admin();
if (!$conn) {
    die('Database connection failed');
}

// Fetch the service based on the passed ID in the URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id === 0) {
    die("Invalid service ID.");
}

// Initialize error and success messages
$success = '';
$error = '';

try {
    // Fetch the service details
    $stmt = $conn->prepare("SELECT * FROM services WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $service = $stmt->fetch();

    if (!$service) {
        die('Service not found.');
    }

    // If the form is submitted, process the update
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the posted data
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = floatval($_POST['price']);

        // Validate inputs
        if (empty($name)) {
            $error = "Service name is required.";
        } elseif ($price <= 0) {
            $error = "Price must be a positive number.";
        } else {
            // Update the service in the database
            $stmt = $conn->prepare("UPDATE services SET name = :name, description = :description, price = :price WHERE id = :id");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                $parsed_url = parse_url($url = $_SERVER['REQUEST_URI']);
                parse_str($parsed_url['query'] ?? '', $query_params);
                $query_params['status'] = 'success';
                header('Location: ' . $parsed_url['path'] . '?' . http_build_query($query_params));
                exit();
            } else {
                $parsed_url = parse_url($url = $_SERVER['REQUEST_URI']);
                parse_str($parsed_url['query'] ?? '', $query_params);
                $query_params['status'] = 'fail';
                header('Location: ' . $parsed_url['path'] . '?' . http_build_query($query_params));
            }
        }
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once HEADER; ?>
    <title>Edit Service</title>
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
                <div id="status-message" class="alert alert-success">Service updated successfully.</div>
            <?php elseif ($url_status == "fail"): ?>
                <div id="status-message" class="alert alert-danger">Failed to update service.</div>
            <?php endif; ?>
            <div class="form-container">
                <!-- Service Edit Form -->
                <form method="POST">
                    <div class="form-group">
                        <label for="name">Service Name</label>
                        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($service['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description"><?php echo htmlspecialchars($service['description']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" name="price" id="price" value="<?php echo htmlspecialchars($service['price']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Service</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='services-view.php'">Cancel</button>
                </form>
            </div>
        </main>
    </div>

</body>
</html>
