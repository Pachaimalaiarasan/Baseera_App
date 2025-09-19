-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2025 at 08:03 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `car_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `booked_slots`
--

CREATE TABLE `booked_slots` (
  `booked_slot_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `booked_slots`
--

INSERT INTO `booked_slots` (`booked_slot_id`, `employee_id`, `slot_id`, `date`) VALUES
(102, 26, 1, '2025-09-01'),
(120, 26, 1, '2025-09-02'),
(123, 26, 1, '2025-09-05'),
(133, 26, 1, '2025-09-07'),
(135, 26, 1, '2025-09-08'),
(103, 26, 2, '2025-09-01'),
(121, 26, 2, '2025-09-02'),
(134, 26, 2, '2025-09-07'),
(104, 26, 3, '2025-09-01'),
(122, 26, 3, '2025-09-02'),
(105, 26, 4, '2025-09-01'),
(107, 26, 5, '2025-09-01'),
(109, 26, 6, '2025-09-01'),
(110, 26, 7, '2025-09-01'),
(127, 26, 7, '2025-09-06'),
(136, 26, 7, '2025-09-18'),
(111, 26, 8, '2025-09-01'),
(128, 26, 8, '2025-09-06'),
(137, 26, 8, '2025-09-18'),
(86, 26, 9, '2025-08-31'),
(112, 26, 9, '2025-09-01'),
(90, 26, 10, '2025-08-31'),
(113, 26, 10, '2025-09-01'),
(92, 26, 11, '2025-08-31'),
(114, 26, 11, '2025-09-01'),
(93, 26, 12, '2025-08-31'),
(115, 26, 12, '2025-09-01'),
(129, 26, 12, '2025-09-06'),
(95, 26, 13, '2025-08-31'),
(116, 26, 13, '2025-09-01'),
(98, 26, 14, '2025-08-31'),
(117, 26, 14, '2025-09-01'),
(99, 26, 15, '2025-08-31'),
(118, 26, 15, '2025-09-01'),
(100, 26, 16, '2025-08-31'),
(119, 26, 16, '2025-09-01'),
(130, 26, 16, '2025-09-06'),
(106, 27, 1, '2025-09-01'),
(108, 27, 2, '2025-09-01'),
(124, 27, 6, '2025-09-06'),
(125, 27, 7, '2025-09-06'),
(126, 27, 8, '2025-09-06'),
(87, 27, 9, '2025-08-31'),
(88, 27, 10, '2025-08-31'),
(89, 27, 11, '2025-08-31'),
(91, 27, 12, '2025-08-31'),
(94, 27, 13, '2025-08-31'),
(96, 27, 14, '2025-08-31'),
(101, 27, 15, '2025-08-31'),
(97, 27, 16, '2025-08-31');

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `branch_id` int(11) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `branch_city` varchar(100) DEFAULT NULL,
  `branch_phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`branch_id`, `branch_name`, `branch_city`, `branch_phone`, `created_at`, `updated_at`) VALUES
(14, 'VEERA Branch', 'VEERA', '89898989', '2025-08-11 05:16:13', '2025-08-11 05:16:13'),
(16, 'APK Branch', 'APK', '9090909009', '2025-08-11 05:19:43', '2025-08-11 05:19:43'),
(17, 'TVPM BOYS', 'TVPM', '9902029029', '2025-09-11 19:54:35', '2025-09-11 19:54:35');

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `car_id` int(11) NOT NULL,
  `car_number` varchar(50) NOT NULL,
  `car_name` varchar(100) NOT NULL,
  `car_image` varchar(100) DEFAULT NULL,
  `customer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`car_id`, `car_number`, `car_name`, `car_image`, `customer_id`) VALUES
(9, 'TN-67-WW-2002', 'W', 'uploads/cars/68b02dbb6684a_client-say (3).png', 1),
(10, 'TN-67-PP-2009', 'ZZ', 'uploads/cars/68b02dbb6684a_client-say (3).png', 1),
(11, 'TN-24-OO-2006', 'Hero', 'uploads/cars/68b3d4a343fb8_2210_w018_n002_1385a_p30_1385.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(15) NOT NULL,
  `redeemable_points` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `customer_name`, `customer_email`, `customer_phone`, `redeemable_points`) VALUES
(1, 'amruth', 'a@gmail.com', '1234567890', 100),
(2, 'vijay', 'v@gmail.com', '1234567899', 10),
(5, 'arasan', 'arasan@gmail.com', '2020202020', 0),
(6, 'sample', 'sample@gmail.com', '678326473267', 0);

-- --------------------------------------------------------

--
-- Table structure for table `customer_address`
--

CREATE TABLE `customer_address` (
  `address_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `state` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `pin_code` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_address`
--

INSERT INTO `customer_address` (`address_id`, `customer_id`, `address_line1`, `address_line2`, `state`, `city`, `pin_code`) VALUES
(1, 1, '2/1 chithambaram oorani street', 'oppo. to vanasangari amman temple', 'Tamil Nadu', 'Ramanathapuram', '123456'),
(4, 2, 'APK', 'APK', 'APK', 'APK', ''),
(5, 1, 'Tvpm', '', 'Tamil Nadu', 'Apk', '626112');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `employee_phone` varchar(15) NOT NULL,
  `employee_email` varchar(100) NOT NULL,
  `employee_image` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `employee_name`, `employee_phone`, `employee_email`, `employee_image`) VALUES
(26, 'Arasu', '67846587346', 'Arasu@gmail.com', 'uploads/employees/26/2210_w018_n002_1385a_p30_1385.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `employeeslots`
--

CREATE TABLE `employeeslots` (
  `employee_slot_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `is_booked` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_ratings`
--

CREATE TABLE `employee_ratings` (
  `rating_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `rating` decimal(3,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emp_and_branch`
--

CREATE TABLE `emp_and_branch` (
  `branch_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_and_branch`
--

INSERT INTO `emp_and_branch` (`branch_id`, `employee_id`) VALUES
(14, 26);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','employee','admin','manager') NOT NULL DEFAULT 'customer',
  `customer_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `fcm_token` varchar(255) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `username`, `email`, `phone`, `password`, `role`, `customer_id`, `employee_id`, `fcm_token`, `manager_id`) VALUES
(1, 'customer', 'customer@example.com', '+1234567890', 'c42864bf107f4c452f5ff7f801159dcb', 'customer', NULL, NULL, NULL, NULL),
(3, 'admin', 'admin@example.com', '+1122334455', '7adc785be4a31eff6783871ff63e18f1', 'admin', NULL, NULL, 'f_0iDZy8QCuAWrV4hdzpOc:APA91bFln0EYto3BCTJyp_dKrRWp7SPGdXPvRapsRYfwgSyJhZj_e4dfYTaibql6dxDY8Dmmsy6DKiU0GkR5I7jGA8bVaX7EMojEP7c-7RKn0cHYtaPuX1A', NULL),
(5, 'amruth', 'a@gmail.com', '1234567890', '$2y$10$dCzMdhYwOeDDpnIMm6Toeuxw5EOx1vJIUWRwWRHMI.Z/HwvgnmqUu', 'customer', 1, NULL, NULL, NULL),
(6, 'vijay', 'v@gmail.com', '1234567899', '$2y$10$h4UUn2JtbyG4KDP6kBm21OxYeo4GJICbOPAgH7W4W/ZuFwTty1DBC', 'customer', 2, NULL, NULL, NULL),
(32, 'm', 'm@gmail.com', '1234512345', '$2y$10$S2WHXzAijOy1v0XrpK1BleubYOXmnrk1l4CjmOdg24OHV.NLsAchO', 'manager', NULL, NULL, NULL, NULL),
(35, 'arasan', 'arasan@gmail.com', '2020202020', '', 'customer', 5, NULL, NULL, NULL),
(36, 'sample', 'sample@gmail.com', '678326473267', '$2y$10$KvNbNPPC3hyvCC72uL8ryedYBe5z5Efo4ZVxaP5CC3pc.m5CGg4/O', 'customer', 6, NULL, NULL, NULL),
(37, 'Arasan', 'am@gmail.com', '89898989', '$2y$10$roN4RJeEmJ2R4/CmozUu7.2vedxMhfe/vPvOzhMfRrnDGWRlpqdKK', 'manager', NULL, NULL, NULL, NULL),
(38, 'Pethu', 'pm@gamil.com', '56687878879', '$2y$10$V2sgC5dpgPadxMqpSZcWe.Xqot7ORmfktI26q9LjehBkAQGuY3GTu', 'manager', NULL, NULL, NULL, 8),
(40, 'Arasan', 'am@manager.com', '4545454554', '$2y$10$n3zUo6bnAjTyp9cd5hEIsuYlwV7iR.iavb9QZP9.lgddlXqIi8VyC', 'manager', NULL, NULL, NULL, 10),
(42, 'Arasan', 'Arasu@gmail.com', '67846587346', '$2y$10$/pNCW9yiGawb8zNPfG6ueefcpHt6IlwaMo/EoxTLUz.BKUVwwKO02', 'employee', NULL, 26, NULL, NULL),
(48, 'arasan', 'tvpm@gmail.com', '6767676767', '$2y$10$em/2dcdBCU2PTnbCoM0VoOP/iMegMsqL8wFqkEjlN8.XsQHSqwZi.', 'manager', NULL, NULL, NULL, 11);

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `manager_id` int(11) NOT NULL,
  `manager_name` varchar(255) NOT NULL,
  `manager_email` varchar(255) NOT NULL,
  `manager_phone` varchar(20) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`manager_id`, `manager_name`, `manager_email`, `manager_phone`, `branch_id`) VALUES
(8, 'Pethu', 'pm@gamil.com', '56687878879', 14),
(10, 'Arasan', 'am@manager.com', '4545454554', 16),
(11, 'arasan', 'tvpm@gmail.com', '6767676767', 17);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `booked_slot_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `plan_id` int(11) NOT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `order_time` datetime NOT NULL DEFAULT current_timestamp(),
  `order_status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `address_id` int(11) DEFAULT NULL,
  `branch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `employee_id`, `booked_slot_id`, `car_id`, `service_id`, `plan_id`, `payment_id`, `order_time`, `order_status`, `created_at`, `updated_at`, `total_amount`, `address_id`, `branch_id`) VALUES
(88, 1, 26, 122, 9, 38, 13, 128, '2025-08-31 15:30:39', 'Confirmed', '2025-08-31 15:30:39', '2025-09-06 10:04:46', 150.00, 1, 14),
(89, 1, 26, 123, 9, 38, 13, 129, '2025-09-04 17:36:10', 'Cancelled', '2025-09-04 17:36:10', '2025-09-06 10:04:32', 150.00, NULL, 14),
(90, 1, 26, 127, 9, 39, 14, 130, '2025-09-06 11:00:27', 'confirmed', '2025-09-06 11:00:27', '2025-09-06 11:39:26', 200.00, NULL, 16),
(91, 1, 26, 128, 10, 38, 13, 131, '2025-09-06 11:40:37', 'confirmed', '2025-09-06 11:40:37', '2025-09-06 11:41:52', 100.00, NULL, 14),
(92, 1, 26, 130, 9, 38, 13, 132, '2025-09-06 15:54:44', 'confirmed', '2025-09-06 15:54:44', '2025-09-06 16:04:52', 100.00, NULL, 14),
(94, 1, 26, 133, 9, 39, 14, 134, '2025-09-06 17:26:27', 'cancelled', '2025-09-06 17:26:27', '2025-09-06 17:27:23', 200.00, NULL, 16),
(95, 1, 26, 134, 9, 39, 14, 135, '2025-09-06 17:30:37', 'confirmed', '2025-09-06 17:30:37', '2025-09-06 17:31:46', 200.00, 1, 16),
(97, 1, 26, 136, 10, 38, 13, 137, '2025-09-18 11:17:41', 'Accepted', '2025-09-18 11:17:41', '2025-09-18 11:18:15', 100.00, NULL, 14),
(98, 1, 26, 137, 9, 38, 13, 138, '2025-09-18 11:28:37', 'pending', '2025-09-18 11:28:37', '2025-09-18 11:28:37', 100.00, 1, 14);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `payment_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` varchar(20) NOT NULL,
  `payment_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `customer_id`, `payment_amount`, `payment_method`, `payment_status`, `payment_date`) VALUES
(84, 1, 200.00, 'upi', 'paid', '2025-04-04 16:35:40'),
(85, 1, 150.00, 'Cash', 'Paid', '2025-08-31 11:19:16'),
(86, 1, 150.00, 'UPI', 'Paid', '2025-08-31 06:21:21'),
(87, 1, 150.00, 'UPI', 'Paid', '2025-08-31 06:22:47'),
(88, 1, 150.00, 'UPI', 'Paid', '2025-08-31 06:22:50'),
(89, 1, 150.00, 'Cash', 'Paid', '2025-08-31 06:29:06'),
(90, 1, 150.00, 'Cash', 'Paid', '2025-08-31 06:30:27'),
(91, 1, 150.00, 'Card', 'Paid', '2025-08-31 06:35:30'),
(92, 1, 150.00, 'Card', 'Paid', '2025-08-31 06:44:30'),
(93, 1, 150.00, 'Cash', 'Paid', '2025-08-31 06:48:52'),
(94, 1, 150.00, 'Cash', 'Paid', '2025-08-31 06:49:49'),
(95, 1, 150.00, 'Card', 'Paid', '2025-08-31 06:55:44'),
(96, 1, 150.00, 'Cash', 'Paid', '2025-08-31 07:03:34'),
(97, 1, 150.00, 'Cash', 'Paid', '2025-08-31 07:06:28'),
(98, 1, 150.00, 'Card', 'Paid', '2025-08-31 07:11:47'),
(99, 1, 150.00, 'UPI', 'Paid', '2025-08-31 07:21:35'),
(100, 1, 150.00, 'Cash', 'Paid', '2025-08-31 07:28:20'),
(101, 1, 150.00, 'Card', 'Paid', '2025-08-31 07:36:40'),
(102, 1, 150.00, 'Card', 'Paid', '2025-08-31 07:38:34'),
(103, 1, 150.00, 'Cash', 'Paid', '2025-08-31 07:46:07'),
(104, 1, 150.00, 'Cash', 'Paid', '2025-08-31 07:47:23'),
(105, 1, 150.00, 'Cash', 'Paid', '2025-08-31 07:52:47'),
(106, 1, 150.00, 'Cash', 'Paid', '2025-08-31 07:55:54'),
(107, 1, 150.00, 'Cash', 'Paid', '2025-08-31 08:02:04'),
(108, 1, 150.00, 'Cash', 'Paid', '2025-08-31 08:04:11'),
(109, 1, 150.00, 'Cash', 'Paid', '2025-08-31 08:08:47'),
(110, 1, 150.00, 'Cash', 'Paid', '2025-08-31 08:12:34'),
(111, 1, 150.00, 'Card', 'Paid', '2025-08-31 08:13:09'),
(112, 1, 150.00, 'Cash', 'Paid', '2025-08-31 08:17:25'),
(113, 1, 150.00, 'Cash', 'Paid', '2025-08-31 08:17:50'),
(114, 1, 150.00, 'Cash', 'Paid', '2025-08-31 08:20:54'),
(115, 1, 150.00, 'Cash', 'Paid', '2025-08-31 08:22:09'),
(116, 1, 150.00, 'Cash', 'Paid', '2025-08-31 08:25:14'),
(117, 1, 150.00, 'Cash', 'Paid', '2025-08-31 08:27:33'),
(118, 1, 150.00, 'Cash', 'Paid', '2025-08-31 09:07:47'),
(119, 1, 150.00, 'Card', 'Paid', '2025-08-31 09:22:24'),
(120, 1, 150.00, 'UPI', 'Paid', '2025-08-31 09:23:24'),
(121, 1, 150.00, 'Cash', 'Paid', '2025-08-31 09:26:35'),
(122, 1, 150.00, 'Cash', 'Paid', '2025-08-31 09:29:32'),
(123, 1, 150.00, 'Cash', 'Paid', '2025-08-31 09:30:27'),
(124, 1, 150.00, 'Cash', 'Paid', '2025-08-31 09:31:34'),
(125, 1, 150.00, 'UPI', 'Paid', '2025-08-31 09:35:55'),
(126, 1, 150.00, 'UPI', 'Paid', '2025-08-31 09:41:05'),
(127, 1, 150.00, 'Card', 'Paid', '2025-08-31 09:43:47'),
(128, 1, 150.00, 'Cash', 'Paid', '2025-08-31 10:00:39'),
(129, 1, 150.00, 'Cash', 'Paid', '2025-09-04 12:06:09'),
(130, 1, 200.00, 'Card', 'Paid', '2025-09-06 05:30:26'),
(131, 1, 100.00, 'UPI', 'Paid', '2025-09-06 06:10:37'),
(132, 1, 100.00, 'Cash', 'Paid', '2025-09-06 10:24:44'),
(133, 1, 100.00, 'Card', 'Paid', '2025-09-06 10:56:59'),
(134, 1, 200.00, 'Cash', 'Paid', '2025-09-06 11:56:27'),
(135, 1, 200.00, 'Cash', 'Paid', '2025-09-06 12:00:37'),
(136, 1, 200.00, 'Cash', 'Paid', '2025-09-07 14:08:31'),
(137, 1, 100.00, 'Cash', 'Paid', '2025-09-18 05:47:41'),
(138, 1, 100.00, 'Cash', 'Paid', '2025-09-18 05:58:37');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_image` varchar(100) DEFAULT NULL,
  `product_buy_price` decimal(10,2) NOT NULL,
  `product_sell_price` decimal(10,2) NOT NULL,
  `product_quantity` int(11) NOT NULL DEFAULT 0,
  `product_percentage_discount` decimal(5,2) DEFAULT NULL,
  `product_desc` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_image`, `product_buy_price`, `product_sell_price`, `product_quantity`, `product_percentage_discount`, `product_desc`) VALUES
(11, 'oil', 'uploads/products/11/68b2ed464deeb.jpg', 200.00, 250.00, 8, 10.00, 'engine oil'),
(12, 'Oil ', 'uploads/products/12/68c31926b4d2c.png', 2000.00, 1900.00, 2, 10.00, 'best oil');

-- --------------------------------------------------------

--
-- Table structure for table `product_and_branch`
--

CREATE TABLE `product_and_branch` (
  `branch_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_and_branch`
--

INSERT INTO `product_and_branch` (`branch_id`, `product_id`) VALUES
(14, 11),
(14, 12);

-- --------------------------------------------------------

--
-- Table structure for table `serviceplans`
--

CREATE TABLE `serviceplans` (
  `plan_id` int(11) NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `plan_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `serviceplans`
--

INSERT INTO `serviceplans` (`plan_id`, `service_id`, `plan_name`, `plan_price`) VALUES
(13, 38, 'normal', 100.00),
(14, 39, 'normal', 200.00);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `name`, `description`, `image`) VALUES
(38, 'washing', NULL, 'uploads/services/38_1756385776.jpg'),
(39, 'Water Wash', NULL, 'uploads/services/39_1756548575.jpg'),
(40, 'TVPM Service', NULL, 'uploads/services/40/client-say (3).png'),
(41, 'basic service', NULL, 'uploads/services/41/Screenshot 2025-03-08 213549.png');

-- --------------------------------------------------------

--
-- Table structure for table `service_and_branch`
--

CREATE TABLE `service_and_branch` (
  `branch_id` int(11) NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_and_branch`
--

INSERT INTO `service_and_branch` (`branch_id`, `service_id`) VALUES
(14, 38),
(14, 41),
(16, 39),
(17, 40);

-- --------------------------------------------------------

--
-- Table structure for table `slots`
--

CREATE TABLE `slots` (
  `slot_id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slots`
--

INSERT INTO `slots` (`slot_id`, `start_time`, `end_time`) VALUES
(1, '08:00:00', '08:30:00'),
(2, '08:30:00', '09:00:00'),
(3, '09:00:00', '09:30:00'),
(4, '09:30:00', '10:00:00'),
(5, '10:00:00', '10:30:00'),
(6, '10:30:00', '11:00:00'),
(7, '11:00:00', '11:30:00'),
(8, '11:30:00', '12:00:00'),
(9, '12:00:00', '12:30:00'),
(10, '12:30:00', '13:00:00'),
(11, '13:00:00', '13:30:00'),
(12, '13:30:00', '14:00:00'),
(13, '14:00:00', '14:30:00'),
(14, '14:30:00', '15:00:00'),
(15, '15:00:00', '15:30:00'),
(16, '15:30:00', '16:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booked_slots`
--
ALTER TABLE `booked_slots`
  ADD PRIMARY KEY (`booked_slot_id`),
  ADD UNIQUE KEY `unique_booking` (`employee_id`,`slot_id`,`date`);

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`branch_id`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`car_id`),
  ADD UNIQUE KEY `car_number` (`car_number`),
  ADD KEY `fk_car_customer` (`customer_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `customer_email` (`customer_email`),
  ADD UNIQUE KEY `customer_phone` (`customer_phone`);

--
-- Indexes for table `customer_address`
--
ALTER TABLE `customer_address`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `employee_phone` (`employee_phone`),
  ADD UNIQUE KEY `employee_email` (`employee_email`);

--
-- Indexes for table `employeeslots`
--
ALTER TABLE `employeeslots`
  ADD PRIMARY KEY (`employee_slot_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `slot_id` (`slot_id`);

--
-- Indexes for table `employee_ratings`
--
ALTER TABLE `employee_ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `emp_and_branch`
--
ALTER TABLE `emp_and_branch`
  ADD PRIMARY KEY (`branch_id`,`employee_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `fk_login_customer` (`customer_id`),
  ADD KEY `fk_login_employee` (`employee_id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`manager_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `idx_customer` (`customer_id`),
  ADD KEY `idx_employee` (`employee_id`),
  ADD KEY `idx_booked_slot` (`booked_slot_id`),
  ADD KEY `idx_car` (`car_id`),
  ADD KEY `idx_service` (`service_id`),
  ADD KEY `idx_plan` (`plan_id`),
  ADD KEY `idx_payment` (`payment_id`),
  ADD KEY `fk_orders_branch` (`branch_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_and_branch`
--
ALTER TABLE `product_and_branch`
  ADD PRIMARY KEY (`branch_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `serviceplans`
--
ALTER TABLE `serviceplans`
  ADD PRIMARY KEY (`plan_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `service_and_branch`
--
ALTER TABLE `service_and_branch`
  ADD PRIMARY KEY (`branch_id`,`service_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `slots`
--
ALTER TABLE `slots`
  ADD PRIMARY KEY (`slot_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booked_slots`
--
ALTER TABLE `booked_slots`
  MODIFY `booked_slot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customer_address`
--
ALTER TABLE `customer_address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `employeeslots`
--
ALTER TABLE `employeeslots`
  MODIFY `employee_slot_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_ratings`
--
ALTER TABLE `employee_ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `manager_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `serviceplans`
--
ALTER TABLE `serviceplans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `slots`
--
ALTER TABLE `slots`
  MODIFY `slot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `fk_car_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer_address`
--
ALTER TABLE `customer_address`
  ADD CONSTRAINT `customer_address_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employeeslots`
--
ALTER TABLE `employeeslots`
  ADD CONSTRAINT `employeeslots_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employeeslots_ibfk_2` FOREIGN KEY (`slot_id`) REFERENCES `slots` (`slot_id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_ratings`
--
ALTER TABLE `employee_ratings`
  ADD CONSTRAINT `employee_ratings_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `emp_and_branch`
--
ALTER TABLE `emp_and_branch`
  ADD CONSTRAINT `emp_and_branch_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`branch_id`),
  ADD CONSTRAINT `emp_and_branch_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `fk_login_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_login_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `login_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `managers` (`manager_id`) ON DELETE SET NULL;

--
-- Constraints for table `managers`
--
ALTER TABLE `managers`
  ADD CONSTRAINT `managers_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`branch_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_booked_slot` FOREIGN KEY (`booked_slot_id`) REFERENCES `booked_slots` (`booked_slot_id`),
  ADD CONSTRAINT `fk_car` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`),
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `fk_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `fk_orders_branch` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`branch_id`),
  ADD CONSTRAINT `fk_payment` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`payment_id`),
  ADD CONSTRAINT `fk_plan` FOREIGN KEY (`plan_id`) REFERENCES `serviceplans` (`plan_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_and_branch`
--
ALTER TABLE `product_and_branch`
  ADD CONSTRAINT `product_and_branch_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`branch_id`),
  ADD CONSTRAINT `product_and_branch_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `serviceplans`
--
ALTER TABLE `serviceplans`
  ADD CONSTRAINT `serviceplans_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE;

--
-- Constraints for table `service_and_branch`
--
ALTER TABLE `service_and_branch`
  ADD CONSTRAINT `service_and_branch_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`branch_id`),
  ADD CONSTRAINT `service_and_branch_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
