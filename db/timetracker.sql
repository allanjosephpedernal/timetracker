-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2022 at 01:01 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `timetracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `first_name` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `datetime_added` datetime DEFAULT NULL,
  `datetime_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `first_name`, `last_name`, `created_by`, `datetime_added`, `datetime_updated`) VALUES
(4, 'Johns', 'Does', 1, '2022-03-10 10:53:43', '2022-03-10 10:58:47');

-- --------------------------------------------------------

--
-- Table structure for table `time_records`
--

CREATE TABLE `time_records` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_added` date DEFAULT NULL,
  `time_in` datetime DEFAULT NULL,
  `time_out` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `time_records`
--

INSERT INTO `time_records` (`id`, `employee_id`, `user_id`, `date_added`, `time_in`, `time_out`) VALUES
(1, 4, 1, '2022-03-10', '2022-03-10 04:19:57', '2022-03-10 19:19:57'),
(2, 4, 1, '2022-03-10', '2022-03-10 12:33:52', NULL),
(3, 4, 1, '2022-03-10', '2022-03-10 12:34:19', NULL),
(4, 4, 1, '2022-03-10', '2022-03-10 12:34:53', '2022-03-10 12:35:29'),
(5, 4, 1, '2022-03-10', '2022-03-10 12:35:43', '2022-03-10 12:35:53'),
(6, 4, 1, '2022-03-10', '2022-03-10 12:44:09', '2022-03-10 12:44:21'),
(7, 4, 1, '2022-03-10', '2022-03-10 12:49:06', '2022-03-10 12:49:30'),
(8, 4, 1, '2022-03-10', '2022-03-10 12:50:16', '2022-03-10 12:50:37'),
(9, 4, 1, '2022-03-10', '2022-03-10 12:51:03', '2022-03-10 12:51:17'),
(10, 4, 1, '2022-03-10', '2022-03-10 12:52:34', '2022-03-10 12:53:43'),
(11, 4, 1, '2022-03-10', '2022-03-10 12:57:10', '2022-03-10 12:57:18'),
(12, 4, 1, '2022-03-10', '2022-03-10 12:59:25', '2022-03-10 12:59:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_name` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_password` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_type` int(11) DEFAULT NULL,
  `datetime_added` datetime DEFAULT NULL,
  `datetime_modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `user_password`, `user_type`, `datetime_added`, `datetime_modified`) VALUES
(1, 'super_admin', '$2y$10$02VIRbNGQtdDHqAz5BZCI.TZ7HO7U7tCSFvLFVNlOYTKWpV3eJJZC', 1, '2022-03-10 00:22:39', NULL),
(20, 'Admin', '$2y$10$/JE8xflcMPJkoFeAOtv4nunnZSMJWkh/MEov94xzWzu29ZWuvXHd6', 2, '2022-03-10 13:01:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `id` int(11) NOT NULL,
  `name` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `name`) VALUES
(1, 'Super Admin'),
(2, 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `datetime_added` (`datetime_added`),
  ADD KEY `datetime_updated` (`datetime_updated`);

--
-- Indexes for table `time_records`
--
ALTER TABLE `time_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `date_added` (`date_added`),
  ADD KEY `time_in` (`time_in`),
  ADD KEY `time_out` (`time_out`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `datetime_added` (`datetime_added`),
  ADD KEY `datetime_modified` (`datetime_modified`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `time_records`
--
ALTER TABLE `time_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
