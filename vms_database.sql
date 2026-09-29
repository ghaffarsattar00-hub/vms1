-- ============================================================
-- VMS - Advanced Immunization System
-- Database Backup (vms_database.sql)
-- Core tables: users, hospitals, vaccines, appointments
-- MySQL 8 / MariaDB | utf8mb4
--
-- Note: This dump contains the four core tables requested for
-- the eProject submission, with sample seed data. The FULL
-- database dump (all 12 tables + complete seed) is available in
-- database/schema_hosting.sql + database/seed_pakistan.sql.
-- Foreign keys referencing tables outside this dump (roles,
-- children, vaccine_doses) are noted in comments.
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------
-- Table: roles (lookup required by users.role_id)
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `role_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `role_key` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `uq_roles_name` (`role_name`),
  UNIQUE KEY `uq_roles_key` (`role_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`role_id`, `role_name`, `role_key`) VALUES
(1, 'Administrator', 'admin'),
(2, 'Parent', 'parent'),
(3, 'Hospital', 'hospital');

-- -----------------------------------------------------------
-- Table: users
-- FK: role_id -> roles.role_id (included above)
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` tinyint(3) unsigned NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` datetime DEFAULT NULL,
  `terms_accepted_at` datetime DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_role` (`role_id`),
  KEY `idx_users_reset_token` (`reset_token`),
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample accounts (passwords: admin/parent = "password", hospitals = "hospital123")
INSERT INTO `users` (`user_id`, `role_id`, `full_name`, `email`, `phone`, `password_hash`, `is_active`) VALUES
(1, 1, 'Dr. Ayesha Khan', 'admin@aku.edu.pk', '+92-21-34861000', '$argon2id$v=19$m=65536,t=4,p=1$ZmYyTUgzVWZjcWNPekt2bQ$VvUPgqvpbNlqwzarxduHhboP7kAE6qOribAEm6SRXZM', 1),
(2, 1, 'Dr. Usman Tariq', 'usman.tariq@shifa.edu.pk', '+92-51-4862000', '$argon2id$v=19$m=65536,t=4,p=1$ZmYyTUgzVWZjcWNPekt2bQ$VvUPgqvpbNlqwzarxduHhboP7kAE6qOribAEm6SRXZM', 1),
(3, 2, 'Ali Raza', 'ali.raza@gmail.com', '+92-300-1234567', '$argon2id$v=19$m=65536,t=4,p=1$ZmYyTUgzVWZjcWNPekt2bQ$VvUPgqvpbNlqwzarxduHhboP7kAE6qOribAEm6SRXZM', 1),
(4, 2, 'Fatima Ahmed', 'fatima.ahmed@outlook.com', '+92-321-9876543', '$argon2id$v=19$m=65536,t=4,p=1$ZmYyTUgzVWZjcWNPekt2bQ$VvUPgqvpbNlqwzarxduHhboP7kAE6qOribAEm6SRXZM', 1),
(5, 2, 'Hassan Malik', 'hassan.malik@yahoo.com', '+92-333-5556667', '$argon2id$v=19$m=65536,t=4,p=1$ZmYyTUgzVWZjcWNPekt2bQ$VvUPgqvpbNlqwzarxduHhboP7kAE6qOribAEm6SRXZM', 1),
(6, 2, 'Saira Bibi', 'saira.bibi@hotmail.com', '+92-345-1112223', '$argon2id$v=19$m=65536,t=4,p=1$ZmYyTUgzVWZjcWNPekt2bQ$VvUPgqvpbNlqwzarxduHhboP7kAE6qOribAEm6SRXZM', 1),
(7, 2, 'Omar Farooq', 'omar.farooq@gmail.com', '+92-301-4445556', '$argon2id$v=19$m=65536,t=4,p=1$ZmYyTUgzVWZjcWNPekt2bQ$VvUPgqvpbNlqwzarxduHhboP7kAE6qOribAEm6SRXZM', 1),
(8, 3, 'Dr. Nadia Iqbal', 'nadia.iqbal@aku.edu.pk', '+92-21-34861000', '$2y$12$nIZWeHOuT/LzoCAcrB4xSeMdUNcE.3vChQtv8gLL8UIyeZDNjWJRC', 1),
(9, 3, 'Dr. Imran Shah', 'imran.shah@shifa.edu.pk', '+92-51-4862000', '$2y$12$nIZWeHOuT/LzoCAcrB4xSeMdUNcE.3vChQtv8gLL8UIyeZDNjWJRC', 1),
(10, 3, 'Dr. Sana Qureshi', 'sana.qureshi@jinnah.edu.pk', '+92-42-35394000', '$2y$12$nIZWeHOuT/LzoCAcrB4xSeMdUNcE.3vChQtv8gLL8UIyeZDNjWJRC', 1),
(11, 3, 'Aga Khan University Hospital', 'info@aku.edu.pk', '+92-21-34861000', '$2y$12$nIZWeHOuT/LzoCAcrB4xSeMdUNcE.3vChQtv8gLL8UIyeZDNjWJRC', 1),
(12, 3, 'Shifa International Hospital', 'info@shifa.edu.pk', '+92-51-4862000', '$2y$12$nIZWeHOuT/LzoCAcrB4xSeMdUNcE.3vChQtv8gLL8UIyeZDNjWJRC', 1),
(13, 3, 'Jinnah Hospital Lahore', 'info@jinnah.edu.pk', '+92-42-35394000', '$2y$12$nIZWeHOuT/LzoCAcrB4xSeMdUNcE.3vChQtv8gLL8UIyeZDNjWJRC', 1),
(14, 3, 'PNS SHIFA', 'navyshifa@pns.net', '+92-51-4851000', '$2y$12$nIZWeHOuT/LzoCAcrB4xSeMdUNcE.3vChQtv8gLL8UIyeZDNjWJRC', 1);

-- -----------------------------------------------------------
-- Table: hospitals
-- FK: created_by -> users.user_id (ON DELETE SET NULL)
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `hospitals`;
CREATE TABLE `hospitals` (
  `hospital_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `registration_number` varchar(60) DEFAULT NULL,
  `email` varchar(190) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `address_line1` varchar(190) NOT NULL,
  `address_line2` varchar(190) DEFAULT NULL,
  `city` varchar(80) NOT NULL,
  `state` varchar(80) NOT NULL,
  `postal_code` varchar(15) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`hospital_id`),
  UNIQUE KEY `uq_hospitals_name` (`name`),
  KEY `idx_hospitals_city` (`city`),
  KEY `idx_hospitals_active` (`is_active`),
  KEY `idx_hospitals_created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `hospitals` (`hospital_id`, `name`, `registration_number`, `email`, `phone`, `address_line1`, `city`, `state`, `is_active`, `created_by`) VALUES
(1, 'Aga Khan University Hospital', 'AKU-001', 'info@aku.edu.pk', '+92-21-34861000', 'Stadium Road', 'Karachi', 'Sindh', 1, 1),
(2, 'Shifa International Hospital', 'SHIFA-002', 'info@shifa.edu.pk', '+92-51-4862000', 'Plot 112, Park Road', 'Islamabad', 'Islamabad Capital Territory', 1, 1),
(3, 'Jinnah Hospital Lahore', 'JHL-003', 'info@jinnah.edu.pk', '+92-42-35394000', 'Queens Road', 'Lahore', 'Punjab', 1, 1),
(4, 'PNS Shifa', 'PNS-004', 'navyshifa@pns.net', '+92-51-4851000', 'Mall Road', 'Islamabad', 'Islamabad Capital Territory', 1, 1);

-- -----------------------------------------------------------
-- Table: vaccines
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `vaccines`;
CREATE TABLE `vaccines` (
  `vaccine_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `targeted_disease` varchar(150) NOT NULL,
  `manufacturer` varchar(120) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`vaccine_id`),
  UNIQUE KEY `uq_vaccines_name` (`name`),
  KEY `idx_vaccines_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `vaccines` (`vaccine_id`, `name`, `targeted_disease`, `manufacturer`, `is_required`, `is_active`) VALUES
(1, 'BCG', 'Tuberculosis', 'Serum Institute of Pakistan', 1, 1),
(2, 'OPV', 'Poliomyelitis (Polio)', 'National Institute of Health', 1, 1),
(3, 'IPV', 'Poliomyelitis (Polio)', 'Sanofi Pasteur', 1, 1),
(4, 'Pentavalent', 'Diphtheria / Tetanus / Pertussis / Hep-B / Hib', 'Serum Institute of Pakistan', 1, 1),
(5, 'PCV', 'Pneumococcal Disease', 'GSK', 1, 1),
(6, 'Measles-Rubella', 'Measles / Rubella', 'Serum Institute of Pakistan', 1, 1),
(7, 'Rotavirus', 'Rotavirus Diarrhea', 'Serum Institute of Pakistan', 1, 1);

-- -----------------------------------------------------------
-- Table: appointments
-- FK: child_id -> children.child_id (table in full dump)
-- FK: dose_id -> vaccine_doses.dose_id (table in full dump)
-- FK: hospital_id -> hospitals.hospital_id (included above)
-- FK: cancelled_by -> users.user_id (included above)
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `appointments`;
CREATE TABLE `appointments` (
  `appointment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `child_id` int(10) unsigned NOT NULL,
  `hospital_id` int(10) unsigned NOT NULL,
  `dose_id` int(10) unsigned NOT NULL,
  `scheduled_date` date NOT NULL,
  `status` enum('pending','approved','rejected','vaccinated','not_vaccinated','cancelled') DEFAULT 'pending',
  `admin_notes` varchar(500) DEFAULT NULL,
  `hospital_notes` varchar(500) DEFAULT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_decision_at` datetime DEFAULT NULL,
  `treated_at` datetime DEFAULT NULL,
  `cancelled_by` int(10) unsigned DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `cancel_reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`appointment_id`),
  KEY `idx_appt_child` (`child_id`),
  KEY `idx_appt_hospital` (`hospital_id`),
  KEY `idx_appt_dose` (`dose_id`),
  KEY `idx_appt_status` (`status`),
  KEY `idx_appt_scheduled_date` (`scheduled_date`),
  KEY `idx_appt_hospital_status_date` (`hospital_id`,`status`,`scheduled_date`),
  KEY `idx_appt_status_date` (`status`,`scheduled_date`),
  KEY `idx_appt_cancelled_by` (`cancelled_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample appointments (child_id/dose_id reference children + vaccine_doses from the full dump)
INSERT INTO `appointments` (`appointment_id`, `child_id`, `hospital_id`, `dose_id`, `scheduled_date`, `status`, `treated_at`) VALUES
(1, 1, 1, 1, '2026-09-15', 'vaccinated', '2026-09-15 10:30:00'),
(2, 1, 1, 2, '2026-10-15', 'approved', NULL),
(3, 2, 2, 1, '2026-09-20', 'vaccinated', '2026-09-20 11:00:00'),
(4, 3, 3, 3, '2026-10-02', 'pending', NULL),
(5, 4, 4, 4, '2026-10-05', 'pending', NULL);

SET FOREIGN_KEY_CHECKS = 1;

-- Dump completed.
