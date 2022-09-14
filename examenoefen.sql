-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 14 sep 2022 om 22:44
-- Serverversie: 10.4.24-MariaDB
-- PHP-versie: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `examenoefen`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `aanmeldingen`
--

CREATE TABLE `aanmeldingen` (
  `aanmelding_id` int(10) NOT NULL,
  `speler_id` int(10) NOT NULL,
  `toernooi_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `scholen`
--

CREATE TABLE `scholen` (
  `school_id` int(10) NOT NULL,
  `schoolnaam` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `scholen`
--

INSERT INTO `scholen` (`school_id`, `schoolnaam`) VALUES
(1, 'ROC van Amsterdam'),
(2, 'Media College'),
(3, 'TRIAS');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `spelers`
--

CREATE TABLE `spelers` (
  `speler_id` int(10) NOT NULL,
  `voornaam` varchar(50) NOT NULL,
  `tussenvoegsel` varchar(20) DEFAULT NULL,
  `achternaam` varchar(50) NOT NULL,
  `school_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `spelers`
--

INSERT INTO `spelers` (`speler_id`, `voornaam`, `tussenvoegsel`, `achternaam`, `school_id`) VALUES
(1, 'Hannah', 'de', 'Wit', 2),
(2, 'Linda', '', 'Jansen', 1),
(3, 'Linah', '', 'Willemse', 1),
(4, 'Jurre', 'de', 'Vries', 2),
(5, 'Wim', '', 'Mol', 2),
(6, 'Piet', '', 'Heijn', 2),
(7, 'Albert', '', 'Heijn', 1),
(8, 'Bert', 'de', 'Groot', 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `toernooi`
--

CREATE TABLE `toernooi` (
  `toernooi_id` int(10) NOT NULL,
  `toernooi_naam` varchar(50) NOT NULL,
  `omschrijving` varchar(100) DEFAULT NULL,
  `datum` datetime DEFAULT current_timestamp(),
  `winnaar_id` int(10) DEFAULT NULL,
  `afgesloten` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `toernooi`
--

INSERT INTO `toernooi` (`toernooi_id`, `toernooi_naam`, `omschrijving`, `datum`, `winnaar_id`, `afgesloten`) VALUES
(1, 'Toernooi1', 'Voor alle studenten', '2022-09-13 14:54:04', 1, 1),
(2, 'Toernooi2', '', '2022-09-13 14:57:46', NULL, 0),
(3, 'Toernooi3', '', '2022-09-13 15:01:30', NULL, 0),
(4, 'Testtoernooi', 'Dit is een test', '2022-09-13 15:02:37', NULL, 0),
(5, 'Testtoernooi2', 'Dit is om te testen', '2022-09-13 15:03:24', NULL, 0),
(6, 'Toernooitje', '', '2022-09-13 15:14:50', 4, 1),
(7, 'twhsete', '', '2022-09-13 15:17:00', NULL, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `wedstrijd`
--

CREATE TABLE `wedstrijd` (
  `wedstrijd_id` int(10) NOT NULL,
  `toernooi_id` int(10) NOT NULL,
  `ronde` int(11) NOT NULL,
  `speler1_id` int(10) DEFAULT NULL,
  `speler2_id` int(10) DEFAULT NULL,
  `score1` int(1) DEFAULT NULL,
  `score2` int(1) DEFAULT NULL,
  `winnaar_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `worker`
--

CREATE TABLE `worker` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `user_pwd` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `worker`
--

INSERT INTO `worker` (`id`, `user_id`, `user_pwd`, `user_email`) VALUES
(1, 'naomi', 'ww', 'test@live.nl');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `aanmeldingen`
--
ALTER TABLE `aanmeldingen`
  ADD PRIMARY KEY (`aanmelding_id`),
  ADD KEY `speler_id` (`speler_id`),
  ADD KEY `toernooi_id` (`toernooi_id`);

--
-- Indexen voor tabel `scholen`
--
ALTER TABLE `scholen`
  ADD PRIMARY KEY (`school_id`);

--
-- Indexen voor tabel `spelers`
--
ALTER TABLE `spelers`
  ADD PRIMARY KEY (`speler_id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexen voor tabel `toernooi`
--
ALTER TABLE `toernooi`
  ADD PRIMARY KEY (`toernooi_id`),
  ADD KEY `winnaar_id` (`winnaar_id`);

--
-- Indexen voor tabel `wedstrijd`
--
ALTER TABLE `wedstrijd`
  ADD PRIMARY KEY (`wedstrijd_id`),
  ADD KEY `toernooi_id` (`toernooi_id`),
  ADD KEY `speler1_id` (`speler1_id`),
  ADD KEY `speler2_id` (`speler2_id`),
  ADD KEY `winnaar_id` (`winnaar_id`);

--
-- Indexen voor tabel `worker`
--
ALTER TABLE `worker`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `aanmeldingen`
--
ALTER TABLE `aanmeldingen`
  MODIFY `aanmelding_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT voor een tabel `scholen`
--
ALTER TABLE `scholen`
  MODIFY `school_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `spelers`
--
ALTER TABLE `spelers`
  MODIFY `speler_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT voor een tabel `toernooi`
--
ALTER TABLE `toernooi`
  MODIFY `toernooi_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `wedstrijd`
--
ALTER TABLE `wedstrijd`
  MODIFY `wedstrijd_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT voor een tabel `worker`
--
ALTER TABLE `worker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `aanmeldingen`
--
ALTER TABLE `aanmeldingen`
  ADD CONSTRAINT `aanmeldingen_ibfk_1` FOREIGN KEY (`toernooi_id`) REFERENCES `toernooi` (`toernooi_id`),
  ADD CONSTRAINT `aanmeldingen_ibfk_2` FOREIGN KEY (`speler_id`) REFERENCES `spelers` (`speler_id`);

--
-- Beperkingen voor tabel `spelers`
--
ALTER TABLE `spelers`
  ADD CONSTRAINT `spelers_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `scholen` (`school_id`);

--
-- Beperkingen voor tabel `toernooi`
--
ALTER TABLE `toernooi`
  ADD CONSTRAINT `toernooi_ibfk_1` FOREIGN KEY (`winnaar_id`) REFERENCES `spelers` (`speler_id`);

--
-- Beperkingen voor tabel `wedstrijd`
--
ALTER TABLE `wedstrijd`
  ADD CONSTRAINT `wedstrijd_ibfk_1` FOREIGN KEY (`toernooi_id`) REFERENCES `toernooi` (`toernooi_id`),
  ADD CONSTRAINT `wedstrijd_ibfk_2` FOREIGN KEY (`speler1_id`) REFERENCES `spelers` (`speler_id`),
  ADD CONSTRAINT `wedstrijd_ibfk_3` FOREIGN KEY (`speler2_id`) REFERENCES `spelers` (`speler_id`),
  ADD CONSTRAINT `wedstrijd_ibfk_4` FOREIGN KEY (`winnaar_id`) REFERENCES `spelers` (`speler_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
