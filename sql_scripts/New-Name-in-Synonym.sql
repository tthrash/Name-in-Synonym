
-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 12, 2017 at 07:48 AM
-- Server version: 5.7.10-log
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
--
use ics325;

-- --------------------------------------------------------

--
-- Table structure for table characters
--

CREATE TABLE characters (
  word_id int(11) NOT NULL COMMENT 'fk',
  character_index int(11) NOT NULL,
  character_value char(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table characters
--

INSERT INTO characters (word_id, character_index, character_value) VALUES
(1, 0, 'm'),
(4, 1, 'e'),
(5, 1, 't'),
(5, 5, 'i'),
(7, 1, 'r'),
(9, 0, 'c'),
(9, 1, 'o'),
(10, 1, 'e'),
(11, 0, 'n');

-- --------------------------------------------------------

--
-- Table structure for table puzzle
--

CREATE TABLE puzzle (
  puzzle_id int(11) NOT NULL,
  puzzle_name varchar(30) NOT NULL,
  user_id int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table puzzle
--

INSERT INTO puzzle (puzzle_id, puzzle_name, user_id) VALUES
(1, 'metro', 1),
(2, 'nice', 1);

-- --------------------------------------------------------

--
-- Table structure for table puzzle_words
--

CREATE TABLE puzzle_words (
  puzzle_id int(11) NOT NULL,
  word_id int(11) NOT NULL,
  position int(11) NOT NULL COMMENT 'position in name'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table puzzle_words
--

INSERT INTO puzzle_words (puzzle_id, word_id, position) VALUES
(1, 1, 0),
(1, 4, 1),
(1, 5, 2),
(1, 7, 3),
(1, 9, 4),
(2, 11, 0),
(2, 5, 1),
(2, 9, 2),
(2, 10, 3);

-- --------------------------------------------------------

--
-- Table structure for table users
--

CREATE TABLE users (
  user_email varchar(255) NOT NULL COMMENT 'email address is the key',
  display_name varchar(15) NOT NULL COMMENT 'if the user doesn''t want to display the user name',
  password char(64) NOT NULL COMMENT 'for storing the password',
  is_verified tinyint(4) NOT NULL COMMENT '0 for false, 1 for true',
  activation_token char(100) NOT NULL COMMENT 'for storing the activation code when the users register or forget password',
  role tinyint(4) NOT NULL COMMENT '0 for ADMIN, 1 for registered user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table users
--

INSERT INTO users (user_email, display_name, password, is_verified, activation_token, role) VALUES
('fm2584uk@metrostate.edu', 'prashant', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 1, '753951', 0),
('hp6449qy@metrostate.edu', 'tyler', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 1, '951357', 0);

-- --------------------------------------------------------

--
-- Table structure for table word
--

CREATE TABLE word (
  word_id int(11) NOT NULL,
  word varchar(25) NOT NULL COMMENT 'words that have been added',
  rep_id int(11) NOT NULL COMMENT 'for storing the ID of the representative'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table word
--

INSERT INTO word (word_id, word, rep_id) VALUES
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
(12, 'amount', 11),
(13, 'mat', 1),
(14, 'mad', 1),
(15, 'took', 3),
(16, 'run', 3),
(17, 'action', 5),
(18, 'bold', 5),
(19, 'arrow', 7),
(20, 'ocean', 7),
(21, 'come', 9),
(22, 'bargin', 9),
(23, 'numb', 11),
(24, 'aim', 11),
(22, 'bold', 9),
(23, 'no', 11);

--
-- Indexes for dumped tables
--

--
-- Indexes for table characters
--
ALTER TABLE characters
  ADD PRIMARY KEY (word_id,character_index);

--
-- Indexes for table puzzle
--
ALTER TABLE puzzle
  ADD PRIMARY KEY (puzzle_id);

--
-- Indexes for table puzzle_words
--
ALTER TABLE puzzle_words
  ADD KEY puzzle_id (puzzle_id),
  ADD KEY word_id (word_id);

--
-- Indexes for table users
--
ALTER TABLE users
  ADD PRIMARY KEY (user_email);

--
-- Indexes for table word
--
ALTER TABLE word
  ADD PRIMARY KEY (word_id);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table puzzle
--
ALTER TABLE puzzle
  MODIFY puzzle_id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table word
--
ALTER TABLE word
  MODIFY word_id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- Constraints for dumped tables
--

--
-- Constraints for table characters
--
ALTER TABLE characters
  ADD CONSTRAINT word_id_fk FOREIGN KEY (word_id) REFERENCES word (word_id) ON UPDATE CASCADE;

--
-- Constraints for table puzzle_words
--
ALTER TABLE puzzle_words
  ADD CONSTRAINT puzzle_id_fk FOREIGN KEY (puzzle_id) REFERENCES puzzle (puzzle_id) ON UPDATE CASCADE,
  ADD CONSTRAINT word_idfk FOREIGN KEY (word_id) REFERENCES word (word_id) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;