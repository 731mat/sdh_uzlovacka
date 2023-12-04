-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Počítač: database:3306
-- Vytvořeno: Ned 04. pro 2022, 21:18
-- Verze serveru: 10.10.2-MariaDB-1:10.10.2+maria~ubu2204
-- Verze PHP: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `grundybike`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `race__category`
--

CREATE TABLE `race__category` (
                                  `id` int(11) NOT NULL,
                                  `race_id` int(11) NOT NULL,
                                  `name` varchar(200) NOT NULL,
                                  `description` varchar(100) NOT NULL,
                                  `price` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `race__list`
--

CREATE TABLE `race__list` (
                              `id` int(11) NOT NULL,
                              `name` varchar(300) NOT NULL,
                              `description` text NOT NULL DEFAULT '',
                              `date` datetime NOT NULL,
                              `place` varchar(100) NOT NULL,
                              `photo` varchar(50) DEFAULT NULL,
                              `regulations` text NOT NULL DEFAULT '',
                              `web` varchar(500) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `race__user`
--

CREATE TABLE `race__user` (
                              `id` int(11) NOT NULL,
                              `id_race_category` int(11) NOT NULL,
                              `id_race` int(11) NOT NULL,
                              `first_name` varchar(200) NOT NULL,
                              `last_name` varchar(200) NOT NULL,
                              `date_birth` date NOT NULL,
                              `email` varchar(200) NOT NULL,
                              `phone` varchar(200) NOT NULL,
                              `team` varchar(100) NOT NULL,
                              `pay` varchar(100) NOT NULL,
                              `hash` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `system_variable`
--

CREATE TABLE `system_variable` (
                                   `id` varchar(100) NOT NULL,
                                   `nazev` varchar(100) NOT NULL,
                                   `val` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `system_variable`
--

INSERT INTO `system_variable` (`id`, `nazev`, `val`) VALUES
                                                         ('adresa_firmy', 'Adresa firmy', 'Opava'),
                                                         ('hlavni_email', 'Hlavní email', 'info@matyho.cz'),
                                                         ('hlavni_telefon', 'Hlavní telefon', '+420 731 882 688'),
                                                         ('Nazev_firmy', 'Název firmy', 'ZměřímTo.cz'),
                                                         ('nazev_firmy_kratky', 'Název firmy krátky', 'ZměřímTo'),
                                                         ('oteviraci_doba', 'Otevírací doba', 'Volej kdykoliv'),
                                                         ('url_google_mapy', 'URL google mapy', '#'),
                                                         ('url_mapy_cz', 'URL mapy.cz', '#'),
                                                         ('url_youtube', 'URL Youtube', '#'),
                                                         ('fontawesome_logo', 'FontAwesome_logo', 'fa fa-stopwatch');

-- --------------------------------------------------------

--
-- Struktura tabulky `users_internal`
--

CREATE TABLE `users_internal` (
                                  `id` int(11) NOT NULL,
                                  `name` varchar(100) NOT NULL DEFAULT '',
                                  `username` varchar(100) NOT NULL,
                                  `password` varchar(300) NOT NULL,
                                  `email` varchar(130) NOT NULL,
                                  `role` varchar(30) NOT NULL DEFAULT 'guest'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `users_internal`
--

INSERT INTO `users_internal` (`id`, `name`, `username`, `password`, `email`, `role`) VALUES
    (1, 'Matěj Hloušek', 'mm', '$2y$10$O.hvGyGufoygOFj6suoh/OfAKosiqoGS/g5Ddo87QjZXvFWwf9yI2', 'm@matyho.cz', 'admin');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `race__category`
--
ALTER TABLE `race__category`
    ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `race__list`
--
ALTER TABLE `race__list`
    ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `race__user`
--
ALTER TABLE `race__user`
    ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `users_internal`
--
ALTER TABLE `users_internal`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `race__category`
--
ALTER TABLE `race__category`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `race__list`
--
ALTER TABLE `race__list`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `race__user`
--
ALTER TABLE `race__user`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `users_internal`
--
ALTER TABLE `users_internal`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
