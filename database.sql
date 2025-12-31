-- پاک کردن دیتابیس و ایجاد مجدد آن برای اطمینان از یکپارچگی
DROP DATABASE IF EXISTS `simple_shop`;
CREATE DATABASE `simple_shop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `simple_shop`;

-- --------------------------------------------------------

--
-- ساختار جدول `users`
--
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- درج کاربر ادمین نمونه با هش جدید
-- رمز عبور: admin123
--
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Admin User', 'admin@gmail.com', '$2y$10$aevA79behFPkEA5/z9Q/Te2XTv73lm6wgwSm7labY4wOYLdUeMP9O', 'admin');


-- --------------------------------------------------------

--
-- ساختار جدول `products`
--
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- درج داده‌های نمونه برای محصولات
--
INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`) VALUES
(1, 'اسپیکر بلوتوثی', 'یک اسپیکر قابل حمل با صدای فوق‌العاده.', 1500000.00, '1.jpg'),
(2, 'هدفون بی‌سیم', 'هدفون با قابلیت حذف نویز و عمر باتری طولانی.', 2500000.00, '2.jpg'),
(3, 'ساب‌ووفر قدرتمند', 'بیس عمیق و قدرتمند برای سیستم صوتی خانگی شما.', 4000000.00, '3.jpg'),
(4, 'اسپیکر هوشمند', 'اسپیکر هوشمند با دستیار صوتی و کیفیت صدای عالی.', 3200000.00, '4.jpg');

-- --------------------------------------------------------

--
-- ساختار جدول `cart` (سبد خرید)
--
CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- ساختار جدول `orders` (سفارش‌ها)
--
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- ساختار جدول `order_items` (اقلام سفارش)
--
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- افزودن کلیدهای خارجی (Constraints)
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;
