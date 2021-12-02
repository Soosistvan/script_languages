-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2020. Máj 09. 16:44
-- Kiszolgáló verziója: 10.1.38-MariaDB
-- PHP verzió: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `barlang`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `barlang`
--

CREATE TABLE `barlang` (
  `id` int(11) NOT NULL,
  `nev` varchar(128) COLLATE utf8_bin NOT NULL,
  `hossz` int(11) NOT NULL,
  `kiterjedes` int(11) NOT NULL,
  `melyseg` int(11) NOT NULL,
  `magassag` int(11) NOT NULL,
  `telepules` varchar(128) COLLATE utf8_bin NOT NULL,
  `fenykep` varchar(128) COLLATE utf8_bin NOT NULL,
  `bekuldte` varchar(255) CHARACTER SET utf8 COLLATE utf8_hungarian_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- A tábla adatainak kiíratása `barlang`
--

INSERT INTO `barlang` (`id`, `nev`, `hossz`, `kiterjedes`, `melyseg`, `magassag`, `telepules`, `fenykep`, `bekuldte`) VALUES
(13, 'Istállós-kői-barlang', 57, 17, 1, 16, 'Szilvásvárad', '2031174915Istállós-kő.jpg', ''),
(12, 'Szent István-barlang', 1470, 101, 24, 77, 'Miskolc', '1737473777Szent István barlang.jpg', ''),
(14, 'asd', 2, 1, 1, 1, '1', '337508161', 'admin');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felhasznalok`
--

CREATE TABLE `felhasznalok` (
  `id` int(11) NOT NULL,
  `nev` varchar(255) COLLATE utf8_hungarian_ci NOT NULL,
  `felhasznalonev` varchar(255) COLLATE utf8_hungarian_ci NOT NULL,
  `jelszo` varchar(255) COLLATE utf8_hungarian_ci NOT NULL,
  `engedely` varchar(32) COLLATE utf8_hungarian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `felhasznalok`
--

INSERT INTO `felhasznalok` (`id`, `nev`, `felhasznalonev`, `jelszo`, `engedely`) VALUES
(5, 'admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `menu`
--

CREATE TABLE `menu` (
  `href` varchar(128) COLLATE utf8_bin NOT NULL,
  `description` varchar(128) COLLATE utf8_bin NOT NULL,
  `engedely` varchar(32) COLLATE utf8_bin DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- A tábla adatainak kiíratása `menu`
--

INSERT INTO `menu` (`href`, `description`, `engedely`) VALUES
('felvitel.php', 'Felvitel', 'admin'),
('index.php', 'Listázás', NULL),
('torles.php', 'Törlés', 'admin'),
('toplista.php', 'Toplista', NULL),
('logout.php', 'Kilépés', NULL);

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `barlang`
--
ALTER TABLE `barlang`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `felhasznalok`
--
ALTER TABLE `felhasznalok`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `felhasznalonev` (`felhasznalonev`);

--
-- A tábla indexei `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`href`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `barlang`
--
ALTER TABLE `barlang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT a táblához `felhasznalok`
--
ALTER TABLE `felhasznalok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
