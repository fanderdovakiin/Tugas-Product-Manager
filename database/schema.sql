-- ============================================================
-- Product Manager — Database Schema
-- Gunakan collation utf8mb4_unicode_ci agar UNIQUE index
-- bersifat case-insensitive secara default.
-- ============================================================

CREATE DATABASE IF NOT EXISTS product_manager
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE product_manager;

CREATE TABLE IF NOT EXISTS products (
  id          INT UNSIGNED      AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(150)      NOT NULL,
  category    VARCHAR(100)      NOT NULL,
  price       DECIMAL(12,2)     NOT NULL,
  stock       INT UNSIGNED      NOT NULL DEFAULT 0,
  created_at  TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP
                                ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_products_name (name)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
