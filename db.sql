-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.25-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.4.0.6659
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for datnan
CREATE DATABASE IF NOT EXISTS `datnan` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `datnan`;

-- Dumping structure for table datnan.brands
CREATE TABLE IF NOT EXISTS `brands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_name_unique` (`name`),
  KEY `brands_user_id_foreign` (`user_id`),
  CONSTRAINT `brands_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.brands: ~0 rows (approximately)
REPLACE INTO `brands` (`id`, `name`, `image`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 'Smile', 'storage/image/product/20230419011254.jpg', 3, '2023-04-18 18:12:54', '2023-04-18 18:12:54');

-- Dumping structure for table datnan.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  KEY `categories_user_id_foreign` (`user_id`),
  CONSTRAINT `categories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.categories: ~0 rows (approximately)
REPLACE INTO `categories` (`id`, `name`, `image`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 'Niềng răng', 'storage/image/product/20230419011436.png', 3, '2023-04-18 18:14:37', '2023-04-18 18:14:37');

-- Dumping structure for table datnan.comments
CREATE TABLE IF NOT EXISTS `comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_user_id_foreign` (`user_id`),
  KEY `comments_product_id_foreign` (`product_id`),
  CONSTRAINT `comments_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.comments: ~0 rows (approximately)
REPLACE INTO `comments` (`id`, `user_id`, `product_id`, `description`, `created_at`, `updated_at`) VALUES
	(1, 2, 1, 'nam mo a di da lat', '2023-04-18 19:05:08', '2023-04-18 19:05:08');

-- Dumping structure for table datnan.details_invoice_export
CREATE TABLE IF NOT EXISTS `details_invoice_export` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_export_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `into_money` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `details_invoice_export_invoice_export_id_foreign` (`invoice_export_id`),
  KEY `details_invoice_export_product_id_foreign` (`product_id`),
  CONSTRAINT `details_invoice_export_invoice_export_id_foreign` FOREIGN KEY (`invoice_export_id`) REFERENCES `invoice_export` (`id`),
  CONSTRAINT `details_invoice_export_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.details_invoice_export: ~4 rows (approximately)
REPLACE INTO `details_invoice_export` (`id`, `invoice_export_id`, `product_id`, `quantity`, `into_money`, `created_at`, `updated_at`) VALUES
	(1, 2, 1, 1, 5000000, '2023-04-18 21:43:34', '2023-04-18 21:43:34'),
	(2, 3, 1, 1, 5000000, '2023-04-18 21:44:35', '2023-04-18 21:44:35'),
	(3, 4, 1, 1, 5000000, '2023-04-18 21:45:41', '2023-04-18 21:45:41'),
	(4, 5, 1, 1, 5000000, '2023-04-19 00:17:44', '2023-04-19 00:17:44');

-- Dumping structure for table datnan.details_invoice_import
CREATE TABLE IF NOT EXISTS `details_invoice_import` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_import_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `into_money` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `details_invoice_import_invoice_import_id_foreign` (`invoice_import_id`),
  KEY `details_invoice_import_product_id_foreign` (`product_id`),
  CONSTRAINT `details_invoice_import_invoice_import_id_foreign` FOREIGN KEY (`invoice_import_id`) REFERENCES `invoice_import` (`id`),
  CONSTRAINT `details_invoice_import_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.details_invoice_import: ~0 rows (approximately)
REPLACE INTO `details_invoice_import` (`id`, `invoice_import_id`, `product_id`, `quantity`, `price`, `into_money`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 10, 4500000, 45000000, '2023-04-18 18:18:03', '2023-04-18 18:18:03');

-- Dumping structure for table datnan.details_product
CREATE TABLE IF NOT EXISTS `details_product` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `details_product_product_id_foreign` (`product_id`),
  CONSTRAINT `details_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.details_product: ~2 rows (approximately)
REPLACE INTO `details_product` (`id`, `product_id`, `image`, `created_at`, `updated_at`) VALUES
	(1, 1, 'storage/image/product/202304190116300Sw97LpEyP.png', '2023-04-18 18:16:30', '2023-04-18 18:16:30'),
	(2, 1, 'storage/image/product/20230419011631SkZm7qybjY.jpg', '2023-04-18 18:16:31', '2023-04-18 18:16:31'),
	(3, 1, 'storage/image/product/20230419011632dL36OozFYX.jpg', '2023-04-18 18:16:32', '2023-04-18 18:16:32');

-- Dumping structure for table datnan.doctor
CREATE TABLE IF NOT EXISTS `doctor` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_id` bigint(20) unsigned NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `doctor_level_id_foreign` (`level_id`),
  CONSTRAINT `doctor_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `level` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.doctor: ~2 rows (approximately)
REPLACE INTO `doctor` (`id`, `image`, `name`, `level_id`, `active`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'storage/image/product/20230417043645.png', 'first title', 2, 0, 'a', '2023-04-16 21:36:45', '2023-04-16 23:40:04'),
	(2, 'storage/image/product/20230419014111.jpg', 'Vũ Thị Hải', 4, 1, 'Bác sĩ Hải có 20 năm kinh nghiệm làm việc với chuyên môn nha khoa', '2023-04-16 23:40:36', '2023-04-18 18:41:11');

-- Dumping structure for table datnan.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table datnan.invoice_export
CREATE TABLE IF NOT EXISTS `invoice_export` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code_invoice` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `into_money` int(11) NOT NULL DEFAULT 0,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `admin_id` bigint(20) unsigned DEFAULT NULL,
  `status_ship` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_pay_cod` tinyint(1) NOT NULL DEFAULT 0,
  `is_payment` tinyint(1) NOT NULL DEFAULT 0,
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `need_pay` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_export_code_invoice_unique` (`code_invoice`),
  KEY `invoice_export_user_id_foreign` (`user_id`),
  KEY `invoice_export_admin_id_foreign` (`admin_id`),
  CONSTRAINT `invoice_export_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`),
  CONSTRAINT `invoice_export_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.invoice_export: ~4 rows (approximately)
REPLACE INTO `invoice_export` (`id`, `code_invoice`, `into_money`, `user_id`, `admin_id`, `status_ship`, `is_pay_cod`, `is_payment`, `message`, `email_user`, `phone_user`, `name_user`, `address`, `need_pay`, `created_at`, `updated_at`) VALUES
	(2, 'aV7M9JmG5G', 5000000, 2, NULL, 'Đã tiếp nhận đơn hàng', 1, 0, NULL, 'user@gmail.com', '345', '9V36qTC41N', 'a', 5030000, '2023-04-18 21:43:34', '2023-04-18 21:43:34'),
	(3, 'xdeBpVuKll', 5000000, 2, NULL, 'Đã tiếp nhận đơn hàng', 1, 0, NULL, 'user@gmail.com', '345', '9V36qTC41N', 'a', 5030000, '2023-04-18 21:44:35', '2023-04-18 21:44:35'),
	(4, 'ZCnUjfeNPG', 5000000, 2, NULL, 'Đã tiếp nhận đơn hàng', 1, 0, NULL, 'user@gmail.com', '345', '9V36qTC41N', 'a', 5030000, '2023-04-18 21:45:41', '2023-04-18 21:45:41'),
	(5, 'vXNzC9QOwC', 5000000, NULL, NULL, 'Đã tiếp nhận đơn hàng', 0, 1, NULL, 'abc@gmail.com', '1', 'a', 'a', 0, '2023-04-19 00:17:43', '2023-04-19 00:17:43');

-- Dumping structure for table datnan.invoice_import
CREATE TABLE IF NOT EXISTS `invoice_import` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `into_money` int(11) NOT NULL DEFAULT 0,
  `user_id` bigint(20) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_import_user_id_foreign` (`user_id`),
  CONSTRAINT `invoice_import_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.invoice_import: ~0 rows (approximately)
REPLACE INTO `invoice_import` (`id`, `into_money`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
	(1, 45000000, 3, 1, '2023-04-18 18:18:03', '2023-04-18 18:18:26');

-- Dumping structure for table datnan.level
CREATE TABLE IF NOT EXISTS `level` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.level: ~6 rows (approximately)
REPLACE INTO `level` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'Cử nhân', NULL, NULL),
	(2, 'Thạc sĩ', NULL, NULL),
	(3, 'Tiến sĩ', NULL, NULL),
	(4, 'Trưởng khoa', NULL, NULL),
	(5, 'Phó giáo sư', NULL, NULL),
	(6, 'Giáo sư', NULL, NULL);

-- Dumping structure for table datnan.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.migrations: ~17 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_100000_create_password_resets_table', 1),
	(2, '2019_08_19_000000_create_failed_jobs_table', 1),
	(3, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(4, '2021_04_05_134525_create_flights_roles', 1),
	(5, '2021_10_12_000000_create_users_table', 1),
	(6, '2022_04_20_225019_create_brands_table', 1),
	(7, '2022_04_20_225021_create_categories_table', 1),
	(8, '2022_04_28_210925_create_products_table', 1),
	(9, '2022_04_28_210926_create_invoice_import_table', 1),
	(10, '2022_04_28_210927_create_details_invoice_import_table', 1),
	(11, '2022_04_28_210929_create_invoice_export_table', 1),
	(12, '2022_04_28_210930_create_details_invoice_export_table', 1),
	(13, '2022_04_28_210931_create_comments_table', 1),
	(14, '2022_04_28_210932_create_details_product_table', 1),
	(15, '2022_04_28_210933_create_sidebar_table', 1),
	(16, '2022_04_28_211032_create_level_table', 1),
	(17, '2022_04_28_211033_create_doctor_table', 1),
	(18, '2022_04_28_211034_create_reservation_table', 2);

-- Dumping structure for table datnan.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.password_resets: ~0 rows (approximately)

-- Dumping structure for table datnan.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.personal_access_tokens: ~8 rows (approximately)
REPLACE INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
	(1, 'App\\Models\\User', 3, 'authToken', 'cc1619ff2de08da4aa443fcecbcafd16175b977642282e73f070199c33c602b1', '["*"]', NULL, '2023-04-16 23:36:10', '2023-04-16 23:36:10'),
	(2, 'App\\Models\\User', 3, 'authToken', 'afb9939d793e0f1f028217928fd9e6632fb748e1dac28d07f38a62c3ac7ab526', '["*"]', NULL, '2023-04-18 01:02:48', '2023-04-18 01:02:48'),
	(3, 'App\\Models\\User', 1, 'authToken', '160b9e214cdfe6a2a22442d6c69f0d8ada1fdb817ffe79b1e47d56d203212e6a', '["*"]', NULL, '2023-04-18 18:05:35', '2023-04-18 18:05:35'),
	(4, 'App\\Models\\User', 3, 'authToken', '9d76e85ad62a7aa09cd86b0b10d89af5bd6474c143c7095ca7ae47b87a3783fd', '["*"]', NULL, '2023-04-18 18:06:21', '2023-04-18 18:06:21'),
	(5, 'App\\Models\\User', 3, 'authToken', '833d30c43c8c6ef4771071ce4a209923f2d7841a74d2895ef361030de0ca8734', '["*"]', NULL, '2023-04-18 18:21:59', '2023-04-18 18:21:59'),
	(6, 'App\\Models\\User', 2, 'authToken', '5e08c1d8e7db6547415df5e2b20fd7aced377316041395a6f52eaac5f610eeaf', '["*"]', NULL, '2023-04-18 18:41:43', '2023-04-18 18:41:43'),
	(7, 'App\\Models\\User', 3, 'authToken', 'f6d8c928b72915fe7d08805cd38b4a826ae15b180634f0b3183835f4971bf059', '["*"]', NULL, '2023-04-18 18:45:09', '2023-04-18 18:45:09'),
	(8, 'App\\Models\\User', 2, 'authToken', 'c62d39b34722e4a53917822739827922aee59e0415bf8735648dd87e47721aeb', '["*"]', NULL, '2023-04-18 19:04:33', '2023-04-18 19:04:33'),
	(9, 'App\\Models\\User', 2, 'authToken', '459e432faf5ef90dca8bf6f542edeb3ba153b49f79f89e099da9e4a9f4e04800', '["*"]', NULL, '2023-04-19 01:12:01', '2023-04-19 01:12:01'),
	(10, 'App\\Models\\User', 3, 'authToken', 'd71bb9473bc6f7a250e8b6db89d20555994fda3962379464aff25f54a81ff98d', '["*"]', NULL, '2023-04-24 19:05:10', '2023-04-24 19:05:10');

-- Dumping structure for table datnan.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `price` int(11) NOT NULL,
  `price_down` int(11) NOT NULL,
  `start_promotion` date NOT NULL,
  `end_promotion` date NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_brand_id_foreign` (`brand_id`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_user_id_foreign` (`user_id`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.products: ~0 rows (approximately)
REPLACE INTO `products` (`id`, `name`, `brand_id`, `category_id`, `user_id`, `price`, `price_down`, `start_promotion`, `end_promotion`, `quantity`, `image`, `short_description`, `active`, `is_deleted`, `created_at`, `updated_at`) VALUES
	(1, 'Niềng răng Clear aligner', 1, 1, 3, 5000000, 4800000, '2023-04-17', '2023-04-18', 10, 'storage/image/product/20230419011629.jpg', 'Niềng răng clear thế giới\r\nNâng tầm thẩm mỹ người dùng', 1, 0, '2023-04-18 18:16:29', '2023-04-18 18:48:15');

-- Dumping structure for table datnan.reservation
CREATE TABLE IF NOT EXISTS `reservation` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `reservation_doctor_id_foreign` (`doctor_id`),
  KEY `reservation_user_id_foreign` (`user_id`),
  CONSTRAINT `reservation_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`),
  CONSTRAINT `reservation_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.reservation: ~4 rows (approximately)
REPLACE INTO `reservation` (`id`, `doctor_id`, `user_id`, `date`, `time`, `message`, `status`, `created_at`, `updated_at`, `name`, `phone`) VALUES
	(1, 2, 1, '2023-04-18', '15:30:30', 'acv', 1, NULL, '2023-04-17 01:49:16', '', ''),
	(2, 2, NULL, '2023-04-25', '09:30:00', NULL, 1, '2023-04-24 18:44:48', '2023-04-24 18:44:48', 'ac', '000'),
	(3, 2, NULL, '2023-04-25', '10:00:00', NULL, 1, '2023-04-24 18:57:28', '2023-04-24 18:57:28', 'a', '000'),
	(4, 2, NULL, '2023-04-26', '18:30:00', 'aa', 1, '2023-04-24 19:03:54', '2023-04-24 19:03:54', 'ad', '000');

-- Dumping structure for table datnan.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.roles: ~3 rows (approximately)
REPLACE INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'admin', NULL, NULL),
	(2, 'user', NULL, NULL),
	(3, 'manager', NULL, NULL);

-- Dumping structure for table datnan.sidebar
CREATE TABLE IF NOT EXISTS `sidebar` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.sidebar: ~0 rows (approximately)

-- Dumping structure for table datnan.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Mo252gGceGgpJFFq6xrGA0CjqJo40lAd99M',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table datnan.users: ~3 rows (approximately)
REPLACE INTO `users` (`id`, `name`, `email`, `email_verified_at`, `username`, `password`, `phone`, `address`, `role_id`, `is_deleted`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'kRU93m6qj2', 'admin@gmail.com', NULL, 'admin', '$2y$10$6HxbSverttZL3CXMhe6bbOgI0NkToE1rtN.CMPonz292tY6kL8Jvi', '234', NULL, 1, 0, 'vu6OkYEP7Eq9I6VFsbwS628FKrFk3nMLFJRt48P2axaRQOZF7rYn0O6c4DUr', NULL, NULL),
	(2, '9V36qTC41N', 'user@gmail.com', NULL, 'user', '$2y$10$J83NAKcKUrPAh9nxnXNkAuad4SZzCYl01k9rskksNyJadCT8FhO3e', '345', NULL, 2, 0, 'PhQUAG4TcLBNUGieb7Kh0hUZlwZm91Esbj6JMdz5hxIcJOuN2hiLhMnVhQX1', NULL, NULL),
	(3, 'bDlPxYQLRS', 'manager@gmail.com', NULL, 'manager', '$2y$10$GdSTkXQPzdaXC5rfLWFTeuXJiUHgGiQ7ZA4MqXNgx.zKL3fj5JkNK', '345', NULL, 3, 0, 'RSNG2ZYDJ7QP1kzF3n640lz7UN8r9CIbmwggYjahGQgIl1Vqj83RUv7FsqXp', NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
