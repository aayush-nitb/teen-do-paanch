-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 19, 2013 at 10:58 PM
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

DROP TABLE IF EXISTS `game`;
CREATE TABLE `game` (
  `gameCode` varchar(8) NOT NULL,
  `turn` text NOT NULL,
  `player1` text NOT NULL,
  `player2` text NOT NULL,
  `player3` text NOT NULL,
  `owner_lastmove` int(20) NOT NULL,
  `spade1` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `spade7` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `spade8` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `spade9` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `spade10` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `spade11` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `spade12` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `spade13` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `heart1` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `heart7` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `heart8` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `heart9` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `heart10` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `heart11` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `heart12` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `heart13` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `club1` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `club8` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `club9` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `club10` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `club11` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `club12` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `club13` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `diamond1` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `diamond8` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `diamond9` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `diamond10` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `diamond11` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `diamond12` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `diamond13` set('player1','player2','player3','current_trick','trick1','trick2','trick3','trick4','trick5','trick6','trick7','trick8','trick9','trick10') NOT NULL,
  `trick1` set('player1','player2','player3') DEFAULT NULL,
  `trick2` set('player1','player2','player3') DEFAULT NULL,
  `trick3` set('player1','player2','player3') DEFAULT NULL,
  `trick4` set('player1','player2','player3') DEFAULT NULL,
  `trick5` set('player1','player2','player3') DEFAULT NULL,
  `trick6` set('player1','player2','player3') DEFAULT NULL,
  `trick7` set('player1','player2','player3') DEFAULT NULL,
  `trick8` set('player1','player2','player3') DEFAULT NULL,
  `trick9` set('player1','player2','player3') DEFAULT NULL,
  `trick10` set('player1','player2','player3') DEFAULT NULL,
  PRIMARY KEY (`gameCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `server`
--

DROP TABLE IF EXISTS `server`;
CREATE TABLE `server` (
  `ownerExpiry` int(20) NOT NULL,
  `product_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `server`
--

INSERT INTO `server` (`ownerExpiry`, `product_name`) VALUES
(1369004244, 'teen-do-paanch');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `name` text NOT NULL,
  `gameCode` varchar(8) NOT NULL,
  `role` set('player','spectator') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DELIMITER $$
--
-- Events
--
DROP EVENT `ownerExpiry`$$
CREATE DEFINER=`root`@`localhost` EVENT `ownerExpiry` ON SCHEDULE EVERY 1 MINUTE STARTS '2013-05-02 15:55:24' ON COMPLETION PRESERVE ENABLE DO BEGIN
UPDATE `server` SET `ownerExpiry`=UNIX_TIMESTAMP();
UPDATE `game` SET `player1`=`player2`, `player2`=`player3`, `player3`='' WHERE `owner_lastmove` < UNIX_TIMESTAMP()-3600;
DELETE FROM `game` WHERE `player1`='';
DELETE FROM `user` WHERE NOT EXISTS (SELECT * FROM `game` WHERE `game`.`gameCode`=`user`.`gameCode`);
END$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
