-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 06:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `larosa-commerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `landmark_address` varchar(255) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `landmark_address`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 160.00, '2025-05-22 20:12:00', '2025-05-25 20:12:00'),
(2, 1, NULL, 540.00, '2025-05-23 23:15:17', '2025-05-23 23:15:17'),
(3, 1, NULL, 180.00, '2025-05-23 23:18:07', '2025-05-23 23:18:07'),
(4, 1, NULL, 160.00, '2025-05-23 23:25:15', '2025-05-23 23:25:15'),
(5, 2, NULL, 190.00, '2025-05-23 23:31:34', '2025-05-23 23:31:34');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `order_status` enum('Pending','Delivered','Cancelled','Processing') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `subtotal`, `order_status`) VALUES
(1, 1, 1, 1, 160.00, 160.00, 'Delivered'),
(2, 2, 1, 1, 160.00, 160.00, 'Cancelled'),
(3, 2, 7, 1, 190.00, 190.00, 'Cancelled'),
(4, 2, 8, 1, 190.00, 190.00, 'Cancelled'),
(5, 3, 2, 1, 180.00, 180.00, 'Delivered'),
(6, 4, 5, 1, 160.00, 160.00, 'Pending'),
(7, 5, 8, 1, 190.00, 190.00, 'Processing');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `slug`, `image_path`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'Classic Honey Matcha Latte', 'Smooth iced matcha with a touch of natural honey. Lightly sweet and refreshingly creamy.', 160.00, 'classic-honey-matcha-latte', 'uploads/Classic Honey Matcha Latte.jpg', 1, '2025-05-23 19:49:49', '2025-05-23 13:49:49'),
(2, 'Strawberry Matcha Latte', 'Vibrant matcha blended with fresh strawberry puree and creamy milk. Fruity and earthy in perfect harmony.', 180.00, 'strawberry-matcha-latte', 'uploads/Strawberry Matcha Latte.jpg', 1, '2025-05-23 19:50:13', '2025-05-23 13:50:13'),
(3, 'Honey Hojicha Matcha Latte', 'Roasted hojicha tea with honey and milk over ice. A nutty, caramel-like twist to your latte.', 170.00, 'honey-hojicha-matcha-latte', 'uploads/Honey Hojicha Matcha Latte.jpg', 1, '2025-05-23 19:50:49', '2025-05-23 13:50:49'),
(4, 'Classic Honey Matcha Tea', 'Fresh brewed matcha tea with sweet honey, served over ice. Light, bold, and naturally sweet.', 150.00, 'classic-honey-matcha-tea', 'uploads/Classic Honey Matcha Tea.jpg', 2, '2025-05-23 19:51:13', '2025-05-23 13:51:13'),
(5, 'Yuzu Matcha Tea', 'Citrusy yuzu meets green matcha in a tangy, energizing iced tea.', 160.00, 'yuzu-matcha-tea', 'uploads/Yuzu Matcha Tea.jpg', 2, '2025-05-23 19:51:34', '2025-05-23 13:51:34'),
(6, 'Lychee Matcha Tea', 'Floral lychee pairs beautifully with matcha for a lightly sweet, tropical iced tea.', 160.00, 'lychee-matcha-tea', 'uploads/Lychee Matcha Tea.jpg', 2, '2025-05-23 19:51:59', '2025-05-23 13:51:59'),
(7, 'Strawberry Matcha Float', 'Iced strawberry matcha topped with a scoop of vanilla ice cream. Creamy, fruity indulgence.', 190.00, 'strawberry-matcha-float', 'uploads/Strawberry Matcha Float.jpg', 3, '2025-05-23 19:52:32', '2025-05-23 13:52:32'),
(8, 'Lychee Matcha Float', 'Lychee-infused matcha over ice, finished with vanilla ice cream for a refreshing treat.', 190.00, 'lychee-matcha-float', 'uploads/Lychee Matcha Float.jpg', 3, '2025-05-23 19:52:53', '2025-05-23 13:52:53'),
(9, 'Hojicha Matcha Float', 'Roasted hojicha tea with a scoop of vanilla ice cream. Deep, nutty flavors meet sweet cream.', 180.00, 'hojicha-matcha-float', 'uploads/Hojicha Matcha Float.jpg', 3, '2025-05-23 19:53:19', '2025-05-23 13:53:19');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `category_name`, `created_at`, `updated_at`) VALUES
(1, 'Matcha Latte (Iced)', '2025-05-23 17:36:45', '2025-05-23 17:36:45'),
(2, 'Matcha Tea (Iced)', '2025-05-23 17:36:45', '2025-05-23 17:36:45'),
(3, 'Matcha Float', '2025-05-23 17:36:45', '2025-05-23 17:36:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('Admin','Customer') NOT NULL DEFAULT 'Customer',
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_profile_complete` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `address`, `phone`, `birthdate`, `created_at`, `updated_at`, `is_profile_complete`) VALUES
(1, 'Rodeliza La Rosa', 'rodeliza.2002@gmail.com', '$2y$10$lMYD7fjH29s3LeE2G6LEku6oy/wqj9mI8IqQITKPamrXip3bYTIku', 'Admin', 'Ward III, Minglanilla, Cebu', '09917940262', '2002-10-20', '2025-05-23 08:35:44', '2025-05-23 08:35:44', 1),
(2, 'Rode', 'rode@gmail.com', '$2y$10$0VQgQSE6pUOvGYBqiJf5t.FSAtDbfh23tlYb8M1PEWTFYURzn.VhC', 'Customer', 'MOLAVE', '09912222222', '2002-10-10', '2025-05-23 10:41:21', '2025-05-23 10:41:21', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
