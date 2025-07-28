-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2025 at 02:44 AM
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
-- Database: `shoes_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id`, `full_name`, `username`, `password`) VALUES
(15, 'admin', 'admin', '81dc9bdb52d04dc20036dbd8313ed055');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `featured` varchar(10) NOT NULL,
  `active` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`id`, `title`, `image_name`, `featured`, `active`) VALUES
(16, 'first', 'Shoes_Category_103.png', 'Yes', 'Yes'),
(17, 'second', 'Shoes_Category_176.webp', 'Yes', 'Yes'),
(18, 'third', 'Shoes_Category_758.avif', 'Yes', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `id` int(10) UNSIGNED NOT NULL,
  `shoes` varchar(150) NOT NULL,
  `size` varchar(10) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `total` decimal(10,0) NOT NULL,
  `order_date` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  `customer_name` varchar(150) NOT NULL,
  `customer_contact` varchar(20) NOT NULL,
  `customer_email` varchar(150) NOT NULL,
  `customer_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_order`
--

INSERT INTO `tbl_order` (`id`, `shoes`, `size`, `price`, `qty`, `total`, `order_date`, `status`, `customer_name`, `customer_contact`, `customer_email`, `customer_address`) VALUES
(1, 'third', '', 200000.00, 2, 400000, '2025-05-28 21:31:34', 'Delivered', 'Abdulrahman Ahmed Abdo Rashed', '773130247', 'man2rashed@gmail.com', 'Taiz-Yemen'),
(2, 'new brand', '', 0.00, 3, 0, '2025-05-29 15:54:23', 'Delivered', 'adbul', '32534645746756', 'man2@gmail.com', 'Taiz-Yemen'),
(4, 'forth', '', 300000.00, 1, 300000, '2025-05-30 08:33:27', 'Delivered', 'salah babi', '32534645746', 'man2rashed@gmail.com', 'Taiz-Yemen'),
(5, 'third', '', 200000.00, 1, 200000, '2025-05-31 10:20:04', 'Delivered', 'Abdulrahman Ahmed Abdo Rashed', '32534645746756', 'man2rashed@gmail.com', 'Taiz-Yemen'),
(6, 'ayaya', '', 1000000.00, 2, 2000000, '2025-06-06 17:48:39', 'On Delivery', 'Abdulrahman Ahmed Abdo Rashed', '32534645746756', 'man2rashed@gmail.com', 'Taiz-Yemen'),
(8, 'FIRST', '40', 120000.00, 1, 120000, '2025-06-08 21:10:40', 'Ordered', 'Abdulrahman Ahmed Abdo Rashed', '32534645746756', 'man2rashed@gmail.com', 'Taiz-Yemen'),
(9, 'FIRST', '40', 120000.00, 1, 120000, '2025-06-08 21:12:50', 'Ordered', 'Abdulrahman Ahmed Abdo Rashed', '32534645746756', 'man2rashed@gmail.com', 'Taiz-Yemen'),
(10, 'forth', '36', 300000.00, 1, 300000, '2025-06-08 21:13:44', 'Ordered', 'Abdulrahman Ahmed Abdo Rashed', '32534645746756', 'man2rashed@gmail.com', 'Taiz-Yemen'),
(11, 'sixth', '35', 3400000.00, 1, 3400000, '2025-06-08 21:14:35', 'Ordered', 'Abdulrahman Ahmed Abdo Rashed', '32534645746756', 'man2rashed@gmail.com', 'Taiz-Yemen'),
(12, 'FIRST', '45', 120000.00, 1, 120000, '2025-06-08 22:16:23', 'Ordered', 'Abdulrahman Ahmed Abdo Rashed', '32534645746756', 'man2rashed@gmail.com', 'Taiz-Yemen'),
(13, 'forth', '30', 300000.00, 1, 300000, '2025-06-08 22:45:24', 'Delivered', 'Salah', '32534645746756', 'salah@gmail.com', 'Taiz-Yemen');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shoes`
--

CREATE TABLE `tbl_shoes` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `featured` varchar(10) NOT NULL,
  `active` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_shoes`
--

INSERT INTO `tbl_shoes` (`id`, `title`, `description`, `price`, `image_name`, `category_id`, `featured`, `active`) VALUES
(7, 'third', 'sldahadkfhsdkjhlkj', 200000.00, 'Shoes-Name-2298.avif', 11, 'Yes', 'Yes'),
(8, 'forth', 'dsadl;sahdlfsah', 300000.00, 'Shoes-Name-4556.jpg', 11, 'Yes', 'Yes'),
(9, 'fifth', 'djs;ldfhslklkdjflsjlas', 400000.00, 'Shoes-Name-3996.jpeg', 6, 'Yes', 'Yes'),
(10, 'sixth', 'fsl;dhfsljfhasldhfsldjhlhsdl', 3400000.00, 'Shoes-Name-792.webp', 7, 'Yes', 'Yes'),
(11, 'FIRST', 'JLDVVSLD;VSL', 120000.00, 'Shoes-Name-4863.webp', 7, 'Yes', 'Yes'),
(12, 'FIDFDF', 'JFL;VSJDVLDFKJL', 133000.00, 'Shoes-Name-6376.jpg', 6, 'Yes', 'Yes'),
(14, 'ayaya', 'wawwww', 1000000.00, 'Shoes-Name-543.jpg', 17, 'Yes', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `status`, `created_at`) VALUES
(3, 'omar', 'omar@gmial.com', '$2y$10$qHWvler0Dgwhq1AG4XD.WOyVbVT06CYo7w5QCTm6OTE.VYFdyf2ii', 'active', '2025-06-01 16:10:54'),
(4, 'har', 'har@gmail.com', '$2y$10$jYQpHmBcj/XQyH61CUHukudf3Dg2u0S4B1uGYRNwbX6KweyUPwD.m', 'active', '2025-06-01 16:12:41'),
(5, 'abod', 'man2rashed@gmail.com', '$2y$10$0VSXC0n.QbpYtOCQMlQipuxU92Xn4lD4hNSwn5z1jjrihKLPT2Hja', 'active', '2025-06-03 16:10:51'),
(6, 'Salah', 'salah@gmail.com', '$2y$10$tq/fPs7edd7GHdx5bm0JmOCwdKrJ8QilJVuQegdFyAqFeDSVzjt2i', 'active', '2025-06-08 15:44:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_shoes`
--
ALTER TABLE `tbl_shoes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_shoes`
--
ALTER TABLE `tbl_shoes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
