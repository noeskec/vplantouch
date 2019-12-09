-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 14. Feb 2017 um 16:59
-- Server-Version: 10.1.19-MariaDB
-- PHP-Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `webscheduler`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `metadata`
--

CREATE TABLE `metadata` (
  `untisTimeStamp` DATETIME NOT NULL,
  `lastFetchTimeStamp` DATETIME NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `forms`
--

CREATE TABLE `forms` (
  `id` int(11) NOT NULL,
  `utid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `fullname` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `utid` int(11) NOT NULL,
  `name` varchar(8) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `building` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `schoolyear`
--

CREATE TABLE `schoolyear` (
  `id` int(11) NOT NULL,
  `utid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `startDate` int(15) NOT NULL,
  `endDate` int(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `utid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `fullname` varchar(40) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `utid` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `timetables`
--

CREATE TABLE `timetables` (
  `id` int(11) NOT NULL,
  `startTime` int(15) NOT NULL,
  `endTime` int(15) NOT NULL,
  `date` int(15) NOT NULL,
  `is_replacement` tinyint(1) DEFAULT NULL,
  `is_removed` tinyint(1) DEFAULT NULL,
  `is_exam` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ttforms`
--

CREATE TABLE `ttforms` (
  `id` int(11) NOT NULL,
  `timetableId` int(11) NOT NULL,
  `formId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ttrooms`
--

CREATE TABLE `ttrooms` (
  `id` int(11) NOT NULL,
  `timetableId` int(11) NOT NULL,
  `roomId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ttsubjects`
--

CREATE TABLE `ttsubjects` (
  `id` int(11) NOT NULL,
  `timetableId` int(11) NOT NULL,
  `subjectId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ttteachers`
--

CREATE TABLE `ttteachers` (
  `id` int(11) NOT NULL,
  `timetableId` int(11) NOT NULL,
  `teacherId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `metadata`
--
ALTER TABLE `metadata`
  ADD PRIMARY KEY (`untisTimeStamp`);

--
-- Indizes für die Tabelle `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `schoolyear`
--
ALTER TABLE `schoolyear`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `timetables`
--
ALTER TABLE `timetables`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `ttforms`
--
ALTER TABLE `ttforms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timetableId` (`timetableId`),
  ADD KEY `formId` (`formId`);

--
-- Indizes für die Tabelle `ttrooms`
--
ALTER TABLE `ttrooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timetableId` (`timetableId`),
  ADD KEY `roomId` (`roomId`);

--
-- Indizes für die Tabelle `ttsubjects`
--
ALTER TABLE `ttsubjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subjectId` (`subjectId`),
  ADD KEY `timetableId` (`timetableId`);

--
-- Indizes für die Tabelle `ttteachers`
--
ALTER TABLE `ttteachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timetableId` (`timetableId`),
  ADD KEY `teacherId` (`teacherId`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;
--
-- AUTO_INCREMENT für Tabelle `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;
--
-- AUTO_INCREMENT für Tabelle `schoolyear`
--
ALTER TABLE `schoolyear`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT für Tabelle `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=344;
--
-- AUTO_INCREMENT für Tabelle `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;
--
-- AUTO_INCREMENT für Tabelle `timetables`
--
ALTER TABLE `timetables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16959;
--
-- AUTO_INCREMENT für Tabelle `ttforms`
--
ALTER TABLE `ttforms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39175;
--
-- AUTO_INCREMENT für Tabelle `ttrooms`
--
ALTER TABLE `ttrooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17276;
--
-- AUTO_INCREMENT für Tabelle `ttsubjects`
--
ALTER TABLE `ttsubjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16929;
--
-- AUTO_INCREMENT für Tabelle `ttteachers`
--
ALTER TABLE `ttteachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17101;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
