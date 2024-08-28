-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2024 at 09:14 PM
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
-- Database: `cue_book`
--

-- --------------------------------------------------------

--
-- Table structure for table `club_user`
--

CREATE TABLE `club_user` (
  `club_id` int(11) NOT NULL,
  `clubname` varchar(255) NOT NULL,
  `club_fullname` varchar(255) NOT NULL,
  `club_email` varchar(255) NOT NULL,
  `club_mobile` varchar(255) NOT NULL,
  `club_password` varchar(255) NOT NULL,
  `club_cpassword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `club_user`
--

INSERT INTO `club_user` (`club_id`, `clubname`, `club_fullname`, `club_email`, `club_mobile`, `club_password`, `club_cpassword`) VALUES
(2, 'WB-tech', 'Uzair', 'abc@gmail.com', '03132367428', 'test', 'test');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `club_user`
--
ALTER TABLE `club_user`
  ADD PRIMARY KEY (`club_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `club_user`
--
ALTER TABLE `club_user`
  MODIFY `club_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
