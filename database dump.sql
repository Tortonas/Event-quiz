-- phpMyAdmin SQL Dump
-- version 4.4.15
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 20, 2019 at 06:43 PM
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `LaikiuxAdmins`
--

INSERT INTO `LaikiuxAdmins` (`id`, `ip`, `nickname`, `level`, `steamid`) VALUES
(1, 'admin ip', 'Tortonas', '2', '76561198044384206'),
(2, 'admin ip', 'In*Victus', '1', '76561198086184454'),
(3, 'admin ip', 'Sparta*', '1', '76561198257515603'),
(4, 'admin ip', 'Haxters', '1', '76561198200040894');

-- --------------------------------------------------------

--
-- Table structure for table `LaikiuxCredits`
--

CREATE TABLE IF NOT EXISTS `LaikiuxCredits` (
  `id` int(11) NOT NULL,
  `ip` varchar(128) NOT NULL,
  `credits` int(11) NOT NULL,
  `nick` varchar(1000) NOT NULL,
  `bannedTill` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `LaikiuxCredits`
--

INSERT INTO `LaikiuxCredits` (`id`, `ip`, `credits`, `nick`, `bannedTill`) VALUES
(3, 'player ip', 0, '', '0000-00-00 00:00:00'),
(37, 'player ip', 0, 'per lengva ', '2019-01-19 19:55:28'),
(38, 'player ip', 1, 'jokiro', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `LaikiuxHints`
--

CREATE TABLE IF NOT EXISTS `LaikiuxHints` (
  `id` int(11) NOT NULL,
  `hinttext` varchar(1000) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `LaikiuxQuestions`
--

CREATE TABLE IF NOT EXISTS `LaikiuxQuestions` (
  `id` int(11) NOT NULL,
  `question` varchar(1000) NOT NULL,
  `answer` varchar(1000) NOT NULL,
  `prize` varchar(1000) NOT NULL,
  `photolink` varchar(1000) NOT NULL,
  `organizer` varchar(1000) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `LaikiuxQuestions`
--

INSERT INTO `LaikiuxQuestions` (`id`, `question`, `answer`, `prize`, `photolink`, `organizer`) VALUES
(47, 'ApskaiÄiuokite X dispersijÄ….  Ats pvz 1.23', '0.84', 'VIP 2days', 'https://i.imgur.com/UNkiK1r.png', 'Tortonas'),
(48, 'ApskaiÄiuokite Y vidurkÄ¯ ats pvz.: 1.1', '0.8', 'VIP 2days', 'https://i.imgur.com/MwbtIS3.png', 'Tortonas'),
(49, 'ApskaiÄiuokite Y dispersijÄ….  Ats pvz 1.23', '0.76', 'VIP 2days', 'https://i.imgur.com/mUbGXuN.png', 'Tortonas'),
(50, 'ApskaiÄiuokite kovariacijÄ… pagal x,y. Ats pvz.: 0.45', '0.08', 'VIP 2days', 'https://i.imgur.com/pVai9RR.png', 'Tortonas');

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
  `organizer` varchar(1000) NOT NULL,
  `dateTime` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `LaikiuxSubmission`
--

INSERT INTO `LaikiuxSubmission` (`id`, `question`, `answer`, `prize`, `HasAnyoneWon`, `photolink`, `organizer`, `dateTime`) VALUES
(1, 'ApskaiÄiuokite vidurkÄ¯ ats pvz.: 1.1', '0.4', 'VIP 2days', '0', 'https://i.imgur.com/eUsXpjS.png', 'Tortonas', '2019-01-21 18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `LaikiuxWinners`
--

CREATE TABLE IF NOT EXISTS `LaikiuxWinners` (
  `id` int(11) NOT NULL,
  `Nickname` varchar(1000) NOT NULL,
  `organizer` varchar(1000) NOT NULL,
  `winnerIp` varchar(128) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `LaikiuxWinners`
--

INSERT INTO `LaikiuxWinners` (`id`, `Nickname`, `organizer`, `winnerIp`) VALUES
(34, 'Braskes Vaikas VIP 1day', 'Tortonas', 'player ip'),
(128, 'tabak VIP 2days', 'Tortonas', 'player ip'),
(129, 'tabak VIP 2days', 'Tortonas', 'player ip'),
(130, 'per lengva  VIP 2days', 'Tortonas', 'player ip');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `LaikiuxAdmins`
--
ALTER TABLE `LaikiuxAdmins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `LaikiuxCredits`
--
ALTER TABLE `LaikiuxCredits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `LaikiuxHints`
--
ALTER TABLE `LaikiuxHints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `LaikiuxQuestions`
--
ALTER TABLE `LaikiuxQuestions`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `LaikiuxCredits`
--
ALTER TABLE `LaikiuxCredits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `LaikiuxHints`
--
ALTER TABLE `LaikiuxHints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `LaikiuxQuestions`
--
ALTER TABLE `LaikiuxQuestions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `LaikiuxSubmission`
--
ALTER TABLE `LaikiuxSubmission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `LaikiuxWinners`
--
ALTER TABLE `LaikiuxWinners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=131;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
