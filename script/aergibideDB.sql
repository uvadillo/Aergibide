-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 11-11-2020 a las 13:44:21
-- Versión del servidor: 8.0.22-0ubuntu0.20.04.2
-- Versión de PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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

DROP TABLE IF EXISTS `ANSWERS`;
CREATE TABLE `ANSWERS` (
  `id_question` int NOT NULL,
  `id_answer` int NOT NULL,
  `id_user` int NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `best_answer` tinyint(1) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `LIKES_ANSWERS`
--

DROP TABLE IF EXISTS `LIKES_ANSWERS`;
CREATE TABLE `LIKES_ANSWERS` (
  `id_question` int NOT NULL,
  `id_answer` int NOT NULL,
  `id_user` int NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `LIKES_QUESTIONS`
--

DROP TABLE IF EXISTS `LIKES_QUESTIONS`;
CREATE TABLE `LIKES_QUESTIONS` (
  `id_user` int NOT NULL,
  `id_question` int NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MEDIA_ANSWERS`
--

DROP TABLE IF EXISTS `MEDIA_ANSWERS`;
CREATE TABLE `MEDIA_ANSWERS` (
  `id_question` int NOT NULL,
  `id_answer` int NOT NULL,
  `id_media` int NOT NULL,
  `media` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MEDIA_QUESTIONS`
--

DROP TABLE IF EXISTS `MEDIA_QUESTIONS`;
CREATE TABLE `MEDIA_QUESTIONS` (
  `id_question` int NOT NULL,
  `id_media` int NOT NULL,
  `media` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `QUESTIONS`
--

DROP TABLE IF EXISTS `QUESTIONS`;
CREATE TABLE `QUESTIONS` (
  `id_question` int NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `id_user` int NOT NULL,
  `id_topic` int NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TOPICS`
--

DROP TABLE IF EXISTS `TOPICS`;
CREATE TABLE `TOPICS` (
  `id_topic` int NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `USERS`
--

DROP TABLE IF EXISTS `USERS`;
CREATE TABLE `USERS` (
  `id_user` int NOT NULL,
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `surname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userType` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `biography` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
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
-- Indices de la tabla `MEDIA_ANSWERS`
--
ALTER TABLE `MEDIA_ANSWERS`
  ADD PRIMARY KEY (`id_question`,`id_answer`,`id_media`),
  ADD KEY `id_answer` (`id_answer`);

--
-- Indices de la tabla `MEDIA_QUESTIONS`
--
ALTER TABLE `MEDIA_QUESTIONS`
  ADD PRIMARY KEY (`id_media`,`id_question`),
  ADD KEY `id_question` (`id_question`);

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
  MODIFY `id_answer` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `MEDIA_QUESTIONS`
--
ALTER TABLE `MEDIA_QUESTIONS`
  MODIFY `id_media` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `QUESTIONS`
--
ALTER TABLE `QUESTIONS`
  MODIFY `id_question` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TOPICS`
--
ALTER TABLE `TOPICS`
  MODIFY `id_topic` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `USERS`
--
ALTER TABLE `USERS`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ANSWERS`
--
ALTER TABLE `ANSWERS`
  ADD CONSTRAINT `ANSWERS_ibfk_1` FOREIGN KEY (`id_question`) REFERENCES `QUESTIONS` (`id_question`),
  ADD CONSTRAINT `ANSWERS_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `USERS` (`id_user`);

--
-- Filtros para la tabla `LIKES_ANSWERS`
--
ALTER TABLE `LIKES_ANSWERS`
  ADD CONSTRAINT `LIKES_ANSWERS_ibfk_1` FOREIGN KEY (`id_question`) REFERENCES `ANSWERS` (`id_question`),
  ADD CONSTRAINT `LIKES_ANSWERS_ibfk_2` FOREIGN KEY (`id_answer`) REFERENCES `ANSWERS` (`id_answer`),
  ADD CONSTRAINT `LIKES_ANSWERS_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `USERS` (`id_user`);

--
-- Filtros para la tabla `LIKES_QUESTIONS`
--
ALTER TABLE `LIKES_QUESTIONS`
  ADD CONSTRAINT `LIKES_QUESTIONS_ibfk_2` FOREIGN KEY (`id_question`) REFERENCES `QUESTIONS` (`id_question`),
  ADD CONSTRAINT `LIKES_QUESTIONS_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `USERS` (`id_user`);

--
-- Filtros para la tabla `MEDIA_ANSWERS`
--
ALTER TABLE `MEDIA_ANSWERS`
  ADD CONSTRAINT `MEDIA_ANSWERS_ibfk_1` FOREIGN KEY (`id_question`) REFERENCES `ANSWERS` (`id_question`),
  ADD CONSTRAINT `MEDIA_ANSWERS_ibfk_2` FOREIGN KEY (`id_answer`) REFERENCES `ANSWERS` (`id_answer`);

--
-- Filtros para la tabla `MEDIA_QUESTIONS`
--
ALTER TABLE `MEDIA_QUESTIONS`
  ADD CONSTRAINT `MEDIA_QUESTIONS_ibfk_1` FOREIGN KEY (`id_question`) REFERENCES `QUESTIONS` (`id_question`);

--
-- Filtros para la tabla `QUESTIONS`
--
ALTER TABLE `QUESTIONS`
  ADD CONSTRAINT `QUESTIONS_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `USERS` (`id_user`),
  ADD CONSTRAINT `QUESTIONS_ibfk_2` FOREIGN KEY (`id_topic`) REFERENCES `TOPICS` (`id_topic`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
