-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 21. Jan 2019 um 17:38
-- Server-Version: 8.0.12
-- PHP-Version: 7.1.19

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `uni_students_advice`
--
CREATE DATABASE IF NOT EXISTS `uni_students_advice` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `uni_students_advice`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `abschluss`
--

DROP TABLE IF EXISTS `abschluss`;
CREATE TABLE `abschluss` (
  `Student_id` int(11) NOT NULL,
  `Semester` int(11) NOT NULL,
  `FachEndNote` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hzb`
--

DROP TABLE IF EXISTS `hzb`;
CREATE TABLE `hzb` (
  `Student_id` int(11) NOT NULL,
  `Semester` int(11) DEFAULT NULL,
  `HZBNote` varchar(11) DEFAULT NULL,
  `Art` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hzb_extension`
--

DROP TABLE IF EXISTS `hzb_extension`;
CREATE TABLE `hzb_extension` (
  `Student_id` int(11) NOT NULL,
  `Wechsel` tinyint(1) DEFAULT NULL,
  `Abbruch` tinyint(1) DEFAULT NULL,
  `Durchschnittsnote` decimal(3,2) DEFAULT NULL,
  `EndNote` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `noten`
--

DROP TABLE IF EXISTS `noten`;
CREATE TABLE `noten` (
  `Student_id` int(11) NOT NULL,
  `Unit_id` int(11) NOT NULL,
  `Semester` int(11) NOT NULL,
  `Note` varchar(11) NOT NULL,
  `BNF` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `units`
--

DROP TABLE IF EXISTS `units`;
CREATE TABLE `units` (
  `Unit_id` int(11) NOT NULL,
  `Titel` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `units_equivalence`
--

DROP TABLE IF EXISTS `units_equivalence`;
CREATE TABLE `units_equivalence` (
  `Unit_id` int(11) NOT NULL,
  `Unit_id_2005` varchar(11) DEFAULT NULL,
  `Titel_2005` varchar(255) DEFAULT NULL,
  `Type_2005` varchar(10) DEFAULT NULL,
  `Unit_id_2012` varchar(11) DEFAULT NULL,
  `Titel_2012` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Type_2012` varchar(10) DEFAULT NULL,
  `Unit_id_2017` varchar(11) DEFAULT NULL,
  `Titel_2017` varchar(255) DEFAULT NULL,
  `Type_2017` varchar(10) DEFAULT NULL,
  `Unit_id_final` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `units_extension`
--

DROP TABLE IF EXISTS `units_extension`;
CREATE TABLE `units_extension` (
  `Unit_id` int(11) NOT NULL,
  `Wahlpflicht` tinyint(1) DEFAULT NULL,
  `Plansemester` varchar(5) DEFAULT NULL,
  `Durchschnittsnote` decimal(3,2) DEFAULT NULL,
  `Durchschnittsnote_5` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `units_original`
--

DROP TABLE IF EXISTS `units_original`;
CREATE TABLE `units_original` (
  `Unit_id` int(11) NOT NULL,
  `Titel` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `abschluss`
--
ALTER TABLE `abschluss`
  ADD PRIMARY KEY (`Student_id`);

--
-- Indizes für die Tabelle `hzb`
--
ALTER TABLE `hzb`
  ADD PRIMARY KEY (`Student_id`);

--
-- Indizes für die Tabelle `hzb_extension`
--
ALTER TABLE `hzb_extension`
  ADD PRIMARY KEY (`Student_id`);

--
-- Indizes für die Tabelle `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`Unit_id`);

--
-- Indizes für die Tabelle `units_equivalence`
--
ALTER TABLE `units_equivalence`
  ADD PRIMARY KEY (`Unit_id`);

--
-- Indizes für die Tabelle `units_extension`
--
ALTER TABLE `units_extension`
  ADD PRIMARY KEY (`Unit_id`);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `hzb_extension`
--
ALTER TABLE `hzb_extension`
  ADD CONSTRAINT `hzb_extension_ibfk_1` FOREIGN KEY (`Student_id`) REFERENCES `hzb` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `units_extension`
--
ALTER TABLE `units_extension`
  ADD CONSTRAINT `units_extension_ibfk_1` FOREIGN KEY (`Unit_id`) REFERENCES `units` (`Unit_id`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
