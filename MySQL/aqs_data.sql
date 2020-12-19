--
-- Database: `aqs_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `LogData`
--

CREATE TABLE IF NOT EXISTS `LogData` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log` varchar(255) NOT NULL,
  `reading_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `SensorData`
--

CREATE TABLE IF NOT EXISTS `SensorData` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `macaddress` varchar(12) NOT NULL,
  `sensor` varchar(10) NOT NULL,
  `feature` char(1) NOT NULL,
  `value` varchar(10) NOT NULL,
  `reading_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `SensorMAC`
--

CREATE TABLE IF NOT EXISTS `SensorMAC` (
  `macaddress` varchar(12) NOT NULL,
  `active_since` date NOT NULL,
  `active_until` date NOT NULL,
  `location` varchar(30) NOT NULL,
  `checked` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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


