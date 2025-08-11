-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2025 at 08:51 AM
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
-- Database: `quang_trong_hang_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `size`, `quantity`, `created_at`) VALUES
(7, 6, 7, 'L', 14, '2025-08-09 10:11:12'),
(8, 6, 4, 'M', 2, '2025-08-11 05:31:37');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `subject` varchar(190) NOT NULL,
  `message` text NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `ip`, `created_at`) VALUES
(1, 'Hong Quang', 'quang1@gmail.com', 'Product', 'Hello 2', '::1', '2025-08-11 06:46:38');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,0) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `shipping_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `shipping_address`, `created_at`) VALUES
(4, 6, 1000000, 'pending', 'Hong Van, Ha Noi', '2025-08-09 10:04:54');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `size`, `quantity`, `price`) VALUES
(4, 4, 3, 'L', 1, 1000000);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `color` varchar(100) DEFAULT NULL,
  `material` varchar(100) DEFAULT NULL,
  `size` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `images` text DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `price`, `color`, `material`, `size`, `description`, `images`, `stock_quantity`, `created_at`) VALUES
(1, 'Manchester United Jersey 24/25 Short Sleeve Version', 800000, 'Red', 'Polyester', 'S,M,L,XL,2XL', 'Manchester United short sleeve jersey for the 24/25 season, modern design', 'assets/img/img8.avif', 100, '2025-08-05 14:41:40'),
(2, 'Manchester United Jersey 25/26 Long Sleeve Version', 1000000, 'Red', 'Polyester', 'S,M,L,XL,2XL', 'Manchester United long sleeve jersey for the 25/26 season, suitable for winter', 'assets/img/img2.webp', 30, '2025-08-05 14:41:40'),
(3, 'Manchester United Jersey 23/24 Short Sleeve Version', 1000000, 'Red', 'Polyester', 'S,M,L,XL,2XL', 'Manchester United jersey for the 23/24 season, classic design', 'assets/img/img12.jpg', 23, '2025-08-05 14:41:40'),
(4, 'Manchester United 2007/2008 season jersey', 5000000, 'Red', 'Polyester', 'S,M,L,XL,2XL', 'Manchester United jersey for the 2007/2008 season - legendary season', 'assets/img/img4.jpg', 10, '2025-08-05 14:41:40'),
(5, 'Manchester United Away Jersey 24/25 ', 1000000, 'White', 'Polyester', 'S,M,L,XL,2XL', 'Manchester United away jersey for the 24/25 season, elegant white color', 'assets/img/img6.webp', 40, '2025-08-05 14:41:40'),
(6, 'Manchester United Third Away Jersey 24/25', 1000000, 'Black', 'Polyester', 'S,M,L,XL,2XL', 'Manchester United third away jersey for the 24/25 season, stylish black', 'assets/img/img65.jpg', 35, '2025-08-05 14:41:40'),
(7, 'Manchester United Away Jersey 25/26', 1000000, 'White', 'Polyester', 'S,M,L,XL,2XL', 'Manchester United away jersey for the 25/26 season, latest design', 'assets/img/img10.avif', 45, '2025-08-05 14:41:40'),
(8, 'Manchester United Hoodie', 1000000, 'Red', 'Cotton', 'S,M,L,XL,2XL', 'Manchester United hoodie, soft and warm cotton material', 'assets/img/img11.avif', 60, '2025-08-05 14:41:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `address`, `created_at`) VALUES
(1, 'oppahuy', 'oppahuy1@gmail.com', '$2y$10$cDCNoaUJN/8LLZwPmT7yFOV9hTis/c3r7XGScJNH6dmExlEr9lyO.', 'oppahuy', '123345656', 'def', '2025-08-05 14:42:25'),
(2, 'meo simmy', 'meosimmy2@gmail.com', '$2y$10$J9DqWvLI5LKQ1RmQP8oa0.DQXRh6e4YJV7XXEfS.ugXN1KWbiejVq', 'Meo Simmy', '0123345656', 'Dinh Thon Street, Ha Noi', '2025-08-08 02:37:04'),
(3, 'sammy', 'sammy1@gmail.com', '$2y$10$K4geFzcJtDlgdtz2zXshEeWMo4f3XEd1G0oKfWqbVoNVfGbHlpsHy', 'Sammy Dao', '0123456789', 'Duyen Thai, Thuong Tin, Ha Noi', '2025-08-09 08:00:19'),
(6, 'Quang', 'quang1@gmail.com', '$2y$10$C00kZNeJRRt9DlF9ZKTp9.NXwi0VHXQHw8cucuRsMxkqqCU/KWXKW', 'Hong Quang', '0393991226', 'Hong Van, Ha Noi', '2025-08-09 09:54:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
