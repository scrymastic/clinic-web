<?php
// Include session check to ensure patient is logged in
require_once './includes/session.php';
require_once '../config/autoload.php'; // Include autoload file
require_once './includes/path.php';


$conn = connect_as_patient(); // Connect as patient

// Get the logged-in patient's ID from session
$patient_id = $_SESSION['patient_id'];

// Fetch available specialties
$sql = "SELECT id, name FROM specialties";
$stmt = $conn->prepare($sql);
$stmt->execute();
$specialties = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize variables
$errors = [];
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor_id = $_POST['doctor_id'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';
    $duration = $_POST['duration'] ?? '00:30:00';
    $notes = $_POST['notes'] ?? '';

    // Validation
    if (empty($doctor_id)) {
        $errors[] = 'Please select a doctor.';
    }

    if (empty($appointment_time) || strtotime($appointment_time) < time()) {
        $errors[] = 'Please select a valid future appointment time.';
    }

    // If no errors, insert the appointment into the database
    if (empty($errors)) {
        $sql = "INSERT INTO appointments (doctor_id, patient_id, appointment_time, duration, notes)
                VALUES (:doctor_id, :patient_id, :appointment_time, :duration, :notes)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'doctor_id' => $doctor_id,
            'patient_id' => $patient_id,
            'appointment_time' => $appointment_time,
            'duration' => $duration,
            'notes' => $notes,
        ]);

        $success = 'Appointment booked successfully!';
    }
}

// Handle AJAX request to fetch doctors based on specialty
if (isset($_GET['specialty_id'])) {
    $specialty_id = $_GET['specialty_id'];

    // Prepare the SQL statement to fetch doctors by specialty
    $sql = "SELECT id, username FROM doctors WHERE specialty_id = :specialty_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':specialty_id', $specialty_id, PDO::PARAM_INT);
    $stmt->execute();
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the doctors as a JSON response
    echo json_encode($doctors);
    exit; // Exit to prevent the rest of the script from executing
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once HEADER; ?>
    <style>
        /* Additional styling for form */
        .form-container {
            margin: 20px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php require_once TOP_NAVBAR; ?>

    <main class="container my-5">
        <h1 class="text-center mb-4">Book an Appointment</h1>
        <!-- Display success or error messages -->
        <?php if (!empty($success)): ?>
        <div class="alert alert-success" id="status-message"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger" id="status-message">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <div class="form-container">

            <!-- Appointment booking form -->
            <form method="POST" action="appointment-book.php" id="appointmentForm">
                <div class="form-group">
                    <label for="specialty_id">Select Specialty</label>
                    <select name="specialty_id" id="specialty_id" class="form-control">
                        <option value="">-- Choose a specialty --</option>
                        <?php foreach ($specialties as $specialty): ?>
                            <option value="<?php echo $specialty['id']; ?>">
                                <?php echo htmlspecialchars($specialty['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="doctor_id">Select Doctor</label>
                    <select name="doctor_id" id="doctor_id" class="form-control" required>
                        <option value="">-- Choose a doctor --</option>
                        <!-- Doctors will be loaded here based on the specialty -->
                    </select>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="appointment_time">Appointment Time</label>
                        <input type="datetime-local" name="appointment_time" id="appointment_time" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="duration">Duration</label>
                        <input type="time" name="duration" id="duration" class="form-control" value="00:30:00">
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">Notes (optional)</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Book Appointment</button>
            </form>
        </div>
    </main>

    <?php require_once FOOTER; ?>

    <script>
        document.getElementById('specialty_id').addEventListener('change', function() {
            var specialtyId = this.value;

            // Clear the doctor select box
            var doctorSelect = document.getElementById('doctor_id');
            doctorSelect.innerHTML = '<option value="">-- Choose a doctor --</option>';

            if (specialtyId) {
                // Perform AJAX request to fetch doctors
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'appointment-book.php?specialty_id=' + specialtyId, true);
                xhr.onload = function() {
                    if (this.status === 200) {
                        var doctors = JSON.parse(this.responseText);
                        doctors.forEach(function(doctor) {
                            var option = document.createElement('option');
                            option.value = doctor.id;
                            option.textContent = doctor.username;
                            doctorSelect.appendChild(option);
                        });
                    }
                };
                xhr.send();
            }
        });
    </script>

    <script>
        // Function to hide alerts after a specific time
        window.onload = function() {
            var statusMessage = document.getElementById('status-message');
            if (statusMessage) {
                setTimeout(function() {
                    statusMessage.style.display = 'none';
                }, 5000);
            }
        };
    </script>

</body>

</html>

<?php
// Close the connection
$conn = null;
?>