<?php
session_start();
require_once './includes/path.php';
require_once '../config/autoload.php';

// Connect to the database
$conn = connect_as_patient();
if (!$conn) {
    die('Database connection failed');
}

// Fetch the patient's information from the database
$patient_id = $_SESSION['patient_id'];
$sql = 'SELECT * FROM patients WHERE id = :id';
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $patient_id]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$patient) {
    die('Patient not found');
}

// Handle form submission to update the patient’s information
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? $patient['email'];
    $phone = $_POST['phone'] ?? $patient['phone'];
    $date_of_birth = $_POST['date_of_birth'] ?? $patient['date_of_birth'];
    $gender = $_POST['gender'] ?? $patient['gender'];
    $medical_history = $_POST['medical_history'] ?? $patient['medical_history'];

    // Validate fields
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address';
    }

    // Update the database if no errors
    if (empty($errors)) {
        $sql = 'UPDATE patients SET email = :email, phone = :phone, date_of_birth = :date_of_birth, 
                gender = :gender, medical_history = :medical_history WHERE id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'email' => $email,
            'phone' => $phone,
            'date_of_birth' => $date_of_birth,
            'gender' => $gender,
            'medical_history' => $medical_history,
            'id' => $patient_id
        ]);
        $success = 'Information updated successfully!';
        // Refresh the patient data
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $patient_id]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once HEADER; ?>
</head>
<body>
    <?php require_once TOP_NAVBAR; ?>
    <main class="container my-5">
        <h1 class="text-center mb-4">Your Information</h1>
        <div class="container-fluid my-5">
            <!-- Show success or error messages -->
            <?php if (!empty($success)) : ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if (!empty($errors)) : ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error) : ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Patient information form -->
            <form method="POST" action="patients-view.php">
                <!-- Username -->
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($patient['username']); ?>" readonly>
                </div>

                <!-- Email and Phone in the same row -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email" value="<?php echo htmlspecialchars($patient['email']); ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" class="form-control" id="phone" value="<?php echo htmlspecialchars($patient['phone']); ?>">
                    </div>
                </div>

                <!-- Date of Birth and Gender in the same row -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control" id="date_of_birth" value="<?php echo htmlspecialchars($patient['date_of_birth']); ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="gender">Gender</label>
                        <select name="gender" class="form-control" id="gender">
                            <option value="male" <?php echo ($patient['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo ($patient['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
                            <option value="other" <?php echo ($patient['gender'] === 'other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>

                <!-- Medical History -->
                <div class="form-group">
                    <label for="medical_history">Medical History</label>
                    <textarea name="medical_history" class="form-control" id="medical_history"><?php echo htmlspecialchars($patient['medical_history']); ?></textarea>
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-primary">Update Information</button>
            </form>
        </div>
    </main>
    <?php require_once FOOTER; ?>
</body>
</html>
