SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: name_and_synonym
--
use ics325;
-- --------------------------------------------------------

--
-- Make sure to drop any redundant tables.
--
-- drop table puzzle_words;
-- drop table puzzles;
-- drop table users;
-- drop table characters;
-- drop table words;

-- --------------------------------------------------------
--
-- Table structure for table users
--

CREATE TABLE users (
  user_email varchar(255) COMMENT 'email address is the key',
  display_name varchar(25) NOT NULL COMMENT 'if the user doesn''t want to display the user name',
  password varchar(64) NOT NULL COMMENT 'for storing the password',
  id_verified tinyint(1) NOT NULL COMMENT '0 for false, 1 for true',
  activation_token varchar(15) NOT NULL  COMMENT 'for storing the activation code when the users register or forget password',
  role tinyint(1) NOT NULL COMMENT '0 for ADMIN, 1 for registered user',
  primary key (user_email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table users
--

INSERT INTO users (user_email, display_name, password, id_verified, activation_token, role) VALUES
('fm2584uk@metrostate.edu', 'prashant', '', 1, '753951', 0),
('hp6449qy@metrostate.edu', 'tyler', '', 1, '1234', 0);

-- --------------------------------------------------------
--
-- Table structure for table word
--

CREATE TABLE words (
  word_id int(11) AUTO_INCREMENT,
  word_value varchar(25) NOT NULL COMMENT 'words that have been added',
  rep_id int(11) NOT NULL COMMENT 'for storing the ID of the representative',
  primary key (word_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE words AUTO_INCREMENT=13;
ALTER TABLE words
  ADD CONSTRAINT rep_id_fk FOREIGN KEY (rep_id) REFERENCES words (word_id) ON UPDATE CASCADE;

--
-- Dumping data for table word
--

INSERT INTO words (word_id, word_value, rep_id) VALUES
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

-- --------------------------------------------------------
--
-- Table structure for table characters
--

CREATE TABLE characters (
  word_id int(11),
  character_index smallint(25) NOT NULL,
  character_value char(1) NOT NULL,
  primary key (word_id, character_index, character_value)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE characters
  ADD CONSTRAINT word_id_fk FOREIGN KEY (word_id) REFERENCES words (word_id) ON UPDATE CASCADE;
  
-- --------------------------------------------------------
--
-- Table structure for table puzzles
--

CREATE TABLE puzzles (
  puzzle_id int(11) NOT NULL AUTO_INCREMENT,
  puzzle_name varchar(30) NOT NULL,
  creator_email varchar(255) NOT NULL,
  primary key (puzzle_id),
  key (creator_email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE words AUTO_INCREMENT=3;
ALTER TABLE puzzles
  ADD CONSTRAINT creator_email_fk FOREIGN KEY (creator_email) REFERENCES users (user_email) ON UPDATE CASCADE;
--
-- Dumping data for table puzzles
--

INSERT INTO puzzles (puzzle_id, puzzle_name, creator_email) VALUES
(1, 'metro', 'fm2584uk@metrostate.edu'),
(2, 'nice', 'hp6449qy@metrostate.edu');

-- --------------------------------------------------------
--
-- Table structure for table puzzle_words
--

CREATE TABLE puzzle_words (
  puzzle_id int(11),
  word_id int(11),
  position_inName smallint(25),
  primary key (puzzle_id, word_id, position_inName),
  FOREIGN KEY (word_id)
  REFERENCES words (word_id) ON UPDATE CASCADE,
  key (puzzle_id),
  key (word_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE puzzle_words
  ADD CONSTRAINT puzzle_id_fk FOREIGN KEY (puzzle_id) REFERENCES puzzles (puzzle_id) ON UPDATE CASCADE;
--
-- Dumping data for table puzzle_words
--
-- 'mutter,remove,atrocious,archaic,commence'
-- 'number,archaic,commence,remove'

INSERT INTO puzzle_words (puzzle_id, word_id, position_inName) VALUES
(1,1,0),
(1,4,1),
(1,5,2),
(1,7,3),
(1,9,4),
(2,11,0),
(2,7,1),
(2,9,2),
(2,4,3);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
