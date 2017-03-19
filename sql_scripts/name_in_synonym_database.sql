SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database name:
--
use ics325;
-- --------------------------------------------------------

--
-- Make sure to drop any redundant tables.
--

drop table puzzle_words;
drop table puzzles;
drop table users;
drop table characters;
drop table words;

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
('fm2584uk@metrostate.edu', 'prashant', SHA1('password'), 1, '753951', 0),
('hp6449qy@metrostate.edu', 'tyler', SHA1('password'), 1, '1234', 0);

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

ALTER TABLE words AUTO_INCREMENT=26;
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
(25, 'no', 11);

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

INSERT INTO characters (word_id, character_index, character_value) VALUES
(1, 0, 'm'), (1, 1, 'u'), (1, 2, 't'), (1, 3, 't'), (1, 4, 'e'), (1, 5, 'r'),
(2, 0, 'm'), (2, 1, 'u'), (2, 2, 'm'), (2, 3, 'b'), (2, 4, 'l'), (2, 5, 'e'),
(3, 0, 't'), (3, 1, 'a'), (3, 2, 'k'), (3, 3, 'e'),
(4, 0, 'r'), (4, 1, 'e'), (4, 2, 'm'), (4, 3, 'o'), (4, 4, 'v'), (4, 5, 'e'),
(5, 0, 'a'), (5, 1, 't'), (5, 2, 'r'), (5, 3, 'o'), (5, 4, 'c'), (5, 5, 'i'), (5, 6, 'o'), (5, 7, 'u'), (5, 8, 's'),
(6, 0, 'b'), (6, 1, 'a'), (6, 2, 'd'),
(7, 0, 'a'), (7, 1, 'r'), (7, 2, 'c'), (7, 3, 'h'), (7, 4, 'a'), (7, 5, 'i'), (7, 6, 'c'),
(8, 0, 'o'), (8, 1, 'l'), (8, 2, 'd'),
(9, 0, 'c'), (9, 1, 'o'), (9, 2, 'm'), (9, 3, 'm'), (9, 4, 'e'), (9, 5, 'n'), (9, 6, 'c'), (9, 7, 'e'),
(10, 0, 'b'), (10, 1, 'e'), (10, 2, 'g'), (10, 3, 'i'), (10, 4, 'n'),
(11, 0, 'n'), (11, 1, 'u'), (11, 2, 'm'), (11, 3, 'b'), (11, 4, 'e'), (11, 5, 'r'),
(12, 0, 'a'), (12, 1, 'm'), (12, 2, 'o'), (12, 3, 'u'), (12, 4, 'n'), (12, 5, 't'),
(13, 0, 'm'), (13, 1, 'a'), (13, 2, 't'),
(14, 0, 'm'), (14, 1, 'a'), (14, 2, 'd'),
(15, 0, 't'), (15, 1, '0'), (15, 2, '0'), (15, 3, 'k'),
(16, 0, 'r'), (16, 1, 'u'), (16, 2, 'n'),
(17, 0, 'a'), (17, 1, 'c'), (17, 2, 't'), (17, 3, 'i'), (17, 4, 'o'), (17, 5, 'n'),
(18, 0, 'b'), (18, 1, '0'), (18, 2, 'l'), (18, 3, 'd'),
(19, 0, 'a'), (19, 1, 'r'), (19, 2, 'r'), (19, 3, 'o'), (19, 4, 'w'),
(20, 0, 'o'), (20, 1, 'c'), (20, 2, 'e'),(20, 3, 'a'), (20, 4, 'n'),
(21, 0, 'c'), (21, 1, 'o'), (21, 2, 'm'), (9, 3, 'e'), 
(22, 0, 'b'), (22, 1, 'a'), (22, 2, 'r'), (22, 3, 'g'), (22, 4, 'i'), (22, 5, 'n'),
(23, 0, 'n'), (23, 1, 'u'), (23, 2, 'm'), (23, 3, 'b'),
(24, 0, 'a'), (24, 1, 'i'), (24, 2, 'm'),
(25, 3, 'n'), (25, 1, 'o');

-- --------------------------------------------------------
--
-- Table structure for table puzzles
--

CREATE TABLE puzzles (
  puzzle_id int(11) NOT NULL AUTO_INCREMENT,
  puzzle_name varchar(30) NOT NULL,
  creator_email varchar(255) NOT NULL,
  primary key (puzzle_id, puzzle_name),
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
