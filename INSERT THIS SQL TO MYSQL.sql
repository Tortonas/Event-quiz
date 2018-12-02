-- phpMyAdmin SQL Dump
-- version 4.4.15
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 02, 2018 at 11:28 PM
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `LaikiuxCredits`
--

INSERT INTO `LaikiuxCredits` (`id`, `ip`, `credits`, `nick`, `bannedTill`) VALUES
(3, 'playerIp', 0, '', '0000-00-00 00:00:00'),
(4, 'playerIp', 0, '', '0000-00-00 00:00:00'),
(5, 'playerIp', 0, '', '0000-00-00 00:00:00'),
(6, 'playerIp', 2, 'Sparta', '2420-01-01 00:00:00'),
(7, 'playerIp', 0, 'BoyNewToy', '0000-00-00 00:00:00'),
(8, 'playerIp', 0, 'tRust', '0000-00-00 00:00:00'),
(9, 'playerIp', 2, 'K3peja$', '2018-12-03 19:00:00'),
(12, 'playerIp', 500, 'Tortonas', '2018-12-02 02:18:48'),
(13, 'playerIp', 0, 'In*Victus', '2420-01-01 00:00:00'),
(14, 'playerIp', 0, 'Haxters', '2420-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `LaikiuxHints`
--

CREATE TABLE IF NOT EXISTS `LaikiuxHints` (
  `id` int(11) NOT NULL,
  `hinttext` varchar(1000) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `LaikiuxQuestions`
--

INSERT INTO `LaikiuxQuestions` (`id`, `question`, `answer`, `prize`, `photolink`, `organizer`) VALUES
(6, 'Is kurio filmo yra sis pagrindinis veikejas?(Angliskai)', 'answer', 'VIP 2days', 'https://i.imgur.com/****2.png', 'Haxters'),
(14, 'question', 'ans', 'VIP 2days', 'https://i.imgur.com/*****.png', 'Haxters');

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
(1, 'Kokio seno s. admino profilis pavaizduotas? Atsakymas vardininkas.', 'Vyskupas', 'VIP 2days', '1', 'https://i.imgur.com/aBVz7gN.png', 'Tortonas', '2018-12-03 18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `LaikiuxWinners`
--

CREATE TABLE IF NOT EXISTS `LaikiuxWinners` (
  `id` int(11) NOT NULL,
  `Nickname` varchar(1000) NOT NULL,
  `organizer` varchar(1000) NOT NULL,
  `winnerIp` varchar(128) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `LaikiuxWinners`
--

INSERT INTO `LaikiuxWinners` (`id`, `Nickname`, `organizer`, `winnerIp`) VALUES
(34, 'Braskes Vaikas VIP 1day', 'Tortonas', 'userIp'),
(35, 'In*Victus VIP 1day', 'Tortonas', 'userIp'),
(81, 'K3peja$ VIP 2days', 'Tortonas', 'userIp');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `LaikiuxHints`
--
ALTER TABLE `LaikiuxHints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `LaikiuxQuestions`
--
ALTER TABLE `LaikiuxQuestions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `LaikiuxSubmission`
--
ALTER TABLE `LaikiuxSubmission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `LaikiuxWinners`
--
ALTER TABLE `LaikiuxWinners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=88;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
