-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Čtv 16. lis 2017, 00:20
-- Verze MySQL: 5.6.33
-- Verze PHP: 5.3.29

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `xmatej52`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `iis_h_objednavka`
--

CREATE TABLE IF NOT EXISTS `iis_h_objednavka` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `cas` date NOT NULL,
  `poznamka` varchar(1024) COLLATE latin2_czech_cs DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `iis_h_potravina`
--

CREATE TABLE IF NOT EXISTS `iis_h_potravina` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `druh` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `doba_pripravy` int(11) DEFAULT NULL,
  `popis_pripravy` varchar(4000) COLLATE latin2_czech_cs DEFAULT NULL,
  `cena` float NOT NULL,
  `prodano_kusu` int(11) NOT NULL,
  `talir` varchar(128) COLLATE latin2_czech_cs DEFAULT NULL,
  `sklenice` varchar(128) COLLATE latin2_czech_cs DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `iis_h_rezervace`
--

CREATE TABLE IF NOT EXISTS `iis_h_rezervace` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `cas` date NOT NULL,
  `zamestnanec` int(11) NOT NULL,
  `jmeno_obj` varchar(64) COLLATE latin2_czech_cs NOT NULL,
  `kontakt_obj` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `jmeno_zak` varchar(64) COLLATE latin2_czech_cs DEFAULT NULL,
  `kontakt_zak` varchar(128) COLLATE latin2_czech_cs DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `iis_h_stul`
--

CREATE TABLE IF NOT EXISTS `iis_h_stul` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `lokace` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `pocet_zidli` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `iis_h_surovina`
--

CREATE TABLE IF NOT EXISTS `iis_h_surovina` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `alergeny` varchar(128) COLLATE latin2_czech_cs DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `jmeno` (`jmeno`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `iis_h_uctenka`
--

CREATE TABLE IF NOT EXISTS `iis_h_uctenka` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `datum` date NOT NULL,
  `zaloha` int(11) DEFAULT NULL,
  `suma` int(11) NOT NULL,
  `objednavka` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `iis_h_uzivatele`
--

CREATE TABLE IF NOT EXISTS `iis_h_uzivatele` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `prijmeni` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `email` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `heslo` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `mesto` varchar(128) COLLATE latin2_czech_cs DEFAULT NULL,
  `ulice` varchar(128) COLLATE latin2_czech_cs DEFAULT NULL,
  `cislo_popisne` varchar(128) COLLATE latin2_czech_cs DEFAULT NULL,
  `psc` int(5) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `iis_h_zamestnanec`
--

CREATE TABLE IF NOT EXISTS `iis_h_zamestnanec` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `pracovni_pozice` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `rodne_cislo` int(11) NOT NULL,
  `cislo_uctu` int(11) DEFAULT NULL,
  `plat` int(11) NOT NULL,
  `uzivatel` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `iis_s_objednavka_potravina`
--

CREATE TABLE IF NOT EXISTS `iis_s_objednavka_potravina` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `objednavka` int(11) NOT NULL,
  `potravina` int(11) NOT NULL,
  `mnozstvi` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `iis_s_potravina_surovina`
--

CREATE TABLE IF NOT EXISTS `iis_s_potravina_surovina` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `potravina` int(11) NOT NULL,
  `surovina` int(11) NOT NULL,
  `mnozstvi` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `iis_s_rezervace_stul`
--

CREATE TABLE IF NOT EXISTS `iis_s_rezervace_stul` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `rezervace` int(11) NOT NULL,
  `stul` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `itu_h_firmy`
--

CREATE TABLE IF NOT EXISTS `itu_h_firmy` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `ulice` varchar(128) COLLATE latin2_czech_cs DEFAULT NULL,
  `cislo_domu` varchar(128) COLLATE latin2_czech_cs DEFAULT NULL,
  `psc` int(5) DEFAULT NULL,
  `mesto` varchar(128) COLLATE latin2_czech_cs DEFAULT NULL,
  `telefon` int(9) DEFAULT NULL,
  `email` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `itu_h_mesta`
--

CREATE TABLE IF NOT EXISTS `itu_h_mesta` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `itu_h_obory`
--

CREATE TABLE IF NOT EXISTS `itu_h_obory` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `itu_h_podobory`
--

CREATE TABLE IF NOT EXISTS `itu_h_podobory` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `obor` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `itu_h_prace`
--

CREATE TABLE IF NOT EXISTS `itu_h_prace` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `zadavatel` int(11) NOT NULL,
  `plat` varchar(39) COLLATE latin2_czech_cs DEFAULT NULL,
  `zadani_strucne` varchar(4000) COLLATE latin2_czech_cs NOT NULL,
  `zadani_odkaz` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `casova_narocnost` varchar(128) COLLATE latin2_czech_cs DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `itu_h_skoly`
--

CREATE TABLE IF NOT EXISTS `itu_h_skoly` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(128) COLLATE latin2_czech_cs DEFAULT NULL,
  `mesto` int(11) NOT NULL,
  `email` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `itu_h_studenti`
--

CREATE TABLE IF NOT EXISTS `itu_h_studenti` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `prijmeni` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `telefon` int(9) DEFAULT NULL,
  `email` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `skola` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `itu_h_ucty`
--

CREATE TABLE IF NOT EXISTS `itu_h_ucty` (
  `login` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `heslo` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs;

-- --------------------------------------------------------

--
-- Struktura tabulky `itu_s_firmy_mesta`
--

CREATE TABLE IF NOT EXISTS `itu_s_firmy_mesta` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `firma` int(11) NOT NULL,
  `mesto` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `itu_s_prace_mesta`
--

CREATE TABLE IF NOT EXISTS `itu_s_prace_mesta` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `mesto` int(11) NOT NULL,
  `prace` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `itu_s_prace_skoly`
--

CREATE TABLE IF NOT EXISTS `itu_s_prace_skoly` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `skola` int(11) NOT NULL,
  `prace` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `itu_s_prace_studenti`
--

CREATE TABLE IF NOT EXISTS `itu_s_prace_studenti` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `prace` int(11) NOT NULL,
  `student` int(11) NOT NULL,
  `zaregistrovano` int(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `itu_s_skoly_obory`
--

CREATE TABLE IF NOT EXISTS `itu_s_skoly_obory` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `skola` int(11) NOT NULL,
  `obor` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
