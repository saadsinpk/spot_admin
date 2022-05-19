-- Adminer 4.7.8 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `offer`;
CREATE TABLE `offer` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `requirements` longtext DEFAULT NULL,
  `icon` text DEFAULT NULL,
  `payout` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `device` text DEFAULT NULL,
  `categories` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2022-05-19 13:14:24
