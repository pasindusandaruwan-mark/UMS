
DROP DATABASE IF EXISTS manager_db;

-- Create database
CREATE DATABASE manager_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE manager_db;

/* ========================================
   TABLE 1: USERS
   Managers, Admins, Officers store කරන්න
======================================== */
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(250) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    nic VARCHAR(50),
    password VARCHAR(255) NOT NULL,
    role ENUM('Manager','Admin','Officer') NOT NULL,
    status ENUM('Active','Inactive','Suspended') DEFAULT 'Active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

/* ========================================
   TABLE 2: CUSTOMERS
======================================== */
CREATE TABLE Customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(250) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20) NOT NULL,
    nic VARCHAR(50) NOT NULL,
    address TEXT,
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

/* ========================================
   TABLE 3: SERVICES
======================================== */
CREATE TABLE Services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    service_type ENUM('Electricity','Water') NOT NULL,
    rate_per_unit DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB;

/* ========================================
   TABLE 4: METERS
======================================== */
CREATE TABLE Meters (
    meter_id INT AUTO_INCREMENT PRIMARY KEY,
    meter_number VARCHAR(50) NOT NULL UNIQUE,
    customer_id INT NOT NULL,
    service_id INT NOT NULL,
    install_date DATE,
    status ENUM('Active','Inactive') DEFAULT 'Active',
    
    FOREIGN KEY (customer_id) REFERENCES Customers(customer_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES Services(service_id)
) ENGINE=InnoDB;

/* ========================================
   TABLE 5: COMPLAINTS
======================================== */
CREATE TABLE Complaints (
    complaint_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    meter_id INT NOT NULL,
    complaint_date DATE NOT NULL,
    description TEXT NOT NULL,
    status ENUM('Pending','In Progress','Resolved') DEFAULT 'Pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (customer_id) REFERENCES Customers(customer_id) ON DELETE CASCADE,
    FOREIGN KEY (meter_id) REFERENCES Meters(meter_id) ON DELETE CASCADE
) ENGINE=InnoDB;

/* ========================================
   TABLE 6: REPLIES
======================================== */
CREATE TABLE Replies (
    reply_id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_id INT NOT NULL,
    replied_by INT NOT NULL,
    reply_message TEXT NOT NULL,
    reply_date DATE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (complaint_id) REFERENCES Complaints(complaint_id) ON DELETE CASCADE,
    FOREIGN KEY (replied_by) REFERENCES Users(user_id)
) ENGINE=InnoDB;

/* ========================================
   INSERT SAMPLE DATA
======================================== */

-- Users (Admins, Managers, Officers)
INSERT INTO Users (name, email, phone, nic, password, role, status) VALUES
('Admin User', 'admin@manager.com', '0771111111', '199012345671', '1234', 'Admin', 'Active'),
('Manager User', 'manager@manager.com', '0772222222', '199112345672', '1234', 'Manager', 'Active'),
('Officer John', 'john@manager.com', '0773333333', '199212345673', '1234', 'Officer', 'Active'),
('Officer Mary', 'mary@manager.com', '0774444444', '199312345674', '1234', 'Officer', 'Active'),
('Admin Two', 'admin2@manager.com', '0775555555', '199412345675', '1234', 'Admin', 'Active');

-- Customers
INSERT INTO Customers (name, email, phone, nic, address, status) VALUES
('Sarath Silva', 'sarath@gmail.com', '0771234501', '198512345601', 'No 123, Colombo 07', 'Active'),
('Nimal Perera', 'nimal@gmail.com', '0771234502', '199012345602', 'No 456, Kandy', 'Active'),
('Kamala Fernando', 'kamala@gmail.com', '0771234503', '198812345603', 'No 789, Galle', 'Active'),
('Anura Jayasinghe', 'anura@gmail.com', '0771234504', '198612345604', 'No 234, Maharagama', 'Active'),
('Dilini Wickrama', 'dilini@gmail.com', '0771234505', '199212345605', 'No 567, Nugegoda', 'Active'),
('Pradeep Kumar', 'pradeep@gmail.com', '0771234506', '199512345606', 'No 890, Moratuwa', 'Active'),
('Sanduni Perera', 'sanduni@gmail.com', '0771234507', '198812345607', 'No 111, Dehiwala', 'Active'),
('Tharaka Silva', 'tharaka@gmail.com', '0771234508', '199012345608', 'No 222, Mount Lavinia', 'Active'),
('Chamodi Fernando', 'chamodi@gmail.com', '0771234509', '199312345609', 'No 333, Panadura', 'Active'),
('Ravindu Bandara', 'ravindu@gmail.com', '0771234510', '199612345610', 'No 444, Kalutara', 'Active');

-- Services
INSERT INTO Services (service_name, service_type, rate_per_unit) VALUES
('Electricity Standard', 'Electricity', 30.00),
('Water Standard', 'Water', 15.00);

-- Meters
INSERT INTO Meters (meter_number, customer_id, service_id, install_date, status) VALUES
('ELX001', 1, 1, '2024-01-15', 'Active'),
('WTR001', 1, 2, '2024-01-15', 'Active'),
('ELX002', 2, 1, '2024-02-10', 'Active'),
('WTR002', 3, 2, '2024-03-05', 'Active'),
('ELX003', 4, 1, '2024-04-01', 'Active'),
('WTR003', 4, 2, '2024-04-01', 'Active'),
('ELX004', 5, 1, '2024-05-15', 'Active'),
('WTR004', 5, 2, '2024-05-15', 'Active'),
('ELX005', 6, 1, '2024-06-10', 'Active'),
('WTR005', 6, 2, '2024-06-10', 'Inactive'),
('ELX006', 7, 1, '2024-07-20', 'Active'),
('WTR006', 8, 2, '2024-08-05', 'Active'),
('ELX007', 9, 1, '2024-09-12', 'Active'),
('WTR007', 9, 2, '2024-09-12', 'Active'),
('ELX008', 10, 1, '2024-10-01', 'Active');

-- Complaints
INSERT INTO Complaints (customer_id, meter_id, complaint_date, description, status) VALUES
(1, 1, '2025-01-02', 'High electricity bill this month. Please check meter reading.', 'Pending'),
(2, 3, '2025-01-03', 'Meter display not working properly.', 'Pending'),
(3, 4, '2025-01-04', 'Water pressure very low in morning.', 'Pending'),
(4, 5, '2025-01-05', 'Electricity meter making strange noise.', 'Pending'),
(5, 7, '2025-01-06', 'Bill amount calculation error.', 'Pending'),
(6, 9, '2024-12-28', 'Water meter leaking.', 'In Progress'),
(7, 11, '2024-12-29', 'Electricity fluctuation issue.', 'In Progress'),
(8, 12, '2024-12-30', 'Water supply interrupted.', 'In Progress'),
(9, 13, '2024-12-25', 'Meter reading incorrect.', 'In Progress'),
(1, 2, '2024-12-20', 'Water quality issue resolved.', 'Resolved'),
(4, 6, '2024-12-22', 'Billing issue resolved.', 'Resolved'),
(5, 8, '2024-12-18', 'Meter replaced successfully.', 'Resolved');

-- Replies
INSERT INTO Replies (complaint_id, replied_by, reply_message, reply_date) VALUES
(6, 2, 'Technician scheduled for tomorrow 10 AM - 2 PM to fix the leak.', '2024-12-29'),
(7, 2, 'Electrician will visit today evening to check the issue.', '2024-12-30'),
(8, 2, 'Water supply restored. Issue was in main pipeline.', '2024-12-31'),
(9, 2, 'Meter reading team will recheck tomorrow morning.', '2024-12-26'),
(10, 2, 'Water quality tests completed. All parameters normal now.', '2024-12-21'),
(11, 2, 'Corrected bill sent to your email. Apologies for the error.', '2024-12-23'),
(12, 2, 'New meter installed and working properly. Please confirm.', '2024-12-19');

/* ========================================
   VERIFICATION
======================================== */

-- Check tables created
SELECT 'Database Created Successfully!' AS Status;

-- Show counts
SELECT 
    (SELECT COUNT(*) FROM Users) AS Users,
    (SELECT COUNT(*) FROM Customers) AS Customers,
    (SELECT COUNT(*) FROM Services) AS Services,
    (SELECT COUNT(*) FROM Meters) AS Meters,
    (SELECT COUNT(*) FROM Complaints) AS Complaints,
    (SELECT COUNT(*) FROM Replies) AS Replies;

-- Show complaints by status
SELECT status, COUNT(*) as count 
FROM Complaints 
GROUP BY status;

/* ========================================
   DATABASE SETUP COMPLETE!
   
   Database Name: manager_db
   Tables: 6
   - Users (5 records)
   - Customers (10 records)
   - Services (2 records)
   - Meters (15 records)
   - Complaints (12 records)
   - Replies (7 records)
   
   Next Step: Update db_connection.php
======================================== */
