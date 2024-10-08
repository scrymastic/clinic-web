
<?php require_once './includes/session.php'; ?>
<?php require_once './includes/path.php'; ?>
<?php require_once '../config/autoload.php'; ?>
<?php
    // Connect to the database as a user
    $conn = connect_as_admin();
    if (!$conn) {
        die('Database connection failed');
    }
?>

<?php
        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect and sanitize input data
            $username = trim($_POST['username']);
            $password = custom_hash_password($_POST['password']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $specialty_id = (int)$_POST['specialty_id'];
            $qualification = trim($_POST['qualification']);
            $bio = trim($_POST['bio']);
            $fee_per_hour = (float)$_POST['fee_per_hour'];

            // Prepare and execute the SQL statement
            try {
                $sql = "INSERT INTO doctors (username, password, email, phone, specialty_id, qualification, bio, fee_per_hour)
                        VALUES (:username, :password, :email, :phone, :specialty_id, :qualification, :bio, :fee_per_hour)";
                $stmt = $conn->prepare($sql);

                // Bind parameters
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':specialty_id', $specialty_id);
                $stmt->bindParam(':qualification', $qualification);
                $stmt->bindParam(':bio', $bio);
                $stmt->bindParam(':fee_per_hour', $fee_per_hour);

                // Execute the statement
                if ($stmt->execute()) {
                    header("Location: doctor-view.php?status=success");
                    exit();
                } else {
                    header("Location: doctor-view.php?status=fail");
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
            <!-- Display success or failure message for delete operation -->
            <?php $url_status = isset($_GET['status']) ? $_GET['status'] : ''; ?>
            <?php if ($url_status == "success"): ?>
                <div id="status-message" class="alert alert-success">Doctor deleted successfully.</div>
            <?php elseif ($url_status == "fail"): ?>
                <div id="status-message" class="alert alert-danger">Failed to delete doctor.</div>
            <?php endif; ?>

            <form method="POST" class="shadow-effect p-5">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Enter username" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter email" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" class="form-control" placeholder="Enter phone number">
                    </div>
                </div>
                <div class="form-group">
                    <label for="specialty_id">Specialty</label>
                    <select id="specialty_id" name="specialty_id" class="form-control" required>
                        <?php
                        // Fetch specialties for the dropdown
                        $specialties = $conn->query("SELECT id, name FROM specialties")->fetchAll();
                        foreach ($specialties as $specialty) {
                            echo "<option value='{$specialty['id']}'>{$specialty['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="qualification">Qualification</label>
                        <input type="text" id="qualification" name="qualification" class="form-control" placeholder="Enter qualification" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="fee_per_hour">Fee per Hour</label>
                        <input type="number" step="0.01" id="fee_per_hour" name="fee_per_hour" class="form-control" placeholder="Enter fee per hour" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" class="form-control" rows="3" placeholder="Tell us about yourself"></textarea>
                </div>
                <button type="reset" class="btn btn-secondary">Clear Info</button>
                <button type="submit" class="btn btn-primary">Add Doctor</button>
            </form>
        </main>
    </div>

</body>
</html>

        
        