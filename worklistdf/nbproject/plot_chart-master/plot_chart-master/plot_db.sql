# ************************************************************
# Database: plot_db
# Generation Time: 2015-08-01 05:56:07 +0000
# ************************************************************

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

# Dump of table enquete
# ------------------------------------------------------------

DROP TABLE IF EXISTS `enquete`;

CREATE TABLE `enquete` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `problema` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `enquete` WRITE;
/*!40000 ALTER TABLE `enquete` DISABLE KEYS */;

INSERT INTO `enquete` (`id`, `problema`)
VALUES
	(1,'Violência'),
	(2,'Transporte Público'),
	(3,'Transporte Público'),
	(4,'Transporte Público'),
	(5,'Desemprego'),
	(6,'Transporte Público'),
	(7,'Violência'),
	(8,'Desemprego'),
	(9,'Saúde'),
	(10,'Poluição'),
	(11,'Saúde'),
	(12,'Educação'),
	(13,'Educação'),
	(14,'Desigualdade social'),
	(15,'Desigualdade social'),
	(16,'Violência'),
	(17,'Educação'),
	(18,'Educação'),
	(19,'Saúde'),
	(20,'Saúde'),
	(21,'Saúde'),
	(22,'Educação'),
	(23,'Educação'),
	(24,'Poluição'),
	(25,'Violência'),
	(26,'Violência'),
	(27,'Transporte Público'),
	(28,'Transporte Público'),
	(29,'Poluição'),
	(30,'Poluição'),
	(31,'Educação'),
	(32,'Desemprego'),
	(33,'Desemprego');

/*!40000 ALTER TABLE `enquete` ENABLE KEYS */;
UNLOCK TABLES;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
