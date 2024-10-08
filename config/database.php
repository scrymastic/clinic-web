
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'clinxme');
define('DB_ADMIN', 'kali');
define('DB_ADMIN_PASS', 'kali');
define('DB_DOCTOR', 'kali');
define('DB_DOCTOR_PASS', 'kali');
define('DB_PATIENT', 'kali');
define('DB_PATIENT_PASS', 'kali');
?>

<?php
function connect_as_admin() {
    $conn = null;
    try {
        // Create a new PDO instance
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $conn = new PDO($dsn, DB_ADMIN, DB_ADMIN_PASS, $options);

    } catch (PDOException $e) {
        // Handle connection error
        die("Connection failed: " . $e->getMessage());
    } finally {
        return $conn;
    }
}

function connect_as_doctor() {
    $conn = null;
    try {
        // Create a new PDO instance
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $conn = new PDO($dsn, DB_DOCTOR, DB_DOCTOR_PASS, $options);
        return $conn;

    } catch (PDOException $e) {
        // Handle connection error
        die("Connection failed: " . $e->getMessage());
    } finally {
        return $conn;
    }
}

function connect_as_patient() {
    $conn = null;
    try {
        // Create a new PDO instance
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $conn = new PDO($dsn, DB_PATIENT, DB_PATIENT_PASS, $options);
        return $conn;

    } catch (PDOException $e) {
        // Handle connection error
        die("Connection failed: " . $e->getMessage());
    } finally {
        return $conn;
    }
}
?>