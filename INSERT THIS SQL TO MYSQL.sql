-- phpMyAdmin SQL Dump
-- version 4.4.15
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 11, 2018 at 04:58 PM
-- Server version: 5.5.27
-- PHP Version: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `galgaldas_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `LaikiuxAdmins`
--

CREATE TABLE IF NOT EXISTS `LaikiuxAdmins` (
  `id` int(11) NOT NULL,
  `ip` varchar(128) NOT NULL,
  `nickname` varchar(128) NOT NULL,
  `level` varchar(128) NOT NULL,
  `steamid` varchar(1000) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `LaikiuxAdmins`
--

INSERT INTO `LaikiuxAdmins` (`id`, `ip`, `nickname`, `level`, `steamid`) VALUES
(1, 'admin ip', 'Tortonas', '2', '76561198044384206'),
(2, 'admin ip', 'In*Victus', '1', '76561198086184454');

-- --------------------------------------------------------

--
-- Table structure for table `LaikiuxHints`
--

CREATE TABLE IF NOT EXISTS `LaikiuxHints` (
  `id` int(11) NOT NULL,
  `hinttext` varchar(1000) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `LaikiuxSubmission`
--

CREATE TABLE IF NOT EXISTS `LaikiuxSubmission` (
  `id` int(11) NOT NULL,
  `question` varchar(1000) NOT NULL,
  `answer` varchar(1000) NOT NULL,
  `prize` varchar(1000) NOT NULL,
  `HasAnyoneWon` varchar(1) NOT NULL,
  `photolink` varchar(1000) NOT NULL,
  `organizer` varchar(1000) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `LaikiuxSubmission`
--

INSERT INTO `LaikiuxSubmission` (`id`, `question`, `answer`, `prize`, `HasAnyoneWon`, `photolink`, `organizer`) VALUES
(1, 'Kokioje Å¡alyje yra Å¡i pilis? Ats raÅ¡yti lietuviÅ¡kai', 'rumunija', '1000', '0', 'https://i.imgur.com/nfwP1Yg.jpg', 'Tortonas');

-- --------------------------------------------------------

--
-- Table structure for table `LaikiuxWinners`
--

CREATE TABLE IF NOT EXISTS `LaikiuxWinners` (
  `id` int(11) NOT NULL,
  `Nickname` varchar(1000) NOT NULL,
  `organizer` varchar(1000) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `LaikiuxWinners`
--

INSERT INTO `LaikiuxWinners` (`id`, `Nickname`, `organizer`) VALUES
(1, 'Re1den 1500 kreditai', ''),
(11, 'DvitaÅ¡kis 1500 kreditai', ''),
(12, 'DvitaÅ¡kis 1500 kreditai', ''),
(14, 'Markas 1000 kreditai', ''),
(16, 'zzz 1500 kreditai (sponsored)', ''),
(17, 'XxX_DAnkmemer_xXx 2000 kreditai (sponsored)', ''),
(18, 'Kappar 1500 kreditai', ''),
(19, 'Kappar 1500 kreditai (sponsored)', ''),
(20, 'DvitaÅ¡kis 2000 kreditai (sponsored)', ''),
(21, 'DvitaÅ¡kis 1500 kreditai (sponsored)', ''),
(22, 'Kappar 1500 kreditai (sponsored)', ''),
(23, 'DvitaÅ¡kis 1500 kreditai', ''),
(24, 'Re1Den 1000 kreditai', ''),
(25, 'Markas 1500 kreditai', ''),
(27, 'Slapyvardis 1500 kreditai', ''),
(28, 'XxX_DAnkmemer_xXx 1000 kreditai', ''),
(31, 'suva 1000 kreditai', 'Tortonas'),
(32, 'XxX_DAnkmemer_xXx 1500 kreditai', 'In*Victus'),
(33, 'suva 1500 kreditai', 'Tortonas');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `LaikiuxAdmins`
--
ALTER TABLE `LaikiuxAdmins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `LaikiuxHints`
--
ALTER TABLE `LaikiuxHints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `LaikiuxSubmission`
--
ALTER TABLE `LaikiuxSubmission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `LaikiuxWinners`
--
ALTER TABLE `LaikiuxWinners`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `LaikiuxAdmins`
--
ALTER TABLE `LaikiuxAdmins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `LaikiuxHints`
--
ALTER TABLE `LaikiuxHints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `LaikiuxSubmission`
--
ALTER TABLE `LaikiuxSubmission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `LaikiuxWinners`
--
ALTER TABLE `LaikiuxWinners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
