﻿# Host: localhost  (Version: 5.6.16)
# Date: 2016-06-06 01:00:45
# Generator: MySQL-Front 5.3  (Build 2.36)

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='NO_ENGINE_SUBSTITUTION' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

USE `bicorp`;

#
# Source for table "user_empresas"
#

DROP TABLE IF EXISTS `user_empresas`;
CREATE TABLE `user_empresas` (
  `user_empresa_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `rut` varchar(20) NOT NULL DEFAULT '0',
  `razon_social` varchar(255) DEFAULT NULL,
  `createdAt` int(10) unsigned NOT NULL,
  `rutaArchivo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_empresa_id`),
  KEY `rut_empresa` (`rut`),
  KEY `user_id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

#
# Data for table "user_empresas"
#



/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
