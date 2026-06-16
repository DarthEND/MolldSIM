-- MolldSIM database schema
-- Run via: db/setup.php

CREATE TABLE IF NOT EXISTS users (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(100)              NOT NULL UNIQUE,
    password_hash VARCHAR(255)              NOT NULL,
    role          ENUM('admin', 'demo')     NOT NULL DEFAULT 'demo',
    created_at    TIMESTAMP                 DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS prepay_plans (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    operator            VARCHAR(100) NOT NULL,
    operator_key        VARCHAR(100) NOT NULL,
    operator_color      VARCHAR(20) NOT NULL DEFAULT '#888888',
    name                VARCHAR(200) NOT NULL,
    price               DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT 0,
    duration_days       SMALLINT UNSIGNED NOT NULL DEFAULT 30,
    data_gb             DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT 0,
    minutes             INT UNSIGNED NOT NULL DEFAULT 0,
    sms                 INT UNSIGNED NOT NULL DEFAULT 0,
    roaming_gb          DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT 0,
    is_recommended      TINYINT(1) NOT NULL DEFAULT 0,
    additional_features JSON NOT NULL,
    link                VARCHAR(500) NOT NULL DEFAULT '',
    created_at          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_prepaid_operator (operator_key),
    INDEX idx_prepaid_price (price),
    INDEX idx_prepaid_data (data_gb),
    UNIQUE KEY unique_prepaid_plan (operator_key, name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS abonament_plans (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    operator            VARCHAR(100) NOT NULL,
    operator_key        VARCHAR(100) NOT NULL,
    operator_color      VARCHAR(20) NOT NULL DEFAULT '#888888',
    name                VARCHAR(200) NOT NULL,
    price               DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT 0,
    duration_days       SMALLINT UNSIGNED NOT NULL DEFAULT 30,
    data_gb             DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT 0,
    minutes             INT UNSIGNED NOT NULL DEFAULT 0,
    sms                 INT UNSIGNED NOT NULL DEFAULT 0,
    roaming_gb          DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT 0,
    is_recommended      TINYINT(1) NOT NULL DEFAULT 0,
    additional_features JSON NOT NULL,
    link                VARCHAR(500) NOT NULL DEFAULT '',
    created_at          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_abonament_operator (operator_key),
    INDEX idx_abonament_price (price),
    INDEX idx_abonament_data (data_gb),
    UNIQUE KEY unique_abonament_plan (operator_key, name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS internet_plans (
    id                    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    operator              VARCHAR(100) NOT NULL,
    operator_key          VARCHAR(100) NOT NULL,
    operator_color        CHAR(7) NOT NULL DEFAULT '#888888',
    name                  VARCHAR(150) NOT NULL,
    price                 DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    duration_months       INT UNSIGNED NOT NULL DEFAULT 1,
    download_speed_mbps   INT UNSIGNED NOT NULL DEFAULT 0,
    upload_speed_mbps     INT UNSIGNED NOT NULL DEFAULT 0,
    installation_price    DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    router_name           VARCHAR(150) NULL,
    is_recommended        TINYINT(1) NOT NULL DEFAULT 0,
    additional_features   JSON NULL,
    link                  VARCHAR(500) NULL,
    created_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_internet_operator (operator_key),
    INDEX idx_internet_price (price),
    INDEX idx_internet_download_speed (download_speed_mbps),
    UNIQUE KEY unique_internet_plan (operator_key, name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS internet_tv_plans (
    id                    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    operator              VARCHAR(100) NOT NULL,
    operator_key          VARCHAR(100) NOT NULL,
    operator_color        CHAR(7) NOT NULL DEFAULT '#888888',
    name                  VARCHAR(150) NOT NULL,
    price                 DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    duration_months       INT UNSIGNED NOT NULL DEFAULT 1,
    download_speed_mbps   INT UNSIGNED NOT NULL DEFAULT 0,
    upload_speed_mbps     INT UNSIGNED NOT NULL DEFAULT 0,
    tv_channels           INT UNSIGNED NOT NULL DEFAULT 0,
    hd_channels           INT UNSIGNED NOT NULL DEFAULT 0,
    router_name           VARCHAR(150) NULL,
    installation_price    DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    is_recommended        TINYINT(1) NOT NULL DEFAULT 0,
    additional_features   JSON NULL,
    link                  VARCHAR(500) NULL,
    created_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_internet_tv_operator (operator_key),
    INDEX idx_internet_tv_price (price),
    INDEX idx_internet_tv_download_speed (download_speed_mbps),
    UNIQUE KEY unique_internet_tv_plan (operator_key, name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE prepay_plans
    ADD COLUMN IF NOT EXISTS is_recommended TINYINT(1) NOT NULL DEFAULT 0;

ALTER TABLE abonament_plans
    ADD COLUMN IF NOT EXISTS is_recommended TINYINT(1) NOT NULL DEFAULT 0;

ALTER TABLE internet_plans
    ADD COLUMN IF NOT EXISTS is_recommended TINYINT(1) NOT NULL DEFAULT 0;

ALTER TABLE internet_tv_plans
    ADD COLUMN IF NOT EXISTS is_recommended TINYINT(1) NOT NULL DEFAULT 0;
