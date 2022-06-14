-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2021 at 07:23 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tokoku`
--

-- --------------------------------------------------------

--
-- Table structure for table `measure`
--

CREATE TABLE `measure` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `measure`
--

INSERT INTO `measure` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'buah', '2021-03-29 08:48:24', '2021-03-29 08:48:24'),
(2, 'meter', '2021-03-29 08:48:24', '2021-03-29 08:48:24');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2019_03_14_074237_create_measure_table', 1),
('2019_03_14_074237_create_product_table', 1),
('2019_03_14_074237_create_transaction_table', 1),
('2019_03_14_074247_create_foreign_keys', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `measure_id` int(10) UNSIGNED NOT NULL,
  `price` bigint(20) NOT NULL,
  `warn_stock` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `code`, `measure_id`, `price`, `warn_stock`, `created_at`, `updated_at`) VALUES
(1, 'Kabel Hitam', 'KBL001', 2, 20000, 20, '2021-03-29 08:58:38', '2021-03-29 08:58:38'),
(2, 'Kabel Putih', 'KBL002', 2, 30000, 20, '2021-05-06 09:17:37', '2021-05-06 09:17:37'),
(4, 'Kabel Biru Tua', 'KBL004', 2, 30000, 30, '2021-05-17 09:51:12', '2021-05-17 09:51:12'),
(6, 'Kabel Biru Muda', 'KBL005', 2, 20000, 20, '2021-05-31 08:20:22', '2021-05-31 08:20:22'),
(7, 'Lampu LED 12 W', 'LMP001', 2, 20000, 20, '2021-05-31 08:29:15', '2021-05-31 08:29:15'),
(15, 'Lampu Phillips LED 20W', 'LMP004', 1, 50000, 10, '2021-06-06 23:35:28', '2021-06-06 23:35:28'),
(16, 'Kabel Transparan', 'KBL099', 2, 200000, 10, '2021-06-06 23:38:38', '2021-06-06 23:38:38');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `price` bigint(20) NOT NULL,
  `qty` int(11) NOT NULL,
  `type` enum('S','B','SO') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `product_id`, `date`, `price`, `qty`, `type`, `created_at`, `updated_at`) VALUES
(1, 1, '2021-03-23', 0, 100, 'SO', '2021-03-29 09:37:23', '2021-03-29 09:37:23'),
(6, 2, '2021-05-14', 10000, 30, 'B', NULL, NULL),
(7, 1, '2021-05-14', 15000, 20, 'B', NULL, NULL),
(8, 2, '2021-05-26', 0, 100, 'SO', '2021-05-26 09:27:40', '2021-05-26 09:27:40'),
(11, 1, '2021-05-31', 20000, -8, 'S', '2021-05-31 00:26:24', '2021-05-31 00:26:24'),
(12, 1, '2021-05-31', 20000, 10, 'B', '2021-05-31 00:35:17', '2021-05-31 00:35:17'),
(13, 4, '2021-05-31', 0, 69, 'SO', '2021-05-31 08:21:49', '2021-05-31 08:21:49'),
(15, 1, '2021-05-26', 20000, -20, 'S', NULL, NULL),
(16, 1, '2021-06-02', 20000, -20, 'S', NULL, NULL),
(17, 6, '2021-06-02', 0, 50, 'SO', '2021-06-02 03:23:46', '2021-06-02 03:23:46'),
(18, 6, '2021-06-02', 15000, 20, 'B', NULL, NULL),
(20, 6, '2021-06-02', 20000, -5, 'S', NULL, NULL),
(21, 1, '2021-06-07', 20000, -12, 'S', '2021-06-06 23:24:55', '2021-06-06 23:24:55'),
(22, 1, '2021-06-07', 20000, -5, 'S', '2021-06-07 09:07:11', '2021-06-07 09:07:11'),
(23, 1, '2021-06-12', 20000, -10, 'S', NULL, NULL),
(24, 1, '2021-06-15', 20000, -12, 'S', NULL, NULL),
(25, 1, '2021-06-15', 14000, 12, 'B', NULL, NULL),
(30, 1, '2021-07-08', 10000, -50, 'S', NULL, NULL),
(31, 2, '2021-07-08', 35000, -50, 'S', NULL, NULL),
(32, 4, '2021-07-08', 20000, -100, 'S', NULL, NULL),
(33, 2, '2021-07-08', 10000, -10, 'S', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `blocked` enum('N','Y') COLLATE utf8_unicode_ci NOT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `blocked`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Hafizh Andifaisa', 'hafizhandifaisa@gmail.com', '$2y$10$wkkylYx52e35pAsUz97Xme.Nf/hxTSecgrI4V3H7L/00xEX0bKTuC', 'N', NULL, 's8sbfLhiSIuzgw54zbJcTQRKWn97YqzvO99bPdYIm3N8csVytxnPjKxWSBvg', '2021-03-29 08:48:24', '2021-07-07 18:22:57'),
(2, 'Admin', 'admin@admin.com', '$2y$10$b9cc8jhpfzgOom7.f/ac/.aL/.zb7ngMgIQ47pEBa22ZzTGfs6tfy', 'N', NULL, 'RlpWx15KxILibGjlHKSsvhBiBKaDNv4b4U8l2hfJQtlAq1RC0fCw57LTX7DB', '2021-03-29 08:48:24', '2021-06-06 08:54:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `measure`
--
ALTER TABLE `measure`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_code_unique` (`code`),
  ADD KEY `product_measure_id_foreign` (`measure_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_product_id_foreign` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `measure`
--
ALTER TABLE `measure`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_measure_id_foreign` FOREIGN KEY (`measure_id`) REFERENCES `measure` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
