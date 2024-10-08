CREATE TABLE `admin` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `phone` VARCHAR(15),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `specialties` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `icon` VARCHAR(255) DEFAULT 'fas fa-stethoscope',
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `doctors` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `phone` VARCHAR(15),
    `specialty_id` INT,
    `qualification` VARCHAR(255),
    `bio` TEXT,
    `fee_per_hour` DECIMAL(10, 2) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`specialty_id`) REFERENCES `specialties`(`id`)
);

CREATE TABLE `patients` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `phone` VARCHAR(15),
    `date_of_birth` DATE,
    `gender` ENUM('male', 'female', 'other') NOT NULL DEFAULT 'other',
    `medical_history` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `appointments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `doctor_id` INT NOT NULL,
    `patient_id` INT NOT NULL,
    `appointment_time` DATETIME NOT NULL,
    `duration` TIME DEFAULT '00:30:00' NOT NULL,
    `status` ENUM('pending', 'scheduled', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    `booked_by` ENUM('patient', 'doctor') NOT NULL DEFAULT 'patient',
    `cancelled_by` ENUM('none', 'patient', 'doctor') DEFAULT 'none' NOT NULL,
    `cancel_reason` TEXT NULL,
    `notes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`doctor_id`) REFERENCES `doctors`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE
);


CREATE TABLE `services` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT,
    -- `icon` VARCHAR(255) DEFAULT 'fas fa-stethoscope',
    `price` DECIMAL(10, 2) NOT NULL CHECK (`price` >= 0),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE `medical_records` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `appointment_id` INT NOT NULL,
    `symptoms` TEXT,
    `diagnosis` TEXT,
    `advice` TEXT,
    `additional_service` TEXT DEFAULT NULL,
    `additional_fee` DECIMAL(10, 2) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`appointment_id`) REFERENCES `appointments`(`id`) ON DELETE CASCADE
);

CREATE TABLE `medical_record_services` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `medical_record_id` INT NOT NULL,
    `service_id` INT NOT NULL,
    `notes` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`service_id`) REFERENCES `services`(`id`)
);

-- New Table: Invoices for Medical Records
CREATE TABLE `invoices` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `medical_record_id` INT NOT NULL,
    `total_amount` DECIMAL(10, 2) NOT NULL,
    `status` ENUM('pending', 'paid', 'overdue', 'cancelled') NOT NULL DEFAULT 'pending',
    `payment_method` ENUM('cash', 'credit card', 'insurance', 'other') NOT NULL,
    `issued_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `paid_at` DATETIME DEFAULT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records`(`id`) ON DELETE CASCADE
);

-- New Table: Examination Results
CREATE TABLE `examination_results` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `medical_record_id` INT NOT NULL,
    `test_type` VARCHAR(100) NOT NULL,
    `result` TEXT NOT NULL,
    `performed_by` VARCHAR(100),
    `result_date` DATE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records`(`id`) ON DELETE CASCADE
);


CREATE TABLE `doctor_schedules` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `doctor_id` INT NOT NULL,
    `week_start_date` DATE NOT NULL,
    `day_of_week` ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    `start_time` TIME NOT NULL,
    `end_time` TIME NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`doctor_id`) REFERENCES `doctors`(`id`)
);

CREATE TABLE `clinic_info` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `address` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(15),
    `email` VARCHAR(100),
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `announcements` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `content` TEXT,
    `publish_date` DATETIME NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `reviews` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `patient_id` INT NOT NULL,
    `doctor_id` INT NOT NULL,
    `rating` INT CHECK(`rating` >= 1 AND `rating` <= 5),
    `comment` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`doctor_id`) REFERENCES `doctors`(`id`) ON DELETE CASCADE
);


-- Sample data for 'admin' table
INSERT INTO `admin` (`username`, `password`, `email`, `phone`) VALUES
('admin1', 'kali', 'admin1@example.com', '1234567890'),
('admin2', 'kali', 'admin2@example.com', '0987654321');

-- Sample data for 'specialties' table
INSERT INTO `specialties` (`name`, `icon`, `description`) VALUES
('Cardiology', 'fas fa-heart', 'Heart and circulatory system treatments.'),
('Pediatrics', 'fas fa-baby', 'Child healthcare and development.'),
('Dermatology', 'fas fa-allergies', 'Skin and hair treatments.'),
('Neurology', 'fas fa-brain', 'Brain and nervous system treatments.');

-- Sample data for 'doctors' table
INSERT INTO `doctors` (`username`, `password`, `email`, `phone`, `specialty_id`, `qualification`, `bio`, `fee_per_hour`) VALUES
('dr_john', 'kali', 'dr.john@example.com', '5551234567', 1, 'MBBS, MD (Cardiology)', 'Expert in heart-related diseases.', 150.00),
('dr_jane', 'kali', 'dr.jane@example.com', '5559876543', 2, 'MBBS, MD (Pediatrics)', 'Specialist in child healthcare.', 100.00),
('dr_mark', 'kali', 'dr.mark@example.com', '5553456789', 3, 'MBBS, MD (Dermatology)', 'Treats skin conditions.', 120.00);

-- Sample data for 'patients' table
INSERT INTO `patients` (`username`, `password`, `email`, `phone`, `date_of_birth`, `gender`, `medical_history`) VALUES
('patient1', 'kali', 'patient1@example.com', '6661234567', '1990-01-15', 'male', 'History of hypertension.'),
('patient2', 'kali', 'patient2@example.com', '6669876543', '1985-06-30', 'female', 'Asthma and allergies.'),
('patient3', 'kali', 'patient3@example.com', '6663456789', '2000-12-25', 'other', 'No significant medical history.');

-- Sample data for 'appointments' table
INSERT INTO `appointments` (`doctor_id`, `patient_id`, `appointment_time`, `duration`, `status`, `booked_by`, `cancelled_by`, `notes`) VALUES
(1, 1, '2024-10-01 10:00:00', '00:30:00', 'scheduled', 'patient', 'none', 'Routine heart check-up.'),
(2, 2, '2024-10-02 11:00:00', '00:30:00', 'completed', 'patient', 'none', "Child's regular health check-up."),
(3, 3, '2024-10-03 09:30:00', '00:30:00', 'cancelled', 'doctor', 'doctor', 'Skin rash consultation.');

-- Sample data for 'services' table
INSERT INTO `services` (`name`, `description`, `price`) VALUES
('General Check-up', 'Routine general health examination.',  50.00),
('Blood Test', 'Comprehensive blood testing.', 20.00),
('X-Ray', 'Imaging test for bones.', 100.00),
('MRI Scan', 'Detailed body scan using MRI technology.', 250.00);

-- Sample data for 'medical_records' table
INSERT INTO `medical_records` (`appointment_id`, `symptoms`, `diagnosis`, `advice`, `additional_service`, `additional_fee`) VALUES
(1, 'Chest pain, shortness of breath.', 'Mild hypertension.', 'Regular exercise and medication.', 'Blood Test', 20.00),
(2, 'Fever, cough.', 'Seasonal flu.', 'Hydration and rest.', NULL, NULL);

-- Sample data for 'medical_record_services' table
INSERT INTO `medical_record_services` (`medical_record_id`, `service_id`, `notes`) VALUES
(1, 2, 'Blood test to check cholesterol levels.'),
(2, 1, "Routine check-up for child's flu symptoms.");

-- Sample data for 'medical_record_details' table
INSERT INTO `medical_record_details` (`medical_record_id`, `doctor_id`, `examination_date`, `next_appointment_date`, `notes`) VALUES
(1, 1, '2024-10-01', '2024-10-15', 'Follow-up to monitor blood pressure.'),
(2, 2, '2024-10-02', NULL, 'No follow-up required.');

-- Sample data for 'invoices' table
INSERT INTO `invoices` (`medical_record_id`, `total_amount`, `status`, `payment_method`, `issued_at`, `paid_at`) VALUES
(1, 70.00, 'Paid', 'Cash', '2024-10-01 11:00:00', '2024-10-01 11:05:00'),
(2, 50.00, 'Pending', 'Credit Card', '2024-10-02 12:00:00', NULL);

-- Sample data for 'examination_results' table
INSERT INTO `examination_results` (`medical_record_id`, `test_type`, `result`, `performed_by`, `result_date`) VALUES
(1, 'Blood Test', 'Cholesterol levels normal.', 'Lab Technician A', '2024-10-01'),
(2, 'General Examination', 'Flu symptoms detected.', 'Dr. Jane', '2024-10-02');

-- Sample data for 'doctor_schedules' table
INSERT INTO `doctor_schedules` (`doctor_id`, `week_start_date`, `day_of_week`, `start_time`, `end_time`) VALUES
(1, '2024-09-30', 'Monday', '09:00:00', '12:00:00'),
(2, '2024-09-30', 'Tuesday', '10:00:00', '14:00:00'),
(3, '2024-09-30', 'Wednesday', '11:00:00', '13:00:00');

-- Sample data for 'clinic_info' table
INSERT INTO `clinic_info` (`name`, `address`, `phone`, `email`, `description`) VALUES
('Healthy Life Clinic', '123 Wellness St, Health City', '7771234567', 'info@healthylifeclinic.com', 'A modern clinic offering a range of healthcare services.');

-- Sample data for 'announcements' table
INSERT INTO `announcements` (`title`, `content`, `publish_date`) VALUES
('Clinic Holiday Notice', 'The clinic will be closed on October 10th for a public holiday.', '2024-09-28 09:00:00'),
('New Services Available', 'We are now offering MRI scans and other advanced diagnostic tests.', '2024-10-01 10:00:00');

-- Sample data for 'reviews' table
INSERT INTO `reviews` (`patient_id`, `doctor_id`, `rating`, `comment`) VALUES
(1, 1, 5, 'Dr. John is very knowledgeable and kind. Highly recommended.'),
(2, 2, 4, "Good experience with Dr. Jane, my child's check-up went smoothly."),
(3, 3, 3, 'Consultation was okay, but had to wait for a while.');


-- More data for 'admin' table
INSERT INTO `admin` (`username`, `password`, `email`, `phone`) VALUES
('admin3', 'kali', 'admin3@example.com', '1122334455'),
('admin4', 'kali', 'admin4@example.com', '5566778899');

-- More data for 'specialties' table
INSERT INTO `specialties` (`name`, `icon`, `description`) VALUES
('Orthopedics', 'fas fa-bone', 'Bone and joint treatments.'),
('Psychiatry', 'fas fa-brain', 'Mental health and therapy.'),
('Ophthalmology', 'fas fa-eye', 'Eye care and vision treatments.'),
('Gastroenterology', 'fas fa-stomach', 'Digestive system and liver treatments.');

-- More data for 'doctors' table
INSERT INTO `doctors` (`username`, `password`, `email`, `phone`, `specialty_id`, `qualification`, `bio`, `fee_per_hour`) VALUES
('dr_ashley', 'kali', 'dr.ashley@example.com', '5554567890', 4, 'MBBS, MD (Orthopedics)', 'Specialist in bone injuries and surgeries.', 200.00),
('dr_david', 'kali', 'dr.david@example.com', '5556543210', 5, 'MBBS, MD (Psychiatry)', 'Experienced psychiatrist and mental health counselor.', 180.00),
('dr_sara', 'kali', 'dr.sara@example.com', '5557896541', 6, 'MBBS, MD (Ophthalmology)', 'Expert in eye surgery and vision care.', 140.00),
('dr_samuel', 'kali', 'dr.samuel@example.com', '5559871234', 7, 'MBBS, MD (Gastroenterology)', 'Digestive system and liver expert.', 160.00);

-- More data for 'patients' table
INSERT INTO `patients` (`username`, `password`, `email`, `phone`, `date_of_birth`, `gender`, `medical_history`) VALUES
('patient4', 'kali', 'patient4@example.com', '6666543210', '1995-07-20', 'male', 'Diabetes type 2.'),
('patient5', 'kali', 'patient5@example.com', '6663210987', '1978-04-15', 'female', 'History of depression and anxiety.'),
('patient6', 'kali', 'patient6@example.com', '6669876541', '2002-11-10', 'male', 'No significant medical history.'),
('patient7', 'kali', 'patient7@example.com', '6661230987', '1988-03-05', 'female', 'Gastroesophageal reflux disease (GERD).');

-- More data for 'appointments' table
INSERT INTO `appointments` (`doctor_id`, `patient_id`, `appointment_time`, `duration`, `status`, `booked_by`, `cancelled_by`, `notes`) VALUES
(4, 4, '2024-10-05 09:00:00', '00:30:00', 'scheduled', 'patient', 'none', 'Orthopedic check-up for knee pain.'),
(5, 5, '2024-10-06 14:00:00', '01:00:00', 'completed', 'patient', 'none', 'Therapy session for depression.'),
(6, 6, '2024-10-07 10:00:00', '00:30:00', 'scheduled', 'patient', 'none', 'Vision check-up and eyeglasses prescription.'),
(7, 7, '2024-10-08 11:30:00', '00:45:00', 'scheduled', 'patient', 'none', 'Consultation for digestive issues.');

-- More data for 'services' table
INSERT INTO `services` (`name`, `description`,`price`) VALUES
('CT Scan', 'Advanced imaging technique to view internal organs.', 300.00),
('Ultrasound', 'Imaging technique to view internal body structures.', 80.00),
('Therapy Session', 'Mental health counseling.', 150.00),
('Bone X-Ray', 'X-ray imaging for bone fractures.', 100.00);

-- More data for 'medical_records' table
INSERT INTO `medical_records` (`appointment_id`, `symptoms`, `diagnosis`, `advice`, `additional_service`, `additional_fee`) VALUES
(4, 'Knee pain, difficulty walking.', 'Knee arthritis.', 'Physical therapy and medication.', 'Ultrasound', 80.00),
(5, 'Feeling anxious and low energy.', 'Chronic depression.', 'Regular therapy sessions and medication.', 'Therapy Session', 150.00),
(6, 'Blurry vision.', 'Nearsightedness.', 'Prescription eyeglasses.', NULL, NULL),
(7, 'Frequent heartburn and indigestion.', 'GERD.', 'Diet modification and medication.', 'CT Scan', 300.00);

-- More data for 'medical_record_services' table
INSERT INTO `medical_record_services` (`medical_record_id`, `service_id`, `notes`) VALUES
(3, 4, 'Performed X-Ray to check knee joint structure.'),
(4, 3, 'Therapy session for mental health.'),
(5, 6, 'Performed general eye examination for vision issues.'),
(6, 5, 'Performed CT Scan to examine digestive tract.');

-- More data for 'medical_record_details' table
INSERT INTO `medical_record_details` (`medical_record_id`, `doctor_id`, `examination_date`, `next_appointment_date`, `notes`) VALUES
(3, 4, '2024-10-05', '2024-10-12', 'Follow-up for physical therapy progress.'),
(4, 5, '2024-10-06', '2024-10-13', 'Therapy session every week.'),
(5, 6, '2024-10-07', '2024-10-28', 'Come back for eyeglasses fitting.'),
(6, 7, '2024-10-08', '2024-10-22', 'Monitor symptoms after starting medication.');

-- More data for 'invoices' table
INSERT INTO `invoices` (`medical_record_id`, `total_amount`, `status`, `payment_method`, `issued_at`, `paid_at`) VALUES
(3, 280.00, 'Paid', 'Cash', '2024-10-05 12:00:00', '2024-10-05 12:10:00'),
(4, 300.00, 'Pending', 'Insurance', '2024-10-06 16:00:00', NULL),
(5, 140.00, 'Paid', 'Credit Card', '2024-10-07 12:30:00', '2024-10-07 12:35:00'),
(6, 460.00, 'Pending', 'Cash', '2024-10-08 13:00:00', NULL);

-- More data for 'examination_results' table
INSERT INTO `examination_results` (`medical_record_id`, `test_type`, `result`, `performed_by`, `result_date`) VALUES
(3, 'Bone X-Ray', 'Arthritis in knee joint detected.', 'Radiologist B', '2024-10-05'),
(4, 'Therapy Session', 'Patient shows signs of improvement.', 'Dr. David', '2024-10-06'),
(5, 'Eye Exam', 'Nearsightedness confirmed.', 'Dr. Sara', '2024-10-07'),
(6, 'CT Scan', 'No major abnormalities in digestive tract.', 'Radiologist C', '2024-10-08');

-- More data for 'doctor_schedules' table
INSERT INTO `doctor_schedules` (`doctor_id`, `week_start_date`, `day_of_week`, `start_time`, `end_time`) VALUES
(4, '2024-09-30', 'Thursday', '08:00:00', '12:00:00'),
(5, '2024-09-30', 'Friday', '10:00:00', '15:00:00'),
(6, '2024-09-30', 'Saturday', '09:00:00', '13:00:00'),
(7, '2024-09-30', 'Sunday', '12:00:00', '16:00:00');

-- More data for 'announcements' table
INSERT INTO `announcements` (`title`, `content`, `publish_date`) VALUES
('Flu Vaccination Drive', 'We are organizing a flu vaccination drive from October 15th to 20th. All patients are encouraged to get vaccinated.', '2024-10-01'),
('New Specialist Joining', 'We are happy to announce that Dr. Samuel, a gastroenterologist, is joining our clinic. Appointments open from October 10th.', '2024-10-02');

ALTER TABLE `appointments` DROP COLUMN `booked_by`;