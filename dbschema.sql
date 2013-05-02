-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 02, 2013 at 01:07 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `teen-do-paanch`
--

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE IF NOT EXISTS `game` (
  `gameCode` varchar(8) NOT NULL,
  `turn` text NOT NULL,
  `player1` text NOT NULL,
  `player2` text NOT NULL,
  `player3` text NOT NULL,
  `owner_lastmove` int(20) NOT NULL,
  PRIMARY KEY (`gameCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `server`
--

CREATE TABLE IF NOT EXISTS `server` (
  `ownerExpiry` int(20) NOT NULL,
  `product_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `server`
--

INSERT INTO `server` (`ownerExpiry`, `product_name`) VALUES
(1367499804, 'teen-do-paanch');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `name` text NOT NULL,
  `gameCode` varchar(8) NOT NULL,
  `role` set('player','spectator','candidate') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `ownerExpiry` ON SCHEDULE EVERY 1 MINUTE STARTS '2013-05-02 15:55:24' ON COMPLETION PRESERVE ENABLE DO BEGIN
UPDATE `server` SET `ownerExpiry`=UNIX_TIMESTAMP();
UPDATE `game` SET `player1`=`player2`, `player2`=`player3`, `player3`='' WHERE `owner_lastmove` < UNIX_TIMESTAMP()-300;
DELETE FROM `user` WHERE `gameCode`=(SELECT `gameCode` FROM `game` WHERE `player1`='');
DELETE FROM `game` WHERE `player1`='';
END$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
