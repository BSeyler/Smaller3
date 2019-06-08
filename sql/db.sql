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
--This file contains the basic table structure for the WTIA site without sample data

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
