<?php
session_start();
require_once '../config/autoload.php';
require_once './includes/path.php';

// Initialize variables for form data and errors
$username = $email = $phone = "";
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = connect_as_patient();
    if (!$conn) {
        die('Database connection failed');
    }

    // Sanitize inputs
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));

    // Validate inputs
    if (empty($username) || empty($password) || empty($confirm_password) || empty($email)) {
        $errors[] = "Please fill in all required fields.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // If no errors, insert into database
    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare SQL query
        $sql = "INSERT INTO patients (username, password, email, phone)
                VALUES (:username, :password, :email, :phone)";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Registration successful! You can now <a href='login.php'>login</a>.</div>";
        } else {
            $errors[] = "Failed to register. Please try again.";
        }
    }

    // Close connection
    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once HEADER; ?>
    <title>Patient Registration</title>
    <style>
        body {
            background-color: #f8f9fa; /* Light background color for better contrast */
        }
        .container {
            max-width: 600px; /* Set a maximum width for the form */
            margin-top: 50px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-row {
            display: flex;
            justify-content: space-between;
            gap: 10px; /* Space between fields */
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Register</h2>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error) {
                    echo $error . "<br>";
                } ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group flex-fill">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="form-group flex-fill">
                    <label for="phone">Phone (optional)</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($phone); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group flex-fill">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="form-group flex-fill">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>
        <div class="text-center mt-3">
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </div>
    </div>
</body>
</html>
