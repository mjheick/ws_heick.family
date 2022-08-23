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
-- Table structure for table `audit`
--

DROP TABLE IF EXISTS `audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit` (
  `pk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry` datetime NOT NULL,
  `who` text DEFAULT NULL,
  `query` text DEFAULT NULL,
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit`
--

LOCK TABLES `audit` WRITE;
/*!40000 ALTER TABLE `audit` DISABLE KEYS */;
INSERT INTO `audit` VALUES (1,'2022-08-23 02:00:41','google:103331904967854672425','UPDATE `family` SET `name`=\"Kiela Thompson\",`parent-bio-x`=\"0\",`parent-bio-y`=\"0\",`parent-adopt-x`=\"0\",`parent-adopt-y`=\"0\",`partner`=\"0\",`dob`=\"0000-00-00\",`dod`=\"0000-00-00\" WHERE `id`=40 LIMIT 1'),(2,'2022-08-23 02:01:56','google:103331904967854672425','UPDATE `family` SET `name`=\"Kiela Thompson\",`parent-bio-x`=\"46\",`parent-bio-y`=\"28\",`parent-adopt-x`=\"0\",`parent-adopt-y`=\"0\",`partner`=\"0\",`dob`=\"0000-00-00\",`dod`=\"0000-00-00\" WHERE `id`=40 LIMIT 1');
/*!40000 ALTER TABLE `audit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `family`
--

DROP TABLE IF EXISTS `family`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `family` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'primary key',
  `name` char(200) NOT NULL,
  `parent-bio-x` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'mother xx',
  `parent-bio-y` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'father xy',
  `parent-adopt-x` int(10) unsigned NOT NULL DEFAULT 0,
  `parent-adopt-y` int(10) unsigned NOT NULL DEFAULT 0,
  `partner` int(10) unsigned NOT NULL DEFAULT 0,
  `dob` char(10) NOT NULL DEFAULT '0000-00-00' COMMENT 'date of birth ymd',
  `dod` char(10) NOT NULL DEFAULT '0000-00-00' COMMENT 'date of death ymd',
  `lastupdated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `family`
--

LOCK TABLES `family` WRITE;
/*!40000 ALTER TABLE `family` DISABLE KEYS */;
INSERT INTO `family` VALUES (1,'Matthew James Heick',4,3,0,0,2,'1981-10-14','0000-00-00','2022-01-12 02:04:20'),(2,'Molly Marie Neri Heick',0,0,0,0,1,'1981-01-17','0000-00-00','2022-08-22 00:06:49'),(3,'James Robert Heick',0,0,0,0,0,'1954-02-06','0000-00-00','2022-08-20 03:57:45'),(4,'Ardelle Marie Myers Hintz',30,33,0,0,0,'1954-10-30','0000-00-00','2022-08-21 23:20:27'),(5,'Brian Patrick Heick',4,3,0,0,0,'1983-04-28','0000-00-00','2022-02-09 02:00:53'),(6,'Michelle Lee Heick Elmore',4,3,0,0,0,'0000-00-00','0000-00-00','2022-08-21 23:33:05'),(7,'Michael Shane Heick',4,3,0,0,0,'0000-00-00','0000-00-00','2022-02-09 02:01:58'),(8,'Mark Andrew Heick',4,3,0,0,0,'0000-00-00','0000-00-00','2022-02-09 02:02:27'),(9,'Jennifer Renee Everson Cejka',18,19,0,0,0,'1984-06-14','0000-00-00','2022-08-21 23:07:26'),(10,'Devon Orion Heick',1,9,0,0,0,'2003-04-15','0000-00-00','2022-08-20 03:56:02'),(11,'Angelique Renee-Marie Heick',1,9,0,2,0,'2004-06-18','0000-00-00','2022-08-20 03:50:09'),(12,'James Rexford Heick',1,9,0,0,0,'2006-01-11','0000-00-00','2022-08-20 03:56:18'),(13,'Savannah Lee Heick',1,9,0,0,0,'2007-12-27','0000-00-00','2022-08-20 03:56:28'),(14,'Cara Mia Heick',1,2,0,0,0,'2018-09-21','0000-00-00','2022-08-20 03:56:52'),(15,'Michael Christopher Muraco',0,0,0,0,0,'0000-00-00','0000-00-00','2022-08-21 23:03:46'),(16,'Kariah Grace Neri',2,15,0,0,0,'2007-09-14','0000-00-00','2022-08-20 03:56:41'),(17,'Derpy McDerpFace Heick',17,17,0,0,17,'1979-01-01','2022-08-15','2022-08-19 20:10:29'),(18,'Grant Rexford Everson',0,0,0,0,19,'1947-10-11','2016-11-25','2022-08-21 22:57:23'),(19,'Debra Riley Everson',26,25,0,0,18,'0000-00-00','0000-00-00','2022-08-21 23:08:22'),(20,'Rylunn Lloyd Charles Thompson',46,28,0,0,0,'2003-01-16','2021-03-01','2022-08-22 02:26:02'),(21,'Elizabeth Riehle Harter',0,0,0,0,0,'1909-10-13','2011-06-20','2022-08-21 23:01:33'),(22,'Lillian Tokarz',21,24,0,0,0,'0000-00-00','0000-00-00','2022-08-21 23:03:26'),(23,'Mary Heick',21,24,0,0,0,'0000-00-00','0000-00-00','2022-08-21 23:03:19'),(24,'Ludwig Harter',0,0,0,0,21,'0000-00-00','0000-00-00','2022-08-21 23:03:11'),(25,'Jean Louise VanGuilder Riley',0,0,0,0,26,'1939-04-25','2009-08-19','2022-08-21 23:08:03'),(26,'Edward J Riley',0,0,0,0,25,'0000-00-00','1995-01-02','2022-08-22 00:04:16'),(27,'Shane Thompson',0,19,0,0,0,'0000-00-00','0000-00-00','2022-08-21 23:09:27'),(28,'Michael Thompson',0,19,0,0,0,'0000-00-00','0000-00-00','2022-08-21 23:09:41'),(29,'Michael James Myers',30,33,0,0,0,'1958-01-20','2005-07-30','2022-08-22 00:06:34'),(30,'Ardelle Lloyd Burns Leach',0,45,0,0,34,'1933-10-23','2005-02-27','2022-08-21 23:59:44'),(31,'Darryl Eugene Lewis',0,0,0,0,0,'1963-06-14','1990-02-26','2022-08-21 23:15:18'),(32,'Lisa Ann Myers',30,33,0,0,0,'0000-00-00','0000-00-00','2022-08-21 23:30:44'),(33,'William Edward \"Bill\" Myers Jr',0,0,0,0,0,'1932-02-28','1994-08-07','2022-08-22 02:09:24'),(34,'Andrew F Leach',0,0,0,0,30,'1938-08-09','1994-04-01','2022-08-21 23:22:31'),(35,'Andrew F Leach Jr',0,34,0,0,0,'0000-00-00','0000-00-00','2022-08-22 00:04:20'),(36,'William F Leach',0,34,0,0,0,'0000-00-00','0000-00-00','2022-08-21 23:24:52'),(37,'Gail Leach Bush',0,34,0,0,0,'0000-00-00','0000-00-00','2022-08-21 23:25:13'),(38,'William K Myers',30,33,0,0,0,'0000-00-00','0000-00-00','2022-08-21 23:26:54'),(39,'Steven E Myers',30,33,0,0,0,'0000-00-00','0000-00-00','2022-08-21 23:27:13'),(40,'Kiela Thompson',46,28,0,0,0,'0000-00-00','0000-00-00','2022-08-23 02:01:56'),(41,'Robert F Myers',30,33,0,0,0,'0000-00-00','0000-00-00','2022-08-21 23:27:53'),(42,'Mark R Myers',30,33,0,0,0,'0000-00-00','0000-00-00','2022-08-21 23:28:20'),(43,'Janine Myers',30,33,0,0,0,'0000-00-00','0000-00-00','2022-08-21 23:30:21'),(44,'Anna T Myers Hall-Beede',30,33,0,0,0,'1966-08-02','2018-09-08','2022-08-21 23:31:30'),(45,'Dorothy Vivian Young Steinhebel',0,0,0,0,0,'1904-05-02','2000-01-30','2022-08-21 23:59:04'),(46,'Lindsey Michelle Rodgers Thompson',0,47,0,0,0,'1983-11-12','0000-00-00','2022-08-22 02:25:11'),(47,'Mary Schaffer',0,0,0,0,0,'0000-00-00','0000-00-00','2022-08-22 02:24:56');
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

-- Dump completed on 2022-08-23  2:02:26
