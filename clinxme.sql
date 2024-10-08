-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2024 at 07:48 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clinxme`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `email`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'admin1', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'admin1@example.com', '1234567890', '2024-10-01 04:37:01', '2024-10-02 17:30:08'),
(2, 'admin2', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'admin2@example.com', '0987654321', '2024-10-01 04:37:01', '2024-10-02 17:30:12'),
(3, 'admin3', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'admin3@example.com', '1122334455', '2024-10-01 04:37:01', '2024-10-02 17:29:22'),
(4, 'admin4', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'admin4@example.com', '5566778899', '2024-10-01 04:37:01', '2024-10-02 17:29:24');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `publish_date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `publish_date`, `created_at`, `updated_at`) VALUES
(1, 'Clinic Holiday Notice', 'The clinic will be closed on October 10th for a public holiday.', '2024-09-28 09:00:00', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(2, 'New Services Available', 'We are now offering MRI scans and other advanced diagnostic tests.', '2024-10-01 10:00:00', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(3, 'Flu Vaccination Drive', 'We are organizing a flu vaccination drive from October 15th to 20th. All patients are encouraged to get vaccinated.', '2024-10-01 00:00:00', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(4, 'New Specialist Joining', 'We are happy to announce that Dr. Samuel, a gastroenterologist, is joining our clinic. Appointments open from October 10th.', '2024-10-02 00:00:00', '2024-10-01 04:37:01', '2024-10-01 04:37:01');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `appointment_time` datetime NOT NULL,
  `duration` time NOT NULL DEFAULT '00:30:00',
  `status` enum('pending','scheduled','completed','cancelled') NOT NULL DEFAULT 'pending',
  `cancelled_by` enum('none','patient','doctor') DEFAULT 'none',
  `cancel_reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `doctor_id`, `patient_id`, `appointment_time`, `duration`, `status`, `cancelled_by`, `cancel_reason`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2024-10-01 10:00:00', '00:30:00', 'scheduled', 'patient', NULL, 'Routine heart check-up.', '2024-10-01 04:37:01', '2024-10-02 17:45:07'),
(2, 2, 2, '2024-10-02 11:00:00', '00:30:00', 'completed', 'none', NULL, 'Childs regular health check-up.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(3, 3, 3, '2024-10-03 09:30:00', '00:30:00', 'cancelled', 'doctor', NULL, 'Skin rash consultation.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(4, 4, 4, '2024-10-05 09:00:00', '00:30:00', 'scheduled', 'none', NULL, 'Orthopedic check-up for knee pain.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(5, 5, 5, '2024-10-06 14:00:00', '01:00:00', 'completed', 'none', NULL, 'Therapy session for depression.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(6, 6, 6, '2024-10-07 10:00:00', '00:30:00', 'scheduled', 'none', NULL, 'Vision check-up and eyeglasses prescription.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(7, 7, 7, '2024-10-08 11:30:00', '00:45:00', 'scheduled', 'none', NULL, 'Consultation for digestive issues.', '2024-10-01 04:37:01', '2024-10-01 04:37:01');

-- --------------------------------------------------------

--
-- Table structure for table `clinic_info`
--

CREATE TABLE `clinic_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clinic_info`
--

INSERT INTO `clinic_info` (`id`, `name`, `address`, `phone`, `email`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Healthy Life Clinic', '123 Wellness St, Health City', '7771234567', 'info@healthylifeclinic.com', 'A modern clinic offering a range of healthcare services.', '2024-10-01 04:37:01', '2024-10-01 04:37:01');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `specialty_id` int(11) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `fee_per_hour` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `username`, `password`, `email`, `phone`, `specialty_id`, `qualification`, `bio`, `fee_per_hour`, `created_at`, `updated_at`) VALUES
(1, 'dr_john', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'dr.john@example.com', '5551234567', 1, 'MBBS, MD (Cardiology)', 'Expert in heart-related diseases.', '150.00', '2024-10-01 04:37:01', '2024-10-02 17:29:49'),
(2, 'dr_jane', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'dr.jane@example.com', '5559876543', 2, 'MBBS, MD (Pediatrics)', 'Specialist in child healthcare.', '100.00', '2024-10-01 04:37:01', '2024-10-02 17:29:41'),
(3, 'dr_mark', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'dr.mark@example.com', '5553456789', 3, 'MBBS, MD (Dermatology)', 'Treats skin conditions.', '120.00', '2024-10-01 04:37:01', '2024-10-02 17:29:45'),
(4, 'dr_ashley', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'dr.ashley@example.com', '5554567890', 4, 'MBBS, MD (Orthopedics)', 'Specialist in bone injuries and surgeries.', '200.00', '2024-10-01 04:37:01', '2024-10-02 17:29:52'),
(5, 'dr_david', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'dr.david@example.com', '5556543210', 5, 'MBBS, MD (Psychiatry)', 'Experienced psychiatrist and mental health counselor.', '180.00', '2024-10-01 04:37:01', '2024-10-02 17:29:55'),
(6, 'dr_sara', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'dr.sara@example.com', '5557896541', 6, 'MBBS, MD (Ophthalmology)', 'Expert in eye surgery and vision care.', '140.00', '2024-10-01 04:37:01', '2024-10-02 17:29:57'),
(7, 'dr_samuel', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'dr.samuel@example.com', '5559871234', 7, 'MBBS, MD (Gastroenterology)', 'Digestive system and liver expert.', '160.00', '2024-10-01 04:37:01', '2024-10-02 17:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_schedules`
--

CREATE TABLE `doctor_schedules` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `week_start_date` date NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctor_schedules`
--

INSERT INTO `doctor_schedules` (`id`, `doctor_id`, `week_start_date`, `day_of_week`, `start_time`, `end_time`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-09-30', 'Monday', '09:00:00', '12:00:00', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(2, 2, '2024-09-30', 'Tuesday', '10:00:00', '14:00:00', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(3, 3, '2024-09-30', 'Wednesday', '11:00:00', '13:00:00', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(4, 4, '2024-09-30', 'Thursday', '08:00:00', '12:00:00', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(5, 5, '2024-09-30', 'Friday', '10:00:00', '15:00:00', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(6, 6, '2024-09-30', 'Saturday', '09:00:00', '13:00:00', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(7, 7, '2024-09-30', 'Sunday', '12:00:00', '16:00:00', '2024-10-01 04:37:01', '2024-10-01 04:37:01');

-- --------------------------------------------------------

--
-- Table structure for table `exam_results`
--

CREATE TABLE `exam_results` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `symptoms` text DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `advice` text DEFAULT NULL,
  `additional_service` text DEFAULT NULL,
  `additional_fee` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `exam_results`
--

INSERT INTO `exam_results` (`id`, `appointment_id`, `symptoms`, `diagnosis`, `advice`, `additional_service`, `additional_fee`, `created_at`, `updated_at`) VALUES
(1, 1, 'Chest pain, shortness of breath.', 'Mild hypertension.', 'Regular exercise and medication.', 'Blood Test', '20.00', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(2, 2, 'Fever, cough.', 'Seasonal flu.', 'Hydration and rest.', NULL, NULL, '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(3, 4, 'Knee pain, difficulty walking.', 'Knee arthritis.', 'Physical therapy and medication.', 'Ultrasound', '80.00', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(4, 5, 'Feeling anxious and low energy.', 'Chronic depression.', 'Regular therapy sessions and medication.', 'Therapy Session', '150.00', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(5, 6, 'Blurry vision.', 'Nearsightedness.', 'Prescription eyeglasses.', NULL, NULL, '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(6, 7, 'Frequent heartburn and indigestion.', 'GERD.', 'Diet modification and medication.', 'CT Scan', '300.00', '2024-10-01 04:37:01', '2024-10-01 04:37:01');

-- --------------------------------------------------------

--
-- Table structure for table `exam_services`
--

CREATE TABLE `exam_services` (
  `id` int(11) NOT NULL,
  `exam_result_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `exam_services`
--

INSERT INTO `exam_services` (`id`, `exam_result_id`, `service_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Blood test to check cholesterol levels.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(2, 2, 1, 'Routine check-up for childs flu symptoms.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(3, 3, 4, 'Performed X-Ray to check knee joint structure.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(4, 4, 3, 'Therapy session for mental health.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(5, 5, 6, 'Performed general eye examination for vision issues.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(6, 6, 5, 'Performed CT Scan to examine digestive tract.', '2024-10-01 04:37:01', '2024-10-01 04:37:01');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `exam_result_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','overdue','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` enum('cash','credit card','insurance','other') NOT NULL,
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `paid_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `invoices`
MODIFY `status` ENUM('pending', 'paid', 'overdue', 'cancelled', 'waiting') NOT NULL DEFAULT 'waiting';

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `exam_result_id`, `total_amount`, `status`, `payment_method`, `issued_at`, `paid_at`, `updated_at`) VALUES
(1, 1, '70.00', 'paid', 'cash', '2024-10-01 04:00:00', '2024-10-01 11:05:00', '2024-10-01 04:37:01'),
(2, 2, '50.00', 'pending', 'credit card', '2024-10-02 05:00:00', NULL, '2024-10-01 04:37:01'),
(3, 3, '280.00', 'paid', 'cash', '2024-10-05 05:00:00', '2024-10-05 12:10:00', '2024-10-01 04:37:01'),
(4, 4, '300.00', 'pending', 'insurance', '2024-10-06 09:00:00', NULL, '2024-10-01 04:37:01'),
(5, 5, '140.00', 'paid', 'credit card', '2024-10-07 05:30:00', '2024-10-07 12:35:00', '2024-10-01 04:37:01'),
(6, 6, '460.00', 'pending', 'cash', '2024-10-08 06:00:00', NULL, '2024-10-01 04:37:01');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') NOT NULL DEFAULT 'other',
  `medical_history` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `username`, `password`, `email`, `phone`, `date_of_birth`, `gender`, `medical_history`, `created_at`, `updated_at`) VALUES
(1, 'patient1', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'patient1@example.com', '6661234567', '1990-01-15', 'male', 'History of hypertension.', '2024-10-01 04:37:01', '2024-10-02 17:28:59'),
(2, 'patient2', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'patient2@example.com', '6669876543', '1985-06-30', 'female', 'Asthma and allergies.', '2024-10-01 04:37:01', '2024-10-02 17:29:00'),
(3, 'patient3', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'patient3@example.com', '6663456789', '2000-12-25', 'other', 'No significant medical history.', '2024-10-01 04:37:01', '2024-10-02 17:29:04'),
(4, 'patient4', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'patient4@example.com', '6666543210', '1995-07-20', 'male', 'Diabetes type 2.', '2024-10-01 04:37:01', '2024-10-02 17:28:58'),
(5, 'patient5', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'patient5@example.com', '6663210987', '1978-04-15', 'female', 'History of depression and anxiety.', '2024-10-01 04:37:01', '2024-10-02 17:28:54'),
(6, 'patient6', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'patient6@example.com', '6669876541', '2002-11-10', 'male', 'No significant medical history.', '2024-10-01 04:37:01', '2024-10-02 17:28:53'),
(7, 'patient7', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'patient7@example.com', '6661230987', '1988-03-05', 'female', 'Gastroesophageal reflux disease (GERD).', '2024-10-01 04:37:01', '2024-10-02 17:28:51'),
(8, 'test', '$2y$10$zvTWtXdsAuuGlXNx4wSw2.ntsmg.Sc9/SPFXQViG2MuiNYN.M.VaG', 'test@example.us', '1234567890', NULL, 'other', NULL, '2024-10-02 17:28:23', '2024-10-02 17:28:23');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `patient_id`, `doctor_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, 'Dr. John is very knowledgeable and kind. Highly recommended.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(2, 2, 2, 4, 'Good experience with Dr. Jane, my childs check-up went smoothly.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(3, 3, 3, 3, 'Consultation was okay, but had to wait for a while.', '2024-10-01 04:37:01', '2024-10-01 04:37:01');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL CHECK (`price` >= 0),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `price`, `created_at`, `updated_at`) VALUES
(1, 'General Check-up', 'Routine general health examination.', '50.00', '2024-10-01 04:37:01', '2024-10-02 04:22:50'),
(2, 'Blood Test', 'Comprehensive blood testing.', '20.00', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(3, 'X-Ray', 'Imaging test for bones.', '100.00', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(4, 'MRI Scan', 'Detailed body scan using MRI technology.', '250.00', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(5, 'CT Scan', 'Advanced imaging technique to view internal organs.', '300.00', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(6, 'Ultrasound', 'Imaging technique to view internal body structures.', '80.00', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(7, 'Therapy Session', 'Mental health counseling.', '150.00', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(9, 'Bone X-Ray', 'X-ray imaging for bone fractures.', '100.00', '2024-10-01 06:27:47', '2024-10-01 06:27:47');

-- --------------------------------------------------------

--
-- Table structure for table `specialties`
--

CREATE TABLE `specialties` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon` varchar(255) DEFAULT 'fas fa-stethoscope',
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `specialties`
--

INSERT INTO `specialties` (`id`, `name`, `icon`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Cardiology', 'fas fa-heart', 'Heart and circulatory system treatments.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(2, 'Pediatrics', 'fas fa-baby', 'Child healthcare and development.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(3, 'Dermatology', 'fas fa-allergies', 'Skin and hair treatments.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(4, 'Neurology', 'fas fa-brain', 'Brain and nervous system treatments.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(5, 'Orthopedics', 'fas fa-bone', 'Bone and joint treatments.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(6, 'Psychiatry', 'fas fa-brain', 'Mental health and therapy.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(7, 'Ophthalmology', 'fas fa-eye', 'Eye care and vision treatments.', '2024-10-01 04:37:01', '2024-10-01 04:37:01'),
(8, 'Gastroenterology', 'fas fa-stomach', 'Digestive system and liver treatments.', '2024-10-01 04:37:01', '2024-10-01 04:37:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `clinic_info`
--
ALTER TABLE `clinic_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `specialty_id` (`specialty_id`);

--
-- Indexes for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `exam_results`
--
ALTER TABLE `exam_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `exam_services`
--
ALTER TABLE `exam_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medical_record_id` (`exam_result_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medical_record_id` (`exam_result_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `specialties`
--
ALTER TABLE `specialties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `clinic_info`
--
ALTER TABLE `clinic_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `exam_results`
--
ALTER TABLE `exam_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `exam_services`
--
ALTER TABLE `exam_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `specialties`
--
ALTER TABLE `specialties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`specialty_id`) REFERENCES `specialties` (`id`);

--
-- Constraints for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  ADD CONSTRAINT `doctor_schedules_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`);

--
-- Constraints for table `exam_results`
--
ALTER TABLE `exam_results`
  ADD CONSTRAINT `exam_results_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exam_services`
--
ALTER TABLE `exam_services`
  ADD CONSTRAINT `exam_services_ibfk_1` FOREIGN KEY (`exam_result_id`) REFERENCES `exam_results` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`exam_result_id`) REFERENCES `exam_results` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
