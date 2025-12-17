-- ایجاد دیتابیس اگر وجود نداشته باشد
CREATE DATABASE IF NOT EXISTS `simple_shop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- استفاده از دیتابیس
USE `simple_shop`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `products`
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
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`) VALUES
(1, 'اسپیکر بلوتوثی', 'یک اسپیکر قابل حمل با صدای فوق‌العاده.', 1500000.00, '1.jpg'),
(2, 'هدفون بی‌سیم', 'هدفون با قابلیت حذف نویز و عمر باتری طولانی.', 2500000.00, '2.jpg'),
(3, 'ساب‌ووفر قدرتمند', 'بیس عمیق و قدرتمند برای سیستم صوتی خانگی شما.', 4000000.00, '3.jpg'),
(4, 'اسپیکر هوشمند', 'اسپیکر هوشمند با دستیار صوتی و کیفیت صدای عالی.', 3200000.00, '4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
