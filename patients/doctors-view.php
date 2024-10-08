<?php session_start(); ?>
<?php require_once './includes/path.php'; ?>
<?php require_once '../config/autoload.php'; ?>

<?php
// Connect to the database
$conn = connect_as_patient();
if (!$conn) {
    die('Database connection failed');
}

// Fetch doctors
$sql = "SELECT d.id, d.username, d.email, d.phone, d.qualification, d.bio, d.fee_per_hour, s.name AS specialty 
        FROM doctors d 
        LEFT JOIN specialties s ON d.specialty_id = s.id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once HEADER; ?>
    <style>
        /* Custom styling for the doctors' cards */
        .doctor-card {
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease;
            text-align: left; /* Align all text to the left by default */
        }
        .doctor-card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .doctor-img {
            height: 150px;
            width: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
        }
        .card-body {
            text-align: left; /* Align all text to the left by default */
        }
        .doctor-info h5, .doctor-info h6 {
            text-align: center; /* Align only name and specialty to the center */
            font-weight: bold;
        }
        .doctor-info p {
            margin-bottom: 5px;
            color: #6c757d;
        }
        .fee-info {
            font-weight: bold;
            margin-top: 15px;
            text-align: center;
            color: #007bff !important;
        }
    </style>

</head>

<!-- body.php -->
<body>
    <?php require_once TOP_NAVBAR; ?>

    <main class="container my-5">
        <h1 class="text-center mb-4">Meet Our Doctors</h1>
        <div class="row">
            <?php foreach ($doctors as $doctor): ?>
                <div class="col-md-4 mb-4">
                    <div class="card doctor-card shadow-sm">
                        <div class="card-body">
                            <!-- Placeholder for doctor image -->
                            <!-- <div class="text-center">
                                <img src="path/to/default-profile.png" alt="Doctor" class="doctor-img">
                            </div> -->
                            <div class="doctor-info">
                                <h5><?php echo htmlspecialchars($doctor['username']); ?></h5>
                                <h6 class="text-muted"><?php echo htmlspecialchars($doctor['specialty']); ?></h6>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($doctor['email']); ?></p>
                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($doctor['phone']); ?></p>
                                <p><strong>Qualification:</strong> <?php echo htmlspecialchars($doctor['qualification']); ?></p>
                                <p><strong>Bio:</strong> <?php echo htmlspecialchars($doctor['bio']); ?></p>
                                <p class="fee-info">$<?php echo number_format($doctor['fee_per_hour'], 2); ?> per hour</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <?php require_once FOOTER; ?>
</body>

</html>

<?php
    // Close the connection
    $conn = null;
?>

