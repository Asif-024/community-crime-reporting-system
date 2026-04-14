-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2026 at 06:14 PM
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
-- Database: `crime_reporting`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `comment_text` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `parent_commentid` int(11) DEFAULT NULL,
  `report_id` int(11) DEFAULT NULL,
  `comment_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `comment_text`, `user_id`, `parent_commentid`, `report_id`, `comment_date`) VALUES
(3, 'I saw it', 12, NULL, 1, '2026-04-08 11:36:00');

-- --------------------------------------------------------

--
-- Table structure for table `crime_categories`
--

CREATE TABLE `crime_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crime_categories`
--

INSERT INTO `crime_categories` (`category_id`, `category_name`) VALUES
(1, 'theft'),
(2, 'robbery'),
(3, 'assult'),
(4, 'offence'),
(5, 'vandalism');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_id` int(11) NOT NULL,
  `district` varchar(20) DEFAULT NULL,
  `division` varchar(20) DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`location_id`, `district`, `division`, `area`) VALUES
(1, 'Dhaka', 'Dhaka', 'Uttara'),
(2, 'Dhaka', 'Dhaka', 'Sonirakra'),
(3, 'Dhaka', 'Dhaka', 'Zatrabari'),
(4, 'Dhaka', 'Dhaka', 'Mohammadpur'),
(5, 'Dhaka', 'Dhaka', 'Dhaka University'),
(6, 'Dhaka', 'Dhaka', 'Banani');

-- --------------------------------------------------------

--
-- Table structure for table `police`
--

CREATE TABLE `police` (
  `police_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `badge_no` varchar(50) DEFAULT NULL,
  `rank` varchar(50) DEFAULT NULL,
  `station_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `police`
--

INSERT INTO `police` (`police_id`, `user_id`, `badge_no`, `rank`, `station_id`) VALUES
(1, 13, '121212', 'DG', 1),
(2, 17, '2323', 'SP', 12);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `submission_date` datetime DEFAULT current_timestamp(),
  `report_status` enum('approved','pending','rejected') NOT NULL DEFAULT 'pending',
  `description` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `police_status` enum('investigating','verified','resolved','false') NOT NULL DEFAULT 'investigating',
  `visibility` enum('visible','hidden') DEFAULT 'hidden'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`report_id`, `submission_date`, `report_status`, `description`, `user_id`, `location_id`, `police_status`, `visibility`) VALUES
(1, '2026-03-14 07:04:12', 'approved', 'This guy stole my wallet', 10, 1, 'investigating', 'visible'),
(2, '2026-04-12 22:27:06', 'approved', 'yesterday around 12PM, a group of armed brats burged into my place and took all the stuff they could', 10, 1, 'investigating', 'visible'),
(9, '2026-04-13 16:08:28', 'pending', 'I was on my bike today. the police wronged with a 4k fine when I had everything right near the asad gate today', 11, 6, 'investigating', 'hidden'),
(10, '2026-04-13 16:39:34', 'pending', 'pickpocketed my phone near DRMC', 11, 4, 'investigating', 'hidden');

-- --------------------------------------------------------

--
-- Table structure for table `report_category`
--

CREATE TABLE `report_category` (
  `report_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_category`
--

INSERT INTO `report_category` (`report_id`, `category_id`) VALUES
(1, 1),
(1, 2),
(2, 2),
(2, 4),
(2, 5),
(10, 4);

-- --------------------------------------------------------

--
-- Table structure for table `report_credibility`
--

CREATE TABLE `report_credibility` (
  `credibility_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `credibility_value` enum('valid','invalid') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_credibility`
--

INSERT INTO `report_credibility` (`credibility_id`, `user_id`, `report_id`, `credibility_value`) VALUES
(1, 12, 1, 'valid'),
(6, 10, 1, 'valid'),
(14, 15, 2, 'invalid'),
(15, 16, 2, 'invalid'),
(16, 17, 2, 'valid');

-- --------------------------------------------------------

--
-- Table structure for table `report_flags`
--

CREATE TABLE `report_flags` (
  `flag_id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `flag_status` enum('pending','reviewed') NOT NULL DEFAULT 'pending',
  `flag_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_flags`
--

INSERT INTO `report_flags` (`flag_id`, `report_id`, `user_id`, `reason`, `flag_status`, `flag_date`) VALUES
(1, 1, 13, 'sus', 'pending', '2026-04-10 10:55:07'),
(2, 2, 13, 'Flase', 'pending', '2026-04-13 21:38:04'),
(3, 2, 17, 'false', 'reviewed', '2026-04-14 12:47:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `NID` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `verification_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `account_status` enum('active','banned') DEFAULT 'active',
  `role` enum('user','police','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `phone`, `NID`, `password`, `verification_status`, `account_status`, `role`) VALUES
(10, 'Adison', 'Silva', 'adison11@gmail.com', '99024345', '3112456', '$2y$10$Tc91Vllq3D5P1LlKBkXde.RNnRiOnZoAE4o7w9tP96/MYgUV6xjHC', 'approved', 'active', 'user'),
(11, 'Ray', '', 'ray12@gmail.com', '99034232', '2432255', '$2y$10$zvTKQc2paopYI7lPvTA9M.LfxZcmbF5/SMpvmbz4AtDcQf6E6BYGq', 'approved', 'active', 'user'),
(12, 'brenden', 'scaulb', 'brenden@yahoo.com', '+9610029', '233451', '$2y$10$aIZxYnWkEJrcvBXE5cq9J.TNmY1CxfO9VPkF24J/t/G1G7dcjxm1K', 'approved', 'active', 'user'),
(13, 'hector', 'ford', 'hector@admin.com', '+911', '23131', '$2y$10$3qc2qSSP.397kkRzkfWF1OlIwsJRIiXdyZVAOXJ3CWsWbBAuY0i7i', 'approved', 'active', 'police'),
(14, 'Admin', 'User', 'admin@gmail.com', '0123456789', '1234567890', '$2y$10$RH0IbLq5YGoDp7.iYXWofu7eXh8rNTeAcNn9CP6WmsWTc4THpfPi.', 'approved', 'active', 'admin'),
(15, 'Hamim', 'Haque', 'hamim1@gmail.com', '019348264223', '3427319', '$2y$10$CzPET1HOqaYVLx/WeHTZqufMPIEnd0h6Nqd/kv1qeFR7j1oHTvUnS', 'approved', 'active', 'user'),
(16, 'Andreson', 'Silva', 'andreson42@gmail.com', '+9921323', '12312312', '$2y$10$JBU7aamLlnQgxP9iSbC7luTCeA.SRUCyDI017BfH8AfwnlXE2mgIG', 'approved', 'active', 'user'),
(17, 'kasraf', 'hossain', 'kashraf12@gmail.com', '019239322323', '231313', '$2y$10$3.qPbQuvAn6X9IH9xu//V.j6J0J6Ee903MJz.y/qKuxM/sIQ2Rkh2', 'approved', 'active', 'police');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `report_id` (`report_id`),
  ADD KEY `parent_commentid` (`parent_commentid`);

--
-- Indexes for table `crime_categories`
--
ALTER TABLE `crime_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `police`
--
ALTER TABLE `police`
  ADD PRIMARY KEY (`police_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `badge_no` (`badge_no`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `report_category`
--
ALTER TABLE `report_category`
  ADD PRIMARY KEY (`report_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `report_credibility`
--
ALTER TABLE `report_credibility`
  ADD PRIMARY KEY (`credibility_id`),
  ADD UNIQUE KEY `report_id` (`report_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `report_flags`
--
ALTER TABLE `report_flags`
  ADD PRIMARY KEY (`flag_id`),
  ADD UNIQUE KEY `report_id` (`report_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD UNIQUE KEY `unique_phone` (`phone`),
  ADD UNIQUE KEY `unique_nid` (`NID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `crime_categories`
--
ALTER TABLE `crime_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `police`
--
ALTER TABLE `police`
  MODIFY `police_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `report_credibility`
--
ALTER TABLE `report_credibility`
  MODIFY `credibility_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `report_flags`
--
ALTER TABLE `report_flags`
  MODIFY `flag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`report_id`) REFERENCES `reports` (`report_id`),
  ADD CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`parent_commentid`) REFERENCES `comments` (`comment_id`);

--
-- Constraints for table `police`
--
ALTER TABLE `police`
  ADD CONSTRAINT `police_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`);

--
-- Constraints for table `report_category`
--
ALTER TABLE `report_category`
  ADD CONSTRAINT `fk_report_category_reports` FOREIGN KEY (`report_id`) REFERENCES `reports` (`report_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `crime_categories` (`category_id`);

--
-- Constraints for table `report_credibility`
--
ALTER TABLE `report_credibility`
  ADD CONSTRAINT `report_credibility_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `report_credibility_ibfk_2` FOREIGN KEY (`report_id`) REFERENCES `reports` (`report_id`);

--
-- Constraints for table `report_flags`
--
ALTER TABLE `report_flags`
  ADD CONSTRAINT `report_flags_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`report_id`),
  ADD CONSTRAINT `report_flags_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `report_flags_ibfk_3` FOREIGN KEY (`report_id`) REFERENCES `reports` (`report_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
