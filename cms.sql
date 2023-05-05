-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 05 Maj 2023, 22:15
-- Wersja serwera: 10.4.22-MariaDB
-- Wersja PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `cms`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `likes`
--

INSERT INTO `likes` (`id`, `post_id`, `user_id`, `value`) VALUES
(4, 6, 2, 1),
(6, 5, 2, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `filename` char(96) NOT NULL,
  `userId` int(11) NOT NULL,
  `removed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `post`
--

INSERT INTO `post` (`id`, `timestamp`, `filename`, `userId`, `removed`) VALUES
(1, '2023-04-23 12:02:42', 'a1df3cdaee1f1b7937cc173be2a77dc75db7e563788fd763e54b39468a1e5d28', 1, 0),
(2, '2023-04-23 12:11:26', 'ddba85d4dd91a419f1dd6fc58c24bc814928ca8cecd228d08e10e2f605a55e6e', 1, 0),
(3, '2023-04-23 12:40:59', '5923def15d060601b783c94ece64b84e2875d4b2bf3bcdeb8363412498cd23a4', 2, 0),
(4, '2023-04-30 16:40:51', 'f06295925a8f4b00ff03272e7bf86b20d39cac5ddd63a00fb5a22c0362086de4', 1, 1),
(5, '2023-04-30 16:55:23', '1eb46b2e4fa27b144583583277261051f447674f23d4c1a21b7f414ed402e4e8', 2, 0),
(6, '2023-04-30 17:11:51', 'b14a8dfc3d49f369a5209f89de4ba73f290a9b89d27cac5711734e574c45be30', 2, 0),
(7, '2023-05-04 16:28:48', 'bec81fe4fa0b98462c507a3461b3b1aa47782aa30751d91b45cd4fa45ec27490', 2, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id`, `email`, `password`) VALUES
(1, 'jan@kowalski.pl', '$argon2i$v=19$m=65536,t=4,p=1$ay5PVEJ3YjlEdnM0a0EveQ$Vp7GeKyRSJSHzThS52YmQpr2SQ1JcEaZ3z78M6EUNaA'),
(2, 'test@54321.com', '$argon2i$v=19$m=65536,t=4,p=1$RW9xRjZNME5UQXpjNW9PYQ$BsAYrFNXS1v1yjvnXdcUgM424XNoWEhP4i1YkAc+SU0');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT dla tabeli `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
