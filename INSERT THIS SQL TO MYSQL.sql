-- phpMyAdmin SQL Dump
-- version 4.4.15
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 08, 2018 at 06:53 PM
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `LaikiuxHints`
--

INSERT INTO `LaikiuxHints` (`id`, `hinttext`) VALUES
(5, 'test hint'),
(6, 'test hint');

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
(1, 'Klausimas nurodytas. Jeigu atsakymas per kablelÄ¯, bÅ«tina jÄ¯ atskirti taÅ¡ku vietoje kablelio. Pvz (0.68)', '0.829', 'VIP 1day', '1', 'https://i.imgur.com/NpWWGt2.png', 'Tortonas');

-- --------------------------------------------------------

--
-- Table structure for table `LaikiuxWinners`
--

CREATE TABLE IF NOT EXISTS `LaikiuxWinners` (
  `id` int(11) NOT NULL,
  `Nickname` varchar(1000) NOT NULL,
  `organizer` varchar(1000) NOT NULL,
  `winnerIp` varchar(128) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `LaikiuxWinners`
--

INSERT INTO `LaikiuxWinners` (`id`, `Nickname`, `organizer`, `winnerIp`) VALUES
(34, 'Braskes Vaikas VIP 1day', 'Tortonas', ''),
(35, 'In*Victus VIP 1day', 'Tortonas', ''),
(36, 'Wez VIP 1day', 'Tortonas', ''),
(37, 'Negras su raudonu bemwu VIP 1day', 'Tortonas', '');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `LaikiuxSubmission`
--
ALTER TABLE `LaikiuxSubmission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `LaikiuxWinners`
--
ALTER TABLE `LaikiuxWinners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=46;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
