-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 01, 2020 at 09:05 PM
-- Server version: 5.7.31-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zavrsnirad2`
--

-- --------------------------------------------------------

--
-- Table structure for table `aktivnosti`
--

CREATE TABLE `aktivnosti` (
  `id` int(11) NOT NULL,
  `id_zaposlenik` int(5) NOT NULL,
  `id_vozilo` int(11) NOT NULL,
  `period_start` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `period_stop` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `stanje_goriva_start` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `stanje_goriva_stop` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `kilometraza` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `aktivnosti`
--

INSERT INTO `aktivnosti` (`id`, `id_zaposlenik`, `id_vozilo`, `period_start`, `period_stop`, `stanje_goriva_start`, `stanje_goriva_stop`, `kilometraza`) VALUES
(1, 1, 2, '3', '4', '5', '6', 200),
(2, 1, 2, '3', '4', '5', '6', 220),
(3, 18, 9, '2020-08-13T12:14', '2020-08-05T12:14', '2000', '9000', 320),
(4, 18, 9, '2020-08-13T12:14', '2020-08-05T12:14', '2000', '9000', 410),
(5, 20, 11, '2023-10-11T12:18', '2020-05-13T12:21', '1', '2', 540),
(6, 20, 11, '2023-10-11T12:18', '2020-05-13T12:21', '1', '2', 670),
(7, 18, 9, '2020-08-25T12:23', '2020-08-12T00:23', '12', '13', 730),
(8, 18, 9, '2020-08-25T12:23', '2020-08-12T00:23', '12', '13', 840),
(9, 18, 9, '2020-08-25T12:23', '2020-08-12T00:23', '12', '13', 980),
(10, 18, 9, '2020-08-25T12:23', '2020-08-12T00:23', '12', '13', 1040),
(11, 1, 1, '2020-08-17T12:21', '2020-08-08T12:21', '999', '888', 1150),
(12, 1, 1, '2020-08-17T12:21', '2020-08-08T12:21', '999', '888', 1290),
(13, 1, 1, '2020-08-26T12:23', '2020-09-24T12:26', '999', '888888', 1320),
(14, 21, 8, '2020-08-18T16:07', '2020-08-19T13:07', '1000', '323232', 1430),
(15, 11, 12, '2020-08-20T13:10', '2020-08-17T13:10', '1000', '990', 1500),
(16, 17, 10, '2020-08-12T17:00', '2020-08-07T18:03', 'ssss', 'ssssss', 1600),
(17, 15, 12, '6659', '65656', '9898', '98989', 1760),
(18, 23, 17, '2020-08-06T16:10', '2020-08-05T16:10', '55', '45', 1810),
(19, 23, 16, '2020-08-03T21:11', '2020-08-05T17:14', '999', '99999', 2000),
(20, 3, 17, '2020-08-21T23:15', '2020-08-12T19:13', '1000', '990', 2010),
(21, 18, 17, '2020-08-02T20:12', '2020-08-02T22:14', '888999', '323232', 2110),
(22, 15, 15, '2020-08-10T12:51', '2020-08-09T12:51', '1000', '990', 2290),
(23, 21, 13, '2020-08-10T12:52', '2020-08-13T12:52', '99', '45', 2400),
(24, 23, 17, '2020-08-09T12:52', '2020-08-27T21:49', '1000', '45', 2430),
(25, 23, 17, '2020-08-11T00:54', '2020-08-29T14:56', '1000', '45', 2530),
(26, 11, 10, '2020-08-14T13:55', '2020-08-09T12:55', '1000', '990', 2610),
(27, 1, 1, '2020-08-25T15:23', '2020-08-29T15:23', '1000', '990', 2740),
(28, 1, 1, '2020-08-25T15:23', '2020-08-29T15:23', '1000', '990', 2800),
(29, 20, 8, '12', '12', '22', '22', 2940),
(30, 20, 8, '12', '12', '22', '22', 3010),
(31, 1, 1, '2000', '1000', '1000', '1500', 3130),
(32, 1, 1, '2000', '1000', '1000', '1500', 3300),
(33, 8, 11, '2020-07-06T19:43', '2020-08-10T19:43', '1000', '990', 3370),
(34, 8, 11, '2020-07-06T19:43', '2020-08-10T19:43', '1000', '990', 3420),
(35, 1, 1, '2020-05-03T19:44', '2020-08-03T19:44', '888999', '323232', 3570),
(36, 1, 1, '2020-05-03T19:44', '2020-08-03T19:44', '888999', '323232', 3700),
(37, 23, 1, '2020-08-04T19:01', '2020-09-30T22:04', '99', '323232', 3760),
(38, 23, 1, '2020-08-04T19:01', '2020-09-30T22:04', '99', '323232', 3880),
(40, 23, 17, '2020-08-02T23:51', '2020-08-09T12:52', '1000', '45', 4000),
(41, 22, 15, '2020-08-02T12:03', '2020-08-30T15:06', '1', '2', 4130),
(42, 22, 15, '2020-08-02T12:03', '2020-08-30T15:06', '1', '2', 4240),
(43, 1, 1, '2020-08-04T22:10', '2020-08-12T23:11', '1', '2', 4360),
(44, 1, 1, '2020-08-04T22:10', '2020-08-12T23:11', '1', '2', 4400),
(45, 3, 4, '2020-08-02T12:14', '2020-08-03T13:15', '2', '3', 4540),
(46, 7, 14, '2020-08-11T13:37', '2020-08-13T13:37', '55', '990', 4690),
(47, 11, 14, '2020-08-12T13:54', '2020-08-20T14:55', '55', '990', 4700),
(48, 11, 11, '2020-08-10T23:30', '255', '55', '323232', 4890),
(49, 1, 1, '2020-05-02T03:53', '2020-06-02T03:53', '1', '1', 4920),
(50, 1, 1, '2020-09-02T03:54', '2020-09-24T03:54', '55', '990', 5070),
(51, 1, 1, '2020-09-15T03:57', '2020-09-24T03:57', '1000', '45', 5200);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(5) NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `date_birth` date DEFAULT NULL,
  `date_reg` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `ime` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `prezime` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `date_birth`, `date_reg`, `ime`, `prezime`, `user_type`) VALUES
(1, 'mail@mail.hrrrr', '$2y$10$x1e1NIOIdfOQyxXxQiThZ.WiPpG.vkw0bXepT1lvUBXuqAkim4aEC', '2020-08-04', '2020-08-27 17:44:19.923278', 'Admin', 'Adminovic', 0),
(3, 'king', '$2y$10$VHu/qus7ty2jCS1umqMRnuPtk5cvNmbJyA2oO8lqQDbQ3RoiUI2Uq', '2020-08-04', '2020-08-27 17:44:19.923278', 'Admin', 'Adminović', 0),
(7, 'nemam@mail.net', '', '2020-07-28', '2020-08-28 14:23:42.000000', 'IvanGGG', 'Horvatićrrrrrrr', 1),
(8, 'mail@mail.hu', '', '2020-07-28', '2020-08-28 14:23:55.000000', '78945612399999999', 'Horvatićuuuu', 1),
(9, 'mail@mail.haaaaasss', '', '2020-07-28', '2020-08-28 14:24:33.000000', 'Ivanaaaa', 'Horvatićaaaawwwsssss', 1),
(11, 'aaaaaaauuuu', '', '2020-08-05', '2020-08-28 14:29:49.000000', '111', 'Zaposlenik', 1),
(12, 'mail', '', '2020-08-30', '2020-08-28 14:37:08.000000', 'ABCssss', 'ŽĆđšččhhhh', 1),
(15, 'ggg55555', '', '2020-08-13', '2020-08-28 18:15:39.000000', 'Ivanaaaa', 'JUJUJUJgggsss', 1),
(17, '12345', '', '2000-02-02', '2020-08-28 18:39:44.000000', 'KKKKKK', 'JJJJJdddd', 1),
(18, 'wwww', '$2y$10$y9sGbqih6dM6WZQaxaMpGeizjcPrJVfcmK7ek.1o.SgLgoHmtnXUy', '2020-08-11', '2020-08-28 22:24:59.000000', 'www999', 'ssssssssssssss', 1),
(20, 'novi', '$2y$10$gm4yROcqpG7N09qMcvgHAukkD5UZ36YDjJj3V3.T8XDrVdi1YVzTm', '2020-11-11', '2020-08-28 23:21:07.000000', 'novitok', 'novi', 1),
(21, 'ddd', '7', '2020-08-19', '2020-08-29 01:26:12.000000', 'Prva', 'Osoba', 1),
(22, '5656', '$2y$10$26dSyxriPocbIKmFKkrTxu.HLgrI4A8OociQWEkRdUByqtBNBKzfG', '2020-08-29', '2020-08-29 01:38:52.000000', 'Ivan', 'Peričić', 1),
(23, 'zzzzuuu', '$2y$10$kCdD7J2hO7X7NGlL7qDgsu9XM7ngi4mq128CXWdA1zjHj5RT4tK4C', '2020-08-18', '2020-08-29 10:29:22.000000', 'zzz', 'Dugaaaa', 1),
(24, 'king@king.king', '$2y$10$mtFgPoVxEGQc6UmOdYKhEOjyoCTiyuTf.aw8DR.4fChBsjTDITxhm', '1990-08-06', '2020-08-30 22:30:49.000000', 'Ivan', 'Horvatić', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vozila`
--

CREATE TABLE `vozila` (
  `id` int(5) NOT NULL,
  `proizvodjac` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `model` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `registracija` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `datum` date NOT NULL,
  `motor` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vrsta_goriva` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `stanje_goriva` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `kilometraza` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vozila`
--

INSERT INTO `vozila` (`id`, `proizvodjac`, `model`, `registracija`, `datum`, `motor`, `vrsta_goriva`, `stanje_goriva`, `status`, `kilometraza`) VALUES
(1, 'HHH', '25G', 'ZG-10000-AB', '2022-08-12', 'V8', 'benzin', '55.58', 'posudjeno', 158998),
(4, 'ASD', 'KUJ', ' Du-999-AD', '2222-08-15', '999', 'dizel', '20', 'dostupno', 444),
(5, 'fffffff', 'dddd', 'dddddd', '2020-08-06', '9595', '99999', '9595', 'dostupno', 555555),
(6, 'Audiaaaaa', '8888sssss', '5555', '9599-05-08', '2222', '22222', '2222', 'dostupno', 55555),
(8, 'renault', 'clio', '123', '2020-08-07', 'V8', 'benzin', '55', 'dostupno', 2222),
(9, 'UUU', 'AAA', '989898', '2020-08-17', 'Tomos Mali', 'Euro95', 'FULL', 'dostupno', 158998),
(10, 'UUU', 'AAA', 'eeeeeeeeeeeeeeeeeeee', '2020-08-17', 'Tomos Mali', 'Euro95', 'FULL', 'dostupno', 158998),
(11, '151515', '666', '666', '2220-02-23', '5555', '9989898', '98989', 'dostupno', 150),
(12, '888', 'dhsdgsg', 'fsgdg', '2020-08-17', 'safasfas', 'sdfddd', '20', 'dostupno', 9999),
(13, 'renault', '666', '111', '2200-02-22', '222', '333', '666', 'dostupno', 665),
(14, 'fff', '569', '123', '2021-01-06', 'Tomos', 'benzin', '888', 'dostupno', 9999),
(15, 'sss', 'sss', 'sss', '2020-08-02', 'Tomos Mali', 'sss', '555', 'dostupno', 20000),
(16, 'Mercedez', 'AAA', '5555', '2020-08-14', '999', 'benzin', '55', 'dostupno', 55555),
(17, 'Mercedez', 'Benz', '123456789', '2020-07-27', 'V8', 'benzin', '55', 'dostupno', 170000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aktivnosti`
--
ALTER TABLE `aktivnosti`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vozila`
--
ALTER TABLE `vozila`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aktivnosti`
--
ALTER TABLE `aktivnosti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `vozila`
--
ALTER TABLE `vozila`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
