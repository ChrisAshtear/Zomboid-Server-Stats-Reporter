-- Adminer 4.8.1 MySQL 8.0.27 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;

SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `Zombo`;
USE `Zombo`;

CREATE TABLE IF NOT EXISTS `Game` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dayofmonth` int DEFAULT NULL,
  `month` int DEFAULT NULL,
  `daysSinceStart` int DEFAULT NULL,
  `timeOfDay` time DEFAULT NULL,
  `curPlayers` int DEFAULT NULL,
  `name` text,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `maxPlayers` smallint DEFAULT NULL,
  `startDay` smallint DEFAULT NULL,
  `startMonth` smallint DEFAULT NULL,
  `startYear` smallint DEFAULT NULL,
  `year` smallint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `Players` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `charname` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `data` blob,
  `x` decimal(10,0) DEFAULT NULL,
  `y` decimal(10,0) DEFAULT NULL,
  `lastOnline` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- 2022-04-21 13:44:08