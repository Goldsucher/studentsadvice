-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 12. Nov 2018 um 17:31
-- Server-Version: 8.0.12
-- PHP-Version: 7.1.19

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

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `abschluss`
--

DROP TABLE IF EXISTS `abschluss`;
CREATE TABLE `abschluss` (
  `ID` int(11) NOT NULL,
  `Semester` int(11) NOT NULL,
  `FachEndNote` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hzb`
--

DROP TABLE IF EXISTS `hzb`;
CREATE TABLE `hzb` (
  `ID` int(11) NOT NULL,
  `Semester` int(11) DEFAULT NULL,
  `HZBNote` int(11) DEFAULT NULL,
  `Art` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `noten`
--

DROP TABLE IF EXISTS `noten`;
CREATE TABLE `noten` (
  `ID` int(11) NOT NULL,
  `Unit` int(11) NOT NULL,
  `Semester` int(11) NOT NULL,
  `Note` varchar(10) NOT NULL,
  `BNF` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `units`
--

DROP TABLE IF EXISTS `units`;
CREATE TABLE `units` (
  `Unit` int(11) NOT NULL,
  `Titel` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `abschluss`
--
ALTER TABLE `abschluss`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `hzb`
--
ALTER TABLE `hzb`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`Unit`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
