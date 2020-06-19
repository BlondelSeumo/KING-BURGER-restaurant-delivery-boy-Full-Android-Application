-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2020 at 05:37 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `activations`
--

CREATE TABLE `activations` (

  `id` int(11) AUTO_INCREMENT NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activations`
--

INSERT INTO `activations` (`id`, `user_id`, `code`, `completed`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'BmFTUQA57zF832HyXbiv30VexUQ1bAG8', 1, NULL, '2019-08-04 18:30:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `app_user`
--

CREATE TABLE `app_user` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `profile_pic` varchar(500) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `email` varchar(500) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `create_at` varchar(30) DEFAULT NULL,
  `mob_number` varchar(191) NOT NULL UNIQUE,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=>is_not_deleted,1=>is_deleted',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `phone` varchar(250) DEFAULT NULL,
  `message` varchar(5000) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fooddelivery_food_desc`
--

CREATE TABLE `fooddelivery_food_desc` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `ingredients_id` varchar(255) NOT NULL,
  `item_amt` varchar(50) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_qty` int(11) NOT NULL,
  `ItemTotalPrice` varchar(50) NOT NULL,
  `set_order_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `food_category`
--

CREATE TABLE `food_category` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `cat_icon` varchar(70) NOT NULL,
  `cat_name` varchar(70) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=>not_delete,1=>delete',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `food_city`
--

CREATE TABLE `food_city` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `city_name` varchar(70) NOT NULL,
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=>not_delete,1=>delete',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `food_delivery_boy`
--

CREATE TABLE `food_delivery_boy` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `action` int(11) NOT NULL,
  `profile` varchar(250) DEFAULT NULL,
  `attendance` varchar(10) DEFAULT 'No',
  `create_at` varchar(30) DEFAULT NULL,
  `email` varchar(50) NOT NULL UNIQUE,
  `mobile_no` varchar(15) NOT NULL,
  `name` varchar(30) NOT NULL,
  `password` varchar(250) NOT NULL,
  `vehicle_no` varchar(30) NOT NULL,
  `vehicle_type` varchar(30) NOT NULL,
  `is_deleted` enum('0','1') NOT NULL COMMENT '0=>not_delete,1=>delete',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `food_ingredients`
--

CREATE TABLE `food_ingredients` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `category` int(11) NOT NULL,
  `item_name` varchar(50) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `price` varchar(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1=>paid,0=>free',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=>not_delete,1=>delete',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `food_menu`
--

CREATE TABLE `food_menu` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `category` int(11) NOT NULL,
  `description` text NOT NULL,
  `menu_image` varchar(70) NOT NULL,
  `menu_name` varchar(70) NOT NULL,
  `price` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=>not_delete,1=>delete',
  `facebook_share` varchar(500) DEFAULT NULL,
  `twitter_share` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `food_notification`
--

CREATE TABLE `food_notification` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `android_key` varchar(255) NOT NULL,
  `ios_key` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `food_notification`
--

INSERT INTO `food_notification` (`id`, `android_key`, `ios_key`, `created_at`, `updated_at`) VALUES
(1, 'AAAAzfOzFf3:APA91bGDYxjaXRFePTRG0AnHW624nG7xaRzgtQWdTf-a_yGw3NZ9X0y8PoInegJUxxxFgTXta_VpbgqWw4yoHaOLsYR_u6xGvptgwYhiBuxqpx4s0XsLVn-AwQKo1wq8CX-Or6bNiIcJ', '1234546', '2019-08-30 16:47:57', '2019-12-15 18:14:43');

-- --------------------------------------------------------

--
-- Table structure for table `food_order_response`
--

CREATE TABLE `food_order_response` (
 `id` int(11) AUTO_INCREMENT NOT NULL,
  `desc` text NOT NULL,
  `order_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `food_send_notification`
--

CREATE TABLE `food_send_notification` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `message` varchar(5000) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `food_tokandata`
--

CREATE TABLE `food_tokandata` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `token` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `delivery_boyid` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `food_user`
--

CREATE TABLE `food_user` (
  `userid` int(11) AUTO_INCREMENT NOT NULL,
  `currency` varchar(30) NOT NULL,
  `email` varchar(70) NOT NULL,
  `password` varchar(30) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `food_user`
--

INSERT INTO `food_user` (`userid`, `currency`, `email`, `password`, `user_name`) VALUES
(1, 'BRL - R$', 'admin@gmail.com', '123', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_07_02_230147_migration_cartalyst_sentinel', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE `payment_method` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_method` varchar(250) DEFAULT NULL,
  `charges` varchar(250) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `persistences`
--

CREATE TABLE `persistences` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reset_password`
--

CREATE TABLE `reset_password` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_users`
--

CREATE TABLE `role_users` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `stripe_key` varchar(250) DEFAULT NULL,
  `stripe_secret` varchar(250) DEFAULT NULL,
  `paypal_client_id` varchar(250) DEFAULT NULL,
  `paypal_client_secret` varchar(250) DEFAULT NULL,
  `paypal_mode` enum('1','0') NOT NULL DEFAULT '0' COMMENT '0=>sandbox,1=>live',
  `order_status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=>offline,1=>online',
  `app_store_url` varchar(250) DEFAULT NULL,
  `play_store_url` varchar(250) DEFAULT NULL,
  `delivery_charges` varchar(250) DEFAULT NULL,
  `facebook_id` varchar(250) DEFAULT NULL,
  `twitter_id` varchar(250) DEFAULT NULL,
  `linkedin_id` varchar(250) DEFAULT NULL,
  `google_plus_id` varchar(250) DEFAULT NULL,
  `whatsapp` varchar(250) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `phone` varchar(250) DEFAULT NULL,
  `timezone`  varchar(250) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `stripe_key`, `stripe_secret`, `paypal_client_id`, `paypal_client_secret`, `paypal_mode`, `order_status`, `app_store_url`, `play_store_url`, `delivery_charges`, `facebook_id`, `twitter_id`, `linkedin_id`, `google_plus_id`, `whatsapp`, `address`, `email`, `phone`,`timezone`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, NULL, NULL, '0', '1', NULL, NULL, '15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,'2019-09-09 16:10:55', '2019-11-25 11:44:52');

-- --------------------------------------------------------

--
-- Table structure for table `set_order_detail`
--

CREATE TABLE `set_order_detail` (
 `id` int(11) AUTO_INCREMENT NOT NULL,
  `assign_date_time` varchar(30) DEFAULT NULL,
  `assign_status` varchar(225) DEFAULT NULL,
  `delivered_date_time` varchar(30) DEFAULT NULL,
  `delivered_status` varchar(11) DEFAULT NULL,
  `dispatched_date_time` varchar(30) DEFAULT NULL,
  `dispatched_status` varchar(11) DEFAULT NULL,
  `is_assigned` varchar(11) DEFAULT NULL,
  `order_placed_date` varchar(30) DEFAULT NULL,
  `cancel_date_time` varchar(50) DEFAULT NULL,
  `order_status` int(11) DEFAULT NULL,
  `preparing_date_time` varchar(30) DEFAULT NULL,
  `preparing_status` varchar(225) DEFAULT NULL,
  `total_price` varchar(225) DEFAULT NULL,
  `latlong` varchar(155) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `phone_no` varchar(250) DEFAULT NULL,
  `name` varchar(225) DEFAULT NULL,
  `address` varchar(225) DEFAULT NULL,
  `email` varchar(225) DEFAULT NULL,
  `payment_type` varchar(225) DEFAULT NULL,
  `notes` varchar(225) DEFAULT NULL,
  `city` varchar(225) DEFAULT NULL,
  `notify` int(11) DEFAULT NULL,
  `shipping_type` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1=>home delivery,2=>local pickup',
  `subtotal` double DEFAULT NULL,
  `delivery_charges` varchar(225) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `charges_id` varchar(250) DEFAULT NULL,
  `pay_pal_paymentId` varchar(250) DEFAULT NULL,
  `pay_pal_token` varchar(250) DEFAULT NULL,
  `pay_pal_PayerID` varchar(250) DEFAULT NULL,
  `delivery_mode` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=>home delivery,1=>pickup',
  `pickup_order_time` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `throttle`
--

CREATE TABLE `throttle` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_demo` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0=>demo,1=>live',
  `profile_pic` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mobile_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2',
  `currency` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `is_demo`, `profile_pic`, `password`, `permissions`, `last_login`, `name`, `created_at`, `updated_at`, `mobile_number`, `user_type`, `currency`, `user_name`) VALUES
(1, 'admin@gmail.com', '1', 'Dj1K4BQpDO1576473965.png', '$2y$10$1oqNKAooPEYGmM7yAWrqkekRj3HQCP3m3xS0Opi4UcjmvH5ef95GG', NULL, '2020-02-24 11:25:12', 'Admin', NULL, '2020-02-24 11:25:12', '8980083337', '1', 'BRL - R$', 'Admin');




ALTER TABLE `throttle`
  ADD KEY `throttle_user_id_index` (`user_id`);



--