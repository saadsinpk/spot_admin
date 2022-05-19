-- Adminer 4.7.8 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `offerwalls`;
CREATE TABLE `offerwalls` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `points` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `featured` varchar(2) NOT NULL,
  `position` varchar(10) NOT NULL,
  `status` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `offerwalls` (`id`, `name`, `subtitle`, `url`, `points`, `image`, `type`, `featured`, `position`, `status`) VALUES
(17,	'Ad GateMedia',	'',	'https://api.adgatemedia.com/v2/offers?aff=87678&api_key=8216315fce32bc01c28af524837f5dcd&wall_code=oKuTqQ&countries=BR&orderby=payout',	'',	'',	'',	'',	'',	'1'),
(18,	'Wannads',	'',	'http://api.wannads.com/v2/offers?api_key=613a7124dbc06523590742&api_secret=12370d64cb&country=BR&device=android',	'',	'',	'',	'',	'',	'1'),
(19,	'KiwiWall',	'',	'https://www.kiwiwall.com/get-offers/PsiNiO4ces58PdkOkMocFGkJer5i7LLw/?country=BR',	'',	'',	'',	'',	'',	'1'),
(20,	'CPA Lead',	'',	'https://cpalead.com/dashboard/reports/campaign_json.php?country=BR',	'',	'',	'',	'',	'',	'1');

-- 2022-05-19 13:27:33
