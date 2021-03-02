SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `nursery` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `nursery`;

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `dashboard` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module_id` int(10) UNSIGNED NOT NULL,
  `track` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `rows` int(11) NOT NULL,
  `cols` int(11) NOT NULL,
  `hidden` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `humidity` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `value` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5960 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `sensor_id` int(10) UNSIGNED NOT NULL,
  `sensor_address` varchar(4) DEFAULT NULL,
  `sensor_channel` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`,`sensor_id`),
  KEY `sensor_relation` (`sensor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `moisture` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `value` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5959 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pressure` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `value` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=968 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `sensors` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `library` varchar(255) DEFAULT NULL,
  `label` varchar(255) NOT NULL,
  `seesaw` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `spectral` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `f1` int(11) NOT NULL,
  `f2` int(11) NOT NULL,
  `f3` int(11) NOT NULL,
  `f4` int(11) NOT NULL,
  `f5` int(11) NOT NULL,
  `f6` int(11) NOT NULL,
  `f7` int(11) NOT NULL,
  `f8` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5959 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `temperature` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `value` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5961 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `light` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `value` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5961 DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `ultraviolet` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `value` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5961 DEFAULT CHARSET=utf8mb4;

ALTER TABLE `dashboard`
  ADD CONSTRAINT `module_relation` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`);

ALTER TABLE `modules`
  ADD CONSTRAINT `category_relation` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `sensor_relation` FOREIGN KEY (`sensor_id`) REFERENCES `sensors` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
