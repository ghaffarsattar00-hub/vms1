-- ============================================================
-- Migration: Password Reset columns on users table
-- Run this ONCE on an existing database (local or phpMyAdmin).
-- Fresh installs already include these columns in schema_hosting.sql.
-- ============================================================
ALTER TABLE `users`
  ADD COLUMN `reset_token` VARCHAR(64) DEFAULT NULL,
  ADD COLUMN `reset_expires` DATETIME DEFAULT NULL,
  ADD INDEX `idx_users_reset_token` (`reset_token`);
