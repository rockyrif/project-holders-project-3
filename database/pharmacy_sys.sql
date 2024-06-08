-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2024 at 10:53 AM
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
(6, 'rifky', 'mnamrifky@gmail.com', '320b sailan road kalmunai kudy 3', 776040064, '2024-06-15');

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
(3, 'Harcourts', 'rockyrif199913@gmail.com', '320b sailan road kalmunai kudy 3');

-- --------------------------------------------------------

--
-- Table structure for table `prescription_and_address`
--

CREATE TABLE `prescription_and_address` (
  `prescription_id` int(11) NOT NULL,
  `pharmacy_id` int(255) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `images` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL,
  `delivery_address` varchar(255) NOT NULL,
  `delivery_time_1` datetime NOT NULL,
  `delivery_time_2` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescription_and_address`
--

INSERT INTO `prescription_and_address` (`prescription_id`, `pharmacy_id`, `customer_id`, `email`, `images`, `note`, `delivery_address`, `delivery_time_1`, `delivery_time_2`) VALUES
(27, 3, 6, 'mnamrifky@gmail.com', '../../images/prescriptions/mnamrifky@gmail.com-20240608102834', 'test', '320b sailan road kalmunai kudy 3', '2024-06-06 13:58:00', '2024-06-15 13:58:00.000000');

-- --------------------------------------------------------

--
-- Table structure for table `quotation`
--

CREATE TABLE `quotation` (
  `quotation_id` int(11) NOT NULL,
  `prescription_id` int(11) NOT NULL,
  `pharmacy_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `drug_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `user_acceptence` enum('Accept','Reject','Not yet') NOT NULL DEFAULT 'Not yet'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotation`
--

INSERT INTO `quotation` (`quotation_id`, `prescription_id`, `pharmacy_id`, `customer_id`, `drug_name`, `quantity`, `unit_price`, `amount`, `user_acceptence`) VALUES
(162, 27, 3, 6, 'test1', 1, 4, 4, 'Reject'),
(163, 27, 3, 6, 'test2', 3, 3, 9, 'Not yet');

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
(25, 'rifky', '$2y$10$eadFW5RKZ787p5D3NOPereD1H6JGiXTpUebSMwkBty1tzsOvXYNtm', 'mnamrifky@gmail.com', 'customer'),
(26, 'ameen', '$2y$10$w5AOjD0ooVWTjEHEEXXy7eRezhPq6xWsgy5RFQCky7M0RUqNepqRS', 'rockyrif199913@gmail.com', 'pharmacist');

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
-- Indexes for table `quotation`
--
ALTER TABLE `quotation`
  ADD PRIMARY KEY (`quotation_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `pharmacy_id` (`pharmacy_id`),
  ADD KEY `prescription_id` (`prescription_id`);

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
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pharmacy_details`
--
ALTER TABLE `pharmacy_details`
  MODIFY `pharmacy_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prescription_and_address`
--
ALTER TABLE `prescription_and_address`
  MODIFY `prescription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `quotation`
--
ALTER TABLE `quotation`
  MODIFY `quotation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `user_login`
--
ALTER TABLE `user_login`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `prescription_and_address`
--
ALTER TABLE `prescription_and_address`
  ADD CONSTRAINT `prescription_and_address_ibfk_1` FOREIGN KEY (`pharmacy_id`) REFERENCES `pharmacy_details` (`pharmacy_id`),
  ADD CONSTRAINT `prescription_and_address_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer_details` (`customer_id`);

--
-- Constraints for table `quotation`
--
ALTER TABLE `quotation`
  ADD CONSTRAINT `quotation_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer_details` (`customer_id`),
  ADD CONSTRAINT `quotation_ibfk_2` FOREIGN KEY (`pharmacy_id`) REFERENCES `pharmacy_details` (`pharmacy_id`),
  ADD CONSTRAINT `quotation_ibfk_3` FOREIGN KEY (`prescription_id`) REFERENCES `prescription_and_address` (`prescription_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
