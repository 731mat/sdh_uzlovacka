-- phpMyAdmin SQL Dump
-- version 5.0.4deb2+deb11u1
-- https://www.phpmyadmin.net/
--
-- Počítač: localhost:3306
-- Vytvořeno: Pon 04. pro 2023, 11:29
-- Verze serveru: 10.5.19-MariaDB-0+deb11u2
-- Verze PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `c9neplachovice2`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `competitor`
--

CREATE TABLE `competitor` (
  `id` int(11) NOT NULL,
  `start_number` int(11) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `register_hash` text NOT NULL DEFAULT '',
  `sdh` varchar(45) NOT NULL DEFAULT '',
  `year` int(11) NOT NULL DEFAULT 0,
  `category` int(11) NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `round` int(11) NOT NULL DEFAULT 0,
  `first_time` int(11) NOT NULL DEFAULT 999,
  `hash` varchar(150) NOT NULL DEFAULT 'asdfjaksehasdkfshefku',
  `vysledne_poradi` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `competitor`
--

INSERT INTO `competitor` (`id`, `start_number`, `first_name`, `last_name`, `register_hash`, `sdh`, `year`, `category`, `score`, `round`, `first_time`, `hash`, `vysledne_poradi`) VALUES
(29, 1, 'Václav', 'Francek', '', 'Neplachovice', 2017, 1, 1, 3, 40, 'c0200792d59dcf7c6eb5d8c536c3b696', 996),
(30, 2, 'Barča', 'Vojkůvková', '', 'Neplachovice', 2016, 1, 0, 2, 50, 'e082aa163c9efeb8cd7114335a83d70f', 999),
(31, 3, 'Lilly', 'Richter', '', 'Neplachovice', 2016, 1, 0, 2, 50, 'e082aa163c9efeb8cd7114335a83d70f', 998),
(32, 4, 'Erika', 'Snášelová', '', 'Neplachovice', 2015, 1, 2, 4, 40, 'e082aa163c9efeb8cd7114335a83d70f', 993),
(33, 5, 'Marie', 'Lukášová', '', 'Neplachovice', 2016, 1, 4, 6, 50, 'e082aa163c9efeb8cd7114335a83d70f', 989),
(34, 6, 'Matyaš', 'Kružberský', '', 'Neplachovice', 2017, 1, 0, 2, 60, 'e082aa163c9efeb8cd7114335a83d70f', 997),
(35, 7, 'Pavel', 'Černín', '', 'Neplachovice', 2016, 1, 1, 3, 30, 'e082aa163c9efeb8cd7114335a83d70f', 995),
(36, 8, 'Adam', 'Schreier', '', 'Neplachovice', 2017, 1, 2, 4, 50, 'e082aa163c9efeb8cd7114335a83d70f', 992),
(37, 31, 'Natálie', 'Polová', '', 'Holasovice', 2012, 2, 5, 6, 45, 'b678b76de69f37cdfd8ac762e9e0dd0d', 0),
(38, 9, 'Olivie', 'Stavařová', '', 'Holasovice', 2013, 1, 5, 7, 55, 'b678b76de69f37cdfd8ac762e9e0dd0d', 0),
(39, 32, 'Kateřina ', 'Jonáková', '', 'Holasovice', 2011, 2, 0, 2, 75, 'b678b76de69f37cdfd8ac762e9e0dd0d', 999),
(40, 33, 'Jonáš ', 'Vávra', '', 'Holasovice', 2012, 2, 1, 3, 60, 'b678b76de69f37cdfd8ac762e9e0dd0d', 996),
(41, 34, 'Štěpánka', 'Dehnerová', '', 'Holasovice', 2012, 2, 2, 4, 60, 'b678b76de69f37cdfd8ac762e9e0dd0d', 993),
(42, 35, 'Agáta', 'Svobodová', '', 'Holasovice', 2012, 2, 3, 5, 55, 'b678b76de69f37cdfd8ac762e9e0dd0d', 991),
(43, 36, 'Eva', 'Bieliková', '', 'Holasovice', 2011, 2, 1, 3, 65, 'b678b76de69f37cdfd8ac762e9e0dd0d', 995),
(45, 10, 'Emma', 'Krpcová', '', 'Holasovice', 2014, 1, 3, 5, 65, 'b678b76de69f37cdfd8ac762e9e0dd0d', 990),
(46, 11, 'Adriana', 'Dehnerová', '', 'Holasovice', 2014, 1, 3, 5, 90, 'b678b76de69f37cdfd8ac762e9e0dd0d', 991),
(47, 12, 'Valérie', 'Krupová', '', 'Holasovice', 2014, 1, 1, 3, 90, 'b678b76de69f37cdfd8ac762e9e0dd0d', 994),
(56, 61, 'Adéla', 'Polišenská', '', 'Jezdkovice', 2007, 3, 3, 5, 27, 'fd352f6c0cef5a7154e4dc2f7b134b37', 0),
(57, 38, 'Adam', 'Berger', '', 'Jezdkovice', 2009, 2, 1, 3, 41, 'fd352f6c0cef5a7154e4dc2f7b134b37', 994),
(58, 39, 'Sára', 'Kavanová', '', 'Jezdkovice', 2012, 2, 5, 6, 61, 'fd352f6c0cef5a7154e4dc2f7b134b37', 0),
(59, 13, 'Natálie', 'Polišenská', '', 'Jezdkovice', 2014, 1, 7, 7, 65, 'fd352f6c0cef5a7154e4dc2f7b134b37', 0),
(61, 40, 'Karel ', 'Macík', '', 'Vávrovice ', 2011, 2, 2, 4, 36, 'e8acfd2078e779c75391f4c20e5fcd75', 992),
(62, 48, 'Eliška ', 'Grzymková', '', 'Vávrovice ', 2011, 2, 0, 2, 58, 'e8acfd2078e779c75391f4c20e5fcd75', 997),
(63, 42, 'Johana', 'Bartusková', '', 'Vávrovice ', 2012, 2, 0, 2, 42, 'e8acfd2078e779c75391f4c20e5fcd75', 998),
(64, 43, 'Viktorie', 'Fedčuk', '', 'Vávrovice', 2012, 2, 4, 6, 37, 'e8acfd2078e779c75391f4c20e5fcd75', 990),
(67, 62, 'Adéla', 'Tenglerová', '', 'Neplachovice', 2008, 3, 2, 4, 30, 'fd352f6c0cef5a7154e4dc2f7b134b37', 997),
(68, 63, 'Viki', 'Švachová', '', 'Neplachovice', 2008, 3, 1, 3, 30, 'fd352f6c0cef5a7154e4dc2f7b134b37', 998),
(69, 64, 'Jan', 'Matoušek', '', 'Neplachovice', 2006, 3, 5, 5, 25, 'fd352f6c0cef5a7154e4dc2f7b134b37', 0),
(70, 0, '-', '-', '00', '0', 0, 99, 0, 10, 999, 'asdfjaksehasdkfshefku', 0),
(71, 65, 'Jakub', 'Malý ', '', 'Neplachovice ', 2007, 3, 0, 2, 35, '4c77837de1ce210e76156b0b8581864b', 999);

-- --------------------------------------------------------

--
-- Struktura tabulky `matches`
--

CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL DEFAULT 1,
  `round` int(11) NOT NULL,
  `first_competitor` int(11) NOT NULL,
  `second_competitor` int(11) NOT NULL,
  `first_competitor_mistakes` int(11) DEFAULT NULL,
  `second_competitor_mistakes` int(11) DEFAULT NULL,
  `first_competitor_time` decimal(8,2) DEFAULT NULL,
  `second_competitor_time` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `matches`
--

INSERT INTO `matches` (`id`, `table_id`, `round`, `first_competitor`, `second_competitor`, `first_competitor_mistakes`, `second_competitor_mistakes`, `first_competitor_time`, `second_competitor_time`) VALUES
(359, 1, 0, 38, 35, 0, 0, '42.34', '97.19'),
(360, 1, 0, 29, 34, 0, 1, '85.01', '93.83'),
(361, 1, 0, 32, 45, 0, 1, '45.13', '50.12'),
(362, 1, 0, 30, 59, 1, 0, '55.47', '45.97'),
(363, 1, 0, 31, 46, 0, 0, '61.23', '54.21'),
(364, 1, 0, 47, 33, 2, 1, '67.84', '101.46'),
(372, 1, 0, 36, 70, 0, 5, '117.86', '1830.30'),
(373, 3, 0, 68, 69, 0, 0, '20.59', '20.08'),
(374, 3, 0, 71, 56, 1, 0, '23.02', '23.97'),
(375, 3, 0, 67, 70, 0, 5, '21.71', '20.00'),
(376, 1, 2, 30, 35, 2, 1, '83.17', '77.41'),
(377, 1, 2, 31, 45, 1, 0, '48.73', '49.29'),
(378, 1, 2, 34, 47, 2, 2, '103.84', '78.20'),
(379, 1, 2, 29, 38, 2, 0, '115.01', '59.06'),
(380, 1, 2, 32, 46, 0, 2, '44.51', '51.60'),
(381, 1, 2, 33, 59, 0, 0, '96.34', '45.67'),
(382, 1, 2, 36, 70, 1, 5, '119.47', '1830.30'),
(383, 2, 0, 62, 61, 2, 0, '80.72', '39.65'),
(384, 2, 0, 40, 64, 0, 0, '30.31', '28.58'),
(385, 2, 0, 41, 57, 0, 1, '37.64', '42.36'),
(386, 2, 0, 58, 63, 0, 1, '37.83', '42.22'),
(387, 2, 0, 43, 37, 0, 0, '59.10', '33.99'),
(388, 2, 0, 39, 42, 2, 0, '58.47', '42.32'),
(389, 3, 2, 68, 71, 0, 0, '20.80', '31.97'),
(390, 3, 2, 56, 69, 2, 0, '20.36', '20.36'),
(391, 3, 2, 67, 70, 0, 5, '18.93', '20.00'),
(392, 2, 2, 39, 57, 2, 1, '52.25', '31.68'),
(393, 2, 2, 40, 62, 1, 1, '37.60', '41.44'),
(394, 2, 2, 43, 63, 0, 0, '40.38', '44.81'),
(395, 2, 2, 37, 58, 1, 0, '32.38', '40.36'),
(396, 2, 2, 41, 61, 1, 1, '35.55', '40.00'),
(397, 2, 2, 42, 64, 0, 0, '40.54', '29.47'),
(398, 3, 3, 68, 56, 0, 0, '21.21', '20.65'),
(399, 3, 3, 67, 69, 0, 0, '23.32', '21.62'),
(400, 1, 3, 29, 46, 0, 0, '152.46', '53.96'),
(401, 1, 3, 35, 33, 1, 0, '59.04', '87.85'),
(402, 1, 3, 45, 47, 1, 2, '56.95', '58.85'),
(403, 1, 3, 32, 59, 1, 0, '47.05', '40.82'),
(404, 1, 3, 36, 38, 1, 1, '84.44', '52.83'),
(405, 2, 3, 40, 61, 2, 0, '32.87', '30.62'),
(406, 2, 3, 43, 42, 0, 0, '36.80', '33.81'),
(407, 2, 3, 57, 37, 1, 0, '68.45', '30.25'),
(408, 2, 3, 41, 58, 1, 0, '38.53', '41.15'),
(409, 2, 3, 64, 70, 0, 5, '37.56', '20.00'),
(410, 1, 4, 32, 33, 1, 0, '44.69', '74.31'),
(411, 1, 4, 36, 45, 0, 0, '76.97', '59.86'),
(412, 1, 4, 59, 38, 0, 1, '58.20', '41.33'),
(413, 1, 4, 46, 70, 0, 5, '52.23', '145.33'),
(414, 3, 4, 67, 56, 1, 0, '25.87', '21.79'),
(415, 3, 4, 69, 70, 0, 5, '20.70', '20.00'),
(416, 2, 4, 41, 42, 0, 0, '43.65', '34.47'),
(417, 2, 4, 61, 37, 0, 0, '34.59', '32.06'),
(418, 2, 4, 64, 58, 1, 0, '27.13', '34.15'),
(421, 1, 5, 33, 46, 0, 1, '66.00', '46.25'),
(422, 1, 5, 38, 45, 1, 1, '43.87', '80.57'),
(423, 1, 5, 59, 70, 1, 5, '45.05', '85.56'),
(424, 2, 5, 42, 37, 0, 0, '39.08', '32.35'),
(425, 2, 5, 64, 58, 0, 0, '36.49', '46.06'),
(426, 3, 5, 56, 69, 0, 0, '28.99', '20.10'),
(427, 2, 6, 64, 37, 0, 0, '42.50', '30.28'),
(428, 2, 6, 58, 70, 1, 5, '39.70', '20.00'),
(429, 1, 6, 33, 38, 1, 0, '54.81', '41.54'),
(430, 1, 6, 59, 70, 0, 5, '45.37', '85.25'),
(431, 2, 7, 37, 70, NULL, NULL, NULL, NULL),
(432, 1, 7, 38, 59, 1, 0, '44.47', '70.83');

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
('hlavni_email', 'Hlavní email', 'matej.hlousek@email.cz'),
('hlavni_telefon', 'Hlavní telefon', '+420 731 882 688'),
('Nazev_firmy', 'Název firmy', 'Uzlovačka Neplachovice 2023'),
('nazev_firmy_kratky', 'Název firmy krátky', 'SDH Neplachovice'),
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
(1, 'Matěj Hloušek', 'matej', '$2y$10$O.hvGyGufoygOFj6suoh/OfAKosiqoGS/g5Ddo87QjZXvFWwf9yI2', 'm@matyho.cz', 'admin'),
(3, 'Matěj Hloušek', 'maty', '$2y$10$IpNRiQD0YG18C6VNY3LerOlWoFffZAaFdGKbuwf6gDJgek/F1L0AW', 'm@bmail.cz', 'admin'),
(4, '', 'hasici', '$2y$10$s/udbDz9UpbzLMVvoUiqaOiisAEmzd/TFGokWwIhOAqX2.IkY8GsC', 'm@bmail.cz', 'guest');

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `competitor`
--
ALTER TABLE `competitor`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `users_internal`
--
ALTER TABLE `users_internal`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `competitor`
--
ALTER TABLE `competitor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT pro tabulku `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=433;

--
-- AUTO_INCREMENT pro tabulku `users_internal`
--
ALTER TABLE `users_internal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
