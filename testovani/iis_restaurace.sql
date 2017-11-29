-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Stř 29. lis 2017, 02:42
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
  `url_obrazku` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `popis` varchar(256) COLLATE latin2_czech_cs NOT NULL,
  `poradi` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=24 ;

--
-- Vypisuji data pro tabulku `iis_h_potravina`
--

INSERT INTO `iis_h_potravina` (`ID`, `jmeno`, `druh`, `doba_pripravy`, `popis_pripravy`, `cena`, `prodano_kusu`, `talir`, `sklenice`, `url_obrazku`, `popis`, `poradi`) VALUES
(2, 'Losos se smetanovo-rajčatovou omáčkou s těstovinami', 'Rybí pokrmy', 60, NULL, 179.9, 5, 'Velký mělký talíř', NULL, 'https://kuchynelidlu.cz/img/CZ/653x414/201611151007-farfalle-s-rajcatovou-omackou-a-opecenym-lososem_Roman.jpg', 'Pečený losos se smetanovo-rajčatovou omáčkou podávaný s vařenými těstovinami', 40),
(5, 'Kapr smažený', 'Rybí pokrmy', 15, NULL, 100, 20, 'Velký mělký talíř', NULL, '', 'Kapr smažený v těstíčku', 40),
(7, 'Pstruh smažený', 'Rybí pokrmy', 15, NULL, 100, 9, 'Velký mělký talíř', NULL, '', 'Pstruh smažený v těstíčku', 40),
(8, 'Pstruh na roštu', 'Rybí pokrmy', 25, NULL, 120, 10, 'Velký mělký talíř', NULL, '', 'Pstruh pomalu pečený na roštu', 40),
(9, 'Kapr na modro', 'Rybí pokrmy', 25, NULL, 140, 2, 'Velký mělký talíř', NULL, '', 'Kapr dušený na zeleninou, brambory', 40),
(23, 'Rýže', 'Příloha', 12, 'Vařit vodu. Přidat opláchnutou rýži a vařit 12 minut. Zcedit a podávat.', 20, 2, 'Malý mělký talíř', NULL, './pictures', 'Rýže vařená', 100);

-- --------------------------------------------------------

--
-- Struktura tabulky `iis_h_rezervace`
--

CREATE TABLE IF NOT EXISTS `iis_h_rezervace` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `odkdy` datetime NOT NULL,
  `dokdy` datetime NOT NULL,
  `zamestnanec` int(11) NOT NULL,
  `jmeno` varchar(128) COLLATE latin2_czech_cs DEFAULT NULL,
  `kontakt` varchar(256) COLLATE latin2_czech_cs DEFAULT NULL,
  `uzivatel` int(11) DEFAULT NULL,
  `stul` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `zamestnanec` (`zamestnanec`),
  KEY `uzivatel` (`uzivatel`),
  KEY `stul_rezervovany` (`stul`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=174 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `iis_h_stul`
--

CREATE TABLE IF NOT EXISTS `iis_h_stul` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `lokace` varchar(128) COLLATE latin2_czech_cs NOT NULL,
  `pocet_zidli` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=48 ;

--
-- Vypisuji data pro tabulku `iis_h_stul`
--

INSERT INTO `iis_h_stul` (`ID`, `lokace`, `pocet_zidli`) VALUES
(1, 'zahrádka', 6),
(2, 'zahrádka', 6),
(3, 'zahrádka', 6),
(4, 'zahrádka', 8),
(5, 'zahrádka', 8),
(6, 'zahrádka', 12),
(7, 'malá místnost u zahrádky', 4),
(8, 'malá místnost u zahrádky', 4),
(9, 'místnost s barem', 6),
(10, 'místnost s barem', 6),
(11, 'místnost s barem', 6),
(12, 'jídelna', 6),
(13, 'jídelna', 6),
(14, 'jídelna', 6),
(15, 'jídelna', 2),
(16, 'jídelna', 8),
(17, 'jídelna', 8),
(18, 'jídelna', 4);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=30 ;

--
-- Vypisuji data pro tabulku `iis_h_surovina`
--

INSERT INTO `iis_h_surovina` (`ID`, `jmeno`, `alergeny`) VALUES
(1, 'Mléko', 'mléko'),
(2, 'Okoun', 'ryby'),
(3, 'Candát', 'ryby'),
(4, 'Úhoř', 'ryby'),
(5, 'Pstruh', 'ryby'),
(6, 'Kapr', 'ryby'),
(7, 'Tolstolobik', 'ryby'),
(8, 'Treska', 'ryby'),
(9, 'Losos', 'ryby'),
(10, 'Jeseter', 'ryby'),
(11, 'Máslo', 'mléko'),
(12, 'Rajčata', 'rajčata'),
(13, 'Smetana', 'mléko'),
(14, 'Sůl', NULL),
(15, 'Celer', 'celer'),
(16, 'Hořčice', 'hořčice'),
(17, 'Brambory', NULL),
(18, 'Těstoviny', 'lepek'),
(19, 'Vejce', 'vejce'),
(20, 'Mouka hladká', 'lepek'),
(21, 'Houska strouhaná', 'lepek'),
(22, 'Olej', NULL),
(23, 'Petržel', 'petržel'),
(24, 'Mrkev', 'mrkev'),
(26, 'Cibule', 'cibule'),
(27, 'Ocet', 'ocet'),
(28, 'Okurka', NULL),
(29, 'Rýže', 'rýže');

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
  PRIMARY KEY (`ID`),
  KEY `objednavka2` (`objednavka`)
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
  `pravo` varchar(16) COLLATE latin2_czech_cs NOT NULL DEFAULT 'zakaznik',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=31 ;

--
-- Vypisuji data pro tabulku `iis_h_uzivatele`
--

INSERT INTO `iis_h_uzivatele` (`ID`, `jmeno`, `prijmeni`, `email`, `heslo`, `mesto`, `ulice`, `cislo_popisne`, `psc`, `pravo`) VALUES
(1, 'Boris', 'Brambora', 'test01@ether123.net', 'test01', 'Brno', 'Vídeňská', '96', 63900, 'masterchef'),
(2, 'Jan', 'Ananas', 'test02@ether123.net', 'test02', 'Plzeň', 'Mikulášské nám. ', '23', 32600, 'zakaznik'),
(3, 'Michal', 'Jablko', 'test03@ether123.net', 'test03', 'Olomouc', 'tř. Míru', '104', 77900, 'zakaznik'),
(4, 'Petr', 'Hruška', 'test04@ether123.net', 'test04', 'Znojmo', 'Brněnská', '21', 66902, 'zakaznik'),
(5, 'Jana', 'Třešničková', 'test05@ether123.net', 'test05', 'Lednice', 'Mikulovská', '139', 69144, 'zakaznik'),
(6, 'Soňa', 'Višňová', 'test06@ether123.net', 'test06', 'Mikulov', 'Vídeňská', '80', 69201, 'zamestnanec'),
(7, 'Mirek', 'Broskev', 'test07@ether123.net', 'test07', 'Mohelnice', 'Družstevní', '8', 78985, 'zakaznik'),
(8, 'Jiří', 'Angrešt', 'test08@ether123.net', 'test08', 'Pelhřimov', 'Křemešnická', '1', 39301, 'zakaznik'),
(9, 'Martina', 'Malinová', 'test09@ether123.net', 'test09', 'Bohumín', 'Opletalova', '298', 73531, 'zakaznik'),
(11, 'Jiří', 'Matějka', 'xmatej52@stud.fit.vutbr.cz', 'admin', 'Brno', 'Božetěchova', '2', 61266, 'spravce'),
(12, 'Miroslava', 'Míšová', 'xmisov00@stud.fit.vutbr.cz', 'xmisov00', 'Brno', 'Binární', '101010', 10110, 'vedouci'),
(21, 'Stanislav', 'Hrobnický', 'standa@sunnynight.cz', 'standa', 'Brno', 'Binární', '10', 10110, 'zakaznik'),
(22, 'test', 'test', 'test', 'test', NULL, NULL, NULL, NULL, 'zakaznik'),
(26, 'Tonda', 'Kouzelník', 'test12@ether123.net', 'test12', '', '', '', 0, 'chef'),
(27, 'Radek', 'Nestřídmý', 'test13@test.cz', 'test13', 'Brno', 'Palackého', '2', 60200, 'chef'),
(28, 'Orgoj', 'Chorchoj', 'test14@test.cz', 'test14', '', '', '', 0, 'zamestnanec'),
(29, 'Baba', 'Jaga', 'test15@test.cz', 'test15', '', '', '', 0, 'zamestnanec'),
(30, 'Babice', 'Škaredá Slepice', 'test16@test.cz', 'test16', '', '', '', 0, 'zakaznik');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=10 ;

--
-- Vypisuji data pro tabulku `iis_h_zamestnanec`
--

INSERT INTO `iis_h_zamestnanec` (`ID`, `pracovni_pozice`, `rodne_cislo`, `cislo_uctu`, `plat`, `uzivatel`) VALUES
(1, 'Správce informačního systému', 1111110111, 2147483647, 30000, 11),
(2, 'Vedoucí restaurace', 2147483647, NULL, 42000, 12),
(3, 'Šéfkuchař', 2147483647, NULL, 28000, 1),
(4, 'číšnice', 2147483647, NULL, 12000, 6),
(5, 'Uklízečka', 1000000000, NULL, 4000, 10),
(6, 'kuchař', 1111111111, NULL, 18000, 26),
(7, 'kuchař', 1100000000, NULL, 12000, 27),
(8, 'číšnice', 1111111111, NULL, 20000, 29),
(9, 'barman', 2147483647, NULL, 15000, 28);

-- --------------------------------------------------------

--
-- Struktura tabulky `iis_s_objednavka_potravina`
--

CREATE TABLE IF NOT EXISTS `iis_s_objednavka_potravina` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `objednavka` int(11) NOT NULL,
  `potravina` int(11) NOT NULL,
  `mnozstvi` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `objednavka` (`objednavka`),
  KEY `potravina` (`potravina`)
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=40 ;

--
-- Vypisuji data pro tabulku `iis_s_potravina_surovina`
--

INSERT INTO `iis_s_potravina_surovina` (`ID`, `potravina`, `surovina`, `mnozstvi`) VALUES
(3, 2, 9, 120),
(4, 2, 13, 20),
(5, 2, 18, 150),
(7, 5, 6, 150),
(8, 5, 14, 4),
(9, 5, 20, 10),
(10, 5, 19, 1),
(11, 5, 1, 10),
(12, 5, 21, 20),
(13, 5, 22, 30),
(14, 7, 5, 150),
(15, 7, 14, 4),
(16, 7, 20, 10),
(17, 7, 19, 1),
(18, 7, 1, 10),
(19, 7, 21, 25),
(20, 7, 22, 30),
(21, 8, 5, 150),
(22, 8, 14, 3),
(23, 8, 22, 8),
(24, 8, 11, 5),
(25, 9, 6, 150),
(26, 9, 24, 5),
(27, 9, 15, 5),
(28, 9, 23, 5),
(29, 9, 26, 5),
(30, 9, 14, 4),
(31, 9, 27, 5),
(32, 9, 11, 15),
(39, 23, 29, 150);

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `iis_h_rezervace`
--
ALTER TABLE `iis_h_rezervace`
  ADD CONSTRAINT `stul_rezervovany` FOREIGN KEY (`stul`) REFERENCES `iis_h_stul` (`ID`),
  ADD CONSTRAINT `uzivatel` FOREIGN KEY (`uzivatel`) REFERENCES `iis_h_uzivatele` (`ID`);

--
-- Omezení pro tabulku `iis_h_uctenka`
--
ALTER TABLE `iis_h_uctenka`
  ADD CONSTRAINT `objednavka2` FOREIGN KEY (`objednavka`) REFERENCES `iis_h_objednavka` (`ID`);

--
-- Omezení pro tabulku `iis_s_objednavka_potravina`
--
ALTER TABLE `iis_s_objednavka_potravina`
  ADD CONSTRAINT `objednavka` FOREIGN KEY (`objednavka`) REFERENCES `iis_h_objednavka` (`ID`),
  ADD CONSTRAINT `potravina` FOREIGN KEY (`potravina`) REFERENCES `iis_h_potravina` (`ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
