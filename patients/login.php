<?php
session_start(); // Start the session
require_once '../config/autoload.php';
require_once './includes/path.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = connect_as_patient();
    if (!$conn) {
        die('Database connection failed');
    }

    // Sanitize inputs
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Check if username and password are filled
    if (!empty($username) && !empty($password)) {
        // Prepare a SQL query
        $sql = "SELECT id, username, password FROM patients WHERE username = :username LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch the user
        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the password
            if (custom_verify_password($password, $user['password'])) {
                // Store session data
                $_SESSION['patient_id'] = $user['id'];
                $_SESSION['patient_username'] = $user['username'];
                header('Location: index.php');
                exit();
            } else {
                $error = "Incorrect username or password!";
            }
        } else {
            $error = "No account found with that username!";
        }
    } else {
        $error = "Please fill in all fields!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once HEADER; ?>
    <style>
        body {
            background-color: #f8f9fa; /* Light background color for better contrast */
        }

        .container {
            max-width: 400px; /* Set a maximum width for the form */
            margin-top: 50px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 6px;
            height: 45px;
            font-size: 16px;
        }

        .btn-primary {
            width: 100%;
            border-radius: 6px;
            font-size: 18px;
            padding: 10px;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
</html>

<?php
// Close the connection
$conn = null;
?>
