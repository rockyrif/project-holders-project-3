-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2024 at 07:16 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pharmacy_sys`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer_details`
--

CREATE TABLE `customer_details` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact` int(255) NOT NULL,
  `dob` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_details`
--

INSERT INTO `customer_details` (`customer_id`, `name`, `email`, `address`, `contact`, `dob`) VALUES
(3, 'rifky', 'mnamrifky1@gmail.com', '320b sailan road kalmunai kudy 3', 776040064, '2024-06-07');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_details`
--

CREATE TABLE `pharmacy_details` (
  `pharmacy_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pharmacy_details`
--

INSERT INTO `pharmacy_details` (`pharmacy_id`, `name`, `email`, `address`) VALUES
(1, 'Harcourts', 'mnamrifky@gmail.com', '320b sailan road kalmunai kudy 3');

-- --------------------------------------------------------

--
-- Table structure for table `prescription_and_address`
--

CREATE TABLE `prescription_and_address` (
  `prescription_id` int(11) NOT NULL,
  `pharmacy_id` int(255) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `images` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL,
  `delivery_address` varchar(255) NOT NULL,
  `delivery_time_1` datetime NOT NULL,
  `delivery_time_2` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescription_and_address`
--

INSERT INTO `prescription_and_address` (`prescription_id`, `pharmacy_id`, `customer_id`, `images`, `note`, `delivery_address`, `delivery_time_1`, `delivery_time_2`) VALUES
(13, 1, 3, '../../images/prescriptions/mnamrifky1@gmail.com-20240606180202', 'test', '320b sailan road kalmunai kudy 3', '2024-06-06 11:14:00', '2024-06-08 11:15:00.000000'),
(14, 1, 3, '../../images/prescriptions/mnamrifky1@gmail.com-20240606185934', 'trest', '320b sailan road kalmunai kudy 3', '2024-06-06 22:29:00', '2024-06-08 22:29:00.000000'),
(15, 1, 3, '../../images/prescriptions/mnamrifky1@gmail.com-20240606190140', 'trest', '320b sailan road kalmunai kudy 3', '2024-06-06 22:29:00', '2024-06-08 22:29:00.000000'),
(16, 1, 3, '../../images/prescriptions/mnamrifky1@gmail.com-20240606190220', 'trest', '320b sailan road kalmunai kudy 3', '2024-06-06 22:29:00', '2024-06-08 22:29:00.000000'),
(17, 1, 3, '../../images/prescriptions/mnamrifky1@gmail.com-20240606190311', 'trest', '320b sailan road kalmunai kudy 3', '2024-06-06 22:29:00', '2024-06-08 22:29:00.000000'),
(18, 1, 3, '../../images/prescriptions/mnamrifky1@gmail.com-20240606190803', 'trest', '320b sailan road kalmunai kudy 3', '2024-06-06 22:29:00', '2024-06-08 22:29:00.000000');

-- --------------------------------------------------------

--
-- Table structure for table `user_login`
--

CREATE TABLE `user_login` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `privilage` enum('pharmacist','customer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_login`
--

INSERT INTO `user_login` (`user_id`, `username`, `password`, `email`, `privilage`) VALUES
(21, 'rifky', '$2y$10$JdiuHCyXFWAFEL1RU0MZLOhebY3MhxjfBQYXmZT85fENuSkz2xf0q', 'mnamrifky@gmail.com', 'pharmacist'),
(22, 'rifky1', '$2y$10$Tlm8aCXHEW7E4ntEkZDtL.caZXj1Wslo95xLniGUq99AQcZpdv.tC', 'mnamrifky1@gmail.com', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer_details`
--
ALTER TABLE `customer_details`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `pharmacy_details`
--
ALTER TABLE `pharmacy_details`
  ADD PRIMARY KEY (`pharmacy_id`);

--
-- Indexes for table `prescription_and_address`
--
ALTER TABLE `prescription_and_address`
  ADD PRIMARY KEY (`prescription_id`),
  ADD KEY `pharmacy_id` (`pharmacy_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `user_login`
--
ALTER TABLE `user_login`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer_details`
--
ALTER TABLE `customer_details`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pharmacy_details`
--
ALTER TABLE `pharmacy_details`
  MODIFY `pharmacy_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `prescription_and_address`
--
ALTER TABLE `prescription_and_address`
  MODIFY `prescription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user_login`
--
ALTER TABLE `user_login`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `prescription_and_address`
--
ALTER TABLE `prescription_and_address`
  ADD CONSTRAINT `prescription_and_address_ibfk_1` FOREIGN KEY (`pharmacy_id`) REFERENCES `pharmacy_details` (`pharmacy_id`),
  ADD CONSTRAINT `prescription_and_address_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer_details` (`customer_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
