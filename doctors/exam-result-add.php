<?php require_once './includes/session.php'; ?>
<?php require_once './includes/path.php'; ?>
<?php require_once '../config/autoload.php'; ?>

<?php
    // Connect to the database as a doctor
    $conn = connect_as_doctor(); // Change this function if needed
    if (!$conn) {
        die('Database connection failed');
    }

    // Fetch available services
    $services_sql = "SELECT id, name FROM services"; // Change 'services' to your actual services table name
    $services_stmt = $conn->prepare($services_sql);
    $services_stmt->execute();
    $services = $services_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get appointment ID from the URL
    $appointment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Default to 0 if not set

    if ($appointment_id === 0) {
        die('Invalid appointment ID');
    }
?>

<?php
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Collect and sanitize input data
        $symptoms = trim($_POST['symptoms']);
        $diagnosis = trim($_POST['diagnosis']);
        $advice = trim($_POST['advice']);
        $additional_service = trim($_POST['additional_service']); // Get additional service
        $additional_fee = (float)$_POST['additional_fee'];

        // Prepare and execute the SQL statement for exam results
        try {
            $sql = "INSERT INTO exam_results (appointment_id, symptoms, diagnosis, advice, additional_service, additional_fee)
                    VALUES (:appointment_id, :symptoms, :diagnosis, :advice, :additional_service, :additional_fee)";
            $stmt = $conn->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':appointment_id', $appointment_id);
            $stmt->bindParam(':symptoms', $symptoms);
            $stmt->bindParam(':diagnosis', $diagnosis);
            $stmt->bindParam(':advice', $advice);
            $stmt->bindParam(':additional_service', $additional_service); // Bind additional service
            $stmt->bindParam(':additional_fee', $additional_fee);

            // Execute the statement
            if ($stmt->execute()) {
                $exam_result_id = $conn->lastInsertId(); // Get the last inserted ID

                // Handle selected services
                if (isset($_POST['services'])) {
                    foreach ($_POST['services'] as $index => $service_id) {
                        echo $service_id;
                        echo $index;
                        $notes = isset($_POST['service_notes'][$index]) ? $_POST['service_notes'][$index] : '';
                        $service_sql = "INSERT INTO exam_services (exam_result_id, service_id, notes) 
                                        VALUES (:exam_result_id, :service_id, :notes)";
                        $service_stmt = $conn->prepare($service_sql);
                        $service_stmt->bindParam(':exam_result_id', $exam_result_id);
                        $service_stmt->bindParam(':service_id', $service_id);
                        $service_stmt->bindParam(':notes', $notes);
                        $service_stmt->execute();
                    }
                }
            
                // Mark the appointment as completed
                $update_sql = "UPDATE appointments SET status = 'completed' WHERE id = :appointment_id";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bindParam(':appointment_id', $appointment_id);
                $update_stmt->execute();

                header("Location: exam-result-add.php?status=success");
                exit();
            } else {
                header("Location: exam-result-add.php?status=fail");
                exit();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once HEADER; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.css" />
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
        .btn-sm {
            padding: 0.25rem 0.5rem; /* Reduce padding for smaller buttons */
            font-size: 0.875rem; /* Smaller font size */
        }

        .service-row {
            display: flex; /* Align service elements in a row */
            align-items: center; /* Center vertically */
            margin-bottom: 10px; /* Space between service rows */
        }

        .service-group {
            flex: 1; /* Fill remaining space */
            margin-right: 10px; /* Space between service elements */
        }

    </style>
</head>

<body>
    <?php require_once SIDE_NAVBAR; ?>

    <div class="main-content">
        <?php require_once TOP_NAVBAR; ?>

        <main class="content mt-5">
            <?php $url_status = isset($_GET['status']) ? $_GET['status'] : ''; ?>
            <?php if ($url_status == "success"): ?>
                <div id="status-message" class="alert alert-success">Examination result added successfully.</div>
            <?php elseif ($url_status == "fail"): ?>
                <div id="status-message" class="alert alert-danger">Failed to add examination result.</div>
            <?php endif; ?>

            <form method="POST" class="shadow-effect p-5">
                <div class="form-group">
                    <label for="appointment_id">Appointment ID</label>
                    <input type="number" id="appointment_id" name="appointment_id" class="form-control" value="<?= $appointment_id ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="symptoms">Symptoms</label>
                    <textarea id="symptoms" name="symptoms" class="form-control" rows="3" placeholder="Enter symptoms" required></textarea>
                </div>
                <div class="form-group">
                    <label for="diagnosis">Diagnosis</label>
                    <textarea id="diagnosis" name="diagnosis" class="form-control" rows="3" placeholder="Enter diagnosis" required></textarea>
                </div>
                <div class="form-group">
                    <label for="advice">Advice</label>
                    <textarea id="advice" name="advice" class="form-control" rows="3" placeholder="Enter advice" required></textarea>
                </div>
                <div class="form-group>
                    <label for="additional_service">Additional Service</label>
                    <input type="text" id="additional_service" name="additional_service" class="form-control" placeholder="Enter additional service">
                </div>
                <div class="form-group">
                    <label for="additional_fee">Additional Fee</label>
                    <input type="number" id="additional_fee" name="additional_fee" class="form-control" placeholder="Enter additional fee" step="0.01">
                </div>
                
                <div class="form-group service-group">
                    <label for="services">Select Service</label>
                    <div id="service-container">
                        <!-- <div class="service-row">
                            <div class="service-group">
                                <select name="services[]" class="form-control service-select" required>
                                    <option value="">Select a service</option>
                                    <?php foreach ($services as $service): ?>
                                        <option value="<?= htmlspecialchars($service['id']) ?>"><?= htmlspecialchars($service['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <textarea name="service_notes[]" class="form-control" placeholder="Enter notes for the service" rows="2" style="margin-top: 5px;"></textarea>
                            </div>
                            <button type="button" class="btn btn-secondary btn-sm remove-service" style="margin-left: 10px;">-</button>
                        </div> -->
                    </div>
                    <button type="button" class="btn btn-primary btn-sm add-service" style="margin-top: 10px;">+</button>
                </div>

                <button type="reset" class="btn btn-secondary">Clear Info</button>
                <button type="submit" class="btn btn-primary">Add Exam Result</button>
            </form>
        </main>
    </div>

    <script>
    $(document).ready(function() {
        // Function to create a new service select dropdown and note textarea
        function createServiceRow() {
            const serviceRow = $('<div class="service-row"></div>');
            const serviceGroup = $('<div class="service-group"></div>');
            const serviceSelect = $('<select name="services[]" class="form-control service-select" required></select>');
            const serviceNote = $('<textarea name="service_notes[]" class="form-control" placeholder="Enter notes for the service" rows="2" style="margin-top: 5px;"></textarea>');
            const removeButton = $('<button type="button" class="btn btn-secondary btn-sm remove-service" style="margin-left: 5px;">-</button>');

            // Populate the service select options
            serviceSelect.append('<option value="">Select a service</option>');
            <?php foreach ($services as $service): ?>
                serviceSelect.append('<option value="<?= htmlspecialchars($service['id']) ?>"><?= htmlspecialchars($service['name']) ?></option>');
            <?php endforeach; ?>

            serviceGroup.append(serviceSelect).append(serviceNote);
            serviceRow.append(serviceGroup).append(removeButton);
            $('#service-container').append(serviceRow);
        }

        // Event listener to add a new service select when + button is clicked
        $('.add-service').on('click', function() {
            createServiceRow();
        });

        // Event listener to remove a service select when - button is clicked
        $('#service-container').on('click', '.remove-service', function() {
            $(this).closest('.service-row').remove();
        });
    });
    </script>


</body>
</html>
