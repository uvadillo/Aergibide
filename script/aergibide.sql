-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 27-11-2020 a las 08:46:30
-- Versión del servidor: 5.7.32-0ubuntu0.18.04.1
-- Versión de PHP: 7.2.24-0ubuntu0.18.04.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `aergibide`
--
CREATE DATABASE IF NOT EXISTS `aergibide` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `aergibide`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ANSWERS`
--

CREATE TABLE `ANSWERS` (
  `id_question` int(11) NOT NULL,
  `id_answer` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `best_answer` tinyint(1) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `answerImage` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `LIKES_ANSWERS`
--

CREATE TABLE `LIKES_ANSWERS` (
  `id_question` int(11) NOT NULL,
  `id_answer` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `LIKES_QUESTIONS`
--

CREATE TABLE `LIKES_QUESTIONS` (
  `id_user` int(11) NOT NULL,
  `id_question` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `QUESTIONS`
--

CREATE TABLE `QUESTIONS` (
  `id_question` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_topic` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `questionImage` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TOPICS`
--

CREATE TABLE `TOPICS` (
  `id_topic` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `USERS`
--

CREATE TABLE `USERS` (
  `id_user` int(11) NOT NULL,
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `surname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userType` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `biography` tinytext COLLATE utf8_unicode_ci,
  `profile_image` longtext COLLATE utf8_unicode_ci,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ANSWERS`
--
ALTER TABLE `ANSWERS`
  ADD PRIMARY KEY (`id_answer`,`id_question`),
  ADD KEY `ANSWERS_FK_QUESTIONS` (`id_question`),
  ADD KEY `ANSWERS_FK_USERS` (`id_user`);

--
-- Indices de la tabla `LIKES_ANSWERS`
--
ALTER TABLE `LIKES_ANSWERS`
  ADD PRIMARY KEY (`id_question`,`id_answer`,`id_user`),
  ADD KEY `id_answer` (`id_answer`),
  ADD KEY `id_user` (`id_user`);

--
-- Indices de la tabla `LIKES_QUESTIONS`
--
ALTER TABLE `LIKES_QUESTIONS`
  ADD PRIMARY KEY (`id_user`,`id_question`),
  ADD KEY `LIKESQ_FK_QUESTIONS` (`id_question`);

--
-- Indices de la tabla `QUESTIONS`
--
ALTER TABLE `QUESTIONS`
  ADD PRIMARY KEY (`id_question`),
  ADD KEY `USERS_FK_QUESTIONS` (`id_user`),
  ADD KEY `QUESTIONS_FK_TOPICS` (`id_topic`);

--
-- Indices de la tabla `TOPICS`
--
ALTER TABLE `TOPICS`
  ADD PRIMARY KEY (`id_topic`);

--
-- Indices de la tabla `USERS`
--
ALTER TABLE `USERS`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ANSWERS`
--
ALTER TABLE `ANSWERS`
  MODIFY `id_answer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `QUESTIONS`
--
ALTER TABLE `QUESTIONS`
  MODIFY `id_question` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `TOPICS`
--
ALTER TABLE `TOPICS`
  MODIFY `id_topic` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `USERS`
--
ALTER TABLE `USERS`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ANSWERS`
--
ALTER TABLE `ANSWERS`
  ADD CONSTRAINT `ANSWERS_ibfk_1` FOREIGN KEY (`id_question`) REFERENCES `QUESTIONS` (`id_question`) ON DELETE CASCADE,
  ADD CONSTRAINT `ANSWERS_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `USERS` (`id_user`);

--
-- Filtros para la tabla `LIKES_ANSWERS`
--
ALTER TABLE `LIKES_ANSWERS`
  ADD CONSTRAINT `LIKES_ANSWERS_ibfk_1` FOREIGN KEY (`id_question`) REFERENCES `ANSWERS` (`id_question`) ON DELETE CASCADE,
  ADD CONSTRAINT `LIKES_ANSWERS_ibfk_2` FOREIGN KEY (`id_answer`) REFERENCES `ANSWERS` (`id_answer`) ON DELETE CASCADE,
  ADD CONSTRAINT `LIKES_ANSWERS_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `USERS` (`id_user`) ON DELETE CASCADE;

--
-- Filtros para la tabla `LIKES_QUESTIONS`
--
ALTER TABLE `LIKES_QUESTIONS`
  ADD CONSTRAINT `LIKES_QUESTIONS_ibfk_2` FOREIGN KEY (`id_question`) REFERENCES `QUESTIONS` (`id_question`) ON DELETE CASCADE,
  ADD CONSTRAINT `LIKES_QUESTIONS_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `USERS` (`id_user`) ON DELETE CASCADE;

--
-- Filtros para la tabla `QUESTIONS`
--
ALTER TABLE `QUESTIONS`
  ADD CONSTRAINT `QUESTIONS_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `USERS` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `QUESTIONS_ibfk_2` FOREIGN KEY (`id_topic`) REFERENCES `TOPICS` (`id_topic`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

INSERT INTO `TOPICS` (`id_topic`, `name`) VALUES
(1, 'Alas'),
(2, 'Motor'),
(3, 'Vuelos'),
(4, 'Pasajeros'),
(5, 'Ruedas'),
(6, 'Combustible'),
(7, 'Aterrizajes'),
(8, 'Despegue'),
(9, 'Destinos'),
(10, 'Tripulacion'),
(11, 'Emergencias'),
(12, 'Prioritarios');