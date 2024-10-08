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

        .service-card {
            height: 100%; /* Ensure all cards are the same height */
            display: flex;
            flex-direction: column; /* Allow flexbox to manage content arrangement */
            transition: transform 0.3s; /* Animation for hover */
        }

        .service-card:hover {
            transform: translateY(-5px); /* Lift card on hover */
        }

        .card-body {
            flex: 1; /* Allow card body to take up remaining space */
            display: flex;
            flex-direction: column; /* Stack items vertically */
            justify-content: space-between; /* Distribute space evenly */
        }

        .card-title {
            font-weight: bold;
            margin-bottom: 10px; /* Add some margin for spacing */
        }

        .card-price {
            font-weight: bold;
            color: #007bff; /* Bootstrap primary color */
            margin-top: auto; /* Push price to the bottom of the card */
        }
    </style>

</head>

<!-- body.php -->
<body>
    <?php require_once TOP_NAVBAR; ?>

    <main class="container my-5">
        <h1 class="text-center mb-4">Available Services</h1>
        <div class="row">
            <?php
            // Connect to the database as a user
            $conn = connect_as_admin();
            if (!$conn) {
                die('Database connection failed');
            }

            // Query to get all services
            $sql = "SELECT * FROM services";
            $stmt = $conn->query($sql);

            if ($stmt->rowCount() > 0) {
                // Output data for each service
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="col-md-3 mb-4">';
                    echo '    <div class="card shadow service-card">';
                    echo '        <div class="card-body">';
                    echo '            <h5 class="card-title">' . htmlspecialchars($row["name"]) . '</h5>';
                    echo '            <p class="card-text">' . htmlspecialchars($row["description"]) . '</p>';
                    echo '            <h6 class="card-price">$' . number_format($row["price"], 2) . '</h6>';
                    echo '        </div>';
                    echo '    </div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="col-12">';
                echo '    <div class="alert alert-warning">No services available at the moment.</div>';
                echo '</div>';
            }
            ?>
        </div>
    </main>

    <?php require_once FOOTER; ?>
</body>

</html>
<?php
    // Close the connection
    $conn = null;
?>


    