-- MySQL dump 10.17  Distrib 10.3.17-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: heick.family
-- ------------------------------------------------------
-- Server version	10.3.17-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `family`
--

DROP TABLE IF EXISTS `family`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `family` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'primary key',
  `name` char(200) NOT NULL,
  `parent-x` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'mother xx',
  `parent-y` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'father xy',
  `partner` int(10) unsigned NOT NULL DEFAULT 0,
  `dob` char(10) NOT NULL DEFAULT '0000-00-00' COMMENT 'date of birth ymd',
  `dod` char(10) NOT NULL DEFAULT '0000-00-00' COMMENT 'date of death ymd',
  `lastupdated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `family`
--

LOCK TABLES `family` WRITE;
/*!40000 ALTER TABLE `family` DISABLE KEYS */;
INSERT INTO `family` VALUES (1,'Matthew James Heick',4,3,2,'1981-10-14','0000-00-00','2022-01-12 02:04:20'),(2,'Molly Marie Neri',0,0,1,'1981-01-17','0000-00-00','2022-01-12 02:04:20'),(3,'James Robert Heick',0,0,0,'0000-00-00','0000-00-00','2022-01-12 02:04:20'),(4,'Ardelle Marie Myers',0,0,0,'0000-00-00','0000-00-00','2022-01-12 02:04:20'),(5,'Brian Patrick Heick',4,3,0,'1983-04-28','0000-00-00','2022-02-09 02:00:53'),(6,'Michelle Lee Heick',4,3,0,'0000-00-00','0000-00-00','2022-02-09 02:01:30'),(7,'Michael Shane Heick',4,3,0,'0000-00-00','0000-00-00','2022-02-09 02:01:58'),(8,'Mark Andrew Heick',4,3,0,'0000-00-00','0000-00-00','2022-02-09 02:02:27'),(9,'Jennifer Renee Everson',0,0,0,'0000-00-00','0000-00-00','2022-02-09 02:03:20'),(10,'Devon Orion Heick',1,9,0,'0000-00-00','0000-00-00','2022-02-09 02:04:39'),(11,'Angelique Renee-Marie Heick',1,9,0,'0000-00-00','0000-00-00','2022-02-09 02:03:53'),(12,'James Rexford Heick',1,9,0,'0000-00-00','0000-00-00','2022-02-09 02:04:04'),(13,'Savannah Lee Heick',1,9,0,'0000-00-00','0000-00-00','2022-02-09 02:04:18'),(14,'Cara Mia Heick',1,2,0,'0000-00-00','0000-00-00','2022-02-09 02:06:39'),(15,'Michael Muraco',0,0,0,'0000-00-00','0000-00-00','2022-02-09 02:08:19'),(16,'Kariah Grace Neri',2,15,0,'0000-00-00','0000-00-00','2022-02-09 02:08:42');
/*!40000 ALTER TABLE `family` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-02-09  2:23:40
