-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 28, 2012 at 01:42 PM
-- Server version: 5.5.15
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `albumservice`
--

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

DROP TABLE IF EXISTS `albums`;
CREATE TABLE IF NOT EXISTS `albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artist` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`id`, `artist`, `title`, `quantity`) VALUES
(1, 'Metallica', 'Garage Inc', 4),
(2, 'REM', 'Moster', 2),
(3, 'Faith No More', 'We Care Alot', 1),
(4, 'The Magic Numbers', 'This Love', 1),
(5, 'Chevelle', 'Sci-Fi Crimes', 1),
(6, 'Chevele', 'Vena Sera', 0),
(7, 'Chevele', 'Wonder Whats Next', 2),
(8, 'Anberlin', 'Cities', 1),
(9, 'Anberlin', 'VITAL', 0),
(10, 'Anberlin', 'New Surrender', 1),
(11, 'Slipknot', 'Iowa', 2),
(12, 'Michael Jackson', 'Thriller', 8),
(13, 'Jimmy Eat World', 'Invented', 3),
(14, 'Thousand Foot Krutch', 'Welcome To The Masquerade', 2),
(15, 'The Bouncing Souls', 'The Gold Record', 2),
(16, 'Snow Patrol', 'Fallen Empires', 5),
(17, 'Arcade Fire', 'The Suburbs', 2),
(18, 'The Foo Fighters', 'Greatest Hits', 11);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
