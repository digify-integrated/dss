-- MariaDB dump 10.19  Distrib 10.4.24-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: dssdb
-- ------------------------------------------------------
-- Server version	10.4.24-MariaDB

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
-- Table structure for table `employee_department`
--

DROP TABLE IF EXISTS `employee_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_department` (
  `DEPARTMENT_ID` varchar(50) NOT NULL,
  `DEPARTMENT` varchar(100) NOT NULL,
  `PARENT_DEPARTMENT` varchar(50) DEFAULT NULL,
  `MANAGER` varchar(100) DEFAULT NULL,
  `STATUS` tinyint(1) DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`DEPARTMENT_ID`),
  KEY `employee_department_index` (`DEPARTMENT_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_department`
--

LOCK TABLES `employee_department` WRITE;
/*!40000 ALTER TABLE `employee_department` DISABLE KEYS */;
INSERT INTO `employee_department` VALUES ('4','Data Center Department','','',1,'264','INS->ADMIN->2022-12-24 09:05:22');
/*!40000 ALTER TABLE `employee_department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_departure_reason`
--

DROP TABLE IF EXISTS `employee_departure_reason`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_departure_reason` (
  `DEPARTURE_REASON_ID` varchar(50) NOT NULL,
  `DEPARTURE_REASON` varchar(100) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`DEPARTURE_REASON_ID`),
  KEY `employee_departure_reason_index` (`DEPARTURE_REASON_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_departure_reason`
--

LOCK TABLES `employee_departure_reason` WRITE;
/*!40000 ALTER TABLE `employee_departure_reason` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_departure_reason` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_employee_type`
--

DROP TABLE IF EXISTS `employee_employee_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_employee_type` (
  `EMPLOYEE_TYPE_ID` varchar(50) NOT NULL,
  `EMPLOYEE_TYPE` varchar(100) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEE_TYPE_ID`),
  KEY `employee_employee_type_index` (`EMPLOYEE_TYPE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_employee_type`
--

LOCK TABLES `employee_employee_type` WRITE;
/*!40000 ALTER TABLE `employee_employee_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_employee_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_job_position`
--

DROP TABLE IF EXISTS `employee_job_position`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_job_position` (
  `JOB_POSITION_ID` varchar(100) NOT NULL,
  `JOB_POSITION` varchar(100) NOT NULL,
  `DESCRIPTION` varchar(500) NOT NULL,
  `RECRUITMENT_STATUS` tinyint(1) DEFAULT NULL,
  `DEPARTMENT` varchar(50) DEFAULT NULL,
  `EXPECTED_NEW_EMPLOYEES` int(10) DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`JOB_POSITION_ID`),
  KEY `employee_job_position_index` (`JOB_POSITION_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_job_position`
--

LOCK TABLES `employee_job_position` WRITE;
/*!40000 ALTER TABLE `employee_job_position` DISABLE KEYS */;
INSERT INTO `employee_job_position` VALUES ('6','Test','Test',2,'4',0,'TL-297','UPD->ADMIN->2022-12-27 04:53:20');
/*!40000 ALTER TABLE `employee_job_position` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_job_position_attachment`
--

DROP TABLE IF EXISTS `employee_job_position_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_job_position_attachment` (
  `ATTACHMENT_ID` varchar(100) NOT NULL,
  `JOB_POSITION_ID` varchar(100) NOT NULL,
  `ATTACHMENT_NAME` varchar(100) NOT NULL,
  `ATTACHMENT` varchar(500) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ATTACHMENT_ID`),
  KEY `employee_job_position_attachment_index` (`ATTACHMENT_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_job_position_attachment`
--

LOCK TABLES `employee_job_position_attachment` WRITE;
/*!40000 ALTER TABLE `employee_job_position_attachment` DISABLE KEYS */;
INSERT INTO `employee_job_position_attachment` VALUES ('1','1','Test','./assets/employee/job_position_attachment/oockfbh6ha.pdf','279','UPD->ADMIN->2022-12-27 11:52:15'),('2','1','Test','./assets/employee/job_position_attachment/1wvfd1e6x3.pdf','280','INS->ADMIN->2022-12-27 11:45:53'),('3','1','Test','./assets/employee/job_position_attachment/vg4ozglhqt.pdf','281','INS->ADMIN->2022-12-27 11:46:34');
/*!40000 ALTER TABLE `employee_job_position_attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_job_position_qualification`
--

DROP TABLE IF EXISTS `employee_job_position_qualification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_job_position_qualification` (
  `QUALIFICATION_ID` varchar(100) NOT NULL,
  `JOB_POSITION_ID` varchar(100) NOT NULL,
  `QUALIFICATION` varchar(500) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`QUALIFICATION_ID`),
  KEY `employee_job_position_qualification_index` (`QUALIFICATION_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_job_position_qualification`
--

LOCK TABLES `employee_job_position_qualification` WRITE;
/*!40000 ALTER TABLE `employee_job_position_qualification` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_job_position_qualification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_job_position_requirement`
--

DROP TABLE IF EXISTS `employee_job_position_requirement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_job_position_requirement` (
  `REQUIREMENT_ID` varchar(100) NOT NULL,
  `JOB_POSITION_ID` varchar(100) NOT NULL,
  `REQUIREMENT` varchar(500) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`REQUIREMENT_ID`),
  KEY `employee_job_position_requirement_index` (`REQUIREMENT_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_job_position_requirement`
--

LOCK TABLES `employee_job_position_requirement` WRITE;
/*!40000 ALTER TABLE `employee_job_position_requirement` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_job_position_requirement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_job_position_responsibility`
--

DROP TABLE IF EXISTS `employee_job_position_responsibility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_job_position_responsibility` (
  `RESPONSIBILITY_ID` varchar(100) NOT NULL,
  `JOB_POSITION_ID` varchar(100) NOT NULL,
  `RESPONSIBILITY` varchar(500) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`RESPONSIBILITY_ID`),
  KEY `employee_job_position_responsibility_index` (`RESPONSIBILITY_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_job_position_responsibility`
--

LOCK TABLES `employee_job_position_responsibility` WRITE;
/*!40000 ALTER TABLE `employee_job_position_responsibility` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_job_position_responsibility` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_wage_type`
--

DROP TABLE IF EXISTS `employee_wage_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_wage_type` (
  `WAGE_TYPE_ID` varchar(50) NOT NULL,
  `WAGE_TYPE` varchar(100) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`WAGE_TYPE_ID`),
  KEY `employee_wage_type_index` (`WAGE_TYPE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_wage_type`
--

LOCK TABLES `employee_wage_type` WRITE;
/*!40000 ALTER TABLE `employee_wage_type` DISABLE KEYS */;
INSERT INTO `employee_wage_type` VALUES ('3','Test2','TL-337','UPD->ADMIN->2023-01-02 11:18:53');
/*!40000 ALTER TABLE `employee_wage_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_work_location`
--

DROP TABLE IF EXISTS `employee_work_location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_work_location` (
  `WORK_LOCATION_ID` varchar(50) NOT NULL,
  `WORK_LOCATION` varchar(100) NOT NULL,
  `WORK_LOCATION_ADDRESS` varchar(500) DEFAULT NULL,
  `EMAIL` varchar(50) DEFAULT NULL,
  `TELEPHONE` varchar(20) DEFAULT NULL,
  `MOBILE` varchar(20) DEFAULT NULL,
  `LOCATION_NUMBER` int(10) DEFAULT NULL,
  `STATUS` tinyint(1) DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`WORK_LOCATION_ID`),
  KEY `employee_work_location_index` (`WORK_LOCATION_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_work_location`
--

LOCK TABLES `employee_work_location` WRITE;
/*!40000 ALTER TABLE `employee_work_location` DISABLE KEYS */;
INSERT INTO `employee_work_location` VALUES ('3','te','ste','','','',1,1,'TL-309','INS->ADMIN->2022-12-29 01:47:10');
/*!40000 ALTER TABLE `employee_work_location` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Table structure for table `global_country`
--

DROP TABLE IF EXISTS `global_country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_country` (
  `COUNTRY_ID` int(50) NOT NULL,
  `COUNTRY_NAME` varchar(200) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`COUNTRY_ID`),
  KEY `global_country_index` (`COUNTRY_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_country`
--

LOCK TABLES `global_country` WRITE;
/*!40000 ALTER TABLE `global_country` DISABLE KEYS */;
INSERT INTO `global_country` VALUES (4,'Test','220','INS->ADMIN->2022-12-16 04:09:00');
/*!40000 ALTER TABLE `global_country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_email_setting`
--

DROP TABLE IF EXISTS `global_email_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_email_setting` (
  `EMAIL_SETTING_ID` int(50) NOT NULL,
  `EMAIL_SETTING_NAME` varchar(100) NOT NULL,
  `DESCRIPTION` varchar(200) NOT NULL,
  `STATUS` tinyint(1) NOT NULL,
  `MAIL_HOST` varchar(100) NOT NULL,
  `PORT` int(11) NOT NULL,
  `SMTP_AUTH` int(1) NOT NULL,
  `SMTP_AUTO_TLS` int(1) NOT NULL,
  `MAIL_USERNAME` varchar(200) NOT NULL,
  `MAIL_PASSWORD` varchar(200) NOT NULL,
  `MAIL_ENCRYPTION` varchar(20) DEFAULT NULL,
  `MAIL_FROM_NAME` varchar(200) DEFAULT NULL,
  `MAIL_FROM_EMAIL` varchar(200) DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`EMAIL_SETTING_ID`),
  KEY `global_email_setting_index` (`EMAIL_SETTING_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_email_setting`
--

LOCK TABLES `global_email_setting` WRITE;
/*!40000 ALTER TABLE `global_email_setting` DISABLE KEYS */;
INSERT INTO `global_email_setting` VALUES (7,'asd','asd',1,'asd',0,0,0,'asd','sad','SSL','asda','asd','209','UPD->ADMIN->2022-12-16 02:34:14');
/*!40000 ALTER TABLE `global_email_setting` ENABLE KEYS */;
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
INSERT INTO `global_interface_setting` VALUES (1,'Encore Leasing and Finance Corp.','Interface setting for Encore Leasing and Finance Corp.',1,NULL,NULL,NULL,NULL,'138','UPD->ADMIN->2022-12-16 02:24:11');
/*!40000 ALTER TABLE `global_interface_setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_notification_channel`
--

DROP TABLE IF EXISTS `global_notification_channel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_notification_channel` (
  `NOTIFICATION_SETTING_ID` int(50) DEFAULT NULL,
  `CHANNEL` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_notification_channel`
--

LOCK TABLES `global_notification_channel` WRITE;
/*!40000 ALTER TABLE `global_notification_channel` DISABLE KEYS */;
INSERT INTO `global_notification_channel` VALUES (4,'EMAIL');
/*!40000 ALTER TABLE `global_notification_channel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_notification_role_recipient`
--

DROP TABLE IF EXISTS `global_notification_role_recipient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_notification_role_recipient` (
  `NOTIFICATION_SETTING_ID` int(50) DEFAULT NULL,
  `ROLE_ID` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_notification_role_recipient`
--

LOCK TABLES `global_notification_role_recipient` WRITE;
/*!40000 ALTER TABLE `global_notification_role_recipient` DISABLE KEYS */;
/*!40000 ALTER TABLE `global_notification_role_recipient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_notification_setting`
--

DROP TABLE IF EXISTS `global_notification_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_notification_setting` (
  `NOTIFICATION_SETTING_ID` int(50) NOT NULL,
  `NOTIFICATION_SETTING` varchar(100) NOT NULL,
  `DESCRIPTION` varchar(200) NOT NULL,
  `NOTIFICATION_TITLE` varchar(500) DEFAULT NULL,
  `NOTIFICATION_MESSAGE` varchar(500) DEFAULT NULL,
  `SYSTEM_LINK` varchar(200) DEFAULT NULL,
  `EMAIL_LINK` varchar(200) DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`NOTIFICATION_SETTING_ID`),
  KEY `global_notification_setting_index` (`NOTIFICATION_SETTING_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_notification_setting`
--

LOCK TABLES `global_notification_setting` WRITE;
/*!40000 ALTER TABLE `global_notification_setting` DISABLE KEYS */;
INSERT INTO `global_notification_setting` VALUES (4,'asd','asd','asd','asd','asd','asd','212','INS->ADMIN->2022-12-16 03:15:40');
/*!40000 ALTER TABLE `global_notification_setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_notification_user_account_recipient`
--

DROP TABLE IF EXISTS `global_notification_user_account_recipient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_notification_user_account_recipient` (
  `NOTIFICATION_SETTING_ID` int(50) DEFAULT NULL,
  `USERNAME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_notification_user_account_recipient`
--

LOCK TABLES `global_notification_user_account_recipient` WRITE;
/*!40000 ALTER TABLE `global_notification_user_account_recipient` DISABLE KEYS */;
/*!40000 ALTER TABLE `global_notification_user_account_recipient` ENABLE KEYS */;
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
-- Table structure for table `global_state`
--

DROP TABLE IF EXISTS `global_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_state` (
  `STATE_ID` int(50) NOT NULL,
  `STATE_NAME` varchar(200) NOT NULL,
  `COUNTRY_ID` int(50) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`STATE_ID`),
  KEY `global_state_index` (`STATE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_state`
--

LOCK TABLES `global_state` WRITE;
/*!40000 ALTER TABLE `global_state` DISABLE KEYS */;
INSERT INTO `global_state` VALUES (14,'asd',4,'TL-298','INS->ADMIN->2022-12-28 02:05:54');
/*!40000 ALTER TABLE `global_state` ENABLE KEYS */;
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
INSERT INTO `global_system_code` VALUES ('1','SYSTYPE','SYSTYPE','System Code','TL-4',NULL),('10','FILETYPE','mp4','Video (.mp4)','77','INS->ADMIN->2022-12-07 11:55:59'),('11','FILETYPE','mkv','Video (.mkv)','78','INS->ADMIN->2022-12-07 11:56:17'),('12','FILETYPE','mov','Video (.mov)','79','INS->ADMIN->2022-12-07 11:56:36'),('13','FILETYPE','mpg','Video (.mpg)','80','INS->ADMIN->2022-12-07 11:56:50'),('14','FILETYPE','mpeg','Video (.mpeg)','81','INS->ADMIN->2022-12-07 11:57:00'),('15','FILETYPE','avi','Video (.avi)','82','INS->ADMIN->2022-12-07 11:57:13'),('16','FILETYPE','flv','Video (.flv)','83','INS->ADMIN->2022-12-07 11:57:23'),('17','FILETYPE','wmv','Video (.wmv)','84','INS->ADMIN->2022-12-07 11:57:36'),('18','FILETYPE','gif','Image (.gif)','85','INS->ADMIN->2022-12-07 11:57:49'),('19','FILETYPE','wav','Audio (.wav)','86','INS->ADMIN->2022-12-07 11:58:05'),('2','SYSTYPE','MODULECAT','Module Category','TL-5',NULL),('20','FILETYPE','doc','Word (.doc)','87','INS->ADMIN->2022-12-07 11:58:16'),('21','FILETYPE','docx','Word (.docx)','88','INS->ADMIN->2022-12-07 11:58:27'),('22','FILETYPE','xls','Excel (.xls)','89','INS->ADMIN->2022-12-07 11:58:38'),('23','FILETYPE','xlsx','Excel (.xlsx)','90','INS->ADMIN->2022-12-07 11:58:50'),('24','FILETYPE','ppt','Powerpoint (.ppt)','91','INS->ADMIN->2022-12-07 11:59:02'),('25','FILETYPE','pptx','Powerpoint (.pptx)','92','INS->ADMIN->2022-12-07 11:59:11'),('26','FILETYPE','zip','Compressed (.zip)','93','INS->ADMIN->2022-12-07 11:59:34'),('27','FILETYPE','7z','Compressed (.7z)','94','INS->ADMIN->2022-12-07 11:59:44'),('28','FILETYPE','rar','Compressed (.rar)','95','INS->ADMIN->2022-12-07 11:59:55'),('29','FILETYPE','pdf','Document (.pdf)','96','INS->ADMIN->2022-12-07 12:00:06'),('3','MODULECAT','TECHNICAL','Technical','TL-6',NULL),('30','FILETYPE','txt','Document (.txt)','97','INS->ADMIN->2022-12-07 12:00:17'),('31','FILETYPE','csv','Data (.csv)','98','INS->ADMIN->2022-12-07 12:00:27'),('32','FILETYPE','mp3','Audio (.mp3)','99','INS->ADMIN->2022-12-07 12:00:38'),('33','FILETYPE','sql','Data (.sql)','100','INS->ADMIN->2022-12-07 12:00:56'),('34','SYSTYPE','MAILENCRYPTION','Mail Encryption','146','INS->ADMIN->2022-12-12 01:56:26'),('35','MAILENCRYPTION','SSL','SSL','147','INS->ADMIN->2022-12-12 01:56:37'),('36','MAILENCRYPTION','None','None','148','INS->ADMIN->2022-12-12 01:56:45'),('37','MAILENCRYPTION','STARTTLS','STARTTLS','149','INS->ADMIN->2022-12-12 01:56:54'),('38','MAILENCRYPTION','TLS','TLS','150','INS->ADMIN->2022-12-12 01:57:10'),('39','SYSTYPE','NOTIFICATIONCHANNEL','Notification Channel','170','INS->ADMIN->2022-12-14 01:03:03'),('4','SYSTYPE','FILETYPE','File Type','71','INS->ADMIN->2022-12-07 11:51:52'),('40','NOTIFICATIONCHANNEL','EMAIL','Email','171','INS->ADMIN->2022-12-14 01:03:18'),('41','NOTIFICATIONCHANNEL','SYSTEM','System','172','INS->ADMIN->2022-12-14 01:03:34'),('42','MODULECAT','HUMANRESOURCES','Human Resources','231','INS->ADMIN->2022-12-21 02:27:52'),('43','SYSTYPE','SCHEDULEPAY','Schedule Pay','TL-341','UPD->ADMIN->2023-01-02 11:22:53'),('44','SCHEDULEPAY','MONTHLY','Monthly','TL-342','INS->ADMIN->2023-01-02 11:23:13'),('45','SCHEDULEPAY','QUARTERLY','Quarterly','TL-343','INS->ADMIN->2023-01-02 11:24:40'),('46','SCHEDULEPAY','SEMI-ANNUALLY','Semi-annually','TL-344','INS->ADMIN->2023-01-02 11:25:03'),('47','SCHEDULEPAY','ANNUALLY','Annually','TL-345','INS->ADMIN->2023-01-02 11:25:21'),('48','SCHEDULEPAY','WEEKLY','Weekly','TL-346','INS->ADMIN->2023-01-02 11:25:34'),('49','SCHEDULEPAY','BI-WEEKLY','Bi-weekly','TL-347','INS->ADMIN->2023-01-02 11:25:51'),('5','FILETYPE','svg','Image (.svg)','72','INS->ADMIN->2022-12-07 11:54:58'),('50','SCHEDULEPAY','BI-MONTHLY','Bi-monthly','TL-348','INS->ADMIN->2023-01-02 11:26:17'),('6','FILETYPE','png','Image (.png)','73','INS->ADMIN->2022-12-07 11:55:08'),('7','FILETYPE','jpg','Image (.jpg)','74','INS->ADMIN->2022-12-07 11:55:23'),('8','FILETYPE','ico','Image (.ico)','75','INS->ADMIN->2022-12-07 11:55:37'),('9','FILETYPE','jpeg','Image (.jpeg)','76','INS->ADMIN->2022-12-07 11:55:49');
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
INSERT INTO `global_system_parameters` VALUES (1,'System Parameter','Parameter for system parameters.','',25,'TL-15','UPD->ADMIN->2023-01-02 10:26:24'),(2,'Transaction Log','Parameter for transaction logs.','TL-',348,'TL-16','UPD->ADMIN->2023-01-02 11:26:17'),(3,'Module','Parameter for modules.','',5,'TL-17','UPD->ADMIN->2022-12-21 02:36:46'),(4,'Page','Parameter for pages.','',42,'TL-25','UPD->ADMIN->2023-01-02 10:21:28'),(5,'Action','Parameter for actions.','',116,'TL-33','UPD->ADMIN->2023-01-02 11:10:59'),(6,'Role','Parameter for roles.','',1,'TL-39','UPD->ADMIN->2022-12-06 03:00:37'),(7,'Upload Settings','Parameter for upload settings.','',7,'57','UPD->ADMIN->2022-12-23 04:42:20'),(8,'System Code','Parameter for system code.','',50,'68','UPD->ADMIN->2023-01-02 11:26:17'),(9,'Company','Parameter for company.','',1,'110','UPD->ADMIN->2022-12-09 09:45:59'),(10,'Interface Setting','Parameter for interface setting.','',1,'120','UPD->ADMIN->2022-12-12 12:02:53'),(11,'Email Setting','Parameter for email setting.','',7,'151','UPD->ADMIN->2022-12-16 02:16:48'),(12,'Notification Setting','Parameter for notification setting.','',4,'158','UPD->ADMIN->2022-12-16 03:15:40'),(13,'Country','Parameter for country.','',6,'176','UPD->ADMIN->2022-12-16 04:10:50'),(14,'State','Parameter for state.','',14,'177','UPD->ADMIN->2022-12-28 02:05:54'),(15,'Zoom API','Parameter for Zoom API','',4,'204','UPD->ADMIN->2022-12-16 11:48:24'),(16,'Department','Parameter for department.','',4,'242','UPD->ADMIN->2022-12-24 09:05:22'),(17,'Job Position','Parameter for job position.','',6,'263','UPD->ADMIN->2022-12-27 01:57:40'),(18,'Job Position Responsibility','Parameter for job position responsibility.','',3,'270','UPD->ADMIN->2022-12-27 01:31:19'),(19,'Job Position Requirement','Parameter for job position requirement.','',0,'271','INS->ADMIN->2022-12-24 05:44:34'),(20,'Job Position Qualification','Parameter for job position qualification.','',1,'272','UPD->ADMIN->2022-12-27 01:31:29'),(21,'Job Position Attachment','Parameter for job position attachment.','',5,'273','UPD->ADMIN->2022-12-27 01:10:52'),(22,'Work Location','Parameter for work location.','',3,'TL-306','UPD->ADMIN->2022-12-29 01:47:10'),(23,'Departure Reason','Parameter for departure reason','',4,'TL-310','UPD->ADMIN->2022-12-31 02:15:52'),(24,'Employee Type','Parameter for employee type.','',3,'TL-323','UPD->ADMIN->2022-12-31 04:22:51'),(25,'Wage Type','Parameter for wage type.','',3,'TL-331','UPD->ADMIN->2023-01-02 11:18:46');
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
INSERT INTO `global_transaction_log` VALUES ('TL-1','ADMIN','Update','2022-09-20 13:37:24','User ADMIN updated user account password.'),('TL-1','ADMIN','Log In','2022-09-20 13:37:28','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-09-21 09:59:22','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-09-22 13:34:58','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-09-23 08:35:00','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-24 17:01:02','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-24 17:01:20','User ADMIN logged in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:01:45','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-10-24 17:01:52','User ADMIN logged in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:02:56','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-10-24 17:03:37','User ADMIN logged in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:38','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:39','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:40','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:40','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:41','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-25 09:51:47','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-25 09:51:50','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-10-25 09:51:52','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-26 08:57:46','User ADMIN logged in.'),('TL-1','ADMIN','Attempt Log In','2022-10-27 13:02:32','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-10-27 13:02:36','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-28 09:08:02','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-29 21:45:41','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-30 09:07:23','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-30 18:47:01','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-31 09:44:15','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-15 09:11:11','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-17 15:54:46','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-18 11:34:23','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-21 15:50:26','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-22 10:51:22','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-22 16:49:17','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-29 09:14:44','User ADMIN logged in.'),('TL-3','ADMIN','Update','2022-11-29 16:33:49','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:33:51','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:33:56','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:34:02','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:35:09','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:36:19','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:44:11','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:44:30','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:17','User ADMIN updated icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:17','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:45','User ADMIN updated icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:45','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:48','User ADMIN updated module icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:48','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:48','User ADMIN updated module icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:48','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:59','User ADMIN updated module icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:59','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:47:24','User ADMIN updated module icon.'),('TL-3','ADMIN','Update','2022-11-29 16:47:24','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:47:35','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 17:12:25','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 17:12:30','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 17:12:40','User ADMIN updated module.'),('TL-18','ADMIN','Insert','2022-11-29 17:13:15','User ADMIN inserted module.'),('TL-19','ADMIN','Insert','2022-11-29 17:14:06','User ADMIN inserted module.'),('TL-20','ADMIN','Insert','2022-11-30 12:34:02','User ADMIN inserted module.'),('TL-21','ADMIN','Insert','2022-11-30 12:34:11','User ADMIN inserted module.'),('TL-22','ADMIN','Insert','2022-11-30 12:35:09','User ADMIN inserted module.'),('TL-23','ADMIN','Insert','2022-11-30 12:35:41','User ADMIN inserted module.'),('TL-24','ADMIN','Insert','2022-11-30 12:36:17','User ADMIN inserted module.'),('TL-1','ADMIN','Log In','2022-11-30 17:56:03','User ADMIN logged in.'),('TL-24','ADMIN','Update','2022-11-30 18:03:01','User ADMIN updated module.'),('TL-1','ADMIN','Log In','2022-11-30 20:11:21','User ADMIN logged in.'),('TL-25','ADMIN','Insert','2022-11-30 20:18:14','User ADMIN inserted module.'),('TL-3','ADMIN','Update','2022-11-30 20:22:04','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-30 20:22:09','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-30 20:22:15','User ADMIN updated module.'),('TL-1','ADMIN','Log In','2022-12-01 10:41:23','User ADMIN logged in.'),('TL-26','ADMIN','Insert','2022-12-01 13:22:24','User ADMIN inserted page.'),('TL-27','ADMIN','Insert','2022-12-01 13:33:06','User ADMIN inserted page.'),('TL-28','ADMIN','Insert','2022-12-01 13:34:24','User ADMIN inserted module.'),('TL-29','ADMIN','Insert','2022-12-01 14:05:33','User ADMIN inserted page.'),('TL-26','ADMIN','Update','2022-12-01 14:29:29','User ADMIN updated page.'),('TL-1','ADMIN','Log In','2022-12-02 09:25:01','User ADMIN logged in.'),('TL-34','ADMIN','Insert','2022-12-02 09:30:22','User ADMIN inserted action.'),('TL-21','ADMIN','Update','2022-12-02 09:37:48','User ADMIN updated action.'),('TL-34','ADMIN','Insert','2022-12-02 09:55:03','User ADMIN inserted page.'),('TL-35','ADMIN','Insert','2022-12-02 13:57:57','User ADMIN inserted action.'),('TL-36','ADMIN','Insert','2022-12-02 13:58:13','User ADMIN inserted action.'),('TL-37','ADMIN','Insert','2022-12-02 13:58:24','User ADMIN inserted action.'),('TL-38','ADMIN','Insert','2022-12-02 14:47:02','User ADMIN inserted page.'),('TL-34','ADMIN','Update','2022-12-02 14:47:59','User ADMIN updated page.'),('TL-15','ADMIN','Update','2022-12-02 17:19:30','User ADMIN updated system parameter.'),('TL-39','ADMIN','Insert','2022-12-02 17:20:07','User ADMIN inserted system parameter.'),('TL-39','ADMIN','Update','2022-12-02 17:20:17','User ADMIN updated system parameter.'),('TL-15','ADMIN','Update','2022-12-02 17:21:53','User ADMIN updated system parameter.'),('TL-16','ADMIN','Update','2022-12-02 17:22:03','User ADMIN updated system parameter.'),('39','ADMIN','Insert','2022-12-02 17:22:15','User ADMIN inserted system parameter.'),('39','ADMIN','Update','2022-12-02 17:22:20','User ADMIN updated system parameter.'),('40','ADMIN','Insert','2022-12-02 17:22:59','User ADMIN inserted page.'),('TL-25','ADMIN','Update','2022-12-02 17:23:23','User ADMIN updated system parameter.'),('TL-16','ADMIN','Update','2022-12-02 17:23:32','User ADMIN updated system parameter.'),('40','ADMIN','Insert','2022-12-02 17:24:52','User ADMIN inserted page.'),('41','ADMIN','Insert','2022-12-02 17:25:12','User ADMIN inserted page.'),('42','ADMIN','Insert','2022-12-02 17:25:25','User ADMIN inserted action.'),('TL-33','ADMIN','Update','2022-12-02 17:25:56','User ADMIN updated system parameter.'),('TL-16','ADMIN','Update','2022-12-02 17:26:03','User ADMIN updated system parameter.'),('42','ADMIN','Insert','2022-12-02 17:26:22','User ADMIN inserted action.'),('42','ADMIN','Update','2022-12-02 17:26:24','User ADMIN updated action.'),('43','ADMIN','Insert','2022-12-02 17:26:44','User ADMIN inserted action.'),('43','ADMIN','Update','2022-12-02 17:26:53','User ADMIN updated action.'),('44','ADMIN','Insert','2022-12-02 17:26:59','User ADMIN inserted action.'),('45','ADMIN','Insert','2022-12-02 17:27:18','User ADMIN inserted action.'),('46','ADMIN','Insert','2022-12-02 17:27:34','User ADMIN inserted action.'),('47','ADMIN','Insert','2022-12-02 17:27:51','User ADMIN inserted action.'),('48','ADMIN','Insert','2022-12-02 17:28:04','User ADMIN inserted action.'),('49','ADMIN','Insert','2022-12-02 17:28:17','User ADMIN inserted action.'),('50','ADMIN','Insert','2022-12-02 17:28:30','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-04 09:50:27','User ADMIN logged in.'),('51','ADMIN','Insert','2022-12-04 09:52:54','User ADMIN inserted action.'),('52','ADMIN','Insert','2022-12-04 09:53:16','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-05 11:32:09','User ADMIN logged in.'),('53','ADMIN','Insert','2022-12-05 15:45:35','User ADMIN inserted role.'),('54','ADMIN','Insert','2022-12-05 15:55:59','User ADMIN inserted role.'),('55','ADMIN','Insert','2022-12-05 16:06:03','User ADMIN inserted role.'),('TL-1','ADMIN','Log In','2022-12-06 11:26:41','User ADMIN logged in.'),('56','ADMIN','Insert','2022-12-06 11:28:27','User ADMIN inserted role.'),('TL-39','ADMIN','Update','2022-12-06 15:00:37','User ADMIN updated system parameter.'),('57','ADMIN','Insert','2022-12-06 15:07:33','User ADMIN inserted system parameter.'),('58','ADMIN','Insert','2022-12-06 15:07:55','User ADMIN inserted page.'),('59','ADMIN','Insert','2022-12-06 15:10:25','User ADMIN inserted action.'),('60','ADMIN','Insert','2022-12-06 15:10:45','User ADMIN inserted action.'),('61','ADMIN','Insert','2022-12-06 15:11:18','User ADMIN inserted action.'),('62','ADMIN','Insert','2022-12-06 16:11:17','User ADMIN inserted page.'),('63','ADMIN','Insert','2022-12-06 17:02:51','User ADMIN inserted page.'),('63','ADMIN','Update','2022-12-06 17:02:54','User ADMIN updated page.'),('64','ADMIN','Insert','2022-12-06 17:03:46','User ADMIN inserted page.'),('65','ADMIN','Insert','2022-12-06 17:03:57','User ADMIN inserted action.'),('66','ADMIN','Insert','2022-12-06 17:04:18','User ADMIN inserted action.'),('67','ADMIN','Insert','2022-12-06 17:04:34','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-07 09:41:11','User ADMIN logged in.'),('68','ADMIN','Insert','2022-12-07 10:01:07','User ADMIN inserted system parameter.'),('68','ADMIN','Update','2022-12-07 10:01:31','User ADMIN updated system parameter.'),('69','ADMIN','Insert','2022-12-07 11:43:14','User ADMIN inserted system code.'),('68','ADMIN','Update','2022-12-07 11:43:54','User ADMIN updated system parameter.'),('70','ADMIN','Insert','2022-12-07 11:44:06','User ADMIN inserted system code.'),('68','ADMIN','Update','2022-12-07 11:47:14','User ADMIN updated system parameter.'),('71','ADMIN','Insert','2022-12-07 11:51:52','User ADMIN inserted system code.'),('72','ADMIN','Insert','2022-12-07 11:54:58','User ADMIN inserted system code.'),('73','ADMIN','Insert','2022-12-07 11:55:08','User ADMIN inserted system code.'),('74','ADMIN','Insert','2022-12-07 11:55:23','User ADMIN inserted system code.'),('75','ADMIN','Insert','2022-12-07 11:55:37','User ADMIN inserted system code.'),('76','ADMIN','Insert','2022-12-07 11:55:49','User ADMIN inserted system code.'),('77','ADMIN','Insert','2022-12-07 11:55:59','User ADMIN inserted system code.'),('78','ADMIN','Insert','2022-12-07 11:56:17','User ADMIN inserted system code.'),('79','ADMIN','Insert','2022-12-07 11:56:36','User ADMIN inserted system code.'),('80','ADMIN','Insert','2022-12-07 11:56:50','User ADMIN inserted system code.'),('81','ADMIN','Insert','2022-12-07 11:57:00','User ADMIN inserted system code.'),('82','ADMIN','Insert','2022-12-07 11:57:13','User ADMIN inserted system code.'),('83','ADMIN','Insert','2022-12-07 11:57:23','User ADMIN inserted system code.'),('84','ADMIN','Insert','2022-12-07 11:57:36','User ADMIN inserted system code.'),('85','ADMIN','Insert','2022-12-07 11:57:49','User ADMIN inserted system code.'),('86','ADMIN','Insert','2022-12-07 11:58:05','User ADMIN inserted system code.'),('87','ADMIN','Insert','2022-12-07 11:58:16','User ADMIN inserted system code.'),('88','ADMIN','Insert','2022-12-07 11:58:27','User ADMIN inserted system code.'),('89','ADMIN','Insert','2022-12-07 11:58:38','User ADMIN inserted system code.'),('90','ADMIN','Insert','2022-12-07 11:58:50','User ADMIN inserted system code.'),('91','ADMIN','Insert','2022-12-07 11:59:02','User ADMIN inserted system code.'),('92','ADMIN','Insert','2022-12-07 11:59:11','User ADMIN inserted system code.'),('93','ADMIN','Insert','2022-12-07 11:59:34','User ADMIN inserted system code.'),('94','ADMIN','Insert','2022-12-07 11:59:44','User ADMIN inserted system code.'),('95','ADMIN','Insert','2022-12-07 11:59:55','User ADMIN inserted system code.'),('96','ADMIN','Insert','2022-12-07 12:00:06','User ADMIN inserted system code.'),('97','ADMIN','Insert','2022-12-07 12:00:17','User ADMIN inserted system code.'),('98','ADMIN','Insert','2022-12-07 12:00:27','User ADMIN inserted system code.'),('99','ADMIN','Insert','2022-12-07 12:00:38','User ADMIN inserted system code.'),('100','ADMIN','Insert','2022-12-07 12:00:56','User ADMIN inserted system code.'),('101','ADMIN','Insert','2022-12-07 13:22:51','User ADMIN inserted action.'),('102','ADMIN','Insert','2022-12-07 13:23:12','User ADMIN inserted action.'),('57','ADMIN','Update','2022-12-07 15:18:57','User ADMIN updated system parameter.'),('103','ADMIN','Insert','2022-12-07 15:18:59','User ADMIN inserted upload setting.'),('104','ADMIN','Insert','2022-12-07 15:25:19','User ADMIN inserted upload setting.'),('57','ADMIN','Update','2022-12-07 15:25:36','User ADMIN updated system parameter.'),('105','ADMIN','Insert','2022-12-07 15:50:51','User ADMIN inserted page.'),('106','ADMIN','Insert','2022-12-07 15:51:05','User ADMIN inserted page.'),('107','ADMIN','Insert','2022-12-07 15:54:10','User ADMIN inserted action.'),('107','ADMIN','Update','2022-12-07 15:54:12','User ADMIN updated action.'),('108','ADMIN','Insert','2022-12-07 15:54:24','User ADMIN inserted action.'),('109','ADMIN','Insert','2022-12-07 15:54:36','User ADMIN inserted action.'),('110','ADMIN','Insert','2022-12-07 16:50:52','User ADMIN inserted system parameter.'),('110','ADMIN','Update','2022-12-07 16:51:03','User ADMIN updated system parameter.'),('111','ADMIN','Insert','2022-12-07 16:53:02','User ADMIN inserted upload setting.'),('112','ADMIN','Insert','2022-12-07 17:36:22','User ADMIN inserted company.'),('113','ADMIN','Insert','2022-12-07 17:36:27','User ADMIN inserted company.'),('114','ADMIN','Insert','2022-12-07 17:37:25','User ADMIN inserted company.'),('115','ADMIN','Insert','2022-12-07 17:38:34','User ADMIN inserted company.'),('115','ADMIN','Update','2022-12-07 17:38:34','User ADMIN updated company logo.'),('TL-1','ADMIN','Log In','2022-12-09 08:45:56','User ADMIN logged in.'),('110','ADMIN','Update','2022-12-09 08:48:46','User ADMIN updated system parameter.'),('110','ADMIN','Update','2022-12-09 08:48:51','User ADMIN updated system parameter.'),('116','ADMIN','Insert','2022-12-09 08:50:50','User ADMIN inserted company.'),('116','ADMIN','Update','2022-12-09 08:50:50','User ADMIN updated company logo.'),('110','ADMIN','Update','2022-12-09 09:19:09','User ADMIN updated system parameter.'),('117','ADMIN','Insert','2022-12-09 09:36:26','User ADMIN inserted company.'),('117','ADMIN','Update','2022-12-09 09:36:26','User ADMIN updated company logo.'),('110','ADMIN','Update','2022-12-09 09:41:50','User ADMIN updated system parameter.'),('118','ADMIN','Insert','2022-12-09 09:42:02','User ADMIN inserted company.'),('118','ADMIN','Update','2022-12-09 09:42:02','User ADMIN updated company logo.'),('110','ADMIN','Update','2022-12-09 09:45:44','User ADMIN updated system parameter.'),('119','ADMIN','Insert','2022-12-09 09:45:59','User ADMIN inserted company.'),('119','ADMIN','Update','2022-12-09 09:45:59','User ADMIN updated company logo.'),('120','ADMIN','Insert','2022-12-09 09:56:50','User ADMIN inserted system parameter.'),('120','ADMIN','Update','2022-12-09 09:56:53','User ADMIN updated system parameter.'),('121','ADMIN','Insert','2022-12-09 09:57:14','User ADMIN inserted page.'),('122','ADMIN','Insert','2022-12-09 09:57:28','User ADMIN inserted action.'),('123','ADMIN','Insert','2022-12-09 09:57:41','User ADMIN inserted action.'),('124','ADMIN','Insert','2022-12-09 09:58:04','User ADMIN inserted action.'),('124','ADMIN','Update','2022-12-09 09:58:15','User ADMIN updated action.'),('125','ADMIN','Insert','2022-12-09 09:58:46','User ADMIN inserted action.'),('126','ADMIN','Insert','2022-12-09 09:59:03','User ADMIN inserted action.'),('127','ADMIN','Insert','2022-12-09 10:00:19','User ADMIN inserted page.'),('128','ADMIN','Insert','2022-12-09 10:16:09','User ADMIN inserted upload setting.'),('129','ADMIN','Insert','2022-12-09 10:16:41','User ADMIN inserted upload setting.'),('130','ADMIN','Insert','2022-12-09 10:18:34','User ADMIN inserted upload setting.'),('131','ADMIN','Insert','2022-12-09 10:19:11','User ADMIN inserted upload setting.'),('127','ADMIN','Update','2022-12-09 10:35:36','User ADMIN updated page.'),('121','ADMIN','Update','2022-12-09 10:35:44','User ADMIN updated page.'),('122','ADMIN','Update','2022-12-09 10:38:57','User ADMIN updated action.'),('123','ADMIN','Update','2022-12-09 10:39:01','User ADMIN updated action.'),('123','ADMIN','Update','2022-12-09 10:39:06','User ADMIN updated action.'),('124','ADMIN','Update','2022-12-09 10:39:12','User ADMIN updated action.'),('125','ADMIN','Update','2022-12-09 10:39:18','User ADMIN updated action.'),('126','ADMIN','Update','2022-12-09 10:39:24','User ADMIN updated action.'),('132','ADMIN','Insert','2022-12-09 16:10:09','User ADMIN inserted interface setting.'),('132','ADMIN','Update','2022-12-09 16:10:09','User ADMIN updated login background.'),('132','ADMIN','Update','2022-12-09 16:10:09','User ADMIN updated login logo.'),('132','ADMIN','Update','2022-12-09 16:10:09','User ADMIN updated menu logo.'),('132','ADMIN','Update','2022-12-09 16:10:09','User ADMIN updated favicon.'),('133','ADMIN','Insert','2022-12-09 16:13:29','User ADMIN inserted interface setting.'),('134','ADMIN','Insert','2022-12-09 16:53:03','User ADMIN inserted interface setting.'),('135','ADMIN','Insert','2022-12-09 16:53:14','User ADMIN inserted interface setting.'),('136','ADMIN','Insert','2022-12-09 17:06:14','User ADMIN inserted interface setting.'),('137','ADMIN','Insert','2022-12-09 17:06:22','User ADMIN inserted interface setting.'),('136','ADMIN','Deactivate','2022-12-09 17:06:30','User ADMIN updated interface setting status.'),('136','ADMIN','Activate','2022-12-09 17:07:50','User ADMIN updated interface setting status.'),('136','ADMIN','Activate','2022-12-09 17:08:12','User ADMIN updated interface setting status.'),('136','ADMIN','Deactivate','2022-12-09 17:08:13','User ADMIN updated interface setting status.'),('136','ADMIN','Activate','2022-12-09 17:09:16','User ADMIN updated interface setting status.'),('136','ADMIN','Deactivate','2022-12-09 17:09:16','User ADMIN updated interface setting status.'),('136','ADMIN','Activate','2022-12-09 17:10:29','User ADMIN updated interface setting status.'),('136','ADMIN','Deactivate','2022-12-09 17:10:29','User ADMIN updated interface setting status.'),('136','ADMIN','Deactivate','2022-12-09 17:13:33','User ADMIN updated interface setting status.'),('120','ADMIN','Update','2022-12-09 17:14:11','User ADMIN updated system parameter.'),('TL-1','ADMIN','Log In','2022-12-12 12:02:27','User ADMIN logged in.'),('138','ADMIN','Insert','2022-12-12 12:02:53','User ADMIN inserted interface setting.'),('138','ADMIN','Activate','2022-12-12 12:03:14','User ADMIN updated interface setting status.'),('138','ADMIN','Deactivate','2022-12-12 12:03:14','User ADMIN updated interface setting status.'),('138','ADMIN','Update','2022-12-12 12:47:13','User ADMIN updated interface setting.'),('139','ADMIN','Insert','2022-12-12 12:48:32','User ADMIN inserted page.'),('140','ADMIN','Insert','2022-12-12 12:48:47','User ADMIN inserted page.'),('141','ADMIN','Insert','2022-12-12 12:55:18','User ADMIN inserted action.'),('142','ADMIN','Insert','2022-12-12 12:55:29','User ADMIN inserted action.'),('143','ADMIN','Insert','2022-12-12 12:55:41','User ADMIN inserted action.'),('144','ADMIN','Insert','2022-12-12 12:56:09','User ADMIN inserted action.'),('145','ADMIN','Insert','2022-12-12 12:56:22','User ADMIN inserted action.'),('146','ADMIN','Insert','2022-12-12 13:56:26','User ADMIN inserted system code.'),('147','ADMIN','Insert','2022-12-12 13:56:37','User ADMIN inserted system code.'),('148','ADMIN','Insert','2022-12-12 13:56:45','User ADMIN inserted system code.'),('149','ADMIN','Insert','2022-12-12 13:56:54','User ADMIN inserted system code.'),('150','ADMIN','Insert','2022-12-12 13:57:10','User ADMIN inserted system code.'),('151','ADMIN','Insert','2022-12-12 15:06:17','User ADMIN inserted system parameter.'),('152','ADMIN','Insert','2022-12-12 16:19:14','User ADMIN inserted email setting.'),('153','ADMIN','Insert','2022-12-12 16:20:26','User ADMIN inserted email setting.'),('153','ADMIN','Activate','2022-12-12 16:20:30','User ADMIN updated email setting status.'),('153','ADMIN','Deactivate','2022-12-12 16:20:30','User ADMIN updated email setting status.'),('154','ADMIN','Insert','2022-12-12 16:21:45','User ADMIN inserted email setting.'),('155','ADMIN','Insert','2022-12-12 17:02:01','User ADMIN inserted email setting.'),('156','ADMIN','Insert','2022-12-12 17:10:48','User ADMIN inserted email setting.'),('157','ADMIN','Insert','2022-12-12 17:11:40','User ADMIN inserted email setting.'),('158','ADMIN','Insert','2022-12-12 17:24:52','User ADMIN inserted system parameter.'),('159','ADMIN','Insert','2022-12-12 17:25:24','User ADMIN inserted page.'),('160','ADMIN','Insert','2022-12-12 17:25:42','User ADMIN inserted page.'),('161','ADMIN','Insert','2022-12-12 17:26:27','User ADMIN inserted action.'),('162','ADMIN','Insert','2022-12-12 17:26:45','User ADMIN inserted action.'),('163','ADMIN','Insert','2022-12-12 17:27:06','User ADMIN inserted action.'),('164','ADMIN','Insert','2022-12-12 17:27:35','User ADMIN inserted action.'),('165','ADMIN','Insert','2022-12-12 17:27:56','User ADMIN inserted action.'),('166','ADMIN','Insert','2022-12-12 17:28:15','User ADMIN inserted action.'),('167','ADMIN','Insert','2022-12-12 17:28:32','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-13 08:34:47','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-12-14 09:06:08','User ADMIN logged in.'),('168','ADMIN','Insert','2022-12-14 12:55:28','User ADMIN inserted action.'),('169','ADMIN','Insert','2022-12-14 12:55:41','User ADMIN inserted action.'),('170','ADMIN','Insert','2022-12-14 13:03:03','User ADMIN inserted system code.'),('171','ADMIN','Insert','2022-12-14 13:03:18','User ADMIN inserted system code.'),('172','ADMIN','Insert','2022-12-14 13:03:34','User ADMIN inserted system code.'),('173','ADMIN','Insert','2022-12-14 16:57:54','User ADMIN inserted notification setting.'),('174','ADMIN','Insert','2022-12-14 16:58:00','User ADMIN inserted notification setting.'),('175','ADMIN','Insert','2022-12-14 16:58:19','User ADMIN inserted notification setting.'),('175','ADMIN','Update','2022-12-14 17:02:03','User ADMIN updated notification setting.'),('TL-1','ADMIN','Log In','2022-12-15 08:40:50','User ADMIN logged in.'),('176','ADMIN','Insert','2022-12-15 13:46:51','User ADMIN inserted system parameter.'),('177','ADMIN','Insert','2022-12-15 13:47:01','User ADMIN inserted system parameter.'),('178','ADMIN','Insert','2022-12-15 13:47:18','User ADMIN inserted page.'),('179','ADMIN','Insert','2022-12-15 13:48:05','User ADMIN inserted page.'),('180','ADMIN','Insert','2022-12-15 13:48:26','User ADMIN inserted page.'),('181','ADMIN','Insert','2022-12-15 13:48:40','User ADMIN inserted page.'),('182','ADMIN','Insert','2022-12-15 13:52:37','User ADMIN inserted action.'),('183','ADMIN','Insert','2022-12-15 13:52:49','User ADMIN inserted action.'),('184','ADMIN','Insert','2022-12-15 13:52:58','User ADMIN inserted action.'),('185','ADMIN','Insert','2022-12-15 13:53:08','User ADMIN inserted action.'),('186','ADMIN','Insert','2022-12-15 13:53:17','User ADMIN inserted action.'),('187','ADMIN','Insert','2022-12-15 13:53:27','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-15 14:41:56','User ADMIN logged in.'),('188','ADMIN','Insert','2022-12-15 15:38:23','User ADMIN inserted country.'),('189','ADMIN','Insert','2022-12-15 15:44:07','User ADMIN inserted state.'),('190','ADMIN','Insert','2022-12-15 15:45:30','User ADMIN inserted state.'),('191','ADMIN','Insert','2022-12-15 15:46:18','User ADMIN inserted state.'),('192','ADMIN','Insert','2022-12-15 15:46:39','User ADMIN inserted state.'),('193','ADMIN','Insert','2022-12-15 15:47:58','User ADMIN inserted country.'),('194','ADMIN','Insert','2022-12-15 15:48:06','User ADMIN inserted state.'),('195','ADMIN','Insert','2022-12-15 15:49:08','User ADMIN inserted state.'),('196','ADMIN','Insert','2022-12-15 16:02:51','User ADMIN inserted country.'),('197','ADMIN','Insert','2022-12-15 16:07:37','User ADMIN inserted page.'),('198','ADMIN','Insert','2022-12-15 16:08:21','User ADMIN inserted page.'),('199','ADMIN','Insert','2022-12-15 16:17:49','User ADMIN inserted action.'),('197','ADMIN','Update','2022-12-15 16:20:20','User ADMIN updated page.'),('198','ADMIN','Update','2022-12-15 16:20:30','User ADMIN updated page.'),('200','ADMIN','Insert','2022-12-15 16:20:41','User ADMIN inserted action.'),('201','ADMIN','Insert','2022-12-15 16:20:54','User ADMIN inserted action.'),('202','ADMIN','Insert','2022-12-15 16:21:38','User ADMIN inserted action.'),('203','ADMIN','Insert','2022-12-15 16:21:51','User ADMIN inserted action.'),('TL-1','ADMIN','Log Out','2022-12-16 08:56:56','User ADMIN logged out.'),('TL-1','ADMIN','Log In','2022-12-16 08:57:10','User ADMIN logged in.'),('TL-3','ADMIN','Update','2022-12-16 08:58:15','User ADMIN updated module.'),('TL-1','ADMIN','Attempt Log In','2022-12-16 09:46:01','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-12-16 09:46:04','User ADMIN logged in.'),('204','ADMIN','Insert','2022-12-16 11:10:55','User ADMIN inserted system parameter.'),('205','ADMIN','Insert','2022-12-16 11:42:33','User ADMIN inserted zoom API.'),('206','ADMIN','Insert','2022-12-16 11:42:37','User ADMIN inserted zoom API.'),('207','ADMIN','Insert','2022-12-16 11:43:06','User ADMIN inserted zoom API.'),('207','ADMIN','Activate','2022-12-16 11:46:15','User ADMIN updated zoom API status.'),('208','ADMIN','Insert','2022-12-16 11:48:24','User ADMIN inserted zoom API.'),('208','ADMIN','Activate','2022-12-16 14:05:58','User ADMIN updated zoom API status.'),('209','ADMIN','Insert','2022-12-16 14:16:48','User ADMIN inserted email setting.'),('209','ADMIN','Activate','2022-12-16 14:17:08','User ADMIN updated email setting status.'),('138','ADMIN','Deactivate','2022-12-16 14:24:08','User ADMIN updated interface setting status.'),('138','ADMIN','Activate','2022-12-16 14:24:11','User ADMIN updated interface setting status.'),('209','ADMIN','Deactivate','2022-12-16 14:34:10','User ADMIN updated email setting status.'),('209','ADMIN','Activate','2022-12-16 14:34:14','User ADMIN updated email setting status.'),('210','ADMIN','Insert','2022-12-16 15:03:47','User ADMIN inserted page.'),('210','ADMIN','Update','2022-12-16 15:04:10','User ADMIN updated page.'),('211','ADMIN','Insert','2022-12-16 15:04:19','User ADMIN inserted page.'),('212','ADMIN','Insert','2022-12-16 15:15:40','User ADMIN inserted notification setting.'),('213','ADMIN','Insert','2022-12-16 15:36:46','User ADMIN inserted action.'),('214','ADMIN','Insert','2022-12-16 15:37:00','User ADMIN inserted action.'),('215','ADMIN','Insert','2022-12-16 15:37:13','User ADMIN inserted action.'),('215','ADMIN','Update','2022-12-16 15:37:17','User ADMIN updated action.'),('216','ADMIN','Insert','2022-12-16 15:37:34','User ADMIN inserted action.'),('217','ADMIN','Insert','2022-12-16 15:37:47','User ADMIN inserted action.'),('218','ADMIN','Insert','2022-12-16 15:37:59','User ADMIN inserted action.'),('219','ADMIN','Insert','2022-12-16 15:38:19','User ADMIN inserted action.'),('210','ADMIN','Update','2022-12-16 15:43:20','User ADMIN updated page.'),('220','ADMIN','Insert','2022-12-16 16:09:00','User ADMIN inserted country.'),('221','ADMIN','Insert','2022-12-16 16:09:07','User ADMIN inserted state.'),('222','ADMIN','Insert','2022-12-16 16:10:06','User ADMIN inserted country.'),('223','ADMIN','Insert','2022-12-16 16:10:50','User ADMIN inserted country.'),('TL-1','ADMIN','Log In','2022-12-19 13:58:50','User ADMIN logged in.'),('224','ADMIN','Insert','2022-12-19 14:03:33','User ADMIN inserted action.'),('225','ADMIN','Insert','2022-12-19 14:03:50','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-20 10:03:34','User ADMIN logged in.'),('226','ADMIN','Insert','2022-12-20 15:28:46','User ADMIN inserted user account.'),('226','ADMIN','Deactivated','2022-12-20 15:34:47','User ADMIN deactivated user account.'),('226','ADMIN','Lock','2022-12-20 15:34:51','User ADMIN locked user account.'),('226','ADMIN','Unlock','2022-12-20 15:34:54','User ADMIN unlocked user account.'),('226','ADMIN','Activate','2022-12-20 15:35:56','User ADMIN activated user account.'),('226','ADMIN','Deactivated','2022-12-20 15:35:59','User ADMIN deactivated user account.'),('226','ADMIN','Lock','2022-12-20 15:36:02','User ADMIN locked user account.'),('227','ADMIN','Insert','2022-12-20 15:36:37','User ADMIN inserted user account.'),('227','ADMIN','Update','2022-12-20 15:39:51','User ADMIN updated user account.'),('227','ADMIN','Update','2022-12-20 15:39:55','User ADMIN updated user account.'),('227','ADMIN','Activate','2022-12-20 15:43:07','User ADMIN activated user account.'),('227','ADMIN','Lock','2022-12-20 15:43:10','User ADMIN locked user account.'),('227','ADMIN','Deactivated','2022-12-20 15:45:02','User ADMIN deactivated user account.'),('TL-1','ADMIN','Update','2022-12-20 15:54:11','User ADMIN updated user account.'),('228','ADMIN','Insert','2022-12-20 16:02:49','User ADMIN inserted user account.'),('229','ADMIN','Insert','2022-12-20 16:20:09','User ADMIN inserted user account.'),('229','ADMIN','Activate','2022-12-20 16:20:29','User ADMIN activated user account.'),('229','LDAGULTO','Log In','2022-12-20 16:20:35','User LDAGULTO logged in.'),('229','ADMIN','Deactivated','2022-12-20 16:20:49','User ADMIN deactivated user account.'),('229','LDAGULTO','Log Out','2022-12-20 16:20:52','User LDAGULTO logged out.'),('TL-1','ADMIN','Attempt Log In','2022-12-21 11:36:05','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-12-21 11:36:07','User ADMIN logged in.'),('229','ADMIN','Activate','2022-12-21 11:36:29','User ADMIN activated user account.'),('229','LDAGULTO','Log In','2022-12-21 11:36:37','User LDAGULTO logged in.'),('229','ADMIN','Deactivated','2022-12-21 11:36:52','User ADMIN deactivated user account.'),('229','LDAGULTO','Log Out','2022-12-21 11:36:58','User LDAGULTO logged out.'),('229','ADMIN','Activate','2022-12-21 11:37:07','User ADMIN activated user account.'),('TL-1','ADMIN','Update','2022-12-21 11:43:17','User ADMIN updated user account.'),('TL-3','ADMIN','Update','2022-12-21 14:04:29','User ADMIN updated module.'),('230','ADMIN','Insert','2022-12-21 14:27:06','User ADMIN inserted module.'),('231','ADMIN','Insert','2022-12-21 14:27:52','User ADMIN inserted system code.'),('230','ADMIN','Update','2022-12-21 14:28:03','User ADMIN updated module.'),('232','ADMIN','Insert','2022-12-21 14:31:24','User ADMIN inserted module.'),('233','ADMIN','Insert','2022-12-21 14:32:27','User ADMIN inserted module.'),('234','ADMIN','Insert','2022-12-21 14:36:46','User ADMIN inserted module.'),('234','ADMIN','Update','2022-12-21 14:41:28','User ADMIN updated module.'),('235','ADMIN','Insert','2022-12-21 15:11:23','User ADMIN inserted page.'),('236','ADMIN','Insert','2022-12-21 15:13:34','User ADMIN inserted page.'),('237','ADMIN','Insert','2022-12-21 15:13:49','User ADMIN inserted action.'),('238','ADMIN','Insert','2022-12-21 15:14:24','User ADMIN inserted action.'),('239','ADMIN','Insert','2022-12-21 15:14:34','User ADMIN inserted action.'),('240','ADMIN','Insert','2022-12-21 15:14:44','User ADMIN inserted action.'),('241','ADMIN','Insert','2022-12-21 15:15:00','User ADMIN inserted action.'),('242','ADMIN','Insert','2022-12-21 15:20:57','User ADMIN inserted system parameter.'),('229','LDAGULTO','Log In','2022-12-23 09:47:03','User LDAGULTO logged in.'),('229','LDAGULTO','Log Out','2022-12-23 09:47:12','User LDAGULTO logged out.'),('TL-1','ADMIN','Log In','2022-12-23 09:47:16','User ADMIN logged in.'),('235','ADMIN','Update','2022-12-23 10:20:49','User ADMIN updated page.'),('236','ADMIN','Update','2022-12-23 10:21:00','User ADMIN updated page.'),('236','ADMIN','Update','2022-12-23 10:25:18','User ADMIN updated page.'),('235','ADMIN','Update','2022-12-23 10:25:34','User ADMIN updated page.'),('237','ADMIN','Update','2022-12-23 10:28:08','User ADMIN updated action.'),('238','ADMIN','Update','2022-12-23 10:28:11','User ADMIN updated action.'),('239','ADMIN','Update','2022-12-23 10:28:15','User ADMIN updated action.'),('240','ADMIN','Update','2022-12-23 10:28:23','User ADMIN updated action.'),('241','ADMIN','Update','2022-12-23 10:28:28','User ADMIN updated action.'),('242','ADMIN','Update','2022-12-23 11:57:42','User ADMIN updated system parameter.'),('243','ADMIN','Insert','2022-12-23 15:26:50','User ADMIN inserted department.'),('244','ADMIN','Insert','2022-12-23 15:27:15','User ADMIN inserted department.'),('244','ADMIN','Archive','2022-12-23 16:12:37','User ADMIN updated department status.'),('244','ADMIN','Unarchive','2022-12-23 16:12:46','User ADMIN updated department status.'),('243','ADMIN','Archive','2022-12-23 16:16:10','User ADMIN updated department status.'),('243','ADMIN','Unarchive','2022-12-23 16:16:13','User ADMIN updated department status.'),('245','ADMIN','Insert','2022-12-23 16:16:29','User ADMIN inserted department.'),('246','ADMIN','Insert','2022-12-23 16:32:50','User ADMIN inserted page.'),('247','ADMIN','Insert','2022-12-23 16:33:22','User ADMIN inserted page.'),('248','ADMIN','Insert','2022-12-23 16:35:00','User ADMIN inserted action.'),('249','ADMIN','Insert','2022-12-23 16:35:10','User ADMIN inserted action.'),('250','ADMIN','Insert','2022-12-23 16:35:21','User ADMIN inserted action.'),('251','ADMIN','Insert','2022-12-23 16:36:26','User ADMIN inserted action.'),('251','ADMIN','Update','2022-12-23 16:36:29','User ADMIN updated action.'),('252','ADMIN','Insert','2022-12-23 16:36:47','User ADMIN inserted action.'),('253','ADMIN','Insert','2022-12-23 16:42:20','User ADMIN inserted upload setting.'),('230','ADMIN','Update','2022-12-23 16:48:53','User ADMIN updated module.'),('254','ADMIN','Insert','2022-12-23 17:15:09','User ADMIN inserted action.'),('255','ADMIN','Insert','2022-12-23 17:15:25','User ADMIN inserted action.'),('255','ADMIN','Update','2022-12-23 17:15:30','User ADMIN updated action.'),('256','ADMIN','Insert','2022-12-23 17:16:00','User ADMIN inserted action.'),('257','ADMIN','Insert','2022-12-23 17:16:14','User ADMIN inserted action.'),('258','ADMIN','Insert','2022-12-23 17:16:34','User ADMIN inserted action.'),('259','ADMIN','Insert','2022-12-23 17:16:45','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-24 08:04:50','User ADMIN logged in.'),('260','ADMIN','Insert','2022-12-24 08:05:36','User ADMIN inserted action.'),('260','ADMIN','Update','2022-12-24 08:05:38','User ADMIN updated action.'),('261','ADMIN','Insert','2022-12-24 08:05:54','User ADMIN inserted action.'),('262','ADMIN','Insert','2022-12-24 08:06:08','User ADMIN inserted action.'),('260','ADMIN','Update','2022-12-24 08:09:41','User ADMIN updated action.'),('261','ADMIN','Update','2022-12-24 08:09:56','User ADMIN updated action.'),('262','ADMIN','Update','2022-12-24 08:10:09','User ADMIN updated action.'),('263','ADMIN','Insert','2022-12-24 08:46:45','User ADMIN inserted system parameter.'),('242','ADMIN','Update','2022-12-24 08:47:51','User ADMIN updated system parameter.'),('264','ADMIN','Insert','2022-12-24 09:05:22','User ADMIN inserted department.'),('255','ADMIN','Update','2022-12-24 09:32:35','User ADMIN updated action.'),('256','ADMIN','Update','2022-12-24 09:32:50','User ADMIN updated action.'),('257','ADMIN','Update','2022-12-24 09:33:17','User ADMIN updated action.'),('258','ADMIN','Update','2022-12-24 09:34:02','User ADMIN updated action.'),('259','ADMIN','Update','2022-12-24 09:34:16','User ADMIN updated action.'),('259','ADMIN','Update','2022-12-24 09:34:21','User ADMIN updated action.'),('260','ADMIN','Update','2022-12-24 09:34:39','User ADMIN updated action.'),('261','ADMIN','Update','2022-12-24 09:34:53','User ADMIN updated action.'),('262','ADMIN','Update','2022-12-24 09:35:10','User ADMIN updated action.'),('265','ADMIN','Update','2022-12-24 10:20:17','User ADMIN updated upload setting.'),('266','ADMIN','Update','2022-12-24 10:20:23','User ADMIN updated upload setting.'),('267','ADMIN','Update','2022-12-24 10:20:28','User ADMIN updated upload setting.'),('267','ADMIN','Update','2022-12-24 10:20:48','User ADMIN updated upload setting.'),('268','ADMIN','Insert','2022-12-24 10:50:02','User ADMIN inserted action.'),('269','ADMIN','Insert','2022-12-24 10:50:18','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-24 17:08:42','User ADMIN logged in.'),('270','ADMIN','Insert','2022-12-24 17:27:24','User ADMIN inserted system parameter.'),('270','ADMIN','Update','2022-12-24 17:44:11','User ADMIN updated system parameter.'),('271','ADMIN','Insert','2022-12-24 17:44:34','User ADMIN inserted system parameter.'),('272','ADMIN','Insert','2022-12-24 17:45:01','User ADMIN inserted system parameter.'),('273','ADMIN','Insert','2022-12-24 17:45:19','User ADMIN inserted system parameter.'),('TL-1','ADMIN','Log In','2022-12-26 09:18:14','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-12-26 12:33:41','User ADMIN logged in.'),('269','ADMIN','Update','2022-12-26 12:33:51','User ADMIN updated action.'),('274','ADMIN','Insert','2022-12-26 12:34:01','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-27 09:11:10','User ADMIN logged in.'),('275','ADMIN','Insert','2022-12-27 11:06:15','User ADMIN inserted job position.'),('275','ADMIN','Start','2022-12-27 11:11:52','User ADMIN updated job position recruitment status.'),('276','ADMIN','Insert','2022-12-27 11:12:00','User ADMIN inserted job position.'),('277','ADMIN','Insert','2022-12-27 11:13:18','User ADMIN inserted job position.'),('278','ADMIN','Insert','2022-12-27 11:14:06','User ADMIN inserted job position.'),('275','ADMIN','Update','2022-12-27 11:14:42','User ADMIN updated job position.'),('275','ADMIN','Update','2022-12-27 11:14:43','User ADMIN updated job position.'),('275','ADMIN','Update','2022-12-27 11:14:44','User ADMIN updated job position.'),('275','ADMIN','Update','2022-12-27 11:14:44','User ADMIN updated job position.'),('275','ADMIN','Update','2022-12-27 11:14:44','User ADMIN updated job position.'),('275','ADMIN','Update','2022-12-27 11:14:44','User ADMIN updated job position.'),('275','ADMIN','Update','2022-12-27 11:14:45','User ADMIN updated job position.'),('275','ADMIN','Update','2022-12-27 11:15:03','User ADMIN updated job position.'),('275','ADMIN','Update','2022-12-27 11:15:10','User ADMIN updated job position.'),('275','ADMIN','Stop','2022-12-27 11:16:09','User ADMIN updated job position recruitment status.'),('275','ADMIN','Start','2022-12-27 11:16:28','User ADMIN updated job position recruitment status.'),('275','ADMIN','Update','2022-12-27 11:16:32','User ADMIN updated job position.'),('279','ADMIN','Insert','2022-12-27 11:44:51','User ADMIN inserted job position attachment.'),('280','ADMIN','Insert','2022-12-27 11:45:53','User ADMIN inserted job position attachment.'),('280','ADMIN','Update','2022-12-27 11:45:53','User ADMIN updated job position attachment.'),('281','ADMIN','Insert','2022-12-27 11:46:34','User ADMIN inserted job position attachment.'),('281','ADMIN','Update','2022-12-27 11:46:34','User ADMIN updated job position attachment.'),('279','ADMIN','Update','2022-12-27 11:50:17','User ADMIN updated job position attachment.'),('279','ADMIN','Update','2022-12-27 11:52:02','User ADMIN updated job position attachment.'),('279','ADMIN','Update','2022-12-27 11:52:02','User ADMIN updated job position attachment.'),('279','ADMIN','Update','2022-12-27 11:52:15','User ADMIN updated job position attachment.'),('279','ADMIN','Update','2022-12-27 11:52:15','User ADMIN updated job position attachment.'),('276','ADMIN','Update','2022-12-27 11:58:20','User ADMIN updated job position.'),('282','ADMIN','Insert','2022-12-27 11:58:28','User ADMIN inserted job position attachment.'),('282','ADMIN','Update','2022-12-27 11:58:28','User ADMIN updated job position attachment.'),('276','ADMIN','Start','2022-12-27 12:01:11','User ADMIN updated job position recruitment status.'),('276','ADMIN','Stop','2022-12-27 12:01:16','User ADMIN updated job position recruitment status.'),('276','ADMIN','Start','2022-12-27 12:01:25','User ADMIN updated job position recruitment status.'),('276','ADMIN','Stop','2022-12-27 12:58:23','User ADMIN updated job position recruitment status.'),('276','ADMIN','Start','2022-12-27 12:58:28','User ADMIN updated job position recruitment status.'),('276','ADMIN','Update','2022-12-27 13:02:10','User ADMIN updated job position.'),('283','ADMIN','Insert','2022-12-27 13:10:52','User ADMIN inserted job position attachment.'),('283','ADMIN','Update','2022-12-27 13:10:52','User ADMIN updated job position attachment.'),('280','ADMIN','Update','2022-12-27 13:10:57','User ADMIN updated job position attachment.'),('284','ADMIN','Insert','2022-12-27 13:26:09','User ADMIN inserted job position responsibility.'),('276','ADMIN','Update','2022-12-27 13:26:42','User ADMIN updated job position.'),('285','ADMIN','Insert','2022-12-27 13:26:48','User ADMIN inserted job position responsibility.'),('285','ADMIN','Update','2022-12-27 13:31:01','User ADMIN updated job position responsibility.'),('286','ADMIN','Insert','2022-12-27 13:31:19','User ADMIN inserted job position requirement.'),('287','ADMIN','Update','2022-12-27 13:31:21','User ADMIN updated job position requirement.'),('288','ADMIN','Insert','2022-12-27 13:31:29','User ADMIN inserted job position qualification.'),('289','ADMIN','Update','2022-12-27 13:32:48','User ADMIN updated job position qualification.'),('290','ADMIN','Insert','2022-12-27 13:37:37','User ADMIN inserted job position.'),('TL-16','ADMIN','Update','2022-12-27 13:42:10','User ADMIN updated system parameter.'),('TL-291','ADMIN','Insert','2022-12-27 13:53:17','User ADMIN inserted state.'),('TL-292','ADMIN','Insert','2022-12-27 13:53:38','User ADMIN inserted state.'),('TL-293','ADMIN','Insert','2022-12-27 13:55:35','User ADMIN inserted state.'),('TL-292','ADMIN','Update','2022-12-27 13:56:02','User ADMIN updated state.'),('TL-291','ADMIN','Update','2022-12-27 13:56:05','User ADMIN updated state.'),('TL-294','ADMIN','Insert','2022-12-27 13:56:09','User ADMIN inserted state.'),('TL-295','ADMIN','Insert','2022-12-27 13:56:16','User ADMIN inserted state.'),('TL-296','ADMIN','Insert','2022-12-27 13:57:21','User ADMIN inserted state.'),('TL-297','ADMIN','Insert','2022-12-27 13:57:40','User ADMIN inserted job position.'),('TL-297','ADMIN','Update','2022-12-27 16:53:20','User ADMIN updated job position.'),('TL-1','ADMIN','Log In','2022-12-28 11:28:27','User ADMIN logged in.'),('TL-7','ADMIN','Update','2022-12-28 11:53:55','User ADMIN updated action.'),('TL-7','ADMIN','Update','2022-12-28 11:56:30','User ADMIN updated action.'),('TL-298','ADMIN','Insert','2022-12-28 14:05:54','User ADMIN inserted state.'),('TL-299','ADMIN','Insert','2022-12-28 14:47:00','User ADMIN inserted page.'),('TL-300','ADMIN','Insert','2022-12-28 14:47:17','User ADMIN inserted page.'),('TL-301','ADMIN','Insert','2022-12-28 14:50:18','User ADMIN inserted action.'),('TL-302','ADMIN','Insert','2022-12-28 14:50:30','User ADMIN inserted action.'),('TL-303','ADMIN','Insert','2022-12-28 14:50:50','User ADMIN inserted action.'),('TL-303','ADMIN','Update','2022-12-28 14:50:59','User ADMIN updated action.'),('TL-303','ADMIN','Update','2022-12-28 14:51:03','User ADMIN updated action.'),('TL-304','ADMIN','Insert','2022-12-28 14:51:10','User ADMIN inserted action.'),('TL-305','ADMIN','Insert','2022-12-28 14:51:25','User ADMIN inserted action.'),('TL-299','ADMIN','Update','2022-12-28 14:55:51','User ADMIN updated page.'),('229','LDAGULTO','Attempt Log In','2022-12-29 11:58:44','User LDAGULTO attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-12-29 11:58:49','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-12-29 11:58:54','User ADMIN logged in.'),('TL-306','ADMIN','Insert','2022-12-29 12:42:19','User ADMIN inserted system parameter.'),('TL-307','ADMIN','Insert','2022-12-29 13:45:57','User ADMIN inserted work location.'),('TL-307','ADMIN','Archive','2022-12-29 13:46:04','User ADMIN updated work location status.'),('TL-308','ADMIN','Insert','2022-12-29 13:46:16','User ADMIN inserted work location.'),('TL-308','ADMIN','Archive','2022-12-29 13:46:56','User ADMIN updated work location status.'),('TL-308','ADMIN','Unarchive','2022-12-29 13:47:01','User ADMIN updated work location status.'),('TL-309','ADMIN','Insert','2022-12-29 13:47:10','User ADMIN inserted work location.'),('TL-1','ADMIN','Log In','2022-12-31 11:25:59','User ADMIN logged in.'),('TL-10','ADMIN','Update','2022-12-31 12:41:25','User ADMIN updated page.'),('208','ADMIN','Update','2022-12-31 12:56:50','User ADMIN updated zoom API.'),('208','ADMIN','Update','2022-12-31 12:57:02','User ADMIN updated zoom API.'),('TL-310','ADMIN','Insert','2022-12-31 12:58:12','User ADMIN inserted system parameter.'),('TL-311','ADMIN','Insert','2022-12-31 12:58:28','User ADMIN inserted page.'),('TL-312','ADMIN','Insert','2022-12-31 12:58:49','User ADMIN inserted page.'),('TL-313','ADMIN','Insert','2022-12-31 12:59:07','User ADMIN inserted action.'),('TL-314','ADMIN','Insert','2022-12-31 12:59:20','User ADMIN inserted action.'),('TL-315','ADMIN','Insert','2022-12-31 12:59:30','User ADMIN inserted action.'),('TL-316','ADMIN','Insert','2022-12-31 13:54:45','User ADMIN inserted departure reason.'),('TL-317','ADMIN','Insert','2022-12-31 13:57:48','User ADMIN inserted departure reason.'),('TL-316','ADMIN','Update','2022-12-31 14:00:34','User ADMIN updated departure reason.'),('TL-316','ADMIN','Update','2022-12-31 14:00:41','User ADMIN updated departure reason.'),('TL-318','ADMIN','Insert','2022-12-31 14:03:02','User ADMIN inserted page.'),('TL-319','ADMIN','Insert','2022-12-31 14:03:22','User ADMIN inserted page.'),('TL-320','ADMIN','Insert','2022-12-31 14:03:47','User ADMIN inserted action.'),('TL-321','ADMIN','Insert','2022-12-31 14:03:59','User ADMIN inserted action.'),('TL-322','ADMIN','Insert','2022-12-31 14:04:13','User ADMIN inserted action.'),('TL-323','ADMIN','Insert','2022-12-31 14:04:32','User ADMIN inserted system parameter.'),('TL-324','ADMIN','Insert','2022-12-31 14:15:44','User ADMIN inserted employee type.'),('TL-325','ADMIN','Insert','2022-12-31 14:15:52','User ADMIN inserted employee type.'),('TL-326','ADMIN','Insert','2022-12-31 14:20:50','User ADMIN inserted employee type.'),('TL-1','ADMIN','Log In','2022-12-31 16:22:13','User ADMIN logged in.'),('TL-327','ADMIN','Insert','2022-12-31 16:22:42','User ADMIN inserted employee type.'),('TL-328','ADMIN','Insert','2022-12-31 16:22:51','User ADMIN inserted employee type.'),('TL-328','ADMIN','Update','2022-12-31 16:22:54','User ADMIN updated employee type.'),('TL-1','ADMIN','Log In','2023-01-02 10:19:33','User ADMIN logged in.'),('TL-329','ADMIN','Insert','2023-01-02 10:21:08','User ADMIN inserted page.'),('TL-329','ADMIN','Update','2023-01-02 10:21:11','User ADMIN updated page.'),('TL-330','ADMIN','Insert','2023-01-02 10:21:28','User ADMIN inserted page.'),('TL-331','ADMIN','Insert','2023-01-02 10:26:24','User ADMIN inserted system parameter.'),('TL-1','ADMIN','Attempt Log In','2023-01-02 11:08:58','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2023-01-02 11:09:02','User ADMIN logged in.'),('TL-332','ADMIN','Insert','2023-01-02 11:10:37','User ADMIN inserted action.'),('TL-333','ADMIN','Insert','2023-01-02 11:10:47','User ADMIN inserted action.'),('TL-334','ADMIN','Insert','2023-01-02 11:10:59','User ADMIN inserted action.'),('TL-333','ADMIN','Update','2023-01-02 11:11:12','User ADMIN updated action.'),('TL-335','ADMIN','Insert','2023-01-02 11:13:25','User ADMIN inserted wage type.'),('TL-336','ADMIN','Insert','2023-01-02 11:13:33','User ADMIN inserted wage type.'),('TL-335','ADMIN','Update','2023-01-02 11:13:59','User ADMIN updated wage type.'),('TL-337','ADMIN','Insert','2023-01-02 11:18:46','User ADMIN inserted wage type.'),('TL-337','ADMIN','Update','2023-01-02 11:18:53','User ADMIN updated wage type.'),('TL-338','ADMIN','Insert','2023-01-02 11:22:09','User ADMIN inserted system code.'),('TL-339','ADMIN','Update','2023-01-02 11:22:32','User ADMIN updated system code.'),('TL-340','ADMIN','Update','2023-01-02 11:22:35','User ADMIN updated system code.'),('TL-341','ADMIN','Update','2023-01-02 11:22:40','User ADMIN updated system code.'),('TL-341','ADMIN','Update','2023-01-02 11:22:53','User ADMIN updated system code.'),('TL-342','ADMIN','Insert','2023-01-02 11:23:13','User ADMIN inserted system code.'),('TL-343','ADMIN','Insert','2023-01-02 11:24:40','User ADMIN inserted system code.'),('TL-344','ADMIN','Insert','2023-01-02 11:25:03','User ADMIN inserted system code.'),('TL-345','ADMIN','Insert','2023-01-02 11:25:21','User ADMIN inserted system code.'),('TL-346','ADMIN','Insert','2023-01-02 11:25:34','User ADMIN inserted system code.'),('TL-347','ADMIN','Insert','2023-01-02 11:25:51','User ADMIN inserted system code.'),('TL-348','ADMIN','Insert','2023-01-02 11:26:17','User ADMIN inserted system code.');
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
INSERT INTO `global_upload_file_type` VALUES (1,'jpeg'),(1,'svg'),(1,'png'),(1,'jpg'),(2,'jpeg'),(2,'jpg'),(2,'png'),(3,'jpeg'),(3,'jpg'),(3,'png'),(4,'jpeg'),(4,'jpg'),(4,'png'),(4,'svg'),(3,'svg'),(2,'svg'),(5,'jpeg'),(5,'jpg'),(5,'png'),(5,'svg'),(6,'ico'),(6,'jpeg'),(6,'jpg'),(6,'png'),(6,'svg'),(7,'pdf');
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
INSERT INTO `global_upload_setting` VALUES (1,'Module Icon','Upload setting for module icon.',5,'TL-14',NULL),(2,'Company Logo','Upload setting for company logo.',5,'111','INS->ADMIN->2022-12-07 04:53:02'),(3,'Login Background','Upload setting for login background.',5,'128','INS->ADMIN->2022-12-09 10:16:09'),(4,'Login Logo','Upload setting for login logo.',5,'129','INS->ADMIN->2022-12-09 10:16:41'),(5,'Menu Logo','Upload setting for menu logo.',5,'130','INS->ADMIN->2022-12-09 10:18:34'),(6,'Favicon','Upload setting for favicon.',5,'131','INS->ADMIN->2022-12-09 10:19:11'),(7,'Job Position Attachment','Upload setting for job position attachment.',5,'267','UPD->ADMIN->2022-12-24 10:20:48');
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
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`USERNAME`),
  KEY `global_user_account_index` (`USERNAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_user_account`
--

LOCK TABLES `global_user_account` WRITE;
/*!40000 ALTER TABLE `global_user_account` DISABLE KEYS */;
INSERT INTO `global_user_account` VALUES ('ADMIN','68aff5412f35ed76','Administrator','Active','2023-06-21',0,'2023-01-02 11:08:58','2023-01-02 11:09:02','TL-1','UPD->ADMIN->2022-12-21 11:43:17'),('LDAGULTO','68aff5412f35ed76','Lawrence','Active','2023-06-20',1,'2022-12-29 11:58:44','2022-12-23 09:47:03','229','ACT->ADMIN->2022-12-21 11:37:07');
/*!40000 ALTER TABLE `global_user_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_zoom_api`
--

DROP TABLE IF EXISTS `global_zoom_api`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_zoom_api` (
  `ZOOM_API_ID` int(50) NOT NULL,
  `ZOOM_API_NAME` varchar(100) NOT NULL,
  `DESCRIPTION` varchar(200) NOT NULL,
  `API_KEY` varchar(1000) NOT NULL,
  `API_SECRET` varchar(1000) NOT NULL,
  `STATUS` tinyint(1) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ZOOM_API_ID`),
  KEY `global_zoom_api_index` (`ZOOM_API_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_zoom_api`
--

LOCK TABLES `global_zoom_api` WRITE;
/*!40000 ALTER TABLE `global_zoom_api` DISABLE KEYS */;
INSERT INTO `global_zoom_api` VALUES (4,'test','test','test','test',1,'208','UPD->ADMIN->2022-12-31 12:57:02');
/*!40000 ALTER TABLE `global_zoom_api` ENABLE KEYS */;
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
INSERT INTO `technical_action` VALUES ('1','Add Module','TL-7','UPD->ADMIN->2022-12-28 11:56:30'),('10','Delete Page Access Right','TL-24',NULL),('100','Add Job Position Attachment','268','INS->ADMIN->2022-12-24 10:50:02'),('101','Update Job Position Attachment','269','UPD->ADMIN->2022-12-26 12:33:51'),('102','Delete Job Position Attachment','274','INS->ADMIN->2022-12-26 12:34:01'),('103','Add Work Location','TL-301','INS->ADMIN->2022-12-28 02:50:18'),('104','Update Work Location','TL-302','INS->ADMIN->2022-12-28 02:50:30'),('105','Delete Work Location','TL-303','UPD->ADMIN->2022-12-28 02:51:03'),('106','Archive Work Location','TL-304','INS->ADMIN->2022-12-28 02:51:10'),('107','Unarchive Work Location','TL-305','INS->ADMIN->2022-12-28 02:51:25'),('108','Add Departure Reason','TL-313','INS->ADMIN->2022-12-31 12:59:07'),('109','Update Departure Reason','TL-314','INS->ADMIN->2022-12-31 12:59:20'),('11','Add Action','TL-28',NULL),('110','Delete Departure Reason','TL-315','INS->ADMIN->2022-12-31 12:59:30'),('111','Add Employee Type','TL-320','INS->ADMIN->2022-12-31 02:03:47'),('112','Update Employee Type','TL-321','INS->ADMIN->2022-12-31 02:03:59'),('113','Delete Employee Type','TL-322','INS->ADMIN->2022-12-31 02:04:13'),('114','Add Wage Type','TL-332','INS->ADMIN->2023-01-02 11:10:37'),('115','Update Wage Type','TL-333','UPD->ADMIN->2023-01-02 11:11:12'),('116','Delete Wage Type','TL-334','INS->ADMIN->2023-01-02 11:10:59'),('12','Update Action','TL-29',NULL),('13','Delete Action','TL-30',NULL),('14','Add Action Access Right','TL-31',NULL),('15','Delete Action Access Right','TL-32',NULL),('17','Add System Parameter','TL-35','INS->ADMIN->2022-12-02 01:57:57'),('18','Update System Parameter','TL-36','INS->ADMIN->2022-12-02 01:58:13'),('19','Delete System Parameter','TL-37','INS->ADMIN->2022-12-02 01:58:24'),('2','Update Module','TL-8',NULL),('20','Add Role','42','UPD->ADMIN->2022-12-02 05:26:24'),('21','Update Role','43','UPD->ADMIN->2022-12-02 05:26:53'),('22','Delete Role','44','INS->ADMIN->2022-12-02 05:26:59'),('23','Add Role Module Access','45','INS->ADMIN->2022-12-02 05:27:18'),('24','Delete Role Module Access','46','INS->ADMIN->2022-12-02 05:27:34'),('25','Add Role Page Access','47','INS->ADMIN->2022-12-02 05:27:51'),('26','Delete Role Page Access','48','INS->ADMIN->2022-12-02 05:28:04'),('27','Add Role Action Access','49','INS->ADMIN->2022-12-02 05:28:17'),('28','Delete Role Action Access','50','INS->ADMIN->2022-12-02 05:28:30'),('29','Add Role User Account','51','INS->ADMIN->2022-12-04 09:52:54'),('3','Delete Module','TL-9',NULL),('30','Delete Role User Account','52','INS->ADMIN->2022-12-04 09:53:16'),('31','Add Upload Setting','59','INS->ADMIN->2022-12-06 03:10:25'),('32','Update Upload Setting','60','INS->ADMIN->2022-12-06 03:10:45'),('33','Delete Upload Setting','61','INS->ADMIN->2022-12-06 03:11:18'),('34','Add System Code','65','INS->ADMIN->2022-12-06 05:03:57'),('35','Update System Code','66','INS->ADMIN->2022-12-06 05:04:18'),('36','Delete System Code','67','INS->ADMIN->2022-12-06 05:04:34'),('37','Add Upload Setting File Type','101','INS->ADMIN->2022-12-07 01:22:51'),('38','Delete Upload Setting File Type','102','INS->ADMIN->2022-12-07 01:23:12'),('39','Add Company','107','UPD->ADMIN->2022-12-07 03:54:12'),('4','Add Module Access Right','TL-12',NULL),('40','Update Company','108','INS->ADMIN->2022-12-07 03:54:24'),('41','Delete Company','109','INS->ADMIN->2022-12-07 03:54:36'),('42','Add Interface Setting','122','UPD->ADMIN->2022-12-09 10:38:57'),('43','Update Interface Setting','123','UPD->ADMIN->2022-12-09 10:39:06'),('44','Delete Interface Setting','124','UPD->ADMIN->2022-12-09 10:39:12'),('45','Activate Interface Setting','125','UPD->ADMIN->2022-12-09 10:39:18'),('46','Deactivate Interface Setting','126','UPD->ADMIN->2022-12-09 10:39:24'),('47','Add Email Setting','141','INS->ADMIN->2022-12-12 12:55:18'),('48','Update Email Setting','142','INS->ADMIN->2022-12-12 12:55:29'),('49','Delete Email Setting','143','INS->ADMIN->2022-12-12 12:55:41'),('5','Delete Module Access Right','TL-13',NULL),('50','Activate Email Setting','144','INS->ADMIN->2022-12-12 12:56:09'),('51','Deactivate Email Setting','145','INS->ADMIN->2022-12-12 12:56:22'),('52','Add Notification Setting','161','INS->ADMIN->2022-12-12 05:26:27'),('53','Update Notification Setting','162','INS->ADMIN->2022-12-12 05:26:45'),('54','Delete Notification Setting','163','INS->ADMIN->2022-12-12 05:27:06'),('55','Add Role Notification Recipient','164','INS->ADMIN->2022-12-12 05:27:35'),('56','Delete Role Notification Recipient','165','INS->ADMIN->2022-12-12 05:27:56'),('57','Add User Notification Recipient','166','INS->ADMIN->2022-12-12 05:28:15'),('58','Delete User Notification Recipient','167','INS->ADMIN->2022-12-12 05:28:32'),('59','Add Notification Channel','168','INS->ADMIN->2022-12-14 12:55:28'),('6','Add Page','TL-20',NULL),('60','Delete Notification Channel','169','INS->ADMIN->2022-12-14 12:55:41'),('61','Add Country','182','INS->ADMIN->2022-12-15 01:52:37'),('62','Update Country','183','INS->ADMIN->2022-12-15 01:52:49'),('63','Delete Country','184','INS->ADMIN->2022-12-15 01:52:58'),('64','Add State','185','INS->ADMIN->2022-12-15 01:53:08'),('65','Update State','186','INS->ADMIN->2022-12-15 01:53:17'),('66','Delete State','187','INS->ADMIN->2022-12-15 01:53:27'),('67','Add Zoom API','199','INS->ADMIN->2022-12-15 04:17:49'),('68','Update Zoom API','200','INS->ADMIN->2022-12-15 04:20:41'),('69','Delete Zoom API','201','INS->ADMIN->2022-12-15 04:20:54'),('7','Update Page','TL-21','UPD->ADMIN->2022-12-02 09:37:48'),('70','Activate Zoom API','202','INS->ADMIN->2022-12-15 04:21:38'),('71','Deactivate Zoom API','203','INS->ADMIN->2022-12-15 04:21:51'),('72','Add User Account','213','INS->ADMIN->2022-12-16 03:36:46'),('73','Update User Account','214','INS->ADMIN->2022-12-16 03:37:00'),('74','Delete User Account','215','UPD->ADMIN->2022-12-16 03:37:17'),('75','Lock User Account','216','INS->ADMIN->2022-12-16 03:37:34'),('76','Unlock User Account','217','INS->ADMIN->2022-12-16 03:37:47'),('77','Activate User Account','218','INS->ADMIN->2022-12-16 03:37:59'),('78','Deactivate User Account','219','INS->ADMIN->2022-12-16 03:38:19'),('79','Add User Account Role','224','INS->ADMIN->2022-12-19 02:03:33'),('8','Delete Page','TL-22',NULL),('80','Delete User Account Role','225','INS->ADMIN->2022-12-19 02:03:50'),('81','Add Department','237','UPD->ADMIN->2022-12-23 10:28:08'),('82','Update Department','238','UPD->ADMIN->2022-12-23 10:28:11'),('83','Delete Department','239','UPD->ADMIN->2022-12-23 10:28:15'),('84','Archive Department','240','UPD->ADMIN->2022-12-23 10:28:23'),('85','Unarchive Department','241','UPD->ADMIN->2022-12-23 10:28:28'),('86','Add Job Position','248','INS->ADMIN->2022-12-23 04:35:00'),('87','Update Job Position','249','INS->ADMIN->2022-12-23 04:35:10'),('88','Delete Job Position','250','INS->ADMIN->2022-12-23 04:35:21'),('89','Start Job Position Recruitment','251','UPD->ADMIN->2022-12-23 04:36:29'),('9','Add Page Access Right','TL-23',NULL),('90','Stop Job Position Recruitment','252','INS->ADMIN->2022-12-23 04:36:47'),('91','Add Job Position Responsibility','254','INS->ADMIN->2022-12-23 05:15:09'),('92','Update Job Position Responsibility','255','UPD->ADMIN->2022-12-24 09:32:35'),('93','Delete Job Position Responsibility','256','UPD->ADMIN->2022-12-24 09:32:50'),('94','Add Job Position Requirement','257','UPD->ADMIN->2022-12-24 09:33:17'),('95','Update Job Position Requirement','258','UPD->ADMIN->2022-12-24 09:34:02'),('96','Delete Job Position Requirement','259','UPD->ADMIN->2022-12-24 09:34:21'),('97','Add Job Position Qualification','260','UPD->ADMIN->2022-12-24 09:34:39'),('98','Update Job Position Qualification','261','UPD->ADMIN->2022-12-24 09:34:53'),('99','Delete Job Position Qualification','262','UPD->ADMIN->2022-12-24 09:35:10');
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
INSERT INTO `technical_action_access_rights` VALUES ('1','1'),('10','1'),('11','1'),('12','1'),('13','1'),('14','1'),('15','1'),('17','1'),('18','1'),('19','1'),('2','1'),('20','1'),('21','1'),('22','1'),('23','1'),('24','1'),('25','1'),('26','1'),('27','1'),('28','1'),('29','1'),('3','1'),('30','1'),('4','1'),('5','1'),('6','1'),('7','1'),('8','1'),('9','1'),('31','1'),('32','1'),('33','1'),('34','1'),('35','1'),('36','1'),('37','1'),('38','1'),('39','1'),('40','1'),('41','1'),('42','1'),('43','1'),('44','1'),('45','1'),('46','1'),('47','1'),('48','1'),('49','1'),('50','1'),('52','1'),('53','1'),('54','1'),('55','1'),('56','1'),('57','1'),('58','1'),('59','1'),('60','1'),('61','1'),('62','1'),('63','1'),('64','1'),('65','1'),('66','1'),('67','1'),('68','1'),('69','1'),('70','1'),('71','1'),('51','1'),('72','1'),('73','1'),('74','1'),('75','1'),('76','1'),('77','1'),('78','1'),('79','1'),('80','1'),('81','1'),('82','1'),('83','1'),('84','1'),('85','1'),('86','1'),('87','1'),('88','1'),('89','1'),('90','1'),('91','1'),('92','1'),('93','1'),('94','1'),('95','1'),('96','1'),('97','1'),('98','1'),('99','1'),('100','1'),('101','1'),('102','1'),('103','1'),('104','1'),('105','1'),('106','1'),('107','1'),('108','1'),('109','1'),('110','1'),('111','1'),('112','1'),('113','1'),('114','1'),('115','1'),('116','1');
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
  `DEFAULT_PAGE` varchar(100) NOT NULL,
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
INSERT INTO `technical_module` VALUES ('1','Settings','1.0.2','Administration Module',NULL,'TECHNICAL','modules.php','TL-3','UPD->ADMIN->2022-12-21 02:04:29',99),('2','Employee','1.0.0','Centralize employee information.',NULL,'HUMANRESOURCES','departments.php','230','UPD->ADMIN->2022-12-23 04:48:53',0),('3','Attendances','1.0.0','Track employee attendance.',NULL,'HUMANRESOURCES','attendance.php','232','INS->ADMIN->2022-12-21 02:31:24',NULL),('4','Time Off ','1.0.0','Allocate PTOs and follow leaves requests',NULL,'HUMANRESOURCES','leave.php','233','INS->ADMIN->2022-12-21 02:32:27',NULL),('5','Payroll ','1.0.0','Manage your employee payroll records.',NULL,'HUMANRESOURCES','payroll.php','234','UPD->ADMIN->2022-12-21 02:41:28',2);
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
INSERT INTO `technical_module_access_rights` VALUES ('1','1'),('2','1'),('3','1'),('4','1'),('5','1');
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
INSERT INTO `technical_page` VALUES ('1','Modules','1','TL-10','UPD->ADMIN->2022-12-31 12:41:25'),('10','Role Form','1','41','INS->ADMIN->2022-12-02 05:25:12'),('11','Upload Settings','1','58','INS->ADMIN->2022-12-06 03:07:55'),('12','Upload Setting Form','1','62','INS->ADMIN->2022-12-06 04:11:17'),('13','System Codes','1','63','UPD->ADMIN->2022-12-06 05:02:54'),('14','System Code Form','1','64','INS->ADMIN->2022-12-06 05:03:46'),('15','Company','1','105','INS->ADMIN->2022-12-07 03:50:51'),('16','Company Form','1','106','INS->ADMIN->2022-12-07 03:51:05'),('17','Interface Settings','1','121','UPD->ADMIN->2022-12-09 10:35:44'),('18','Interface Setting Form','1','127','UPD->ADMIN->2022-12-09 10:35:36'),('19','Email Settings','1','139','INS->ADMIN->2022-12-12 12:48:32'),('2','Module Form','1','TL-11',NULL),('20','Email Setting Page','1','140','INS->ADMIN->2022-12-12 12:48:47'),('21','Notification Settings','1','159','INS->ADMIN->2022-12-12 05:25:24'),('22','Notification Setting Form','1','160','INS->ADMIN->2022-12-12 05:25:42'),('23','Country','1','178','INS->ADMIN->2022-12-15 01:47:18'),('24','Country Form','1','179','INS->ADMIN->2022-12-15 01:48:04'),('25','State','1','180','INS->ADMIN->2022-12-15 01:48:26'),('26','State Form','1','181','INS->ADMIN->2022-12-15 01:48:40'),('27','Zoom API','1','197','UPD->ADMIN->2022-12-15 04:20:20'),('28','Zoom API Form','1','198','UPD->ADMIN->2022-12-15 04:20:30'),('29','User Accounts','1','210','UPD->ADMIN->2022-12-16 03:43:20'),('3','Pages','1','TL-18',NULL),('30','User Account Form','1','211','INS->ADMIN->2022-12-16 03:04:19'),('31','Departments','2','235','UPD->ADMIN->2022-12-23 10:25:34'),('32','Department Form','2','236','UPD->ADMIN->2022-12-23 10:25:18'),('33','Job Positions','2','246','INS->ADMIN->2022-12-23 04:32:50'),('34','Job Position Form','2','247','INS->ADMIN->2022-12-23 04:33:22'),('35','Work Locations','2','TL-299','UPD->ADMIN->2022-12-28 02:55:51'),('36','Work Location Form','2','TL-300','INS->ADMIN->2022-12-28 02:47:17'),('37','Departure Reason','2','TL-311','INS->ADMIN->2022-12-31 12:58:28'),('38','Departure Reason Form','2','TL-312','INS->ADMIN->2022-12-31 12:58:49'),('39','Employee Types','2','TL-318','INS->ADMIN->2022-12-31 02:03:02'),('4','Page Form','1','TL-19',NULL),('40','Employee Type Form','2','TL-319','INS->ADMIN->2022-12-31 02:03:22'),('41','Wage Types','2','TL-329','UPD->ADMIN->2023-01-02 10:21:11'),('42','Wage Type Form','2','TL-330','INS->ADMIN->2023-01-02 10:21:28'),('5','Action','1','TL-26','UPD->ADMIN->2022-12-01 02:29:29'),('6','Action Form','1','TL-27','INS->ADMIN->2022-12-01 02:05:33'),('7','System Parameters','1','TL-34','UPD->ADMIN->2022-12-02 02:47:59'),('8','System Parameter Form','1','TL-38','INS->ADMIN->2022-12-02 02:47:02'),('9','Role','1','40','INS->ADMIN->2022-12-02 05:24:52');
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
INSERT INTO `technical_page_access_rights` VALUES ('1','1'),('10','1'),('2','1'),('3','1'),('4','1'),('6','1'),('7','1'),('8','1'),('9','1'),('11','1'),('12','1'),('13','1'),('14','1'),('15','1'),('16','1'),('17','1'),('18','1'),('19','1'),('20','1'),('21','1'),('22','1'),('23','1'),('24','1'),('25','1'),('26','1'),('27','1'),('28','1'),('29','1'),('30','1'),('5','1'),('31','1'),('32','1'),('34','1'),('33','1'),('35','1'),('36','1'),('37','1'),('38','1'),('39','1'),('40','1'),('41','1'),('42','1');
/*!40000 ALTER TABLE `technical_page_access_rights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'dssdb'
--
/*!50003 DROP PROCEDURE IF EXISTS `check_action_access_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `check_action_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `check_company_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `check_country_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_country_exist`(IN country_id INT(50))
BEGIN
	SET @country_id = country_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_country WHERE COUNTRY_ID = @country_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_department_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_department_exist`(IN department_id VARCHAR(50))
BEGIN
	SET @department_id = department_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_department WHERE DEPARTMENT_ID = @department_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_departure_reason_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_departure_reason_exist`(IN departure_reason_id VARCHAR(50))
BEGIN
	SET @departure_reason_id = departure_reason_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_departure_reason WHERE DEPARTURE_REASON_ID = @departure_reason_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_email_setting_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_email_setting_exist`(IN email_setting_id INT(50))
BEGIN
	SET @email_setting_id = email_setting_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_email_setting WHERE EMAIL_SETTING_ID = @email_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_employee_type_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_employee_type_exist`(IN employee_type_id VARCHAR(50))
BEGIN
	SET @employee_type_id = employee_type_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_employee_type WHERE EMPLOYEE_TYPE_ID = @employee_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_interface_setting_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `check_job_position_attachment_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_job_position_attachment_exist`(IN attachment_id VARCHAR(100))
BEGIN
	SET @attachment_id = attachment_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_job_position_attachment WHERE ATTACHMENT_ID = @attachment_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_job_position_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_job_position_exist`(IN job_position_id VARCHAR(100))
BEGIN
	SET @job_position_id = job_position_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_job_position WHERE JOB_POSITION_ID = @job_position_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_job_position_qualification_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_job_position_qualification_exist`(IN qualification_id VARCHAR(100))
BEGIN
	SET @qualification_id = qualification_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_job_position_qualification WHERE QUALIFICATION_ID = @qualification_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_job_position_requirement_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_job_position_requirement_exist`(IN requirement_id VARCHAR(100))
BEGIN
	SET @requirement_id = requirement_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_job_position_requirement WHERE REQUIREMENT_ID = @requirement_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_job_position_responsibility_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_job_position_responsibility_exist`(IN responsibility_id VARCHAR(100))
BEGIN
	SET @responsibility_id = responsibility_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_job_position_responsibility WHERE RESPONSIBILITY_ID = @responsibility_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_module_access_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `check_module_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `check_notification_channel_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_notification_channel_exist`(IN notification_setting_id INT(50), IN channel VARCHAR(20))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @channel = channel;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_notification_channel WHERE NOTIFICATION_SETTING_ID = @notification_setting_id AND CHANNEL = @channel';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_notification_role_recipient_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_notification_role_recipient_exist`(IN `notification_setting_id` INT(50), IN `role_id` VARCHAR(50))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @role_id = role_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_notification_role_recipient WHERE NOTIFICATION_SETTING_ID = @notification_setting_id AND ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_notification_setting_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_notification_setting_exist`(IN notification_setting_id INT(50))
BEGIN
	SET @notification_setting_id = notification_setting_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_notification_setting WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_notification_user_account_recipient_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_notification_user_account_recipient_exist`(IN notification_setting_id INT(50), IN username VARCHAR(50))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @username = username;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_notification_user_account_recipient WHERE NOTIFICATION_SETTING_ID = @notification_setting_id AND USERNAME = @username';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_page_access_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `check_page_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `check_role_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `check_role_user_account_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `check_state_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_state_exist`(IN state_id INT(50))
BEGIN
	SET @state_id = state_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_state WHERE STATE_ID = @state_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_system_code_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `check_system_parameter_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `check_upload_setting_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `check_upload_setting_file_type_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `check_user_account_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `check_wage_type_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_wage_type_exist`(IN wage_type_id VARCHAR(50))
BEGIN
	SET @wage_type_id = wage_type_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_wage_type WHERE WAGE_TYPE_ID = @wage_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_work_location_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_work_location_exist`(IN work_location_id VARCHAR(50))
BEGIN
	SET @work_location_id = work_location_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_work_location WHERE WORK_LOCATION_ID = @work_location_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_zoom_api_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_zoom_api_exist`(IN zoom_api_id INT(50))
BEGIN
	SET @zoom_api_id = zoom_api_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_zoom_api WHERE ZOOM_API_ID = @zoom_api_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_action` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_action_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_action_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_job_position_qualification` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_job_position_qualification`(IN job_position_id VARCHAR(100))
BEGIN
	SET @job_position_id = job_position_id;

	SET @query = 'DELETE FROM employee_job_position_qualification WHERE JOB_POSITION_ID = @job_position_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_job_position_requirement` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_job_position_requirement`(IN job_position_id VARCHAR(100))
BEGIN
	SET @job_position_id = job_position_id;

	SET @query = 'DELETE FROM employee_job_position_requirement WHERE JOB_POSITION_ID = @job_position_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_job_position_responsibility` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_job_position_responsibility`(IN job_position_id VARCHAR(100))
BEGIN
	SET @job_position_id = job_position_id;

	SET @query = 'DELETE FROM employee_job_position_responsibility WHERE JOB_POSITION_ID = @job_position_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_module_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_notification_channel` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_notification_channel`(IN notification_setting_id INT(50))
BEGIN
	SET @notification_setting_id = notification_setting_id;

	SET @query = 'DELETE FROM global_notification_channel WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_notification_role_recipient` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_notification_role_recipient`(IN notification_setting_id INT(50))
BEGIN
	SET @notification_setting_id = notification_setting_id;

	SET @query = 'DELETE FROM global_notification_role_recipient WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_notification_user_account_recipient` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_notification_user_account_recipient`(IN notification_setting_id INT(50))
BEGIN
	SET @notification_setting_id = notification_setting_id;

	SET @query = 'DELETE FROM global_notification_user_account_recipient WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_page_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_role_user_account` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_state` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_state`(IN country_id INT(50))
BEGIN
	SET @country_id = country_id;

	SET @query = 'DELETE FROM global_state WHERE COUNTRY_ID = @country_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_upload_setting_file_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_user_account_role` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_user_account_role`(IN username VARCHAR(50))
BEGIN
	SET @username = username;

	SET @query = 'DELETE FROM global_role_user_account WHERE USERNAME = @username';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_company` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_country` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_country`(IN country_id INT(50))
BEGIN
	SET @country_id = country_id;

	SET @query = 'DELETE FROM global_country WHERE COUNTRY_ID = @country_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_department` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_department`(IN department_id VARCHAR(50))
BEGIN
	SET @department_id = department_id;

	SET @query = 'DELETE FROM employee_department WHERE DEPARTMENT_ID = @department_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_departure_reason` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_departure_reason`(IN departure_reason_id VARCHAR(50))
BEGIN
	SET @departure_reason_id = departure_reason_id;

	SET @query = 'DELETE FROM employee_departure_reason WHERE DEPARTURE_REASON_ID = @departure_reason_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_email_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_email_setting`(IN email_setting_id INT(50))
BEGIN
	SET @email_setting_id = email_setting_id;

	SET @query = 'DELETE FROM global_email_setting WHERE EMAIL_SETTING_ID = @email_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_employee_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_employee_type`(IN employee_type_id VARCHAR(50))
BEGIN
	SET @employee_type_id = employee_type_id;

	SET @query = 'DELETE FROM employee_employee_type WHERE EMPLOYEE_TYPE_ID = @employee_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_interface_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_job_position` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_job_position`(IN job_position_id VARCHAR(100))
BEGIN
	SET @job_position_id = job_position_id;

	SET @query = 'DELETE FROM employee_job_position WHERE JOB_POSITION_ID = @job_position_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_job_position_attachment` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_job_position_attachment`(IN attachment_id VARCHAR(100))
BEGIN
	SET @attachment_id = attachment_id;

	SET @query = 'DELETE FROM employee_job_position_attachment WHERE ATTACHMENT_ID = @attachment_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_job_position_qualification` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_job_position_qualification`(IN qualification_id VARCHAR(100))
BEGIN
	SET @qualification_id = qualification_id;

	SET @query = 'DELETE FROM employee_job_position_qualification WHERE QUALIFICATION_ID = @qualification_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_job_position_requirement` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_job_position_requirement`(IN requirement_id VARCHAR(100))
BEGIN
	SET @requirement_id = requirement_id;

	SET @query = 'DELETE FROM employee_job_position_requirement WHERE REQUIREMENT_ID = @requirement_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_job_position_responsibility` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_job_position_responsibility`(IN responsibility_id VARCHAR(100))
BEGIN
	SET @responsibility_id = responsibility_id;

	SET @query = 'DELETE FROM employee_job_position_responsibility WHERE RESPONSIBILITY_ID = @responsibility_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_module` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_module_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_notification_channel` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_notification_channel`(IN notification_setting_id INT(50), IN channel VARCHAR(20))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @channel = channel;

	SET @query = 'DELETE FROM global_notification_channel WHERE NOTIFICATION_SETTING_ID = @notification_setting_id AND CHANNEL = @channel';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_notification_role_recipient` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_notification_role_recipient`(IN notification_setting_id INT(50), IN role_id VARCHAR(50))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @role_id = role_id;

	SET @query = 'DELETE FROM global_notification_role_recipient WHERE NOTIFICATION_SETTING_ID = @notification_setting_id AND ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_notification_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_notification_setting`(IN notification_setting_id INT(50))
BEGIN
	SET @notification_setting_id = notification_setting_id;

	SET @query = 'DELETE FROM global_notification_setting WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_notification_user_account_recipient` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_notification_user_account_recipient`(IN notification_setting_id INT(50), IN username VARCHAR(50))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @username = username;

	SET @query = 'DELETE FROM global_notification_user_account_recipient WHERE NOTIFICATION_SETTING_ID = @notification_setting_id AND USERNAME = @username';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_page` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_page_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_role` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_role_action_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_role_module_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_role_page_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_role_user_account` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_state` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_state`(IN state_id INT(50))
BEGIN
	SET @state_id = state_id;

	SET @query = 'DELETE FROM global_state WHERE STATE_ID = @state_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_system_code` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_system_parameter` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_upload_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_upload_setting_file_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `delete_user_account` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_user_account`(IN username VARCHAR(50))
BEGIN
	SET @username = username;

	SET @query = 'DELETE FROM global_user_account WHERE USERNAME = @username';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_wage_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_wage_type`(IN wage_type_id VARCHAR(50))
BEGIN
	SET @wage_type_id = wage_type_id;

	SET @query = 'DELETE FROM employee_wage_type WHERE WAGE_TYPE_ID = @wage_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_work_location` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_work_location`(IN work_location_id VARCHAR(50))
BEGIN
	SET @work_location_id = work_location_id;

	SET @query = 'DELETE FROM employee_work_location WHERE WORK_LOCATION_ID = @work_location_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_zoom_api` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_zoom_api`(IN zoom_api_id INT(50))
BEGIN
	SET @zoom_api_id = zoom_api_id;

	SET @query = 'DELETE FROM global_zoom_api WHERE ZOOM_API_ID = @zoom_api_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_country_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_country_options`()
BEGIN
	SET @query = 'SELECT COUNTRY_ID, COUNTRY_NAME FROM global_country ORDER BY COUNTRY_NAME';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_department_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_department_options`(IN generation_type VARCHAR(10))
BEGIN
	IF @generation_type = 'active' THEN
		SET @query = 'SELECT DEPARTMENT_ID, DEPARTMENT FROM employee_department WHERE STATUS = "1" ORDER BY DEPARTMENT';
	ELSE
		SET @query = 'SELECT DEPARTMENT_ID, DEPARTMENT FROM employee_department ORDER BY DEPARTMENT';
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
/*!50003 DROP PROCEDURE IF EXISTS `generate_departure_reason_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_departure_reason_options`()
BEGIN
	SET @query = 'SELECT DEPARTURE_REASON_ID, DEPARTURE_REASON FROM employee_departure_reason ORDER BY DEPARTURE_REASON';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_employee_type_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_employee_type_options`()
BEGIN
	SET @query = 'SELECT EMPLOYEE_TYPE_ID, EMPLOYEE_TYPE FROM employee_employee_type ORDER BY EMPLOYEE_TYPE';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_job_position_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_job_position_options`(IN generation_type VARCHAR(10))
BEGIN
	IF @generation_type = 'active' THEN
		SET @query = 'SELECT JOB_POSITION_ID, JOB_POSITION FROM employee_work_location WHERE RECRUITMENT_STATUS = "1" ORDER BY JOB_POSITION';
	ELSEIF @generation_type = 'inactive' THEN
		SET @query = 'SELECT JOB_POSITION_ID, JOB_POSITION FROM employee_work_location WHERE RECRUITMENT_STATUS = "2" ORDER BY JOB_POSITION';
	ELSE
		SET @query = 'SELECT JOB_POSITION_ID, JOB_POSITION FROM employee_work_location ORDER BY JOB_POSITION';
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
/*!50003 DROP PROCEDURE IF EXISTS `generate_module_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `generate_role_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `generate_system_code_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `generate_wage_type_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_wage_type_options`()
BEGIN
	SET @query = 'SELECT WAGE_TYPE_ID, WAGE_TYPE FROM employee_wage_type ORDER BY WAGE_TYPE';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_work_location_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_work_location_options`(IN generation_type VARCHAR(10))
BEGIN
	IF @generation_type = 'active' THEN
		SET @query = 'SELECT WORK_LOCATION_ID, WORK_LOCATION FROM employee_work_location WHERE STATUS = "1" ORDER BY WORK_LOCATION';
	ELSE
		SET @query = 'SELECT WORK_LOCATION_ID, WORK_LOCATION FROM employee_work_location ORDER BY WORK_LOCATION';
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
/*!50003 DROP PROCEDURE IF EXISTS `get_access_rights_count` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `get_action_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `get_activated_email_setting_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_activated_email_setting_details`()
BEGIN
	SET @query = 'SELECT EMAIL_SETTING_ID, EMAIL_SETTING_NAME, DESCRIPTION, STATUS, MAIL_HOST, PORT, SMTP_AUTH, SMTP_AUTO_TLS, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_NAME, MAIL_FROM_EMAIL, TRANSACTION_LOG_ID, RECORD_LOG FROM global_email_setting WHERE STATUS = 1';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_activated_interface_setting_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `get_activated_zoom_api_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_activated_zoom_api_details`()
BEGIN
	SET @query = 'SELECT ZOOM_API_ID, ZOOM_API_NAME, DESCRIPTION, API_KEY, API_SECRET, STATUS, TRANSACTION_LOG_ID, RECORD_LOG FROM global_zoom_api WHERE STATUS = 1';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_all_accessible_module_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_accessible_module_details`(IN username VARCHAR(50))
BEGIN
	SET @username = username;

	SET @query = 'SELECT MODULE_ID, MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPTION, MODULE_ICON, MODULE_CATEGORY, DEFAULT_PAGE, TRANSACTION_LOG_ID, RECORD_LOG, ORDER_SEQUENCE FROM technical_module WHERE MODULE_ID IN (SELECT MODULE_ID FROM technical_module_access_rights WHERE ROLE_ID IN (SELECT ROLE_ID FROM global_role_user_account WHERE USERNAME = @username)) ORDER BY ORDER_SEQUENCE';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_company_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `get_country_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_country_details`(IN country_id INT(50))
BEGIN
	SET @country_id = country_id;

	SET @query = 'SELECT COUNTRY_NAME, TRANSACTION_LOG_ID, RECORD_LOG FROM global_country WHERE COUNTRY_ID = @country_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_department_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_department_details`(IN department_id VARCHAR(50))
BEGIN
	SET @department_id = department_id;

	SET @query = 'SELECT DEPARTMENT, PARENT_DEPARTMENT, MANAGER, STATUS, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_department WHERE DEPARTMENT_ID = @department_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_departure_reason_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_departure_reason_details`(IN departure_reason_id VARCHAR(50))
BEGIN
	SET @departure_reason_id = departure_reason_id;

	SET @query = 'SELECT DEPARTURE_REASON, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_departure_reason WHERE DEPARTURE_REASON_ID = @departure_reason_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_email_setting_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_email_setting_details`(IN email_setting_id INT(50))
BEGIN
	SET @email_setting_id = email_setting_id;

	SET @query = 'SELECT EMAIL_SETTING_NAME, DESCRIPTION, STATUS, MAIL_HOST, PORT, SMTP_AUTH, SMTP_AUTO_TLS, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_NAME, MAIL_FROM_EMAIL, TRANSACTION_LOG_ID, RECORD_LOG FROM global_email_setting WHERE EMAIL_SETTING_ID = @email_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_employee_type_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_employee_type_details`(IN employee_type_id VARCHAR(50))
BEGIN
	SET @employee_type_id = employee_type_id;

	SET @query = 'SELECT EMPLOYEE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_employee_type WHERE EMPLOYEE_TYPE_ID = @employee_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_interface_setting_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `get_job_position_attachment_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_job_position_attachment_details`(IN attachment_id VARCHAR(100))
BEGIN
	SET @attachment_id = attachment_id;

	SET @query = 'SELECT JOB_POSITION_ID, ATTACHMENT_NAME, ATTACHMENT, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_job_position_attachment WHERE ATTACHMENT_ID = @attachment_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_job_position_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_job_position_details`(IN job_position_id VARCHAR(100))
BEGIN
	SET @job_position_id = job_position_id;

	SET @query = 'SELECT JOB_POSITION, DESCRIPTION, RECRUITMENT_STATUS, DEPARTMENT, EXPECTED_NEW_EMPLOYEES, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_job_position WHERE JOB_POSITION_ID = @job_position_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_job_position_qualification_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_job_position_qualification_details`(IN qualification_id VARCHAR(100))
BEGIN
	SET @qualification_id = qualification_id;

	SET @query = 'SELECT JOB_POSITION_ID, QUALIFICATION, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_job_position_qualification WHERE QUALIFICATION_ID = @qualification_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_job_position_requirement_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_job_position_requirement_details`(IN requirement_id VARCHAR(100))
BEGIN
	SET @requirement_id = requirement_id;

	SET @query = 'SELECT JOB_POSITION_ID, REQUIREMENT, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_job_position_requirement WHERE REQUIREMENT_ID = @requirement_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_job_position_responsibility_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_job_position_responsibility_details`(IN `responsibility_id` VARCHAR(100))
BEGIN
	SET @responsibility_id = responsibility_id;

	SET @query = 'SELECT JOB_POSITION_ID, RESPONSIBILITY, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_job_position_responsibility WHERE RESPONSIBILITY_ID = @responsibility_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_module_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_module_details`(IN module_id VARCHAR(100))
BEGIN
	SET @module_id = module_id;

	SET @query = 'SELECT MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPTION, MODULE_ICON, MODULE_CATEGORY, DEFAULT_PAGE, TRANSACTION_LOG_ID, RECORD_LOG, ORDER_SEQUENCE FROM technical_module WHERE MODULE_ID = @module_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_notification_setting_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_notification_setting_details`(IN notification_setting_id INT(50))
BEGIN
	SET @notification_setting_id = notification_setting_id;

	SET @query = 'SELECT NOTIFICATION_SETTING, DESCRIPTION, NOTIFICATION_TITLE, NOTIFICATION_MESSAGE, SYSTEM_LINK, EMAIL_LINK, TRANSACTION_LOG_ID, RECORD_LOG FROM global_notification_setting WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_page_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `get_role_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `get_state_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_state_details`(IN state_id INT(50))
BEGIN
	SET @state_id = state_id;

	SET @query = 'SELECT STATE_NAME, COUNTRY_ID, TRANSACTION_LOG_ID, RECORD_LOG FROM global_state WHERE STATE_ID = @state_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_system_code_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `get_system_parameter` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `get_system_parameter_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `get_upload_file_type_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `get_upload_setting_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `get_user_account_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `get_wage_type_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_wage_type_details`(IN wage_type_id VARCHAR(50))
BEGIN
	SET @wage_type_id = wage_type_id;

	SET @query = 'SELECT WAGE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_wage_type WHERE WAGE_TYPE_ID = @wage_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_work_location_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_work_location_details`(IN work_location_id VARCHAR(50))
BEGIN
	SET @work_location_id = work_location_id;

	SET @query = 'SELECT WORK_LOCATION, WORK_LOCATION_ADDRESS, EMAIL, TELEPHONE, MOBILE, LOCATION_NUMBER, STATUS, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_work_location WHERE WORK_LOCATION_ID = @work_location_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_zoom_api_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_zoom_api_details`(IN zoom_api_id INT(50))
BEGIN
	SET @zoom_api_id = zoom_api_id;

	SET @query = 'SELECT ZOOM_API_NAME, DESCRIPTION, API_KEY, API_SECRET, STATUS, TRANSACTION_LOG_ID, RECORD_LOG FROM global_zoom_api WHERE ZOOM_API_ID = @zoom_api_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_action` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `insert_action_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `insert_company` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `insert_country` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_country`(IN country_id INT(50), IN country_name VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @country_id = country_id;
	SET @country_name = country_name;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_country (COUNTRY_ID, COUNTRY_NAME, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@country_id, @country_name, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_department` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_department`(IN department_id VARCHAR(50), IN department VARCHAR(100), IN parent_department VARCHAR(50), IN manager VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @department_id = department_id;
	SET @department = department;
	SET @parent_department = parent_department;
	SET @manager = manager;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO employee_department (DEPARTMENT_ID, DEPARTMENT, PARENT_DEPARTMENT, MANAGER, STATUS, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@department_id, @department, @parent_department, @manager, "1", @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_departure_reason` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_departure_reason`(IN departure_reason_id VARCHAR(50), IN departure_reason VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @departure_reason_id = departure_reason_id;
	SET @departure_reason = departure_reason;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO employee_departure_reason (DEPARTURE_REASON_ID, DEPARTURE_REASON, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@departure_reason_id, @departure_reason, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_email_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_email_setting`(IN email_setting_id INT(50), IN email_setting_name VARCHAR(100), IN description VARCHAR(200), IN mail_host VARCHAR(100), IN port INT, IN smtp_auth INT(1), IN smtp_auto_tls INT(1), IN mail_username VARCHAR(200), IN mail_password VARCHAR(200), IN mail_encryption VARCHAR(20), IN mail_from_name VARCHAR(200), IN mail_from_email VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @email_setting_id = email_setting_id;
	SET @email_setting_name = email_setting_name;
	SET @description = description;
	SET @mail_host = mail_host;
	SET @port = port;
	SET @smtp_auth = smtp_auth;
	SET @smtp_auto_tls = smtp_auto_tls;
	SET @mail_username = mail_username;
	SET @mail_password = mail_password;
	SET @mail_encryption = mail_encryption;
	SET @mail_from_name = mail_from_name;
	SET @mail_from_email = mail_from_email;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_email_setting (EMAIL_SETTING_ID, EMAIL_SETTING_NAME, DESCRIPTION, STATUS, MAIL_HOST, PORT, SMTP_AUTH, SMTP_AUTO_TLS, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_NAME, MAIL_FROM_EMAIL, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@email_setting_id, @email_setting_name, @description, "2", @mail_host, @port, @smtp_auth, @smtp_auto_tls, @mail_username, @mail_password, @mail_encryption, @mail_from_name, @mail_from_email, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_employee_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_employee_type`(IN employee_type_id VARCHAR(50), IN employee_type VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @employee_type_id = employee_type_id;
	SET @employee_type = employee_type;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO employee_employee_type (EMPLOYEE_TYPE_ID, EMPLOYEE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@employee_type_id, @employee_type, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_interface_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `insert_job_position` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_job_position`(IN job_position_id VARCHAR(100), IN job_position VARCHAR(100), IN description VARCHAR(500), IN department VARCHAR(50), IN expected_new_employees INT(10), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @job_position_id = job_position_id;
	SET @job_position = job_position;
	SET @description = description;
	SET @department = department;
	SET @expected_new_employees = expected_new_employees;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO employee_job_position (JOB_POSITION_ID, JOB_POSITION, DESCRIPTION, RECRUITMENT_STATUS, DEPARTMENT, EXPECTED_NEW_EMPLOYEES, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@job_position_id, @job_position, @description, "2", @department, @expected_new_employees, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_job_position_attachment` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_job_position_attachment`(IN attachment_id VARCHAR(100), IN job_position_id VARCHAR(100), IN attachment_name VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @attachment_id = attachment_id;
	SET @job_position_id = job_position_id;
	SET @attachment_name = attachment_name;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO employee_job_position_attachment (ATTACHMENT_ID, JOB_POSITION_ID, ATTACHMENT_NAME, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@attachment_id, @job_position_id, @attachment_name, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_job_position_qualification` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_job_position_qualification`(IN qualification_id VARCHAR(100), IN job_position_id VARCHAR(100), IN qualification VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @qualification_id = qualification_id;
	SET @job_position_id = job_position_id;
	SET @qualification = qualification;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO employee_job_position_qualification (QUALIFICATION_ID, JOB_POSITION_ID, QUALIFICATION, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@qualification_id, @job_position_id, @qualification, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_job_position_requirement` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_job_position_requirement`(IN requirement_id VARCHAR(100), IN job_position_id VARCHAR(100), IN requirement VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @requirement_id = requirement_id;
	SET @job_position_id = job_position_id;
	SET @requirement = requirement;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO employee_job_position_requirement (REQUIREMENT_ID, JOB_POSITION_ID, REQUIREMENT, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@requirement_id, @job_position_id, @requirement, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_job_position_responsibility` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_job_position_responsibility`(IN responsibility_id VARCHAR(100), IN job_position_id VARCHAR(100), IN responsibility VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @responsibility_id = responsibility_id;
	SET @job_position_id = job_position_id;
	SET @responsibility = responsibility;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO employee_job_position_responsibility (RESPONSIBILITY_ID, JOB_POSITION_ID, RESPONSIBILITY, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@responsibility_id, @job_position_id, @responsibility, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_module` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_module`(IN module_id VARCHAR(100), IN module_name VARCHAR(200), IN module_version VARCHAR(20), IN module_description VARCHAR(500), IN module_category VARCHAR(50), IN default_page VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100), IN order_sequence INT)
BEGIN
	SET @module_id = module_id;
	SET @module_name = module_name;
	SET @module_version = module_version;
	SET @module_description = module_description;
	SET @module_category = module_category;
	SET @default_page = default_page;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO technical_module (MODULE_ID, MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPTION, MODULE_CATEGORY, DEFAULT_PAGE, TRANSACTION_LOG_ID, RECORD_LOG, ORDER_SEQUENCE) VALUES(@module_id, @module_name, @module_version, @module_description, @module_category, @default_page, @transaction_log_id, @record_log, @order_sequence)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_module_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `insert_notification_channel` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_notification_channel`(IN `notification_setting_id` INT(50), IN `channel` VARCHAR(20))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @channel = channel;

	SET @query = 'INSERT INTO global_notification_channel (NOTIFICATION_SETTING_ID, CHANNEL) VALUES(@notification_setting_id, @channel)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_notification_role_recipient` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_notification_role_recipient`(IN notification_setting_id INT(50), IN role_id VARCHAR(50))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @role_id = role_id;

	SET @query = 'INSERT INTO global_notification_role_recipient (NOTIFICATION_SETTING_ID, ROLE_ID) VALUES(@notification_setting_id, @role_id)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_notification_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_notification_setting`(IN notification_setting_id INT(50), IN notification_setting VARCHAR(100), IN description VARCHAR(200), IN notification_title VARCHAR(500), IN notification_message VARCHAR(500), IN system_link VARCHAR(200), IN email_link VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @notification_setting = notification_setting;
	SET @description = description;
	SET @notification_title = notification_title;
	SET @notification_message = notification_message;
	SET @system_link = system_link;
	SET @email_link = email_link;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_notification_setting (NOTIFICATION_SETTING_ID, NOTIFICATION_SETTING, DESCRIPTION, NOTIFICATION_TITLE, NOTIFICATION_MESSAGE, SYSTEM_LINK, EMAIL_LINK, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@notification_setting_id, @notification_setting, @description, @notification_title, @notification_message, @system_link, @email_link, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_notification_user_account_recipient` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_notification_user_account_recipient`(IN notification_setting_id INT(50), IN username VARCHAR(50))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @username = username;

	SET @query = 'INSERT INTO global_notification_user_account_recipient (NOTIFICATION_SETTING_ID, USERNAME) VALUES(@notification_setting_id, @username)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_page` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `insert_page_access` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `insert_role` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `insert_role_user_account` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `insert_state` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_state`(IN state_id INT(50), IN state_name VARCHAR(200), IN country_id INT(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @state_id = state_id;
	SET @state_name = state_name;
	SET @country_id = country_id;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_state (STATE_ID, STATE_NAME, COUNTRY_ID, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@state_id, @state_name, @country_id, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_system_code` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `insert_system_parameter` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `insert_transaction_log` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `insert_upload_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `insert_upload_setting_file_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `insert_user_account` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_user_account`(IN username VARCHAR(50), IN password VARCHAR(200), IN file_as VARCHAR (300), IN password_expiry_date DATE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @username = username;
	SET @password = password;
	SET @file_as = file_as;
	SET @password_expiry_date = password_expiry_date;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_user_account (USERNAME, PASSWORD, FILE_AS, USER_STATUS, PASSWORD_EXPIRY_DATE, FAILED_LOGIN, LAST_FAILED_LOGIN, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@username, @password, @file_as, "Inactive", @password_expiry_date, 0, null, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_wage_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_wage_type`(IN wage_type_id VARCHAR(50), IN wage_type VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @wage_type_id = wage_type_id;
	SET @wage_type = wage_type;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO employee_wage_type (WAGE_TYPE_ID, WAGE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@wage_type_id, @wage_type, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_work_location` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_work_location`(IN work_location_id VARCHAR(50), IN work_location VARCHAR(100), IN work_location_address VARCHAR(500), IN email VARCHAR(50), IN telephone VARCHAR(50), IN mobile VARCHAR(50), IN location_number INT, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @work_location_id = work_location_id;
	SET @work_location = work_location;
	SET @work_location_address = work_location_address;
	SET @email = email;
	SET @telephone = telephone;
	SET @mobile = mobile;
	SET @location_number = location_number;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO employee_work_location (WORK_LOCATION_ID, WORK_LOCATION, WORK_LOCATION_ADDRESS, EMAIL, TELEPHONE, MOBILE, LOCATION_NUMBER, STATUS, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@work_location_id, @work_location, @work_location_address, @email, @telephone, @mobile, @location_number, "1", @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_zoom_api` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_zoom_api`(IN zoom_api_id INT(50), IN zoom_api_name VARCHAR(100), IN description VARCHAR(200), IN api_key VARCHAR(1000), IN api_secret VARCHAR(1000), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @zoom_api_id = zoom_api_id;
	SET @zoom_api_name = zoom_api_name;
	SET @description = description;
	SET @api_key = api_key;
	SET @api_secret = api_secret;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_zoom_api (ZOOM_API_ID, ZOOM_API_NAME, DESCRIPTION, API_KEY, API_SECRET, STATUS, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@zoom_api_id, @zoom_api_name, @description, @api_key, @api_secret, "2", @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_action` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `update_company` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `update_company_logo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `update_country` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_country`(IN country_id INT(50), IN country_name VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @country_id = country_id;
	SET @country_name = country_name;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_country SET COUNTRY_NAME = @country_name, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE COUNTRY_ID = @country_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_department` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_department`(IN department_id VARCHAR(50), IN department VARCHAR(100), IN parent_department VARCHAR(50), IN manager VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @department_id = department_id;
	SET @department = department;
	SET @parent_department = parent_department;
	SET @manager = manager;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_department SET DEPARTMENT = @department, PARENT_DEPARTMENT = @parent_department, MANAGER = @manager, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE DEPARTMENT_ID = @department_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_department_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_department_status`(IN department_id VARCHAR(50), IN status TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @department_id = department_id;
	SET @status = status;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_department SET STATUS = @status, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE DEPARTMENT_ID = @department_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_departure_reason` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_departure_reason`(IN departure_reason_id VARCHAR(50), IN departure_reason VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @departure_reason_id = departure_reason_id;
	SET @departure_reason = departure_reason;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_departure_reason SET DEPARTURE_REASON = @departure_reason, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE DEPARTURE_REASON_ID = @departure_reason_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_email_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_email_setting`(IN email_setting_id INT(50), IN email_setting_name VARCHAR(100), IN description VARCHAR(200), IN mail_host VARCHAR(100), IN port INT, IN smtp_auth INT(1), IN smtp_auto_tls INT(1), IN mail_username VARCHAR(200), IN mail_password VARCHAR(200), IN mail_encryption VARCHAR(20), IN mail_from_name VARCHAR(200), IN mail_from_email VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @email_setting_id = email_setting_id;
	SET @email_setting_name = email_setting_name;
	SET @description = description;
	SET @mail_host = mail_host;
	SET @port = port;
	SET @smtp_auth = smtp_auth;
	SET @smtp_auto_tls = smtp_auto_tls;
	SET @mail_username = mail_username;
	SET @mail_password = mail_password;
	SET @mail_encryption = mail_encryption;
	SET @mail_from_name = mail_from_name;
	SET @mail_from_email = mail_from_email;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_email_setting SET EMAIL_SETTING_NAME = @email_setting_name, DESCRIPTION = @description, MAIL_HOST = @mail_host, PORT = @port, SMTP_AUTH = @smtp_auth, SMTP_AUTO_TLS = @smtp_auto_tls, MAIL_USERNAME = @mail_username, MAIL_PASSWORD = @mail_password, MAIL_ENCRYPTION = @mail_encryption, MAIL_FROM_NAME = @mail_from_name, MAIL_FROM_EMAIL = @mail_from_email, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE EMAIL_SETTING_ID = @interface_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_email_setting_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_email_setting_status`(IN email_setting_id INT(50), IN status TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @email_setting_id = email_setting_id;
	SET @status = status;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_email_setting SET STATUS = @status, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE EMAIL_SETTING_ID = @email_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_employee_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_employee_type`(IN employee_type_id VARCHAR(50), IN employee_type VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @employee_type_id = employee_type_id;
	SET @employee_type = employee_type;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_employee_type SET EMPLOYEE_TYPE = @employee_type, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE EMPLOYEE_TYPE_ID = @employee_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_interface_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `update_interface_settings_images` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `update_interface_setting_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `update_job_position` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_job_position`(IN job_position_id VARCHAR(100), IN job_position VARCHAR(100), IN description VARCHAR(500), IN department VARCHAR(50), IN expected_new_employees INT(10), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @job_position_id = job_position_id;
	SET @job_position = job_position;
	SET @description = description;
	SET @department = department;
	SET @expected_new_employees = expected_new_employees;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_job_position SET JOB_POSITION = @job_position, DESCRIPTION = @description, DEPARTMENT = @department, EXPECTED_NEW_EMPLOYEES = @expected_new_employees, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE JOB_POSITION_ID = @job_position_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_job_position_attachment` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_job_position_attachment`(IN attachment_id VARCHAR(100), IN attachment VARCHAR(500))
BEGIN
	SET @attachment_id = attachment_id;
	SET @attachment = attachment;

	SET @query = 'UPDATE employee_job_position_attachment SET ATTACHMENT = @attachment WHERE ATTACHMENT_ID = @attachment_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_job_position_attachment_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_job_position_attachment_details`(IN attachment_id VARCHAR(100), IN job_position_id VARCHAR(100), IN attachment_name VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @attachment_id = attachment_id;
	SET @job_position_id = job_position_id;
	SET @attachment_name = attachment_name;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_job_position_attachment SET ATTACHMENT_NAME = @attachment_name, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ATTACHMENT_ID = @attachment_id AND JOB_POSITION_ID = @job_position_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_job_position_qualification` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_job_position_qualification`(IN qualification_id VARCHAR(100), IN job_position_id VARCHAR(100), IN qualification VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @qualification_id = qualification_id;
	SET @job_position_id = job_position_id;
	SET @qualification = qualification;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_job_position_qualification SET QUALIFICATION = @qualification, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE QUALIFICATION_ID = @qualification_id AND JOB_POSITION_ID = @job_position_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_job_position_recruitment_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_job_position_recruitment_status`(IN job_position_id VARCHAR(50), IN recruitment_status TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @job_position_id = job_position_id;
	SET @recruitment_status = recruitment_status;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	IF @recruitment_status = 2 THEN
		SET @query = 'UPDATE employee_job_position SET RECRUITMENT_STATUS = @recruitment_status, EXPECTED_NEW_EMPLOYEES = 0, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE JOB_POSITION_ID = @job_position_id';
	ELSE
		SET @query = 'UPDATE employee_job_position SET RECRUITMENT_STATUS = @recruitment_status, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE JOB_POSITION_ID = @job_position_id';
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
/*!50003 DROP PROCEDURE IF EXISTS `update_job_position_requirement` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_job_position_requirement`(IN requirement_id VARCHAR(100), IN job_position_id VARCHAR(100), IN requirement VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @requirement_id = requirement_id;
	SET @job_position_id = job_position_id;
	SET @requirement = requirement;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_job_position_requirement SET REQUIREMENT = @requirement, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE REQUIREMENT_ID = @requirement_id AND JOB_POSITION_ID = @job_position_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_job_position_responsibility` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_job_position_responsibility`(IN responsibility_id VARCHAR(100), IN job_position_id VARCHAR(100), IN responsibility VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @responsibility_id = responsibility_id;
	SET @job_position_id = job_position_id;
	SET @responsibility = responsibility;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_job_position_responsibility SET RESPONSIBILITY = @responsibility, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE RESPONSIBILITY_ID = @responsibility_id AND JOB_POSITION_ID = @job_position_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_login_attempt` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `update_module` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_module`(IN module_id VARCHAR(100), IN module_name VARCHAR(200), IN module_version VARCHAR(20), IN module_description VARCHAR(500), IN module_category VARCHAR(50), IN default_page VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100), IN order_sequence INT)
BEGIN
	SET @module_id = module_id;
	SET @module_name = module_name;
	SET @module_version = module_version;
	SET @module_description = module_description;
	SET @module_category = module_category;
	SET @default_page = default_page;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;
	SET @order_sequence = order_sequence;

	SET @query = 'UPDATE technical_module SET MODULE_NAME = @module_name, MODULE_VERSION = @module_version, MODULE_DESCRIPTION = @module_description, MODULE_CATEGORY = @module_category, DEFAULT_PAGE = @default_page, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log, ORDER_SEQUENCE = @order_sequence WHERE MODULE_ID = @module_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_module_icon` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `update_notification_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_notification_setting`(IN notification_setting_id INT(50), IN notification_setting VARCHAR(100), IN description VARCHAR(200), IN notification_title VARCHAR(500), IN notification_message VARCHAR(500), IN system_link VARCHAR(200), IN email_link VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @notification_setting = notification_setting;
	SET @description = description;
	SET @notification_title = notification_title;
	SET @notification_message = notification_message;
	SET @system_link = system_link;
	SET @email_link = email_link;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_notification_setting SET NOTIFICATION_SETTING = @notification_setting, DESCRIPTION = @description, NOTIFICATION_TITLE = @notification_title, NOTIFICATION_MESSAGE = @notification_message, SYSTEM_LINK = @system_link, EMAIL_LINK = @email_link, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_other_email_setting_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_other_email_setting_status`(IN email_setting_id INT(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @email_setting_id = email_setting_id;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_email_setting SET STATUS = 2, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE EMAIL_SETTING_ID != @email_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_other_interface_setting_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `update_other_zoom_api_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_other_zoom_api_status`(IN zoom_api_id INT(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @zoom_api_id = zoom_api_id;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_zoom_api SET STATUS = 2, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ZOOM_API_ID != @zoom_api_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_page` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `update_role` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `update_state` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_state`(IN state_id INT(50), IN state_name VARCHAR(200), IN country_id INT(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @state_id = state_id;
	SET @state_name = state_name;
	SET @country_id = country_id;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_state SET STATE_NAME = @state_name, COUNTRY_ID = @country_id, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE STATE_ID = @state_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_system_code` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `update_system_parameter` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `update_system_parameter_value` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `update_upload_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `update_user_account` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_user_account`(IN username VARCHAR(50), IN password VARCHAR(200), IN file_as VARCHAR (300), IN password_expiry_date DATE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @username = username;
	SET @password = password;
	SET @file_as = file_as;
	SET @password_expiry_date = password_expiry_date;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	IF @password IS NULL OR @password = '' THEN
		SET @query = 'UPDATE global_user_account SET FILE_AS = @file_as, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE USERNAME = @username';
	ELSE
		SET @query = 'UPDATE global_user_account SET FILE_AS = @file_as, PASSWORD = @password, PASSWORD_EXPIRY_DATE = @password_expiry_date, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE USERNAME = @username';
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
/*!50003 DROP PROCEDURE IF EXISTS `update_user_account_lock_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_user_account_lock_status`(IN username VARCHAR(50), IN transaction_type VARCHAR(10), IN last_failed_login DATE, IN record_log VARCHAR(100))
BEGIN
	SET @username = username;
	SET @transaction_type = transaction_type;
	SET @last_failed_login = last_failed_login;
	SET @record_log = record_log;

	IF @transaction_type = 'unlock' THEN
		SET @query = 'UPDATE global_user_account SET FAILED_LOGIN = 0, LAST_FAILED_LOGIN = null, RECORD_LOG = @record_log WHERE USERNAME = @username';
	ELSE
		SET @query = 'UPDATE global_user_account SET FAILED_LOGIN = 5, LAST_FAILED_LOGIN = @last_failed_login, RECORD_LOG = @record_log WHERE USERNAME = @username';
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
/*!50003 DROP PROCEDURE IF EXISTS `update_user_account_password` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `update_user_account_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_user_account_status`(IN username VARCHAR(50), IN user_status VARCHAR(10), IN record_log VARCHAR(100))
BEGIN
	SET @username = username;
	SET @user_status = user_status;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_user_account SET USER_STATUS = @user_status, RECORD_LOG = @record_log WHERE USERNAME = @username';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_user_last_connection` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
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
/*!50003 DROP PROCEDURE IF EXISTS `update_wage_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_wage_type`(IN wage_type_id VARCHAR(50), IN wage_type VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @wage_type_id = wage_type_id;
	SET @wage_type = wage_type;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_wage_type SET WAGE_TYPE = @wage_type, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE WAGE_TYPE_ID = @wage_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_work_location` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_work_location`(IN work_location_id VARCHAR(50), IN work_location VARCHAR(100), IN work_location_address VARCHAR(500), IN email VARCHAR(50), IN telephone VARCHAR(50), IN mobile VARCHAR(50), IN location_number INT, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @work_location_id = work_location_id;
	SET @work_location = work_location;
	SET @work_location_address = work_location_address;
	SET @email = email;
	SET @telephone = telephone;
	SET @mobile = mobile;
	SET @location_number = location_number;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_work_location SET WORK_LOCATION = @work_location, WORK_LOCATION_ADDRESS = @work_location_address, EMAIL = @email, TELEPHONE = @telephone, MOBILE = @mobile, LOCATION_NUMBER = @location_number, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE WORK_LOCATION_ID = @work_location_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_work_location_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_work_location_status`(IN work_location_id VARCHAR(50), IN status TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @work_location_id = work_location_id;
	SET @status = status;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_work_location SET STATUS = @status, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE WORK_LOCATION_ID = @work_location_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_zoom_api` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_zoom_api`(IN zoom_api_id INT(50), IN zoom_api_name VARCHAR(100), IN description VARCHAR(200), IN api_key VARCHAR(1000), IN api_secret VARCHAR(1000), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @zoom_api_id = zoom_api_id;
	SET @zoom_api_name = zoom_api_name;
	SET @description = description;
	SET @api_key = api_key;
	SET @api_secret = api_secret;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_zoom_api SET ZOOM_API_NAME = @zoom_api_name, DESCRIPTION = @description, API_KEY = @api_key, API_SECRET = @api_secret, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ZOOM_API_ID = @zoom_api_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_zoom_api_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_zoom_api_status`(IN zoom_api_id INT(50), IN status TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @zoom_api_id = zoom_api_id;
	SET @status = status;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_zoom_api SET STATUS = @status, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ZOOM_API_ID = @zoom_api_id';

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

-- Dump completed on 2023-01-02 14:21:17
