-- ============================================
-- CABGO DATABASE
-- Cab Booking Management System
-- ============================================


CREATE DATABASE IF NOT EXISTS cabgo;

USE cabgo;


-- ============================================
-- USERS TABLE
-- Stores both Users and Admin
-- ============================================

CREATE TABLE users (

    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,

    email VARCHAR(100) NOT NULL UNIQUE,

    phone VARCHAR(20) NOT NULL,

    password VARCHAR(255) NOT NULL,

    role ENUM('user', 'admin')
    NOT NULL DEFAULT 'user',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);


-- ============================================
-- CABS TABLE
-- Stores Cab Information
-- ============================================

CREATE TABLE cabs (

    id INT AUTO_INCREMENT PRIMARY KEY,

    cab_number VARCHAR(50)
    NOT NULL UNIQUE,

    cab_type VARCHAR(50)
    NOT NULL,

    driver_name VARCHAR(100)
    NOT NULL,

    driver_phone VARCHAR(20)
    NOT NULL,

    fare_per_km DECIMAL(10,2)
    NOT NULL,

    status ENUM(
        'Available',
        'Unavailable'
    )
    DEFAULT 'Available',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);


-- ============================================
-- BOOKINGS TABLE
-- Stores User Cab Bookings
-- ============================================

CREATE TABLE bookings (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    cab_id INT NOT NULL,

    pickup_location VARCHAR(255)
    NOT NULL,

    drop_location VARCHAR(255)
    NOT NULL,

    booking_date DATE
    NOT NULL,

    booking_time TIME
    NOT NULL,

    distance DECIMAL(10,2)
    NOT NULL,

    fare DECIMAL(10,2)
    NOT NULL,

    status ENUM(
        'Pending',
        'Confirmed',
        'Completed',
        'Cancelled'
    )
    DEFAULT 'Pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,


    CONSTRAINT fk_booking_user

        FOREIGN KEY (user_id)

        REFERENCES users(id)

        ON DELETE CASCADE,


    CONSTRAINT fk_booking_cab

        FOREIGN KEY (cab_id)

        REFERENCES cabs(id)

        ON DELETE CASCADE

);


-- ============================================
-- SAMPLE CABS
-- ============================================

INSERT INTO cabs
(
    cab_number,
    cab_type,
    driver_name,
    driver_phone,
    fare_per_km,
    status
)

VALUES

(
    'CAB101',
    'Hatchback',
    'Rahul Sharma',
    '9876543210',
    12.00,
    'Available'
),

(
    'CAB102',
    'Sedan',
    'Amit Patel',
    '9876543211',
    15.00,
    'Available'
),

(
    'CAB103',
    'SUV',
    'Rohan Mehta',
    '9876543212',
    20.00,
    'Available'
);