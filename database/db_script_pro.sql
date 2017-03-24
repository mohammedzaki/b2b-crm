-- phpMyAdmin SQL Dump
-- version 4.0.10.18
-- https://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Mar 22, 2017 at 10:08 PM
-- Server version: 5.6.35
-- PHP Version: 5.6.30
SET FOREIGN_KEY_CHECKS = 0;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bbadvcom_b2bpos_pro`
--

-- --------------------------------------------------------

--
-- Table structure for table `absent_types`
--

DROP TABLE IF EXISTS `absent_types`;
CREATE TABLE IF NOT EXISTS `absent_types` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `salary_deduction` smallint(4) DEFAULT NULL,
  `editable_deduction` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=3 ;

--
-- Truncate table before insert `absent_types`
--

TRUNCATE TABLE `absent_types`;
--
-- Dumping data for table `absent_types`
--

INSERT INTO `absent_types` (`id`, `name`, `salary_deduction`, `editable_deduction`, `created_at`, `updated_at`) VALUES
(1, 'غياب بإذن', 0, 0, NULL, NULL),
(2, 'غياب بدون إذن', 100, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
CREATE TABLE IF NOT EXISTS `attendances` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `shift` tinyint(4) DEFAULT NULL,
  `check_in` timestamp NULL DEFAULT NULL,
  `check_out` timestamp NULL DEFAULT NULL,
  `absent_check` tinyint(1) DEFAULT NULL,
  `absent_type_id` tinyint(4) DEFAULT '1',
  `absent_deduction` double DEFAULT NULL,
  `salary_deduction` double DEFAULT NULL,
  `mokaf` double DEFAULT NULL,
  `notes` varchar(1000) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `process_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_attend` (`date`,`employee_id`,`shift`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=169 ;

--
-- Truncate table before insert `attendances`
--

TRUNCATE TABLE `attendances`;
--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `date`, `shift`, `check_in`, `check_out`, `absent_check`, `absent_type_id`, `absent_deduction`, `salary_deduction`, `mokaf`, `notes`, `employee_id`, `process_id`, `created_at`, `updated_at`) VALUES
(1, '2017-03-01', 1, '2017-03-01 05:00:00', '2017-03-01 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 4, 7, '2017-03-16 15:31:25', '2017-03-16 15:31:48'),
(2, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2017-03-16 15:31:25', '2017-03-16 15:31:25'),
(3, '2017-03-02', 1, '2017-03-02 08:00:00', '2017-03-02 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 4, 6, '2017-03-16 15:32:08', '2017-03-16 15:32:37'),
(4, '2017-03-03', 1, '2017-03-03 05:00:00', '2017-03-04 01:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 4, 7, '2017-03-16 15:33:08', '2017-03-16 15:33:30'),
(5, '2017-03-04', 1, '2017-03-04 10:00:00', '2017-03-04 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 4, 2, '2017-03-16 15:34:06', '2017-03-16 15:34:27'),
(6, '2017-03-05', 1, '2017-03-05 05:00:00', '2017-03-05 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 4, 2, '2017-03-16 15:35:06', '2017-03-16 15:35:41'),
(7, '2017-03-06', 1, '2017-03-06 10:00:00', '2017-03-06 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 4, 3, '2017-03-16 15:36:03', '2017-03-16 15:36:22'),
(8, '2017-03-07', 1, '2017-03-07 08:00:00', '2017-03-07 20:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 4, 3, '2017-03-16 15:36:52', '2017-03-16 15:37:17'),
(9, '2017-03-08', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 4, -1, '2017-03-16 15:37:48', '2017-03-16 15:37:48'),
(10, '2017-03-09', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 4, -1, '2017-03-16 15:38:02', '2017-03-16 15:52:00'),
(11, '2017-03-10', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 4, -1, '2017-03-16 15:38:21', '2017-03-16 15:38:21'),
(12, '2017-03-11', 1, '2017-03-11 08:30:00', '2017-03-11 21:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 4, 4, '2017-03-16 15:39:36', '2017-03-16 15:39:37'),
(13, '2017-03-12', 1, '2017-03-12 08:15:00', '2017-03-12 23:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, '2017-03-16 15:40:15', '2017-03-16 15:40:43'),
(14, '2017-03-13', 1, '2017-03-13 08:30:00', '2017-03-14 02:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 4, 7, '2017-03-16 15:41:12', '2017-03-16 15:41:34'),
(15, '2017-03-14', 1, '2017-03-14 10:00:00', '2017-03-14 21:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, '2017-03-16 15:41:59', '2017-03-16 15:42:23'),
(16, '2017-03-15', 1, '2017-03-15 08:00:00', '2017-03-15 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, '2017-03-16 15:43:14', '2017-03-16 15:43:35'),
(17, '2017-03-16', 1, '2017-03-16 08:00:00', '2017-03-16 19:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, '2017-03-16 15:44:02', '2017-03-18 10:59:38'),
(18, '2017-03-01', 1, '2017-03-01 05:00:00', '2017-03-01 16:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 5, 7, '2017-03-16 15:44:56', '2017-03-16 15:45:30'),
(19, '2017-03-02', 1, '2017-03-02 08:00:00', '2017-03-02 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 5, 7, '2017-03-16 15:45:58', '2017-03-16 15:46:21'),
(20, '2017-03-03', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 5, -1, '2017-03-16 15:46:41', '2017-03-16 15:46:41'),
(21, '2017-03-04', 1, '2017-03-04 08:00:00', '2017-03-04 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 5, 6, '2017-03-16 15:47:37', '2017-03-16 15:47:50'),
(22, '2017-03-05', 1, '2017-03-05 08:00:00', '2017-03-05 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 5, 3, '2017-03-16 15:48:22', '2017-03-16 15:48:50'),
(23, '2017-03-06', 1, '2017-03-06 09:30:00', '2017-03-06 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 5, 2, '2017-03-16 15:49:18', '2017-03-16 15:49:50'),
(24, '2017-03-07', 1, '2017-03-07 08:00:00', '2017-03-07 20:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 5, 4, '2017-03-16 15:50:13', '2017-03-16 15:50:38'),
(25, '2017-03-08', 1, '2017-03-08 08:00:00', '2017-03-09 02:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 5, 7, '2017-03-16 15:51:06', '2017-03-16 15:51:35'),
(26, '2017-03-10', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 5, -1, '2017-03-16 15:52:11', '2017-03-16 15:52:12'),
(27, '2017-03-11', 1, '2017-03-11 11:10:00', '2017-03-11 18:00:00', NULL, NULL, NULL, 80, NULL, 'خصم يوم كامل 10 ساعات   أ / بيشوى', 5, 1, '2017-03-16 15:53:51', '2017-03-16 15:53:51'),
(28, '2017-03-12', 1, '2017-03-12 09:00:00', '2017-03-12 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 5, 1, '2017-03-16 15:55:04', '2017-03-16 15:55:50'),
(29, '2017-03-13', 1, '2017-03-13 11:00:00', '2017-03-14 01:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 5, 5, '2017-03-16 15:56:15', '2017-03-16 15:56:42'),
(30, '2017-03-14', 1, '2017-03-14 10:00:00', '2017-03-14 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 5, 1, '2017-03-16 15:57:13', '2017-03-16 15:57:47'),
(31, '2017-03-15', 1, '2017-03-15 08:45:00', '2017-03-15 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 5, 5, '2017-03-16 15:58:29', '2017-03-16 15:59:12'),
(32, '2017-03-01', 1, '2017-03-01 07:00:00', '2017-03-01 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 6, 1, '2017-03-16 16:00:10', '2017-03-16 16:01:23'),
(33, '2017-03-02', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 6, -1, '2017-03-16 16:01:51', '2017-03-16 16:01:51'),
(34, '2017-03-03', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 6, -1, '2017-03-16 16:02:05', '2017-03-16 16:02:05'),
(35, '2017-03-04', 1, '2017-03-04 08:40:00', '2017-03-04 18:20:00', NULL, NULL, NULL, NULL, NULL, NULL, 6, 7, '2017-03-16 16:02:47', '2017-03-16 16:02:47'),
(36, '2017-03-05', 1, '2017-03-05 08:10:00', '2017-03-05 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 6, 4, '2017-03-16 16:03:26', '2017-03-16 16:03:50'),
(37, '2017-03-06', 1, '2017-03-06 08:15:00', '2017-03-06 22:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 6, 7, '2017-03-16 16:04:49', '2017-03-16 16:05:20'),
(38, '2017-03-07', 1, '2017-03-07 09:00:00', '2017-03-07 20:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 6, 1, '2017-03-16 16:06:09', '2017-03-16 16:06:49'),
(39, '2017-03-08', 1, '2017-03-08 08:15:00', '2017-03-09 03:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 6, 2, '2017-03-16 16:07:30', '2017-03-16 16:07:57'),
(40, '2017-03-09', 1, '2017-03-09 13:00:00', '2017-03-09 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 6, 5, '2017-03-16 16:21:13', '2017-03-16 16:21:40'),
(41, '2017-03-10', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 6, -1, '2017-03-16 16:23:15', '2017-03-16 16:23:16'),
(42, '2017-03-16', 1, '2017-03-16 10:00:00', '2017-03-16 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 5, 6, '2017-03-18 10:06:24', '2017-03-18 10:06:59'),
(43, '2017-03-17', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 5, -1, '2017-03-18 10:07:33', '2017-03-18 10:07:33'),
(44, '2017-03-11', NULL, NULL, NULL, 1, 2, NULL, NULL, NULL, 'غياب بسبب عدم الرد على التليفون وعدم متابعة بتاع الكاوتش', 6, -1, '2017-03-18 10:55:46', '2017-03-18 10:55:46'),
(45, '2017-03-12', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 6, -1, '2017-03-18 10:56:05', '2017-03-18 10:56:05'),
(46, '2017-03-13', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 6, -1, '2017-03-18 10:56:19', '2017-03-18 10:56:19'),
(47, '2017-03-14', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 6, -1, '2017-03-18 10:56:32', '2017-03-18 10:56:32'),
(48, '2017-03-15', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 6, -1, '2017-03-18 10:56:48', '2017-03-18 10:56:48'),
(49, '2017-03-16', 1, '2017-03-16 09:30:00', '2017-03-16 19:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 6, 7, '2017-03-18 10:58:11', '2017-03-18 10:58:11'),
(50, '2017-03-17', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 6, -1, '2017-03-18 10:58:33', '2017-03-18 10:58:33'),
(51, '2017-03-17', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 4, -1, '2017-03-18 11:00:03', '2017-03-18 11:00:03'),
(52, '2017-03-01', 1, '2017-03-01 05:00:00', '2017-03-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 7, 3, '2017-03-18 11:01:05', '2017-03-18 11:01:34'),
(53, '2017-03-02', 1, '2017-03-02 08:00:00', '2017-03-02 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 7, 7, '2017-03-18 11:02:07', '2017-03-18 11:02:44'),
(54, '2017-03-03', 1, '2017-03-03 05:00:00', '2017-03-04 01:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 7, 4, '2017-03-18 11:03:10', '2017-03-18 11:03:38'),
(55, '2017-03-04', 1, '2017-03-04 09:30:00', '2017-03-04 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 7, 1, '2017-03-18 11:04:13', '2017-03-18 11:04:34'),
(56, '2017-03-05', 1, '2017-03-05 06:30:00', '2017-03-05 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 7, 1, '2017-03-18 11:05:00', '2017-03-18 11:05:19'),
(57, '2017-03-06', 1, '2017-03-06 06:30:00', '2017-03-06 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 7, 1, '2017-03-18 11:05:40', '2017-03-18 11:05:57'),
(58, '2017-03-07', 1, '2017-03-07 08:00:00', '2017-03-07 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 7, 9, '2017-03-18 11:07:00', '2017-03-18 11:07:20'),
(59, '2017-03-08', 1, '2017-03-07 23:30:00', '2017-03-09 03:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 7, 10, '2017-03-18 11:08:25', '2017-03-18 11:08:52'),
(60, '2017-03-09', 1, '2017-03-09 13:00:00', '2017-03-09 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 7, 1, '2017-03-18 11:09:18', '2017-03-18 11:10:19'),
(61, '2017-03-10', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 7, -1, '2017-03-18 11:11:02', '2017-03-18 11:11:02'),
(62, '2017-03-11', 1, '2017-03-11 08:20:00', '2017-03-11 21:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 7, 1, '2017-03-18 11:11:49', '2017-03-18 11:11:49'),
(63, '2017-03-12', 1, '2017-03-12 08:00:00', '2017-03-12 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 7, 1, '2017-03-18 11:12:08', '2017-03-18 11:12:28'),
(64, '2017-03-13', 1, '2017-03-13 10:00:00', '2017-03-14 01:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 7, 5, '2017-03-18 11:12:52', '2017-03-18 11:13:30'),
(65, '2017-03-14', 1, '2017-03-14 10:00:00', '2017-03-14 19:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 7, 5, '2017-03-18 11:13:54', '2017-03-18 11:14:15'),
(66, '2017-03-15', 1, '2017-03-15 09:00:00', '2017-03-15 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 7, 1, '2017-03-18 11:14:49', '2017-03-18 11:15:14'),
(67, '2017-03-16', 1, '2017-03-16 08:55:00', '2017-03-16 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 7, 6, '2017-03-18 11:15:51', '2017-03-18 11:16:13'),
(68, '2017-03-01', 1, '2017-03-01 05:00:00', '2017-03-01 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 8, 6, '2017-03-18 11:17:06', '2017-03-18 11:17:26'),
(69, '2017-03-02', 1, '2017-03-02 08:00:00', '2017-03-02 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 8, 7, '2017-03-18 11:17:48', '2017-03-18 11:18:12'),
(70, '2017-03-03', 1, '2017-03-03 05:00:00', '2017-03-04 01:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 8, 1, '2017-03-18 11:18:36', '2017-03-18 11:19:01'),
(71, '2017-03-04', 1, '2017-03-04 08:00:00', '2017-03-04 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 8, 8, '2017-03-18 11:19:26', '2017-03-18 11:19:48'),
(72, '2017-03-05', 1, '2017-03-05 05:00:00', '2017-03-05 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 8, 5, '2017-03-18 11:20:17', '2017-03-18 11:20:33'),
(73, '2017-03-06', 1, '2017-03-06 06:00:00', '2017-03-06 22:30:00', NULL, 0, 0, NULL, NULL, NULL, 8, 1, '2017-03-18 11:20:56', '2017-03-18 11:43:14'),
(74, '2017-03-01', 1, '2017-03-01 08:00:00', '2017-03-01 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, '2017-03-18 11:24:12', '2017-03-18 11:24:35'),
(75, '2017-03-02', 1, '2017-03-02 06:00:00', '2017-03-02 19:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, '2017-03-18 11:25:13', '2017-03-18 11:25:42'),
(76, '2017-03-03', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 11, -1, '2017-03-18 11:26:03', '2017-03-18 11:26:03'),
(77, '2017-03-04', 1, '2017-03-04 08:00:00', '2017-03-04 18:20:00', NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, '2017-03-18 11:31:00', '2017-03-18 11:31:22'),
(78, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2017-03-18 11:31:00', '2017-03-18 11:31:00'),
(79, '2017-03-05', 1, '2017-03-05 08:00:00', '2017-03-05 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, '2017-03-18 11:31:55', '2017-03-18 11:32:11'),
(80, '2017-03-06', 1, '2017-03-06 08:00:00', '2017-03-06 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, '2017-03-18 11:32:36', '2017-03-18 11:34:18'),
(82, '2017-03-07', 1, '2017-03-07 08:00:00', '2017-03-07 20:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, '2017-03-18 11:34:35', '2017-03-18 11:34:57'),
(83, '2017-03-08', 1, '2017-03-08 08:00:00', '2017-03-08 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, '2017-03-18 11:35:16', '2017-03-18 11:35:35'),
(84, '2017-03-09', 1, '2017-03-09 08:00:00', '2017-03-09 22:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, '2017-03-18 11:35:54', '2017-03-18 11:36:27'),
(85, '2017-03-10', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 11, -1, '2017-03-18 11:36:55', '2017-03-18 11:36:55'),
(86, '2017-03-11', 1, '2017-03-11 08:00:00', '2017-03-11 20:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, '2017-03-18 11:37:22', '2017-03-18 11:37:22'),
(87, '2017-03-12', 1, '2017-03-12 08:00:00', '2017-03-12 19:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, '2017-03-18 11:37:48', '2017-03-18 11:38:10'),
(88, '2017-03-13', 1, '2017-03-13 08:00:00', '2017-03-13 19:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, '2017-03-18 11:38:29', '2017-03-18 11:39:03'),
(89, '2017-03-14', 1, '2017-03-14 08:00:00', '2017-03-14 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, '2017-03-18 11:39:24', '2017-03-18 11:40:01'),
(90, '2017-03-15', 1, '2017-03-15 08:00:00', '2017-03-15 20:30:00', NULL, NULL, NULL, NULL, NULL, 'ذهاب الى صلاح الدين ومنزل أ / بيشوى بعد العمل', 11, NULL, '2017-03-18 11:40:20', '2017-03-18 11:41:23'),
(91, '2017-03-16', 1, '2017-03-16 06:45:00', '2017-03-16 19:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, '2017-03-18 11:42:06', '2017-03-18 11:42:33'),
(92, '2017-03-07', 1, '2017-03-07 05:00:00', '2017-03-07 22:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 8, 9, '2017-03-18 11:44:35', '2017-03-18 11:44:57'),
(93, '2017-03-08', 1, '2017-03-08 08:00:00', '2017-03-09 03:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 8, 1, '2017-03-18 11:45:23', '2017-03-18 11:45:42'),
(94, '2017-03-09', 1, '2017-03-09 08:30:00', '2017-03-09 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 8, 3, '2017-03-18 11:46:08', '2017-03-18 11:46:29'),
(95, '2017-03-10', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 8, -1, '2017-03-18 11:46:50', '2017-03-18 11:46:50'),
(96, '2017-03-11', 1, '2017-03-11 08:00:00', '2017-03-11 21:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 8, 5, '2017-03-18 11:47:22', '2017-03-18 11:47:23'),
(97, '2017-03-12', 1, '2017-03-12 08:00:00', '2017-03-12 23:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 8, 1, '2017-03-18 11:47:46', '2017-03-18 11:48:09'),
(98, '2017-03-13', 1, '2017-03-13 09:00:00', '2017-03-14 02:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 8, 1, '2017-03-18 11:48:41', '2017-03-18 11:49:02'),
(99, '2017-03-14', 1, '2017-03-14 08:00:00', '2017-03-14 21:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 8, 2, '2017-03-18 11:49:21', '2017-03-18 11:49:42'),
(100, '2017-03-15', 1, '2017-03-15 08:00:00', '2017-03-15 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 8, 5, '2017-03-18 11:50:03', '2017-03-18 11:50:21'),
(101, '2017-03-16', 1, '2017-03-16 08:00:00', '2017-03-16 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 8, 1, '2017-03-18 11:50:44', '2017-03-18 11:51:02'),
(102, '2017-03-17', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 8, -1, '2017-03-18 11:51:19', '2017-03-18 11:51:19'),
(103, '2017-03-18', 1, '2017-03-18 10:30:00', '2017-03-18 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 5, 7, '2017-03-19 15:14:09', '2017-03-19 15:14:09'),
(104, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2017-03-19 15:14:09', '2017-03-19 15:14:09'),
(105, '2017-03-18', 1, '2017-03-18 08:15:00', '2017-03-18 18:40:00', NULL, NULL, NULL, NULL, NULL, NULL, 6, 12, '2017-03-19 15:15:29', '2017-03-19 15:15:29'),
(106, '2017-03-18', 1, '2017-03-18 08:00:00', '2017-03-18 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 8, 12, '2017-03-19 15:29:21', '2017-03-19 15:29:21'),
(107, '2017-03-18', 1, '2017-03-18 08:00:00', '2017-03-18 23:00:00', NULL, NULL, NULL, 25, NULL, 'خصم نص يوم  ( بدء العمل ببرنامج ال mass )', 11, NULL, '2017-03-19 15:32:05', '2017-03-19 15:32:05'),
(109, '2017-03-07', 1, '2017-03-07 05:00:00', '2017-03-07 22:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 9, 1, '2017-03-19 15:35:56', '2017-03-19 15:36:22'),
(110, '2017-03-08', 1, '2017-03-08 08:00:00', '2017-03-08 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 9, 10, '2017-03-19 15:36:54', '2017-03-19 15:37:13'),
(111, '2017-03-09', 1, '2017-03-09 08:00:00', '2017-03-09 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 9, 1, '2017-03-19 15:37:41', '2017-03-19 15:38:09'),
(112, '2017-03-10', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 9, -1, '2017-03-19 15:38:24', '2017-03-19 15:38:24'),
(113, '2017-03-11', 1, '2017-03-11 08:00:00', '2017-03-11 21:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 9, 14, '2017-03-19 15:39:10', '2017-03-19 15:39:10'),
(114, '2017-03-12', 1, '2017-03-12 08:00:00', '2017-03-12 23:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 9, 15, '2017-03-19 15:39:47', '2017-03-19 15:40:26'),
(115, '2017-03-13', 1, '2017-03-13 08:00:00', '2017-03-13 23:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 9, 11, '2017-03-19 15:40:59', '2017-03-19 15:41:23'),
(116, '2017-03-14', 1, '2017-03-14 08:00:00', '2017-03-14 21:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 9, 1, '2017-03-19 15:42:43', '2017-03-19 15:43:04'),
(117, '2017-03-15', 1, '2017-03-15 10:00:00', '2017-03-15 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 9, 1, '2017-03-19 15:43:32', '2017-03-19 15:43:57'),
(118, '2017-03-16', 1, '2017-03-16 08:00:00', '2017-03-16 18:00:00', NULL, 0, 0, NULL, NULL, NULL, 9, 16, '2017-03-19 15:44:47', '2017-03-19 15:47:33'),
(119, '2017-03-17', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 9, -1, '2017-03-19 15:48:08', '2017-03-19 15:48:08'),
(120, '2017-03-18', 1, '2017-03-18 08:00:00', '2017-03-18 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 9, 7, '2017-03-19 15:49:53', '2017-03-19 15:49:53'),
(122, '2017-03-19', 1, '2017-03-19 08:00:00', '2017-03-19 20:45:00', NULL, NULL, NULL, NULL, NULL, NULL, 5, 17, '2017-03-19 15:51:01', '2017-03-20 06:15:25'),
(123, '2017-03-19', 1, '2017-03-19 08:30:00', '2017-03-19 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 6, 7, '2017-03-19 15:52:22', '2017-03-19 17:59:30'),
(124, '2017-03-19', 1, '2017-03-19 08:00:00', '2017-03-19 21:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 8, 7, '2017-03-19 15:52:55', '2017-03-20 06:15:04'),
(125, '2017-03-19', 1, '2017-03-19 08:00:00', '2017-03-19 21:30:00', NULL, 0, 0, NULL, NULL, NULL, 11, NULL, '2017-03-19 15:54:11', '2017-03-20 06:16:13'),
(126, '2017-03-18', 1, '2017-03-18 08:00:00', '2017-03-18 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 12, 7, '2017-03-19 15:54:56', '2017-03-19 15:55:24'),
(127, '2017-03-19', 1, '2017-03-19 08:00:00', '2017-03-19 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 12, 17, '2017-03-19 15:57:01', '2017-03-19 18:04:36'),
(128, '2017-03-19', 1, '2017-03-19 08:00:00', '2017-03-19 21:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 9, 17, '2017-03-19 18:04:15', '2017-03-20 06:14:31'),
(130, '2017-03-20', 1, '2017-03-20 08:25:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 7, '2017-03-20 06:34:46', '2017-03-20 06:34:46'),
(132, '2017-03-20', 1, '2017-03-20 09:00:00', '2017-03-21 04:00:00', NULL, 0, 0, NULL, NULL, NULL, 6, NULL, '2017-03-20 07:41:18', '2017-03-22 09:17:54'),
(133, '2017-03-20', 1, '2017-03-20 08:00:00', '2017-03-21 04:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 8, 7, '2017-03-20 07:49:51', '2017-03-21 16:53:29'),
(134, '2017-03-20', 1, '2017-03-20 08:00:00', '2017-03-21 04:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 9, 7, '2017-03-20 07:50:16', '2017-03-21 16:53:59'),
(135, '2017-03-20', 1, '2017-03-20 08:00:00', '2017-03-20 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, '2017-03-20 08:13:22', '2017-03-20 16:30:58'),
(136, '2017-03-20', 1, '2017-03-20 08:50:00', '2017-03-20 21:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 12, 7, '2017-03-20 08:14:49', '2017-03-21 16:54:40'),
(138, '2017-03-20', 1, '2017-03-20 13:00:00', '2017-03-20 21:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 5, 7, '2017-03-20 15:12:31', '2017-03-21 16:52:50'),
(139, '2017-03-18', 1, '2017-03-18 08:00:00', '2017-03-18 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 4, 7, '2017-03-20 15:24:37', '2017-03-20 15:25:16'),
(140, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2017-03-20 15:24:37', '2017-03-20 15:24:37'),
(141, '2017-03-21', 1, '2017-03-21 09:10:00', '2017-03-21 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 12, 2, '2017-03-21 16:57:59', '2017-03-22 09:20:45'),
(142, '2017-03-21', 1, '2017-03-21 08:00:00', '2017-03-21 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, '2017-03-21 16:58:17', '2017-03-22 09:22:51'),
(143, '2017-03-21', 1, '2017-03-21 09:35:00', '2017-03-21 21:20:00', NULL, NULL, NULL, NULL, NULL, NULL, 9, 9, '2017-03-21 16:58:53', '2017-03-22 09:20:12'),
(144, '2017-03-21', 1, '2017-03-21 08:00:00', '2017-03-21 21:20:00', NULL, NULL, NULL, NULL, NULL, NULL, 8, 9, '2017-03-21 16:59:31', '2017-03-22 09:19:38'),
(145, '2017-03-21', 1, '2017-03-21 09:50:00', '2017-03-21 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 5, 2, '2017-03-21 17:00:13', '2017-03-22 09:19:02'),
(146, '2017-03-01', 1, '2017-03-01 08:00:00', '2017-03-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2017-03-21 17:01:01', '2017-03-21 17:01:26'),
(147, '2017-03-02', 1, '2017-03-02 08:00:00', '2017-03-02 18:00:00', NULL, 0, 0, NULL, NULL, NULL, 1, 1, '2017-03-21 17:01:44', '2017-03-21 17:04:24'),
(148, '2017-03-03', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 1, -1, '2017-03-21 17:05:03', '2017-03-21 17:05:03'),
(149, '2017-03-04', 1, '2017-03-04 08:00:00', '2017-03-04 06:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2017-03-21 17:05:38', '2017-03-21 17:05:39'),
(150, '2017-03-05', 1, '2017-03-05 08:00:00', '2017-03-05 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2017-03-21 17:06:10', '2017-03-21 17:06:32'),
(151, '2017-03-06', 1, '2017-03-06 08:00:00', '2017-03-06 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2017-03-21 17:08:24', '2017-03-21 17:08:57'),
(152, '2017-03-07', 1, '2017-03-07 08:00:00', '2017-03-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2017-03-21 17:09:31', '2017-03-21 17:09:51'),
(153, '2017-03-08', 1, '2017-03-08 08:00:00', '2017-03-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2017-03-21 17:10:16', '2017-03-21 17:10:40'),
(154, '2017-03-09', 1, '2017-03-09 08:00:00', '2017-03-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2017-03-21 17:10:57', '2017-03-21 17:11:16'),
(155, '2017-03-10', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 1, -1, '2017-03-21 17:11:32', '2017-03-21 17:11:33'),
(156, '2017-03-11', 1, '2017-03-11 08:00:00', '2017-03-11 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2017-03-21 17:12:29', '2017-03-21 17:12:29'),
(157, '2017-03-12', 1, '2017-03-12 08:00:00', '2017-03-12 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2017-03-21 17:12:57', '2017-03-21 17:13:14'),
(158, '2017-03-13', 1, '2017-03-13 08:00:00', '2017-03-13 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2017-03-21 17:13:48', '2017-03-21 17:14:14'),
(159, '2017-03-14', 1, '2017-03-14 08:00:00', '2017-03-14 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2017-03-21 17:14:46', '2017-03-21 17:15:11'),
(160, '2017-03-15', 1, '2017-03-15 08:00:00', '2017-03-15 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2017-03-21 17:15:49', '2017-03-21 17:16:46'),
(161, '2017-03-16', 1, '2017-03-16 08:00:00', '2017-03-16 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2017-03-21 17:17:20', '2017-03-21 17:18:43'),
(162, '2017-03-17', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 1, -1, '2017-03-21 17:19:06', '2017-03-21 17:19:06'),
(163, '2017-03-18', 1, '2017-03-18 08:00:00', '2017-03-18 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2017-03-21 17:20:09', '2017-03-21 17:20:09'),
(164, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2017-03-21 17:20:09', '2017-03-21 17:20:09'),
(165, '2017-03-22', 1, '2017-03-22 08:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, 2, '2017-03-22 09:25:24', '2017-03-22 09:25:24'),
(166, '2017-03-22', 1, '2017-03-22 08:35:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, '2017-03-22 09:26:21', '2017-03-22 09:26:22'),
(167, '2017-03-22', 1, '2017-03-22 09:05:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, 2, '2017-03-22 09:26:46', '2017-03-22 09:26:46'),
(168, '2017-03-22', 1, '2017-03-22 08:30:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, '2017-03-22 09:27:49', '2017-03-22 09:27:49');

-- --------------------------------------------------------

--
-- Table structure for table `authorized_people`
--

DROP TABLE IF EXISTS `authorized_people`;
CREATE TABLE IF NOT EXISTS `authorized_people` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `jobtitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=12 ;

--
-- Truncate table before insert `authorized_people`
--

TRUNCATE TABLE `authorized_people`;
--
-- Dumping data for table `authorized_people`
--

INSERT INTO `authorized_people` (`id`, `client_id`, `name`, `jobtitle`, `telephone`, `email`, `created_at`, `updated_at`) VALUES
(1, 2, 'محمد عبد الفتاح', 'مدير مشروعات', '01202220762', 'mohamed.abdelfatah@leoburnett.com', NULL, NULL),
(2, 3, 'مهندسة نهال فتحى خليل', 'مدير عام تنفيذى المعمارى', '01227451336', 'nehal_petrojet@yahoo.com', NULL, NULL),
(3, 4, 'م/ محمود', 'مدير الشركة', '1009506655', 'bestprintcairo@yahoo.com', NULL, NULL),
(4, 6, 'ENG. Louay El-Zeiny', 'Engineering Department Manager', '01223731178', 'lelzeiny@smashmgmt.com', NULL, NULL),
(5, 6, 'Samah Chouman', 'Sales & Marketing Manager', '01099812641', 'schouman@smashmgmt.com', NULL, NULL),
(6, 7, 'MICHAEL GUIRGUIS', 'SALES MANAGER', '01201125448', 'info@spectrumegypt.com', NULL, NULL),
(7, 8, 'حسين', 'مدير المصنع', '01006540589', 'info@picoegypt.com', NULL, NULL),
(8, 9, 'Amir Wahid Youssef', 'Chief Executive Officer', '01288880107', 'amirw@visiongroupeg.com', NULL, NULL),
(9, 11, 'Elsha Youwakeem', 'مدير الشركة', '01221625204', 'lookup_media@yahoo.com', NULL, NULL),
(10, 12, 'henry farid', 'managing director', '0106058128', 'all_in_one@windowslive.com', NULL, NULL),
(11, 5, 'محمد عبد الفتاح', 'صاحب الشركة', '01202220762', 'mofattah@gmail.com', '2017-03-19 11:32:28', '2017-03-19 11:32:28');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `referral_id` int(10) unsigned DEFAULT NULL,
  `referral_percentage` decimal(8,2) DEFAULT NULL,
  `credit_limit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_client_company` tinyint(1) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT AUTO_INCREMENT=16 ;

--
-- Truncate table before insert `clients`
--

TRUNCATE TABLE `clients`;
--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `address`, `telephone`, `mobile`, `referral_id`, `referral_percentage`, `credit_limit`, `is_client_company`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Leo burnett', '2005c corniche el nil st. nile city towers.', '24618000', '01000060180', NULL, NULL, '500000', 1, NULL, '2017-03-14 08:03:42', '2017-03-14 08:03:42'),
(2, 'بتروجت', 'التجمع الخامس مجمع البترول', '26253329', '01227451336', NULL, NULL, '500000', 1, NULL, '2017-03-14 10:18:50', '2017-03-14 10:18:50'),
(3, 'john flimoun', 'التجمع الخامس', '22461800', '01222109970', NULL, NULL, '250000', 1, NULL, '2017-03-16 12:53:50', '2017-03-16 12:53:50'),
(4, 'Best print', '4 Qambiz St. Heliopolis, Cairo', '26370324', '0106200900', NULL, NULL, '150000', 1, NULL, '2017-03-16 12:58:41', '2017-03-16 12:58:41'),
(5, 'محمد عبد الفتاح', '6 اكتوبر', '22461800', '01202220762', NULL, NULL, '100000', 1, NULL, '2017-03-16 13:00:02', '2017-03-16 13:00:02'),
(6, 'Smash Management Co', '152, Al Orouba road, Heliopolis, Cairo, Egypt.', '24177930', '01099812641', NULL, NULL, '150000', 1, NULL, '2017-03-16 13:23:21', '2017-03-16 13:23:21'),
(7, 'Spectrum Advertising', 'Add. 21 Misr Al Tameer Bldgs., First Zone Sheraton 11361 Heliopolis, Cairo - EGYPT', '222690691', '0123978455', NULL, NULL, '250000', 1, NULL, '2017-03-16 13:32:55', '2017-03-16 13:32:55'),
(8, 'PICO', 'مدينة نصر', '224773399', '0101092799', NULL, NULL, '100000', 1, NULL, '2017-03-16 13:39:02', '2017-03-16 13:39:02'),
(9, 'vision group', 'المهندسين', '33035003', '01288880107', NULL, NULL, '100000', 1, NULL, '2017-03-16 13:57:50', '2017-03-16 13:57:50'),
(10, 'shadow print', '6 El Zahra St. Dokki – Giza, Egypt.', '01008215769', '37621948', NULL, NULL, '100000', 1, NULL, '2017-03-16 14:03:45', '2017-03-16 14:03:45'),
(11, 'lookup media', 'الدوقى', '22461800', '01221625204', NULL, NULL, '100000', 1, NULL, '2017-03-16 14:12:32', '2017-03-16 14:12:32'),
(12, 'all in one', '16 Milsa Buildings, Ard El Golf, Cairo, Egypt', '24177833', '012229000631', NULL, NULL, '100000', 1, NULL, '2017-03-16 14:18:00', '2017-03-16 14:18:00'),
(13, 'محمصة حازم', 'مدينة نصر امام الحديقة الدولية', '22461800', '01005005922', NULL, NULL, '100000', 1, NULL, '2017-03-16 14:24:44', '2017-03-16 14:24:44'),
(14, 'الشركة الهندسية للمقاولات', 'مدينة نصر بجوار النادى الاهلى', '22461800', '01223132226', NULL, NULL, '750000', 1, NULL, '2017-03-16 15:08:07', '2017-03-16 15:08:07'),
(15, 'clint', 'شارع عمار بن ياسر مصر الجديدة', '26210446', '01001400446', NULL, NULL, '100000', 1, NULL, '2017-03-22 11:32:22', '2017-03-22 11:32:22');

-- --------------------------------------------------------

--
-- Table structure for table `client_processes`
--

DROP TABLE IF EXISTS `client_processes`;
CREATE TABLE IF NOT EXISTS `client_processes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` int(10) unsigned NOT NULL,
  `employee_id` int(10) unsigned NOT NULL,
  `status` enum('active','temporary_closed','closed') COLLATE utf8_unicode_ci NOT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `has_discount` tinyint(1) DEFAULT NULL,
  `discount_percentage` decimal(11,3) DEFAULT NULL,
  `discount_value` decimal(11,3) DEFAULT NULL,
  `discount_reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `has_source_discount` tinyint(1) DEFAULT NULL,
  `source_discount_percentage` decimal(11,3) DEFAULT NULL,
  `source_discount_value` decimal(11,3) DEFAULT NULL,
  `require_invoice` tinyint(1) NOT NULL,
  `taxes_value` decimal(11,3) DEFAULT NULL,
  `total_price_taxes` decimal(11,3) DEFAULT NULL,
  `total_price` decimal(11,3) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_processes_client_id_index` (`client_id`),
  KEY `client_processes_employee_id_index` (`employee_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT AUTO_INCREMENT=20 ;

--
-- Truncate table before insert `client_processes`
--

TRUNCATE TABLE `client_processes`;
--
-- Dumping data for table `client_processes`
--

INSERT INTO `client_processes` (`id`, `name`, `client_id`, `employee_id`, `status`, `notes`, `has_discount`, `discount_percentage`, `discount_value`, `discount_reason`, `has_source_discount`, `source_discount_percentage`, `source_discount_value`, `require_invoice`, `taxes_value`, `total_price_taxes`, `total_price`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'ديبوند فرع المرغنى مول مصر مول العرب', 1, 1, 'active', 'لايوجد ملاحظات', 1, '0.500', '518.000', 'خصم من الممبع', 0, '0.000', '0.000', 1, '11045.060', '96007.060', '85480.000', NULL, '2017-03-14 10:13:12', '2017-03-18 13:15:44'),
(2, 'لوجو استكانة', 10, 6, 'active', 'لايوجد ملاحظات', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '9000.000', '9000.000', NULL, '2017-03-16 14:39:41', '2017-03-16 14:39:41'),
(3, 'المستشفى الدولى للكلى فرع المهندسين', 12, 6, 'active', 'لايوجد ملاحظات', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '21450.000', '21450.000', NULL, '2017-03-16 14:47:26', '2017-03-21 17:14:11'),
(4, 'deel mce', 3, 6, 'active', 'لايوجد ملاحظات', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '23200.000', '23200.000', NULL, '2017-03-16 14:52:06', '2017-03-16 14:52:06'),
(5, 'محمصات حازم', 13, 1, 'closed', 'باقى الحساب بعد التركيب والماراجعة', 0, '0.005', '1.000', 'تعديل', 0, '0.000', '0.000', 0, '0.000', '20000.000', '20000.000', NULL, '2017-03-16 14:58:16', '2017-03-20 07:59:00'),
(6, 'stone park', 7, 1, 'closed', 'تصفية حسابات', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '4000.000', '4000.000', NULL, '2017-03-16 15:16:24', '2017-03-22 15:57:47'),
(7, 'مول المشير', 14, 1, 'active', 'تصفية حسابات', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '50000.000', '50000.000', NULL, '2017-03-16 15:17:53', '2017-03-21 22:22:10'),
(8, 'stone park2', 7, 1, 'active', 'باقى الحساب بعد التركيب والماراجعة', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '7000.000', '7000.000', NULL, '2017-03-18 08:16:31', '2017-03-18 08:16:31'),
(9, 'الافتات الخارجية لمبنى التجمع استاليس', 2, 1, 'active', 'عملية الفاتورة رقم 126', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 1, '39251.030', '341182.030', '301931.000', NULL, '2017-03-18 10:34:17', '2017-03-18 13:56:25'),
(10, 'نيم تاج وعينات قديمة', 5, 1, 'active', 'ليزير أ بولس', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '575.000', '575.000', NULL, '2017-03-18 10:38:53', '2017-03-18 11:43:36'),
(11, 'لافتة زجاج التجمع الخامس المدخل الرئيسى', 2, 1, 'active', 'لم يتم الفوترة', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '45000.000', '45000.000', NULL, '2017-03-18 11:42:41', '2017-03-18 11:42:41'),
(12, 'بو كيت اكريلك', 2, 1, 'active', 'لا يوجد', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '23360.000', '23360.000', NULL, '2017-03-18 12:30:36', '2017-03-18 14:11:56'),
(13, 'AL KARMA', 6, 1, 'active', 'لايوجد ملاحظات', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 1, '0.000', '100000.000', '100000.000', NULL, '2017-03-18 13:06:29', '2017-03-18 13:06:29'),
(14, 'بايلون التجمع', 2, 1, 'active', 'لايوجد ملاحظات', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 1, '15340.000', '133340.000', '118000.000', NULL, '2017-03-18 13:59:30', '2017-03-18 13:59:30'),
(15, 'لوجو بتروجت الكونتر', 2, 6, 'active', 'لايوجد', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 1, '624.000', '5424.000', '4800.000', NULL, '2017-03-18 14:06:52', '2017-03-18 14:06:52'),
(16, 'لوحات ارشادية حلوانى', 5, 6, 'active', 'لا يوجد', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '24625.000', '24625.000', NULL, '2017-03-18 14:40:57', '2017-03-18 14:40:57'),
(17, 'غرب الجولف', 4, 1, 'active', 'لا يوجد', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '3000.000', '3000.000', NULL, '2017-03-18 18:05:04', '2017-03-18 18:05:04'),
(18, 'ستاند مارلوبورو', 9, 1, 'active', 'لايوجد', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '17000.000', '17000.000', NULL, '2017-03-19 10:30:27', '2017-03-19 10:30:27'),
(19, 'italian eyewear', 15, 1, 'active', 'لا يوجد', 0, NULL, NULL, NULL, 0, NULL, '0.000', 0, '0.000', '13900.000', '13900.000', NULL, '2017-03-22 12:09:44', '2017-03-22 12:09:44');

-- --------------------------------------------------------

--
-- Table structure for table `client_process_items`
--

DROP TABLE IF EXISTS `client_process_items`;
CREATE TABLE IF NOT EXISTS `client_process_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `process_id` int(10) unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `unit_price` decimal(8,2) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_process_items_process_id_index` (`process_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=42 ;

--
-- Truncate table before insert `client_process_items`
--

TRUNCATE TABLE `client_process_items`;
--
-- Dumping data for table `client_process_items`
--

INSERT INTO `client_process_items` (`id`, `process_id`, `description`, `quantity`, `unit_price`, `deleted_at`, `created_at`, `updated_at`) VALUES
(4, 1, 'ديبوند المرغنى', 45, '1200.00', NULL, '2017-03-14 10:13:12', '2017-03-14 10:13:12'),
(5, 1, 'ديبوند + لافتة + فلاج مول مصر', 1, '8740.00', NULL, '2017-03-14 10:13:12', '2017-03-14 10:13:12'),
(6, 1, 'ديبوند +لافتة + فلاج مول العرب', 1, '8740.00', NULL, '2017-03-14 10:13:12', '2017-03-14 10:13:12'),
(7, 1, 'فريمات الامنيوم فرع محى الدين', 1, '14000.00', NULL, '2017-03-14 10:13:12', '2017-03-14 10:13:12'),
(8, 2, 'لوجو استاليس', 1, '9000.00', NULL, '2017-03-16 14:39:41', '2017-03-16 14:39:41'),
(9, 3, 'ديبوند مقاس 11م * 104', 15, '650.00', NULL, '2017-03-16 14:47:26', '2017-03-16 14:47:26'),
(10, 3, 'حروف صاج مفرغ بداخلة بلاستك اضائة ليد', 13, '900.00', NULL, '2017-03-16 14:47:26', '2017-03-16 14:47:26'),
(11, 4, 'ديبوند', 16, '700.00', NULL, '2017-03-16 14:52:06', '2017-03-16 14:52:06'),
(12, 4, 'حروف بلاستك', 8, '1500.00', NULL, '2017-03-16 14:52:06', '2017-03-16 14:52:06'),
(13, 5, 'ديبوند + استاليس', 1, '20000.00', NULL, '2017-03-16 14:58:16', '2017-03-16 14:58:16'),
(14, 6, 'لافتة صاج', 1, '4000.00', NULL, '2017-03-16 15:16:24', '2017-03-16 15:16:24'),
(15, 7, 'ديبوند2', 1, '50000.00', NULL, '2017-03-16 15:17:54', '2017-03-16 15:33:15'),
(17, 8, 'صاج مرشوش اليكترو استاتك', 1, '7000.00', NULL, '2017-03-18 08:16:31', '2017-03-18 08:16:31'),
(18, 9, 'ستاليس مبنى التجمع', 1, '301931.00', NULL, '2017-03-18 10:34:17', '2017-03-18 10:34:17'),
(19, 10, 'نيم تاج مغناتيس', 5, '25.00', NULL, '2017-03-18 10:38:53', '2017-03-18 10:38:53'),
(20, 10, 'نيم تاج دبوس', 5, '15.00', NULL, '2017-03-18 10:38:53', '2017-03-18 10:38:53'),
(21, 10, 'عينات علبة فير', 2, '187.50', NULL, '2017-03-18 10:38:53', '2017-03-18 10:38:53'),
(22, 11, 'لوحة زجاج المدخل الرئيسى', 1, '45000.00', NULL, '2017-03-18 11:42:41', '2017-03-18 11:42:41'),
(23, 12, 'بوكيت اكريلك شفاف + فينيل مصنفر', 35, '130.00', NULL, '2017-03-18 12:30:36', '2017-03-18 14:11:56'),
(24, 12, 'علبة مضيئة غرفة الأجتماعات', 12, '350.00', NULL, '2017-03-18 12:34:47', '2017-03-18 12:34:47'),
(25, 13, 'الوحات الخارجة والداخلية', 1, '100000.00', NULL, '2017-03-18 13:06:29', '2017-03-18 13:06:29'),
(26, 14, 'عدد 2 بايلون التجمع', 2, '49000.00', NULL, '2017-03-18 13:59:30', '2017-03-18 13:59:30'),
(27, 14, 'تغير بلاستك داى اند نايت', 40, '500.00', NULL, '2017-03-18 13:59:30', '2017-03-18 13:59:30'),
(28, 15, 'لوجو استاليس للكاونترمقاس 1م', 4, '1200.00', NULL, '2017-03-18 14:06:52', '2017-03-18 14:06:52'),
(29, 12, 'شرايح رومارك مقاس 28*6.5', 3, '30.00', NULL, '2017-03-18 14:11:56', '2017-03-18 14:11:56'),
(30, 12, 'بوكيت اكريليك الوان 11سم * 28سم', 57, '160.00', NULL, '2017-03-18 14:11:57', '2017-03-18 14:11:57'),
(31, 12, 'PVC فضى ارقام ابواب', 300, '18.00', NULL, '2017-03-18 14:11:57', '2017-03-18 14:11:57'),
(32, 16, 'لوحات خشب وبكتات اكريليك وفنيل', 5, '4500.00', NULL, '2017-03-18 14:40:57', '2017-03-18 14:40:57'),
(33, 16, 'بوكيت شفاف فقط', 25, '45.00', NULL, '2017-03-18 14:40:57', '2017-03-18 14:40:57'),
(34, 16, 'نقل وتعديل الشغل وفك وتركيب', 1, '1000.00', NULL, '2017-03-18 14:40:57', '2017-03-18 14:40:57'),
(35, 17, 'تغيير خشب وليدات', 1, '3000.00', NULL, '2017-03-18 18:05:04', '2017-03-18 18:05:04'),
(36, 18, 'ستاند مارلوبورو كبير', 3, '5000.00', NULL, '2017-03-19 10:30:27', '2017-03-19 10:30:27'),
(37, 18, 'ستاند مارلوبورو صغير', 1, '2000.00', NULL, '2017-03-19 10:30:27', '2017-03-19 10:30:27'),
(38, 19, 'جلو لايت', 1, '1500.00', NULL, '2017-03-22 12:09:44', '2017-03-22 12:09:44'),
(39, 19, 'الدور الأرضى ( بلاستيك صينى + صورة مضيئة )', 1, '3300.00', NULL, '2017-03-22 12:09:44', '2017-03-22 12:09:44'),
(40, 19, 'الدور التانى ( بلاستيك ابيض صينى )', 1, '3900.00', NULL, '2017-03-22 12:09:44', '2017-03-22 12:09:44'),
(41, 19, 'لافتة مضيئة', 1, '5200.00', NULL, '2017-03-22 12:09:44', '2017-03-22 12:09:44');

-- --------------------------------------------------------

--
-- Table structure for table `deposit_withdraws`
--

DROP TABLE IF EXISTS `deposit_withdraws`;
CREATE TABLE IF NOT EXISTS `deposit_withdraws` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `depositValue` decimal(8,2) DEFAULT '0.00',
  `withdrawValue` decimal(8,2) DEFAULT '0.00',
  `recordDesc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cbo_processes` int(11) DEFAULT '0',
  `client_id` int(11) DEFAULT '0',
  `employee_id` int(11) DEFAULT '0',
  `supplier_id` int(11) DEFAULT '0',
  `expenses_id` int(11) DEFAULT '0',
  `payMethod` int(11) DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `saveStatus` tinyint(4) DEFAULT '1' COMMENT '1: can edit, 2: can not edit ',
  `due_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=184 ;

--
-- Truncate table before insert `deposit_withdraws`
--

TRUNCATE TABLE `deposit_withdraws`;
--
-- Dumping data for table `deposit_withdraws`
--

INSERT INTO `deposit_withdraws` (`id`, `depositValue`, `withdrawValue`, `recordDesc`, `cbo_processes`, `client_id`, `employee_id`, `supplier_id`, `expenses_id`, `payMethod`, `notes`, `saveStatus`, `due_date`, `created_at`, `updated_at`) VALUES
(1, '5000.00', NULL, 'دفعة من الحساب بيد حنا من امير فى الدقى', 2, 10, NULL, NULL, NULL, 0, NULL, 2, '2017-03-13 22:00:00', '2017-03-16 15:43:00', '2017-03-18 14:56:14'),
(2, NULL, '600.00', 'عهدة شراء صاج محمصة حازم ياسر', NULL, NULL, 8, NULL, 1, 0, NULL, 2, '2017-03-13 22:00:00', '2017-03-16 15:45:42', '2017-03-18 14:56:14'),
(3, '600.00', NULL, 'مرتجع عهدة شراء صاج محمصة حازم ياسر', NULL, NULL, 8, NULL, 2, 0, NULL, 2, '2017-03-13 22:00:00', '2017-03-16 15:46:29', '2017-03-18 14:56:14'),
(4, NULL, '345.00', 'عدد 3 لوح صاج مجلفن', 5, 13, NULL, NULL, NULL, 0, NULL, 2, '2017-03-13 22:00:00', '2017-03-16 15:48:17', '2017-03-18 14:56:15'),
(5, NULL, '50.00', 'مسامير فليكس', 5, 13, NULL, NULL, NULL, 0, NULL, 2, '2017-03-13 22:00:00', '2017-03-16 15:48:58', '2017-03-18 14:56:15'),
(6, NULL, '60.00', 'عشاء للعمال', 5, 13, NULL, NULL, NULL, 0, NULL, 2, '2017-03-13 22:00:00', '2017-03-16 15:49:35', '2017-03-18 14:56:15'),
(8, NULL, '100.00', 'عهدة تغيير الاكريليك بتروجت', NULL, NULL, 10, NULL, 1, 0, NULL, 2, '2017-03-13 22:00:00', '2017-03-16 15:51:14', '2017-03-18 14:56:15'),
(9, NULL, '1145.00', 'عهدة تركيب محمصات حازم', NULL, NULL, 4, NULL, 1, 0, NULL, 2, '2017-03-13 22:00:00', '2017-03-16 15:53:08', '2017-03-18 14:56:15'),
(10, NULL, '350.00', 'نقل علب حديد', 7, 14, NULL, NULL, NULL, 0, NULL, 2, '2017-03-13 22:00:00', '2017-03-16 16:07:21', '2017-03-18 14:56:15'),
(11, NULL, '200.00', 'نقل الافتة النحاس من التجمع', 11, 2, NULL, NULL, NULL, 0, NULL, 2, '2017-03-13 22:00:00', '2017-03-18 14:42:46', '2017-03-18 14:56:15'),
(12, NULL, '300.00', 'سلفة من المرتب يوم سفر اخومحمود', NULL, NULL, 10, NULL, 3, 0, NULL, 2, '2017-03-13 22:00:00', '2017-03-18 14:50:35', '2017-03-18 14:56:15'),
(13, NULL, '50.00', 'جورج النجار شريط خشب', 16, 5, NULL, NULL, NULL, 0, NULL, 2, '2017-03-13 22:00:00', '2017-03-18 14:51:23', '2017-03-18 14:56:15'),
(14, NULL, '400.00', 'نقل الشغل من المصنع الى العاشر', 16, 5, NULL, NULL, NULL, 0, NULL, 2, '2017-03-13 22:00:00', '2017-03-18 14:52:17', '2017-03-18 14:56:15'),
(15, NULL, '350.00', 'نقل الديبوند من العبور الى التجمع', 7, 14, NULL, NULL, NULL, 0, NULL, 2, '2017-03-13 22:00:00', '2017-03-18 14:52:54', '2017-03-18 14:56:15'),
(16, NULL, '200.00', 'نقل الحديد الى التجمع', 7, 14, NULL, NULL, NULL, 0, NULL, 2, '2017-03-13 22:00:00', '2017-03-18 14:53:24', '2017-03-18 14:56:15'),
(18, '2000.00', NULL, 'دفعه من الحساب', 6, 7, NULL, NULL, NULL, 0, NULL, 2, '2017-03-14 22:00:00', '2017-03-18 15:04:52', '2017-03-18 16:24:51'),
(19, NULL, '4.00', 'اسلحة قطر', 1, 1, NULL, NULL, NULL, 0, NULL, 2, '2017-03-14 22:00:00', '2017-03-18 15:06:24', '2017-03-18 16:24:51'),
(20, NULL, '10.00', 'عدد 2 بكرة لزق عريض', 1, 1, NULL, NULL, NULL, 0, NULL, 2, '2017-03-14 22:00:00', '2017-03-18 15:08:15', '2017-03-18 16:24:51'),
(21, NULL, '20.00', 'عدد3 فوطة نظافة', 5, 13, NULL, NULL, NULL, 0, NULL, 2, '2017-03-14 22:00:00', '2017-03-18 15:08:58', '2017-03-18 16:24:51'),
(22, NULL, '500.00', 'دفعه من الحساب', NULL, NULL, NULL, NULL, 5, 0, NULL, 2, '2017-03-14 22:00:00', '2017-03-18 15:58:47', '2017-03-18 16:24:52'),
(23, '1145.00', NULL, 'وارد عهدة يوم 14/3 عماد', NULL, NULL, 4, NULL, 2, 0, NULL, 2, '2017-03-14 22:00:00', '2017-03-18 16:03:04', '2017-03-18 16:24:52'),
(24, NULL, '2000.00', 'عهدة عماد', NULL, NULL, 4, NULL, 1, 0, NULL, 2, '2017-03-14 22:00:00', '2017-03-18 16:03:50', '2017-03-18 16:24:52'),
(25, NULL, '1000.00', 'عمر من مرتب شهر فبراير', NULL, NULL, NULL, NULL, 5, 0, NULL, 2, '2017-03-14 22:00:00', '2017-03-18 16:06:00', '2017-03-18 16:24:52'),
(27, NULL, '500.00', 'عهدة', NULL, NULL, 6, NULL, 1, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 16:40:43', '2017-03-18 17:14:36'),
(28, '2000.00', NULL, 'رد عهدة عماد يوم 15/3', NULL, NULL, 4, NULL, 2, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 16:48:19', '2017-03-18 17:14:36'),
(29, NULL, '500.00', 'عهدة عماد طرمبة السيارة', NULL, NULL, 4, NULL, 1, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 16:49:08', '2017-03-18 17:14:36'),
(30, NULL, '495.00', 'عدد 3 ترنس 150وات', 5, 13, NULL, NULL, NULL, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 16:50:15', '2017-03-18 17:14:36'),
(31, NULL, '100.00', 'مصروفات اكل للعمال', 5, 13, NULL, NULL, NULL, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 16:51:01', '2017-03-18 17:14:36'),
(32, NULL, '50.00', 'مواصلات العمال', 5, 13, NULL, NULL, NULL, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 16:52:00', '2017-03-18 17:14:37'),
(33, NULL, '25.00', 'مساحة زجاج لتنظيف الوجها', 5, 13, NULL, NULL, NULL, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 16:52:40', '2017-03-18 17:14:37'),
(34, NULL, '20.00', 'سكر وشاى', 5, 13, NULL, NULL, NULL, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 16:53:15', '2017-03-18 17:14:37'),
(35, NULL, '20.00', 'سلفة من المرتب بيد عماد', NULL, NULL, 8, NULL, 3, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 16:53:44', '2017-03-18 17:14:37'),
(36, NULL, '15.00', 'قلم + شريط لحام', 5, 13, NULL, NULL, NULL, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 16:54:15', '2017-03-18 17:14:37'),
(37, NULL, '70.00', 'فيش لوصلات المصنع', NULL, NULL, NULL, NULL, 5, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 16:55:30', '2017-03-18 17:14:37'),
(38, NULL, '30.00', 'مواسير استاليس', 1, 1, NULL, NULL, NULL, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 16:56:16', '2017-03-18 17:14:37'),
(39, NULL, '1060.00', '150ليد+2ترنس100وات و60وات', 1, 1, NULL, NULL, NULL, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 16:58:02', '2017-03-18 17:14:37'),
(40, NULL, '20.00', 'مواصلات عمال', 1, 1, NULL, NULL, NULL, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 16:58:33', '2017-03-18 17:14:37'),
(41, NULL, '100.00', 'عمالة خارجية عصام', 13, 6, NULL, NULL, NULL, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 16:59:13', '2017-03-18 17:14:37'),
(42, NULL, '50.00', 'سلفة من المرتب بيد عماد', NULL, NULL, 10, NULL, 3, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 16:59:54', '2017-03-18 17:14:37'),
(43, NULL, '15.00', 'اسبراى اسود', 1, 1, NULL, NULL, NULL, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 17:01:56', '2017-03-18 17:14:37'),
(45, NULL, '10.00', 'بنط 7مم حدادى', 1, 1, NULL, NULL, NULL, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 17:04:47', '2017-03-18 17:14:37'),
(46, '500.00', NULL, 'مرتجع عهدة عماد', NULL, NULL, 4, NULL, 2, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 17:06:41', '2017-03-18 17:14:37'),
(47, NULL, '350.00', 'طرمبة سيارة سوزوكى', NULL, NULL, NULL, NULL, 7, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 17:09:06', '2017-03-18 17:14:37'),
(48, NULL, '100.00', 'سلفة من المرتب', NULL, NULL, 10, NULL, 3, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 17:11:33', '2017-03-18 17:14:37'),
(49, NULL, '100.00', 'سلفة من المرتب', NULL, NULL, 8, NULL, 3, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 17:13:44', '2017-03-18 17:14:37'),
(50, NULL, '100.00', 'سلفة من المرتب', NULL, NULL, 4, NULL, 3, 0, NULL, 2, '2017-03-15 22:00:00', '2017-03-18 17:14:32', '2017-03-18 17:14:37'),
(51, '500.00', NULL, 'رد عهدة احمد طمان', NULL, NULL, 6, NULL, 2, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:11:11', '2017-03-18 20:17:49'),
(52, NULL, '50.00', 'سلفة ياسر بيد احمد', NULL, NULL, 8, NULL, 3, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:13:00', '2017-03-18 20:17:50'),
(53, NULL, '200.00', 'من حساب الكاوتش باقى الحساب', 7, 14, NULL, NULL, NULL, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:13:53', '2017-03-18 20:17:50'),
(54, NULL, '95.00', 'بنطة هاتى 60سم', 13, 6, NULL, NULL, NULL, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:14:51', '2017-03-18 20:17:50'),
(55, NULL, '5.00', 'لقمة سرف', 1, 1, NULL, NULL, NULL, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:15:20', '2017-03-18 20:17:50'),
(56, NULL, '150.00', 'اكل للعمال', 5, 13, NULL, NULL, NULL, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:16:19', '2017-03-18 20:17:50'),
(57, NULL, '1067.00', 'دفعة من الحساب عدد2 لوح بلاستك شفاف', 2, NULL, NULL, 1, NULL, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:22:23', '2017-03-18 20:17:50'),
(58, NULL, '836.33', 'عدد1 لوح بلاستك اسود', 12, 2, NULL, NULL, NULL, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:29:51', '2017-03-18 20:17:50'),
(59, NULL, '1672.67', 'عدد 2 لوح ازرق غامق', 13, 6, NULL, NULL, NULL, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:31:01', '2017-03-18 20:17:50'),
(60, '20000.00', NULL, 'دفعة من الحساب', 4, 3, NULL, NULL, NULL, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:32:22', '2017-03-18 20:17:51'),
(61, '11000.00', NULL, 'دفعة من الحساب', 3, 12, NULL, NULL, NULL, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:32:52', '2017-03-18 20:17:51'),
(62, NULL, '1000.00', 'سلفه من المرتب يوم سفر البلد مرض والدة', NULL, NULL, 4, NULL, 3, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:38:46', '2017-03-18 20:17:51'),
(63, NULL, '421.00', 'خالص مرتب عمر شهر فبراير', NULL, NULL, NULL, NULL, 5, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:39:56', '2017-03-18 20:17:51'),
(64, NULL, '500.00', 'عهدة شراء حجارة بطارية', NULL, NULL, 6, NULL, 1, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:41:42', '2017-03-18 20:17:51'),
(65, NULL, '150.00', 'فاتورة النت شهر 3', NULL, NULL, NULL, NULL, 8, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:47:35', '2017-03-18 20:17:51'),
(66, NULL, '1000.00', 'فاتورة الموبيلات 3', NULL, NULL, NULL, NULL, 8, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:47:35', '2017-03-18 20:17:51'),
(67, NULL, '1600.00', 'ايجار المحلات شهر 3', NULL, NULL, NULL, NULL, 1, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:53:38', '2017-03-18 20:17:51'),
(68, NULL, '267.50', 'ايجار شقة عين شمس شهر 3', NULL, NULL, NULL, NULL, 9, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:55:32', '2017-03-18 20:17:51'),
(69, NULL, '10000.00', 'الاسم الثانى والاسم الاول دفع قبل التسجيل على البرنامج', NULL, NULL, NULL, NULL, 10, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 18:58:51', '2017-03-18 20:17:51'),
(70, NULL, '2500.00', 'خالص مرتب شهر فبراير أ/ بيشوى', NULL, NULL, NULL, NULL, 5, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 19:13:43', '2017-03-18 20:17:51'),
(71, NULL, '2266.50', 'سلفة من مرتب مارس', NULL, NULL, 1, NULL, 3, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 19:16:02', '2017-03-18 20:17:52'),
(75, NULL, '1500.00', 'سلفة', NULL, NULL, 11, NULL, 3, 0, NULL, 2, '2017-03-17 22:00:00', '2017-03-18 19:38:31', '2017-03-18 20:17:52'),
(84, NULL, '100.00', 'عهدة شراء بوية واقلام غرب الجولف', NULL, NULL, 9, NULL, 1, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 09:53:23', '2017-03-19 09:53:23'),
(85, '100.00', NULL, 'رد عهدة بويات واقلام غرب الجولف', NULL, NULL, 9, NULL, 2, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 09:54:30', '2017-03-19 09:54:30'),
(86, NULL, '25.00', '1 ك بلاستيك غرب الجولف', 17, 4, NULL, NULL, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 09:56:33', '2017-03-19 09:56:33'),
(87, NULL, '8.00', 'رولا دهان غرب الجولف', 17, 4, NULL, NULL, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 09:57:56', '2017-03-19 09:57:56'),
(88, NULL, '3.00', 'عدد 2 قلم شغل غرب الجولف', 17, 4, NULL, NULL, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 10:11:06', '2017-03-19 10:11:06'),
(89, NULL, '1.00', 'كيس بريل شغل غرب الجولف', 17, 4, NULL, NULL, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 10:11:39', '2017-03-19 10:11:39'),
(90, NULL, '56.00', 'كارت شحن موبايل أ / بيشوى', NULL, NULL, NULL, NULL, 8, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 10:12:18', '2017-03-19 10:12:18'),
(91, '500.00', NULL, 'عهدة شراء حجارة بطارية شغل بوكتات بتروجيت', NULL, NULL, 6, NULL, 2, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 10:43:35', '2017-03-19 10:43:35'),
(92, NULL, '2000.00', 'عهدة شراء صاج شغل مستشفى الكلى  ( اوول وان )', NULL, NULL, 6, NULL, 1, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 10:44:37', '2017-03-19 12:20:54'),
(93, NULL, '180.00', 'بطاريات 9 فولت', 12, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 10:45:34', '2017-03-19 12:20:57'),
(94, NULL, '50.00', 'تركيب اسلاك لمفاتيح اضائة البوكتات', 12, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 10:46:26', '2017-03-19 12:21:01'),
(95, NULL, '10.00', 'مواصلات شراء بطاريات البوكتات', 12, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 10:47:13', '2017-03-19 12:21:04'),
(96, NULL, '35.00', 'صنفرة صاروخ', 15, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 10:48:09', '2017-03-19 12:21:08'),
(97, '100.00', NULL, 'رد عهدة  محمود تغير الأكريلك', NULL, NULL, 10, NULL, 2, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 10:49:47', '2017-03-19 12:21:11'),
(98, NULL, '45.00', 'اكل للعمال', 9, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 11:01:45', '2017-03-19 12:21:14'),
(99, NULL, '4.00', 'مياه شرب للعمال', 9, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 11:02:30', '2017-03-19 12:21:18'),
(100, NULL, '51.00', 'سلفة من مرتب شهر مارس', NULL, NULL, 10, NULL, 3, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 11:04:07', '2017-03-19 12:21:25'),
(101, NULL, '300.00', 'عهدة شراء ليدات شغل غرب الجولف', NULL, NULL, 10, NULL, 1, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 11:05:50', '2017-03-19 12:21:33'),
(102, NULL, '2500.00', 'باقى حساب راضى', 14, NULL, NULL, 5, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 11:38:37', '2017-03-19 12:21:36'),
(103, NULL, '300.00', 'نقل استاندات مارلبورو من مصر القديمة الى المصنع دوكو', 18, 9, NULL, NULL, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 11:48:41', '2017-03-19 12:21:41'),
(104, NULL, '100.00', 'عهدة كيرلس صواميل وورد وشرائط لحام شغل غرب الجولف', NULL, NULL, 9, NULL, 1, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 12:24:06', '2017-03-19 12:24:06'),
(105, NULL, '100.00', 'سلفة من مرتب شهر مارس', NULL, NULL, 8, NULL, 3, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 13:03:28', '2017-03-19 13:03:28'),
(106, '2000.00', NULL, 'رد عهدة شراء صاج شغل مستشفى الكلى  اوول وان احمد', NULL, NULL, 6, NULL, 2, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 13:40:48', '2017-03-19 13:40:48'),
(107, NULL, '840.00', 'عدد 2 لوح صاج 1ملل', 3, 12, NULL, NULL, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 13:45:22', '2017-03-19 13:51:09'),
(108, NULL, '680.00', 'عدد 2 لوح  .8ملل', 3, 12, NULL, NULL, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 13:47:10', '2017-03-19 14:02:23'),
(109, NULL, '40.00', 'تقطيع شرائح  صاج', 3, 12, NULL, NULL, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 13:48:25', '2017-03-19 14:02:29'),
(110, NULL, '200.00', 'عهدة اجمد طمان ( فرق العهدة ال 500 )', NULL, NULL, 6, NULL, 1, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 14:04:07', '2017-03-19 14:04:07'),
(112, NULL, '10.00', 'مواصلات شراء مسامير  بوكتات  بتروجيت', 12, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 17:22:28', '2017-03-19 17:22:28'),
(113, '300.00', NULL, 'رد عهدة محمود شراء ليدات غرب الجولف', NULL, NULL, 10, NULL, 2, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 17:36:40', '2017-03-19 17:36:40'),
(114, NULL, '190.00', 'ليدات 3 نقطة شغل غرب  الجولف عدد 100', 17, 4, NULL, NULL, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 17:38:12', '2017-03-19 17:39:09'),
(115, NULL, '36.00', 'عدد 20 ليد شغل غرب الجولف', 17, 4, NULL, NULL, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 17:40:21', '2017-03-19 17:40:21'),
(116, NULL, '30.00', 'مواصلات شراء ليدات غرب الجولف', 17, 4, NULL, NULL, NULL, 0, NULL, 1, '2017-03-18 22:00:00', '2017-03-19 17:42:26', '2017-03-19 17:42:26'),
(117, NULL, '100.00', 'عهدة محمود مصاريف شغل خارجى عملية بتروجيت تغير بلاستيك يافطة خارجية', NULL, NULL, 10, NULL, 1, 0, NULL, 1, '2017-03-19 22:00:00', '2017-03-20 06:40:38', '2017-03-20 06:40:38'),
(118, NULL, '100.00', 'عهدة ياسر مواصلات مول المشير', NULL, NULL, 8, NULL, 1, 0, NULL, 1, '2017-03-19 22:00:00', '2017-03-20 07:43:20', '2017-03-20 07:43:20'),
(119, NULL, '11.00', 'كارت شحن موبايل احمد طمان', NULL, NULL, NULL, NULL, 8, 0, NULL, 1, '2017-03-19 22:00:00', '2017-03-20 07:53:17', '2017-03-20 07:53:17'),
(120, '20000.00', NULL, 'وارد من محمصة حازم خالص', 5, 13, NULL, NULL, NULL, 0, NULL, 1, '2017-03-19 22:00:00', '2017-03-20 07:59:00', '2017-03-20 07:59:00'),
(121, NULL, '15000.00', 'عهدة أ / بيشوى لشراء بلاستيك داى اند نايت + زجاج شغل بتروجيت', NULL, NULL, 1, NULL, 1, 0, NULL, 1, '2017-03-19 22:00:00', '2017-03-20 09:23:21', '2017-03-20 10:23:22'),
(122, '200.00', NULL, 'رد عهدة احمد طمان ( فرق عهدة ال 500)', NULL, NULL, 6, NULL, 2, 0, NULL, 1, '2017-03-19 22:00:00', '2017-03-20 10:19:10', '2017-03-20 10:19:10'),
(123, NULL, '800.00', 'عهدة احمد شراء قصدير +فينيل شغل مستشفى الكلى', NULL, NULL, 6, NULL, 1, 0, NULL, 1, '2017-03-19 22:00:00', '2017-03-20 10:20:33', '2017-03-20 14:28:49'),
(124, '800.00', NULL, 'رد عهدة احمد طمان لشراء قصدير +فينيل شغل مستشفى الكلى', NULL, NULL, 6, NULL, 2, 0, NULL, 1, '2017-03-19 22:00:00', '2017-03-20 16:01:49', '2017-03-20 16:01:49'),
(125, NULL, '655.00', '1ك قصدير + 1.5 رصاص شغل مستشفى الكلى', 3, 12, NULL, NULL, NULL, 0, NULL, 1, '2017-03-19 22:00:00', '2017-03-20 16:07:40', '2017-03-20 16:07:40'),
(126, NULL, '82.00', 'فينيل مصنفر  1 متر شغل بوكتات بتروجيت', 12, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-19 22:00:00', '2017-03-20 16:08:48', '2017-03-20 16:08:48'),
(127, NULL, '14.00', 'مواصلات شراء قصدير + رصاص ( احمد طمان ) شغل مستشفى الكلى', 3, 12, NULL, NULL, NULL, 0, NULL, 1, '2017-03-19 22:00:00', '2017-03-20 16:10:20', '2017-03-20 16:10:20'),
(128, NULL, '500.00', 'عهدة احمد طمان تركيب اورانج مول العرب', NULL, NULL, 6, NULL, 1, 0, NULL, 1, '2017-03-19 22:00:00', '2017-03-20 16:22:32', '2017-03-20 16:22:32'),
(129, NULL, '200.00', 'عهدة حنا صيانة موبايلات', NULL, NULL, 11, NULL, 1, 0, NULL, 1, '2017-03-19 22:00:00', '2017-03-20 16:23:12', '2017-03-20 16:23:12'),
(130, '200.00', NULL, 'رد عهدة حنا لصيانة الموبايلات', NULL, NULL, 11, NULL, 2, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 13:55:29', '2017-03-21 13:55:29'),
(131, '100.00', NULL, 'رد عهدة محمود مصاريف شغل خارجى عملية يتروجيت اكريلك خارجى', NULL, NULL, 10, NULL, 2, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 13:57:49', '2017-03-21 13:57:49'),
(132, NULL, '62.00', 'اكل عمال شغل بتروجيت اكريلك خارجى', 12, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 13:59:11', '2017-03-21 14:10:08'),
(133, NULL, '8.00', 'مواصلات بتروجيت اكريلك التجمع', 12, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 13:59:47', '2017-03-21 13:59:47'),
(134, NULL, '7.50', 'شاى  للعمال شغل اكريلك بتروجيت', 12, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 14:00:37', '2017-03-21 14:00:37'),
(136, NULL, '100.00', 'عهدة محمود شغل بتروجيت اليافطة الخارجية', NULL, NULL, 10, NULL, 1, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 14:03:51', '2017-03-21 14:03:51'),
(137, NULL, '190.00', 'بطارية موبيل + شاشة', NULL, NULL, NULL, NULL, 8, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 15:33:43', '2017-03-21 15:33:43'),
(138, NULL, '250.00', 'خالص حساب يوم تركيب العداد', 6, NULL, NULL, 3, NULL, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 15:34:38', '2017-03-21 15:34:38'),
(139, NULL, '2000.00', 'من مديونية حمد وباقى  يوم فرح قريب حمد5000', NULL, NULL, NULL, NULL, 5, 0, 'أ/ بيشوى', 1, '2017-03-20 22:00:00', '2017-03-21 15:36:28', '2017-03-21 15:36:28'),
(140, NULL, '2000.00', 'خالص حساب تقطيع بلاستك +الخامة', 16, NULL, NULL, 7, NULL, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 15:38:44', '2017-03-21 15:38:44'),
(141, NULL, '1000.00', 'اصلاح كاميرات', NULL, NULL, NULL, NULL, 5, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 15:50:45', '2017-03-21 15:50:45'),
(142, '15000.00', NULL, 'مريجع العهدة', NULL, NULL, 1, NULL, 2, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 15:51:35', '2017-03-21 15:51:35'),
(143, NULL, '5500.00', 'عدد3لوح بلاستك دى اند نيت', 14, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 15:54:13', '2017-03-21 15:54:13'),
(144, NULL, '6000.00', 'خالص حساب الافتة الزجاج', 11, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 15:54:52', '2017-03-21 15:54:52'),
(145, NULL, '2414.00', 'ضبل فيس', 11, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 16:01:43', '2017-03-21 16:01:43'),
(146, NULL, '286.00', 'بنزين سارة أ بيشوى', NULL, NULL, NULL, NULL, 5, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 16:03:01', '2017-03-21 16:03:47'),
(147, NULL, '4.00', 'صابونة وجه', NULL, NULL, NULL, NULL, 5, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 16:13:09', '2017-03-21 16:13:09'),
(148, NULL, '5.00', 'عدد 2 قلم جاف للمكتب', NULL, NULL, NULL, NULL, 6, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 16:22:45', '2017-03-21 16:22:45'),
(149, NULL, '240.00', 'عدد 1 لوح خشب mdf شغل deel mce', 4, 3, NULL, NULL, NULL, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 17:49:06', '2017-03-21 17:49:06'),
(150, NULL, '100.00', 'سلفة عادل من مرتب شهر مارس', NULL, NULL, 12, NULL, 3, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 17:51:04', '2017-03-21 17:51:04'),
(151, NULL, '22.50', 'سلفة محمود من مرتب شهر مارس', NULL, NULL, 10, NULL, 3, 0, NULL, 1, '2017-03-20 22:00:00', '2017-03-21 17:56:02', '2017-03-21 17:56:02'),
(152, '500.00', NULL, 'رد عهدة احمد طمان تركيب اورانج مول العرب', NULL, NULL, 6, NULL, 2, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 09:06:52', '2017-03-22 09:06:52'),
(153, NULL, '8.00', 'كيس قطن', 1, 1, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 09:07:22', '2017-03-22 09:07:22'),
(154, NULL, '20.00', 'شاى + سكر', 1, 1, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 09:07:44', '2017-03-22 09:07:44'),
(155, NULL, '125.00', 'اكل للعمال', 1, 1, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 09:08:14', '2017-03-22 09:08:14'),
(156, NULL, '30.00', 'مواصلات العمال الى اورانج مول العرب', 1, 1, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 09:12:13', '2017-03-22 09:12:13'),
(157, '100000.00', NULL, 'دفعة من حساب مول المشير', 7, 14, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 09:42:59', '2017-03-22 09:42:59'),
(158, NULL, '20000.00', 'رصيد بالبنك', NULL, NULL, NULL, NULL, 5, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 09:44:10', '2017-03-22 09:44:10'),
(159, NULL, '10366.00', 'رصيد بالبنك لصرف شيك حساب الضرائب العامة', NULL, NULL, NULL, NULL, 11, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 10:06:44', '2017-03-22 10:13:20'),
(160, NULL, '2000.00', 'عهدة احمد طمان لشراء خشب عملية مارلبورو', NULL, NULL, 6, NULL, 1, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 10:09:26', '2017-03-22 10:13:41'),
(161, '100.00', NULL, 'رد عهدة ياسر مواصلات مول المشير', NULL, NULL, 8, NULL, 2, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 11:00:15', '2017-03-22 11:00:15'),
(162, NULL, '20.00', 'تيش شغل مول العرب ر', 1, 1, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 11:01:50', '2017-03-22 11:02:12'),
(163, NULL, '30.00', 'مواصلات العمال مول المشير ذهاب', 7, 14, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 11:03:13', '2017-03-22 11:05:00'),
(164, NULL, '25.00', 'مواصلات عودة من مول المشير', 7, 14, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 11:05:33', '2017-03-22 11:05:33'),
(165, NULL, '25.00', 'اكل عمال شغل مول المشير ( ياسر + كيرلس)', 7, 14, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 11:06:54', '2017-03-22 11:06:54'),
(166, NULL, '100.00', 'سلفة محمود من مرتب شهر مارس', NULL, NULL, 10, NULL, 3, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 11:07:56', '2017-03-22 11:07:56'),
(167, NULL, '100.00', 'سلفة ياسر من مرتب شهر مارس', NULL, NULL, 8, NULL, 3, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 11:09:40', '2017-03-22 11:09:40'),
(168, NULL, '300.00', 'عهدة ياسر شغل اللافتة الزجاج بتروجت', NULL, NULL, 8, NULL, 1, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 11:11:09', '2017-03-22 11:11:09'),
(169, NULL, '5000.00', 'عهدة أ / بيشوى', NULL, NULL, 1, NULL, 1, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 12:14:12', '2017-03-22 12:14:12'),
(170, NULL, '2567.00', 'شراء عدد 3 لوح اكريلك ازرق غامق', 13, 6, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 15:46:55', '2017-03-22 15:48:23'),
(171, NULL, '3724.00', 'شراء عدد 3 لوح مصدف 2م * 130سم', 13, 6, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 15:50:02', '2017-03-22 15:50:02'),
(172, NULL, '52.00', 'شراء عدد 1 زجاجة بولى فاست', 13, 6, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 15:51:56', '2017-03-22 15:51:56'),
(173, NULL, '40.00', 'نقل + اكرامية ( نقل الواح الأكريلك + البولى فاست )', 13, 6, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 15:55:15', '2017-03-22 15:55:15'),
(174, '2000.00', NULL, 'وارد من حساب سبيكترم  عملية ستون بارك  1 خالص', 6, 7, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 15:57:47', '2017-03-22 16:00:54'),
(175, '2500.00', NULL, 'وارد من حساب سبيكترم  عملية ستون بارك 2', 8, 7, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 16:00:34', '2017-03-22 16:01:21'),
(176, NULL, '30.00', 'مواصلات عدد2 مرة تحصيل سبيكترم', NULL, NULL, NULL, NULL, 5, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 16:10:19', '2017-03-22 16:10:19'),
(177, '300.00', NULL, 'رد عهدة ياسر شغل اللافتة الزجاج بتروجيت', NULL, NULL, 8, NULL, 2, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 16:17:54', '2017-03-22 16:17:54'),
(178, NULL, '160.00', 'شراء عدد 2 زاوية المونيوم', 11, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 16:19:54', '2017-03-22 16:19:54'),
(179, NULL, '60.00', 'علبة مسامير 6سم', 11, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 16:21:46', '2017-03-22 16:21:46'),
(180, NULL, '10.00', 'عدد 1 8مم حدادى', 11, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 16:22:57', '2017-03-22 16:22:57'),
(181, NULL, '10.00', 'بنطة 6 مم هيلتى', 11, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 16:25:40', '2017-03-22 16:27:05'),
(182, NULL, '5.00', 'بنطة 5 مم حدادى', 11, 2, NULL, NULL, NULL, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 16:27:38', '2017-03-22 16:27:38'),
(183, NULL, '55.00', 'عهدة ياسر شراء مستلزمات للورشة', NULL, NULL, 8, NULL, 1, 0, NULL, 1, '2017-03-21 22:00:00', '2017-03-22 16:33:24', '2017-03-22 16:33:24');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `emp_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ssn` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `gender` enum('m','f') COLLATE utf8_unicode_ci NOT NULL,
  `martial_status` enum('single','married','widowed','divorced') COLLATE utf8_unicode_ci NOT NULL,
  `birth_date` date NOT NULL,
  `department` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hiring_date` date NOT NULL,
  `daily_salary` double NOT NULL,
  `working_hours` int(11) NOT NULL,
  `job_title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `telephone` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `facility_id` int(10) unsigned NOT NULL,
  `can_not_use_program` tinyint(1) NOT NULL,
  `borrow_system` tinyint(1) NOT NULL,
  `deleted_at_id` int(10) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employees_user_id_index` (`id`),
  KEY `employees_facility_id_index` (`facility_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=13 ;

--
-- Truncate table before insert `employees`
--

TRUNCATE TABLE `employees`;
--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `emp_id`, `name`, `ssn`, `gender`, `martial_status`, `birth_date`, `department`, `hiring_date`, `daily_salary`, `working_hours`, `job_title`, `telephone`, `mobile`, `facility_id`, `can_not_use_program`, `borrow_system`, `deleted_at_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'استاذ بيشوي', '28100010500755', 'm', 'married', '1980-02-09', '', '2001-01-01', 200, 8, 'مدير', '', '01200000000', 1, 0, 0, NULL, NULL, NULL, '2016-11-12 17:58:14'),
(4, 2, 'عماد ظريف سعيد جرجس', '28210112401113', 'm', 'married', '1982-10-11', 'المرج', '2005-02-02', 125, 10, 'رئيس عمال', '25252525', '01225524231', 1, 1, 1, NULL, NULL, '2017-03-14 08:39:58', '2017-03-15 14:09:55'),
(5, 3, 'عمر سيد عويس عطيه', '28803140102475', 'm', 'single', '1988-03-14', 'مصر القديمة', '2013-01-02', 80, 10, 'عامل', '25252525', '01148209148', 1, 1, 1, NULL, NULL, '2017-03-14 08:46:53', '2017-03-15 14:10:56'),
(6, 4, 'احمد محمود عبد العظيم  طمان', '27803130101639', 'm', 'married', '1978-03-13', 'المرج', '2014-12-14', 80, 10, 'مشرف', '53012912', '01220909014', 1, 1, 1, NULL, NULL, '2017-03-14 08:49:16', '2017-03-15 14:11:34'),
(7, 5, 'جرجس عزيز باسيلى بطرس', '28906010102558', 'm', 'single', '1989-06-01', 'منشية ناصر', '2015-12-12', 80, 10, 'عامل', '15252525', '01270928768', 1, 1, 0, NULL, NULL, '2017-03-14 08:51:50', '2017-03-15 14:11:57'),
(8, 6, 'ياسر صلاح عبد الحميد عثمان', '28702070101297', 'm', 'single', '1987-02-07', 'عين شمس', '2016-02-01', 85, 10, 'عامل', '25252525', '01211096247', 1, 1, 0, NULL, NULL, '2017-03-14 08:54:15', '2017-03-15 14:12:15'),
(9, 7, 'كيرلس رأفت فريز جرجس', '29208080101651', 'm', 'married', '1992-08-08', 'المطرية', '2017-03-07', 60, 10, 'عامل', '22555584', '01223332780', 1, 1, 0, NULL, NULL, '2017-03-14 08:57:34', '2017-03-15 14:12:37'),
(10, 8, 'محمود قاسم احمد محمد', '29009272401558', 'm', 'single', '1990-09-27', 'سمالوط _ المنيا', '2017-03-14', 115, 10, 'عامل', '77121510', '01155591981', 1, 1, 0, NULL, NULL, '2017-03-14 09:10:37', '2017-03-15 14:12:54'),
(11, 9, 'يوحنا ابراهيم بدروس', '28003210103192', 'm', 'single', '1980-03-21', 'عين شمس', '2017-02-01', 57.75, 10, 'محاسب', '22461800', '01282508848', 1, 0, 1, NULL, NULL, '2017-03-14 09:13:17', '2017-03-19 18:01:49'),
(12, 10, 'عادل حنا جيد حنا', '29909011411212', 'm', 'single', '1999-09-01', 'شبرا  الخيمة أول', '2017-03-18', 60, 10, 'عامل', '25252525', '01273964415', 1, 1, 0, NULL, NULL, '2017-03-18 12:22:09', '2017-03-18 12:22:09');

-- --------------------------------------------------------

--
-- Table structure for table `employee_borrows`
--

DROP TABLE IF EXISTS `employee_borrows`;
CREATE TABLE IF NOT EXISTS `employee_borrows` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(10) unsigned NOT NULL,
  `amount` double NOT NULL,
  `borrow_reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pay_amount` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_borrows_employee_id_index` (`employee_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2 ;

--
-- Truncate table before insert `employee_borrows`
--

TRUNCATE TABLE `employee_borrows`;
-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=13 ;

--
-- Truncate table before insert `expenses`
--

TRUNCATE TABLE `expenses`;
--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'ايجار', '2017-03-18 15:17:54', '2017-03-18 15:17:54'),
(2, 'كهرباء', '2017-03-18 15:18:21', '2017-03-18 15:18:21'),
(3, 'المياه', '2017-03-18 15:18:47', '2017-03-18 15:18:47'),
(4, 'عدد وادوات للمصنع', '2017-03-18 15:19:34', '2017-03-18 15:19:34'),
(5, 'صيانة ومصروفات اخرى', '2017-03-18 15:20:06', '2017-03-18 15:20:06'),
(6, 'ادوات مكتبية واجهزة', '2017-03-18 15:20:48', '2017-03-18 15:20:48'),
(7, 'مصروفات السيارة السوزوكى', '2017-03-18 17:09:52', '2017-03-18 17:09:52'),
(8, 'تليفونات ونت', '2017-03-18 18:45:18', '2017-03-18 18:45:18'),
(9, 'شقة الاستاز بيشوى عين شمس', '2017-03-18 18:54:27', '2017-03-18 18:54:27'),
(10, 'جمعية وائل من شهر فبراير وحتى شهر يناير 2018', '2017-03-18 18:57:32', '2017-03-18 18:57:32'),
(11, 'الضرائب العامة', '2017-03-22 09:48:14', '2017-03-22 09:48:14'),
(12, 'الضرائب على المبيعات', '2017-03-22 09:49:06', '2017-03-22 09:49:06');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

DROP TABLE IF EXISTS `facilities`;
CREATE TABLE IF NOT EXISTS `facilities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `manager_id` int(10) unsigned NOT NULL,
  `type` enum('individual','joint','partnership','limited_partnership','stock') COLLATE utf8_unicode_ci NOT NULL,
  `tax_file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_card` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trade_record` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sales_tax` int(255) DEFAULT NULL,
  `opening_amount` decimal(10,0) DEFAULT NULL,
  `country_sales_tax` tinyint(4) DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `region` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `facilities_name_unique` (`name`),
  KEY `facilities_manager_id_index` (`manager_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2 ;

--
-- Truncate table before insert `facilities`
--

TRUNCATE TABLE `facilities`;
--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `name`, `manager_id`, `type`, `tax_file`, `tax_card`, `trade_record`, `sales_tax`, `opening_amount`, `country_sales_tax`, `logo`, `country`, `city`, `region`, `address`, `email`, `website`, `created_at`, `updated_at`) VALUES
(1, 'B2bAdv', 1, 'stock', '11200', '23000', '98100', 13, '459', NULL, 'Q254GxNKrjKryjnN46zRKgloE00ID0DkGvmDajL6.png', 'مصر', 'القاهرة', 'عين شمس', '12 ش محمد متولي', 'info@b2b-adv.com', 'http://www.b2b-adv.com/', NULL, '2017-03-16 15:32:06');

-- --------------------------------------------------------

--
-- Table structure for table `feasibility_study`
--

DROP TABLE IF EXISTS `feasibility_study`;
CREATE TABLE IF NOT EXISTS `feasibility_study` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `process_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` int(11) NOT NULL,
  `rent_per_month` int(11) NOT NULL,
  `rent_time` int(11) NOT NULL,
  `waste_percentage` int(11) NOT NULL,
  `profit_percentage` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `feasibility_study_process_name_unique` (`process_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `feasibility_study`
--

TRUNCATE TABLE `feasibility_study`;
-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_reserved_reserved_at_index` (`queue`,`reserved`,`reserved_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `jobs`
--

TRUNCATE TABLE `jobs`;
-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Truncate table before insert `migrations`
--

TRUNCATE TABLE `migrations`;
--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2016_06_03_190028_create_users_table', 1),
('2016_06_03_190241_create_employees_table', 1),
('2016_06_03_191029_create_password_resets_table', 1),
('2016_06_09_163017_create_facilities_table', 1),
('2016_06_09_163447_entrust_setup_tables', 1),
('2016_06_10_152145_create_phones_table', 1),
('2016_06_24_143843_create_suppliers_table', 1),
('2016_06_25_050147_create_clients_table', 1),
('2016_07_06_141421_create_client_processes_table', 1),
('2016_07_06_141432_create_supplier_processes_table', 1),
('2016_07_06_143327_create_client_process_items_table', 1),
('2016_07_08_073008_create_authorized_people_table', 1),
('2016_10_24_211331_alter_supplier_processess_table', 2),
('2016_10_24_213936_create_supplier_process_item_table', 2),
('2016_10_30_221510_create_expenses_table', 2),
('2016_11_02_092325_alter_suppliers_table', 4),
('2016_11_02_085201_create_deposit_withdraw_table', 5),
('2016_11_04_173731_create_employee_borrow_table', 6),
('2016_12_10_143420_Alter_employee_borrow_table', 7),
('2016_12_10_171055_create_feasibility_study_table', 7),
('2016_12_10_174410_create_fs_related_tables', 7),
('2017_01_22_110601_create_jobs_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `migrations_copy`
--

DROP TABLE IF EXISTS `migrations_copy`;
CREATE TABLE IF NOT EXISTS `migrations_copy` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Truncate table before insert `migrations_copy`
--

TRUNCATE TABLE `migrations_copy`;
-- --------------------------------------------------------

--
-- Table structure for table `opening_amounts`
--

DROP TABLE IF EXISTS `opening_amounts`;
CREATE TABLE IF NOT EXISTS `opening_amounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` decimal(10,0) DEFAULT NULL,
  `deposit_date` datetime DEFAULT NULL,
  `reason` varchar(1000) DEFAULT NULL,
  `facility_id` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2 ;

--
-- Truncate table before insert `opening_amounts`
--

TRUNCATE TABLE `opening_amounts`;
--
-- Dumping data for table `opening_amounts`
--

INSERT INTO `opening_amounts` (`id`, `amount`, `deposit_date`, `reason`, `facility_id`, `updated_at`, `created_at`) VALUES
(1, '459', '2017-03-16 00:00:00', 'ظبط الحساب وبد الحسابات المنطظمة', NULL, '2017-03-16 17:32:06', '2017-03-16 17:29:55');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Truncate table before insert `password_resets`
--

TRUNCATE TABLE `password_resets`;
-- --------------------------------------------------------

--
-- Table structure for table `password_resets_copy`
--

DROP TABLE IF EXISTS `password_resets_copy`;
CREATE TABLE IF NOT EXISTS `password_resets_copy` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Truncate table before insert `password_resets_copy`
--

TRUNCATE TABLE `password_resets_copy`;
-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=17 ;

--
-- Truncate table before insert `permissions`
--

TRUNCATE TABLE `permissions`;
--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'facility-info', 'بيانات المنشأة', NULL, '2016-07-30 21:36:53', '2016-07-30 21:36:53'),
(2, 'employees-permissions', 'صلاحيات الموظفين', NULL, '2016-07-30 21:36:53', '2016-07-30 21:36:53'),
(3, 'deposit-withdraw', 'وارد و منصرف', NULL, '2016-07-30 21:36:53', '2016-07-30 21:36:53'),
(4, 'new-process-client', 'عملية جديدة عميل', NULL, '2016-07-30 21:36:53', '2016-07-30 21:36:53'),
(5, 'new-process-supplier', 'عملية جديدة مورد', NULL, '2016-07-30 21:36:53', '2016-07-30 21:36:53'),
(6, 'new-client', 'عميل جديد', NULL, '2016-07-30 21:36:53', '2016-07-30 21:36:53'),
(7, 'new-supplier', 'مورد جديد', NULL, '2016-07-30 21:36:53', '2016-07-30 21:36:53'),
(8, 'new-outgoing', 'مصروف جديد', NULL, '2016-07-30 21:36:53', '2016-07-30 21:36:53'),
(9, 'attendance', 'حضور وانصراف', NULL, '2016-07-30 21:36:53', '2016-07-30 21:36:53'),
(10, 'query-client', 'استعلام عميل', NULL, '2016-07-30 21:36:53', '2016-07-30 21:36:53'),
(11, 'query-supplier', 'استعلام مورد', NULL, '2016-07-30 21:36:53', '2016-07-30 21:36:53'),
(12, 'query-cost-center', 'استعلام مركز تكلفة', NULL, '2016-07-30 21:36:53', '2016-07-30 21:36:53'),
(13, 'query-invoice', 'استعلام عن فاتورة', NULL, '2016-07-30 21:36:53', '2016-07-30 21:36:53'),
(14, 'database', 'قاعدة بيانات', NULL, '2016-07-30 21:36:53', '2016-07-30 21:36:53'),
(15, 'deposit-withdraw-edit', 'تعديل وارد و منصرف', NULL, NULL, NULL),
(16, 'attendance-edit', 'تعديل حضور و انصراف الموظفين', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE IF NOT EXISTS `permission_role` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Truncate table before insert `permission_role`
--

TRUNCATE TABLE `permission_role`;
--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(2, 12),
(3, 12),
(4, 12),
(5, 12),
(6, 12),
(7, 12),
(9, 12),
(10, 12),
(11, 12),
(13, 12),
(16, 12);

-- --------------------------------------------------------

--
-- Table structure for table `phones`
--

DROP TABLE IF EXISTS `phones`;
CREATE TABLE IF NOT EXISTS `phones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility_id` int(10) unsigned NOT NULL,
  `type` enum('hotline','fax','landline','mobile') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `phones_facility_id_index` (`facility_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `phones`
--

TRUNCATE TABLE `phones`;
-- --------------------------------------------------------

--
-- Table structure for table `raw_material`
--

DROP TABLE IF EXISTS `raw_material`;
CREATE TABLE IF NOT EXISTS `raw_material` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fs_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `raw_material_fs_id_foreign` (`fs_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `raw_material`
--

TRUNCATE TABLE `raw_material`;
-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=14 ;

--
-- Truncate table before insert `roles`
--

TRUNCATE TABLE `roles`;
--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator', NULL, '2016-07-30 21:36:53', '2016-07-30 21:36:53'),
(2, 'role_2', NULL, NULL, '2017-03-03 13:17:41', '2017-03-03 13:17:41'),
(5, 'role_4', NULL, NULL, '2017-03-14 08:39:58', '2017-03-14 08:39:58'),
(6, 'role_5', NULL, NULL, '2017-03-14 08:46:53', '2017-03-14 08:46:53'),
(7, 'role_6', NULL, NULL, '2017-03-14 08:49:16', '2017-03-14 08:49:16'),
(8, 'role_7', NULL, NULL, '2017-03-14 08:51:50', '2017-03-14 08:51:50'),
(9, 'role_8', NULL, NULL, '2017-03-14 08:54:15', '2017-03-14 08:54:15'),
(10, 'role_9', NULL, NULL, '2017-03-14 08:57:34', '2017-03-14 08:57:34'),
(11, 'role_10', NULL, NULL, '2017-03-14 09:10:37', '2017-03-14 09:10:37'),
(12, 'role_11', NULL, NULL, '2017-03-14 09:13:17', '2017-03-14 09:13:17'),
(13, 'role_12', NULL, NULL, '2017-03-18 12:22:10', '2017-03-18 12:22:10');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
CREATE TABLE IF NOT EXISTS `role_user` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Truncate table before insert `role_user`
--

TRUNCATE TABLE `role_user`;
--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`user_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(10, 5),
(11, 6),
(12, 7),
(13, 8),
(14, 9),
(15, 10),
(16, 11),
(17, 12),
(18, 13);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `debit_limit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=10 ;

--
-- Truncate table before insert `suppliers`
--

TRUNCATE TABLE `suppliers`;
--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `address`, `telephone`, `mobile`, `debit_limit`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'B2B LASER', 'عين شمس', '24934917', '01220909012', '50000', '2017-03-18 10:40:18', '2017-03-18 10:40:18', NULL),
(2, 'B2B LASER STEL', 'عين شمس', '24934917', '01220909020', '50000', '2017-03-18 12:50:54', '2017-03-18 12:50:54', NULL),
(3, 'محمد صلاح امين', 'عين شمس', '24618000', '01066851157', '50000', '2017-03-18 12:59:42', '2017-03-18 12:59:42', NULL),
(4, 'محمد سواق نقل', 'عين شمس', '22461800', '01000060180', '2000', '2017-03-18 17:58:18', '2017-03-18 17:58:18', NULL),
(5, 'راضى النجار', 'مصر القديمة', '24618000', '01069636265', '50000', '2017-03-19 10:34:40', '2017-03-19 10:34:40', NULL),
(6, 'جرجس  وليم', 'عين شمس', '25252525', '01270566700', '10000', '2017-03-19 13:10:24', '2017-03-19 13:10:24', NULL),
(7, 'هانى جولد', 'شبرا الخيمة الشارع الجديد محطة الفيلا', '0242238006', '01016139349', '20000', '2017-03-19 15:59:19', '2017-03-19 15:59:19', NULL),
(8, 'جورج النجار', 'ش البترول مؤسسة الزكاة', '25252525', '01282370001', '20000', '2017-03-20 09:17:43', '2017-03-20 09:17:43', NULL),
(9, 'أحمد نقل  حمد', 'عين شمس', '22461800', '01206031019', '10000', '2017-03-21 14:14:08', '2017-03-21 14:14:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_processes`
--

DROP TABLE IF EXISTS `supplier_processes`;
CREATE TABLE IF NOT EXISTS `supplier_processes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_process_id` int(10) NOT NULL,
  `supplier_id` int(10) unsigned NOT NULL,
  `employee_id` int(10) unsigned NOT NULL,
  `status` enum('active','temporary_closed','closed') COLLATE utf8_unicode_ci NOT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `has_discount` tinyint(1) DEFAULT NULL,
  `discount_percentage` decimal(11,3) DEFAULT NULL,
  `discount_value` decimal(11,3) DEFAULT NULL,
  `discount_reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `has_source_discount` tinyint(1) DEFAULT NULL,
  `source_discount_percentage` decimal(11,3) DEFAULT NULL,
  `source_discount_value` decimal(11,3) DEFAULT NULL,
  `require_invoice` tinyint(1) NOT NULL,
  `taxes_value` decimal(11,3) DEFAULT NULL,
  `total_price_taxes` decimal(11,3) DEFAULT NULL,
  `total_price` decimal(11,3) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplier_processes_supplier_id_index` (`supplier_id`),
  KEY `supplier_processes_employee_id_index` (`employee_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=21 ;

--
-- Truncate table before insert `supplier_processes`
--

TRUNCATE TABLE `supplier_processes`;
--
-- Dumping data for table `supplier_processes`
--

INSERT INTO `supplier_processes` (`id`, `name`, `client_process_id`, `supplier_id`, `employee_id`, `status`, `notes`, `has_discount`, `discount_percentage`, `discount_value`, `discount_reason`, `has_source_discount`, `source_discount_percentage`, `source_discount_value`, `require_invoice`, `taxes_value`, `total_price_taxes`, `total_price`, `deleted_at`, `updated_at`, `created_at`) VALUES
(1, 'نيم تاج وعينات قديمة', 10, 1, 1, 'active', 'بدون مكسب نهائى', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '575.000', '575.000', NULL, '2017-03-18 10:42:31', '2017-03-18 10:42:31'),
(2, 'بو كيت اكريلك', 12, 1, 1, 'active', 'لا يوجد', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '1925.000', '1925.000', NULL, '2017-03-18 12:32:19', '2017-03-18 12:32:19'),
(3, 'المستشفى الدولى للكلى فرع المهندسين', 3, 2, 1, 'active', 'السعر تقريبى لحين الرجوع الى أ/ بيمن', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 1, '0.000', '400.000', '400.000', '2017-03-21 01:05:55', '2017-03-21 01:05:55', '2017-03-18 12:56:44'),
(4, 'لوجو استكانة', 2, 3, 1, 'active', 'لم يتم المحاسبة مع محمد', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '250.000', '250.000', NULL, '2017-03-18 13:03:50', '2017-03-18 13:03:50'),
(5, 'AL KARMA', 13, 3, 6, 'active', 'لا يوجد', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '910.000', '910.000', NULL, '2017-03-18 14:21:23', '2017-03-18 14:15:15'),
(6, 'ديبوند فرع المرغنى مول مصر مول العرب', 1, 3, 6, 'closed', 'لايوجد', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '250.000', '250.000', NULL, '2017-03-21 15:34:38', '2017-03-18 14:17:33'),
(7, 'مول المشير', 7, 3, 6, 'active', 'لايوجد', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '280.000', '280.000', NULL, '2017-03-21 17:15:50', '2017-03-18 14:20:10'),
(8, 'لوحات ارشادية حلوانى', 16, 4, 1, 'active', 'لايوجد ملاحظات', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '300.000', '300.000', NULL, '2017-03-18 17:59:41', '2017-03-18 17:59:41'),
(9, 'ديبوند فرع المرغنى مول مصر مول العرب', 1, 4, 1, 'active', 'لايوجد ملاحظات', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '100.000', '100.000', NULL, '2017-03-18 18:00:45', '2017-03-18 18:00:45'),
(10, 'محمصات حازم', 5, 4, 1, 'active', 'لايوجد', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '200.000', '200.000', NULL, '2017-03-18 18:01:31', '2017-03-18 18:01:31'),
(11, 'محمصات حازم', 5, 4, 1, 'active', 'لايوجد ملاحظات', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '150.000', '150.000', NULL, '2017-03-18 18:02:48', '2017-03-18 18:02:48'),
(12, 'لافتة زجاج التجمع الخامس المدخل الرئيسى', 11, 4, 1, 'active', 'لا يوجد', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '100.000', '100.000', NULL, '2017-03-18 18:03:53', '2017-03-18 18:03:53'),
(13, 'غرب الجولف', 17, 4, 1, 'active', 'لا يوجد', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '200.000', '200.000', NULL, '2017-03-18 18:06:07', '2017-03-18 18:06:07'),
(14, 'ستاند مارلوبورو', 18, 5, 1, 'closed', 'لايوجد', 1, '58.333', '3500.000', 'دفعة قبل البرنامج', 0, '0.000', '0.000', 0, '0.000', '2500.000', '6000.000', NULL, '2017-03-19 11:38:38', '2017-03-19 10:54:44'),
(15, 'بو كيت اكريلك', 12, 6, 1, 'active', 'لا يوجد', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '300.000', '300.000', NULL, '2017-03-19 15:09:41', '2017-03-19 15:09:41'),
(16, 'deel mce', 4, 7, 1, 'closed', 'لا يوجد', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '2000.000', '2000.000', NULL, '2017-03-21 15:38:44', '2017-03-20 06:08:03'),
(17, 'لوجو استكانة', 2, 2, 1, 'active', 'لا يوجد', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '150.000', '150.000', NULL, '2017-03-20 11:53:05', '2017-03-20 11:53:05'),
(18, 'المستشفى الدولى للكلى فرع المهندسين', 3, 2, 1, 'active', 'لا يوجد', 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '600.000', '600.000', NULL, '2017-03-20 11:58:58', '2017-03-20 11:55:35'),
(19, 'AL KARMA', 13, 2, 1, 'active', NULL, 0, NULL, NULL, NULL, 0, '0.000', '0.000', 0, '0.000', '100.000', '100.000', NULL, '2017-03-20 11:57:32', '2017-03-20 11:57:32'),
(20, 'المستشفى الدولى للكلى فرع المهندسين', 3, 3, 1, 'active', 'لا يوجد', 0, NULL, NULL, NULL, 0, NULL, '0.000', 0, '0.000', '910.000', '910.000', NULL, '2017-03-22 07:15:23', '2017-03-22 07:15:23');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_process_items`
--

DROP TABLE IF EXISTS `supplier_process_items`;
CREATE TABLE IF NOT EXISTS `supplier_process_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `process_id` int(10) unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `unit_price` decimal(8,2) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplier_process_items_process_id_index` (`process_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=25 ;

--
-- Truncate table before insert `supplier_process_items`
--

TRUNCATE TABLE `supplier_process_items`;
--
-- Dumping data for table `supplier_process_items`
--

INSERT INTO `supplier_process_items` (`id`, `process_id`, `description`, `quantity`, `unit_price`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'نيم تاج مغناطيس', 5, '25.00', NULL, '2017-03-18 10:42:31', '2017-03-18 10:42:31'),
(2, 1, 'نيم تاج دبوس', 5, '15.00', NULL, '2017-03-18 10:42:31', '2017-03-18 10:42:31'),
(3, 1, 'عينات علبة فير', 2, '187.50', NULL, '2017-03-18 10:42:31', '2017-03-18 10:42:31'),
(4, 2, 'بوكيت اكريلك شفاف +فينيل مصنفر', 35, '55.00', NULL, '2017-03-18 12:32:19', '2017-03-18 12:32:19'),
(5, 3, 'تقطيع ليزر معادن', 2, '200.00', '2017-03-21 01:05:55', '2017-03-18 12:56:44', '2017-03-21 01:05:55'),
(6, 4, 'لحام وبرد لوجو استاليس', 1, '250.00', NULL, '2017-03-18 13:03:50', '2017-03-18 13:03:50'),
(7, 5, 'لافتة الخارجية للنادى لحام استاليس', 1, '910.00', NULL, '2017-03-18 14:15:15', '2017-03-18 14:21:23'),
(8, 6, 'تقفيل ديبوند 200سم', 1, '250.00', NULL, '2017-03-18 14:17:33', '2017-03-18 14:17:33'),
(9, 7, 'لحام لوجو استاليس مقاس 100سم', 4, '70.00', NULL, '2017-03-18 14:20:10', '2017-03-18 14:20:10'),
(10, 8, 'نقل اللوحات', 1, '300.00', NULL, '2017-03-18 17:59:41', '2017-03-18 17:59:41'),
(11, 9, 'نقل العدة', 1, '100.00', NULL, '2017-03-18 18:00:45', '2017-03-18 18:00:45'),
(12, 10, 'نقل العدة', 1, '200.00', NULL, '2017-03-18 18:01:31', '2017-03-18 18:01:31'),
(13, 11, 'نقل العدة ثانى مرة', 1, '150.00', NULL, '2017-03-18 18:02:48', '2017-03-18 18:02:48'),
(14, 12, 'نقل العدة الى مدينة نصر', 1, '100.00', NULL, '2017-03-18 18:03:53', '2017-03-18 18:03:53'),
(15, 13, 'فك ونقل الحروف الصاج غرب الجولف', 1, '200.00', NULL, '2017-03-18 18:06:07', '2017-03-18 18:06:07'),
(16, 14, 'استاند مارلوبورو', 3, '2000.00', NULL, '2017-03-19 10:54:44', '2017-03-19 10:54:44'),
(17, 15, 'تصنيع بوكتات اكلريلك', 12, '25.00', NULL, '2017-03-19 15:09:41', '2017-03-19 15:09:41'),
(18, 16, 'تقطيع بلاستيك لوح 2*2.6', 1, '1820.00', NULL, '2017-03-20 06:08:03', '2017-03-20 06:08:03'),
(19, 16, 'تقطيغ ليزر', 1, '130.00', NULL, '2017-03-20 06:08:03', '2017-03-20 06:08:03'),
(20, 16, 'نقل من شبرا لى المنع', 1, '50.00', NULL, '2017-03-20 06:08:03', '2017-03-20 06:08:03'),
(21, 17, 'تقطيع ستانلس  لوجو استكانة', 1, '150.00', NULL, '2017-03-20 11:53:05', '2017-03-20 11:53:05'),
(22, 18, 'تقطيع 2 لوح صاج مستشفى الكلى', 2, '300.00', NULL, '2017-03-20 11:55:35', '2017-03-20 11:55:35'),
(23, 19, 'تقطيع دوائر صاج', 4, '25.00', NULL, '2017-03-20 11:57:32', '2017-03-20 11:57:32'),
(24, 20, 'لحام وتفصيل الصاج', 13, '70.00', NULL, '2017-03-22 07:15:23', '2017-03-22 07:15:23');

-- --------------------------------------------------------

--
-- Table structure for table `test_timers`
--

DROP TABLE IF EXISTS `test_timers`;
CREATE TABLE IF NOT EXISTS `test_timers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `test_timers`
--

TRUNCATE TABLE `test_timers`;
-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_id` int(11) unsigned DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `employee_id` (`employee_id`),
  KEY `employee_id_2` (`employee_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=19 ;

--
-- Truncate table before insert `users`
--

TRUNCATE TABLE `users`;
--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `employee_id`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', 'info@b2b-adv.com', NULL, '$2y$10$H1kgkb1p2GbsLAd6RMW4Vujs7hbHVW76EIspIT/EAXNDabzznaknm', 'lqEe6uUA8SUMEg4WgvGLGffHdyoQgYBDrUV6Gt77yhkXX1t6e02FgRmjDKW9', NULL, '2016-11-12 19:58:51'),
(2, 'beshoy', 'beshoy', 'info@b2b-adv.com', 1, '$2y$10$H1kgkb1p2GbsLAd6RMW4Vujs7hbHVW76EIspIT/EAXNDabzznaknm', '2kmJr8aZcj0Yy83ZVHceTStajj3y0Ap9IZ6L195G6zWzMMgveExnIA9GwAEa', NULL, '2017-01-21 12:59:43'),
(10, NULL, 'عماد ظريف', NULL, 4, '$2y$10$nmCDgz7Y2yCQjdT/kZc86ut.QQJyitz7rP3IQOJ.A3Jz1KfJvnSqq', NULL, '2017-03-14 08:39:58', '2017-03-14 08:39:58'),
(11, NULL, 'عمر سيد', NULL, 5, '$2y$10$QyKxm8Q9jAxLwYs2KgTEte68jUPDHf.XtYF6enIwNYt59dzwB4hpS', NULL, '2017-03-14 08:46:53', '2017-03-14 08:46:53'),
(12, NULL, 'احمد طمان', NULL, 6, '$2y$10$gy4r/kIYDkDpoA/dUgj7d.7hska7oRGbzCwXAbjBhxjX13ndgDYQ2', NULL, '2017-03-14 08:49:16', '2017-03-14 08:49:16'),
(13, NULL, 'جرجس', NULL, 7, '$2y$10$hu5G11XVMIX/sjMzFb10vOZuXI78CUId2o8c6IlgBwGZewr7tdbdK', NULL, '2017-03-14 08:51:50', '2017-03-14 08:51:50'),
(14, NULL, 'ياسر', NULL, 8, '$2y$10$q7KBvolWMrwc2OM7672jv.7Sx3oFi.Rx6L.3ooKDpR1G.G0DdcUmS', NULL, '2017-03-14 08:54:15', '2017-03-14 08:54:15'),
(15, NULL, 'كيرلس', NULL, 9, '$2y$10$sVkCqkuXAqttE/N7Mfr4.er7f9xYr1vAkORss9ANhE88uhw0FfiHi', NULL, '2017-03-14 08:57:34', '2017-03-14 08:57:34'),
(16, NULL, 'محمود', NULL, 10, '$2y$10$ewWoBKoeuGzgq26a001M5eBl3G.QvtE75E.hsHSqSWPZ4bZBcFB0e', NULL, '2017-03-14 09:10:37', '2017-03-14 09:10:37'),
(17, NULL, 'حنا', NULL, 11, '$2y$10$lza9DrMbyk44B0Q5bcRGC.aVRfkUJjm4dEpkqcn0yFnLBF/GtEAAW', 'h4bla574Y1AZHg59XjSC2eiypcvwLx4Vn8zAbmw35uQsFjAoNF3YnTNFa1xu', '2017-03-14 09:13:17', '2017-03-14 09:13:17'),
(18, NULL, 'user_jSl7r', NULL, 12, '$2y$10$l4/5STXnPnCPjeBDao4ZheNlut5M8LRvDI3DZJJ.KZVJH0y2kJDii', NULL, '2017-03-18 12:22:10', '2017-03-18 12:22:10');

-- --------------------------------------------------------

--
-- Table structure for table `users_copy`
--

DROP TABLE IF EXISTS `users_copy`;
CREATE TABLE IF NOT EXISTS `users_copy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `users_copy`
--

TRUNCATE TABLE `users_copy`;
-- --------------------------------------------------------

--
-- Table structure for table `work_force`
--

DROP TABLE IF EXISTS `work_force`;
CREATE TABLE IF NOT EXISTS `work_force` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fs_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  `cost` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `work_force_fs_id_foreign` (`fs_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `work_force`
--

TRUNCATE TABLE `work_force`;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `work_force`
--
ALTER TABLE `work_force`
  ADD CONSTRAINT `work_force_fs_id_foreign` FOREIGN KEY (`fs_id`) REFERENCES `feasibility_study` (`id`) ON DELETE CASCADE;

SET FOREIGN_KEY_CHECKS = 1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
