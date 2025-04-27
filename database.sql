-- Active: 1741001467185@@127.0.0.1@3306@project_index
-- Create the database
CREATE DATABASE IF NOT EXISTS project_index;
USE project_index;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Flights table
-- Flights table
CREATE TABLE flights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    departure_city VARCHAR(50) NOT NULL,
    destination_city VARCHAR(50) NOT NULL,
    flight_used VARCHAR(50) NOT NULL,
    seating_capacity INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    flight_id INT NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    type ENUM('economy', 'business', 'first-class') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('available', 'booked') DEFAULT 'available',
    number_of_people INT NOT NULL DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (flight_id) REFERENCES flights(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Flights seat types
CREATE TABLE flight_seat_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flight_id INT NOT NULL,
    seat_type VARCHAR(50) NOT NULL,  -- E.g., Economy, Business, First Class
    seat_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (flight_id) REFERENCES flights(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seats availability
-- CREATE TABLE seats (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     seat_type_id INT NOT NULL,
--     status ENUM('available', 'booked') DEFAULT 'available',
--     FOREIGN KEY (seat_type_id) REFERENCES flight_seat_types(id) ON DELETE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample flights
INSERT INTO flights (departure_city, destination_city, flight_used, price) VALUES
('Nairobi (JKIA)', 'Mombasa', 'Kenya Airways'),
('Kisumu', 'Nairobi (Wilson)', 'Wilson Airport'),
('Mombasa', 'Nairobi (JKIA)', 'JKIA'),
('Nairobi (JKIA)', 'Eldoret', 'Fly Emirates'),
('Kisumu', 'Diani', 'Kenya Airways'),
('Nairobi (JKIA)', 'Dubai', 'Emirates'),
('Mombasa', 'Zanzibar', 'Air Tanzania'),
('Nairobi (JKIA)', 'Addis Ababa', 'Ethiopian Airlines'),
('Eldoret', 'Mombasa', 'Jambojet'),
('Nairobi (Wilson)', 'Lamu', 'Skyward Express'),
('Malindi', 'Nairobi (JKIA)', 'Jambojet'),
('Nairobi (JKIA)', 'Entebbe', 'Uganda Airlines'),
('Mombasa', 'Dar es Salaam', 'Air Tanzania'),
('Nairobi (JKIA)', 'Kigali', 'RwandAir'),
('Kisumu', 'Mombasa', 'Kenya Airways');


-- Insert Seat Types and Prices
INSERT INTO flight_seat_types (flight_id, seat_type, seat_price)
VALUES
(1, 'Economy', 8500.00),
(1, 'Business', 15000.00),
(1, 'First Class', 25000.00),
(2, 'Economy', 7200.00),
(2, 'Business', 13000.00),
(2, 'First Class', 22000.00),
(3, 'Economy', 9000.00),
(3, 'Business', 16000.00),
(3, 'First Class', 27000.00),
(4, 'Economy', 10500.00),
(4, 'Business', 19000.00),
(4, 'First Class', 30000.00),
(5, 'Economy', 12000.00),
(5, 'Business', 21000.00),
(5, 'First Class', 32000.00),
(6, 'Economy', 45000.00),
(6, 'Business', 70000.00),
(6, 'First Class', 100000.00),
(7, 'Economy', 15000.00),
(7, 'Business', 25000.00),
(7, 'First Class', 35000.00),
(8, 'Economy', 25000.00),
(8, 'Business', 40000.00),
(8, 'First Class', 60000.00),
(9, 'Economy', 9500.00),
(9, 'Business', 16000.00),
(9, 'First Class', 26000.00),
(10, 'Economy', 13500.00),
(10, 'Business', 22000.00),
(10, 'First Class', 35000.00),
(11, 'Economy', 8800.00),
(11, 'Business', 15000.00),
(11, 'First Class', 25000.00),
(12, 'Economy', 18000.00),
(12, 'Business', 30000.00),
(12, 'First Class', 45000.00),
(13, 'Economy', 16500.00),
(13, 'Business', 27000.00),
(13, 'First Class', 40000.00),
(14, 'Economy', 22000.00),
(14, 'Business', 35000.00),
(14, 'First Class', 50000.00),
(15, 'Economy', 11000.00),
(15, 'Business', 19000.00),
(15, 'First Class', 29000.00);


-- Add user_id foreign key
ALTER TABLE bookings
ADD CONSTRAINT fk_bookings_user
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- add number_of_people column to bookings table
ALTER TABLE bookings 
ADD COLUMN number_of_people INT NOT NULL DEFAULT 1;

-- Add flight_id foreign key
ALTER TABLE bookings
ADD CONSTRAINT fk_bookings_flight
FOREIGN KEY (flight_id) REFERENCES flights(id) ON DELETE CASCADE;

-- Display confirmation message
SELECT 'Database setup completed successfully!' AS message;