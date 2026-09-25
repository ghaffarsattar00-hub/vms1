-- =====================================================
-- VACCINATION MANAGEMENT SYSTEM - PAKISTAN LOCALIZED SEED
-- =====================================================
SET FOREIGN_KEY_CHECKS = 0;

DELETE FROM vaccination_records;
DELETE FROM appointments;
DELETE FROM hospital_vaccine_inventory;
DELETE FROM hospital_users;
DELETE FROM vaccine_doses;
DELETE FROM vaccines;
DELETE FROM children;
DELETE FROM hospitals;
DELETE FROM users;
DELETE FROM notifications;
DELETE FROM audit_logs;
DELETE FROM password_resets;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- ROLES (fresh DB ke liye - INSERT IGNORE safe hai)
-- =====================================================
-- role_id 1 = Administrator
-- role_id 2 = Parent
-- role_id 3 = Hospital Staff
INSERT IGNORE INTO roles (role_id, role_name, role_key) VALUES
(1, 'Administrator', 'admin'),
(2, 'Parent', 'parent'),
(3, 'Hospital Staff', 'hospital');

-- =====================================================
-- USERS (Admin, Parents, Hospital Staff)
-- =====================================================
INSERT INTO users (user_id, role_id, full_name, email, phone, password_hash, is_active, created_at, updated_at) VALUES
-- Admins
(1,  1, 'Dr. Ayesha Khan',       'admin@aku.edu.pk',        '+92 300 1234567', '$argon2id$v=19$m=65536,t=4,p=1$ZmYyTUgzVWZjcWNPekt2bQ$VvUPgqvpbNlqwzarxduHhboP7kAE6qOribAEm6SRXZM', 1, NOW(), NOW()),
(2,  1, 'Dr. Usman Tariq',       'usman.tariq@shifa.edu.pk','+92 321 9876543', '$argon2id$v=19$m=65536,t=4,p=1$ZmYyTUgzVWZjcWNPekt2bQ$VvUPgqvpbNlqwzarxduHhboP7kAE6qOribAEm6SRXZM', 1, NOW(), NOW()),
-- Parents
(3,  2, 'Ali Raza',              'ali.raza@gmail.com',      '+92 333 1112233', '$argon2id$v=19$m=65536,t=4,p=1$ZmYyTUgzVWZjcWNPekt2bQ$VvUPgqvpbNlqwzarxduHhboP7kAE6qOribAEm6SRXZM', 1, NOW(), NOW()),
(4,  2, 'Fatima Ahmed',          'fatima.ahmed@outlook.com','+92 300 5556677', '$argon2id$v=19$m=65536,t=4,p=1$ZmYyTUgzVWZjcWNPekt2bQ$VvUPgqvpbNlqwzarxduHhboP7kAE6qOribAEm6SRXZM', 1, NOW(), NOW()),
(5,  2, 'Hassan Malik',          'hassan.malik@yahoo.com',  '+92 312 8889900', '$argon2id$v=19$m=65536,t=4,p=1$ZmYyTUgzVWZjcWNPekt2bQ$VvUPgqvpbNlqwzarxduHhboP7kAE6qOribAEm6SRXZM', 1, NOW(), NOW()),
(6,  2, 'Saira Bibi',            'saira.bibi@hotmail.com',  '+92 345 2223344', '$argon2id$v=19$m=65536,t=4,p=1$ZmYyTUgzVWZjcWNPekt2bQ$VvUPgqvpbNlqwzarxduHhboP7kAE6qOribAEm6SRXZM', 1, NOW(), NOW()),
(7,  2, 'Omar Farooq',           'omar.farooq@gmail.com',   '+92 301 7778899', '$argon2id$v=19$m=65536,t=4,p=1$ZmYyTUgzVWZjcWNPekt2bQ$VvUPgqvpbNlqwzarxduHhboP7kAE6qOribAEm6SRXZM', 1, NOW(), NOW()),
-- Hospital Staff
(8,  3, 'Dr. Nadia Iqbal',       'nadia.iqbal@aku.edu.pk',  '+92 300 4445566', '$argon2id$v=19$m=65536,t=4,p=1$VVl6RHlTS2xJc28uV0JVYQ$LAeEN/iO8jJWWc1gt6qcAhUvl03c3GhDExWQJJJ1c3Y', 1, NOW(), NOW()),
(9,  3, 'Dr. Imran Shah',        'imran.shah@shifa.edu.pk', '+92 321 3334455', '$argon2id$v=19$m=65536,t=4,p=1$VVl6RHlTS2xJc28uV0JVYQ$LAeEN/iO8jJWWc1gt6qcAhUvl03c3GhDExWQJJJ1c3Y', 1, NOW(), NOW()),
(10, 3, 'Dr. Sana Qureshi',      'sana.qureshi@jinnah.edu.pk','+92 333 6667788','$argon2id$v=19$m=65536,t=4,p=1$VVl6RHlTS2xJc28uV0JVYQ$LAeEN/iO8jJWWc1gt6qcAhUvl03c3GhDExWQJJJ1c3Y',1, NOW(), NOW());

-- =====================================================
-- HOSPITALS
-- =====================================================
INSERT INTO hospitals (hospital_id, name, registration_number, email, phone, address_line1, address_line2, city, state, postal_code, is_active, created_by, created_at, updated_at) VALUES
(1, 'Aga Khan University Hospital', 'AKU-REG-001', 'info@aku.edu.pk',    '+92 21 34861000', 'Stadium Road, Karimabad',  NULL, 'Karachi',  'Sindh',       '74800', 1, 1, NOW(), NOW()),
(2, 'Shifa International Hospital', 'SIH-REG-002', 'info@shifa.edu.pk',  '+92 51 8463000', 'Plot 1, Shifa International Hospital Road', 'Sector H-8/1', 'Islamabad', 'ICT',       '44000', 1, 1, NOW(), NOW()),
(3, 'Jinnah Hospital Lahore',       'JHL-REG-003', 'info@jinnah.edu.pk', '+92 42 99201000', 'Mozang Road, near Lakshmi Chowk', NULL, 'Lahore', 'Punjab',       '54000', 1, 1, NOW(), NOW());

-- =====================================================
-- HOSPITAL USERS (link hospital staff to hospitals)
-- =====================================================
INSERT INTO hospital_users (hospital_user_id, hospital_id, user_id, is_primary, is_active, created_at) VALUES
(1, 1, 8, 1, 1, NOW()),
(2, 2, 9, 1, 1, NOW()),
(3, 3, 10, 1, 1, NOW());

-- =====================================================
-- VACCINES
-- =====================================================
INSERT INTO vaccines (vaccine_id, name, targeted_disease, manufacturer, description, is_required, is_active, created_at, updated_at) VALUES
(1, 'BCG',                   'Tuberculosis (TB)',           'Serum Institute of India', 'Bacillus Calmette-Guerin vaccine for TB prevention. Administered at birth.', 1, 1, NOW(), NOW()),
(2, 'Hepatitis B',           'Hepatitis B infection',       'SynCo Bio Partners',      'Protects against Hepatitis B virus. Multi-dose schedule from birth.', 1, 1, NOW(), NOW()),
(3, 'Oral Polio Vaccine (OPV)', 'Poliomyelitis',            'Sanofi Pasteur',          'Oral polio vaccine. Given at 6, 10, and 14 weeks.', 1, 1, NOW(), NOW()),
(4, 'DPT',                   'Diphtheria, Pertussis, Tetanus','GlaxoSmithKline',       'Triple antigen vaccine. Given at 6, 10, and 14 weeks.', 1, 1, NOW(), NOW()),
(5, 'MMR',                   'Measles, Mumps, Rubella',     'Merck & Co.',             'Combined measles, mumps and rubella vaccine. Given at 9 and 15 months.', 1, 1, NOW(), NOW()),
(6, 'Rotavirus',             'Rotavirus diarrhoea',          'Bharat Biotech',          'Oral rotavirus vaccine for infantile diarrhoea prevention.', 0, 1, NOW(), NOW()),
(7, 'PCV (Pneumococcal)',    'Pneumococcal disease',        'Pfizer',                  'Pneumococcal conjugate vaccine. Given at 6, 10, 14 weeks and booster at 12 months.', 0, 1, NOW(), NOW());

-- =====================================================
-- VACCINE DOSES
-- =====================================================
INSERT INTO vaccine_doses (dose_id, vaccine_id, dose_number, min_age_months, max_age_months, interval_days_from_previous, description, created_at) VALUES
-- BCG
(1,  1, 1, 0.00, 1.00,  NULL, 'BCG - at birth', NOW()),
-- Hepatitis B
(2,  2, 1, 0.00, 1.00,  NULL, 'Hep B - 1st dose at birth', NOW()),
(3,  2, 2, 1.50, 3.00,  42,   'Hep B - 2nd dose at 6 weeks', NOW()),
(4,  2, 3, 5.50, 7.00,  28,   'Hep B - 3rd dose at 14 weeks', NOW()),
-- OPV
(5,  3, 1, 1.50, 3.00,  42,   'OPV - 1st dose at 6 weeks', NOW()),
(6,  3, 2, 2.50, 4.00,  28,   'OPV - 2nd dose at 10 weeks', NOW()),
(7,  3, 3, 3.50, 5.00,  28,   'OPV - 3rd dose at 14 weeks', NOW()),
-- DPT
(8,  4, 1, 1.50, 3.00,  42,   'DPT - 1st dose at 6 weeks', NOW()),
(9,  4, 2, 2.50, 4.00,  28,   'DPT - 2nd dose at 10 weeks', NOW()),
(10, 4, 3, 3.50, 5.00,  28,   'DPT - 3rd dose at 14 weeks', NOW()),
-- MMR
(11, 5, 1, 9.00, 12.00, NULL, 'MMR - 1st dose at 9 months', NOW()),
(12, 5, 2, 15.00,18.00, 210,  'MMR - 2nd dose at 15 months', NOW()),
-- Rotavirus
(13, 6, 1, 1.50, 3.00,  42,   'Rotavirus - 1st dose at 6 weeks', NOW()),
(14, 6, 2, 2.50, 4.00,  28,   'Rotavirus - 2nd dose at 10 weeks', NOW()),
-- PCV
(15, 7, 1, 1.50, 3.00,  42,   'PCV - 1st dose at 6 weeks', NOW()),
(16, 7, 2, 2.50, 4.00,  28,   'PCV - 2nd dose at 10 weeks', NOW()),
(17, 7, 3, 3.50, 5.00,  28,   'PCV - 3rd dose at 14 weeks', NOW()),
(18, 7, 4, 11.00,13.00, NULL, 'PCV - booster at 12 months', NOW());

-- =====================================================
-- HOSPITAL VACCINE INVENTORY
-- =====================================================
INSERT INTO hospital_vaccine_inventory (inventory_id, hospital_id, vaccine_id, available_stock, reorder_level, is_available, last_restocked_at, notes, updated_by, created_at, updated_at) VALUES
-- Aga Khan Hospital Karachi
(1,  1, 1, 120, 20, 1, '2026-09-01 09:00:00', NULL, 1, NOW(), NOW()),
(2,  1, 2, 95,  20, 1, '2026-09-01 09:00:00', NULL, 1, NOW(), NOW()),
(3,  1, 3, 200, 30, 1, '2026-09-01 09:00:00', NULL, 1, NOW(), NOW()),
(4,  1, 4, 150, 25, 1, '2026-09-01 09:00:00', NULL, 1, NOW(), NOW()),
(5,  1, 5, 80,  15, 1, '2026-09-01 09:00:00', NULL, 1, NOW(), NOW()),
(6,  1, 6, 60,  10, 1, '2026-09-01 09:00:00', NULL, 1, NOW(), NOW()),
(7,  1, 7, 70,  10, 1, '2026-09-01 09:00:00', NULL, 1, NOW(), NOW()),
-- Shifa International Islamabad
(8,  2, 1, 85,  15, 1, '2026-09-05 10:00:00', NULL, 1, NOW(), NOW()),
(9,  2, 2, 110, 20, 1, '2026-09-05 10:00:00', NULL, 1, NOW(), NOW()),
(10, 2, 3, 180, 30, 1, '2026-09-05 10:00:00', NULL, 1, NOW(), NOW()),
(11, 2, 4, 130, 25, 1, '2026-09-05 10:00:00', NULL, 1, NOW(), NOW()),
(12, 2, 5, 50,  15, 0, '2026-08-20 09:00:00', 'Low stock pending restock', 1, NOW(), NOW()),
(13, 2, 6, 40,  10, 1, '2026-09-05 10:00:00', NULL, 1, NOW(), NOW()),
(14, 2, 7, 55,  10, 1, '2026-09-05 10:00:00', NULL, 1, NOW(), NOW()),
-- Jinnah Hospital Lahore
(15, 3, 1, 90,  15, 1, '2026-09-10 08:30:00', NULL, 1, NOW(), NOW()),
(16, 3, 2, 75,  20, 1, '2026-09-10 08:30:00', NULL, 1, NOW(), NOW()),
(17, 3, 3, 160, 30, 1, '2026-09-10 08:30:00', NULL, 1, NOW(), NOW()),
(18, 3, 4, 100, 25, 1, '2026-09-10 08:30:00', NULL, 1, NOW(), NOW()),
(19, 3, 5, 65,  15, 1, '2026-09-10 08:30:00', NULL, 1, NOW(), NOW()),
(20, 3, 6, 0,   10, 0, '2026-08-01 09:00:00', 'Out of stock', 1, NOW(), NOW()),
(21, 3, 7, 45,  10, 1, '2026-09-10 08:30:00', NULL, 1, NOW(), NOW());

-- =====================================================
-- CHILDREN
-- =====================================================
INSERT INTO children (child_id, parent_user_id, first_name, last_name, date_of_birth, gender, blood_group, birth_registration_number, allergies, medical_notes, is_active, created_at, updated_at) VALUES
-- Ali Raza's children (user 3)
(1,  3, 'Zainab',   'Raza',     '2026-01-15', 'Female', 'A+',  'KHI-2026-00101', 'None',       'Healthy, no complications at birth',  1, NOW(), NOW()),
(2,  3, 'Ahmed',    'Raza',     '2026-06-20', 'Male',   'B+',  'KHI-2026-00202', 'Milk allergy','Requires lactose-free formula',      1, NOW(), NOW()),
-- Fatima Ahmed's children (user 4)
(3,  4, 'Ibrahim',  'Ahmed',    '2025-11-10', 'Male',   'O+',  'ISB-2025-00303', 'None',       'Premature birth (34 weeks), under monitoring', 1, NOW(), NOW()),
(4,  4, 'Maryam',   'Ahmed',    '2026-04-05', 'Female', 'AB+', 'ISB-2025-00404', 'None',       'Normal delivery, healthy',            1, NOW(), NOW()),
-- Hassan Malik's children (user 5)
(5,  5, 'Bilal',    'Malik',    '2026-02-28', 'Male',   'A-',  'LHR-2026-00505', 'Dust allergy','Mild eczema, under dermatologist care', 1, NOW(), NOW()),
-- Saira Bibi's children (user 6)
(6,  6, 'Ayesha',   'Farooq',   '2025-09-12', 'Female', 'B-',  'KHI-2025-00606', 'None',       'Healthy child, regular checkups',     1, NOW(), NOW()),
-- Omar Farooq's children (user 7)
(7,  7, 'Hammad',   'Farooq',   '2026-03-18', 'Male',   'O-',  'LHR-2026-00707', 'Penicillin', 'Allergic to penicillin-based antibiotics', 1, NOW(), NOW()),
(8,  7, 'Zara',     'Farooq',   '2026-07-01', 'Female', 'A+',  'LHR-2026-00808', 'None',       'Born via C-section, recovery normal', 1, NOW(), NOW());

-- =====================================================
-- APPOINTMENTS
-- =====================================================
INSERT INTO appointments (appointment_id, child_id, hospital_id, dose_id, scheduled_date, status, admin_notes, hospital_notes, requested_at, admin_decision_at, treated_at, created_at, updated_at) VALUES
-- Zainab Raza - AKU Karachi
(1,  1, 1, 1,  '2026-02-01', 'vaccinated',  NULL, 'BCG administered successfully', '2026-01-20 10:00:00', '2026-01-21 09:00:00', '2026-02-01 10:30:00', NOW(), NOW()),
(2,  1, 1, 2,  '2026-02-15', 'vaccinated',  NULL, 'Hep B 1st dose given',          '2026-02-01 11:00:00', '2026-02-02 09:00:00', '2026-02-15 11:00:00', NOW(), NOW()),
(3,  1, 1, 5,  '2026-07-15', 'approved',    NULL, NULL,                              '2026-07-01 09:00:00', '2026-07-02 10:00:00', NULL, NOW(), NOW()),
-- Ahmed Raza - AKU Karachi
(4,  2, 1, 1,  '2026-07-20', 'vaccinated',  NULL, 'BCG given, no adverse reaction',  '2026-07-10 08:00:00', '2026-07-11 09:00:00', '2026-07-20 10:00:00', NOW(), NOW()),
(5,  2, 1, 8,  '2026-10-20', 'pending',     NULL, NULL,                              '2026-10-01 07:00:00', NULL, NULL, NOW(), NOW()),
-- Ibrahim Ahmed - Shifa Islamabad
(6,  3, 2, 1,  '2026-02-10', 'vaccinated',  NULL, 'Given at birth, premature baby stable', '2026-01-25 10:00:00', '2026-01-26 09:00:00', '2026-02-10 09:30:00', NOW(), NOW()),
(7,  3, 2, 5,  '2026-08-10', 'approved',    NULL, NULL,                              '2026-08-01 08:00:00', '2026-08-02 10:00:00', NULL, NOW(), NOW()),
-- Maryam Ahmed - Shifa Islamabad
(8,  4, 2, 2,  '2026-09-05', 'pending',     NULL, NULL,                              '2026-09-01 09:00:00', NULL, NULL, NOW(), NOW()),
-- Bilal Malik - Jinnah Lahore
(9,  5, 3, 1,  '2026-03-30', 'vaccinated',  NULL, 'BCG given, monitored for 30 min', '2026-03-15 10:00:00', '2026-03-16 09:00:00', '2026-03-30 10:30:00', NOW(), NOW()),
(10, 5, 3, 8,  '2026-09-30', 'pending',     NULL, NULL,                              '2026-09-20 08:00:00', NULL, NULL, NOW(), NOW()),
-- Ayesha Farooq - AKU Karachi
(11, 6, 1, 11, '2026-09-12', 'vaccinated',  NULL, 'MMR 1st dose given on 1st birthday', '2026-09-01 10:00:00', '2026-09-02 09:00:00', '2026-09-12 11:00:00', NOW(), NOW()),
-- Hammad Farooq - Jinnah Lahore
(12, 7, 3, 1,  '2026-04-18', 'vaccinated',  NULL, 'BCG administered, penicillin allergy noted', '2026-04-05 09:00:00', '2026-04-06 10:00:00', '2026-04-18 10:00:00', NOW(), NOW()),
(13, 7, 3, 5,  '2026-10-18', 'rejected',    'Schedule conflict - child has fever', NULL, '2026-10-01 08:00:00', '2026-10-02 09:00:00', NULL, NOW(), NOW()),
-- Zara Farooq - Shifa Islamabad
(14, 8, 2, 1,  '2026-08-01', 'approved',    NULL, NULL,                              '2026-07-20 10:00:00', '2026-07-21 09:00:00', NULL, NOW(), NOW()),
-- Zainab Raza follow-up
(15, 1, 1, 15, '2026-10-15', 'pending',     NULL, NULL,                              '2026-10-01 11:00:00', NULL, NULL, NOW(), NOW());

-- =====================================================
-- VACCINATION RECORDS (for vaccinated appointments)
-- =====================================================
INSERT INTO vaccination_records (record_id, child_id, dose_id, hospital_id, administered_by, appointment_id, administered_at, batch_number, next_due_date, notes, created_at, updated_at) VALUES
(1,  1, 1,  1, 8, 1,  '2026-02-01 10:30:00', 'AKU-BCG-2026-001', '2026-02-15', 'BCG administered on left upper arm', NOW(), NOW()),
(2,  1, 2,  1, 8, 2,  '2026-02-15 11:00:00', 'AKU-HEPB-2026-01','2026-07-15', 'Hep B 1st dose, no reaction', NOW(), NOW()),
(3,  2, 1,  1, 8, 4,  '2026-07-20 10:00:00', 'AKU-BCG-2026-045', '2026-07-20', 'BCG given, baby cried briefly', NOW(), NOW()),
(4,  3, 1,  2, 9, 6,  '2026-02-10 09:30:00', 'SIH-BCG-2026-012', '2026-03-10', 'Given to premature baby, stable', NOW(), NOW()),
(5,  5, 1,  3, 10, 9, '2026-03-30 10:30:00', 'JHL-BCG-2026-078', '2026-04-30', 'Monitored 30 min post-admin', NOW(), NOW()),
(6,  6, 11, 1, 8, 11, '2026-09-12 11:00:00', 'AKU-MMR-2026-033', '2027-03-12', 'MMR given on 1st birthday', NOW(), NOW()),
(7,  7, 1,  3, 10, 12,'2026-04-18 10:00:00', 'JHL-BCG-2026-091', '2026-05-18', 'Penicillin allergy flagged in record', NOW(), NOW());
