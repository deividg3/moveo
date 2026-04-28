-- backend/schema_full.sql
-- Schema completo. Ejecuta esto una sola vez.
 
CREATE DATABASE IF NOT EXISTS moveo_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
 
USE moveo_db;
 
-- ── Usuarios ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
  id            INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  name          VARCHAR(120)     NOT NULL,
  email         VARCHAR(255)     NOT NULL,
  password_hash VARCHAR(255)     NOT NULL,
  birthdate     DATE             NULL,
  avatar        TINYINT UNSIGNED NOT NULL DEFAULT 0,
  goals         VARCHAR(255)     NOT NULL DEFAULT '',
  streak        SMALLINT         NOT NULL DEFAULT 0,
  last_test_date DATE            NULL,
  created_at    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- ── Administradores ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS admins (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username      VARCHAR(80)  NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- Admin por defecto: usuario=admin, contraseña=admin1234 (cámbiala en producción)
INSERT IGNORE INTO admins (username, password_hash)
VALUES ('admin', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- Genera tu propio hash con: password_hash('tu_contraseña', PASSWORD_BCRYPT)
 
-- ── Tokens de sesión admin ──────────────────────────────────────
CREATE TABLE IF NOT EXISTS admin_sessions (
  token      VARCHAR(64)  NOT NULL,
  admin_id   INT UNSIGNED NOT NULL,
  expires_at DATETIME     NOT NULL,
  PRIMARY KEY (token),
  FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- ── Resultados de tests ─────────────────────────────────────────
-- Opcional: para guardar resultados también en servidor, no solo en localStorage
CREATE TABLE IF NOT EXISTS test_results (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id    INT UNSIGNED NOT NULL,
  topic      VARCHAR(50)  NOT NULL DEFAULT 'random',
  score      TINYINT      NOT NULL,
  total      TINYINT      NOT NULL,
  taken_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_date (user_id, taken_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;