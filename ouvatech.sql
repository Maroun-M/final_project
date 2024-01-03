-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 16, 2023 at 03:10 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ouvatech`
--

-- --------------------------------------------------------

--
-- Table structure for table `blood_glucose`
--

DROP TABLE IF EXISTS `blood_glucose`;
CREATE TABLE IF NOT EXISTS `blood_glucose` (
  `record_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `glucose_level` decimal(5,2) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`record_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `blood_glucose`
--

INSERT INTO `blood_glucose` (`record_id`, `user_id`, `glucose_level`, `timestamp`, `status`) VALUES
(76, 9, '60.00', '2023-06-12 15:55:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blood_oxygen`
--

DROP TABLE IF EXISTS `blood_oxygen`;
CREATE TABLE IF NOT EXISTS `blood_oxygen` (
  `record_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `percentage` float DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`record_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `blood_oxygen`
--

INSERT INTO `blood_oxygen` (`record_id`, `user_id`, `percentage`, `timestamp`) VALUES
(5, 28, 98, '2023-06-12 16:50:11');

-- --------------------------------------------------------

--
-- Table structure for table `blood_pressure`
--

DROP TABLE IF EXISTS `blood_pressure`;
CREATE TABLE IF NOT EXISTS `blood_pressure` (
  `record_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `systolic` int DEFAULT NULL,
  `diastolic` int DEFAULT NULL,
  PRIMARY KEY (`record_id`),
  KEY `fk_patient` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `blood_pressure`
--

INSERT INTO `blood_pressure` (`record_id`, `user_id`, `timestamp`, `systolic`, `diastolic`) VALUES
(22, 9, '2023-06-12 18:13:20', 120, 80),
(23, 9, '2023-06-12 18:13:24', 120, 80),
(24, 9, '2023-06-12 18:13:27', 120, 80);

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

DROP TABLE IF EXISTS `doctors`;
CREATE TABLE IF NOT EXISTS `doctors` (
  `doctor_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `location` varchar(255) NOT NULL,
  `education` varchar(255) NOT NULL,
  `clinic_number` varchar(20) NOT NULL,
  `clinic_name` varchar(255) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  PRIMARY KEY (`doctor_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `user_id`, `location`, `education`, `clinic_number`, `clinic_name`, `date_of_birth`) VALUES
(4, 74, 'Location6193', 'Education2299', '291', 'Clinic7676', '1996-12-25'),
(5, 75, 'Location5163', 'Education6899', '900', 'Clinic4344', '2010-07-05'),
(7, 9, 'Aley', 'PHD IN ....', '81838298', 'CLINIC', '2001-02-06');

-- --------------------------------------------------------

--
-- Table structure for table `fetus`
--

DROP TABLE IF EXISTS `fetus`;
CREATE TABLE IF NOT EXISTS `fetus` (
  `record_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `gestational_age` int NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `heart_rate` int NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`record_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `heart_rate`
--

DROP TABLE IF EXISTS `heart_rate`;
CREATE TABLE IF NOT EXISTS `heart_rate` (
  `record_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `BPM` int DEFAULT NULL,
  PRIMARY KEY (`record_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `heart_rate`
--

INSERT INTO `heart_rate` (`record_id`, `user_id`, `timestamp`, `BPM`) VALUES
(4, 9, '2023-06-12 18:22:55', 85),
(5, 9, '2023-06-12 18:22:59', 86);

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
CREATE TABLE IF NOT EXISTS `patients` (
  `patient_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `previous_pregnancies` tinyint(1) DEFAULT NULL,
  `pregnancy_stage` int DEFAULT NULL,
  `diabetic` tinyint(1) DEFAULT '0',
  `hypertension` tinyint(1) DEFAULT '0',
  `LMP` date DEFAULT NULL,
  `EDD` date DEFAULT NULL,
  `gestational_age` int DEFAULT NULL,
  PRIMARY KEY (`patient_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `user_id`, `location`, `date_of_birth`, `previous_pregnancies`, `pregnancy_stage`, `diabetic`, `hypertension`, `LMP`, `EDD`, `gestational_age`) VALUES
(1, 9, 'Beirut', '2020-04-18', 1, 2, 0, 0, '2023-01-18', '2023-10-31', 122),
(9, 28, 'lebanon', '2020-04-18', 1, 2, 0, 0, '2023-01-18', '2023-10-31', 124);

-- --------------------------------------------------------

--
-- Table structure for table `patient_doctor`
--

DROP TABLE IF EXISTS `patient_doctor`;
CREATE TABLE IF NOT EXISTS `patient_doctor` (
  `patient_id` int NOT NULL,
  `doctor_id` int NOT NULL,
  PRIMARY KEY (`patient_id`,`doctor_id`),
  KEY `doctor_id` (`doctor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `patient_doctor`
--

INSERT INTO `patient_doctor` (`patient_id`, `doctor_id`) VALUES
(1, 4),
(9, 7);

-- --------------------------------------------------------

--
-- Table structure for table `resend_activation_counts`
--

DROP TABLE IF EXISTS `resend_activation_counts`;
CREATE TABLE IF NOT EXISTS `resend_activation_counts` (
  `user_id` int NOT NULL,
  `resend_count` int DEFAULT '0',
  `last_resend_timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `resend_activation_counts`
--

INSERT INTO `resend_activation_counts` (`user_id`, `resend_count`, `last_resend_timestamp`) VALUES
(9, 3, '2023-06-15 16:34:45');

-- --------------------------------------------------------

--
-- Table structure for table `temperature`
--

DROP TABLE IF EXISTS `temperature`;
CREATE TABLE IF NOT EXISTS `temperature` (
  `record_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `temp` decimal(5,2) NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`record_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

DROP TABLE IF EXISTS `tests`;
CREATE TABLE IF NOT EXISTS `tests` (
  `ID` int NOT NULL,
  `test_name` varchar(10) NOT NULL,
  `hi_1st` int NOT NULL,
  `hi_2nd` int NOT NULL,
  `hi_3rd` int NOT NULL,
  `lo_1st` int NOT NULL,
  `lo_2nd` int NOT NULL,
  `lo_3rd` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`ID`, `test_name`, `hi_1st`, `hi_2nd`, `hi_3rd`, `lo_1st`, `lo_2nd`, `lo_3rd`) VALUES
(1, 'hr_bp', 120, 110, 100, 60, 50, 55);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) NOT NULL,
  `account_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `confirmation_code` varchar(255) NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `access_level` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `phone_number`, `email`, `account_password`, `confirmation_code`, `confirmed`, `created_at`, `access_level`) VALUES
(9, 'Maroun', 'Mourad', '+961 81838298', 'maroun233243@gmail.com', '$2y$12$LXTdTJ20ukQxRlI6CXKSB.7ropRaTgR6VngF5GuV90B.65UDs0T/K', 'S9PMQZ', 1, '2023-04-03 17:22:41', 1),
(28, 'Maroun', 'Mourad', '+961 81838', 'maroun360p@gmail.com', '$2y$12$457/50.li5rMbEjQABvO0O1Cat45RMhzks/BqqvqzeUnYo7LmnV2u', '16vOVB', 0, '2023-04-06 11:02:18', 1),
(30, 'Maroun', 'Mourad', '+961 81838', 'maroun233245@gmail.com', '$2y$12$3RmwGAxNo4lV5XwuyN3C0OW71cFRO/pfooZkcBIjr9zrBVnNakLRS', 'h9xBKM', 0, '2023-05-03 19:00:09', 2),
(74, 'First5725', 'Last1289', '555-555-9271', 'user2490@example.com', 'ecd1ffd6a744d742ce8c110b989d7f32', '5715', 0, '2022-10-03 05:58:22', 2),
(75, 'First6054', 'Last9509', '555-555-9381', 'user8379@example.com', '8423d245459cf11530b9c583800f74ea', '3631', 1, '2023-01-06 06:58:22', 2),
(78, 'Maroun', 'Mourad', '81838298', 'maroun4k@gmail.com', '$2y$12$CTdM7oJlD5ejhb4GXpbAVOJi5/CjFCsnnvpUC5e7f7Xwm88SQJfpu', '', 1, '2023-06-12 17:19:15', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_files`
--

DROP TABLE IF EXISTS `user_files`;
CREATE TABLE IF NOT EXISTS `user_files` (
  `record_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `file_path` varchar(255) NOT NULL,
  PRIMARY KEY (`record_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blood_glucose`
--
ALTER TABLE `blood_glucose`
  ADD CONSTRAINT `fk_glucose_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blood_oxygen`
--
ALTER TABLE `blood_oxygen`
  ADD CONSTRAINT `fk_oxygen_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blood_pressure`
--
ALTER TABLE `blood_pressure`
  ADD CONSTRAINT `fk_hrbp_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `fk_doctors_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fetus`
--
ALTER TABLE `fetus`
  ADD CONSTRAINT `fk_fetus_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_patient_fetus_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `heart_rate`
--
ALTER TABLE `heart_rate`
  ADD CONSTRAINT `heart_rate_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `fk_patients_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_doctor`
--
ALTER TABLE `patient_doctor`
  ADD CONSTRAINT `fk_doctor_user_id` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_patient_user_id` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE;

--
-- Constraints for table `temperature`
--
ALTER TABLE `temperature`
  ADD CONSTRAINT `fk_temp_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_files`
--
ALTER TABLE `user_files`
  ADD CONSTRAINT `fk_files_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

DELIMITER $$
--
-- Events
--
DROP EVENT IF EXISTS `update_gestational_age_patients`$$
CREATE DEFINER=`root`@`localhost` EVENT `update_gestational_age_patients` ON SCHEDULE EVERY 1 DAY STARTS '2023-05-19 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE patients
    SET gestational_age = gestational_age + 1,
        pregnancy_stage = CASE
            WHEN (gestational_age + 1) <= 84 THEN 1
            WHEN (gestational_age + 1) <= 168 THEN 2
            ELSE 3
        END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
