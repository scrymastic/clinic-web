<?php session_start(); ?>
<?php require_once './includes/path.php'; ?>
<?php require_once '../config/autoload.php'; ?>
<?php
    // Connect to the database as a user
    $conn = connect_as_patient();
    if (!$conn) {
        die('Database connection failed');
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once HEADER; ?>

    <style>
        .icon-circle {
            width: 80px; /* Adjust the size as needed */
            height: 80px; /* Adjust the size as needed */
            border-radius: 50%;
            background-color: #f8f9fa; /* Light background color */
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* Add smooth transition */
        }
        .card:hover {
            transform: scale(1.05); /* Slightly enlarge the card on hover */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); /* Add shadow effect on hover */
        }
    </style>

</head>

<!-- body.php -->
<body>
<?php require_once TOP_NAVBAR; ?>

<main class="container-fluid my-5">
    <!-- Hero Section / Welcome Banner -->
    <section id="welcome-section" class="text-center py-5 bg-light rounded">
        <h1>Welcome to CLINXME</h1>
        <p class="lead">Your trusted partner in healthcare. We provide expert medical care with a focus on patient satisfaction and quality service.</p>
    </section>

    <!-- List of Specialties Section -->
    <section id="specialties-section" class="py-5">
        <?php
        // Fetch the list of specialties with their icons
        $sql = 'SELECT name, icon FROM specialties';
        $stmt = $conn->query($sql);
        $specialties = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <h2 class="text-center">Our Specialties</h2>
        <p class="text-center">At CLINXME, we offer a wide range of specialties to cater to all your healthcare needs:</p>

        <!-- Display the specialties as cards -->
        <div class="row text-center">
            <div class="container">
                <div class="row">
                    <?php
                    foreach ($specialties as $specialty) {
                        echo '<div class="col-lg-3 col-md-4 col-sm-6 mb-4">'; // Create a column for each specialty
                        echo '  <div class="card h-100 shadow-sm">'; // Add a card with shadow effect
                        echo '    <div class="card-body d-flex flex-column align-items-center">'; // Center the content

                        // Display the icon wrapped in a circle
                        echo '      <div class="icon-circle mb-3 d-flex align-items-center justify-content-center">';
                        echo '          <i class="' . htmlspecialchars($specialty['icon']) . ' fa-3x"></i>'; // Icon with size
                        echo '      </div>';
                        // Display the name beneath the icon
                        echo '      <h5 class="card-title">' . htmlspecialchars($specialty['name']) . '</h5>';

                        echo '    </div>'; // Close card-body
                        echo '  </div>'; // Close card
                        echo '</div>'; // Close column
                    }
                    ?>
                </div>
            </div>
    </section>

    <!-- Why Choose Us Section -->
    <section id="why-choose-us-section" class="py-5 bg-light rounded">
        <h2 class="text-center mb-4">Why Choose CLINXME?</h2>
        <div class="row text-center">
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-user-md mr-2"></i>Experienced Doctors</h5>
                        <p class="card-text">Highly qualified professionals.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-hospital mr-2"></i>State-of-the-art Facilities</h5>
                        <p class="card-text">Advanced technology.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-heartbeat mr-2"></i>Personalized Care</h5>
                        <p class="card-text">We prioritize individual attention.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-calendar-check mr-2"></i>Convenient Scheduling</h5>
                        <p class="card-text">Easy appointment booking.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Commitment Section -->
    <section id="service-commit-section" class="py-5">
        <h2 class="text-center mb-4">Our Service Commitment</h2>
        <p class="text-center">At CLINXME, we are committed to providing high-quality healthcare services. Our promises:</p>
        <div class="row text-center">
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-stethoscope mr-2"></i>Comprehensive Care</h5>
                        <p class="card-text">From diagnosis to service.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-hands-helping mr-2"></i>Compassionate Support</h5>
                        <p class="card-text">Our staff is always ready to assist.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-dollar-sign mr-2"></i>Affordable Services</h5>
                        <p class="card-text">Competitive pricing without compromising quality.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-user-shield mr-2"></i>Patient Privacy</h5>
                        <p class="card-text">Confidentiality and security.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Information or CTA Section -->
    <section id="contact-section" class="py-5 text-center bg-light rounded">
        <h2>Get in Touch</h2>
        <p>Have questions or need to schedule an appointment? Contact us at:</p>
        <?php // Query to get the contact information
        $sql = 'SELECT * FROM clinic_info';
        $stmt = $conn->query($sql);
        $clinic_info = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <ul class="list-unstyled">
            <li><i class="fas fa-phone-alt mr-2"></i><?php echo htmlspecialchars($clinic_info['phone']); ?></li>
            <li><i class="fas fa-envelope mr-2"></i><a href="mailto:<?php echo htmlspecialchars($clinic_info['email']); ?>"><?php echo htmlspecialchars($clinic_info['email']); ?></a></li>
            <li><i class="fas fa-map-marker-alt mr-2"></i><?php echo htmlspecialchars($clinic_info['address']); ?></li>
        </ul>
        <p><a href="appointment-book.php" class="btn btn-primary">Book an Appointment</a></p>
    </section>
</main>

<?php require_once FOOTER; ?>
</body>

</html>


<?php
    // Close the connection
    $conn = null;
?>

