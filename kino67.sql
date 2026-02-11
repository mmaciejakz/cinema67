-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2026 at 03:41 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kino67`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `filmy`
--

CREATE TABLE `filmy` (
  `id_filmu` int(11) NOT NULL,
  `tytul` varchar(250) NOT NULL,
  `autor` varchar(250) NOT NULL,
  `opis` text NOT NULL,
  `id_kategorii` int(11) NOT NULL,
  `zdjecie` text NOT NULL,
  `czas_trwania` int(11) DEFAULT 120,
  `ograniczenie_wiekowe` varchar(10) DEFAULT 'PG'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `filmy`
--

INSERT INTO `filmy` (`id_filmu`, `tytul`, `autor`, `opis`, `id_kategorii`, `zdjecie`, `czas_trwania`, `ograniczenie_wiekowe`) VALUES
(1, 'Incepcja', 'Christopher Nolan', 'Złodziej, który potrafi wykradać tajemnice z podświadomości podczas snu, otrzymuje zadanie wszczepienia pomysłu do umysłu osoby.', 5, 'https://m.media-amazon.com/images/M/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_.jpg', 148, 'PG-13'),
(2, 'Matrix', 'Lana i Lilly Wachowski', 'Haker komputerowy poznaje prawdę o swojej rzeczywistości i swojej roli w wojnie przeciwko kontrolującym ją siłom.', 5, 'https://m.media-amazon.com/images/M/MV5BNzQzOTk3OTAtNDQ0Zi00ZTVkLWI0MTEtMDllZjNkYzNjNTc4L2ltYWdlXkEyXkFqcGdeQXVyNjU0OTQ0OTY@._V1_.jpg', 136, 'R'),
(3, 'Forrest Gump', 'Robert Zemeckis', 'Historia życia Forresta Gumpa, człowieka o niskim IQ, który nieświadomie staje się świadkiem i uczestnikiem ważnych wydarzeń historycznych.', 3, 'https://m.media-amazon.com/images/M/MV5BNWIwODRlZTUtY2U3ZS00Yzg1LWJhNzYtMmZiYmEyNmU1NjMzXkEyXkFqcGdeQXVyMTQxNzMzNDI@._V1_.jpg', 142, 'PG-13'),
(4, ' Ekipa wyburzeniowa', 'Ángel Manuel Soto', 'Skłóceni przyrodni bracia Jonny i James łączą siły po tajemniczej śmierci ojca. W trakcie poszukiwania prawdy wychodzą na jaw sekrety i spisek, który zagraża ich rodzinie.', 2, 'https://m.media-amazon.com/images/S/pv-target-images/3f0edce583e217cc8cf1786c80a9f4c2ec65170161ded55211c865e6d24d0c72.jpg', 122, 'PG'),
(5, 'Zwierzogród 2', 'Jared Bush, Byron Howard', 'Policjanci Judy Hops i Nick Wilde ponownie łączą siły, aby rozwiązać nową sprawę. Trop prowadzi ich do poszukiwanego kryminalisty, węża imieniem Gary.', 6, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT8FxOCOlJVNDy4bfF-4efO1kkJ54u5iSfK4A&s', 107, 'PG'),
(7, ' Avatar', ' James Cameron', 'Jake, sparaliżowany były komandos, zostaje wysłany na planetę Pandora, gdzie zaprzyjaźnia się z lokalną społecznością i postanawia jej pomóc', 5, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRpWCpUZfOQJ6qzSH8RcRZ3llUSZwLir2wlqg&s', 162, 'NC-17'),
(8, ' Gladiator', 'Ridley Scott', 'Generał Maximus - prawa ręka cesarza, szczęśliwy mąż i ojciec - w jednej chwili traci wszystko. Jako niewolnik-gladiator musi walczyć na arenie o przeżycie.', 3, 'https://m.media-amazon.com/images/S/pv-target-images/e1243c292665b54122b66bfe0b4453798ba83dc902ce77b93596021428c95c36.jpg', 155, '18+'),
(9, ' Shrek', ' Andrew Adamson, Vicky Jenson', 'By odzyskać swój dom, brzydki ogr z gadatliwym osłem wyruszają uwolnić piękną księżniczkę.', 6, 'https://maorilandfilm.co.nz/wp-content/uploads/2025/04/6.jpg', 95, 'R'),
(10, 'Titanic', 'James Cameron', 'Rok 1912, brytyjski statek Titanic wyrusza w swój dziewiczy rejs do USA. Na pokładzie emigrant Jack przypadkowo spotyka arystokratkę Rose.', 9, 'https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcT5i6A1AcwzxHDRjQ2VQqRftmbk-rXhB0qgma-Xla7kI2Wn4fak', 194, '18+');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kategorie`
--

CREATE TABLE `kategorie` (
  `id_kategorii` int(11) NOT NULL,
  `kategoria` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `kategorie`
--

INSERT INTO `kategorie` (`id_kategorii`, `kategoria`) VALUES
(1, 'Akcja'),
(2, 'Komedia'),
(3, 'Dramat'),
(4, 'Horror'),
(5, 'Science Fiction'),
(6, 'Animacja'),
(7, 'Fantasy'),
(8, 'Thriller'),
(9, 'Dokumentalny'),
(10, 'Familijny');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `miejsca_w_salach`
--

CREATE TABLE `miejsca_w_salach` (
  `id_miejsca` int(11) NOT NULL,
  `id_sali` int(11) NOT NULL,
  `rzad` int(11) NOT NULL,
  `numer` int(11) NOT NULL,
  `status` enum('wolne','zarezerwowane','zajete') DEFAULT 'wolne'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `miejsca_w_salach`
--

INSERT INTO `miejsca_w_salach` (`id_miejsca`, `id_sali`, `rzad`, `numer`, `status`) VALUES
(1, 1, 1, 1, 'wolne'),
(2, 1, 1, 2, 'wolne'),
(3, 1, 1, 3, 'wolne'),
(4, 1, 1, 4, 'wolne'),
(5, 1, 1, 5, 'wolne'),
(6, 1, 1, 6, 'wolne'),
(7, 1, 1, 7, 'wolne'),
(8, 1, 1, 8, 'wolne'),
(9, 1, 1, 9, 'wolne'),
(10, 1, 1, 10, 'wolne'),
(11, 1, 2, 11, 'wolne'),
(12, 1, 2, 12, 'wolne'),
(13, 1, 2, 13, 'wolne'),
(14, 1, 2, 14, 'wolne'),
(15, 1, 2, 15, 'wolne'),
(16, 1, 2, 16, 'wolne'),
(17, 1, 2, 17, 'wolne'),
(18, 1, 2, 18, 'wolne'),
(19, 1, 2, 19, 'wolne'),
(20, 1, 2, 20, 'wolne'),
(21, 1, 3, 21, 'wolne'),
(22, 1, 3, 22, 'wolne'),
(23, 1, 3, 23, 'wolne'),
(24, 1, 3, 24, 'wolne'),
(25, 1, 3, 25, 'zarezerwowane'),
(26, 1, 3, 26, 'wolne'),
(27, 1, 3, 27, 'wolne'),
(28, 1, 3, 28, 'wolne'),
(29, 1, 3, 29, 'wolne'),
(30, 1, 3, 30, 'wolne'),
(31, 1, 4, 31, 'wolne'),
(32, 1, 4, 32, 'wolne'),
(33, 1, 4, 33, 'wolne'),
(34, 1, 4, 34, 'wolne'),
(35, 1, 4, 35, 'zarezerwowane'),
(36, 1, 4, 36, 'zarezerwowane'),
(37, 1, 4, 37, 'wolne'),
(38, 1, 4, 38, 'wolne'),
(39, 1, 4, 39, 'wolne'),
(40, 1, 4, 40, 'wolne'),
(41, 1, 5, 41, 'wolne'),
(42, 1, 5, 42, 'wolne'),
(43, 1, 5, 43, 'wolne'),
(44, 1, 5, 44, 'wolne'),
(45, 1, 5, 45, 'wolne'),
(46, 1, 5, 46, 'wolne'),
(47, 1, 5, 47, 'wolne'),
(48, 1, 5, 48, 'wolne'),
(49, 1, 5, 49, 'wolne'),
(50, 1, 5, 50, 'wolne'),
(51, 1, 6, 51, 'wolne'),
(52, 1, 6, 52, 'wolne'),
(53, 1, 6, 53, 'wolne'),
(54, 1, 6, 54, 'wolne'),
(55, 1, 6, 55, 'wolne'),
(56, 1, 6, 56, 'wolne'),
(57, 1, 6, 57, 'wolne'),
(58, 1, 6, 58, 'wolne'),
(59, 1, 6, 59, 'wolne'),
(60, 1, 6, 60, 'wolne'),
(61, 2, 1, 1, 'wolne'),
(62, 2, 1, 2, 'wolne'),
(63, 2, 1, 3, 'wolne'),
(64, 2, 1, 4, 'wolne'),
(65, 2, 1, 5, 'wolne'),
(66, 2, 1, 6, 'wolne'),
(67, 2, 1, 7, 'wolne'),
(68, 2, 1, 8, 'wolne'),
(69, 2, 2, 9, 'wolne'),
(70, 2, 2, 10, 'zarezerwowane'),
(71, 2, 2, 11, 'zarezerwowane'),
(72, 2, 2, 12, 'zarezerwowane'),
(73, 2, 2, 13, 'wolne'),
(74, 2, 2, 14, 'wolne'),
(75, 2, 2, 15, 'wolne'),
(76, 2, 2, 16, 'wolne'),
(77, 2, 3, 17, 'wolne'),
(78, 2, 3, 18, 'wolne'),
(79, 2, 3, 19, 'wolne'),
(80, 2, 3, 20, 'zarezerwowane'),
(81, 2, 3, 21, 'zarezerwowane'),
(82, 2, 3, 22, 'wolne'),
(83, 2, 3, 23, 'wolne'),
(84, 2, 3, 24, 'wolne'),
(85, 2, 4, 25, 'wolne'),
(86, 2, 4, 26, 'wolne'),
(87, 2, 4, 27, 'wolne'),
(88, 2, 4, 28, 'zarezerwowane'),
(89, 2, 4, 29, 'zarezerwowane'),
(90, 2, 4, 30, 'wolne'),
(91, 2, 4, 31, 'wolne'),
(92, 2, 4, 32, 'wolne'),
(93, 2, 5, 33, 'wolne'),
(94, 2, 5, 34, 'wolne'),
(95, 2, 5, 35, 'wolne'),
(96, 2, 5, 36, 'wolne'),
(97, 2, 5, 37, 'wolne'),
(98, 2, 5, 38, 'wolne'),
(99, 2, 5, 39, 'wolne'),
(100, 2, 5, 40, 'wolne'),
(101, 2, 6, 41, 'wolne'),
(102, 2, 6, 42, 'wolne'),
(103, 2, 6, 43, 'wolne'),
(104, 2, 6, 44, 'wolne'),
(105, 2, 6, 45, 'wolne'),
(106, 2, 6, 46, 'wolne'),
(107, 2, 6, 47, 'wolne'),
(108, 2, 6, 48, 'wolne'),
(109, 3, 1, 1, 'wolne'),
(110, 3, 1, 2, 'wolne'),
(111, 3, 1, 3, 'wolne'),
(112, 3, 1, 4, 'wolne'),
(113, 3, 1, 5, 'wolne'),
(114, 3, 1, 6, 'wolne'),
(115, 3, 1, 7, 'wolne'),
(116, 3, 1, 8, 'wolne'),
(117, 3, 1, 9, 'wolne'),
(118, 3, 2, 10, 'wolne'),
(119, 3, 2, 11, 'wolne'),
(120, 3, 2, 12, 'wolne'),
(121, 3, 2, 13, 'wolne'),
(122, 3, 2, 14, 'zarezerwowane'),
(123, 3, 2, 15, 'zarezerwowane'),
(124, 3, 2, 16, 'zarezerwowane'),
(125, 3, 2, 17, 'zarezerwowane'),
(126, 3, 2, 18, 'zarezerwowane'),
(127, 3, 3, 19, 'wolne'),
(128, 3, 3, 20, 'wolne'),
(129, 3, 3, 21, 'wolne'),
(130, 3, 3, 22, 'wolne'),
(131, 3, 3, 23, 'wolne'),
(132, 3, 3, 24, 'wolne'),
(133, 3, 3, 25, 'wolne'),
(134, 3, 3, 26, 'wolne'),
(135, 3, 3, 27, 'wolne'),
(136, 3, 4, 28, 'wolne'),
(137, 3, 4, 29, 'wolne'),
(138, 3, 4, 30, 'wolne'),
(139, 3, 4, 31, 'wolne'),
(140, 3, 4, 32, 'wolne'),
(141, 3, 4, 33, 'wolne'),
(142, 3, 4, 34, 'wolne'),
(143, 3, 4, 35, 'wolne'),
(144, 3, 4, 36, 'wolne'),
(145, 3, 5, 37, 'wolne'),
(146, 3, 5, 38, 'wolne'),
(147, 3, 5, 39, 'wolne'),
(148, 3, 5, 40, 'wolne'),
(149, 3, 5, 41, 'wolne'),
(150, 3, 5, 42, 'wolne'),
(151, 3, 5, 43, 'wolne'),
(152, 3, 5, 44, 'wolne'),
(153, 3, 5, 45, 'wolne'),
(154, 3, 6, 46, 'wolne'),
(155, 3, 6, 47, 'wolne'),
(156, 3, 6, 48, 'wolne'),
(157, 3, 6, 49, 'wolne'),
(158, 3, 6, 50, 'wolne'),
(159, 3, 6, 51, 'wolne'),
(160, 3, 6, 52, 'wolne'),
(161, 3, 6, 53, 'wolne'),
(162, 3, 6, 54, 'wolne'),
(163, 4, 1, 1, 'wolne'),
(164, 4, 1, 2, 'wolne'),
(165, 4, 1, 3, 'wolne'),
(166, 4, 1, 4, 'wolne'),
(167, 4, 1, 5, 'wolne'),
(168, 4, 1, 6, 'wolne'),
(169, 4, 2, 7, 'wolne'),
(170, 4, 2, 8, 'zarezerwowane'),
(171, 4, 2, 9, 'zarezerwowane'),
(172, 4, 2, 10, 'zarezerwowane'),
(173, 4, 2, 11, 'zarezerwowane'),
(174, 4, 2, 12, 'wolne'),
(175, 4, 3, 13, 'wolne'),
(176, 4, 3, 14, 'wolne'),
(177, 4, 3, 15, 'zarezerwowane'),
(178, 4, 3, 16, 'zarezerwowane'),
(179, 4, 3, 17, 'wolne'),
(180, 4, 3, 18, 'wolne'),
(181, 4, 4, 19, 'wolne'),
(182, 4, 4, 20, 'wolne'),
(183, 4, 4, 21, 'wolne'),
(184, 4, 4, 22, 'wolne'),
(185, 4, 4, 23, 'wolne'),
(186, 4, 4, 24, 'wolne'),
(187, 4, 5, 25, 'zarezerwowane'),
(188, 4, 5, 26, 'zarezerwowane'),
(189, 4, 5, 27, 'zarezerwowane'),
(190, 4, 5, 28, 'zarezerwowane'),
(191, 4, 5, 29, 'zarezerwowane'),
(192, 4, 5, 30, 'zarezerwowane');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `rezerwacje`
--

CREATE TABLE `rezerwacje` (
  `id_rezerwacji` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_seansu` int(11) NOT NULL,
  `miejsca` text NOT NULL,
  `status` enum('active','cancelled','used') DEFAULT 'active',
  `data_rezerwacji` timestamp NOT NULL DEFAULT current_timestamp(),
  `kod_rezerwacji` varchar(20) DEFAULT NULL,
  `cena_laczna` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rezerwacje`
--

INSERT INTO `rezerwacje` (`id_rezerwacji`, `id_user`, `id_seansu`, `miejsca`, `status`, `data_rezerwacji`, `kod_rezerwacji`, `cena_laczna`) VALUES
(1, 5, 1, '35,38,37,36', 'active', '2026-01-21 19:23:50', '5AF7805E', 25.00),
(2, 5, 1, '36,48,38,16,17', 'active', '2026-01-21 20:05:23', '00C97474', 25.00),
(3, 4, 1, '25,27,18,17,16,45', 'active', '2026-01-21 20:08:38', '8D67CC91', 25.00),
(7, 3, 9, '6,7', 'active', '2026-02-04 13:32:34', '676767', 402.00),
(8, 3, 9, '15,16', 'active', '2026-02-04 13:49:51', '80825AB4', 134.00),
(9, 3, 16, '25,26,27,28,30,29', 'active', '2026-02-04 14:20:55', '2844721A', 588.00),
(10, 3, 13, '10,12,11', 'active', '2026-02-04 14:21:21', 'FB1FB1A5', 75.00),
(11, 5, 15, '14,15,16,18,17', 'active', '2026-02-04 14:21:51', '2B230196', 375.00),
(12, 5, 13, '28,29', 'active', '2026-02-04 14:22:02', '9BE389CE', 50.00),
(13, 5, 10, '8,9,10,11', 'active', '2026-02-04 14:22:20', '73CEFB38', 60.00),
(14, 5, 8, '20,21', 'active', '2026-02-04 14:22:37', '69F997A5', 54.00),
(15, 5, 10, '25,26,27,28,29,30', 'active', '2026-02-04 14:25:46', 'FD142F82', 90.00);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `rezerwacje_miejsc`
--

CREATE TABLE `rezerwacje_miejsc` (
  `id` int(11) NOT NULL,
  `id_rezerwacji` int(11) NOT NULL,
  `id_seansu` int(11) NOT NULL,
  `numer_miejsca` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rezerwacje_miejsc`
--

INSERT INTO `rezerwacje_miejsc` (`id`, `id_rezerwacji`, `id_seansu`, `numer_miejsca`) VALUES
(1, 1, 1, 35),
(2, 2, 1, 36),
(3, 3, 1, 25),
(13, 7, 9, 30),
(14, 7, 9, 28),
(15, 7, 9, 29),
(16, 7, 9, 27),
(17, 7, 9, 26),
(18, 7, 9, 25),
(19, 8, 9, 15),
(20, 8, 9, 16),
(21, 9, 16, 25),
(22, 9, 16, 26),
(23, 9, 16, 27),
(24, 9, 16, 28),
(25, 9, 16, 30),
(26, 9, 16, 29),
(27, 10, 13, 10),
(28, 10, 13, 12),
(29, 10, 13, 11),
(30, 11, 15, 14),
(31, 11, 15, 15),
(32, 11, 15, 16),
(33, 11, 15, 18),
(34, 11, 15, 17),
(35, 12, 13, 28),
(36, 12, 13, 29),
(37, 13, 10, 8),
(38, 13, 10, 9),
(39, 13, 10, 10),
(40, 13, 10, 11),
(41, 14, 8, 20),
(42, 14, 8, 21),
(43, 15, 10, 25),
(44, 15, 10, 26),
(45, 15, 10, 27),
(46, 15, 10, 28),
(47, 15, 10, 29),
(48, 15, 10, 30);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `sale`
--

CREATE TABLE `sale` (
  `id_sali` int(11) NOT NULL,
  `sala` varchar(250) NOT NULL,
  `liczba_miejsc` int(11) DEFAULT 60
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sale`
--

INSERT INTO `sale` (`id_sali`, `sala`, `liczba_miejsc`) VALUES
(1, 'Sala 1 - Premierowa', 60),
(2, 'Sala 2 - Komfort', 48),
(3, 'Sala 3 - Standard', 54),
(4, 'Sala 4 - VIP', 30);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `seanse`
--

CREATE TABLE `seanse` (
  `id_seansu` int(11) NOT NULL,
  `id_filmu` int(11) NOT NULL,
  `id_sali` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `cena_biletu` decimal(6,2) DEFAULT 25.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `seanse`
--

INSERT INTO `seanse` (`id_seansu`, `id_filmu`, `id_sali`, `data`, `cena_biletu`) VALUES
(1, 1, 1, '2026-01-23 13:20:06', 25.00),
(2, 1, 2, '2026-01-23 15:50:06', 25.00),
(3, 2, 1, '2026-01-24 14:20:06', 22.00),
(4, 3, 3, '2026-01-24 12:50:06', 20.00),
(5, 3, 1, '2026-02-14 18:00:00', 26.07),
(6, 1, 4, '2026-02-06 20:00:00', 55.00),
(7, 3, 3, '2026-02-07 20:03:00', 35.00),
(8, 3, 2, '2026-02-14 06:07:00', 27.00),
(9, 2, 4, '2067-07-06 06:07:00', 67.00),
(10, 5, 4, '2026-02-20 21:00:00', 15.00),
(11, 1, 2, '2026-02-13 22:00:00', 45.00),
(12, 2, 3, '2026-02-14 21:00:00', 65.00),
(13, 7, 2, '2026-02-07 22:00:00', 25.00),
(14, 8, 2, '2026-02-13 21:00:00', 25.00),
(15, 9, 3, '2026-02-21 18:00:00', 75.00),
(16, 10, 4, '2026-03-06 22:00:00', 98.00),
(17, 10, 4, '2026-03-05 19:00:00', 95.00),
(18, 10, 4, '2026-03-08 21:00:00', 99.00);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `registration_date`, `admin`) VALUES
(3, 'admin', '$2y$10$7YloqwR0gWcFOfVyQqWB8.cVg1h/PN5oCMir7EQyNBcApTqjuoNZC', 'ram_yt@onet.pl', '2026-01-21 19:23:25', 1),
(4, 'maciejak', '$2y$10$njA7fOLPG90MIYGGFzKS7.11Gal2Nvs2Zci6RcK99yAQabwKozGO.', 'm.maciejak@zset.leszno.pl', '2026-01-21 20:07:12', 0),
(5, 'azjan', '$2y$10$CkWdWBq0Kb2.JyCjMqr/lujBkF.SQMRmvVav37pMs1b5.pp6LadwG', 'w.wdi@ej.ak', '2026-02-04 12:38:03', 0);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `filmy`
--
ALTER TABLE `filmy`
  ADD PRIMARY KEY (`id_filmu`),
  ADD KEY `id_kategorii` (`id_kategorii`);

--
-- Indeksy dla tabeli `kategorie`
--
ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`id_kategorii`);

--
-- Indeksy dla tabeli `miejsca_w_salach`
--
ALTER TABLE `miejsca_w_salach`
  ADD PRIMARY KEY (`id_miejsca`),
  ADD UNIQUE KEY `unique_seat` (`id_sali`,`rzad`,`numer`),
  ADD KEY `id_sali` (`id_sali`);

--
-- Indeksy dla tabeli `rezerwacje`
--
ALTER TABLE `rezerwacje`
  ADD PRIMARY KEY (`id_rezerwacji`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_seansu` (`id_seansu`);

--
-- Indeksy dla tabeli `rezerwacje_miejsc`
--
ALTER TABLE `rezerwacje_miejsc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_seat_reservation` (`id_seansu`,`numer_miejsca`),
  ADD KEY `id_rezerwacji` (`id_rezerwacji`);

--
-- Indeksy dla tabeli `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`id_sali`);

--
-- Indeksy dla tabeli `seanse`
--
ALTER TABLE `seanse`
  ADD PRIMARY KEY (`id_seansu`),
  ADD KEY `id_filmu` (`id_filmu`),
  ADD KEY `id_sali` (`id_sali`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `filmy`
--
ALTER TABLE `filmy`
  MODIFY `id_filmu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `id_kategorii` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `miejsca_w_salach`
--
ALTER TABLE `miejsca_w_salach`
  MODIFY `id_miejsca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `rezerwacje`
--
ALTER TABLE `rezerwacje`
  MODIFY `id_rezerwacji` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `rezerwacje_miejsc`
--
ALTER TABLE `rezerwacje_miejsc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `sale`
--
ALTER TABLE `sale`
  MODIFY `id_sali` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `seanse`
--
ALTER TABLE `seanse`
  MODIFY `id_seansu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `filmy`
--
ALTER TABLE `filmy`
  ADD CONSTRAINT `filmy_ibfk_1` FOREIGN KEY (`id_kategorii`) REFERENCES `kategorie` (`id_kategorii`);

--
-- Constraints for table `miejsca_w_salach`
--
ALTER TABLE `miejsca_w_salach`
  ADD CONSTRAINT `miejsca_w_salach_ibfk_1` FOREIGN KEY (`id_sali`) REFERENCES `sale` (`id_sali`);

--
-- Constraints for table `rezerwacje`
--
ALTER TABLE `rezerwacje`
  ADD CONSTRAINT `rezerwacje_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `rezerwacje_ibfk_2` FOREIGN KEY (`id_seansu`) REFERENCES `seanse` (`id_seansu`);

--
-- Constraints for table `rezerwacje_miejsc`
--
ALTER TABLE `rezerwacje_miejsc`
  ADD CONSTRAINT `rezerwacje_miejsc_ibfk_1` FOREIGN KEY (`id_rezerwacji`) REFERENCES `rezerwacje` (`id_rezerwacji`),
  ADD CONSTRAINT `rezerwacje_miejsc_ibfk_2` FOREIGN KEY (`id_seansu`) REFERENCES `seanse` (`id_seansu`);

--
-- Constraints for table `seanse`
--
ALTER TABLE `seanse`
  ADD CONSTRAINT `seanse_ibfk_1` FOREIGN KEY (`id_filmu`) REFERENCES `filmy` (`id_filmu`),
  ADD CONSTRAINT `seanse_ibfk_2` FOREIGN KEY (`id_sali`) REFERENCES `sale` (`id_sali`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
