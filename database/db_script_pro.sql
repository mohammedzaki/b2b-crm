-- phpMyAdmin SQL Dump
-- version 4.0.10.18
-- https://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Mar 14, 2017 at 03:19 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=3 ;

--
-- Dumping data for table `authorized_people`
--

INSERT INTO `authorized_people` (`id`, `client_id`, `name`, `jobtitle`, `telephone`, `email`, `created_at`, `updated_at`) VALUES
(1, 2, 'محمد عبد الفتاح', 'مدير مشروعات', '01202220762', 'mohamed.abdelfatah@leoburnett.com', NULL, NULL),
(2, 3, 'مهندسة نهال فتحى خليل', 'مدير عام تنفيذى المعمارى', '01227451336', 'nehal_petrojet@yahoo.com', NULL, NULL);

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
  `referral_percentage` decimal(11,3) DEFAULT NULL,
  `credit_limit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_client_company` tinyint(1) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT AUTO_INCREMENT=3 ;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `address`, `telephone`, `mobile`, `referral_id`, `referral_percentage`, `credit_limit`, `is_client_company`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Leo burnett', '2005c corniche el nil st. nile city towers.', '24618000', '01000060180', NULL, NULL, '500000', 1, NULL, '2017-03-14 08:03:42', '2017-03-14 08:03:42'),
(2, 'بتروجت', 'التجمع الخامس مجمع البترول', '26253329', '01227451336', NULL, NULL, '500000', 1, NULL, '2017-03-14 10:18:50', '2017-03-14 10:18:50');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT AUTO_INCREMENT=2 ;

--
-- Dumping data for table `client_processes`
--

INSERT INTO `client_processes` (`id`, `name`, `client_id`, `employee_id`, `status`, `notes`, `has_discount`, `discount_percentage`, `discount_value`, `discount_reason`, `require_invoice`, `taxes_value`, `total_price_taxes`, `total_price`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'ديبوند فرع المرغنى مول مصر مول العرب', 1, 1, 'active', 'لايوجد ملاحظات', 1, '0.500', '518.000', 'خصم من الممبع', 1, '11045.060', '96007.060', '85480.000', NULL, '2017-03-14 10:13:12', '2017-03-14 10:13:12');

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
  `unit_price` decimal(11,3) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_process_items_process_id_index` (`process_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=8 ;

--
-- Dumping data for table `client_process_items`
--

INSERT INTO `client_process_items` (`id`, `process_id`, `description`, `quantity`, `unit_price`, `deleted_at`, `created_at`, `updated_at`) VALUES
(4, 1, 'ديبوند المرغنى', 45, '1200.00', NULL, '2017-03-14 10:13:12', '2017-03-14 10:13:12'),
(5, 1, 'ديبوند + لافتة + فلاج مول مصر', 1, '8740.00', NULL, '2017-03-14 10:13:12', '2017-03-14 10:13:12'),
(6, 1, 'ديبوند +لافتة + فلاج مول العرب', 1, '8740.00', NULL, '2017-03-14 10:13:12', '2017-03-14 10:13:12'),
(7, 1, 'فريمات الامنيوم فرع محى الدين', 1, '14000.00', NULL, '2017-03-14 10:13:12', '2017-03-14 10:13:12');

-- --------------------------------------------------------

--
-- Table structure for table `deposit_withdraws`
--

DROP TABLE IF EXISTS `deposit_withdraws`;
CREATE TABLE IF NOT EXISTS `deposit_withdraws` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `depositValue` decimal(11,3) DEFAULT '0.00',
  `withdrawValue` decimal(11,3) DEFAULT '0.00',
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=12 ;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `emp_id`, `name`, `ssn`, `gender`, `martial_status`, `birth_date`, `department`, `hiring_date`, `daily_salary`, `working_hours`, `job_title`, `telephone`, `mobile`, `facility_id`, `can_not_use_program`, `borrow_system`, `deleted_at_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'استاذ بيشوي', '28100010500755', 'm', 'married', '1980-02-09', '', '2001-01-01', 200, 8, 'مدير', '', '01200000000', 1, 0, 0, NULL, NULL, NULL, '2016-11-12 17:58:14'),
(4, 2, 'عماد ظريف سعيد جرجس', '28210112401113', 'm', 'married', '1982-10-11', 'المرج', '2005-02-02', 125, 10, 'رئيس عمال', '25252525', '01225524231', 1, 0, 0, NULL, NULL, '2017-03-14 08:39:58', '2017-03-14 08:39:58'),
(5, 3, 'عمر سيد عويس عطيه', '28803140102475', 'm', 'single', '1988-03-14', 'مصر القديمة', '2013-01-02', 80, 10, 'عامل', '25252525', '01148209148', 1, 0, 0, NULL, NULL, '2017-03-14 08:46:53', '2017-03-14 08:46:53'),
(6, 4, 'احمد محمود عبد العظيم  طمان', '27803130101639', 'm', 'married', '1978-03-13', 'المرج', '2014-12-14', 80, 10, 'مشرف', '53012912', '01220909014', 1, 0, 0, NULL, NULL, '2017-03-14 08:49:16', '2017-03-14 08:49:16'),
(7, 5, 'جرجس عزيز باسيلى بطرس', '28906010102558', 'm', 'single', '1989-06-01', 'منشية ناصر', '2015-12-12', 80, 10, 'عامل', '15252525', '01270928768', 1, 0, 0, NULL, NULL, '2017-03-14 08:51:50', '2017-03-14 08:51:50'),
(8, 6, 'ياسر صلاح عبد الحميد عثمان', '28702070101297', 'm', 'single', '1987-02-07', 'عين شمس', '2016-02-01', 85, 10, 'عامل', '25252525', '01211096247', 1, 0, 0, NULL, NULL, '2017-03-14 08:54:15', '2017-03-14 08:55:21'),
(9, 7, 'كيرلس رأفت فريز جرجس', '29208080101651', 'm', 'married', '1992-08-08', 'المطرية', '2017-03-07', 60, 10, 'عامل', '22555584', '01223332780', 1, 0, 0, NULL, NULL, '2017-03-14 08:57:34', '2017-03-14 08:57:34'),
(10, 8, 'محمود قاسم احمد محمد', '29009272401558', 'm', 'single', '1990-09-27', 'سمالوط _ المنيا', '2017-03-14', 115, 10, 'عامل', '77121510', '01155591981', 1, 0, 0, NULL, NULL, '2017-03-14 09:10:37', '2017-03-14 09:10:37'),
(11, 9, 'يوحنا ابراهيم بدروس', '28003210103192', 'm', 'single', '1980-03-21', 'عين شمس', '2017-02-01', 57.75, 10, 'محاسب', '22461800', '01282508848', 1, 0, 0, NULL, NULL, '2017-03-14 09:13:17', '2017-03-14 09:13:17');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

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
  `opening_amount` decimal(11,3) DEFAULT NULL,
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
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `name`, `manager_id`, `type`, `tax_file`, `tax_card`, `trade_record`, `sales_tax`, `opening_amount`, `country_sales_tax`, `logo`, `country`, `city`, `region`, `address`, `email`, `website`, `created_at`, `updated_at`) VALUES
(1, 'B2bAdv', 1, 'stock', '11200', '23000', '98100', 13, '0', NULL, 'Q254GxNKrjKryjnN46zRKgloE00ID0DkGvmDajL6.png', 'مصر', 'القاهرة', 'عين شمس', '12 ش محمد متولي', 'info@b2b-adv.com', 'http://www.b2b-adv.com/', NULL, '2016-12-22 21:17:12');

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

-- --------------------------------------------------------

--
-- Table structure for table `opening_amounts`
--

DROP TABLE IF EXISTS `opening_amounts`;
CREATE TABLE IF NOT EXISTS `opening_amounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` decimal(11,3) DEFAULT NULL,
  `deposit_date` datetime DEFAULT NULL,
  `reason` varchar(1000) DEFAULT NULL,
  `facility_id` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

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
(16, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=13 ;

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
(12, 'role_11', NULL, NULL, '2017-03-14 09:13:17', '2017-03-14 09:13:17');

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
(17, 12);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

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
  `unit_price` decimal(11,3) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplier_process_items_process_id_index` (`process_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=18 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `employee_id`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', 'info@b2b-adv.com', NULL, '$2y$10$H1kgkb1p2GbsLAd6RMW4Vujs7hbHVW76EIspIT/EAXNDabzznaknm', 'lqEe6uUA8SUMEg4WgvGLGffHdyoQgYBDrUV6Gt77yhkXX1t6e02FgRmjDKW9', NULL, '2016-11-12 19:58:51'),
(2, 'beshoy', 'beshoy', 'info@b2b-adv.com', 1, '$2y$10$H1kgkb1p2GbsLAd6RMW4Vujs7hbHVW76EIspIT/EAXNDabzznaknm', '1WnQnBl0hAU8wchKZzli58iORnRnG7IR9bIZP6uSuNcUBgaRCHUVEIjyoDA4', NULL, '2017-01-21 12:59:43'),
(10, NULL, 'عماد ظريف', NULL, 4, '$2y$10$nmCDgz7Y2yCQjdT/kZc86ut.QQJyitz7rP3IQOJ.A3Jz1KfJvnSqq', NULL, '2017-03-14 08:39:58', '2017-03-14 08:39:58'),
(11, NULL, 'عمر سيد', NULL, 5, '$2y$10$QyKxm8Q9jAxLwYs2KgTEte68jUPDHf.XtYF6enIwNYt59dzwB4hpS', NULL, '2017-03-14 08:46:53', '2017-03-14 08:46:53'),
(12, NULL, 'احمد طمان', NULL, 6, '$2y$10$gy4r/kIYDkDpoA/dUgj7d.7hska7oRGbzCwXAbjBhxjX13ndgDYQ2', NULL, '2017-03-14 08:49:16', '2017-03-14 08:49:16'),
(13, NULL, 'جرجس', NULL, 7, '$2y$10$hu5G11XVMIX/sjMzFb10vOZuXI78CUId2o8c6IlgBwGZewr7tdbdK', NULL, '2017-03-14 08:51:50', '2017-03-14 08:51:50'),
(14, NULL, 'ياسر', NULL, 8, '$2y$10$q7KBvolWMrwc2OM7672jv.7Sx3oFi.Rx6L.3ooKDpR1G.G0DdcUmS', NULL, '2017-03-14 08:54:15', '2017-03-14 08:54:15'),
(15, NULL, 'كيرلس', NULL, 9, '$2y$10$sVkCqkuXAqttE/N7Mfr4.er7f9xYr1vAkORss9ANhE88uhw0FfiHi', NULL, '2017-03-14 08:57:34', '2017-03-14 08:57:34'),
(16, NULL, 'محمود', NULL, 10, '$2y$10$ewWoBKoeuGzgq26a001M5eBl3G.QvtE75E.hsHSqSWPZ4bZBcFB0e', NULL, '2017-03-14 09:10:37', '2017-03-14 09:10:37'),
(17, NULL, 'حنا', NULL, 11, '$2y$10$lza9DrMbyk44B0Q5bcRGC.aVRfkUJjm4dEpkqcn0yFnLBF/GtEAAW', NULL, '2017-03-14 09:13:17', '2017-03-14 09:13:17');

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
