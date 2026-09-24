-- Vaccination Management System (VMS) - Schema SQL
-- Structured in 3rd Normal Form (3NF)

CREATE DATABASE IF NOT EXISTS `vms_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `vms_db`;

-- Table 1: users
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'parent', 'hospital') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 2: children
CREATE TABLE IF NOT EXISTS `children` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `parent_id` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `dob` DATE NOT NULL,
  `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 3: hospitals
CREATE TABLE IF NOT EXISTS `hospitals` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL UNIQUE, -- Links the user of role 'hospital' to their hospital record
  `hospital_name` VARCHAR(150) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `location` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 4: vaccines
CREATE TABLE IF NOT EXISTS `vaccines` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT DEFAULT NULL,
  `status` ENUM('Available', 'Unavailable') DEFAULT 'Available',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 5: appointments
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `child_id` INT NOT NULL,
  `hospital_id` INT NOT NULL,
  `vaccine_id` INT NOT NULL,
  `booking_date` DATE NOT NULL,
  `status` ENUM('pending', 'approved', 'rejected', 'vaccinated', 'not_vaccinated', 'cancelled') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`child_id`) REFERENCES `children` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`vaccine_id`) REFERENCES `vaccines` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Seed Data
-- Passwords are set to 'password' (bcrypt hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'System Administrator', 'admin@vms.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(2, 'John Doe (Parent)', 'parent@vms.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'parent'),
(3, 'City General Hospital User', 'hospital@vms.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hospital'),
(4, 'St. Jude Children Hospital User', 'stjude@vms.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hospital');

-- Insert Hospitals (linked to hospital users)
INSERT INTO `hospitals` (`id`, `user_id`, `hospital_name`, `address`, `location`) VALUES
(1, 3, 'City General Hospital', '123 Health Ave', 'Downtown'),
(2, 4, 'St. Jude Children Hospital', '456 Care Road', 'Suburbs');

-- Insert Vaccines
INSERT INTO `vaccines` (`id`, `name`, `description`, `status`) VALUES
(1, 'BCG', 'Bacillus Calmette-Guérin - Protects against tuberculosis (TB). Given at birth.', 'Available'),
(2, 'Hepatitis B', 'Prevents Hepatitis B virus infection which causes liver damage. Given at birth and 1-2 months.', 'Available'),
(3, 'OPV (Polio)', 'Oral Polio Vaccine - Protects against poliomyelitis. Given at 6 weeks, 10 weeks, 14 weeks.', 'Available'),
(4, 'DPT-HepB-Hib', 'Pentavalent vaccine protecting against Diphtheria, Pertussis, Tetanus, Hep B and Hib. Given at 6, 10, 14 weeks.', 'Available'),
(5, 'MMR', 'Measles, Mumps, and Rubella vaccine. Given at 9 months and 15 months.', 'Available'),
(6, 'Rotavirus', 'Prevents severe diarrhea and dehydration in infants. Given at 6 and 10 weeks.', 'Unavailable');

-- Insert Children (for Parent John Doe, user_id = 2)
INSERT INTO `children` (`id`, `parent_id`, `name`, `dob`, `gender`) VALUES
(1, 2, 'Emily Doe', '2026-01-15', 'Female'),
(2, 2, 'Leo Doe', '2026-05-20', 'Male');

-- Insert some Appointments
INSERT INTO `appointments` (`id`, `child_id`, `hospital_id`, `vaccine_id`, `booking_date`, `status`) VALUES
(1, 1, 1, 1, '2026-02-01', 'vaccinated'),
(2, 1, 1, 2, '2026-03-10', 'approved'),
(3, 2, 2, 1, '2026-09-30', 'pending');
