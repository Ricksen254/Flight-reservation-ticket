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
CREATE TABLE flights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    departure_city VARCHAR(50) NOT NULL,
    destination_city VARCHAR(50) NOT NULL,
    flight_used VARCHAR(50) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    flight_id INT NOT NULL,
    payment DECIMAL(10, 2) NOT NULL,
    type ENUM('economy', 'business', 'first-class') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (flight_id) REFERENCES flights(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample flights
INSERT INTO flights (departure_city, destination_city, flight_used, price) VALUES
('Nairobi (JKIA)', 'Mombasa', 'Kenya Airways', 8500.00),
('Kisumu', 'Nairobi (Wilson)', 'Wilson Airport', 7200.00),
('Mombasa', 'Nairobi (JKIA)', 'JKIA', 9000.00),
('Nairobi (JKIA)', 'Eldoret', 'Fly Emirates', 10500.00),
('Kisumu', 'Diani', 'Kenya Airways', 12000.00),
('Nairobi (JKIA)', 'Dubai', 'Emirates', 45000.00),
('Mombasa', 'Zanzibar', 'Air Tanzania', 15000.00),
('Nairobi (JKIA)', 'Addis Ababa', 'Ethiopian Airlines', 25000.00),
('Eldoret', 'Mombasa', 'Jambojet', 9500.00),
('Nairobi (Wilson)', 'Lamu', 'Skyward Express', 13500.00),
('Malindi', 'Nairobi (JKIA)', 'Jambojet', 8800.00),
('Nairobi (JKIA)', 'Entebbe', 'Uganda Airlines', 18000.00),
('Mombasa', 'Dar es Salaam', 'Air Tanzania', 16500.00),
('Nairobi (JKIA)', 'Kigali', 'RwandAir', 22000.00),
('Kisumu', 'Mombasa', 'Kenya Airways', 11000.00);


-- Add user_id foreign key
ALTER TABLE bookings
ADD CONSTRAINT fk_bookings_user
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- Add flight_id foreign key
ALTER TABLE bookings
ADD CONSTRAINT fk_bookings_flight
FOREIGN KEY (flight_id) REFERENCES flights(id) ON DELETE CASCADE;

-- Display confirmation message
SELECT 'Database setup completed successfully!' AS message;