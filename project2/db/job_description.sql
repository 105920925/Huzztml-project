-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 22, 2025 at 05:32 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jobs`
--

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_ref` varchar(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `closing_date` date NOT NULL,
  `position_type` enum('Full Time','Part Time','Contract') NOT NULL,
  `location` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_ref`, `title`, `description`, `closing_date`, `position_type`, `location`) VALUES
('JOB001', 'Web Developer', 'Develop and maintain company website.', '2025-06-30', 'Full Time', 'Melbourne'),
('JOB002', 'Graphic Designer', 'Design marketing materials and website assets.', '2025-07-15', 'Part Time', 'Sydney'),
('JOB003', 'Database Administrator', 'Maintain and manage SQL databases.', '2025-06-20', 'Contract', 'Brisbane'),
('JOB004', 'Software Engineer', 'Design, develop, and maintain software applications.', '2025-07-30', 'Full Time', 'Melbourne'),
('JOB005', 'Cybersecurity Analyst', 'Monitor and secure company IT infrastructure from cyber threats.', '2025-08-05', 'Full Time', 'Sydney'),
('JOB006', 'Product Manager', 'Lead the product development lifecycle and manage product teams.', '2025-06-25', 'Full Time', 'Brisbane'),
('JOB007', 'Marketing Specialist', 'Develop and execute marketing strategies to drive company growth.', '2025-08-10', 'Part Time', 'Melbourne'),
('JOB008', 'HR Coordinator', 'Oversee recruitment processes, manage employee relations and performance.', '2025-07-25', 'Contract', 'Sydney'),
('JOB009', 'UI/UX Designer', 'Design user-friendly and visually appealing web and mobile applications.', '2025-09-01', 'Full Time', 'Brisbane'),
('JOB010', 'Sales Manager', 'Develop sales strategies, build relationships with clients, and close deals.', '2025-07-20', 'Full Time', 'Melbourne');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_ref`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
