-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.12.0.7122
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for shopdb
CREATE DATABASE IF NOT EXISTS `shopdb` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci */;
USE `shopdb`;

-- Dumping structure for table shopdb.accounts
CREATE TABLE IF NOT EXISTS `accounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `level` enum('root','group','general','subsidiary') NOT NULL DEFAULT 'subsidiary' COMMENT 'سطح حساب: ریشه(۱رقم)، گروه(۲رقم)، کل(۴رقم)، معین(۶رقم)',
  `code` char(6) NOT NULL COMMENT 'کد حساب با طول ثابت ۶ رقم - صفرهای پیشرو الزامی',
  `title` varchar(255) NOT NULL,
  `nature` enum('debtor','creditor') NOT NULL COMMENT 'ماهیت حساب: بدهکار یا بستانکار',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `accounts_parent_id_foreign` (`parent_id`),
  CONSTRAINT `accounts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.accounts: ~0 rows (approximately)
DELETE FROM `accounts`;

-- Dumping structure for table shopdb.activity_log
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) unsigned DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.activity_log: ~2 rows (approximately)
DELETE FROM `activity_log`;
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
	(1, 'user_management', 'created', 'App\\Models\\User', 'created', 1, NULL, NULL, '{"attributes":{"name":"\\u0645\\u062f\\u06cc\\u0631 \\u0633\\u06cc\\u0633\\u062a\\u0645","email":"edsanig@gmail.com"}}', NULL, '2026-06-01 17:05:55', '2026-06-01 17:05:55'),
	(2, 'product', 'created', 'App\\Models\\Product', 'created', 1, 'App\\Models\\User', 1, '{"attributes":{"id":1,"category_id":2,"name":"\\u0631\\u0698 \\u0644\\u0628 \\u0645\\u0627\\u06cc\\u0639 \\u0648\\u06cc\\u0648\\u0644\\u062a \\u0642\\u0631\\u0645\\u0632 02","sku":"010100001","purchase_price":"30000.00","sale_price":"45000.00","stock":5,"is_active":1,"created_at":"2026-06-01T21:15:20.000000Z","updated_at":"2026-06-01T21:15:20.000000Z","unit_id":1}}', NULL, '2026-06-01 17:45:20', '2026-06-01 17:45:20');

-- Dumping structure for table shopdb.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.cache: ~1 rows (approximately)
DELETE FROM `cache`;
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('atiashop-cache-spatie.permission.cache', 'a:3:{s:5:"alias";a:4:{s:1:"a";s:2:"id";s:1:"b";s:4:"name";s:1:"c";s:10:"guard_name";s:1:"r";s:5:"roles";}s:11:"permissions";a:11:{i:0;a:4:{s:1:"a";i:1;s:1:"b";s:13:"view products";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:1;a:4:{s:1:"a";i:2;s:1:"b";s:15:"create products";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:"a";i:3;s:1:"b";s:13:"edit products";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:"a";i:4;s:1:"b";s:15:"delete products";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:4;a:4:{s:1:"a";i:5;s:1:"b";s:10:"view users";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:"a";i:6;s:1:"b";s:12:"create users";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:6;a:4:{s:1:"a";i:7;s:1:"b";s:10:"edit users";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:7;a:4:{s:1:"a";i:8;s:1:"b";s:12:"delete users";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:8;a:4:{s:1:"a";i:9;s:1:"b";s:12:"view reports";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:9;a:4:{s:1:"a";i:10;s:1:"b";s:17:"manage accounting";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:3;}}i:10;a:4:{s:1:"a";i:11;s:1:"b";s:16:"manage inventory";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:4;}}}s:5:"roles";a:4:{i:0;a:3:{s:1:"a";i:1;s:1:"b";s:5:"admin";s:1:"c";s:3:"web";}i:1;a:3:{s:1:"a";i:2;s:1:"b";s:7:"manager";s:1:"c";s:3:"web";}i:2;a:3:{s:1:"a";i:4;s:1:"b";s:9:"warehouse";s:1:"c";s:3:"web";}i:3;a:3:{s:1:"a";i:3;s:1:"b";s:10:"accountant";s:1:"c";s:3:"web";}}}', 1780434156);

-- Dumping structure for table shopdb.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table shopdb.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_code_parent_id_unique` (`code`,`parent_id`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.categories: ~3 rows (approximately)
DELETE FROM `categories`;
INSERT INTO `categories` (`id`, `parent_id`, `name`, `code`, `created_at`, `updated_at`) VALUES
	(1, NULL, 'رژ لب', '01', '2026-06-01 17:44:13', '2026-06-01 17:44:13'),
	(2, 1, 'رژ لب مایع', '01', '2026-06-01 17:44:24', '2026-06-01 17:44:24'),
	(3, 1, 'رژ لب جامد', '02', '2026-06-01 17:44:34', '2026-06-01 17:44:34');

-- Dumping structure for table shopdb.customers
CREATE TABLE IF NOT EXISTS `customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.customers: ~0 rows (approximately)
DELETE FROM `customers`;

-- Dumping structure for table shopdb.detailed_accounts
CREATE TABLE IF NOT EXISTS `detailed_accounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` char(6) NOT NULL COMMENT 'کد ۶ رقمی ثابت تفصیلی شناور (صفرهای پیشرو الزامی)',
  `title` varchar(255) NOT NULL,
  `type` enum('person','bank','cash','cost_center','project') NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `detailed_accounts_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.detailed_accounts: ~0 rows (approximately)
DELETE FROM `detailed_accounts`;

-- Dumping structure for table shopdb.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table shopdb.invoices
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_type` enum('sales','return') NOT NULL DEFAULT 'sales',
  `parent_invoice_id` bigint(20) unsigned DEFAULT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `receiver_name` varchar(255) DEFAULT NULL,
  `delivery_method` varchar(255) DEFAULT 'internal',
  `address` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `seller_name` varchar(255) DEFAULT NULL,
  `project_code` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `register_number` varchar(255) DEFAULT NULL,
  `order_number` varchar(255) DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `delivery_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(10,2) NOT NULL DEFAULT 10.00 COMMENT 'درصد مالیات',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `extra_charges_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_type` enum('cash','credit','check') NOT NULL DEFAULT 'cash',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `customer_debt` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'مانده حساب مشتری (فقط نمایشی)',
  `status` enum('pending','paid','partially_paid','overdue') NOT NULL DEFAULT 'paid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_customer_id_foreign` (`customer_id`),
  KEY `invoices_user_id_foreign` (`user_id`),
  KEY `invoices_parent_invoice_id_foreign` (`parent_invoice_id`),
  CONSTRAINT `invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `parties` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_parent_invoice_id_foreign` FOREIGN KEY (`parent_invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.invoices: ~0 rows (approximately)
DELETE FROM `invoices`;

-- Dumping structure for table shopdb.invoice_items
CREATE TABLE IF NOT EXISTS `invoice_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `discount_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_percent` decimal(5,2) DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `packing_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `extra_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `staff_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `stock_at_sale` int(11) NOT NULL DEFAULT 0,
  `unit_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  KEY `invoice_items_product_id_foreign` (`product_id`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoice_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.invoice_items: ~0 rows (approximately)
DELETE FROM `invoice_items`;

-- Dumping structure for table shopdb.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table shopdb.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.job_batches: ~0 rows (approximately)
DELETE FROM `job_batches`;

-- Dumping structure for table shopdb.journal_items
CREATE TABLE IF NOT EXISTS `journal_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `journal_voucher_id` bigint(20) unsigned NOT NULL,
  `account_id` bigint(20) unsigned NOT NULL,
  `person_detailed_id` bigint(20) unsigned DEFAULT NULL,
  `bank_cash_detailed_id` bigint(20) unsigned DEFAULT NULL,
  `cost_center_detailed_id` bigint(20) unsigned DEFAULT NULL,
  `project_detailed_id` bigint(20) unsigned DEFAULT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `row_description` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `journal_items_journal_voucher_id_foreign` (`journal_voucher_id`),
  KEY `journal_items_person_detailed_id_foreign` (`person_detailed_id`),
  KEY `journal_items_bank_cash_detailed_id_foreign` (`bank_cash_detailed_id`),
  KEY `journal_items_cost_center_detailed_id_foreign` (`cost_center_detailed_id`),
  KEY `journal_items_project_detailed_id_foreign` (`project_detailed_id`),
  KEY `journal_items_account_id_person_detailed_id_index` (`account_id`,`person_detailed_id`),
  KEY `journal_items_account_id_cost_center_detailed_id_index` (`account_id`,`cost_center_detailed_id`),
  CONSTRAINT `journal_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  CONSTRAINT `journal_items_bank_cash_detailed_id_foreign` FOREIGN KEY (`bank_cash_detailed_id`) REFERENCES `detailed_accounts` (`id`),
  CONSTRAINT `journal_items_cost_center_detailed_id_foreign` FOREIGN KEY (`cost_center_detailed_id`) REFERENCES `detailed_accounts` (`id`),
  CONSTRAINT `journal_items_journal_voucher_id_foreign` FOREIGN KEY (`journal_voucher_id`) REFERENCES `journal_vouchers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `journal_items_person_detailed_id_foreign` FOREIGN KEY (`person_detailed_id`) REFERENCES `detailed_accounts` (`id`),
  CONSTRAINT `journal_items_project_detailed_id_foreign` FOREIGN KEY (`project_detailed_id`) REFERENCES `detailed_accounts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.journal_items: ~0 rows (approximately)
DELETE FROM `journal_items`;

-- Dumping structure for table shopdb.journal_vouchers
CREATE TABLE IF NOT EXISTS `journal_vouchers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `temporary_number` varchar(50) NOT NULL COMMENT 'شماره موقت پیش‌نویس (مثل: TMP-140201-0001)',
  `voucher_number` varchar(50) DEFAULT NULL COMMENT 'شماره نهایی سند مالی (مثال: INV-140501-0001)',
  `issue_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('draft','first_signed','second_signed','finalized') NOT NULL DEFAULT 'draft' COMMENT 'وضعیت سند: draft(پیش‌نویس)، first_signed(امضای اول/قرمز)، second_signed(امضای دوم/آبی)، finalized(امضای سوم/سبز)',
  `created_by` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned NOT NULL,
  `source_type` varchar(255) DEFAULT NULL,
  `source_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `journal_vouchers_temporary_number_unique` (`temporary_number`),
  UNIQUE KEY `journal_vouchers_voucher_number_unique` (`voucher_number`),
  KEY `journal_vouchers_created_by_foreign` (`created_by`),
  KEY `journal_vouchers_team_id_foreign` (`team_id`),
  KEY `journal_vouchers_source_type_source_id_index` (`source_type`,`source_id`),
  KEY `journal_vouchers_status_team_id_index` (`status`,`team_id`),
  CONSTRAINT `journal_vouchers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `journal_vouchers_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.journal_vouchers: ~0 rows (approximately)
DELETE FROM `journal_vouchers`;

-- Dumping structure for table shopdb.menus
CREATE TABLE IF NOT EXISTS `menus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `route` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `permission` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menus_parent_id_foreign` (`parent_id`),
  CONSTRAINT `menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.menus: ~26 rows (approximately)
DELETE FROM `menus`;
INSERT INTO `menus` (`id`, `title`, `route`, `icon`, `parent_id`, `order`, `permission`, `created_at`, `updated_at`) VALUES
	(1, 'میز کار (داشبورد)', 'dashboard', 'fa-chart-line', NULL, 1, NULL, '2026-02-25 18:17:53', '2026-02-25 18:17:53'),
	(2, 'محصولات و خدمات', 'products', 'fa-box', NULL, 3, NULL, '2026-02-25 18:17:53', '2026-02-25 18:17:53'),
	(3, 'لیست محصولات', 'products.index', 'fa-list', 2, 1, NULL, '2026-02-25 18:17:53', '2026-02-25 18:17:53'),
	(4, 'دسته‌بندی‌ها', 'categories.index', 'fa-tags', 2, 2, NULL, '2026-02-25 18:17:53', '2026-02-25 18:17:53'),
	(5, 'تعاریف پایه', NULL, 'fa-cog', NULL, 10, NULL, '2026-02-25 18:17:53', '2026-02-25 18:17:53'),
	(6, 'مشتریان', 'parties.index', 'fa-users', NULL, 4, NULL, '2026-02-25 18:17:53', '2026-02-25 18:17:53'),
	(7, 'مدیریت کاربران', 'users.index', 'fa-user-lock', 15, 1, NULL, '2026-02-25 18:17:53', '2026-02-25 18:17:53'),
	(8, 'واحدهای کالا', 'units.index', 'fa-ruler', 5, 3, NULL, '2026-02-25 18:17:53', '2026-02-25 18:17:53'),
	(9, 'ثبت فاکتور جدید', 'invoices.create', 'fa-file-invoice', 11, 1, NULL, '2026-05-07 14:46:39', '2026-05-07 14:46:39'),
	(10, 'لیست فاکتورها', 'invoices.index', 'fa-list', 11, 2, NULL, '2026-05-08 04:07:27', NULL),
	(11, 'فاکتورها', NULL, 'fa-receipt', NULL, 2, NULL, '2026-05-12 16:50:25', '2026-05-12 16:50:25'),
	(12, 'افزودن محصول جدید', NULL, 'fa-plus-circle', 2, 6, NULL, '2026-05-12 16:50:25', '2026-05-12 16:50:25'),
	(13, 'ورود از اکسل', NULL, 'fa-file-import', 2, 7, NULL, '2026-05-12 16:50:25', '2026-05-12 16:50:25'),
	(14, 'کارکرد کالا', NULL, 'fa-chart-line', 2, 8, NULL, '2026-05-12 16:50:25', '2026-05-12 16:50:25'),
	(15, 'هزینه‌ها', NULL, 'fa-money-bill', 25, 8, NULL, '2026-05-12 16:50:25', '2026-05-12 16:50:25'),
	(16, 'درآمدها', NULL, 'fa-chart-line', 25, 9, NULL, '2026-05-12 16:50:25', '2026-05-12 16:50:25'),
	(17, 'چک‌ها', NULL, 'fa-money-check', 25, 3, NULL, '2026-05-12 16:50:25', '2026-05-12 16:50:25'),
	(18, 'حساب‌ها', NULL, 'fa-building-columns', 25, 4, NULL, '2026-05-12 16:50:25', '2026-05-12 16:50:25'),
	(19, 'سود و زیان', NULL, 'fa-chart-line', 25, 1, NULL, '2026-05-12 16:50:25', '2026-05-12 16:50:25'),
	(20, 'گزارش محصولات', NULL, 'fa-chart-bar', 25, 2, NULL, '2026-05-12 16:50:25', '2026-05-12 16:50:25'),
	(21, 'تنظیمات فروشگاه', 'settings.general', 'fa-store', 38, 1, NULL, '2026-05-12 16:50:25', '2026-05-12 16:50:25'),
	(22, 'کارت‌های داشبورد', NULL, 'fa-th', 38, 2, NULL, '2026-05-12 16:50:25', '2026-05-12 16:50:25'),
	(23, 'پشتیبان‌گیری', NULL, 'fa-database', 38, 3, NULL, '2026-05-12 16:50:25', '2026-05-12 16:50:25'),
	(24, 'نقش‌ها و مجوزها', NULL, 'fa-lock', 38, 2, NULL, '2026-05-12 16:50:26', '2026-05-12 16:50:26'),
	(25, 'گزارشات', NULL, 'fa-chart-line', NULL, 5, NULL, NULL, NULL),
	(38, 'تنظیمات', NULL, 'fa-gear', NULL, 7, NULL, '2026-05-15 16:41:12', '2026-05-15 16:41:12');

-- Dumping structure for table shopdb.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.migrations: ~37 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '0001_01_01_000003_create_customers_table', 1),
	(5, '0001_01_01_000004_create_categories_table', 1),
	(6, '0001_01_01_000005_create_products_table', 1),
	(7, '0001_01_01_000006_create_invoices_table', 1),
	(8, '0001_01_01_000007_create_invoice_items_table', 1),
	(9, '2026_02_19_213852_create_permission_tables', 1),
	(10, '2026_02_22_151235_create_stock_movements_table', 1),
	(11, '2026_02_22_153639_add_parent_id_to_categories_table', 1),
	(12, '2026_02_22_160826_modify_categories_and_products_table', 1),
	(13, '2026_02_22_180515_fix_categories_code_unique', 1),
	(14, '2026_02_22_193629_create_accounts_table', 1),
	(15, '2026_02_22_193713_create_parties_table', 1),
	(16, '2026_02_22_194000_create_transactions_table', 1),
	(17, '2026_02_26_003115_create_menus_table', 1),
	(18, '2026_02_26_010244_create_units_table', 1),
	(19, '2026_02_27_212901_add_symbol_to_units_table', 1),
	(20, '2026_03_02_085922_add_unit_id_to_products_table', 1),
	(21, '2026_05_07_210837_add_invoice_menu_item_to_menus_table', 1),
	(22, '2026_05_07_214523_fix_invoices_foreign_key_to_parties', 1),
	(23, '2026_05_08_000000_add_user_id_to_invoices_table', 1),
	(24, '2026_05_08_110608_add_invoices_list_to_menus_table', 1),
	(25, '2026_05_08_142029_add_advanced_fields_to_invoices_table', 1),
	(26, '2026_05_08_142110_add_advanced_fields_to_invoice_items_table', 1),
	(27, '2026_05_08_144128_add_payment_fields_to_invoices_table', 1),
	(28, '2026_05_12_200043_update_menu_icons_to_fontawesome', 1),
	(29, '2026_05_15_200522_create_settings_table', 1),
	(30, '2026_05_30_163639_create_detailed_accounts_table', 1),
	(31, '2026_05_30_163641_create_teams_table', 1),
	(32, '2026_05_30_165119_create_journal_vouchers_table', 1),
	(33, '2026_05_30_165121_create_journal_items_table', 1),
	(34, '2026_06_01_182346_create_activity_log_table', 1),
	(35, '2026_06_01_182347_add_event_column_to_activity_log_table', 1),
	(36, '2026_06_01_182348_add_batch_uuid_column_to_activity_log_table', 1),
	(37, '2026_06_01_182536_add_deleted_at_to_invoices_table', 1);

-- Dumping structure for table shopdb.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.model_has_permissions: ~0 rows (approximately)
DELETE FROM `model_has_permissions`;

-- Dumping structure for table shopdb.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.model_has_roles: ~1 rows (approximately)
DELETE FROM `model_has_roles`;
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1);

-- Dumping structure for table shopdb.parties
CREATE TABLE IF NOT EXISTS `parties` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `type` enum('real','legal') NOT NULL DEFAULT 'real',
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `national_code` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_customer` tinyint(1) NOT NULL DEFAULT 0,
  `is_supplier` tinyint(1) NOT NULL DEFAULT 0,
  `is_employee` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parties_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.parties: ~1 rows (approximately)
DELETE FROM `parties`;
INSERT INTO `parties` (`id`, `code`, `type`, `first_name`, `last_name`, `name`, `national_code`, `mobile`, `phone`, `address`, `is_customer`, `is_supplier`, `is_employee`, `status`, `created_at`, `updated_at`) VALUES
	(1, '0001', 'real', 'معصومه', 'امینی', 'عمه معصومه', '', '09385247621', '09385247621', 'تهران پردیس فروردین شمالی', 1, 0, 0, 1, '2026-06-01 17:46:58', '2026-06-01 17:47:04');

-- Dumping structure for table shopdb.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table shopdb.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.permissions: ~11 rows (approximately)
DELETE FROM `permissions`;
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'view products', 'web', '2026-06-01 17:05:54', '2026-06-01 17:05:54'),
	(2, 'create products', 'web', '2026-06-01 17:05:54', '2026-06-01 17:05:54'),
	(3, 'edit products', 'web', '2026-06-01 17:05:54', '2026-06-01 17:05:54'),
	(4, 'delete products', 'web', '2026-06-01 17:05:54', '2026-06-01 17:05:54'),
	(5, 'view users', 'web', '2026-06-01 17:05:54', '2026-06-01 17:05:54'),
	(6, 'create users', 'web', '2026-06-01 17:05:54', '2026-06-01 17:05:54'),
	(7, 'edit users', 'web', '2026-06-01 17:05:54', '2026-06-01 17:05:54'),
	(8, 'delete users', 'web', '2026-06-01 17:05:54', '2026-06-01 17:05:54'),
	(9, 'view reports', 'web', '2026-06-01 17:05:54', '2026-06-01 17:05:54'),
	(10, 'manage accounting', 'web', '2026-06-01 17:05:54', '2026-06-01 17:05:54'),
	(11, 'manage inventory', 'web', '2026-06-01 17:05:54', '2026-06-01 17:05:54');

-- Dumping structure for table shopdb.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(20) NOT NULL,
  `purchase_price` decimal(15,2) NOT NULL,
  `sale_price` decimal(15,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'وضعیت فعال یا غیرفعال بودن کالا برای فروش',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unit_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_sku_index` (`sku`),
  KEY `products_unit_id_foreign` (`unit_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.products: ~1 rows (approximately)
DELETE FROM `products`;
INSERT INTO `products` (`id`, `category_id`, `name`, `sku`, `purchase_price`, `sale_price`, `stock`, `is_active`, `created_at`, `updated_at`, `unit_id`) VALUES
	(1, 2, 'رژ لب مایع ویولت قرمز 02', '010100001', 30000.00, 45000.00, 5, 1, '2026-06-01 17:45:20', '2026-06-01 17:45:20', 1);

-- Dumping structure for table shopdb.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.roles: ~4 rows (approximately)
DELETE FROM `roles`;
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'web', '2026-06-01 17:05:54', '2026-06-01 17:05:54'),
	(2, 'manager', 'web', '2026-06-01 17:05:54', '2026-06-01 17:05:54'),
	(3, 'accountant', 'web', '2026-06-01 17:05:54', '2026-06-01 17:05:54'),
	(4, 'warehouse', 'web', '2026-06-01 17:05:54', '2026-06-01 17:05:54');

-- Dumping structure for table shopdb.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.role_has_permissions: ~20 rows (approximately)
DELETE FROM `role_has_permissions`;
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(1, 2),
	(1, 4),
	(2, 1),
	(2, 2),
	(3, 1),
	(3, 2),
	(4, 1),
	(5, 1),
	(5, 2),
	(6, 1),
	(7, 1),
	(8, 1),
	(9, 1),
	(9, 2),
	(9, 3),
	(10, 1),
	(10, 3),
	(11, 1),
	(11, 4);

-- Dumping structure for table shopdb.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.sessions: ~1 rows (approximately)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('Qi4NiEg4bvMxZmdQ3NyThGwUDHZJnwnAvIh9r5gg', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSDIwdE9DTnRyc0lBVHhVU1U3TTRhcFFxTTJNZW9jUGJjM1BwUE91bCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnZvaWNlcy9jcmVhdGUiO3M6NToicm91dGUiO3M6MTU6Imludm9pY2VzLmNyZWF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1780349939);

-- Dumping structure for table shopdb.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.settings: ~14 rows (approximately)
DELETE FROM `settings`;
INSERT INTO `settings` (`id`, `key`, `value`, `type`, `group`, `created_at`, `updated_at`) VALUES
	(1, 'shop_name', 'عاطیا شاپ', 'text', 'general', '2026-06-01 17:38:33', '2026-06-01 17:42:43'),
	(2, 'shop_address', 'تهران', 'text', 'general', '2026-06-01 17:38:33', '2026-06-01 17:42:43'),
	(3, 'shop_phone', '09106957891', 'text', 'general', '2026-06-01 17:38:33', '2026-06-01 17:42:43'),
	(4, 'shop_register_number', '2321', 'text', 'general', '2026-06-01 17:38:33', '2026-06-01 17:42:43'),
	(5, 'shop_economic_code', '---', 'text', 'general', '2026-06-01 17:38:33', '2026-06-01 17:42:43'),
	(6, 'currency', 'تومان', 'text', 'general', '2026-06-01 17:38:33', '2026-06-01 17:42:43'),
	(7, 'tax_enabled', '0', 'boolean', 'general', '2026-06-01 17:38:33', '2026-06-01 17:42:51'),
	(8, 'tax_rate', '10', 'number', 'general', '2026-06-01 17:38:33', '2026-06-01 17:38:33'),
	(9, 'discount_enabled', '1', 'boolean', 'general', '2026-06-01 17:38:33', '2026-06-01 17:42:51'),
	(10, 'invoice_footer_text', NULL, 'text', 'general', '2026-06-01 17:38:33', '2026-06-01 17:38:33'),
	(11, 'invoice_print_size', 'A4', 'text', 'general', '2026-06-01 17:38:33', '2026-06-01 17:38:33'),
	(12, 'invoice_show_logo', '0', 'boolean', 'general', '2026-06-01 17:38:33', '2026-06-01 17:42:51'),
	(13, 'invoice_show_stamp', '0', 'boolean', 'general', '2026-06-01 17:38:33', '2026-06-01 17:42:51'),
	(14, 'dashboard_cards', '["total_sales","debts","total_purchases","receivables","proformas","total_expenses"]', 'json', 'general', '2026-06-01 17:42:07', '2026-06-01 17:42:07');

-- Dumping structure for table shopdb.stock_movements
CREATE TABLE IF NOT EXISTS `stock_movements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `type` enum('in','out') NOT NULL,
  `quantity` int(11) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movements_product_id_foreign` (`product_id`),
  CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.stock_movements: ~0 rows (approximately)
DELETE FROM `stock_movements`;

-- Dumping structure for table shopdb.teams
CREATE TABLE IF NOT EXISTS `teams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'نام تیم (مثال: مالی، فروش، انبار)',
  `team_leader_id` bigint(20) unsigned DEFAULT NULL COMMENT 'سرپرست تیم (ارجاع به users)',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teams_team_leader_id_foreign` (`team_leader_id`),
  CONSTRAINT `teams_team_leader_id_foreign` FOREIGN KEY (`team_leader_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.teams: ~0 rows (approximately)
DELETE FROM `teams`;

-- Dumping structure for table shopdb.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `type` enum('debit','credit') NOT NULL COMMENT 'نوع تراکنش: بدهکار (debit) یا بستانکار (credit)',
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_account_id_foreign` (`account_id`),
  CONSTRAINT `transactions_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.transactions: ~0 rows (approximately)
DELETE FROM `transactions`;

-- Dumping structure for table shopdb.units
CREATE TABLE IF NOT EXISTS `units` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `symbol` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.units: ~1 rows (approximately)
DELETE FROM `units`;
INSERT INTO `units` (`id`, `title`, `symbol`, `created_at`, `updated_at`) VALUES
	(1, 'عدد', 'PCS', '2026-06-01 17:43:22', '2026-06-01 17:43:22');

-- Dumping structure for table shopdb.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table shopdb.users: ~1 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'مدیر سیستم', 'edsanig@gmail.com', NULL, '$2y$12$YlbIO6wS/.UEqCMTE5pMTeit0cyBw0jgtsAZaCYpDx/YAHJ.PqBqC', NULL, '2026-06-01 17:05:55', '2026-06-01 17:05:55');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
