-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 18, 2020 at 10:58 PM
-- Server version: 10.3.25-MariaDB-0+deb10u1
-- PHP Version: 7.3.19-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `esp_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `LogData`
--

CREATE TABLE `LogData` (
  `id` int(11) NOT NULL,
  `log` varchar(255) NOT NULL,
  `reading_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `SensorData`
--

CREATE TABLE `SensorData` (
  `id` int(11) NOT NULL,
  `macaddress` varchar(12) NOT NULL,
  `sensor` varchar(10) NOT NULL,
  `feature` char(1) NOT NULL,
  `value` varchar(10) NOT NULL,
  `reading_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `SensorMAC`
--

CREATE TABLE `SensorMAC` (
  `macaddress` varchar(12) NOT NULL,
  `active_since` date NOT NULL,
  `active_until` date NOT NULL,
  `location` varchar(30) NOT NULL,
  `checked` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for table `LogData`
--
ALTER TABLE `LogData`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `SensorData`
--
ALTER TABLE `SensorData`
  ADD PRIMARY KEY (`id`),
  ADD KEY `macaddress` (`macaddress`,`reading_time`),
  ADD KEY `macaddress_2` (`macaddress`,`feature`,`reading_time`);

--
-- Indexes for table `SensorMAC`
--
ALTER TABLE `SensorMAC`
  ADD PRIMARY KEY (`macaddress`,`active_since`),
  ADD KEY `active_since` (`active_since`,`active_until`);

--
-- AUTO_INCREMENT for table `LogData`
--
ALTER TABLE `LogData`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `SensorData`
--
ALTER TABLE `SensorData`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184428;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
