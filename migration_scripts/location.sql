-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Temps de generació: 15-01-2014 a les 10:42:22
-- Versió del servidor: 5.5.34
-- Versió de PHP : 5.3.10-1ubuntu3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de dades: `ebre_escool`
--

-- --------------------------------------------------------

--
-- Estructura de la taula `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `location_Id` int(11) NOT NULL AUTO_INCREMENT,
  `location_name` varchar(150) NOT NULL,
  `location_shortName` varchar(150) NOT NULL,
  `location_description` text,
  `location_entryDate` datetime NOT NULL,
  `location_last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `location_creationUserId` int(11) DEFAULT NULL,
  `location_lastupdateUserId` int(11) DEFAULT NULL,
  `location_parentLocation` int(11) DEFAULT NULL,
  `location_markedForDeletion` enum('n','y') NOT NULL,
  `location_markedForDeletionDate` datetime NOT NULL,
  PRIMARY KEY (`location_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
