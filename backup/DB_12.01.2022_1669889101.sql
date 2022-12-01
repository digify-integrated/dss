-- MariaDB dump 10.19  Distrib 10.4.25-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: dssdb
-- ------------------------------------------------------
-- Server version	10.4.25-MariaDB

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
-- Table structure for table `global_role`
--

DROP TABLE IF EXISTS `global_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_role` (
  `ROLE_ID` varchar(50) NOT NULL,
  `ROLE` varchar(100) NOT NULL,
  `ROLE_DESCRIPTION` varchar(200) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) NOT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ROLE_ID`),
  KEY `global_role_index` (`ROLE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_role`
--

LOCK TABLES `global_role` WRITE;
/*!40000 ALTER TABLE `global_role` DISABLE KEYS */;
INSERT INTO `global_role` VALUES ('1','Administrator','Administrator','TL-2',NULL);
/*!40000 ALTER TABLE `global_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_role_user_account`
--

DROP TABLE IF EXISTS `global_role_user_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_role_user_account` (
  `ROLE_ID` varchar(50) NOT NULL,
  `USERNAME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_role_user_account`
--

LOCK TABLES `global_role_user_account` WRITE;
/*!40000 ALTER TABLE `global_role_user_account` DISABLE KEYS */;
INSERT INTO `global_role_user_account` VALUES ('1','ADMIN');
/*!40000 ALTER TABLE `global_role_user_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_system_code`
--

DROP TABLE IF EXISTS `global_system_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_system_code` (
  `SYSTEM_TYPE` varchar(20) NOT NULL,
  `SYSTEM_CODE` varchar(20) NOT NULL,
  `SYSTEM_DESCRIPTION` varchar(100) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) NOT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_system_code`
--

LOCK TABLES `global_system_code` WRITE;
/*!40000 ALTER TABLE `global_system_code` DISABLE KEYS */;
INSERT INTO `global_system_code` VALUES ('SYSTYPE','SYSTYPE','System Code','TL-4',NULL),('SYSTYPE','MODULECAT','Module Category','TL-5',NULL),('MODULECAT','TECHNICAL','Technical','TL-6',NULL);
/*!40000 ALTER TABLE `global_system_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_system_parameters`
--

DROP TABLE IF EXISTS `global_system_parameters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_system_parameters` (
  `PARAMETER_ID` int(11) NOT NULL,
  `PARAMETER` varchar(100) NOT NULL,
  `PARAMETER_DESCRIPTION` varchar(100) NOT NULL,
  `PARAMETER_EXTENSION` varchar(10) DEFAULT NULL,
  `PARAMETER_NUMBER` int(11) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`PARAMETER_ID`),
  KEY `global_system_parameter_index` (`PARAMETER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_system_parameters`
--

LOCK TABLES `global_system_parameters` WRITE;
/*!40000 ALTER TABLE `global_system_parameters` DISABLE KEYS */;
INSERT INTO `global_system_parameters` VALUES (1,'System Parameter','Parameter for system parameters.','',4,'TL-15',NULL),(2,'Transaction Log','Parameter for transaction logs.','TL-',33,'TL-16','UPD->ADMIN->2022-12-01 02:05:33'),(3,'Module','Parameter for modules.','',1,'TL-17','UPD->ADMIN->2022-12-01 01:34:24'),(4,'Page','Parameter for pages.','',6,'TL-25','UPD->ADMIN->2022-12-01 02:05:33'),(5,'Action','Parameter for actions.','',15,'TL-33',NULL);
/*!40000 ALTER TABLE `global_system_parameters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_transaction_log`
--

DROP TABLE IF EXISTS `global_transaction_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_transaction_log` (
  `TRANSACTION_LOG_ID` varchar(100) NOT NULL,
  `USERNAME` varchar(50) NOT NULL,
  `LOG_TYPE` varchar(100) NOT NULL,
  `LOG_DATE` datetime NOT NULL,
  `LOG` varchar(4000) DEFAULT NULL,
  KEY `global_transaction_log_index` (`TRANSACTION_LOG_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_transaction_log`
--

LOCK TABLES `global_transaction_log` WRITE;
/*!40000 ALTER TABLE `global_transaction_log` DISABLE KEYS */;
INSERT INTO `global_transaction_log` VALUES ('TL-1','ADMIN','Update','2022-09-20 13:37:24','User ADMIN updated user account password.'),('TL-1','ADMIN','Log In','2022-09-20 13:37:28','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-09-21 09:59:22','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-09-22 13:34:58','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-09-23 08:35:00','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-24 17:01:02','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-24 17:01:20','User ADMIN logged in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:01:45','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-10-24 17:01:52','User ADMIN logged in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:02:56','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-10-24 17:03:37','User ADMIN logged in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:38','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:39','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:40','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:40','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:41','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-25 09:51:47','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-25 09:51:50','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-10-25 09:51:52','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-26 08:57:46','User ADMIN logged in.'),('TL-1','ADMIN','Attempt Log In','2022-10-27 13:02:32','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-10-27 13:02:36','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-28 09:08:02','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-29 21:45:41','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-30 09:07:23','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-30 18:47:01','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-31 09:44:15','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-15 09:11:11','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-17 15:54:46','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-18 11:34:23','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-21 15:50:26','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-22 10:51:22','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-22 16:49:17','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-29 09:14:44','User ADMIN logged in.'),('TL-3','ADMIN','Update','2022-11-29 16:33:49','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:33:51','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:33:56','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:34:02','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:35:09','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:36:19','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:44:11','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:44:30','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:17','User ADMIN updated icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:17','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:45','User ADMIN updated icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:45','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:48','User ADMIN updated module icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:48','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:48','User ADMIN updated module icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:48','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:59','User ADMIN updated module icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:59','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:47:24','User ADMIN updated module icon.'),('TL-3','ADMIN','Update','2022-11-29 16:47:24','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:47:35','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 17:12:25','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 17:12:30','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 17:12:40','User ADMIN updated module.'),('TL-18','ADMIN','Insert','2022-11-29 17:13:15','User ADMIN inserted module.'),('TL-19','ADMIN','Insert','2022-11-29 17:14:06','User ADMIN inserted module.'),('TL-20','ADMIN','Insert','2022-11-30 12:34:02','User ADMIN inserted module.'),('TL-21','ADMIN','Insert','2022-11-30 12:34:11','User ADMIN inserted module.'),('TL-22','ADMIN','Insert','2022-11-30 12:35:09','User ADMIN inserted module.'),('TL-23','ADMIN','Insert','2022-11-30 12:35:41','User ADMIN inserted module.'),('TL-24','ADMIN','Insert','2022-11-30 12:36:17','User ADMIN inserted module.'),('TL-1','ADMIN','Log In','2022-11-30 17:56:03','User ADMIN logged in.'),('TL-24','ADMIN','Update','2022-11-30 18:03:01','User ADMIN updated module.'),('TL-1','ADMIN','Log In','2022-11-30 20:11:21','User ADMIN logged in.'),('TL-25','ADMIN','Insert','2022-11-30 20:18:14','User ADMIN inserted module.'),('TL-3','ADMIN','Update','2022-11-30 20:22:04','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-30 20:22:09','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-30 20:22:15','User ADMIN updated module.'),('TL-1','ADMIN','Log In','2022-12-01 10:41:23','User ADMIN logged in.'),('TL-26','ADMIN','Insert','2022-12-01 13:22:24','User ADMIN inserted page.'),('TL-27','ADMIN','Insert','2022-12-01 13:33:06','User ADMIN inserted page.'),('TL-28','ADMIN','Insert','2022-12-01 13:34:24','User ADMIN inserted module.'),('TL-29','ADMIN','Insert','2022-12-01 14:05:33','User ADMIN inserted page.'),('TL-26','ADMIN','Update','2022-12-01 14:29:29','User ADMIN updated page.');
/*!40000 ALTER TABLE `global_transaction_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_upload_file_type`
--

DROP TABLE IF EXISTS `global_upload_file_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_upload_file_type` (
  `UPLOAD_SETTING_ID` int(50) DEFAULT NULL,
  `FILE_TYPE` varchar(50) NOT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_upload_file_type`
--

LOCK TABLES `global_upload_file_type` WRITE;
/*!40000 ALTER TABLE `global_upload_file_type` DISABLE KEYS */;
INSERT INTO `global_upload_file_type` VALUES (1,'jpeg',NULL),(1,'svg',NULL),(1,'png',NULL),(1,'jpg',NULL);
/*!40000 ALTER TABLE `global_upload_file_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_upload_setting`
--

DROP TABLE IF EXISTS `global_upload_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_upload_setting` (
  `UPLOAD_SETTING_ID` int(50) NOT NULL,
  `UPLOAD_SETTING` varchar(200) NOT NULL,
  `DESCRIPTION` varchar(200) NOT NULL,
  `MAX_FILE_SIZE` double DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`UPLOAD_SETTING_ID`),
  KEY `global_upload_setting_index` (`UPLOAD_SETTING_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_upload_setting`
--

LOCK TABLES `global_upload_setting` WRITE;
/*!40000 ALTER TABLE `global_upload_setting` DISABLE KEYS */;
INSERT INTO `global_upload_setting` VALUES (1,'Module Icon','Upload setting for module icon.',5,'TL-14',NULL);
/*!40000 ALTER TABLE `global_upload_setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_user_account`
--

DROP TABLE IF EXISTS `global_user_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_user_account` (
  `USERNAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(200) NOT NULL,
  `FILE_AS` varchar(300) NOT NULL,
  `USER_STATUS` varchar(10) NOT NULL,
  `PASSWORD_EXPIRY_DATE` date NOT NULL,
  `FAILED_LOGIN` int(1) NOT NULL,
  `LAST_FAILED_LOGIN` datetime DEFAULT NULL,
  `LAST_CONNECTION_DATE` datetime DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) NOT NULL,
  PRIMARY KEY (`USERNAME`),
  KEY `global_user_account_index` (`USERNAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_user_account`
--

LOCK TABLES `global_user_account` WRITE;
/*!40000 ALTER TABLE `global_user_account` DISABLE KEYS */;
INSERT INTO `global_user_account` VALUES ('ADMIN','68aff5412f35ed76','Administrator','Active','2022-12-30',0,'2022-10-27 13:02:32','2022-12-01 10:41:23','TL-1');
/*!40000 ALTER TABLE `global_user_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technical_action`
--

DROP TABLE IF EXISTS `technical_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technical_action` (
  `ACTION_ID` varchar(100) NOT NULL,
  `ACTION_NAME` varchar(200) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) NOT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ACTION_ID`),
  KEY `technical_action_index` (`ACTION_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technical_action`
--

LOCK TABLES `technical_action` WRITE;
/*!40000 ALTER TABLE `technical_action` DISABLE KEYS */;
INSERT INTO `technical_action` VALUES ('1','Add Module','TL-7',NULL),('10','Delete Page Access Right','TL-24',NULL),('11','Add Action','TL-28',NULL),('12','Update Action','TL-29',NULL),('13','Delete Action','TL-30',NULL),('14','Add Action Access Right','TL-31',NULL),('15','Delete Action Access Right','TL-32',NULL),('2','Update Module','TL-8',NULL),('3','Delete Module','TL-9',NULL),('4','Add Module Access Right','TL-12',NULL),('5','Delete Module Access Right','TL-13',NULL),('6','Add Page','TL-20',NULL),('7','Update Page','TL-21',NULL),('8','Delete Page','TL-22',NULL),('9','Add Page Access Right','TL-23',NULL);
/*!40000 ALTER TABLE `technical_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technical_action_access_rights`
--

DROP TABLE IF EXISTS `technical_action_access_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technical_action_access_rights` (
  `ACTION_ID` varchar(100) NOT NULL,
  `ROLE_ID` varchar(100) NOT NULL,
  PRIMARY KEY (`ACTION_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technical_action_access_rights`
--

LOCK TABLES `technical_action_access_rights` WRITE;
/*!40000 ALTER TABLE `technical_action_access_rights` DISABLE KEYS */;
INSERT INTO `technical_action_access_rights` VALUES ('1','1'),('10','1'),('11','1'),('12','1'),('13','1'),('14','1'),('15','1'),('2','1'),('3','1'),('4','1'),('5','1'),('6','1'),('7','1'),('8','1'),('9','1');
/*!40000 ALTER TABLE `technical_action_access_rights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technical_module`
--

DROP TABLE IF EXISTS `technical_module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technical_module` (
  `MODULE_ID` varchar(100) NOT NULL,
  `MODULE_NAME` varchar(200) NOT NULL,
  `MODULE_VERSION` varchar(20) NOT NULL,
  `MODULE_DESCRIPTION` varchar(500) DEFAULT NULL,
  `MODULE_ICON` varchar(500) DEFAULT NULL,
  `MODULE_CATEGORY` varchar(50) DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) NOT NULL,
  `RECORD_LOG` varchar(100) NOT NULL,
  `ORDER_SEQUENCE` int(11) DEFAULT NULL,
  PRIMARY KEY (`MODULE_ID`),
  KEY `technical_module_index` (`MODULE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technical_module`
--

LOCK TABLES `technical_module` WRITE;
/*!40000 ALTER TABLE `technical_module` DISABLE KEYS */;
INSERT INTO `technical_module` VALUES ('1','Technical','1.0.2','Administrator Module','./assets/images/module_icon/b3p52ftixa.jpg','TECHNICAL','TL-3','UPD->ADMIN->2022-11-30 08:22:15',99);
/*!40000 ALTER TABLE `technical_module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technical_module_access_rights`
--

DROP TABLE IF EXISTS `technical_module_access_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technical_module_access_rights` (
  `MODULE_ID` varchar(100) NOT NULL,
  `ROLE_ID` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technical_module_access_rights`
--

LOCK TABLES `technical_module_access_rights` WRITE;
/*!40000 ALTER TABLE `technical_module_access_rights` DISABLE KEYS */;
INSERT INTO `technical_module_access_rights` VALUES ('1','1');
/*!40000 ALTER TABLE `technical_module_access_rights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technical_page`
--

DROP TABLE IF EXISTS `technical_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technical_page` (
  `PAGE_ID` varchar(100) NOT NULL,
  `PAGE_NAME` varchar(200) NOT NULL,
  `MODULE_ID` varchar(100) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) NOT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`PAGE_ID`),
  KEY `technical_page_index` (`PAGE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technical_page`
--

LOCK TABLES `technical_page` WRITE;
/*!40000 ALTER TABLE `technical_page` DISABLE KEYS */;
INSERT INTO `technical_page` VALUES ('1','Modules','1','TL-10',NULL),('2','Module Form','1','TL-11',NULL),('3','Pages','1','TL-18',NULL),('4','Page Form','1','TL-19',NULL),('5','Action','1','TL-26','UPD->ADMIN->2022-12-01 02:29:29'),('6','Action Form','1','TL-27','INS->ADMIN->2022-12-01 02:05:33');
/*!40000 ALTER TABLE `technical_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technical_page_access_rights`
--

DROP TABLE IF EXISTS `technical_page_access_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technical_page_access_rights` (
  `PAGE_ID` varchar(100) NOT NULL,
  `ROLE_ID` varchar(100) NOT NULL,
  PRIMARY KEY (`PAGE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technical_page_access_rights`
--

LOCK TABLES `technical_page_access_rights` WRITE;
/*!40000 ALTER TABLE `technical_page_access_rights` DISABLE KEYS */;
INSERT INTO `technical_page_access_rights` VALUES ('1','1'),('2','1'),('3','1'),('4','1'),('5','1'),('6','1'),('7','1');
/*!40000 ALTER TABLE `technical_page_access_rights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'dssdb'
--
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_action_access_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_action_access_exist`(IN action_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @action_id = action_id;
	SET @role_id = role_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_action_access_rights WHERE ACTION_ID = @action_id AND ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_action_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_action_exist`(IN action_id VARCHAR(100))
BEGIN
	SET @action_id = action_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_action WHERE ACTION_ID = @action_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_module_access_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_module_access_exist`(IN module_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @module_id = module_id;
	SET @role_id = role_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_module_access_rights WHERE MODULE_ID = @module_id AND ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_module_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_module_exist`(IN module_id VARCHAR(100))
BEGIN
	SET @module_id = module_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_module WHERE MODULE_ID = @module_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_page_access_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_page_access_exist`(IN page_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @page_id = page_id;
	SET @role_id = role_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_page_access_rights WHERE PAGE_ID = @page_id AND ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_page_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_page_exist`(IN page_id VARCHAR(100))
BEGIN
	SET @page_id = page_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_page WHERE PAGE_ID = @page_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_system_parameter_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_system_parameter_exist`(IN parameter_id INT)
BEGIN
	SET @parameter_id = parameter_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_system_parameters WHERE PARAMETER_ID = @parameter_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_user_account_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_user_account_exist`(IN username VARCHAR(50))
BEGIN
	SET @username = username;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_user_account WHERE BINARY USERNAME = @username';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_action` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_action`(IN action_id VARCHAR(100))
BEGIN
	SET @action_id = action_id;

	SET @query = 'DELETE FROM technical_action WHERE ACTION_ID = @action_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_action_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_action_access`(IN action_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @action_id = action_id;
	SET @role_id = role_id;

	SET @query = 'DELETE FROM technical_action_access_rights WHERE ACTION_ID = @action_id AND ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_action_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_action_access`(IN action_id VARCHAR(100))
BEGIN
	SET @action_id = action_id;

	SET @query = 'DELETE FROM technical_action_access_rights WHERE ACTION_ID = @action_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_module_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_module_access`(IN module_id VARCHAR(100))
BEGIN
	SET @module_id = module_id;

	SET @query = 'DELETE FROM technical_module_access_rights WHERE MODULE_ID = @module_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_page_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_page_access`(IN page_id VARCHAR(100))
BEGIN
	SET @page_id = page_id;

	SET @query = 'DELETE FROM technical_page_access_rights WHERE PAGE_ID = @page_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_module` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_module`(IN `module_id` VARCHAR(100))
BEGIN
	SET @module_id = module_id;

	SET @query = 'DELETE FROM technical_module WHERE MODULE_ID = @module_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_module_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_module_access`(IN module_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @module_id = module_id;
	SET @role_id = role_id;

	SET @query = 'DELETE FROM technical_module_access_rights WHERE MODULE_ID = @module_id AND ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_page` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_page`(IN page_id VARCHAR(100))
BEGIN
	SET @page_id = page_id;

	SET @query = 'DELETE FROM technical_page WHERE PAGE_ID = @page_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_page_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_page_access`(IN page_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @page_id = page_id;
	SET @role_id = role_id;

	SET @query = 'DELETE FROM technical_page_access_rights WHERE PAGE_ID = @page_id AND ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_system_parameter` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_system_parameter`(IN parameter_id INT)
BEGIN
	SET @parameter_id = parameter_id;

	SET @query = 'DELETE FROM global_system_parameters WHERE PARAMETER_ID = @parameter_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_module_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_module_options`()
BEGIN
	SET @query = 'SELECT MODULE_ID, MODULE_NAME FROM technical_module ORDER BY MODULE_NAME';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_role_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_role_options`()
BEGIN
	SET @query = 'SELECT ROLE_ID, ROLE FROM global_role ORDER BY ROLE';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_system_code_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_system_code_options`(IN system_type VARCHAR(100))
BEGIN
	SET @system_type = system_type;

	SET @query = 'SELECT SYSTEM_CODE, SYSTEM_DESCRIPTION FROM global_system_code WHERE SYSTEM_TYPE = @system_type ORDER BY SYSTEM_DESCRIPTION';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_access_rights_count` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_access_rights_count`(IN role_id VARCHAR(50), IN access_right_id VARCHAR(100), IN access_type VARCHAR(10))
BEGIN
	SET @role_id = role_id;
	SET @access_right_id = access_right_id;
	SET @access_type = access_type;

	IF @access_type = 'module' THEN
		SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_module_access_rights WHERE MODULE_ID = @access_right_id AND ROLE_ID = @role_id';
	ELSEIF @access_type = 'page' THEN
		SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_page_access_rights WHERE PAGE_ID = @access_right_id AND ROLE_ID = @role_id';
	ELSE
		SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_action_access_rights WHERE ACTION_ID = @access_right_id AND ROLE_ID = @role_id';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_action_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_action_details`(IN action_id VARCHAR(100))
BEGIN
	SET @action_id = action_id;

	SET @query = 'SELECT ACTION_NAME, TRANSACTION_LOG_ID, RECORD_LOG FROM technical_action WHERE ACTION_ID = @action_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_module_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_module_details`(IN module_id VARCHAR(100))
BEGIN
	SET @module_id = module_id;

	SET @query = 'SELECT MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPTION, MODULE_ICON, MODULE_CATEGORY, TRANSACTION_LOG_ID, RECORD_LOG, ORDER_SEQUENCE FROM technical_module WHERE MODULE_ID = @module_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_page_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_page_details`(IN page_id VARCHAR(100))
BEGIN
	SET @page_id = page_id;

	SET @query = 'SELECT PAGE_NAME, MODULE_ID, TRANSACTION_LOG_ID, RECORD_LOG FROM technical_page WHERE PAGE_ID = @page_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_role_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_role_details`(IN role_id VARCHAR(100))
BEGIN
	SET @role_id = role_id;

	SET @query = 'SELECT ROLE, ROLE_DESCRIPTION, TRANSACTION_LOG_ID, RECORD_LOG FROM global_role WHERE ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_system_code_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_system_code_details`(IN system_type VARCHAR(100), IN system_code VARCHAR(100))
BEGIN
	SET @system_type = system_type;
	SET @system_code = system_code;

	SET @query = 'SELECT SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID, RECORD_LOG FROM global_system_code WHERE SYSTEM_TYPE = @system_type AND SYSTEM_CODE = @system_code';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_system_parameter` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_system_parameter`(IN parameter_id INT)
BEGIN
	SET @parameter_id = parameter_id;

	SET @query = 'SELECT PARAMETER_EXTENSION, PARAMETER_NUMBER FROM global_system_parameters WHERE PARAMETER_ID = @parameter_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_system_parameter_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_system_parameter_details`(IN parameter_id INT)
BEGIN
	SET @parameter_id = parameter_id;

	SET @query = 'SELECT PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER, TRANSACTION_LOG_ID, RECORD_LOG FROM global_system_parameters WHERE PARAMETER_ID = @parameter_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_upload_file_type_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_upload_file_type_details`(IN upload_setting_id INT(50))
BEGIN
	SET @upload_setting_id = upload_setting_id;

	SET @query = 'SELECT FILE_TYPE, RECORD_LOG FROM global_upload_file_type WHERE UPLOAD_SETTING_ID = @upload_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_upload_setting_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_upload_setting_details`(IN upload_setting_id INT(50))
BEGIN
	SET @upload_setting_id = upload_setting_id;

	SET @query = 'SELECT UPLOAD_SETTING, DESCRIPTION, MAX_FILE_SIZE, TRANSACTION_LOG_ID, RECORD_LOG FROM global_upload_setting WHERE UPLOAD_SETTING_ID = @upload_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_user_account_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_account_details`(IN `username` VARCHAR(50))
BEGIN
	SET @username = username;

	SET @query = 'SELECT PASSWORD, FILE_AS, USER_STATUS, PASSWORD_EXPIRY_DATE, FAILED_LOGIN, LAST_FAILED_LOGIN, LAST_CONNECTION_DATE, TRANSACTION_LOG_ID FROM global_user_account WHERE USERNAME = @username';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_action` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_action`(IN action_id VARCHAR(100), IN action_name VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @action_id = action_id;
	SET @action_name = action_name;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO technical_action (PAGE_ID, PAGE_NAME, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@action_id, @action_name, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_action_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_action_access`(IN action_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @action_id = action_id;
	SET @role_id = role_id;

	SET @query = 'INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES(@action_id, @role_id)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_module` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_module`(IN module_id VARCHAR(100), IN module_name VARCHAR(200), IN module_version VARCHAR(20), IN module_description VARCHAR(500), IN module_category VARCHAR(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @module_id = module_id;
	SET @module_name = module_name;
	SET @module_version = module_version;
	SET @module_description = module_description;
	SET @module_category = module_category;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO technical_module (MODULE_ID, MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPTION, MODULE_CATEGORY, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@module_id, @module_name, @module_version, @module_description, @module_category, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_module_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_module_access`(IN module_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @module_id = module_id;
	SET @role_id = role_id;

	SET @query = 'INSERT INTO technical_module_access_rights (MODULE_ID, ROLE_ID) VALUES(@module_id, @role_id)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_page` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_page`(IN page_id VARCHAR(100), IN page_name VARCHAR(200), IN module_id VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @page_id = page_id;
	SET @page_name = page_name;
	SET @module_id= module_id;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO technical_page (PAGE_ID, PAGE_NAME, MODULE_ID, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@page_id, @page_name, @module_id, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_page_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_page_access`(IN page_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @page_id = page_id;
	SET @role_id = role_id;

	SET @query = 'INSERT INTO technical_page_access_rights (PAGE_ID, ROLE_ID) VALUES(@page_id, @role_id)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_system_parameter` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_system_parameter`(IN parameter_id INT, IN parameter VARCHAR(100), IN parameter_description VARCHAR(100), IN extension VARCHAR(10), IN parameter_number INT, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @parameter_id = parameter_id;
	SET @parameter = parameter;
	SET @parameter_description = parameter_description;
	SET @extension = extension;
	SET @parameter_number = parameter_number;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_system_parameters (PARAMETER_ID, PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@parameter_id, @parameter, @parameter_description, @extension, @parameter_number, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_transaction_log` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_transaction_log`(IN transaction_log_id VARCHAR(100), IN username VARCHAR(50), log_type VARCHAR(100), log_date DATETIME, log VARCHAR(4000))
BEGIN
	SET @transaction_log_id = transaction_log_id;
	SET @username = username;
	SET @log_type = log_type;
	SET @log_date = log_date;
	SET @log = log;

	SET @query = 'INSERT INTO global_transaction_log (TRANSACTION_LOG_ID, USERNAME, LOG_TYPE, LOG_DATE, LOG) VALUES(@transaction_log_id, @username, @log_type, @log_date, @log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_action` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_action`(IN action_id VARCHAR(100), IN action_name VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @action_id = action_id;
	SET @action_name = action_name;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE technical_action SET PAGE_NAME = @action_name, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ACTION_ID = @action_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_login_attempt` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_login_attempt`(IN username VARCHAR(50), login_attemp INT(1), last_failed_attempt_date DATETIME)
BEGIN
	SET @username = username;
	SET @login_attemp = login_attemp;
	SET @last_failed_attempt_date = last_failed_attempt_date;

    IF @login_attemp > 0 THEN
		SET @query = 'UPDATE global_user_account SET FAILED_LOGIN = @login_attemp, LAST_FAILED_LOGIN = @last_failed_attempt_date WHERE USERNAME = @username';
	ELSE
		SET @query = 'UPDATE global_user_account SET FAILED_LOGIN = @login_attemp WHERE USERNAME = @username';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_module` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_module`(IN module_id VARCHAR(100), IN module_name VARCHAR(200), IN module_version VARCHAR(20), IN module_description VARCHAR(500), IN module_category VARCHAR(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @module_id = module_id;
	SET @module_name = module_name;
	SET @module_version = module_version;
	SET @module_description = module_description;
	SET @module_category = module_category;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE technical_module SET MODULE_NAME = @module_name, MODULE_VERSION = @module_version, MODULE_DESCRIPTION = @module_description, MODULE_CATEGORY = @module_category, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE MODULE_ID = @module_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_module_icon` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_module_icon`(IN module_id VARCHAR(100), IN module_icon VARCHAR(500))
BEGIN
	SET @module_id = module_id;
	SET @module_icon = module_icon;

	SET @query = 'UPDATE technical_module SET MODULE_ICON = @module_icon WHERE MODULE_ID = @module_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_page` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_page`(IN page_id VARCHAR(100), IN page_name VARCHAR(200), IN module_id VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @page_id = page_id;
	SET @page_name = page_name;
	SET @module_id= module_id;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE technical_page SET PAGE_NAME = @page_name, MODULE_ID = @module_id, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE PAGE_ID = @page_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_system_parameter` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_system_parameter`(IN parameter_id INT, IN parameter VARCHAR(100), IN parameter_description VARCHAR(100), IN extension VARCHAR(10), IN parameter_number INT, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @parameter_id = parameter_id;
	SET @parameter = parameter;
	SET @parameter_description = parameter_description;
	SET @extension = extension;
	SET @parameter_number = parameter_number;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_system_parameters SET PARAMETER = @parameter, PARAMETER_DESCRIPTION = @parameter_description, PARAMETER_EXTENSION = @extension, PARAMETER_NUMBER = @parameter_number, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE PARAMETER_ID = @parameter_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_system_parameter_value` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_system_parameter_value`(IN parameter_id INT, IN parameter_number INT, IN record_log VARCHAR(100))
BEGIN
	SET @parameter_id = parameter_id;
	SET @parameter_number = parameter_number;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_system_parameters SET PARAMETER_NUMBER = @parameter_number, RECORD_LOG = @record_log WHERE PARAMETER_ID = @parameter_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_user_account_password` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_user_account_password`(IN username VARCHAR(50), password VARCHAR(200), password_expiry_date DATE)
BEGIN
	SET @username = username;
	SET @password = password;
	SET @password_expiry_date = password_expiry_date;

	SET @query = 'UPDATE global_user_account SET PASSWORD = @password, PASSWORD_EXPIRY_DATE = @password_expiry_date WHERE USERNAME = @username';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_user_last_connection` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_user_last_connection`(IN username VARCHAR(50), last_connection_date DATETIME)
BEGIN
	SET @username = username;
	SET @last_connection_date = last_connection_date;

	SET @query = 'UPDATE global_user_account SET LAST_CONNECTION_DATE = @last_connection_date WHERE USERNAME = @username';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-12-01 18:05:01
