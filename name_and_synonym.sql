-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2017 at 04:41 AM
-- Server version: 5.7.10-log
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `name_and_synonym`
--

-- --------------------------------------------------------

--
-- Table structure for table `logical_chars`
--

CREATE TABLE `logical_chars` (
  `id` int(11) NOT NULL,
  `word` varchar(25) NOT NULL,
  `logical_char` varchar(25) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `puzzles`
--

CREATE TABLE `puzzles` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `word_list` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `puzzles`
--

INSERT INTO `puzzles` (`id`, `name`, `word_list`, `created_by`) VALUES
(1, 'metro', 'mutter,remove,atrocious,archaic,commence', 'fm2584uk@metrostate.edu'),
(2, 'nice', 'number,archaic,commence,remove', 'hp6449qy@metrostate.edu');

-- --------------------------------------------------------

--
-- Table structure for table `registered_users`
--

CREATE TABLE `registered_users` (
  `user_email` varchar(255) NOT NULL,
  `display_name` varchar(15) NOT NULL,
  `password` char(64) NOT NULL,
  `id_verified` tinyint(4) NOT NULL,
  `activation_token` char(100) NOT NULL,
  `role` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `registered_users`
--

INSERT INTO `registered_users` (`user_email`, `display_name`, `password`, `id_verified`, `activation_token`, `role`) VALUES
('fm2584uk@metrostate.edu', 'prashant', '', 1, '753951', 0),
('hp6449qy@metrostate.edu', 'tyler', '', 1, '1234', 0);

-- --------------------------------------------------------

--
-- Table structure for table `synonyms`
--

CREATE TABLE `synonyms` (
  `id` int(11) NOT NULL,
  `word` varchar(25) NOT NULL,
  `rep_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `synonyms`
--

INSERT INTO `synonyms` (`id`, `word`, `rep_id`) VALUES
(1, 'mutter', 1),
(2, 'mumble', 1),
(3, 'take', 3),
(4, 'remove', 3),
(5, 'atrocious', 5),
(6, 'bad', 5),
(7, 'archaic', 7),
(8, 'old', 7),
(9, 'commence', 9),
(10, 'begin', 9),
(11, 'number', 11),
(12, 'amount', 11);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logical_chars`
--
ALTER TABLE `logical_chars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `puzzles`
--
ALTER TABLE `puzzles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `registered_users`
--
ALTER TABLE `registered_users`
  ADD PRIMARY KEY (`user_email`);

--
-- Indexes for table `synonyms`
--
ALTER TABLE `synonyms`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logical_chars`
--
ALTER TABLE `logical_chars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `puzzles`
--
ALTER TABLE `puzzles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `synonyms`
--
ALTER TABLE `synonyms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `puzzles`
--
ALTER TABLE `puzzles`
  ADD CONSTRAINT `user_email_fk` FOREIGN KEY (`created_by`) REFERENCES `registered_users` (`user_email`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
