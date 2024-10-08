<?php require_once './includes/session.php'; ?>
<?php require_once './includes/path.php'; ?>
<?php require_once '../config/autoload.php'; ?>

<?php
// Connect to the database as a user
$conn = connect_as_doctor();
if (!$conn) {
    die('Database connection failed');
}

// Fetch doctor's information
$doctor_id = $_SESSION['doctor_id']; // Assuming doctor ID is stored in session
$sql = "SELECT username, email, phone, specialty_id, qualification, fee_per_hour, bio 
        FROM doctors 
        WHERE id = :doctor_id";
$stmt = $conn->prepare($sql);
$stmt->execute(['doctor_id' => $doctor_id]);
$doctor_info = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $new_password = $_POST['new_password'] ?? null; // Optional new password

    // Verify current password
    $sql = "SELECT password FROM doctors WHERE id = :doctor_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['doctor_id' => $doctor_id]);
    $hashed_password = $stmt->fetchColumn();

    if (custom_verify_password($current_password, $hashed_password)) {
        // Update email and phone
        $sql = "UPDATE doctors SET email = :email, phone = :phone WHERE id = :doctor_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'email' => $email,
            'phone' => $phone,
            'doctor_id' => $doctor_id
        ]);

        // Update password if provided
        if (!empty($new_password)) {
            $new_hashed_password = custom_hash_password($new_password);
            $sql = "UPDATE doctors SET password = :password WHERE id = :doctor_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'password' => $new_hashed_password,
                'doctor_id' => $doctor_id
            ]);
        }

        header("Location: doctor-view.php?status=success");
        exit();

    } else {
        header("Location: doctor-view.php?status=fail");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once HEADER; ?>
    <style>
        .shadow-effect {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <?php require_once SIDE_NAVBAR; ?>

    <div class="main-content">
        <?php require_once TOP_NAVBAR; ?>
        <main class="content mt-5">
            <!-- Success or failure message from URL parameter -->
            <?php $url_status = isset($_GET['status']) ? $_GET['status'] : ''; ?>
            <?php if ($url_status == "success"): ?>
                <div id="status-message" class="alert alert-success">Profile updated successfully.</div>
            <?php elseif ($url_status == "fail"): ?>
                <div id="status-message" class="alert alert-danger">Failed to update profile.</div>
            <?php endif; ?>
            <form method="POST" class="shadow-effect p-5">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" 
                               value="<?php echo htmlspecialchars($doctor_info['username']); ?>" disabled>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" 
                               placeholder="Enter current password" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?php echo htmlspecialchars($doctor_info['email']); ?>" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" class="form-control" 
                               value="<?php echo htmlspecialchars($doctor_info['phone']); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" 
                               placeholder="Enter new password (leave blank to keep current)">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="qualification">Qualification</label>
                        <input type="text" id="qualification" name="qualification" class="form-control" 
                               value="<?php echo htmlspecialchars($doctor_info['qualification']); ?>" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="fee_per_hour">Fee per Hour</label>
                        <input type="number" step="0.01" id="fee_per_hour" name="fee_per_hour" class="form-control" 
                               value="<?php echo htmlspecialchars($doctor_info['fee_per_hour']); ?>" disabled>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="bio">Bio</label>
                        <textarea id="bio" name="bio" class="form-control" rows="3" disabled><?php echo htmlspecialchars($doctor_info['bio']); ?></textarea>
                    </div>
                </div>
                <button type="reset" class="btn btn-secondary">Clear Info</button>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </main>
    </div>

</body>
</html>
