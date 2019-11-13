SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `mrgoodbot`
--
CREATE DATABASE IF NOT EXISTS `mrgoodbot` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `mrgoodbot`;

-- --------------------------------------------------------

--
-- Table structure for table `botstate`
--

CREATE TABLE `botstate` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Speaking` tinyint(1) NOT NULL,
  `CustomData` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `botstate`
--

INSERT INTO `botstate` (`ID`, `Name`, `Speaking`, `CustomData`) VALUES
(1, 'Mr. Good Bot', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `statements`
--

CREATE TABLE `statements` (
  `ID` int(11) NOT NULL,
  `Bot` varchar(255) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT '0',
  `Statement` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `statements`
--

INSERT INTO `statements` (`ID`, `Bot`, `Status`, `Statement`) VALUES
(1, 'Mr. Good Bot', 0, 'Hello World!'),
(2, 'Mr. Good Bot', 0, 'Testing One, Two, Three.'),
(3, 'Mr. Good Bot', 0, 'If you can hear me then my vocal systems are operational.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `botstate`
--
ALTER TABLE `botstate`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `statements`
--
ALTER TABLE `statements`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `botstate`
--
ALTER TABLE `botstate`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `statements`
--
ALTER TABLE `statements`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;
