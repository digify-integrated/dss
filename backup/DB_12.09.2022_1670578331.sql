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
-- Table structure for table `global_company`
--

DROP TABLE IF EXISTS `global_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_company` (
  `COMPANY_ID` varchar(50) NOT NULL,
  `COMPANY_NAME` varchar(100) NOT NULL,
  `COMPANY_LOGO` varchar(500) DEFAULT NULL,
  `COMPANY_ADDRESS` varchar(500) DEFAULT NULL,
  `EMAIL` varchar(50) DEFAULT NULL,
  `TELEPHONE` varchar(20) DEFAULT NULL,
  `MOBILE` varchar(20) DEFAULT NULL,
  `WEBSITE` varchar(100) DEFAULT NULL,
  `TAX_ID` varchar(100) DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`COMPANY_ID`),
  KEY `global_company_index` (`COMPANY_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_company`
--

LOCK TABLES `global_company` WRITE;
/*!40000 ALTER TABLE `global_company` DISABLE KEYS */;
INSERT INTO `global_company` VALUES ('1','Encore Leasing and Finance Corp.','./assets/images/company/f2hi9s78pe.jpg','','','','','','','119','INS->ADMIN->2022-12-09 09:45:59');
/*!40000 ALTER TABLE `global_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_interface_setting`
--

DROP TABLE IF EXISTS `global_interface_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_interface_setting` (
  `INTERFACE_SETTING_ID` int(50) NOT NULL,
  `INTERFACE_SETTING_NAME` varchar(100) NOT NULL,
  `DESCRIPTION` varchar(200) NOT NULL,
  `STATUS` tinyint(1) NOT NULL,
  `LOGIN_BACKGROUND` varchar(500) DEFAULT NULL,
  `LOGIN_LOGO` varchar(500) DEFAULT NULL,
  `MENU_LOGO` varchar(500) DEFAULT NULL,
  `FAVICON` varchar(500) DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`INTERFACE_SETTING_ID`),
  KEY `global_interface_setting_index` (`INTERFACE_SETTING_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_interface_setting`
--

LOCK TABLES `global_interface_setting` WRITE;
/*!40000 ALTER TABLE `global_interface_setting` DISABLE KEYS */;
/*!40000 ALTER TABLE `global_interface_setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_role`
--

DROP TABLE IF EXISTS `global_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_role` (
  `ROLE_ID` varchar(100) NOT NULL,
  `ROLE` varchar(100) NOT NULL,
  `ROLE_DESCRIPTION` varchar(200) NOT NULL,
  `ASSIGNABLE` tinyint(1) NOT NULL,
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
INSERT INTO `global_role` VALUES ('1','Administrator','Administrator',2,'TL-2',NULL);
/*!40000 ALTER TABLE `global_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_role_user_account`
--

DROP TABLE IF EXISTS `global_role_user_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_role_user_account` (
  `ROLE_ID` varchar(100) NOT NULL,
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
  `SYSTEM_CODE_ID` varchar(100) NOT NULL,
  `SYSTEM_TYPE` varchar(20) NOT NULL,
  `SYSTEM_CODE` varchar(20) NOT NULL,
  `SYSTEM_DESCRIPTION` varchar(100) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) NOT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`SYSTEM_CODE_ID`),
  KEY `global_system_code_index` (`SYSTEM_CODE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_system_code`
--

LOCK TABLES `global_system_code` WRITE;
/*!40000 ALTER TABLE `global_system_code` DISABLE KEYS */;
INSERT INTO `global_system_code` VALUES ('1','SYSTYPE','SYSTYPE','System Code','TL-4',NULL),('10','FILETYPE','mp4','Video (.mp4)','77','INS->ADMIN->2022-12-07 11:55:59'),('11','FILETYPE','mkv','Video (.mkv)','78','INS->ADMIN->2022-12-07 11:56:17'),('12','FILETYPE','mov','Video (.mov)','79','INS->ADMIN->2022-12-07 11:56:36'),('13','FILETYPE','mpg','Video (.mpg)','80','INS->ADMIN->2022-12-07 11:56:50'),('14','FILETYPE','mpeg','Video (.mpeg)','81','INS->ADMIN->2022-12-07 11:57:00'),('15','FILETYPE','avi','Video (.avi)','82','INS->ADMIN->2022-12-07 11:57:13'),('16','FILETYPE','flv','Video (.flv)','83','INS->ADMIN->2022-12-07 11:57:23'),('17','FILETYPE','wmv','Video (.wmv)','84','INS->ADMIN->2022-12-07 11:57:36'),('18','FILETYPE','gif','Image (.gif)','85','INS->ADMIN->2022-12-07 11:57:49'),('19','FILETYPE','wav','Audio (.wav)','86','INS->ADMIN->2022-12-07 11:58:05'),('2','SYSTYPE','MODULECAT','Module Category','TL-5',NULL),('20','FILETYPE','doc','Word (.doc)','87','INS->ADMIN->2022-12-07 11:58:16'),('21','FILETYPE','docx','Word (.docx)','88','INS->ADMIN->2022-12-07 11:58:27'),('22','FILETYPE','xls','Excel (.xls)','89','INS->ADMIN->2022-12-07 11:58:38'),('23','FILETYPE','xlsx','Excel (.xlsx)','90','INS->ADMIN->2022-12-07 11:58:50'),('24','FILETYPE','ppt','Powerpoint (.ppt)','91','INS->ADMIN->2022-12-07 11:59:02'),('25','FILETYPE','pptx','Powerpoint (.pptx)','92','INS->ADMIN->2022-12-07 11:59:11'),('26','FILETYPE','zip','Compressed (.zip)','93','INS->ADMIN->2022-12-07 11:59:34'),('27','FILETYPE','7z','Compressed (.7z)','94','INS->ADMIN->2022-12-07 11:59:44'),('28','FILETYPE','rar','Compressed (.rar)','95','INS->ADMIN->2022-12-07 11:59:55'),('29','FILETYPE','pdf','Document (.pdf)','96','INS->ADMIN->2022-12-07 12:00:06'),('3','MODULECAT','TECHNICAL','Technical','TL-6',NULL),('30','FILETYPE','txt','Document (.txt)','97','INS->ADMIN->2022-12-07 12:00:17'),('31','FILETYPE','csv','Data (.csv)','98','INS->ADMIN->2022-12-07 12:00:27'),('32','FILETYPE','mp3','Audio (.mp3)','99','INS->ADMIN->2022-12-07 12:00:38'),('33','FILETYPE','sql','Data (.sql)','100','INS->ADMIN->2022-12-07 12:00:56'),('4','SYSTYPE','FILETYPE','File Type','71','INS->ADMIN->2022-12-07 11:51:52'),('5','FILETYPE','svg','Image (.svg)','72','INS->ADMIN->2022-12-07 11:54:58'),('6','FILETYPE','png','Image (.png)','73','INS->ADMIN->2022-12-07 11:55:08'),('7','FILETYPE','jpg','Image (.jpg)','74','INS->ADMIN->2022-12-07 11:55:23'),('8','FILETYPE','ico','Image (.ico)','75','INS->ADMIN->2022-12-07 11:55:37'),('9','FILETYPE','jpeg','Image (.jpeg)','76','INS->ADMIN->2022-12-07 11:55:49');
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
INSERT INTO `global_system_parameters` VALUES (1,'System Parameter','Parameter for system parameters.','',10,'TL-15','UPD->ADMIN->2022-12-09 09:56:50'),(2,'Transaction Log','Parameter for transaction logs.','',137,'TL-16','UPD->ADMIN->2022-12-09 05:06:22'),(3,'Module','Parameter for modules.','',1,'TL-17','UPD->ADMIN->2022-12-01 01:34:24'),(4,'Page','Parameter for pages.','',18,'TL-25','UPD->ADMIN->2022-12-09 10:00:19'),(5,'Action','Parameter for actions.','',46,'TL-33','UPD->ADMIN->2022-12-09 09:59:03'),(6,'Role','Parameter for roles.','',1,'TL-39','UPD->ADMIN->2022-12-06 03:00:37'),(7,'Upload Settings','Parameter for upload settings.','',6,'57','UPD->ADMIN->2022-12-09 10:19:11'),(8,'System Code','Parameter for system code.','',33,'68','UPD->ADMIN->2022-12-07 12:00:56'),(9,'Company','Parameter for company.','',1,'110','UPD->ADMIN->2022-12-09 09:45:59'),(10,'Interface Setting','Parameter for interface setting.','',0,'120','UPD->ADMIN->2022-12-09 05:14:11');
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
INSERT INTO `global_transaction_log` VALUES ('TL-1','ADMIN','Update','2022-09-20 13:37:24','User ADMIN updated user account password.'),('TL-1','ADMIN','Log In','2022-09-20 13:37:28','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-09-21 09:59:22','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-09-22 13:34:58','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-09-23 08:35:00','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-24 17:01:02','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-24 17:01:20','User ADMIN logged in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:01:45','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-10-24 17:01:52','User ADMIN logged in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:02:56','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-10-24 17:03:37','User ADMIN logged in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:38','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:39','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:40','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:40','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:41','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-25 09:51:47','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-25 09:51:50','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-10-25 09:51:52','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-26 08:57:46','User ADMIN logged in.'),('TL-1','ADMIN','Attempt Log In','2022-10-27 13:02:32','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-10-27 13:02:36','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-28 09:08:02','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-29 21:45:41','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-30 09:07:23','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-30 18:47:01','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-31 09:44:15','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-15 09:11:11','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-17 15:54:46','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-18 11:34:23','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-21 15:50:26','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-22 10:51:22','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-22 16:49:17','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-29 09:14:44','User ADMIN logged in.'),('TL-3','ADMIN','Update','2022-11-29 16:33:49','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:33:51','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:33:56','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:34:02','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:35:09','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:36:19','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:44:11','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:44:30','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:17','User ADMIN updated icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:17','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:45','User ADMIN updated icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:45','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:48','User ADMIN updated module icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:48','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:48','User ADMIN updated module icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:48','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:59','User ADMIN updated module icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:59','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:47:24','User ADMIN updated module icon.'),('TL-3','ADMIN','Update','2022-11-29 16:47:24','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:47:35','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 17:12:25','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 17:12:30','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 17:12:40','User ADMIN updated module.'),('TL-18','ADMIN','Insert','2022-11-29 17:13:15','User ADMIN inserted module.'),('TL-19','ADMIN','Insert','2022-11-29 17:14:06','User ADMIN inserted module.'),('TL-20','ADMIN','Insert','2022-11-30 12:34:02','User ADMIN inserted module.'),('TL-21','ADMIN','Insert','2022-11-30 12:34:11','User ADMIN inserted module.'),('TL-22','ADMIN','Insert','2022-11-30 12:35:09','User ADMIN inserted module.'),('TL-23','ADMIN','Insert','2022-11-30 12:35:41','User ADMIN inserted module.'),('TL-24','ADMIN','Insert','2022-11-30 12:36:17','User ADMIN inserted module.'),('TL-1','ADMIN','Log In','2022-11-30 17:56:03','User ADMIN logged in.'),('TL-24','ADMIN','Update','2022-11-30 18:03:01','User ADMIN updated module.'),('TL-1','ADMIN','Log In','2022-11-30 20:11:21','User ADMIN logged in.'),('TL-25','ADMIN','Insert','2022-11-30 20:18:14','User ADMIN inserted module.'),('TL-3','ADMIN','Update','2022-11-30 20:22:04','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-30 20:22:09','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-30 20:22:15','User ADMIN updated module.'),('TL-1','ADMIN','Log In','2022-12-01 10:41:23','User ADMIN logged in.'),('TL-26','ADMIN','Insert','2022-12-01 13:22:24','User ADMIN inserted page.'),('TL-27','ADMIN','Insert','2022-12-01 13:33:06','User ADMIN inserted page.'),('TL-28','ADMIN','Insert','2022-12-01 13:34:24','User ADMIN inserted module.'),('TL-29','ADMIN','Insert','2022-12-01 14:05:33','User ADMIN inserted page.'),('TL-26','ADMIN','Update','2022-12-01 14:29:29','User ADMIN updated page.'),('TL-1','ADMIN','Log In','2022-12-02 09:25:01','User ADMIN logged in.'),('TL-34','ADMIN','Insert','2022-12-02 09:30:22','User ADMIN inserted action.'),('TL-21','ADMIN','Update','2022-12-02 09:37:48','User ADMIN updated action.'),('TL-34','ADMIN','Insert','2022-12-02 09:55:03','User ADMIN inserted page.'),('TL-35','ADMIN','Insert','2022-12-02 13:57:57','User ADMIN inserted action.'),('TL-36','ADMIN','Insert','2022-12-02 13:58:13','User ADMIN inserted action.'),('TL-37','ADMIN','Insert','2022-12-02 13:58:24','User ADMIN inserted action.'),('TL-38','ADMIN','Insert','2022-12-02 14:47:02','User ADMIN inserted page.'),('TL-34','ADMIN','Update','2022-12-02 14:47:59','User ADMIN updated page.'),('TL-15','ADMIN','Update','2022-12-02 17:19:30','User ADMIN updated system parameter.'),('TL-39','ADMIN','Insert','2022-12-02 17:20:07','User ADMIN inserted system parameter.'),('TL-39','ADMIN','Update','2022-12-02 17:20:17','User ADMIN updated system parameter.'),('TL-15','ADMIN','Update','2022-12-02 17:21:53','User ADMIN updated system parameter.'),('TL-16','ADMIN','Update','2022-12-02 17:22:03','User ADMIN updated system parameter.'),('39','ADMIN','Insert','2022-12-02 17:22:15','User ADMIN inserted system parameter.'),('39','ADMIN','Update','2022-12-02 17:22:20','User ADMIN updated system parameter.'),('40','ADMIN','Insert','2022-12-02 17:22:59','User ADMIN inserted page.'),('TL-25','ADMIN','Update','2022-12-02 17:23:23','User ADMIN updated system parameter.'),('TL-16','ADMIN','Update','2022-12-02 17:23:32','User ADMIN updated system parameter.'),('40','ADMIN','Insert','2022-12-02 17:24:52','User ADMIN inserted page.'),('41','ADMIN','Insert','2022-12-02 17:25:12','User ADMIN inserted page.'),('42','ADMIN','Insert','2022-12-02 17:25:25','User ADMIN inserted action.'),('TL-33','ADMIN','Update','2022-12-02 17:25:56','User ADMIN updated system parameter.'),('TL-16','ADMIN','Update','2022-12-02 17:26:03','User ADMIN updated system parameter.'),('42','ADMIN','Insert','2022-12-02 17:26:22','User ADMIN inserted action.'),('42','ADMIN','Update','2022-12-02 17:26:24','User ADMIN updated action.'),('43','ADMIN','Insert','2022-12-02 17:26:44','User ADMIN inserted action.'),('43','ADMIN','Update','2022-12-02 17:26:53','User ADMIN updated action.'),('44','ADMIN','Insert','2022-12-02 17:26:59','User ADMIN inserted action.'),('45','ADMIN','Insert','2022-12-02 17:27:18','User ADMIN inserted action.'),('46','ADMIN','Insert','2022-12-02 17:27:34','User ADMIN inserted action.'),('47','ADMIN','Insert','2022-12-02 17:27:51','User ADMIN inserted action.'),('48','ADMIN','Insert','2022-12-02 17:28:04','User ADMIN inserted action.'),('49','ADMIN','Insert','2022-12-02 17:28:17','User ADMIN inserted action.'),('50','ADMIN','Insert','2022-12-02 17:28:30','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-04 09:50:27','User ADMIN logged in.'),('51','ADMIN','Insert','2022-12-04 09:52:54','User ADMIN inserted action.'),('52','ADMIN','Insert','2022-12-04 09:53:16','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-05 11:32:09','User ADMIN logged in.'),('53','ADMIN','Insert','2022-12-05 15:45:35','User ADMIN inserted role.'),('54','ADMIN','Insert','2022-12-05 15:55:59','User ADMIN inserted role.'),('55','ADMIN','Insert','2022-12-05 16:06:03','User ADMIN inserted role.'),('TL-1','ADMIN','Log In','2022-12-06 11:26:41','User ADMIN logged in.'),('56','ADMIN','Insert','2022-12-06 11:28:27','User ADMIN inserted role.'),('TL-39','ADMIN','Update','2022-12-06 15:00:37','User ADMIN updated system parameter.'),('57','ADMIN','Insert','2022-12-06 15:07:33','User ADMIN inserted system parameter.'),('58','ADMIN','Insert','2022-12-06 15:07:55','User ADMIN inserted page.'),('59','ADMIN','Insert','2022-12-06 15:10:25','User ADMIN inserted action.'),('60','ADMIN','Insert','2022-12-06 15:10:45','User ADMIN inserted action.'),('61','ADMIN','Insert','2022-12-06 15:11:18','User ADMIN inserted action.'),('62','ADMIN','Insert','2022-12-06 16:11:17','User ADMIN inserted page.'),('63','ADMIN','Insert','2022-12-06 17:02:51','User ADMIN inserted page.'),('63','ADMIN','Update','2022-12-06 17:02:54','User ADMIN updated page.'),('64','ADMIN','Insert','2022-12-06 17:03:46','User ADMIN inserted page.'),('65','ADMIN','Insert','2022-12-06 17:03:57','User ADMIN inserted action.'),('66','ADMIN','Insert','2022-12-06 17:04:18','User ADMIN inserted action.'),('67','ADMIN','Insert','2022-12-06 17:04:34','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-07 09:41:11','User ADMIN logged in.'),('68','ADMIN','Insert','2022-12-07 10:01:07','User ADMIN inserted system parameter.'),('68','ADMIN','Update','2022-12-07 10:01:31','User ADMIN updated system parameter.'),('69','ADMIN','Insert','2022-12-07 11:43:14','User ADMIN inserted system code.'),('68','ADMIN','Update','2022-12-07 11:43:54','User ADMIN updated system parameter.'),('70','ADMIN','Insert','2022-12-07 11:44:06','User ADMIN inserted system code.'),('68','ADMIN','Update','2022-12-07 11:47:14','User ADMIN updated system parameter.'),('71','ADMIN','Insert','2022-12-07 11:51:52','User ADMIN inserted system code.'),('72','ADMIN','Insert','2022-12-07 11:54:58','User ADMIN inserted system code.'),('73','ADMIN','Insert','2022-12-07 11:55:08','User ADMIN inserted system code.'),('74','ADMIN','Insert','2022-12-07 11:55:23','User ADMIN inserted system code.'),('75','ADMIN','Insert','2022-12-07 11:55:37','User ADMIN inserted system code.'),('76','ADMIN','Insert','2022-12-07 11:55:49','User ADMIN inserted system code.'),('77','ADMIN','Insert','2022-12-07 11:55:59','User ADMIN inserted system code.'),('78','ADMIN','Insert','2022-12-07 11:56:17','User ADMIN inserted system code.'),('79','ADMIN','Insert','2022-12-07 11:56:36','User ADMIN inserted system code.'),('80','ADMIN','Insert','2022-12-07 11:56:50','User ADMIN inserted system code.'),('81','ADMIN','Insert','2022-12-07 11:57:00','User ADMIN inserted system code.'),('82','ADMIN','Insert','2022-12-07 11:57:13','User ADMIN inserted system code.'),('83','ADMIN','Insert','2022-12-07 11:57:23','User ADMIN inserted system code.'),('84','ADMIN','Insert','2022-12-07 11:57:36','User ADMIN inserted system code.'),('85','ADMIN','Insert','2022-12-07 11:57:49','User ADMIN inserted system code.'),('86','ADMIN','Insert','2022-12-07 11:58:05','User ADMIN inserted system code.'),('87','ADMIN','Insert','2022-12-07 11:58:16','User ADMIN inserted system code.'),('88','ADMIN','Insert','2022-12-07 11:58:27','User ADMIN inserted system code.'),('89','ADMIN','Insert','2022-12-07 11:58:38','User ADMIN inserted system code.'),('90','ADMIN','Insert','2022-12-07 11:58:50','User ADMIN inserted system code.'),('91','ADMIN','Insert','2022-12-07 11:59:02','User ADMIN inserted system code.'),('92','ADMIN','Insert','2022-12-07 11:59:11','User ADMIN inserted system code.'),('93','ADMIN','Insert','2022-12-07 11:59:34','User ADMIN inserted system code.'),('94','ADMIN','Insert','2022-12-07 11:59:44','User ADMIN inserted system code.'),('95','ADMIN','Insert','2022-12-07 11:59:55','User ADMIN inserted system code.'),('96','ADMIN','Insert','2022-12-07 12:00:06','User ADMIN inserted system code.'),('97','ADMIN','Insert','2022-12-07 12:00:17','User ADMIN inserted system code.'),('98','ADMIN','Insert','2022-12-07 12:00:27','User ADMIN inserted system code.'),('99','ADMIN','Insert','2022-12-07 12:00:38','User ADMIN inserted system code.'),('100','ADMIN','Insert','2022-12-07 12:00:56','User ADMIN inserted system code.'),('101','ADMIN','Insert','2022-12-07 13:22:51','User ADMIN inserted action.'),('102','ADMIN','Insert','2022-12-07 13:23:12','User ADMIN inserted action.'),('57','ADMIN','Update','2022-12-07 15:18:57','User ADMIN updated system parameter.'),('103','ADMIN','Insert','2022-12-07 15:18:59','User ADMIN inserted upload setting.'),('104','ADMIN','Insert','2022-12-07 15:25:19','User ADMIN inserted upload setting.'),('57','ADMIN','Update','2022-12-07 15:25:36','User ADMIN updated system parameter.'),('105','ADMIN','Insert','2022-12-07 15:50:51','User ADMIN inserted page.'),('106','ADMIN','Insert','2022-12-07 15:51:05','User ADMIN inserted page.'),('107','ADMIN','Insert','2022-12-07 15:54:10','User ADMIN inserted action.'),('107','ADMIN','Update','2022-12-07 15:54:12','User ADMIN updated action.'),('108','ADMIN','Insert','2022-12-07 15:54:24','User ADMIN inserted action.'),('109','ADMIN','Insert','2022-12-07 15:54:36','User ADMIN inserted action.'),('110','ADMIN','Insert','2022-12-07 16:50:52','User ADMIN inserted system parameter.'),('110','ADMIN','Update','2022-12-07 16:51:03','User ADMIN updated system parameter.'),('111','ADMIN','Insert','2022-12-07 16:53:02','User ADMIN inserted upload setting.'),('112','ADMIN','Insert','2022-12-07 17:36:22','User ADMIN inserted company.'),('113','ADMIN','Insert','2022-12-07 17:36:27','User ADMIN inserted company.'),('114','ADMIN','Insert','2022-12-07 17:37:25','User ADMIN inserted company.'),('115','ADMIN','Insert','2022-12-07 17:38:34','User ADMIN inserted company.'),('115','ADMIN','Update','2022-12-07 17:38:34','User ADMIN updated company logo.'),('TL-1','ADMIN','Log In','2022-12-09 08:45:56','User ADMIN logged in.'),('110','ADMIN','Update','2022-12-09 08:48:46','User ADMIN updated system parameter.'),('110','ADMIN','Update','2022-12-09 08:48:51','User ADMIN updated system parameter.'),('116','ADMIN','Insert','2022-12-09 08:50:50','User ADMIN inserted company.'),('116','ADMIN','Update','2022-12-09 08:50:50','User ADMIN updated company logo.'),('110','ADMIN','Update','2022-12-09 09:19:09','User ADMIN updated system parameter.'),('117','ADMIN','Insert','2022-12-09 09:36:26','User ADMIN inserted company.'),('117','ADMIN','Update','2022-12-09 09:36:26','User ADMIN updated company logo.'),('110','ADMIN','Update','2022-12-09 09:41:50','User ADMIN updated system parameter.'),('118','ADMIN','Insert','2022-12-09 09:42:02','User ADMIN inserted company.'),('118','ADMIN','Update','2022-12-09 09:42:02','User ADMIN updated company logo.'),('110','ADMIN','Update','2022-12-09 09:45:44','User ADMIN updated system parameter.'),('119','ADMIN','Insert','2022-12-09 09:45:59','User ADMIN inserted company.'),('119','ADMIN','Update','2022-12-09 09:45:59','User ADMIN updated company logo.'),('120','ADMIN','Insert','2022-12-09 09:56:50','User ADMIN inserted system parameter.'),('120','ADMIN','Update','2022-12-09 09:56:53','User ADMIN updated system parameter.'),('121','ADMIN','Insert','2022-12-09 09:57:14','User ADMIN inserted page.'),('122','ADMIN','Insert','2022-12-09 09:57:28','User ADMIN inserted action.'),('123','ADMIN','Insert','2022-12-09 09:57:41','User ADMIN inserted action.'),('124','ADMIN','Insert','2022-12-09 09:58:04','User ADMIN inserted action.'),('124','ADMIN','Update','2022-12-09 09:58:15','User ADMIN updated action.'),('125','ADMIN','Insert','2022-12-09 09:58:46','User ADMIN inserted action.'),('126','ADMIN','Insert','2022-12-09 09:59:03','User ADMIN inserted action.'),('127','ADMIN','Insert','2022-12-09 10:00:19','User ADMIN inserted page.'),('128','ADMIN','Insert','2022-12-09 10:16:09','User ADMIN inserted upload setting.'),('129','ADMIN','Insert','2022-12-09 10:16:41','User ADMIN inserted upload setting.'),('130','ADMIN','Insert','2022-12-09 10:18:34','User ADMIN inserted upload setting.'),('131','ADMIN','Insert','2022-12-09 10:19:11','User ADMIN inserted upload setting.'),('127','ADMIN','Update','2022-12-09 10:35:36','User ADMIN updated page.'),('121','ADMIN','Update','2022-12-09 10:35:44','User ADMIN updated page.'),('122','ADMIN','Update','2022-12-09 10:38:57','User ADMIN updated action.'),('123','ADMIN','Update','2022-12-09 10:39:01','User ADMIN updated action.'),('123','ADMIN','Update','2022-12-09 10:39:06','User ADMIN updated action.'),('124','ADMIN','Update','2022-12-09 10:39:12','User ADMIN updated action.'),('125','ADMIN','Update','2022-12-09 10:39:18','User ADMIN updated action.'),('126','ADMIN','Update','2022-12-09 10:39:24','User ADMIN updated action.'),('132','ADMIN','Insert','2022-12-09 16:10:09','User ADMIN inserted interface setting.'),('132','ADMIN','Update','2022-12-09 16:10:09','User ADMIN updated login background.'),('132','ADMIN','Update','2022-12-09 16:10:09','User ADMIN updated login logo.'),('132','ADMIN','Update','2022-12-09 16:10:09','User ADMIN updated menu logo.'),('132','ADMIN','Update','2022-12-09 16:10:09','User ADMIN updated favicon.'),('133','ADMIN','Insert','2022-12-09 16:13:29','User ADMIN inserted interface setting.'),('134','ADMIN','Insert','2022-12-09 16:53:03','User ADMIN inserted interface setting.'),('135','ADMIN','Insert','2022-12-09 16:53:14','User ADMIN inserted interface setting.'),('136','ADMIN','Insert','2022-12-09 17:06:14','User ADMIN inserted interface setting.'),('137','ADMIN','Insert','2022-12-09 17:06:22','User ADMIN inserted interface setting.'),('136','ADMIN','Deactivate','2022-12-09 17:06:30','User ADMIN updated interface setting status.'),('136','ADMIN','Activate','2022-12-09 17:07:50','User ADMIN updated interface setting status.'),('136','ADMIN','Activate','2022-12-09 17:08:12','User ADMIN updated interface setting status.'),('136','ADMIN','Deactivate','2022-12-09 17:08:13','User ADMIN updated interface setting status.'),('136','ADMIN','Activate','2022-12-09 17:09:16','User ADMIN updated interface setting status.'),('136','ADMIN','Deactivate','2022-12-09 17:09:16','User ADMIN updated interface setting status.'),('136','ADMIN','Activate','2022-12-09 17:10:29','User ADMIN updated interface setting status.'),('136','ADMIN','Deactivate','2022-12-09 17:10:29','User ADMIN updated interface setting status.'),('136','ADMIN','Deactivate','2022-12-09 17:13:33','User ADMIN updated interface setting status.'),('120','ADMIN','Update','2022-12-09 17:14:11','User ADMIN updated system parameter.');
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
  `FILE_TYPE` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_upload_file_type`
--

LOCK TABLES `global_upload_file_type` WRITE;
/*!40000 ALTER TABLE `global_upload_file_type` DISABLE KEYS */;
INSERT INTO `global_upload_file_type` VALUES (1,'jpeg'),(1,'svg'),(1,'png'),(1,'jpg'),(2,'jpeg'),(2,'jpg'),(2,'png'),(3,'jpeg'),(3,'jpg'),(3,'png'),(4,'jpeg'),(4,'jpg'),(4,'png'),(4,'svg'),(3,'svg'),(2,'svg'),(5,'jpeg'),(5,'jpg'),(5,'png'),(5,'svg'),(6,'ico'),(6,'jpeg'),(6,'jpg'),(6,'png'),(6,'svg');
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
INSERT INTO `global_upload_setting` VALUES (1,'Module Icon','Upload setting for module icon.',5,'TL-14',NULL),(2,'Company Logo','Upload setting for company logo.',5,'111','INS->ADMIN->2022-12-07 04:53:02'),(3,'Login Background','Upload setting for login background.',5,'128','INS->ADMIN->2022-12-09 10:16:09'),(4,'Login Logo','Upload setting for login logo.',5,'129','INS->ADMIN->2022-12-09 10:16:41'),(5,'Menu Logo','Upload setting for menu logo.',5,'130','INS->ADMIN->2022-12-09 10:18:34'),(6,'Favicon','Upload setting for favicon.',5,'131','INS->ADMIN->2022-12-09 10:19:11');
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
INSERT INTO `global_user_account` VALUES ('ADMIN','68aff5412f35ed76','Administrator','Active','2022-12-30',0,'2022-10-27 13:02:32','2022-12-09 08:45:56','TL-1');
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
INSERT INTO `technical_action` VALUES ('1','Add Module','TL-7',NULL),('10','Delete Page Access Right','TL-24',NULL),('11','Add Action','TL-28',NULL),('12','Update Action','TL-29',NULL),('13','Delete Action','TL-30',NULL),('14','Add Action Access Right','TL-31',NULL),('15','Delete Action Access Right','TL-32',NULL),('17','Add System Parameter','TL-35','INS->ADMIN->2022-12-02 01:57:57'),('18','Update System Parameter','TL-36','INS->ADMIN->2022-12-02 01:58:13'),('19','Delete System Parameter','TL-37','INS->ADMIN->2022-12-02 01:58:24'),('2','Update Module','TL-8',NULL),('20','Add Role','42','UPD->ADMIN->2022-12-02 05:26:24'),('21','Update Role','43','UPD->ADMIN->2022-12-02 05:26:53'),('22','Delete Role','44','INS->ADMIN->2022-12-02 05:26:59'),('23','Add Role Module Access','45','INS->ADMIN->2022-12-02 05:27:18'),('24','Delete Role Module Access','46','INS->ADMIN->2022-12-02 05:27:34'),('25','Add Role Page Access','47','INS->ADMIN->2022-12-02 05:27:51'),('26','Delete Role Page Access','48','INS->ADMIN->2022-12-02 05:28:04'),('27','Add Role Action Access','49','INS->ADMIN->2022-12-02 05:28:17'),('28','Delete Role Action Access','50','INS->ADMIN->2022-12-02 05:28:30'),('29','Add Role User Account','51','INS->ADMIN->2022-12-04 09:52:54'),('3','Delete Module','TL-9',NULL),('30','Delete Role User Account','52','INS->ADMIN->2022-12-04 09:53:16'),('31','Add Upload Setting','59','INS->ADMIN->2022-12-06 03:10:25'),('32','Update Upload Setting','60','INS->ADMIN->2022-12-06 03:10:45'),('33','Delete Upload Setting','61','INS->ADMIN->2022-12-06 03:11:18'),('34','Add System Code','65','INS->ADMIN->2022-12-06 05:03:57'),('35','Update System Code','66','INS->ADMIN->2022-12-06 05:04:18'),('36','Delete System Code','67','INS->ADMIN->2022-12-06 05:04:34'),('37','Add Upload Setting File Type','101','INS->ADMIN->2022-12-07 01:22:51'),('38','Delete Upload Setting File Type','102','INS->ADMIN->2022-12-07 01:23:12'),('39','Add Company','107','UPD->ADMIN->2022-12-07 03:54:12'),('4','Add Module Access Right','TL-12',NULL),('40','Update Company','108','INS->ADMIN->2022-12-07 03:54:24'),('41','Delete Company','109','INS->ADMIN->2022-12-07 03:54:36'),('42','Add Interface Setting','122','UPD->ADMIN->2022-12-09 10:38:57'),('43','Update Interface Setting','123','UPD->ADMIN->2022-12-09 10:39:06'),('44','Delete Interface Setting','124','UPD->ADMIN->2022-12-09 10:39:12'),('45','Activate Interface Setting','125','UPD->ADMIN->2022-12-09 10:39:18'),('46','Deactivate Interface Setting','126','UPD->ADMIN->2022-12-09 10:39:24'),('5','Delete Module Access Right','TL-13',NULL),('6','Add Page','TL-20',NULL),('7','Update Page','TL-21','UPD->ADMIN->2022-12-02 09:37:48'),('8','Delete Page','TL-22',NULL),('9','Add Page Access Right','TL-23',NULL);
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
  `ROLE_ID` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technical_action_access_rights`
--

LOCK TABLES `technical_action_access_rights` WRITE;
/*!40000 ALTER TABLE `technical_action_access_rights` DISABLE KEYS */;
INSERT INTO `technical_action_access_rights` VALUES ('1','1'),('10','1'),('11','1'),('12','1'),('13','1'),('14','1'),('15','1'),('17','1'),('18','1'),('19','1'),('2','1'),('20','1'),('21','1'),('22','1'),('23','1'),('24','1'),('25','1'),('26','1'),('27','1'),('28','1'),('29','1'),('3','1'),('30','1'),('4','1'),('5','1'),('6','1'),('7','1'),('8','1'),('9','1'),('31','1'),('32','1'),('33','1'),('34','1'),('35','1'),('36','1'),('37','1'),('38','1'),('39','1'),('40','1'),('41','1'),('42','1'),('43','1'),('44','1'),('45','1'),('46','1');
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
INSERT INTO `technical_page` VALUES ('1','Modules','1','TL-10',NULL),('10','Role Form','1','41','INS->ADMIN->2022-12-02 05:25:12'),('11','Upload Settings','1','58','INS->ADMIN->2022-12-06 03:07:55'),('12','Upload Setting Form','1','62','INS->ADMIN->2022-12-06 04:11:17'),('13','System Codes','1','63','UPD->ADMIN->2022-12-06 05:02:54'),('14','System Code Form','1','64','INS->ADMIN->2022-12-06 05:03:46'),('15','Company','1','105','INS->ADMIN->2022-12-07 03:50:51'),('16','Company Form','1','106','INS->ADMIN->2022-12-07 03:51:05'),('17','Interface Settings','1','121','UPD->ADMIN->2022-12-09 10:35:44'),('18','Interface Setting Form','1','127','UPD->ADMIN->2022-12-09 10:35:36'),('2','Module Form','1','TL-11',NULL),('3','Pages','1','TL-18',NULL),('4','Page Form','1','TL-19',NULL),('5','Action','1','TL-26','UPD->ADMIN->2022-12-01 02:29:29'),('6','Action Form','1','TL-27','INS->ADMIN->2022-12-01 02:05:33'),('7','System Parameters','1','TL-34','UPD->ADMIN->2022-12-02 02:47:59'),('8','System Parameter Form','1','TL-38','INS->ADMIN->2022-12-02 02:47:02'),('9','Role','1','40','INS->ADMIN->2022-12-02 05:24:52');
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
  `ROLE_ID` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technical_page_access_rights`
--

LOCK TABLES `technical_page_access_rights` WRITE;
/*!40000 ALTER TABLE `technical_page_access_rights` DISABLE KEYS */;
INSERT INTO `technical_page_access_rights` VALUES ('1','1'),('10','1'),('2','1'),('3','1'),('4','1'),('5','1'),('6','1'),('7','1'),('8','1'),('9','1'),('11','1'),('12','1'),('13','1'),('14','1'),('15','1'),('16','1'),('17','1'),('18','1');
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
/*!50003 DROP PROCEDURE IF EXISTS `check_company_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_company_exist`(IN company_id VARCHAR(50))
BEGIN
	SET @company_id = company_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_company WHERE COMPANY_ID = @company_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `check_interface_setting_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_interface_setting_exist`(IN interface_setting_id INT(50))
BEGIN
	SET @interface_setting_id = interface_setting_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_interface_setting WHERE INTERFACE_SETTING_ID = @interface_setting_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `check_role_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_role_exist`(IN `role_id` VARCHAR(100))
BEGIN
	SET @role_id = role_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_role WHERE ROLE_ID = @role_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `check_role_user_account_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_role_user_account_exist`(IN `role_id` VARCHAR(100), IN `username` VARCHAR(50))
BEGIN
	SET @role_id = role_id;
	SET @username = username;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_role_user_account WHERE ROLE_ID = @role_id AND USERNAME = @username';

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
/*!50003 DROP PROCEDURE IF EXISTS `check_system_code_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_system_code_exist`(IN system_code_id VARCHAR(100))
BEGIN
	SET @system_code_id = system_code_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_system_code WHERE SYSTEM_CODE_ID = @system_code_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `check_upload_setting_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_upload_setting_exist`(IN upload_setting_id INT(50))
BEGIN
	SET @upload_setting_id = upload_setting_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_upload_setting WHERE UPLOAD_SETTING_ID = @upload_setting_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `check_upload_setting_file_type_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_upload_setting_file_type_exist`(IN upload_setting_id INT(50), IN file_type VARCHAR(50))
BEGIN
	SET @upload_setting_id = upload_setting_id;
	SET @file_type = file_type;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_upload_file_type WHERE UPLOAD_SETTING_ID = @upload_setting_id AND FILE_TYPE = @file_type';

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
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_role_user_account` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_role_user_account`(IN role_id VARCHAR(100))
BEGIN
	SET @role_id = role_id;

	SET @query = 'DELETE FROM global_role_user_account WHERE ROLE_ID = @role_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_upload_setting_file_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_upload_setting_file_type`(IN upload_setting_id INT(50))
BEGIN
	SET @upload_setting_id = upload_setting_id;

	SET @query = 'DELETE FROM global_upload_file_type WHERE UPLOAD_SETTING_ID = @upload_setting_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `delete_company` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_company`(IN company_id VARCHAR(50))
BEGIN
	SET @company_id = company_id;

	SET @query = 'DELETE FROM global_company WHERE COMPANY_ID = @company_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `delete_interface_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_interface_setting`(IN interface_setting_id INT(50))
BEGIN
	SET @interface_setting_id = interface_setting_id;

	SET @query = 'DELETE FROM global_interface_setting WHERE INTERFACE_SETTING_ID = @interface_setting_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `delete_role` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_role`(IN `role_id` VARCHAR(100))
BEGIN
	SET @role_id = role_id;

	SET @query = 'DELETE FROM global_role WHERE ROLE_ID = @role_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `delete_role_action_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_role_action_access`(IN role_id VARCHAR(100))
BEGIN
	SET @role_id = role_id;

	SET @query = 'DELETE FROM technical_action_access_rights WHERE ROLE_ID = @role_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `delete_role_module_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_role_module_access`(IN role_id VARCHAR(100))
BEGIN
	SET @role_id = role_id;

	SET @query = 'DELETE FROM technical_module_access_rights WHERE ROLE_ID = @role_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `delete_role_page_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_role_page_access`(IN role_id VARCHAR(100))
BEGIN
	SET @role_id = role_id;

	SET @query = 'DELETE FROM technical_page_access_rights WHERE ROLE_ID = @role_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `delete_role_user_account` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_role_user_account`(IN `role_id` VARCHAR(100), IN `username` VARCHAR(50))
BEGIN
	SET @role_id = role_id;
	SET @username = username;

	SET @query = 'DELETE FROM global_role_user_account WHERE ROLE_ID = @role_id AND USERNAME = @username';

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
/*!50003 DROP PROCEDURE IF EXISTS `delete_system_code` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_system_code`(IN system_code_id VARCHAR(100))
BEGIN
	SET @system_code_id = system_code_id;

	SET @query = 'DELETE FROM global_system_code WHERE SYSTEM_CODE_ID = @system_code_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `delete_upload_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_upload_setting`(IN upload_setting_id INT(50))
BEGIN
	SET @upload_setting_id = upload_setting_id;

	SET @query = 'DELETE FROM global_upload_setting WHERE UPLOAD_SETTING_ID = @upload_setting_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `delete_upload_setting_file_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_upload_setting_file_type`(IN upload_setting_id INT(50), IN file_type VARCHAR(50))
BEGIN
	SET @upload_setting_id = upload_setting_id;
	SET @file_type = file_type;

	SET @query = 'DELETE FROM global_upload_file_type WHERE UPLOAD_SETTING_ID = @upload_setting_id AND FILE_TYPE = @file_type';

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
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_access_rights_count`(IN `role_id` VARCHAR(100), IN `access_right_id` VARCHAR(100), IN `access_type` VARCHAR(10))
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
/*!50003 DROP PROCEDURE IF EXISTS `get_activated_interface_setting_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_activated_interface_setting_details`()
BEGIN
	SET @query = 'SELECT INTERFACE_SETTING_ID, INTERFACE_SETTING_NAME, DESCRIPTION, STATUS, LOGIN_BACKGROUND, LOGIN_LOGO, MENU_LOGO, FAVICON, TRANSACTION_LOG_ID, RECORD_LOG FROM global_interface_setting WHERE STATUS = 1';

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
/*!50003 DROP PROCEDURE IF EXISTS `get_company_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_company_details`(IN company_id VARCHAR(50))
BEGIN
	SET @company_id = company_id;

	SET @query = 'SELECT COMPANY_NAME, COMPANY_LOGO, COMPANY_ADDRESS, EMAIL, TELEPHONE, MOBILE, WEBSITE, TAX_ID, TRANSACTION_LOG_ID, RECORD_LOG FROM global_company WHERE COMPANY_ID = @company_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `get_interface_setting_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_interface_setting_details`(IN interface_setting_id INT(50))
BEGIN
	SET @interface_setting_id = interface_setting_id;

	SET @query = 'SELECT INTERFACE_SETTING_NAME, DESCRIPTION, STATUS, LOGIN_BACKGROUND, LOGIN_LOGO, MENU_LOGO, FAVICON, TRANSACTION_LOG_ID, RECORD_LOG FROM global_interface_setting WHERE INTERFACE_SETTING_ID = @interface_setting_id';

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

	SET @query = 'SELECT ROLE, ROLE_DESCRIPTION, ASSIGNABLE, TRANSACTION_LOG_ID, RECORD_LOG FROM global_role WHERE ROLE_ID = @role_id';

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
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_system_code_details`(IN system_code_id VARCHAR(100), IN system_type VARCHAR(100), IN system_code VARCHAR(100))
BEGIN
	SET @system_code_id = system_code_id;
	SET @system_type = system_type;
	SET @system_code = system_code;

	IF @system_code_id IS NULL OR @system_code_id = '' THEN
		SET @query = 'SELECT SYSTEM_CODE_ID, SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID, RECORD_LOG FROM global_system_code WHERE SYSTEM_TYPE = @system_type AND SYSTEM_CODE = @system_code';
	ELSE
		SET @query = 'SELECT SYSTEM_CODE_ID, SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID, RECORD_LOG FROM global_system_code WHERE SYSTEM_CODE_ID = @system_code_id';
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

	SET @query = 'SELECT FILE_TYPE FROM global_upload_file_type WHERE UPLOAD_SETTING_ID = @upload_setting_id';

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

	SET @query = 'INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@action_id, @action_name, @transaction_log_id, @record_log)';

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
/*!50003 DROP PROCEDURE IF EXISTS `insert_company` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_company`(IN company_id VARCHAR(50), IN company_name VARCHAR(100), IN company_address VARCHAR(500), IN email VARCHAR(50), IN telephone VARCHAR(20), IN mobile VARCHAR(20), IN website VARCHAR(100), IN tax_id VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @company_id = company_id;
	SET @company_name = company_name;
	SET @company_address = company_address;
	SET @email = email;
	SET @telephone = telephone;
	SET @mobile = mobile;
	SET @website = website;
	SET @tax_id = tax_id;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;


	SET @query = 'INSERT INTO global_company (COMPANY_ID, COMPANY_NAME, COMPANY_ADDRESS, EMAIL, TELEPHONE, MOBILE, WEBSITE, TAX_ID, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@company_id, @company_name, @company_address, @email, @telephone, @mobile, @website, @tax_id, @transaction_log_id, @record_log)';

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
/*!50003 DROP PROCEDURE IF EXISTS `insert_interface_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_interface_setting`(IN interface_setting_id INT(50), IN interface_setting_name VARCHAR(100), IN description VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @interface_setting_id = interface_setting_id;
	SET @interface_setting_name = interface_setting_name;
	SET @description = description;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_interface_setting (INTERFACE_SETTING_ID, INTERFACE_SETTING_NAME, DESCRIPTION, STATUS, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@interface_setting_id, @interface_setting_name, @description, "2", @transaction_log_id, @record_log)';

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
/*!50003 DROP PROCEDURE IF EXISTS `insert_role` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_role`(IN `role_id` VARCHAR(100), IN `role` VARCHAR(100), IN `role_description` VARCHAR(200), IN `assignable` TINYINT(1), IN `transaction_log_id` VARCHAR(100), IN `record_log` VARCHAR(100))
BEGIN
	SET @role_id = role_id;
	SET @role = role;
	SET @role_description = role_description;
	SET @assignable = assignable;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_role (ROLE_ID, ROLE, ROLE_DESCRIPTION, ASSIGNABLE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@role_id, @role, @role_description, @assignable, @transaction_log_id, @record_log)';

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
/*!50003 DROP PROCEDURE IF EXISTS `insert_role_user_account` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_role_user_account`(IN role_id VARCHAR(100), IN username VARCHAR(50))
BEGIN
	SET @role_id = role_id;
	SET @username = username;

	SET @query = 'INSERT INTO global_role_user_account (ROLE_ID, USERNAME) VALUES(@role_id, @username)';

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
/*!50003 DROP PROCEDURE IF EXISTS `insert_system_code` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_system_code`(IN system_code_id VARCHAR(100), IN system_type VARCHAR(20), IN system_code VARCHAR(20), IN system_description VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @system_code_id = system_code_id;
	SET @system_type = system_type;
	SET @system_code= system_code;
	SET @system_description= system_description;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_system_code (SYSTEM_CODE_ID, SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@system_code_id, @system_type, @system_code, @system_description, @transaction_log_id, @record_log)';

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
/*!50003 DROP PROCEDURE IF EXISTS `insert_upload_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_upload_setting`(IN upload_setting_id INT(50), IN upload_setting VARCHAR(100), IN description VARCHAR(100), IN max_file_size VARCHAR(10), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @upload_setting_id = upload_setting_id;
	SET @upload_setting = upload_setting;
	SET @description = description;
	SET @max_file_size = max_file_size;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_upload_setting (UPLOAD_SETTING_ID, UPLOAD_SETTING, DESCRIPTION, MAX_FILE_SIZE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@upload_setting_id, @upload_setting, @description, @max_file_size, @transaction_log_id, @record_log)';

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
/*!50003 DROP PROCEDURE IF EXISTS `insert_upload_setting_file_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_upload_setting_file_type`(IN upload_setting_id INT(50), IN file_type VARCHAR(50))
BEGIN
	SET @upload_setting_id = upload_setting_id;
	SET @file_type = file_type;

	SET @query = 'INSERT INTO global_upload_file_type (UPLOAD_SETTING_ID, FILE_TYPE) VALUES(@upload_setting_id, @file_type)';

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

	SET @query = 'UPDATE technical_action SET ACTION_NAME = @action_name, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ACTION_ID = @action_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `update_company` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_company`(IN company_id VARCHAR(50), IN company_name VARCHAR(100), IN company_address VARCHAR(500), IN email VARCHAR(50), IN telephone VARCHAR(20), IN mobile VARCHAR(20), IN website VARCHAR(100), IN tax_id VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @company_id = company_id;
	SET @company_name = company_name;
	SET @company_address = company_address;
	SET @email = email;
	SET @telephone = telephone;
	SET @mobile = mobile;
	SET @website = website;
	SET @tax_id = tax_id;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_company SET COMPANY_NAME = @company_name, COMPANY_NAME = @company_name, COMPANY_ADDRESS = @company_address, EMAIL = @email, TELEPHONE = @telephone, MOBILE = @mobile, WEBSITE = @website, TAX_ID = @tax_id, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE COMPANY_ID = @company_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `update_company_logo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_company_logo`(IN company_id VARCHAR(50), IN company_logo VARCHAR(500))
BEGIN
	SET @company_id = company_id;
	SET @company_logo = company_logo;

	SET @query = 'UPDATE global_company SET COMPANY_LOGO = @company_logo WHERE COMPANY_ID = @company_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `update_interface_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_interface_setting`(IN interface_setting_id INT(50), IN interface_setting_name VARCHAR(100), IN description VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @interface_setting_id = interface_setting_id;
	SET @interface_setting_name = interface_setting_name;
	SET @description = description;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_interface_setting SET INTERFACE_SETTING_NAME = @interface_setting_name, DESCRIPTION = @description, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE INTERFACE_SETTING_ID = @interface_setting_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `update_interface_settings_images` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_interface_settings_images`(IN interface_setting_id INT(50), IN file_path VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100), IN request_type VARCHAR(20))
BEGIN
	SET @interface_setting_id = interface_setting_id;
	SET @file_path = file_path;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;
	SET @request_type = request_type;

	IF @request_type = 'login background' THEN
		SET @query = 'UPDATE global_interface_setting SET LOGIN_BACKGROUND = @file_path, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE INTERFACE_SETTING_ID = @interface_setting_id';
	ELSEIF @request_type = 'login logo' THEN
		SET @query = 'UPDATE global_interface_setting SET LOGIN_LOGO = @file_path, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE INTERFACE_SETTING_ID = @interface_setting_id';
	ELSEIF @request_type = 'menu logo' THEN
		SET @query = 'UPDATE global_interface_setting SET MENU_LOGO = @file_path, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE INTERFACE_SETTING_ID = @interface_setting_id';
	ELSE
		SET @query = 'UPDATE global_interface_setting SET FAVICON = @file_path, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE INTERFACE_SETTING_ID = @interface_setting_id';
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
/*!50003 DROP PROCEDURE IF EXISTS `update_interface_setting_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_interface_setting_status`(IN interface_setting_id INT(50), IN status TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @interface_setting_id = interface_setting_id;
	SET @status = status;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_interface_setting SET STATUS = @status, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE INTERFACE_SETTING_ID = @interface_setting_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `update_other_interface_setting_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_other_interface_setting_status`(IN interface_setting_id INT(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @interface_setting_id = interface_setting_id;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_interface_setting SET STATUS = 2, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE INTERFACE_SETTING_ID != @interface_setting_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `update_role` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_role`(IN `role_id` VARCHAR(100), IN `role` VARCHAR(100), IN `role_description` VARCHAR(200), IN `assignable` TINYINT(1), IN `transaction_log_id` VARCHAR(100), IN `record_log` VARCHAR(100))
BEGIN
	SET @role_id = role_id;
	SET @role = role;
	SET @role_description = role_description;
	SET @assignable = assignable;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_role SET ROLE = @role, ROLE_DESCRIPTION = @role_description, ASSIGNABLE = @assignable, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ROLE_ID = @role_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `update_system_code` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_system_code`(IN system_code_id VARCHAR(100), IN system_type VARCHAR(20), IN system_code VARCHAR(20), IN system_description VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @system_code_id = system_code_id;
	SET @system_type = system_type;
	SET @system_code= system_code;
	SET @system_description= system_description;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_system_code SET SYSTEM_TYPE = @system_type, SYSTEM_CODE = @system_code, SYSTEM_DESCRIPTION = @system_description, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE SYSTEM_CODE_ID = @system_code_id';

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
/*!50003 DROP PROCEDURE IF EXISTS `update_upload_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_upload_setting`(IN upload_setting_id INT(50), IN upload_setting VARCHAR(100), IN description VARCHAR(100), IN max_file_size VARCHAR(10), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @upload_setting_id = upload_setting_id;
	SET @upload_setting = upload_setting;
	SET @description = description;
	SET @max_file_size = max_file_size;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_upload_setting SET UPLOAD_SETTING = @upload_setting, DESCRIPTION = @description, MAX_FILE_SIZE = @max_file_size, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE UPLOAD_SETTING_ID = @upload_setting_id';

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

-- Dump completed on 2022-12-09 17:32:11
