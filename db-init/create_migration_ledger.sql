
-- Database (explicit, though Compose will create this if you set MYSQL_DATABASE)
CREATE DATABASE IF NOT EXISTS `testdb`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci;

USE `testdb`;

-- Migration ledger
-- Stores one row per migration version (epoch ms) with status and minimal diagnostics.
CREATE TABLE IF NOT EXISTS migration (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  version BIGINT UNSIGNED NOT NULL,                  -- e.g., 1765596282708 (epoch ms)
  name VARCHAR(500) NOT NULL,                        -- e.g., 'CreateSomeRandomTable'
  status ENUM('COMPLETED','PENDING','FAILED') NOT NULL DEFAULT 'PENDING',
  created_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  duration_ms INT UNSIGNED NULL,                     -- execution time (optional)
  error_text TEXT NULL,                              -- error summary (truncate in app to ~4KB)
  PRIMARY KEY (id),
  UNIQUE KEY ux_migration_version (version),
  KEY ix_migration_status_createdon (status, created_on)  -- listing/filtering convenience
) ENGINE=InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

-- Privileges for your migrator user (adjust user/host as needed)
GRANT
  SELECT, INSERT, UPDATE, DELETE,
  CREATE, ALTER, DROP, INDEX, REFERENCES,
  CREATE TEMPORARY TABLES,
  LOCK TABLES
ON `testdb`.* TO 'migrator'@'%';

FLUSH PRIVILEGES;
