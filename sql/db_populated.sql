-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 07, 2019 at 08:41 PM
-- Server version: 10.1.40-MariaDB
-- PHP Version: 7.2.7

--Bradley Seyler
--6/7/2019
--db.sql
--
--This file contains the basic table structure for the WTIA site with sample data


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smallert_wtiaconnect`
--

-- --------------------------------------------------------

--
-- Table structure for table `archived_opportunity`
--

CREATE TABLE `archived_opportunity` (
  `opp_id` int(11) NOT NULL DEFAULT '0',
  `requested_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(40) DEFAULT NULL,
  `requested_by` int(11) NOT NULL,
  `description` text NOT NULL,
  `accepted_speaker` int(11) NOT NULL DEFAULT '0',
  `qa_interview` tinyint(1) DEFAULT '0',
  `lecture` tinyint(1) DEFAULT '0',
  `panel` tinyint(1) DEFAULT '0',
  `workshop` tinyint(1) DEFAULT '0',
  `address` varchar(120) DEFAULT NULL,
  `city` varchar(40) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `dates` varchar(25) DEFAULT NULL,
  `days` varchar(70) DEFAULT NULL,
  `times` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `opportunity`
--

CREATE TABLE `opportunity` (
  `opp_id` int(11) NOT NULL,
  `requested_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(40) DEFAULT NULL,
  `requested_by` int(11) NOT NULL,
  `description` text NOT NULL,
  `accepted_speaker` int(11) NOT NULL DEFAULT '0',
  `qa_interview` tinyint(1) DEFAULT '0',
  `lecture` tinyint(1) DEFAULT '0',
  `panel` tinyint(1) DEFAULT '0',
  `workshop` tinyint(1) DEFAULT '0',
  `address` varchar(120) DEFAULT NULL,
  `city` varchar(40) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `dates` varchar(25) DEFAULT NULL,
  `days` varchar(70) DEFAULT NULL,
  `times` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `opportunity`
--

INSERT INTO `opportunity` (`opp_id`, `requested_on`, `title`, `requested_by`, `description`, `accepted_speaker`, `qa_interview`, `lecture`, `panel`, `workshop`, `address`, `city`, `zip`, `dates`, `days`, `times`) VALUES
(1, '2019-03-18 22:32:37', 'Teacher 2\'s Opportunity 1', 22, 'Opportunity 1 is about C#, C++, MySQL, php, Full Stack Web Dev, Python', 0, 0, 0, 1, 1, '8517 Main St.', 'Bellevue', '92765', '05/15/2019 - 05/31/2019', 'Mon', 'Evenings'),
(2, '2019-03-18 22:32:37', 'Teacher 2\'s Opportunity 2', 22, 'Opportunity 2 is about Javascript', 0, 0, 1, 0, 1, '1806 Hollywood Blvd.', 'Kent', '80318', '04/15/2019 - 05/31/2019', 'Thu, Tue, Sat, Mon', 'Afternoons'),
(3, '2019-03-18 22:32:37', 'Teacher 2\'s Opportunity 3', 22, 'Opportunity 3 is about Java, MySQL, Javascript, Full Stack Web Dev, Python', 0, 1, 1, 1, 0, '5088 Hollywood Blvd.', 'Enumclaw', '13997', '04/15/2019 - 05/31/2019', 'Sat, Mon', 'Mornings, Afternoons'),
(4, '2019-03-18 22:32:37', 'Teacher 2\'s Opportunity 4', 22, 'Opportunity 4 is about C#, Java, C++, MySQL, Python', 0, 0, 1, 0, 0, '1663 Santa Monica Blvd.', 'Enumclaw', '55623', '05/01/2019 - 05/31/2019', 'Thu, Sat, Wed, Sun', 'Mornings, Afternoons'),
(5, '2019-03-18 22:32:37', 'Teacher 2\'s Opportunity 5', 22, 'Opportunity 5 is about Cisco', 0, 0, 0, 1, 0, '6726 314th St.', 'Bellevue', '36736', '05/01/2019 - 05/31/2019', 'Thu, Tue', 'Mornings, Evenings'),
(6, '2019-03-18 22:32:37', 'Teacher 4\'s Opportunity 1', 24, 'Opportunity 1 is about C#', 0, 1, 0, 0, 0, '7681 Abbey Rd.', 'Kent', '74825', '04/15/2019 - 07/31/2019', 'Wed', 'Mornings'),
(7, '2019-03-18 22:32:37', 'Teacher 4\'s Opportunity 2', 24, 'Opportunity 2 is about C#, Java, MySQL, php, Full Stack Web Dev, Python', 0, 1, 1, 1, 1, '5073 Hollywood Blvd.', 'Kent', '50758', '04/15/2019 - 07/15/2019', 'Tue, Wed, Fri, Mon', 'Mornings, Afternoons'),
(8, '2019-03-18 22:32:37', 'Teacher 4\'s Opportunity 3', 24, 'Opportunity 3 is about C#, Python', 0, 0, 0, 0, 1, '2043 Main St.', 'Federal Way', '69256', '04/01/2019 - 07/31/2019', 'Fri, Sun, Mon', 'Afternoons, Evenings'),
(9, '2019-03-18 22:32:37', 'Teacher 4\'s Opportunity 4', 24, 'Opportunity 4 is about MySQL', 0, 0, 1, 0, 1, '7817 Hollywood Blvd.', 'Enumclaw', '96326', '05/15/2019 - 07/15/2019', 'Thu, Mon, Sun', 'Mornings'),
(10, '2019-03-18 22:32:37', 'Teacher 4\'s Opportunity 5', 24, 'Opportunity 5 is about C#, MySQL, Cisco, Full Stack Web Dev', 0, 1, 1, 0, 0, '9687 Park Ave.', 'Kirkland', '94924', '04/15/2019 - 07/31/2019', 'Wed, Fri, Mon', 'Evenings'),
(11, '2019-03-18 22:32:37', 'Teacher 6\'s Opportunity 1', 26, 'Opportunity 1 is about C++, Javascript', 0, 1, 1, 1, 0, '3389 Bourbon St.', 'Renton', '44917', '04/01/2019 - 07/31/2019', 'Wed, Mon', 'Evenings'),
(12, '2019-03-18 22:32:37', 'Teacher 6\'s Opportunity 2', 26, 'Opportunity 2 is about Java, C++, MySQL, Javascript, php, Full Stack Web Dev', 0, 1, 0, 0, 1, '4043 Main St.', 'Federal Way', '82356', '05/01/2019 - 06/15/2019', 'Tue, Wed, Sat', 'Mornings, Evenings'),
(13, '2019-03-18 22:32:37', 'Teacher 6\'s Opportunity 3', 26, 'Opportunity 3 is about C#, Javascript, Cisco, Full Stack Web Dev, Python', 0, 0, 1, 1, 0, '5696 314th St.', 'Federal Way', '20723', '04/01/2019 - 07/31/2019', 'Sat, Mon', 'Mornings, Afternoons, Evenings'),
(14, '2019-03-18 22:32:37', 'Teacher 6\'s Opportunity 4', 26, 'Opportunity 4 is about Java, Cisco, Javascript, Python', 0, 1, 1, 0, 0, '7118 Abbey Rd.', 'Federal Way', '19509', '05/01/2019 - 07/31/2019', 'Thu, Tue, Sun', 'Mornings'),
(15, '2019-03-18 22:32:37', 'Teacher 6\'s Opportunity 5', 26, 'Opportunity 5 is about C#, C++, Javascript, Full Stack Web Dev', 0, 1, 0, 0, 0, '1386 Abbey Rd.', 'Kent', '58118', '04/15/2019 - 07/15/2019', 'Thu, Tue', 'Evenings'),
(16, '2019-03-18 22:32:37', 'Teacher 8\'s Opportunity 1', 28, 'Opportunity 1 is about MySQL, Javascript, Full Stack Web Dev', 0, 0, 0, 0, 1, '4651 Hollywood Blvd.', 'Kirkland', '34502', '04/15/2019 - 05/31/2019', 'Sun', 'Mornings, Evenings'),
(17, '2019-03-18 22:32:37', 'Teacher 8\'s Opportunity 2', 28, 'Opportunity 2 is about Cisco', 0, 0, 1, 0, 0, '2277 Bourbon St.', 'Federal Way', '10904', '04/01/2019 - 07/31/2019', 'Sat', 'Mornings, Evenings'),
(18, '2019-03-18 22:32:37', 'Teacher 8\'s Opportunity 3', 28, 'Opportunity 3 is about C#, Java', 0, 0, 0, 1, 1, '8585 Abbey Rd.', 'Federal Way', '13233', '04/15/2019 - 07/31/2019', 'Tue, Sat, Mon', 'Mornings, Afternoons, Evenings'),
(19, '2019-03-18 22:32:37', 'Teacher 8\'s Opportunity 4', 28, 'Opportunity 4 is about Java, MySQL, Cisco', 0, 0, 1, 0, 0, '6593 Hollywood Blvd.', 'Auburn', '81210', '04/01/2019 - 05/31/2019', 'Thu, Wed, Fri, Sun', 'Mornings, Afternoons'),
(20, '2019-03-18 22:32:37', 'Teacher 8\'s Opportunity 5', 28, 'Opportunity 5 is about MySQL, Cisco, Javascript, php, Full Stack Web Dev', 0, 1, 0, 1, 1, '2431 Bourbon St.', 'Federal Way', '85746', '05/01/2019 - 05/31/2019', 'Wed, Fri, Sun, Mon', 'Evenings'),
(21, '2019-03-18 22:32:37', 'Teacher 10\'s Opportunity 1', 30, 'Opportunity 1 is about Java, MySQL, Full Stack Web Dev', 0, 1, 1, 0, 1, '1312 Santa Monica Blvd.', 'Auburn', '94307', '05/15/2019 - 07/31/2019', 'Wed', 'Evenings'),
(22, '2019-03-18 22:32:37', 'Teacher 10\'s Opportunity 2', 30, 'Opportunity 2 is about C#, Java, C++, Javascript, Full Stack Web Dev, Python', 0, 0, 0, 1, 1, '4154 Santa Monica Blvd.', 'Enumclaw', '50978', '05/15/2019 - 06/15/2019', 'Sat, Sun, Mon', 'Evenings'),
(23, '2019-03-18 22:32:37', 'Teacher 10\'s Opportunity 3', 30, 'Opportunity 3 is about C++, Javascript, php, Full Stack Web Dev', 0, 0, 1, 1, 1, '1942 Park Ave.', 'Renton', '37706', '05/01/2019 - 05/31/2019', 'Thu, Sat', 'Mornings'),
(24, '2019-03-18 22:32:37', 'Teacher 10\'s Opportunity 4', 30, 'Opportunity 4 is about C#, Cisco, php, Full Stack Web Dev', 0, 1, 1, 1, 1, '5851 314th St.', 'Renton', '55528', '04/01/2019 - 07/31/2019', 'Tue, Wed, Fri, Sun', 'Mornings, Evenings'),
(25, '2019-03-18 22:32:37', 'Teacher 10\'s Opportunity 5', 30, 'Opportunity 5 is about C++, MySQL, Cisco, php', 0, 1, 1, 1, 1, '5932 Abbey Rd.', 'Seattle', '71408', '05/01/2019 - 05/31/2019', 'Sun', 'Afternoons, Evenings'),
(26, '2019-03-18 22:32:37', 'Teacher 12\'s Opportunity 1', 32, 'Opportunity 1 is about C#, Cisco, Javascript, php', 0, 1, 1, 1, 0, '9391 Santa Monica Blvd.', 'Auburn', '13664', '05/01/2019 - 05/31/2019', 'Thu, Tue, Wed, Fri', 'Evenings'),
(27, '2019-03-18 22:32:37', 'Teacher 12\'s Opportunity 2', 32, 'Opportunity 2 is about Java, Javascript, Cisco, Full Stack Web Dev', 0, 1, 0, 1, 0, '8748 Fifth Ave.', 'Bellevue', '71087', '05/01/2019 - 06/15/2019', 'Tue, Sat', 'Mornings, Afternoons'),
(28, '2019-03-18 22:32:37', 'Teacher 12\'s Opportunity 3', 32, 'Opportunity 3 is about Cisco', 0, 0, 1, 0, 0, '6839 Main St.', 'Kirkland', '13877', '05/01/2019 - 05/31/2019', 'Wed, Sun, Mon', 'Afternoons, Evenings'),
(29, '2019-03-18 22:32:37', 'Teacher 12\'s Opportunity 4', 32, 'Opportunity 4 is about C++, Cisco, php', 0, 0, 1, 0, 0, '5752 Main St.', 'Renton', '99181', '05/15/2019 - 06/15/2019', 'Tue, Sat, Fri', 'Evenings'),
(30, '2019-03-18 22:32:37', 'Teacher 12\'s Opportunity 5', 32, 'Opportunity 5 is about C++, Cisco, Javascript, php, Full Stack Web Dev, Python', 0, 1, 0, 0, 0, '9425 Hollywood Blvd.', 'Seattle', '13926', '04/01/2019 - 05/31/2019', 'Fri, Sun, Mon', 'Mornings'),
(31, '2019-03-18 22:32:37', 'Teacher 14\'s Opportunity 1', 34, 'Opportunity 1 is about MySQL, php', 0, 1, 1, 0, 1, '8062 Park Ave.', 'Seattle', '40778', '04/15/2019 - 07/15/2019', 'Thu, Wed, Sat, Fri', 'Mornings, Afternoons, Evenings'),
(32, '2019-03-18 22:32:37', 'Teacher 14\'s Opportunity 2', 34, 'Opportunity 2 is about Javascript, Full Stack Web Dev, Python', 0, 0, 0, 0, 1, '7125 314th St.', 'Federal Way', '64364', '04/15/2019 - 06/15/2019', 'Thu, Fri, Mon', 'Mornings, Afternoons, Evenings'),
(33, '2019-03-18 22:32:37', 'Teacher 14\'s Opportunity 3', 34, 'Opportunity 3 is about Javascript', 0, 1, 1, 1, 0, '4000 Park Ave.', 'Federal Way', '17838', '04/15/2019 - 05/31/2019', 'Sat, Fri', 'Evenings'),
(34, '2019-03-18 22:32:37', 'Teacher 14\'s Opportunity 4', 34, 'Opportunity 4 is about C++, Python', 0, 0, 1, 0, 0, '7414 Hollywood Blvd.', 'Seattle', '45294', '05/01/2019 - 07/31/2019', 'Tue, Fri', 'Mornings'),
(35, '2019-03-18 22:32:37', 'Teacher 14\'s Opportunity 5', 34, 'Opportunity 5 is about C#, Java, C++, MySQL, php', 0, 1, 0, 1, 0, '3533 Santa Monica Blvd.', 'Kent', '61049', '05/01/2019 - 07/15/2019', 'Thu, Tue, Mon', 'Mornings, Afternoons, Evenings'),
(36, '2019-03-18 22:32:37', 'Teacher 16\'s Opportunity 1', 36, 'Opportunity 1 is about Javascript, php', 0, 1, 1, 1, 1, '6902 Santa Monica Blvd.', 'Renton', '73575', '05/01/2019 - 06/15/2019', 'Mon', 'Mornings, Evenings'),
(37, '2019-03-18 22:32:37', 'Teacher 16\'s Opportunity 2', 36, 'Opportunity 2 is about Java, C++, Cisco, Javascript, php', 0, 0, 0, 1, 0, '7950 Main St.', 'Kent', '54925', '05/01/2019 - 07/31/2019', 'Sat, Fri', 'Evenings'),
(38, '2019-03-18 22:32:37', 'Teacher 16\'s Opportunity 3', 36, 'Opportunity 3 is about C#, Java, C++, Javascript, php', 0, 1, 1, 0, 1, '4308 314th St.', 'Seattle', '48518', '04/01/2019 - 07/31/2019', 'Thu, Tue, Wed, Fri', 'Evenings'),
(39, '2019-03-18 22:32:37', 'Teacher 16\'s Opportunity 4', 36, 'Opportunity 4 is about C#, Java, MySQL, php', 0, 1, 1, 0, 0, '5215 Abbey Rd.', 'Auburn', '28764', '04/15/2019 - 07/15/2019', 'Tue, Wed, Fri, Mon', 'Mornings, Afternoons'),
(40, '2019-03-18 22:32:37', 'Teacher 16\'s Opportunity 5', 36, 'Opportunity 5 is about C#, Java, C++, MySQL, Cisco, Full Stack Web Dev', 0, 1, 0, 0, 0, '3769 Hollywood Blvd.', 'Kent', '31173', '04/15/2019 - 06/15/2019', 'Wed, Sun', 'Mornings, Afternoons, Evenings'),
(41, '2019-03-18 22:32:37', 'Teacher 18\'s Opportunity 1', 38, 'Opportunity 1 is about C#, C++, Cisco, php', 0, 1, 1, 0, 1, '8794 Park Ave.', 'Redmond', '87182', '05/15/2019 - 05/31/2019', 'Tue, Sun', 'Afternoons'),
(42, '2019-03-18 22:32:37', 'Teacher 18\'s Opportunity 2', 38, 'Opportunity 2 is about Java, MySQL, Javascript, php, Full Stack Web Dev', 0, 0, 1, 1, 0, '4158 Hollywood Blvd.', 'Kirkland', '66251', '05/15/2019 - 07/31/2019', 'Sat', 'Mornings, Afternoons'),
(43, '2019-03-18 22:32:37', 'Teacher 18\'s Opportunity 3', 38, 'Opportunity 3 is about C++, MySQL, Javascript, Cisco, Python', 0, 0, 0, 0, 1, '4071 Abbey Rd.', 'Kirkland', '80033', '04/15/2019 - 07/15/2019', 'Wed, Fri, Sun', 'Afternoons, Evenings'),
(44, '2019-03-18 22:32:37', 'Teacher 18\'s Opportunity 4', 38, 'Opportunity 4 is about C++, Cisco', 0, 0, 0, 0, 1, '4830 Main St.', 'Bellevue', '22547', '05/15/2019 - 07/31/2019', 'Wed, Sat', 'Mornings'),
(45, '2019-03-18 22:32:37', 'Teacher 18\'s Opportunity 5', 38, 'Opportunity 5 is about C#, C++, MySQL, Python', 0, 0, 1, 1, 0, '8534 Santa Monica Blvd.', 'Auburn', '67297', '05/01/2019 - 07/31/2019', 'Mon, Sun', 'Mornings'),
(46, '2019-03-18 22:32:37', 'Teacher 20\'s Opportunity 1', 40, 'Opportunity 1 is about Javascript', 0, 0, 1, 1, 1, '9531 Abbey Rd.', 'Kent', '86261', '04/01/2019 - 07/31/2019', 'Tue, Wed, Sat, Fri', 'Afternoons, Evenings'),
(47, '2019-03-18 22:32:37', 'Teacher 20\'s Opportunity 2', 40, 'Opportunity 2 is about Javascript', 0, 1, 1, 1, 0, '3231 Hollywood Blvd.', 'Redmond', '26513', '05/15/2019 - 07/31/2019', 'Tue, Sat', 'Mornings, Afternoons, Evenings'),
(48, '2019-03-18 22:32:37', 'Teacher 20\'s Opportunity 3', 40, 'Opportunity 3 is about Cisco, Python', 0, 1, 0, 0, 1, '8345 Abbey Rd.', 'Seattle', '48405', '05/01/2019 - 07/15/2019', 'Thu, Sat, Sun', 'Afternoons, Evenings'),
(49, '2019-03-18 22:32:37', 'Teacher 20\'s Opportunity 4', 40, 'Opportunity 4 is about C++, Full Stack Web Dev', 0, 1, 1, 1, 0, '5381 Park Ave.', 'Auburn', '47771', '05/15/2019 - 07/31/2019', 'Sat, Fri, Sun', 'Mornings, Afternoons'),
(50, '2019-03-18 22:32:37', 'Teacher 20\'s Opportunity 5', 40, 'Opportunity 5 is about C#, C++, MySQL, Javascript, Full Stack Web Dev', 0, 0, 0, 1, 0, '7807 Fifth Ave.', 'Federal Way', '20096', '05/15/2019 - 05/31/2019', 'Thu, Tue, Mon', 'Mornings'),
(51, '2019-03-19 22:14:22', 'Need Java Developer', 23, 'Need a java developer to speak to my CS students about career opportunities.', 0, 0, 1, 1, 1, '36201 Enchanted Parkway S.', 'Federal Way', '98003', '02/18/2019 - 03/19/2019', 'Mon, Tue, Thu', 'Afternoons, Evenings'),
(52, '2019-03-20 05:14:07', 'Looking For a Software Engineer', 23, 'Searching for someone with industry experience to show my CS students what a future in software development is like.', 0, 0, 1, 1, 0, '21005 64th Ave S', 'Kent', '98032', '03/19/2019 - 05/19/2019', 'Mon, Tue, Wed', 'Mornings, Afternoons'),
(53, '2019-03-20 05:37:28', 'Need Python Developer', 23, 'Searching for a python developer to show my CS students all the things that can be done with Python.', 0, 0, 0, 0, 1, '21005 64th Ave S', 'Kent', '98032', '03/01/2019 - 03/31/2019', 'Mon, Tue, Wed, Thu, Fri', 'Mornings, Afternoons, Evenings'),
(54, '2019-03-20 05:52:49', 'Seeking Web Developer', 43, 'Trying to find a web developer to come in and show my students how exciting web development can be.', 0, 0, 1, 1, 0, '36201 Enchanted Parkway S.', 'Federal Way', '98003', '02/18/2019 - 03/19/2019', 'Mon, Tue, Wed', 'Mornings, Afternoons'),
(55, '2019-03-20 05:55:06', 'Seeking Full-stack Developer', 43, 'Seeking a full-stack developer for a workshop with my senior level CS students, to show them the many stacks of development.', 0, 1, 1, 0, 0, '36201 Enchanted Parkway S.', 'Federal Way', '98003', '03/22/2019 - 04/26/2019', 'Mon, Tue, Wed, Thu, Fri', 'Mornings, Afternoons, Evenings'),
(56, '2019-03-20 21:39:53', 'Need Web Developer', 44, 'Need a web developer to talk to my CS students', 0, 1, 1, 0, 0, '12345 102nd ave se', 'Kent', '98042', '03/01/2019 - 03/31/2019', 'Mon, Wed, Fri', 'Mornings, Afternoons'),
(66, '2019-06-04 22:12:02', 'Talk to my math class', 71, 'Looking for professional', 0, 1, 0, 0, 0, 'Redmond High', 'Redmond', '98038', '06/01/2019 - 06/30/2019', 'Mon, Tue, Thu', 'Mornings'),
(67, '2019-06-06 22:04:21', 'Speak for 12th grade Web Design Class.', 72, 'Requesting a speaker for a 12th grade web design class. I would like to introduce to them a voice from the industry and outline some basic technologies that are commonly used in the tech industry.', 0, 1, 0, 0, 0, '17801 International Blvd', 'Seattle', '98158', '05/16/2019 - 06/18/2019', 'Mon, Tue, Wed', 'Afternoons');

-- --------------------------------------------------------

--
-- Table structure for table `pros`
--

CREATE TABLE `pros` (
  `user_id` int(11) NOT NULL,
  `company` varchar(80) DEFAULT NULL,
  `job_title` varchar(40) DEFAULT NULL,
  `expertise` text,
  `qa_interview` tinyint(1) DEFAULT '0',
  `lecture` tinyint(1) DEFAULT '0',
  `panel` tinyint(1) DEFAULT '0',
  `workshop` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pros`
--

INSERT INTO `pros` (`user_id`, `company`, `job_title`, `expertise`, `qa_interview`, `lecture`, `panel`, `workshop`) VALUES
(1, 'Indeed', 'Computer Engineer', 'C#, MySQL, php', 1, 1, 1, 0),
(2, 'Nintendo', 'Database Administrator', 'C++, Python', 1, 1, 1, 0),
(3, 'Indeed', 'Software Developer', 'C#, php, Python', 0, 1, 0, 1),
(4, 'Ice Cream Shoppe', 'Security Specialist', 'Javascript, php, Full Stack Web Dev, Python', 0, 1, 0, 0),
(5, 'Boeing', 'Programmer', 'MySQL, Cisco, php, Python', 1, 0, 0, 0),
(6, 'Google', 'Computer Engineer', 'C#, Java, Cisco, php', 0, 1, 1, 0),
(7, 'Microsoft', 'Security Specialist', 'Java, Python', 0, 1, 1, 0),
(8, 'Ice Cream Shoppe', 'Programmer', 'Javascript, Cisco', 0, 1, 0, 0),
(9, 'Ice Cream Shoppe', 'Security Specialist', 'Java, Cisco, Javascript, php', 1, 1, 0, 1),
(10, 'Ice Cream Shoppe', 'Software Developer', 'Java, MySQL, Cisco, Javascript, php', 1, 1, 0, 0),
(11, 'Nintendo', 'Computer Engineer', 'Javascript', 0, 1, 0, 0),
(12, 'Microsoft', 'Database Administrator', 'Java, MySQL', 1, 1, 1, 0),
(13, 'Nintendo', 'Programmer', 'C++, MySQL, Javascript, php', 0, 1, 1, 0),
(14, 'Google', 'Security Specialist', 'Java, C++', 0, 0, 1, 0),
(15, 'Nintendo', 'Database Administrator', 'C#, Java, Javascript, php, Python', 1, 1, 1, 1),
(16, 'Microsoft', 'Computer Engineer', 'C#, Java, Javascript, Full Stack Web Dev', 1, 1, 0, 0),
(17, 'City of Tacoma', 'Programmer', 'Javascript, Cisco, php, Full Stack Web Dev', 1, 1, 1, 1),
(18, 'City of Tacoma', 'Security Specialist', 'Java, C++', 1, 0, 0, 0),
(19, 'City of Tacoma', 'Software Developer', 'MySQL, Full Stack Web Dev, Python', 1, 1, 0, 1),
(20, 'City of Tacoma', 'Software Developer', 'C#, Javascript', 1, 0, 0, 0),
(41, 'Amazon', 'Software Developer', 'C++, Java, Python', 1, 0, 1, 0),
(42, 'Tesla', 'Computer Engineer', 'Python, Java, C++, SQL', 0, 0, 0, 1),
(45, 'Green River', 'Teacher', 'java', 1, 0, 1, 0),
(47, 'Macroshaft', 'Janitor Closet `Secretary', 'DOS, Java', 0, 0, 1, 0),
(48, 'Green River', 'Teacher', 'Java', 1, 1, 0, 0),
(49, 'Amazon', 'Software Developer', 'Java, SQL, JavaScript', 0, 0, 0, 1),
(50, 'Nintend', 'Lol', 'Java, Test', 0, 0, 0, 1),
(51, '', '', 'Java', 0, 1, 0, 0),
(54, 'Green River', 'Student', 'Java', 1, 0, 1, 0),
(55, 'Green River', 'skldfjlskadf', 'jAVA', 0, 1, 1, 0),
(56, '', '', 'java', 1, 0, 0, 0),
(57, '', '', 'java', 1, 0, 0, 0),
(58, 'Green River', 'Student', 'Java Jacascript', 0, 1, 0, 0),
(60, 'Macroshaft', '', 'SQL', 0, 0, 0, 1),
(62, 'Acme Crushing', 'Crusher!', 'java', 0, 0, 0, 1),
(63, 'Nintend', '', 'Java', 1, 0, 0, 0),
(65, '', '', 'java', 1, 0, 0, 0),
(68, '', '', 'Boredom', 0, 0, 1, 0),
(69, '', '', 'Java, PHP', 1, 0, 0, 0),
(70, '', '', 'SQL', 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `speaking_requests`
--

CREATE TABLE `speaking_requests` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `event_organizer` varchar(40) NOT NULL,
  `organizer_email` varchar(4) NOT NULL,
  `requester_name` varchar(40) NOT NULL,
  `requester_email` varchar(40) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `user_id` int(11) NOT NULL,
  `school` varchar(80) DEFAULT NULL,
  `district` varchar(40) DEFAULT NULL,
  `grade` int(11) DEFAULT NULL,
  `subject` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`user_id`, `school`, `district`, `grade`, `subject`) VALUES
(21, 'Kent High School', 'Kent', 11, 'Math'),
(22, 'Federal Way High School', 'Federal Way', 12, 'Underwater Basket Weaving'),
(23, 'Kent High School', 'Kent', 7, 'Programming'),
(24, 'Auburn High School', 'Auburn', 10, 'Engineering'),
(25, 'Enumscratch High School', 'Enumscratch', 11, 'Programming'),
(26, 'Hard Knocks High School', 'Hard Knocks', 12, 'Underwater Basket Weaving'),
(27, 'Auburn High School', 'Auburn', 8, 'Underwater Basket Weaving'),
(28, 'Seattle High School', 'Seattle', 12, 'Underwater Basket Weaving'),
(29, 'Seattle High School', 'Seattle', 6, 'Engineering'),
(30, 'Seattle High School', 'Seattle', 9, 'Engineering'),
(31, 'Auburn High School', 'Auburn', 11, 'Engineering'),
(32, 'Federal Way High School', 'Federal Way', 7, 'Computer Science'),
(33, 'Auburn High School', 'Auburn', 10, 'Programming'),
(34, 'Enumscratch High School', 'Enumscratch', 6, 'Programming'),
(35, 'Federal Way High School', 'Federal Way', 12, 'Computer Science'),
(36, 'Seattle High School', 'Seattle', 7, 'Math'),
(37, 'Hard Knocks High School', 'Hard Knocks', 6, 'Math'),
(38, 'Seattle High School', 'Seattle', 10, 'Math'),
(39, 'Enumscratch High School', 'Enumscratch', 11, 'Math'),
(40, 'Kent High School', 'Kent', 9, 'Computers'),
(43, 'Kentlake', 'Kent', 0, 'Computer Science'),
(44, 'Kentlake', 'Kent', 0, 'Computer Science'),
(46, 'green river', 'king', 0, 'science'),
(52, 'A', 'Nintendistrict', 6, 'Java'),
(53, '', '', 0, ''),
(59, 'Enumclaw High School', 'Enumclaw', 0, 'Java'),
(61, '', '', 0, ''),
(64, '', 'Enumclaw', 0, ''),
(66, '', '', 0, ''),
(67, 'Enumclaw High School', 'Enumclaw', 0, 'sdfds'),
(71, 'Redmond High School', 'Lake Washington High School', 0, 'Math'),
(72, 'Kent Lake High School', 'Seattle', 0, 'JavaScript, Web Design, HTML, CSS, PHP'),
(73, '', '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `password` char(128) NOT NULL,
  `user_type` varchar(25) NOT NULL,
  `bio` text,
  `last_logon` datetime DEFAULT NULL,
  `weekly_msg` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `first_name`, `last_name`, `password`, `user_type`, `bio`, `last_logon`, `weekly_msg`) VALUES
(1, 'sigmund@tolkien.com', 'Sigmund', 'Tolkien', '7982fc49e0d365c96e7baf4266f14e25e6d28cf6622ca913985a242b13bcedb74e6a2300b4dbe524859abd1cbd1d46d83031af9cc60106cc8fc39d45338905a1', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 1),
(2, 'sigmund@herbert.com', 'Sigmund', 'Herbert', '7982fc49e0d365c96e7baf4266f14e25e6d28cf6622ca913985a242b13bcedb74e6a2300b4dbe524859abd1cbd1d46d83031af9cc60106cc8fc39d45338905a1', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(3, 'jacob@miyazaki.com', 'Jacob', 'Miyazaki', '0e7f00807516fa6836c67001a9d3c1539b9a6c7f76f7573bf6a810e9f742553634803f6ea83ad6274dc029aa37b1ac320330fd9bce0b7ab2ce4f2ecd283173e4', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(4, 'john@jackson.com', 'John', 'Jackson', '41b6d0cd5ddab15074f88bf1c356e89c3f330771b1c7a0b034bcdaafee74eb2ca2eca502f8c0b04fe5fd2f1ec5ae0197e0c6088f1cef6c07378b7f78bb64d9e4', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(5, 'penelope@jordan.com', 'Penelope', 'Jordan', 'a3e613a36b09e02418e7f9749162ee943786b81074bcdd5092facad4b4333016c6b20becd97001e6fab5a12ca7a7bc1eabe6ed402023b80f620abf07c5cc68c2', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 1),
(6, 'jacob@tolkien.com', 'Jacob', 'Tolkien', '0e7f00807516fa6836c67001a9d3c1539b9a6c7f76f7573bf6a810e9f742553634803f6ea83ad6274dc029aa37b1ac320330fd9bce0b7ab2ce4f2ecd283173e4', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(7, 'jingle@miyazaki.com', 'Jingle', 'Miyazaki', '9c02e53e53cbee4a4f4a2ea9a1f9e8c8e8a716ddad0a110c99ea0b9f061f73d49df1529373581b68d4c6bae330c1e56b10a6296cc7444b3bc0914f13f5df8239', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(8, 'sandra@smith.com', 'Sandra', 'Smith', '1f31329b35a04bb1d0d3f33fd5869725739208fc5119a090739e88c1676a2de8d10ed5469f04701a3a0a2df332c7349e4903bab66f53244403a7ecd1b7f1ca72', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 1),
(9, 'sigmund@jordan.com', 'Sigmund', 'Jordan', '7982fc49e0d365c96e7baf4266f14e25e6d28cf6622ca913985a242b13bcedb74e6a2300b4dbe524859abd1cbd1d46d83031af9cc60106cc8fc39d45338905a1', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(10, 'sandra@karpyshyn.com', 'Sandra', 'Karpyshyn', '1f31329b35a04bb1d0d3f33fd5869725739208fc5119a090739e88c1676a2de8d10ed5469f04701a3a0a2df332c7349e4903bab66f53244403a7ecd1b7f1ca72', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(11, 'john@tolkien.com', 'John', 'Tolkien', '41b6d0cd5ddab15074f88bf1c356e89c3f330771b1c7a0b034bcdaafee74eb2ca2eca502f8c0b04fe5fd2f1ec5ae0197e0c6088f1cef6c07378b7f78bb64d9e4', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 1),
(12, 'sigmund@smith.com', 'Sigmund', 'Smith', '7982fc49e0d365c96e7baf4266f14e25e6d28cf6622ca913985a242b13bcedb74e6a2300b4dbe524859abd1cbd1d46d83031af9cc60106cc8fc39d45338905a1', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(13, 'aria@miyazaki.com', 'Aria', 'Miyazaki', '17cc56f8e5e015d48a2740ff7141e8e326a0c098ce6c58e3fb163335cbb6791a69f8008cc1c1182ad9ddec099b67fce55d292ccb2f98947db99562ad60e27085', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(14, 'jacob@jackson.com', 'Jacob', 'Jackson', '0e7f00807516fa6836c67001a9d3c1539b9a6c7f76f7573bf6a810e9f742553634803f6ea83ad6274dc029aa37b1ac320330fd9bce0b7ab2ce4f2ecd283173e4', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 1),
(15, 'sigmund@miyazaki.com', 'Sigmund', 'Miyazaki', '7982fc49e0d365c96e7baf4266f14e25e6d28cf6622ca913985a242b13bcedb74e6a2300b4dbe524859abd1cbd1d46d83031af9cc60106cc8fc39d45338905a1', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 1),
(16, 'aria@freud.com', 'Aria', 'Freud', '17cc56f8e5e015d48a2740ff7141e8e326a0c098ce6c58e3fb163335cbb6791a69f8008cc1c1182ad9ddec099b67fce55d292ccb2f98947db99562ad60e27085', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(17, 'john@jordan.com', 'John', 'Jordan', '41b6d0cd5ddab15074f88bf1c356e89c3f330771b1c7a0b034bcdaafee74eb2ca2eca502f8c0b04fe5fd2f1ec5ae0197e0c6088f1cef6c07378b7f78bb64d9e4', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(18, 'jacob@jordan.com', 'Jacob', 'Jordan', '0e7f00807516fa6836c67001a9d3c1539b9a6c7f76f7573bf6a810e9f742553634803f6ea83ad6274dc029aa37b1ac320330fd9bce0b7ab2ce4f2ecd283173e4', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(19, 'beth@miyazaki.com', 'Beth', 'Miyazaki', 'add51351885b182d26a4fd63241177459578246f79abdeac674149453f23ddb06a25246e89cd63c3b3695eed0df2d5afd8e616d83308ad2e05597afd32466d49', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 1),
(20, 'jingle@jordan.com', 'Jingle', 'Jordan', '9c02e53e53cbee4a4f4a2ea9a1f9e8c8e8a716ddad0a110c99ea0b9f061f73d49df1529373581b68d4c6bae330c1e56b10a6296cc7444b3bc0914f13f5df8239', 'Professional', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(21, 'beth@karpyshyn.com', 'Beth', 'Karpyshyn', 'add51351885b182d26a4fd63241177459578246f79abdeac674149453f23ddb06a25246e89cd63c3b3695eed0df2d5afd8e616d83308ad2e05597afd32466d49', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(22, 'sigmund@freud.com', 'Sigmund', 'Freud', '7982fc49e0d365c96e7baf4266f14e25e6d28cf6622ca913985a242b13bcedb74e6a2300b4dbe524859abd1cbd1d46d83031af9cc60106cc8fc39d45338905a1', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(23, 'aria@herbert.com', 'Aria', 'Herbert', '17cc56f8e5e015d48a2740ff7141e8e326a0c098ce6c58e3fb163335cbb6791a69f8008cc1c1182ad9ddec099b67fce55d292ccb2f98947db99562ad60e27085', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 1),
(24, 'jacob@freud.com', 'Jacob', 'Freud', '0e7f00807516fa6836c67001a9d3c1539b9a6c7f76f7573bf6a810e9f742553634803f6ea83ad6274dc029aa37b1ac320330fd9bce0b7ab2ce4f2ecd283173e4', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 1),
(25, 'penelope@tolkien.com', 'Penelope', 'Tolkien', 'a3e613a36b09e02418e7f9749162ee943786b81074bcdd5092facad4b4333016c6b20becd97001e6fab5a12ca7a7bc1eabe6ed402023b80f620abf07c5cc68c2', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 1),
(26, 'beth@jackson.com', 'Beth', 'Jackson', 'add51351885b182d26a4fd63241177459578246f79abdeac674149453f23ddb06a25246e89cd63c3b3695eed0df2d5afd8e616d83308ad2e05597afd32466d49', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(27, 'aria@smith.com', 'Aria', 'Smith', '17cc56f8e5e015d48a2740ff7141e8e326a0c098ce6c58e3fb163335cbb6791a69f8008cc1c1182ad9ddec099b67fce55d292ccb2f98947db99562ad60e27085', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 1),
(28, 'aria@jordan.com', 'Aria', 'Jordan', '17cc56f8e5e015d48a2740ff7141e8e326a0c098ce6c58e3fb163335cbb6791a69f8008cc1c1182ad9ddec099b67fce55d292ccb2f98947db99562ad60e27085', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 1),
(29, 'sandra@miyazaki.com', 'Sandra', 'Miyazaki', '1f31329b35a04bb1d0d3f33fd5869725739208fc5119a090739e88c1676a2de8d10ed5469f04701a3a0a2df332c7349e4903bab66f53244403a7ecd1b7f1ca72', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(30, 'beth@freud.com', 'Beth', 'Freud', 'add51351885b182d26a4fd63241177459578246f79abdeac674149453f23ddb06a25246e89cd63c3b3695eed0df2d5afd8e616d83308ad2e05597afd32466d49', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(31, 'penelope@jackson.com', 'Penelope', 'Jackson', 'a3e613a36b09e02418e7f9749162ee943786b81074bcdd5092facad4b4333016c6b20becd97001e6fab5a12ca7a7bc1eabe6ed402023b80f620abf07c5cc68c2', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 1),
(32, 'john@karpyshyn.com', 'John', 'Karpyshyn', '41b6d0cd5ddab15074f88bf1c356e89c3f330771b1c7a0b034bcdaafee74eb2ca2eca502f8c0b04fe5fd2f1ec5ae0197e0c6088f1cef6c07378b7f78bb64d9e4', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(33, 'beth@herbert.com', 'Beth', 'Herbert', 'add51351885b182d26a4fd63241177459578246f79abdeac674149453f23ddb06a25246e89cd63c3b3695eed0df2d5afd8e616d83308ad2e05597afd32466d49', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(34, 'jingle@smith.com', 'Jingle', 'Smith', '9c02e53e53cbee4a4f4a2ea9a1f9e8c8e8a716ddad0a110c99ea0b9f061f73d49df1529373581b68d4c6bae330c1e56b10a6296cc7444b3bc0914f13f5df8239', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(35, 'penelope@karpyshyn.com', 'Penelope', 'Karpyshyn', 'a3e613a36b09e02418e7f9749162ee943786b81074bcdd5092facad4b4333016c6b20becd97001e6fab5a12ca7a7bc1eabe6ed402023b80f620abf07c5cc68c2', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(36, 'sigmund@jackson.com', 'Sigmund', 'Jackson', '7982fc49e0d365c96e7baf4266f14e25e6d28cf6622ca913985a242b13bcedb74e6a2300b4dbe524859abd1cbd1d46d83031af9cc60106cc8fc39d45338905a1', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(37, 'penelope@freud.com', 'Penelope', 'Freud', 'a3e613a36b09e02418e7f9749162ee943786b81074bcdd5092facad4b4333016c6b20becd97001e6fab5a12ca7a7bc1eabe6ed402023b80f620abf07c5cc68c2', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 0),
(38, 'penelope@miyazaki.com', 'Penelope', 'Miyazaki', 'a3e613a36b09e02418e7f9749162ee943786b81074bcdd5092facad4b4333016c6b20becd97001e6fab5a12ca7a7bc1eabe6ed402023b80f620abf07c5cc68c2', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 1),
(39, 'aria@karpyshyn.com', 'Aria', 'Karpyshyn', '17cc56f8e5e015d48a2740ff7141e8e326a0c098ce6c58e3fb163335cbb6791a69f8008cc1c1182ad9ddec099b67fce55d292ccb2f98947db99562ad60e27085', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 1),
(40, 'john@freud.com', 'John', 'Freud', '41b6d0cd5ddab15074f88bf1c356e89c3f330771b1c7a0b034bcdaafee74eb2ca2eca502f8c0b04fe5fd2f1ec5ae0197e0c6088f1cef6c07378b7f78bb64d9e4', 'Teacher', 'Lorem ipsum dolor', '2019-03-18 18:32:37', 1),
(41, 'johnstamos@mail.com', 'John', 'Stamos', '0123456789', 'Professional', 'Academic, Dreamer, Harry Potter Lover, Genomic Computational Biologist, Wig tester. I unleashed the zombie apocalypse.', '2019-03-20 01:00:12', 1),
(42, 'brucelee@gmail.com', 'Bruce', 'Lee', 'bb96c2fc40d2d54617d6f276febe571f623a8dadf0b734855299b0e107fda32cf6b69f2da32b36445d73690b93cbd0f7bfc20e0f7f28553d2a4428f23b716e90', 'Professional', 'I have worked for 20 years in the tech industry, from web development to machine learning.', '2019-03-20 01:46:43', 0),
(43, 'willsmith@gmail.com', 'Will', 'Smith', 'bb96c2fc40d2d54617d6f276febe571f623a8dadf0b734855299b0e107fda32cf6b69f2da32b36445d73690b93cbd0f7bfc20e0f7f28553d2a4428f23b716e90', 'Teacher', 'I am a high school computer science teacher.', '2019-03-20 01:48:06', 1),
(44, 'jeffbezos@gmail.com', 'Jeff', 'Bezos', 'bb96c2fc40d2d54617d6f276febe571f623a8dadf0b734855299b0e107fda32cf6b69f2da32b36445d73690b93cbd0f7bfc20e0f7f28553d2a4428f23b716e90', 'Teacher', 'I am a CS teacher.', '2019-03-20 17:38:05', 1),
(45, 'testemail123@hotmail.com', 'Test', 'Account', 'bed4efa1d4fdbd954bd3705d6a2a78270ec9a52ecfbfb010c61862af5c76af1761ffeb1aef6aca1bf5d02b3781aa854fabd2b69c790de74e17ecfec3cb6ac4bf', 'Professional', '', '2019-04-04 21:41:58', 0),
(46, 'areynolds8@mail.greenriver.edu', 'aaron', 'reynolds', '59a3b3a21fe6182f4159dcc1a96d5a22b935ca26ee50a307df9477531db1c0462c0368aa3e258559ee7a4befca243f0e41076b1a7b5d3147495f03c65cd6b16a', 'Teacher', '', '2019-04-11 16:20:52', 0),
(47, 'test@test.com', 'Dude', 'McDude', '12b03226a6d8be9c6e8cd5e55dc6c7920caaa39df14aab92d5e3ea9340d1c8a4d3d0b8e4314f1f6ef131ba4bf1ceb9186ab87c801af0d5c95b1befb8cedae2b9', 'Professional', 'Dude McDude is the leading industry professional in DOS in the year 2019. McDude loves playing DOOM, Transport Tycoon Deluxe, as well as writing DOS programs that use SoundBlaster 16.', '2019-04-11 22:02:57', 0),
(48, 'email123@hotmail.com', 'Christian', 'Talmadge', 'e6c83b282aeb2e022844595721cc00bbda47cb24537c1779f9bb84f04039e1676e6ba8573e588da1052510e3aa0a32a9e55879ae22b0c2d62136fc0a3e85f8bb', 'Professional', '', '2019-04-18 21:21:04', 0),
(49, 'email1234@email.com', 'Christian', 'Talmadge', 'e6c83b282aeb2e022844595721cc00bbda47cb24537c1779f9bb84f04039e1676e6ba8573e588da1052510e3aa0a32a9e55879ae22b0c2d62136fc0a3e85f8bb', 'Professional', '', '2019-04-18 21:45:57', 0),
(50, 'bseyler@mail.greenriver.ed', 'Bradbo', '400', 'feb84b83cb85b59f2e7380c3adbdc6b47315c1bb035484245db70e5bc29af3a6e42d0e60945a0b802557035ace1b0dcc27794db3f7f64723260511395b0564d3', 'Professional', 'I test Fat Free framework things', '2019-05-02 00:00:40', 0),
(51, 'test@email.com', 'Jkl', 'Lmo', 'feb84b83cb85b59f2e7380c3adbdc6b47315c1bb035484245db70e5bc29af3a6e42d0e60945a0b802557035ace1b0dcc27794db3f7f64723260511395b0564d3', 'Professional', '', '2019-05-02 00:15:43', 0),
(52, 'MTeacher@mail.com', 'Mario', 'LName', 'feb84b83cb85b59f2e7380c3adbdc6b47315c1bb035484245db70e5bc29af3a6e42d0e60945a0b802557035ace1b0dcc27794db3f7f64723260511395b0564d3', 'Teacher', 'DO the mario!', '2019-05-02 04:31:43', 0),
(53, 'jreynolds8@mail.greenriver.edu', 'john', 'reynolds', 'bc547750b92797f955b36112cc9bdd5cddf7d0862151d03a167ada8995aa24a9ad24610b36a68bc02da24141ee51670aea13ed6469099a4453f335cb239db5da', 'Teacher', '', '2019-05-13 15:49:22', 0),
(54, 'ctalmadge2@mail.greenriver.edu', 'Christian', 'Talmadge', 'bed4efa1d4fdbd954bd3705d6a2a78270ec9a52ecfbfb010c61862af5c76af1761ffeb1aef6aca1bf5d02b3781aa854fabd2b69c790de74e17ecfec3cb6ac4bf', 'Professional', '', '2019-05-13 17:27:26', 0),
(55, 'chris.david.tal@hotmail.com', 'Christian', 'Talmadge', 'e6c83b282aeb2e022844595721cc00bbda47cb24537c1779f9bb84f04039e1676e6ba8573e588da1052510e3aa0a32a9e55879ae22b0c2d62136fc0a3e85f8bb', 'Professional', 'dsfgdfsgfs', '2019-05-13 17:32:52', 0),
(56, 'sadfsd@sdffd.com', 'adfgsdfg', 'dsfg', '82f76584ba60d4679751d3b30ffb08fdd2ec62870385cdd92bafd1b0dfe5ae2087bd809073640ffb372f74ae144f4311f16c6d0835afd2902b937748d9b6dea7', 'Professional', '', '2019-05-13 18:09:43', 0),
(57, 'areyneeeolds8@mail.greenriver.edu', 'Green', 'College', 'bc547750b92797f955b36112cc9bdd5cddf7d0862151d03a167ada8995aa24a9ad24610b36a68bc02da24141ee51670aea13ed6469099a4453f335cb239db5da', 'Professional', '', '2019-05-16 15:58:26', 0),
(58, 'testemail111@hotmail.com', 'Christian', 'Talmadge', 'e6c83b282aeb2e022844595721cc00bbda47cb24537c1779f9bb84f04039e1676e6ba8573e588da1052510e3aa0a32a9e55879ae22b0c2d62136fc0a3e85f8bb', 'Professional', 'Code', '2019-05-16 18:24:42', 1),
(59, 'chris_david_tal@yahoo.com', 'Christian', 'Talmadge', 'e6c83b282aeb2e022844595721cc00bbda47cb24537c1779f9bb84f04039e1676e6ba8573e588da1052510e3aa0a32a9e55879ae22b0c2d62136fc0a3e85f8bb', 'Teacher', 'Blah', '2019-05-16 18:26:11', 0),
(60, 'test@test.lol', 'Dude', 'McDude', '775e594020c21413288a158b124b29f98b505b638b4d1579ebc05208acf07e0403c930000053d3815a99925f47d29ba05c095cf7a8c296d06907e69a1ff0ba1e', 'Professional', '', '2019-05-16 18:36:55', 0),
(61, 'mario@edu.com', 'Jimbo', 'Mario', '12b03226a6d8be9c6e8cd5e55dc6c7920caaa39df14aab92d5e3ea9340d1c8a4d3d0b8e4314f1f6ef131ba4bf1ceb9186ab87c801af0d5c95b1befb8cedae2b9', 'Teacher', '', '2019-05-16 18:48:09', 0),
(62, 'jheuer@fastmail.net', 'Jonathan', 'Heuer', 'd737df445063ff6feba119ce03eda1ce9527f22b87a0f76317902201209bc0700aa1c20d5a643c3fc0dc4bab1f8236a8f4e533e67bc59fb4d59cf9b052a696e1', 'Professional', '', '2019-05-17 14:15:52', 0),
(63, 'bseyler@mail.greenriver.e', 'Bradbo', '4002', '804F50DDBAAB7F28C933A95C162D019ACBF96AFDE56DBA10E4C7DFCFE453DEC4BACF5E78B1DDBDC1695A793BCB5D7D409425DB4CC3370E71C4965E4EF992E8C4', 'Professional', '', '2019-05-23 21:23:31', 0),
(64, 'areynolds8@l.greenriver.edu', 'aaron', 'eornf', '9edbcc9909492133759197d4b3349df2b41a5f50f1acd54bb7f661302869ddeb888d57d51e3da28462f4b91fb5d5287c7a70cfcf2391a6a9e7d22be1e1cc09cb', 'Teacher', '', '2019-05-28 18:49:31', 0),
(65, '111@gmail.com', 'Green', 'River', 'aeef2a80bc71138e030e1b98666cbc28c2883ffa59d5b86ae1c5d3318c5ebae2db2427f30b76d0789d393b44316e65733038a861f8e8f46aeeea81d8fe030191', 'Professional', '', '2019-05-28 18:50:18', 0),
(66, '112@gmail.com', 'Teacher', 'McTeach', 'aeef2a80bc71138e030e1b98666cbc28c2883ffa59d5b86ae1c5d3318c5ebae2db2427f30b76d0789d393b44316e65733038a861f8e8f46aeeea81d8fe030191', 'Teacher', '', '2019-05-28 18:51:19', 0),
(67, 'birdievids@gmail.com', 'Christian', 'Talmadge', '5262c20731fc3511ceb3016536f9f75d2f60df8799f036d3d3b777bf4db925e028f7054bea18b450479dac17e16778941b0879f5fbfcb5df4588fcc46ad25850', 'Teacher', 'sdf', '2019-05-30 18:48:43', 0),
(68, 'name@email.com', 'Dummy', 'Name', '12b03226a6d8be9c6e8cd5e55dc6c7920caaa39df14aab92d5e3ea9340d1c8a4d3d0b8e4314f1f6ef131ba4bf1ceb9186ab87c801af0d5c95b1befb8cedae2b9', 'Professional', '', '2019-05-30 19:11:19', 0),
(69, 'test@gmail.com', 'Bradley', 'Seyler', '804f50ddbaab7f28c933a95c162d019acbf96afde56dba10e4c7dfcfe453dec4bacf5e78b1ddbdc1695a793bcb5d7d409425db4cc3370e71c4965e4ef992e8c4', 'Professional', '', '2019-05-30 19:43:57', 0),
(70, '113@gmail.com', 'Aaron', 'Reynolds', 'bc547750b92797f955b36112cc9bdd5cddf7d0862151d03a167ada8995aa24a9ad24610b36a68bc02da24141ee51670aea13ed6469099a4453f335cb239db5da', 'Professional', '', '2019-05-30 19:52:24', 0),
(71, 'heuerj@gmail.com', 'Jonathan', 'Teacher', 'd737df445063ff6feba119ce03eda1ce9527f22b87a0f76317902201209bc0700aa1c20d5a643c3fc0dc4bab1f8236a8f4e533e67bc59fb4d59cf9b052a696e1', 'Teacher', 'math teacher', '2019-06-04 18:10:29', 1),
(72, '114@gmail.com', 'Larry', 'Johnson', 'bed4efa1d4fdbd954bd3705d6a2a78270ec9a52ecfbfb010c61862af5c76af1761ffeb1aef6aca1bf5d02b3781aa854fabd2b69c790de74e17ecfec3cb6ac4bf', 'Teacher', '', '2019-06-06 18:01:22', 0),
(73, 'test@testemail.com', 'Jim', 'Notmario', '804f50ddbaab7f28c933a95c162d019acbf96afde56dba10e4c7dfcfe453dec4bacf5e78b1ddbdc1695a793bcb5d7d409425db4cc3370e71c4965e4ef992e8c4', 'Teacher', '', '2019-06-06 21:40:28', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `opportunity`
--
ALTER TABLE `opportunity`
  ADD PRIMARY KEY (`opp_id`),
  ADD KEY `requested_by` (`requested_by`),
  ADD KEY `accepted_speaker` (`accepted_speaker`);

--
-- Indexes for table `pros`
--
ALTER TABLE `pros`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `speaking_requests`
--
ALTER TABLE `speaking_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `opportunity`
--
ALTER TABLE `opportunity`
  MODIFY `opp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `speaking_requests`
--
ALTER TABLE `speaking_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
