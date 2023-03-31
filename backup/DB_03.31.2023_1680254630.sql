-- MariaDB dump 10.19  Distrib 10.4.27-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: dssdb
-- ------------------------------------------------------
-- Server version	10.4.27-MariaDB

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
-- Table structure for table `employee_address`
--

DROP TABLE IF EXISTS `employee_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_address` (
  `EMPLOYEE_ADDRESSES_ID` varchar(100) NOT NULL,
  `EMPLOYEE_ID` varchar(100) NOT NULL,
  `ADDRESS_TYPE` varchar(20) NOT NULL,
  `STREET_1` varchar(200) DEFAULT NULL,
  `STREET_2` varchar(200) DEFAULT NULL,
  `COUNTRY_ID` int(11) DEFAULT NULL,
  `STATE_ID` int(11) DEFAULT NULL,
  `CITY` varchar(100) DEFAULT NULL,
  `ZIP_CODE` varchar(10) DEFAULT NULL,
  `NOTES` varchar(1000) DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEE_ADDRESSES_ID`),
  KEY `employee_address_index` (`EMPLOYEE_ADDRESSES_ID`),
  KEY `EMPLOYEE_ID` (`EMPLOYEE_ID`),
  CONSTRAINT `employee_address_ibfk_1` FOREIGN KEY (`EMPLOYEE_ID`) REFERENCES `employees` (`EMPLOYEE_ID`),
  CONSTRAINT `employee_address_ibfk_2` FOREIGN KEY (`EMPLOYEE_ID`) REFERENCES `employees` (`EMPLOYEE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_address`
--

LOCK TABLES `employee_address` WRITE;
/*!40000 ALTER TABLE `employee_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_bank_information`
--

DROP TABLE IF EXISTS `employee_bank_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_bank_information` (
  `EMPLOYEE_BANK_INFORMATION_ID` varchar(100) NOT NULL,
  `EMPLOYEE_ID` varchar(100) NOT NULL,
  `BANK_NAME` varchar(500) NOT NULL,
  `ACCOUNT_NUMBER` varchar(50) NOT NULL,
  `ACCOUNT_TYPE` varchar(20) DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEE_BANK_INFORMATION_ID`),
  KEY `employee_bank_information_index` (`EMPLOYEE_BANK_INFORMATION_ID`),
  KEY `EMPLOYEE_ID` (`EMPLOYEE_ID`),
  CONSTRAINT `employee_bank_information_ibfk_1` FOREIGN KEY (`EMPLOYEE_ID`) REFERENCES `employees` (`EMPLOYEE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_bank_information`
--

LOCK TABLES `employee_bank_information` WRITE;
/*!40000 ALTER TABLE `employee_bank_information` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_bank_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_contact_information`
--

DROP TABLE IF EXISTS `employee_contact_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_contact_information` (
  `EMPLOYEE_CONTACT_INFORMATION_ID` varchar(100) NOT NULL,
  `EMPLOYEE_ID` varchar(100) NOT NULL,
  `CONTACT_INFORMATION_TYPE` varchar(20) NOT NULL,
  `EMAIL` varchar(50) DEFAULT NULL,
  `TELEPHONE` varchar(20) DEFAULT NULL,
  `MOBILE` varchar(20) DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEE_CONTACT_INFORMATION_ID`),
  KEY `employee_contact_information_index` (`EMPLOYEE_CONTACT_INFORMATION_ID`),
  KEY `EMPLOYEE_ID` (`EMPLOYEE_ID`),
  CONSTRAINT `employee_contact_information_ibfk_1` FOREIGN KEY (`EMPLOYEE_ID`) REFERENCES `employees` (`EMPLOYEE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_contact_information`
--

LOCK TABLES `employee_contact_information` WRITE;
/*!40000 ALTER TABLE `employee_contact_information` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_contact_information` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_department`
--

LOCK TABLES `employee_department` WRITE;
/*!40000 ALTER TABLE `employee_department` DISABLE KEYS */;
INSERT INTO `employee_department` VALUES ('1','Data Center Department','','',1,'TL-576','INS->ADMIN->2023-03-10 01:12:48');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_departure_reason`
--

LOCK TABLES `employee_departure_reason` WRITE;
/*!40000 ALTER TABLE `employee_departure_reason` DISABLE KEYS */;
INSERT INTO `employee_departure_reason` VALUES ('1','Resigned','TL-664','INS->ADMIN->2023-03-30 11:04:07');
/*!40000 ALTER TABLE `employee_departure_reason` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_educational_background`
--

DROP TABLE IF EXISTS `employee_educational_background`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_educational_background` (
  `EMPLOYEE_EDUCATIONAL_BACKGROUND_ID` varchar(100) NOT NULL,
  `EMPLOYEE_ID` varchar(100) NOT NULL,
  `INSTITUTION_NAME` varchar(500) NOT NULL,
  `DEGREE` varchar(100) DEFAULT NULL,
  `FIELD_OF_STUDY` varchar(200) DEFAULT NULL,
  `START_DATE` date NOT NULL,
  `END_DATE` date NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEE_EDUCATIONAL_BACKGROUND_ID`),
  KEY `employee_educational_background_index` (`EMPLOYEE_EDUCATIONAL_BACKGROUND_ID`),
  KEY `EMPLOYEE_ID` (`EMPLOYEE_ID`),
  CONSTRAINT `employee_educational_background_ibfk_1` FOREIGN KEY (`EMPLOYEE_ID`) REFERENCES `employees` (`EMPLOYEE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_educational_background`
--

LOCK TABLES `employee_educational_background` WRITE;
/*!40000 ALTER TABLE `employee_educational_background` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_educational_background` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_emergency_contacts`
--

DROP TABLE IF EXISTS `employee_emergency_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_emergency_contacts` (
  `EMPLOYEE_EMERGENCY_CONTACT_ID` varchar(100) NOT NULL,
  `EMPLOYEE_ID` varchar(100) NOT NULL,
  `EMERGENCY_CONTACT_NAME` varchar(500) NOT NULL,
  `RELATIONSHIP` varchar(20) NOT NULL,
  `EMAIL` varchar(50) DEFAULT NULL,
  `TELEPHONE` varchar(20) DEFAULT NULL,
  `MOBILE` varchar(20) DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEE_EMERGENCY_CONTACT_ID`),
  KEY `employee_emergency_contacts_index` (`EMPLOYEE_EMERGENCY_CONTACT_ID`),
  KEY `EMPLOYEE_ID` (`EMPLOYEE_ID`),
  CONSTRAINT `employee_emergency_contacts_ibfk_1` FOREIGN KEY (`EMPLOYEE_ID`) REFERENCES `employees` (`EMPLOYEE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_emergency_contacts`
--

LOCK TABLES `employee_emergency_contacts` WRITE;
/*!40000 ALTER TABLE `employee_emergency_contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_emergency_contacts` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_employee_type`
--

LOCK TABLES `employee_employee_type` WRITE;
/*!40000 ALTER TABLE `employee_employee_type` DISABLE KEYS */;
INSERT INTO `employee_employee_type` VALUES ('1','Regular','TL-582','UPD->ADMIN->2023-03-10 02:09:55');
/*!40000 ALTER TABLE `employee_employee_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_employment_history`
--

DROP TABLE IF EXISTS `employee_employment_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_employment_history` (
  `EMPLOYEE_EMPLOYMENT_HISTORY_ID` varchar(100) NOT NULL,
  `EMPLOYEE_ID` varchar(100) NOT NULL,
  `COMPANY_NAME` varchar(500) NOT NULL,
  `JOB_TITLE` varchar(100) NOT NULL,
  `START_DATE` date DEFAULT NULL,
  `END_DATE` date DEFAULT NULL,
  `DESCRIPTION` varchar(1000) DEFAULT NULL,
  `EMPLOYMENT_CERTIFICATE` varchar(500) DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEE_EMPLOYMENT_HISTORY_ID`),
  KEY `employee_employment_history_index` (`EMPLOYEE_EMPLOYMENT_HISTORY_ID`),
  KEY `EMPLOYEE_ID` (`EMPLOYEE_ID`),
  CONSTRAINT `employee_employment_history_ibfk_1` FOREIGN KEY (`EMPLOYEE_ID`) REFERENCES `employees` (`EMPLOYEE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_employment_history`
--

LOCK TABLES `employee_employment_history` WRITE;
/*!40000 ALTER TABLE `employee_employment_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_employment_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_family_details`
--

DROP TABLE IF EXISTS `employee_family_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_family_details` (
  `EMPLOYEE_FAMILY_DETAILS_ID` varchar(100) NOT NULL,
  `EMPLOYEE_ID` varchar(100) NOT NULL,
  `NAME` varchar(500) NOT NULL,
  `RELATIONSHIP` varchar(20) NOT NULL,
  `BIRTHDAY` date DEFAULT NULL,
  `AGE` int(5) DEFAULT NULL,
  `SCHOOL` varchar(100) DEFAULT NULL,
  `EMPLOYMENT` varchar(100) DEFAULT NULL,
  `EMAIL` varchar(50) DEFAULT NULL,
  `TELEPHONE` varchar(20) DEFAULT NULL,
  `MOBILE` varchar(20) DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEE_FAMILY_DETAILS_ID`),
  KEY `employee_family_details_index` (`EMPLOYEE_FAMILY_DETAILS_ID`),
  KEY `EMPLOYEE_ID` (`EMPLOYEE_ID`),
  CONSTRAINT `employee_family_details_ibfk_1` FOREIGN KEY (`EMPLOYEE_ID`) REFERENCES `employees` (`EMPLOYEE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_family_details`
--

LOCK TABLES `employee_family_details` WRITE;
/*!40000 ALTER TABLE `employee_family_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_family_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_id_type`
--

DROP TABLE IF EXISTS `employee_id_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_id_type` (
  `ID_TYPE_ID` varchar(50) NOT NULL,
  `ID_TYPE` varchar(100) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID_TYPE_ID`),
  KEY `employee_id_type_index` (`ID_TYPE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_id_type`
--

LOCK TABLES `employee_id_type` WRITE;
/*!40000 ALTER TABLE `employee_id_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_id_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_identification`
--

DROP TABLE IF EXISTS `employee_identification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_identification` (
  `EMPLOYEE_IDENTIFICATION_ID` varchar(100) NOT NULL,
  `EMPLOYEE_ID` varchar(100) NOT NULL,
  `ID_TYPE` varchar(100) NOT NULL,
  `ID_NUMBER` varchar(100) NOT NULL,
  `ID_CLASSIFICATION` varchar(20) NOT NULL,
  `ID_IMAGE` varchar(500) DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEE_IDENTIFICATION_ID`),
  KEY `employee_identification_index` (`EMPLOYEE_IDENTIFICATION_ID`),
  KEY `EMPLOYEE_ID` (`EMPLOYEE_ID`),
  CONSTRAINT `employee_identification_ibfk_1` FOREIGN KEY (`EMPLOYEE_ID`) REFERENCES `employees` (`EMPLOYEE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_identification`
--

LOCK TABLES `employee_identification` WRITE;
/*!40000 ALTER TABLE `employee_identification` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_identification` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_job_position`
--

LOCK TABLES `employee_job_position` WRITE;
/*!40000 ALTER TABLE `employee_job_position` DISABLE KEYS */;
INSERT INTO `employee_job_position` VALUES ('1','Data Center Staff','Data Center Staff',2,'1',0,'TL-577','UPD->ADMIN->2023-03-21 03:53:31');
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
  KEY `employee_job_position_attachment_index` (`ATTACHMENT_ID`),
  KEY `JOB_POSITION_ID` (`JOB_POSITION_ID`),
  CONSTRAINT `employee_job_position_attachment_ibfk_1` FOREIGN KEY (`JOB_POSITION_ID`) REFERENCES `employee_job_position` (`JOB_POSITION_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_job_position_attachment`
--

LOCK TABLES `employee_job_position_attachment` WRITE;
/*!40000 ALTER TABLE `employee_job_position_attachment` DISABLE KEYS */;
INSERT INTO `employee_job_position_attachment` VALUES ('13','1','asd','./assets/employee/job_position_attachment/KKIRGXZGH964197de2c64694.91912787.pdf','TL-661','INS->ADMIN->2023-03-21 05:50:26'),('15','1','sadasd','./assets/employee/job_position_attachment/NJOYKIZK1Y641bbe62ecc6a7.87166234.pdf','TL-663','INS->ADMIN->2023-03-23 10:50:10');
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
  KEY `employee_job_position_qualification_index` (`QUALIFICATION_ID`),
  KEY `JOB_POSITION_ID` (`JOB_POSITION_ID`),
  CONSTRAINT `employee_job_position_qualification_ibfk_1` FOREIGN KEY (`JOB_POSITION_ID`) REFERENCES `employee_job_position` (`JOB_POSITION_ID`),
  CONSTRAINT `employee_job_position_qualification_ibfk_2` FOREIGN KEY (`JOB_POSITION_ID`) REFERENCES `employee_job_position` (`JOB_POSITION_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
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
  KEY `employee_job_position_requirement_index` (`REQUIREMENT_ID`),
  KEY `JOB_POSITION_ID` (`JOB_POSITION_ID`),
  CONSTRAINT `employee_job_position_requirement_ibfk_1` FOREIGN KEY (`JOB_POSITION_ID`) REFERENCES `employee_job_position` (`JOB_POSITION_ID`),
  CONSTRAINT `employee_job_position_requirement_ibfk_2` FOREIGN KEY (`JOB_POSITION_ID`) REFERENCES `employee_job_position` (`JOB_POSITION_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
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
  KEY `employee_job_position_responsibility_index` (`RESPONSIBILITY_ID`),
  KEY `JOB_POSITION_ID` (`JOB_POSITION_ID`),
  CONSTRAINT `employee_job_position_responsibility_ibfk_1` FOREIGN KEY (`JOB_POSITION_ID`) REFERENCES `employee_job_position` (`JOB_POSITION_ID`),
  CONSTRAINT `employee_job_position_responsibility_ibfk_2` FOREIGN KEY (`JOB_POSITION_ID`) REFERENCES `employee_job_position` (`JOB_POSITION_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_job_position_responsibility`
--

LOCK TABLES `employee_job_position_responsibility` WRITE;
/*!40000 ALTER TABLE `employee_job_position_responsibility` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_job_position_responsibility` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_personal_information`
--

DROP TABLE IF EXISTS `employee_personal_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_personal_information` (
  `EMPLOYEE_ID` varchar(100) NOT NULL,
  `FILE_AS` varchar(350) NOT NULL,
  `FIRST_NAME` varchar(100) NOT NULL,
  `MIDDLE_NAME` varchar(100) NOT NULL,
  `LAST_NAME` varchar(100) NOT NULL,
  `SUFFIX` varchar(20) DEFAULT NULL,
  `NICKNAME` varchar(20) DEFAULT NULL,
  `CIVIL_STATUS` varchar(20) DEFAULT NULL,
  `NATIONALITY` varchar(20) DEFAULT NULL,
  `GENDER` varchar(20) DEFAULT NULL,
  `BIRTHDAY` date DEFAULT NULL,
  `PLACE_OF_BIRTH` varchar(500) DEFAULT NULL,
  `BLOOD_TYPE` varchar(20) DEFAULT NULL,
  `HEIGHT` double DEFAULT NULL,
  `WEIGHT` double DEFAULT NULL,
  `RELIGION` varchar(20) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  KEY `employee_personal_information_index` (`EMPLOYEE_ID`),
  CONSTRAINT `employee_personal_information_ibfk_1` FOREIGN KEY (`EMPLOYEE_ID`) REFERENCES `employees` (`EMPLOYEE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_personal_information`
--

LOCK TABLES `employee_personal_information` WRITE;
/*!40000 ALTER TABLE `employee_personal_information` DISABLE KEYS */;
INSERT INTO `employee_personal_information` VALUES ('6','Agulto, Lawrence, De Vera, II','Lawrence','De Vera','Agulto','II','test','DIVORCED','AFGHAN','MALE','2023-03-08','asdasd','AB+',28,20,'CHRISTIANITY','UPD->ADMIN->2023-03-30 11:46:52');
/*!40000 ALTER TABLE `employee_personal_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_training_seminars`
--

DROP TABLE IF EXISTS `employee_training_seminars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_training_seminars` (
  `EMPLOYEE_TRAINING_SEMINARS_ID` varchar(100) NOT NULL,
  `EMPLOYEE_ID` varchar(100) NOT NULL,
  `TRAINING_NAME` varchar(200) NOT NULL,
  `TRAINING_DATE` date NOT NULL,
  `TRAINING_LOCATION` varchar(100) NOT NULL,
  `TRAINING_PROVIDER` varchar(100) NOT NULL,
  `DESCRIPTION` varchar(1000) NOT NULL,
  `TRAINING_CERTIFICATE` varchar(500) DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEE_TRAINING_SEMINARS_ID`),
  KEY `employee_training_seminars_index` (`EMPLOYEE_TRAINING_SEMINARS_ID`),
  KEY `EMPLOYEE_ID` (`EMPLOYEE_ID`),
  CONSTRAINT `employee_training_seminars_ibfk_1` FOREIGN KEY (`EMPLOYEE_ID`) REFERENCES `employees` (`EMPLOYEE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_training_seminars`
--

LOCK TABLES `employee_training_seminars` WRITE;
/*!40000 ALTER TABLE `employee_training_seminars` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_training_seminars` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_wage_type`
--

LOCK TABLES `employee_wage_type` WRITE;
/*!40000 ALTER TABLE `employee_wage_type` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_work_location`
--

LOCK TABLES `employee_work_location` WRITE;
/*!40000 ALTER TABLE `employee_work_location` DISABLE KEYS */;
INSERT INTO `employee_work_location` VALUES ('1','Head Office','Head Office','','','',1,1,'TL-579','INS->ADMIN->2023-03-10 01:29:44');
/*!40000 ALTER TABLE `employee_work_location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_working_hours`
--

DROP TABLE IF EXISTS `employee_working_hours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_working_hours` (
  `WORKING_HOURS_ID` varchar(100) NOT NULL,
  `WORKING_SCHEDULE_ID` varchar(100) NOT NULL,
  `WORKING_HOURS` varchar(100) NOT NULL,
  `WORKING_DATE` date DEFAULT NULL,
  `DAY_OF_WEEK` varchar(20) NOT NULL,
  `DAY_PERIOD` varchar(20) NOT NULL,
  `WORK_FROM` time NOT NULL,
  `WORK_TO` time NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`WORKING_HOURS_ID`),
  KEY `WORKING_SCHEDULE_ID` (`WORKING_SCHEDULE_ID`),
  KEY `employee_working_hours_index` (`WORKING_HOURS_ID`),
  CONSTRAINT `employee_working_hours_ibfk_1` FOREIGN KEY (`WORKING_SCHEDULE_ID`) REFERENCES `employee_working_schedule` (`WORKING_SCHEDULE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_working_hours`
--

LOCK TABLES `employee_working_hours` WRITE;
/*!40000 ALTER TABLE `employee_working_hours` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_working_hours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_working_schedule`
--

DROP TABLE IF EXISTS `employee_working_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_working_schedule` (
  `WORKING_SCHEDULE_ID` varchar(100) NOT NULL,
  `WORKING_SCHEDULE` varchar(100) NOT NULL,
  `WORKING_SCHEDULE_TYPE` varchar(20) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`WORKING_SCHEDULE_ID`),
  KEY `employee_working_schedule_index` (`WORKING_SCHEDULE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_working_schedule`
--

LOCK TABLES `employee_working_schedule` WRITE;
/*!40000 ALTER TABLE `employee_working_schedule` DISABLE KEYS */;
INSERT INTO `employee_working_schedule` VALUES ('1','Regular','1','TL-581','INS->ADMIN->2023-03-10 01:30:43');
/*!40000 ALTER TABLE `employee_working_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_working_schedule_type`
--

DROP TABLE IF EXISTS `employee_working_schedule_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_working_schedule_type` (
  `WORKING_SCHEDULE_TYPE_ID` varchar(50) NOT NULL,
  `WORKING_SCHEDULE_TYPE` varchar(100) NOT NULL,
  `WORKING_SCHEDULE_TYPE_CATEGORY` varchar(20) NOT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`WORKING_SCHEDULE_TYPE_ID`),
  KEY `employee_working_schedule_type_index` (`WORKING_SCHEDULE_TYPE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_working_schedule_type`
--

LOCK TABLES `employee_working_schedule_type` WRITE;
/*!40000 ALTER TABLE `employee_working_schedule_type` DISABLE KEYS */;
INSERT INTO `employee_working_schedule_type` VALUES ('1','Regular','FIXED','TL-580','INS->ADMIN->2023-03-10 01:30:33');
/*!40000 ALTER TABLE `employee_working_schedule_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `EMPLOYEE_ID` varchar(100) NOT NULL,
  `USERNAME` varchar(50) DEFAULT NULL,
  `BADGE_ID` varchar(100) NOT NULL,
  `EMPLOYEE_IMAGE` varchar(500) DEFAULT NULL,
  `EMPLOYEE_DIGITAL_SIGNATURE` varchar(500) DEFAULT NULL,
  `COMPANY` varchar(50) DEFAULT NULL,
  `JOB_POSITION` varchar(50) NOT NULL,
  `DEPARTMENT` varchar(50) NOT NULL,
  `WORK_LOCATION` varchar(50) NOT NULL,
  `WORKING_HOURS` varchar(50) NOT NULL,
  `MANAGER` varchar(100) DEFAULT NULL,
  `COACH` varchar(100) DEFAULT NULL,
  `EMPLOYEE_TYPE` varchar(100) NOT NULL,
  `EMPLOYEE_STATUS` tinyint(1) NOT NULL,
  `PERMANENCY_DATE` date DEFAULT NULL,
  `ONBOARD_DATE` date NOT NULL,
  `OFFBOARD_DATE` date DEFAULT NULL,
  `DEPARTURE_REASON` varchar(50) DEFAULT NULL,
  `DETAILED_REASON` varchar(500) DEFAULT NULL,
  `TRANSACTION_LOG_ID` varchar(100) DEFAULT NULL,
  `RECORD_LOG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEE_ID`),
  KEY `employees_index` (`EMPLOYEE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES ('1',NULL,'Test',NULL,NULL,'1','1','1','1','1','','','1',1,NULL,'2023-03-21',NULL,NULL,NULL,'TL-585','INS->ADMIN->2023-03-10 02:10:01'),('2',NULL,'asd',NULL,NULL,'1','1','1','1','1','','','1',1,NULL,'2023-03-21',NULL,NULL,NULL,'TL-587','INS->ADMIN->2023-03-10 02:17:10'),('3',NULL,'asd',NULL,NULL,'1','1','1','1','1','','','1',1,NULL,'2023-03-21',NULL,NULL,NULL,'TL-588','INS->ADMIN->2023-03-10 02:18:10'),('4',NULL,'asd',NULL,NULL,'1','1','1','1','1','','','1',1,NULL,'2023-03-21',NULL,NULL,NULL,'TL-589','INS->ADMIN->2023-03-10 02:19:20'),('5',NULL,'asd',NULL,NULL,'1','1','1','1','1','','','1',1,NULL,'2023-03-21',NULL,NULL,NULL,'TL-590','INS->ADMIN->2023-03-10 02:22:15'),('6',NULL,'asd','./assets/employee/employee_image/6243UZ596J6424f491cecad4.02635919.png','./assets/employee/digital_signature/GQM74L0IJ76424fad91d0467.25858990.png','1','1','1','1','1','6','6','1',1,'2023-03-15','2023-03-21',NULL,NULL,NULL,'TL-591','UPD->ADMIN->2023-03-30 12:43:40');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_company`
--

LOCK TABLES `global_company` WRITE;
/*!40000 ALTER TABLE `global_company` DISABLE KEYS */;
INSERT INTO `global_company` VALUES ('1','Encore Leasing and Finance Corp.',NULL,'','','','','','','TL-578','INS->ADMIN->2023-03-10 01:24:19');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_country`
--

LOCK TABLES `global_country` WRITE;
/*!40000 ALTER TABLE `global_country` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_email_setting`
--

LOCK TABLES `global_email_setting` WRITE;
/*!40000 ALTER TABLE `global_email_setting` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_interface_setting`
--

LOCK TABLES `global_interface_setting` WRITE;
/*!40000 ALTER TABLE `global_interface_setting` DISABLE KEYS */;
INSERT INTO `global_interface_setting` VALUES (1,'Encore Leasing and Finance Corp.','Interface setting for Encore Leasing and Finance Corp.',2,NULL,NULL,NULL,NULL,'TL-535','UPD->ADMIN->2023-03-09 03:50:11');
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
  `CHANNEL` varchar(20) NOT NULL,
  KEY `NOTIFICATION_SETTING_ID` (`NOTIFICATION_SETTING_ID`),
  CONSTRAINT `global_notification_channel_ibfk_1` FOREIGN KEY (`NOTIFICATION_SETTING_ID`) REFERENCES `global_notification_setting` (`NOTIFICATION_SETTING_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_notification_channel`
--

LOCK TABLES `global_notification_channel` WRITE;
/*!40000 ALTER TABLE `global_notification_channel` DISABLE KEYS */;
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
  `ROLE_ID` varchar(50) NOT NULL,
  KEY `NOTIFICATION_SETTING_ID` (`NOTIFICATION_SETTING_ID`),
  CONSTRAINT `global_notification_role_recipient_ibfk_1` FOREIGN KEY (`NOTIFICATION_SETTING_ID`) REFERENCES `global_notification_setting` (`NOTIFICATION_SETTING_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_notification_setting`
--

LOCK TABLES `global_notification_setting` WRITE;
/*!40000 ALTER TABLE `global_notification_setting` DISABLE KEYS */;
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
  `USERNAME` varchar(50) NOT NULL,
  KEY `NOTIFICATION_SETTING_ID` (`NOTIFICATION_SETTING_ID`),
  CONSTRAINT `global_notification_user_account_recipient_ibfk_1` FOREIGN KEY (`NOTIFICATION_SETTING_ID`) REFERENCES `global_notification_setting` (`NOTIFICATION_SETTING_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_notification_user_account_recipient`
--

LOCK TABLES `global_notification_user_account_recipient` WRITE;
/*!40000 ALTER TABLE `global_notification_user_account_recipient` DISABLE KEYS */;
/*!40000 ALTER TABLE `global_notification_user_account_recipient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_password_history`
--

DROP TABLE IF EXISTS `global_password_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_password_history` (
  `USERNAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(200) NOT NULL,
  KEY `global_password_history_index` (`USERNAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_password_history`
--

LOCK TABLES `global_password_history` WRITE;
/*!40000 ALTER TABLE `global_password_history` DISABLE KEYS */;
INSERT INTO `global_password_history` VALUES ('LDAGULTO','epDqiHZKCYMYGdBujdCTypawAuctHHcMNfKaL6IuqPE='),('ADMIN','epDqiHZKCYMYGdBujdCTypawAuctHHcMNfKaL6IuqPE=');
/*!40000 ALTER TABLE `global_password_history` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_role`
--

LOCK TABLES `global_role` WRITE;
/*!40000 ALTER TABLE `global_role` DISABLE KEYS */;
INSERT INTO `global_role` VALUES ('1','Administrator','Administrator',2,'TL-2','UPD->ADMIN->2023-02-15 03:54:29');
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
  `USERNAME` varchar(50) NOT NULL,
  KEY `ROLE_ID` (`ROLE_ID`),
  CONSTRAINT `global_role_user_account_ibfk_1` FOREIGN KEY (`ROLE_ID`) REFERENCES `global_role` (`ROLE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
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
  KEY `global_state_index` (`STATE_ID`),
  KEY `COUNTRY_ID` (`COUNTRY_ID`),
  CONSTRAINT `global_state_ibfk_1` FOREIGN KEY (`COUNTRY_ID`) REFERENCES `global_country` (`COUNTRY_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_state`
--

LOCK TABLES `global_state` WRITE;
/*!40000 ALTER TABLE `global_state` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_system_code`
--

LOCK TABLES `global_system_code` WRITE;
/*!40000 ALTER TABLE `global_system_code` DISABLE KEYS */;
INSERT INTO `global_system_code` VALUES ('1','SYSTYPE','SYSTYPE','System Code','TL-4',NULL),('10','FILETYPE','mp4','Video (.mp4)','77','INS->ADMIN->2022-12-07 11:55:59'),('100','NATIONALITY','CAPE VERDEAN','Cape Verdean','','INS->ADMIN->2023-02-07 10:58:51'),('101','NATIONALITY','CAYMAN ISLANDER','Cayman Islander','','INS->ADMIN->2023-02-07 10:58:51'),('102','NATIONALITY','CENTRAL AFRICAN','Central African','','INS->ADMIN->2023-02-07 10:58:51'),('103','NATIONALITY','CHADIAN','Chadian','','INS->ADMIN->2023-02-07 10:58:51'),('104','NATIONALITY','CHILEAN','Chilean','','INS->ADMIN->2023-02-07 10:58:51'),('105','NATIONALITY','CHINESE','Chinese','','INS->ADMIN->2023-02-07 10:58:51'),('106','NATIONALITY','CITIZEN OF ANTIGUA A','Citizen of Antigua and Barbuda','','INS->ADMIN->2023-02-07 10:58:51'),('107','NATIONALITY','CITIZEN OF BOSNIA AN','Citizen of Bosnia and Herzegovina','','INS->ADMIN->2023-02-07 10:58:51'),('108','NATIONALITY','CITIZEN OF GUINEA-BI','Citizen of Guinea-Bissau','','INS->ADMIN->2023-02-07 10:58:51'),('109','NATIONALITY','CITIZEN OF KIRIBATI','Citizen of Kiribati','','INS->ADMIN->2023-02-07 10:58:51'),('11','FILETYPE','mkv','Video (.mkv)','78','INS->ADMIN->2022-12-07 11:56:17'),('110','NATIONALITY','CITIZEN OF SEYCHELLE','Citizen of Seychelles','','INS->ADMIN->2023-02-07 10:58:51'),('111','NATIONALITY','CITIZEN OF THE DOMIN','Citizen of the Dominican Republic','','INS->ADMIN->2023-02-07 10:58:51'),('112','NATIONALITY','CITIZEN OF VANUATU ','Citizen of Vanuatu ','','INS->ADMIN->2023-02-07 10:58:51'),('113','NATIONALITY','COLOMBIAN','Colombian','','INS->ADMIN->2023-02-07 10:58:51'),('114','NATIONALITY','COMORAN','Comoran','','INS->ADMIN->2023-02-07 10:58:51'),('115','NATIONALITY','CONGOLESE (CONGO)','Congolese (Congo)','','INS->ADMIN->2023-02-07 10:58:51'),('116','NATIONALITY','CONGOLESE (DRC)','Congolese (DRC)','','INS->ADMIN->2023-02-07 10:58:51'),('117','NATIONALITY','COOK ISLANDER','Cook Islander','','INS->ADMIN->2023-02-07 10:58:51'),('118','NATIONALITY','COSTA RICAN','Costa Rican','','INS->ADMIN->2023-02-07 10:58:51'),('119','NATIONALITY','CROATIAN','Croatian','','INS->ADMIN->2023-02-07 10:58:51'),('12','FILETYPE','mov','Video (.mov)','79','INS->ADMIN->2022-12-07 11:56:36'),('120','NATIONALITY','CUBAN','Cuban','','INS->ADMIN->2023-02-07 10:58:51'),('121','NATIONALITY','CYMRAES','Cymraes','','INS->ADMIN->2023-02-07 10:58:51'),('122','NATIONALITY','CYMRO','Cymro','','INS->ADMIN->2023-02-07 10:58:51'),('123','NATIONALITY','CYPRIOT','Cypriot','','INS->ADMIN->2023-02-07 10:58:51'),('124','NATIONALITY','CZECH','Czech','','INS->ADMIN->2023-02-07 10:58:51'),('125','NATIONALITY','DANISH','Danish','','INS->ADMIN->2023-02-07 10:58:51'),('126','NATIONALITY','DJIBOUTIAN','Djiboutian','','INS->ADMIN->2023-02-07 10:58:51'),('127','NATIONALITY','DOMINICAN','Dominican','','INS->ADMIN->2023-02-07 10:58:51'),('128','NATIONALITY','DUTCH','Dutch','','INS->ADMIN->2023-02-07 10:58:51'),('129','NATIONALITY','EAST TIMORESE','East Timorese','','INS->ADMIN->2023-02-07 10:58:51'),('13','FILETYPE','mpg','Video (.mpg)','80','INS->ADMIN->2022-12-07 11:56:50'),('130','NATIONALITY','ECUADOREAN','Ecuadorean','','INS->ADMIN->2023-02-07 10:58:51'),('131','NATIONALITY','EGYPTIAN','Egyptian','','INS->ADMIN->2023-02-07 10:58:51'),('132','NATIONALITY','EMIRATI','Emirati','','INS->ADMIN->2023-02-07 10:58:51'),('133','NATIONALITY','ENGLISH','English','','INS->ADMIN->2023-02-07 10:58:51'),('134','NATIONALITY','EQUATORIAL GUINEAN','Equatorial Guinean','','INS->ADMIN->2023-02-07 10:58:51'),('135','NATIONALITY','ERITREAN','Eritrean','','INS->ADMIN->2023-02-07 10:58:51'),('136','NATIONALITY','ESTONIAN','Estonian','','INS->ADMIN->2023-02-07 10:58:51'),('137','NATIONALITY','ETHIOPIAN','Ethiopian','','INS->ADMIN->2023-02-07 10:58:51'),('138','NATIONALITY','FAROESE','Faroese','','INS->ADMIN->2023-02-07 10:58:51'),('139','NATIONALITY','FIJIAN','Fijian','','INS->ADMIN->2023-02-07 10:58:51'),('14','FILETYPE','mpeg','Video (.mpeg)','81','INS->ADMIN->2022-12-07 11:57:00'),('140','NATIONALITY','FILIPINO','Filipino','','INS->ADMIN->2023-02-07 10:58:51'),('141','NATIONALITY','FINNISH','Finnish','','INS->ADMIN->2023-02-07 10:58:51'),('142','NATIONALITY','FRENCH','French','','INS->ADMIN->2023-02-07 10:58:51'),('143','NATIONALITY','GABONESE','Gabonese','','INS->ADMIN->2023-02-07 10:58:51'),('144','NATIONALITY','GAMBIAN','Gambian','','INS->ADMIN->2023-02-07 10:58:51'),('145','NATIONALITY','GEORGIAN','Georgian','','INS->ADMIN->2023-02-07 10:58:51'),('146','NATIONALITY','GERMAN','German','','INS->ADMIN->2023-02-07 10:58:51'),('147','NATIONALITY','GHANAIAN','Ghanaian','','INS->ADMIN->2023-02-07 10:58:51'),('148','NATIONALITY','GIBRALTARIAN','Gibraltarian','','INS->ADMIN->2023-02-07 10:58:51'),('149','NATIONALITY','GREEK','Greek','','INS->ADMIN->2023-02-07 10:58:51'),('15','FILETYPE','avi','Video (.avi)','82','INS->ADMIN->2022-12-07 11:57:13'),('150','NATIONALITY','GREENLANDIC','Greenlandic','','INS->ADMIN->2023-02-07 10:58:51'),('151','NATIONALITY','GRENADIAN','Grenadian','','INS->ADMIN->2023-02-07 10:58:51'),('152','NATIONALITY','GUAMANIAN','Guamanian','','INS->ADMIN->2023-02-07 10:58:51'),('153','NATIONALITY','GUATEMALAN','Guatemalan','','INS->ADMIN->2023-02-07 10:58:51'),('154','NATIONALITY','GUINEAN','Guinean','','INS->ADMIN->2023-02-07 10:58:51'),('155','NATIONALITY','GUYANESE','Guyanese','','INS->ADMIN->2023-02-07 10:58:51'),('156','NATIONALITY','HAITIAN','Haitian','','INS->ADMIN->2023-02-07 10:58:51'),('157','NATIONALITY','HONDURAN','Honduran','','INS->ADMIN->2023-02-07 10:58:51'),('158','NATIONALITY','HONG KONGER','Hong Konger','','INS->ADMIN->2023-02-07 10:58:51'),('159','NATIONALITY','HUNGARIAN','Hungarian','','INS->ADMIN->2023-02-07 10:58:51'),('16','FILETYPE','flv','Video (.flv)','83','INS->ADMIN->2022-12-07 11:57:23'),('160','NATIONALITY','ICELANDIC','Icelandic','','INS->ADMIN->2023-02-07 10:58:51'),('161','NATIONALITY','INDIAN','Indian','','INS->ADMIN->2023-02-07 10:58:51'),('162','NATIONALITY','INDONESIAN','Indonesian','','INS->ADMIN->2023-02-07 10:58:51'),('163','NATIONALITY','IRANIAN','Iranian','','INS->ADMIN->2023-02-07 10:58:51'),('164','NATIONALITY','IRAQI','Iraqi','','INS->ADMIN->2023-02-07 10:58:51'),('165','NATIONALITY','IRISH','Irish','','INS->ADMIN->2023-02-07 10:58:51'),('166','NATIONALITY','ISRAELI','Israeli','','INS->ADMIN->2023-02-07 10:58:51'),('167','NATIONALITY','ITALIAN','Italian','','INS->ADMIN->2023-02-07 10:58:51'),('168','NATIONALITY','IVORIAN','Ivorian','','INS->ADMIN->2023-02-07 10:58:51'),('169','NATIONALITY','JAMAICAN','Jamaican','','INS->ADMIN->2023-02-07 10:58:51'),('17','FILETYPE','wmv','Video (.wmv)','84','INS->ADMIN->2022-12-07 11:57:36'),('170','NATIONALITY','JAPANESE','Japanese','','INS->ADMIN->2023-02-07 10:58:51'),('171','NATIONALITY','JORDANIAN','Jordanian','','INS->ADMIN->2023-02-07 10:58:51'),('172','NATIONALITY','KAZAKH','Kazakh','','INS->ADMIN->2023-02-07 10:58:51'),('173','NATIONALITY','KENYAN','Kenyan','','INS->ADMIN->2023-02-07 10:58:51'),('174','NATIONALITY','KITTITIAN','Kittitian','','INS->ADMIN->2023-02-07 10:58:51'),('175','NATIONALITY','KOSOVAN','Kosovan','','INS->ADMIN->2023-02-07 10:58:51'),('176','NATIONALITY','KUWAITI','Kuwaiti','','INS->ADMIN->2023-02-07 10:58:51'),('177','NATIONALITY','KYRGYZ','Kyrgyz','','INS->ADMIN->2023-02-07 10:58:51'),('178','NATIONALITY','LAO','Lao','','INS->ADMIN->2023-02-07 10:58:51'),('179','NATIONALITY','LATVIAN','Latvian','','INS->ADMIN->2023-02-07 10:58:51'),('18','FILETYPE','gif','Image (.gif)','85','INS->ADMIN->2022-12-07 11:57:49'),('180','NATIONALITY','LEBANESE','Lebanese','','INS->ADMIN->2023-02-07 10:58:51'),('181','NATIONALITY','LIBERIAN','Liberian','','INS->ADMIN->2023-02-07 10:58:51'),('182','NATIONALITY','LIBYAN','Libyan','','INS->ADMIN->2023-02-07 10:58:51'),('183','NATIONALITY','LIECHTENSTEIN CITIZE','Liechtenstein citizen','','INS->ADMIN->2023-02-07 10:58:51'),('184','NATIONALITY','LITHUANIAN','Lithuanian','','INS->ADMIN->2023-02-07 10:58:51'),('185','NATIONALITY','LUXEMBOURGER','Luxembourger','','INS->ADMIN->2023-02-07 10:58:51'),('186','NATIONALITY','MACANESE','Macanese','','INS->ADMIN->2023-02-07 10:58:51'),('187','NATIONALITY','MACEDONIAN','Macedonian','','INS->ADMIN->2023-02-07 10:58:51'),('188','NATIONALITY','MALAGASY','Malagasy','','INS->ADMIN->2023-02-07 10:58:51'),('189','NATIONALITY','MALAWIAN','Malawian','','INS->ADMIN->2023-02-07 10:58:51'),('19','FILETYPE','wav','Audio (.wav)','86','INS->ADMIN->2022-12-07 11:58:05'),('190','NATIONALITY','MALAYSIAN','Malaysian','','INS->ADMIN->2023-02-07 10:58:51'),('191','NATIONALITY','MALDIVIAN','Maldivian','','INS->ADMIN->2023-02-07 10:58:51'),('192','NATIONALITY','MALIAN','Malian','','INS->ADMIN->2023-02-07 10:58:51'),('193','NATIONALITY','MALTESE','Maltese','','INS->ADMIN->2023-02-07 10:58:51'),('194','NATIONALITY','MARSHALLESE','Marshallese','','INS->ADMIN->2023-02-07 10:58:51'),('195','NATIONALITY','MARTINIQUAIS','Martiniquais','','INS->ADMIN->2023-02-07 10:58:51'),('196','NATIONALITY','MAURITANIAN','Mauritanian','','INS->ADMIN->2023-02-07 10:58:51'),('197','NATIONALITY','MAURITIAN','Mauritian','','INS->ADMIN->2023-02-07 10:58:51'),('198','NATIONALITY','MEXICAN','Mexican','','INS->ADMIN->2023-02-07 10:58:51'),('199','NATIONALITY','MICRONESIAN','Micronesian','','INS->ADMIN->2023-02-07 10:58:51'),('2','SYSTYPE','MODULECAT','Module Category','TL-5',NULL),('20','FILETYPE','doc','Word (.doc)','87','INS->ADMIN->2022-12-07 11:58:16'),('200','NATIONALITY','MOLDOVAN','Moldovan','','INS->ADMIN->2023-02-07 10:58:51'),('201','NATIONALITY','MONEGASQUE','Monegasque','','INS->ADMIN->2023-02-07 10:58:51'),('202','NATIONALITY','MONGOLIAN','Mongolian','','INS->ADMIN->2023-02-07 10:58:51'),('203','NATIONALITY','MONTENEGRIN','Montenegrin','','INS->ADMIN->2023-02-07 10:58:51'),('204','NATIONALITY','MONTSERRATIAN','Montserratian','','INS->ADMIN->2023-02-07 10:58:51'),('205','NATIONALITY','MOROCCAN','Moroccan','','INS->ADMIN->2023-02-07 10:58:51'),('206','NATIONALITY','MOSOTHO','Mosotho','','INS->ADMIN->2023-02-07 10:58:51'),('207','NATIONALITY','MOZAMBICAN','Mozambican','','INS->ADMIN->2023-02-07 10:58:51'),('208','NATIONALITY','NAMIBIAN','Namibian','','INS->ADMIN->2023-02-07 10:58:51'),('209','NATIONALITY','NAURUAN','Nauruan','','INS->ADMIN->2023-02-07 10:58:51'),('21','FILETYPE','docx','Word (.docx)','88','INS->ADMIN->2022-12-07 11:58:27'),('210','NATIONALITY','NEPALESE','Nepalese','','INS->ADMIN->2023-02-07 10:58:51'),('211','NATIONALITY','NEW ZEALANDER','New Zealander','','INS->ADMIN->2023-02-07 10:58:51'),('212','NATIONALITY','NICARAGUAN','Nicaraguan','','INS->ADMIN->2023-02-07 10:58:51'),('213','NATIONALITY','NIGERIAN','Nigerian','','INS->ADMIN->2023-02-07 10:58:51'),('214','NATIONALITY','NIGERIEN','Nigerien','','INS->ADMIN->2023-02-07 10:58:51'),('215','NATIONALITY','NIUEAN','Niuean','','INS->ADMIN->2023-02-07 10:58:51'),('216','NATIONALITY','NORTH KOREAN','North Korean','','INS->ADMIN->2023-02-07 10:58:51'),('217','NATIONALITY','NORTHERN IRISH','Northern Irish','','INS->ADMIN->2023-02-07 10:58:51'),('218','NATIONALITY','NORWEGIAN','Norwegian','','INS->ADMIN->2023-02-07 10:58:51'),('219','NATIONALITY','OMANI','Omani','','INS->ADMIN->2023-02-07 10:58:51'),('22','FILETYPE','xls','Excel (.xls)','89','INS->ADMIN->2022-12-07 11:58:38'),('220','NATIONALITY','PAKISTANI','Pakistani','','INS->ADMIN->2023-02-07 10:58:51'),('221','NATIONALITY','PALAUAN','Palauan','','INS->ADMIN->2023-02-07 10:58:51'),('222','NATIONALITY','PALESTINIAN','Palestinian','','INS->ADMIN->2023-02-07 10:58:51'),('223','NATIONALITY','PANAMANIAN','Panamanian','','INS->ADMIN->2023-02-07 10:58:51'),('224','NATIONALITY','PAPUA NEW GUINEAN','Papua New Guinean','','INS->ADMIN->2023-02-07 10:58:51'),('225','NATIONALITY','PARAGUAYAN','Paraguayan','','INS->ADMIN->2023-02-07 10:58:51'),('226','NATIONALITY','PERUVIAN','Peruvian','','INS->ADMIN->2023-02-07 10:58:51'),('227','NATIONALITY','PITCAIRN ISLANDER','Pitcairn Islander','','INS->ADMIN->2023-02-07 10:58:51'),('228','NATIONALITY','POLISH','Polish','','INS->ADMIN->2023-02-07 10:58:51'),('229','NATIONALITY','PORTUGUESE','Portuguese','','INS->ADMIN->2023-02-07 10:58:51'),('23','FILETYPE','xlsx','Excel (.xlsx)','90','INS->ADMIN->2022-12-07 11:58:50'),('230','NATIONALITY','PRYDEINIG','Prydeinig','','INS->ADMIN->2023-02-07 10:58:51'),('231','NATIONALITY','PUERTO RICAN','Puerto Rican','','INS->ADMIN->2023-02-07 10:58:51'),('232','NATIONALITY','QATARI','Qatari','','INS->ADMIN->2023-02-07 10:58:51'),('233','NATIONALITY','ROMANIAN','Romanian','','INS->ADMIN->2023-02-07 10:58:51'),('234','NATIONALITY','RUSSIAN','Russian','','INS->ADMIN->2023-02-07 10:58:51'),('235','NATIONALITY','RWANDAN','Rwandan','','INS->ADMIN->2023-02-07 10:58:51'),('236','NATIONALITY','SALVADOREAN','Salvadorean','','INS->ADMIN->2023-02-07 10:58:51'),('237','NATIONALITY','SAMMARINESE','Sammarinese','','INS->ADMIN->2023-02-07 10:58:51'),('238','NATIONALITY','SAMOAN','Samoan','','INS->ADMIN->2023-02-07 10:58:51'),('239','NATIONALITY','SAO TOMEAN','Sao Tomean','','INS->ADMIN->2023-02-07 10:58:51'),('24','FILETYPE','ppt','Powerpoint (.ppt)','91','INS->ADMIN->2022-12-07 11:59:02'),('240','NATIONALITY','SAUDI ARABIAN','Saudi Arabian','','INS->ADMIN->2023-02-07 10:58:51'),('241','NATIONALITY','SCOTTISH','Scottish','','INS->ADMIN->2023-02-07 10:58:51'),('242','NATIONALITY','SENEGALESE','Senegalese','','INS->ADMIN->2023-02-07 10:58:51'),('243','NATIONALITY','SERBIAN','Serbian','','INS->ADMIN->2023-02-07 10:58:51'),('244','NATIONALITY','SIERRA LEONEAN','Sierra Leonean','','INS->ADMIN->2023-02-07 10:58:51'),('245','NATIONALITY','SINGAPOREAN','Singaporean','','INS->ADMIN->2023-02-07 10:58:51'),('246','NATIONALITY','SLOVAK','Slovak','','INS->ADMIN->2023-02-07 10:58:51'),('247','NATIONALITY','SLOVENIAN','Slovenian','','INS->ADMIN->2023-02-07 10:58:51'),('248','NATIONALITY','SOLOMON ISLANDER','Solomon Islander','','INS->ADMIN->2023-02-07 10:58:51'),('249','NATIONALITY','SOMALI','Somali','','INS->ADMIN->2023-02-07 10:58:51'),('25','FILETYPE','pptx','Powerpoint (.pptx)','92','INS->ADMIN->2022-12-07 11:59:11'),('250','NATIONALITY','SOUTH AFRICAN','South African','','INS->ADMIN->2023-02-07 10:58:51'),('251','NATIONALITY','SOUTH KOREAN','South Korean','','INS->ADMIN->2023-02-07 10:58:51'),('252','NATIONALITY','SOUTH SUDANESE','South Sudanese','','INS->ADMIN->2023-02-07 10:58:51'),('253','NATIONALITY','SPANISH','Spanish','','INS->ADMIN->2023-02-07 10:58:51'),('254','NATIONALITY','SRI LANKAN','Sri Lankan','','INS->ADMIN->2023-02-07 10:58:51'),('255','NATIONALITY','ST HELENIAN','St Helenian','','INS->ADMIN->2023-02-07 10:58:51'),('256','NATIONALITY','ST LUCIAN','St Lucian','','INS->ADMIN->2023-02-07 10:58:51'),('257','NATIONALITY','STATELESS','Stateless','','INS->ADMIN->2023-02-07 10:58:51'),('258','NATIONALITY','SUDANESE','Sudanese','','INS->ADMIN->2023-02-07 10:58:51'),('259','NATIONALITY','SURINAMESE','Surinamese','','INS->ADMIN->2023-02-07 10:58:51'),('26','FILETYPE','zip','Compressed (.zip)','93','INS->ADMIN->2022-12-07 11:59:34'),('260','NATIONALITY','SWAZI','Swazi','','INS->ADMIN->2023-02-07 10:58:51'),('261','NATIONALITY','SWEDISH','Swedish','','INS->ADMIN->2023-02-07 10:58:51'),('262','NATIONALITY','SWISS','Swiss','','INS->ADMIN->2023-02-07 10:58:51'),('263','NATIONALITY','SYRIAN','Syrian','','INS->ADMIN->2023-02-07 10:58:51'),('264','NATIONALITY','TAIWANESE','Taiwanese','','INS->ADMIN->2023-02-07 10:58:51'),('265','NATIONALITY','TAJIK','Tajik','','INS->ADMIN->2023-02-07 10:58:51'),('266','NATIONALITY','TANZANIAN','Tanzanian','','INS->ADMIN->2023-02-07 10:58:51'),('267','NATIONALITY','THAI','Thai','','INS->ADMIN->2023-02-07 10:58:51'),('268','NATIONALITY','TOGOLESE','Togolese','','INS->ADMIN->2023-02-07 10:58:51'),('269','NATIONALITY','TONGAN','Tongan','','INS->ADMIN->2023-02-07 10:58:51'),('27','FILETYPE','7z','Compressed (.7z)','94','INS->ADMIN->2022-12-07 11:59:44'),('270','NATIONALITY','TRINIDADIAN','Trinidadian','','INS->ADMIN->2023-02-07 10:58:51'),('271','NATIONALITY','TRISTANIAN','Tristanian','','INS->ADMIN->2023-02-07 10:58:51'),('272','NATIONALITY','TUNISIAN','Tunisian','','INS->ADMIN->2023-02-07 10:58:51'),('273','NATIONALITY','TURKISH','Turkish','','INS->ADMIN->2023-02-07 10:58:51'),('274','NATIONALITY','TURKMEN','Turkmen','','INS->ADMIN->2023-02-07 10:58:51'),('275','NATIONALITY','TURKS AND CAICOS ISL','Turks and Caicos Islander','','INS->ADMIN->2023-02-07 10:58:51'),('276','NATIONALITY','TUVALUAN','Tuvaluan','','INS->ADMIN->2023-02-07 10:58:51'),('277','NATIONALITY','UGANDAN','Ugandan','','INS->ADMIN->2023-02-07 10:58:51'),('278','NATIONALITY','UKRAINIAN','Ukrainian','','INS->ADMIN->2023-02-07 10:58:51'),('279','NATIONALITY','URUGUAYAN','Uruguayan','','INS->ADMIN->2023-02-07 10:58:51'),('28','FILETYPE','rar','Compressed (.rar)','95','INS->ADMIN->2022-12-07 11:59:55'),('280','NATIONALITY','UZBEK','Uzbek','','INS->ADMIN->2023-02-07 10:58:51'),('281','NATIONALITY','VATICAN CITIZEN','Vatican citizen','','INS->ADMIN->2023-02-07 10:58:51'),('282','NATIONALITY','VENEZUELAN','Venezuelan','','INS->ADMIN->2023-02-07 10:58:51'),('283','NATIONALITY','VIETNAMESE','Vietnamese','','INS->ADMIN->2023-02-07 10:58:51'),('284','NATIONALITY','VINCENTIAN','Vincentian','','INS->ADMIN->2023-02-07 10:58:51'),('285','NATIONALITY','WALLISIAN','Wallisian','','INS->ADMIN->2023-02-07 10:58:51'),('286','NATIONALITY','WELSH','Welsh','','INS->ADMIN->2023-02-07 10:58:51'),('287','NATIONALITY','YEMENI','Yemeni','','INS->ADMIN->2023-02-07 10:58:51'),('288','NATIONALITY','ZAMBIAN','Zambian','','INS->ADMIN->2023-02-07 10:58:51'),('289','NATIONALITY','ZIMBABWEAN','Zimbabwean','','INS->ADMIN->2023-02-07 10:58:51'),('29','FILETYPE','pdf','Document (.pdf)','96','INS->ADMIN->2022-12-07 12:00:06'),('290','SYSTYPE','GENDER','Gender','TL-439','INS->ADMIN->2023-02-07 11:06:00'),('291','GENDER','MALE','Male','TL-440','INS->ADMIN->2023-02-07 11:06:32'),('292','GENDER','FEMALE','Female','TL-441','INS->ADMIN->2023-02-07 11:06:42'),('293','SYSTYPE','CIVILSTATUS','Civil Status','TL-442','UPD->ADMIN->2023-03-10 02:39:40'),('294','CIVILSTATUS','SINGLE','Single','TL-443','UPD->ADMIN->2023-03-10 02:39:49'),('295','CIVILSTATUS','MARRIED','Married','TL-444','UPD->ADMIN->2023-03-10 02:39:58'),('296','CIVILSTATUS','WIDOWED','Widowed','TL-445','UPD->ADMIN->2023-03-10 02:40:06'),('297','CIVILSTATUS','DIVORCED','Divorced','TL-446','UPD->ADMIN->2023-03-10 02:40:13'),('298','CIVILSTATUS','SEPARATED','Separated','TL-447','UPD->ADMIN->2023-03-10 02:40:20'),('299','SYSTYPE','SUFFIX','Suffix','TL-448','INS->ADMIN->2023-02-07 11:10:34'),('3','MODULECAT','TECHNICAL','Technical','TL-6',NULL),('30','FILETYPE','txt','Document (.txt)','97','INS->ADMIN->2022-12-07 12:00:17'),('300','SUFFIX','JR','Jr.','TL-449','INS->ADMIN->2023-02-07 11:11:02'),('301','SUFFIX','SR','Sr.','TL-450','INS->ADMIN->2023-02-07 11:11:11'),('302','SUFFIX','I','I','TL-451','INS->ADMIN->2023-02-07 11:11:19'),('303','SUFFIX','II','II','TL-452','INS->ADMIN->2023-02-07 11:11:28'),('304','SUFFIX','III','III','TL-453','INS->ADMIN->2023-02-07 11:11:40'),('305','SUFFIX','IV','IV','TL-454','INS->ADMIN->2023-02-07 11:11:48'),('306','SYSTYPE','BLOODTYPE','Blood Type','TL-455','INS->ADMIN->2023-02-07 11:12:41'),('307','BLOODTYPE','O-','O-','TL-456','INS->ADMIN->2023-02-07 11:25:45'),('308','BLOODTYPE','O+','O+','TL-457','INS->ADMIN->2023-02-07 11:25:59'),('309','BLOODTYPE','A-','A-','TL-458','INS->ADMIN->2023-02-07 11:26:16'),('31','FILETYPE','csv','Data (.csv)','98','INS->ADMIN->2022-12-07 12:00:27'),('310','BLOODTYPE','A+','A+','TL-459','INS->ADMIN->2023-02-07 11:26:25'),('311','BLOODTYPE','B-','B-','TL-460','INS->ADMIN->2023-02-07 11:26:36'),('312','BLOODTYPE','B+','B+','TL-461','UPD->ADMIN->2023-02-07 11:27:00'),('313','BLOODTYPE','AB-','AB-','TL-462','INS->ADMIN->2023-02-07 11:27:10'),('314','BLOODTYPE','AB+','AB+','TL-463','INS->ADMIN->2023-02-07 11:27:18'),('315','SYSTYPE','RELIGION','Religion','TL-464','INS->ADMIN->2023-02-07 11:33:03'),('316','RELIGION','AFRICAN TRADITIONAL ','African Traditional & Diasporic','','INS->ADMIN->2023-02-07 10:58:51'),('317','RELIGION','AGNOSTIC','Agnostic','','INS->ADMIN->2023-02-07 10:58:51'),('318','RELIGION','ATHEIST','Atheist','','INS->ADMIN->2023-02-07 10:58:51'),('319','RELIGION','BAHA I','Baha i','','INS->ADMIN->2023-02-07 10:58:51'),('32','FILETYPE','mp3','Audio (.mp3)','99','INS->ADMIN->2022-12-07 12:00:38'),('320','RELIGION','BUDDHISM','Buddhism','','INS->ADMIN->2023-02-07 10:58:51'),('321','RELIGION','CAO DAI','Cao Dai','','INS->ADMIN->2023-02-07 10:58:51'),('322','RELIGION','CHINESE TRADITIONAL ','Chinese traditional religion','','INS->ADMIN->2023-02-07 10:58:51'),('323','RELIGION','CHRISTIANITY','Christianity','','INS->ADMIN->2023-02-07 10:58:51'),('324','RELIGION','HINDUISM','Hinduism','','INS->ADMIN->2023-02-07 10:58:51'),('325','RELIGION','ISLAM','Islam','','INS->ADMIN->2023-02-07 10:58:51'),('326','RELIGION','JAINISM','Jainism','','INS->ADMIN->2023-02-07 10:58:51'),('327','RELIGION','JUCHE','Juche','','INS->ADMIN->2023-02-07 10:58:51'),('328','RELIGION','JUDAISM','Judaism','','INS->ADMIN->2023-02-07 10:58:51'),('329','RELIGION','NEO-PAGANISM','Neo-Paganism','','INS->ADMIN->2023-02-07 10:58:51'),('33','FILETYPE','sql','Data (.sql)','100','INS->ADMIN->2022-12-07 12:00:56'),('330','RELIGION','NON-RELIGIOUS','Non-religious','','INS->ADMIN->2023-02-07 10:58:51'),('331','RELIGION','RASTAFARIANISM','Rastafarianism','','INS->ADMIN->2023-02-07 10:58:51'),('332','RELIGION','SECULAR','Secular','','INS->ADMIN->2023-02-07 10:58:51'),('333','RELIGION','SHINTO','Shinto','','INS->ADMIN->2023-02-07 10:58:51'),('334','RELIGION','SIKHISM','Sikhism','','INS->ADMIN->2023-02-07 10:58:51'),('335','RELIGION','SPIRITISM','Spiritism','','INS->ADMIN->2023-02-07 10:58:51'),('336','RELIGION','TENRIKYO','Tenrikyo','','INS->ADMIN->2023-02-07 10:58:51'),('337','RELIGION','UNITARIAN-UNIVERSALI','Unitarian-Universalism','','INS->ADMIN->2023-02-07 10:58:51'),('338','RELIGION','ZOROASTRIANISM','Zoroastrianism','','INS->ADMIN->2023-02-07 10:58:51'),('339','RELIGION','PRIMAL-INDIGENOUS','Primal-indigenous','','INS->ADMIN->2023-02-07 10:58:51'),('34','SYSTYPE','MAILENCRYPTION','Mail Encryption','146','INS->ADMIN->2022-12-12 01:56:26'),('340','RELIGION','OTHER','Other','','INS->ADMIN->2023-02-07 10:58:51'),('341','SYSTYPE','IDCLASSIFICATION','ID Classification','TL-465','INS->ADMIN->2023-02-07 12:57:34'),('342','IDCLASSIFICATION','PRIMARY','Primary','TL-466','INS->ADMIN->2023-02-07 12:57:47'),('343','IDCLASSIFICATION','SECONDARY','Secondary','TL-467','INS->ADMIN->2023-02-07 12:58:00'),('344','SYSTYPE','ADDRESSTYPE','Address Type','TL-468','INS->ADMIN->2023-02-07 12:59:29'),('345','ADDRESSTYPE','BUSINESSADDRESS','Business Address','TL-469','INS->ADMIN->2023-02-07 01:00:40'),('346','ADDRESSTYPE','EMPLOYMENTADDRESS','Employment Address','TL-470','INS->ADMIN->2023-02-07 01:04:40'),('347','ADDRESSTYPE','RESIDENTIALADDRESS','Residential Address','TL-471','INS->ADMIN->2023-02-07 01:04:57'),('348','SYSTYPE','CONTACTINFOTYPE','Contact Information Type','TL-472','INS->ADMIN->2023-02-07 01:10:26'),('349','CONTACTINFOTYPE','PERSONAL','Personal','TL-473','INS->ADMIN->2023-02-07 01:10:38'),('35','MAILENCRYPTION','SSL','SSL','147','INS->ADMIN->2022-12-12 01:56:37'),('350','CONTACTINFOTYPE','WORK','Work','TL-474','INS->ADMIN->2023-02-07 01:10:49'),('351','SYSTYPE','RELATIONSHIP','Relationship','TL-475','INS->ADMIN->2023-02-07 02:55:19'),('352','RELATIONSHIP','FATHER','Father','TL-476','INS->ADMIN->2023-02-07 02:57:15'),('353','RELATIONSHIP','MOTHER','Mother','TL-477','UPD->ADMIN->2023-02-07 02:57:35'),('354','RELATIONSHIP','SON','Son','TL-478','INS->ADMIN->2023-02-07 02:57:46'),('355','RELATIONSHIP','DAUGHTER','Daughter','TL-479','INS->ADMIN->2023-02-07 02:58:01'),('356','RELATIONSHIP','HUSBAND','Husband','TL-480','INS->ADMIN->2023-02-07 02:58:15'),('357','RELATIONSHIP','WIFE','Wife','TL-481','INS->ADMIN->2023-02-07 02:58:24'),('358','RELATIONSHIP','BROTHER','Brother','TL-482','INS->ADMIN->2023-02-07 02:58:38'),('359','RELATIONSHIP','SISTER','Sister','TL-483','INS->ADMIN->2023-02-07 02:58:46'),('36','MAILENCRYPTION','None','None','148','INS->ADMIN->2022-12-12 01:56:45'),('360','RELATIONSHIP','GRANDFATHER','Grandfather','TL-484','INS->ADMIN->2023-02-07 02:59:05'),('361','RELATIONSHIP','GRANDMOTHER','Grandmother','TL-485','INS->ADMIN->2023-02-07 02:59:20'),('362','RELATIONSHIP','GRANDSON','Grandson','TL-486','INS->ADMIN->2023-02-07 02:59:34'),('363','RELATIONSHIP','GRANDDAUGHTER','Granddaughter','TL-487','INS->ADMIN->2023-02-07 03:00:02'),('364','RELATIONSHIP','UNCLE','Uncle','TL-488','INS->ADMIN->2023-02-07 03:01:40'),('365','RELATIONSHIP','AUNT','Aunt','TL-489','INS->ADMIN->2023-02-07 03:01:51'),('366','RELATIONSHIP','NEPHEW','Nephew','TL-490','INS->ADMIN->2023-02-07 03:05:08'),('367','RELATIONSHIP','NIECE','Niece','TL-491','INS->ADMIN->2023-02-07 03:05:23'),('368','RELATIONSHIP','COUSIN','Cousin','TL-492','INS->ADMIN->2023-02-07 03:05:33'),('369','RELATIONSHIP','FRIEND','Friend','TL-493','INS->ADMIN->2023-02-07 03:05:47'),('37','MAILENCRYPTION','STARTTLS','STARTTLS','149','INS->ADMIN->2022-12-12 01:56:54'),('370','RELATIONSHIP','CO-EMPLOYEE','Co-Employee','TL-494','INS->ADMIN->2023-02-07 03:06:00'),('371','RELATIONSHIP','SISTER-IN-LAW','Sister-in-law','TL-495','INS->ADMIN->2023-02-07 03:07:01'),('372','RELATIONSHIP','BROTHER-IN-LAW','Brother-in-law','TL-496','INS->ADMIN->2023-02-07 03:07:21'),('373','SYSTYPE','ACCOUNTTYPE','Account Type','TL-497','INS->ADMIN->2023-02-07 03:14:10'),('374','ACCOUNTTYPE','SAVINGS','Savings','TL-498','INS->ADMIN->2023-02-07 03:14:18'),('375','ACCOUNTTYPE','CHECKING','Checking','TL-499','INS->ADMIN->2023-02-07 03:14:28'),('38','MAILENCRYPTION','TLS','TLS','150','INS->ADMIN->2022-12-12 01:57:10'),('39','SYSTYPE','NOTIFICATIONCHANNEL','Notification Channel','170','INS->ADMIN->2022-12-14 01:03:03'),('4','SYSTYPE','FILETYPE','File Type','71','INS->ADMIN->2022-12-07 11:51:52'),('40','NOTIFICATIONCHANNEL','EMAIL','Email','171','INS->ADMIN->2022-12-14 01:03:18'),('41','NOTIFICATIONCHANNEL','SYSTEM','System','172','INS->ADMIN->2022-12-14 01:03:34'),('42','MODULECAT','HUMANRESOURCES','Human Resources','231','INS->ADMIN->2022-12-21 02:27:52'),('43','SYSTYPE','SCHEDULEPAY','Schedule Pay','TL-341','UPD->ADMIN->2023-01-02 11:22:53'),('44','SCHEDULEPAY','MONTHLY','Monthly','TL-342','INS->ADMIN->2023-01-02 11:23:13'),('45','SCHEDULEPAY','QUARTERLY','Quarterly','TL-343','INS->ADMIN->2023-01-02 11:24:40'),('46','SCHEDULEPAY','SEMI-ANNUALLY','Semi-annually','TL-344','INS->ADMIN->2023-01-02 11:25:03'),('47','SCHEDULEPAY','ANNUALLY','Annually','TL-345','INS->ADMIN->2023-01-02 11:25:21'),('48','SCHEDULEPAY','WEEKLY','Weekly','TL-346','INS->ADMIN->2023-01-02 11:25:34'),('49','SCHEDULEPAY','BI-WEEKLY','Bi-weekly','TL-347','INS->ADMIN->2023-01-02 11:25:51'),('5','FILETYPE','svg','Image (.svg)','72','INS->ADMIN->2022-12-07 11:54:58'),('50','SCHEDULEPAY','BI-MONTHLY','Bi-monthly','TL-348','INS->ADMIN->2023-01-02 11:26:17'),('51','SYSTYPE','DAYOFWEEK','Day of Week','TL-362','UPD->ADMIN->2023-01-09 11:39:50'),('52','DAYOFWEEK','MONDAY','Monday','TL-366','INS->ADMIN->2023-01-09 11:34:17'),('53','DAYOFWEEK','TUESDAY','Tuesday','TL-367','INS->ADMIN->2023-01-09 11:34:30'),('54','DAYOFWEEK','WEDNESDAY','Wednesday','TL-368','INS->ADMIN->2023-01-09 11:34:45'),('55','DAYOFWEEK','THURSDAY','Thursday','TL-369','INS->ADMIN->2023-01-09 11:34:59'),('56','DAYOFWEEK','FRIDAY','Friday','TL-370','UPD->ADMIN->2023-01-09 11:35:15'),('57','DAYOFWEEK','SATURDAY','Saturday','TL-371','INS->ADMIN->2023-01-09 11:35:26'),('58','DAYOFWEEK','SUNDAY','Sunday','TL-372','INS->ADMIN->2023-01-09 11:35:36'),('59','SYSTYPE','DAYPERIOD','Day Period','TL-373','INS->ADMIN->2023-01-09 11:38:36'),('6','FILETYPE','png','Image (.png)','73','INS->ADMIN->2022-12-07 11:55:08'),('60','DAYPERIOD','MORNING','Morning','TL-374','INS->ADMIN->2023-01-09 11:39:10'),('61','DAYPERIOD','AFTERNOON','Afternoon','TL-375','INS->ADMIN->2023-01-09 11:39:24'),('62','SYSTYPE','WORKINGSCHEDTYPECAT','Working Schedule Type Category','TL-376','UPD->ADMIN->2023-01-16 10:58:17'),('63','WORKINGSCHEDTYPECAT','FIXED','Fixed','TL-377','UPD->ADMIN->2023-01-16 10:59:18'),('64','WORKINGSCHEDTYPECAT','FLEXIBLE','Flexible','TL-378','UPD->ADMIN->2023-01-16 10:59:08'),('65','SYSTYPE','NATIONALITY','Nationality','TL-417','INS->ADMIN->2023-02-07 10:52:56'),('66','NATIONALITY','AFGHAN','Afghan','TL-418','INS->ADMIN->2023-02-07 10:54:31'),('67','NATIONALITY','ALBANIAN','Albanian','TL-419','INS->ADMIN->2023-02-07 10:54:47'),('68','NATIONALITY','ALGERIAN','Algerian','TL-420','INS->ADMIN->2023-02-07 10:55:12'),('69','NATIONALITY','AMERICAN','American','TL-421','INS->ADMIN->2023-02-07 10:55:24'),('7','FILETYPE','jpg','Image (.jpg)','74','INS->ADMIN->2022-12-07 11:55:23'),('70','NATIONALITY','ANGOLAN','Angolan','TL-422','INS->ADMIN->2023-02-07 10:55:47'),('71','NATIONALITY','ANGUILLAN','Anguillan','TL-423','INS->ADMIN->2023-02-07 10:55:59'),('72','NATIONALITY','ARGENTINE','Argentine','TL-424','INS->ADMIN->2023-02-07 10:56:12'),('73','NATIONALITY','ARMENIAN','Armenian','TL-425','INS->ADMIN->2023-02-07 10:56:26'),('74','NATIONALITY','AUSTRALIAN','Australian','TL-426','INS->ADMIN->2023-02-07 10:56:36'),('75','NATIONALITY','AUSTRIAN','Austrian','TL-427','INS->ADMIN->2023-02-07 10:56:50'),('76','NATIONALITY','AZERBAIJANI','Azerbaijani','TL-428','INS->ADMIN->2023-02-07 10:57:03'),('77','NATIONALITY','BAHAMIAN','Bahamian','TL-429','INS->ADMIN->2023-02-07 10:57:15'),('78','NATIONALITY','BAHRAINI','Bahraini','TL-430','INS->ADMIN->2023-02-07 10:57:25'),('79','NATIONALITY','BANGLADESHI','Bangladeshi','TL-431','INS->ADMIN->2023-02-07 10:57:35'),('8','FILETYPE','ico','Image (.ico)','75','INS->ADMIN->2022-12-07 11:55:37'),('80','NATIONALITY','BARBADIAN','Barbadian','TL-432','INS->ADMIN->2023-02-07 10:57:47'),('81','NATIONALITY','BELARUSIAN','Belarusian','TL-433','INS->ADMIN->2023-02-07 10:57:59'),('82','NATIONALITY','BELGIAN','Belgian','TL-434','INS->ADMIN->2023-02-07 10:58:07'),('83','NATIONALITY','BELIZEAN','Belizean','TL-435','INS->ADMIN->2023-02-07 10:58:16'),('84','NATIONALITY','BENINESE','Beninese','TL-436','INS->ADMIN->2023-02-07 10:58:25'),('85','NATIONALITY','BERMUDIAN','Bermudian','TL-437','INS->ADMIN->2023-02-07 10:58:40'),('86','NATIONALITY','BHUTANESE','Bhutanese','TL-438','INS->ADMIN->2023-02-07 10:58:51'),('87','NATIONALITY','BOLIVIAN','Bolivian','','INS->ADMIN->2023-02-07 10:58:51'),('88','NATIONALITY','BOTSWANAN','Botswanan','','INS->ADMIN->2023-02-07 10:58:51'),('89','NATIONALITY','BRAZILIAN','Brazilian','','INS->ADMIN->2023-02-07 10:58:51'),('9','FILETYPE','jpeg','Image (.jpeg)','76','INS->ADMIN->2022-12-07 11:55:49'),('90','NATIONALITY','BRITISH','British','','INS->ADMIN->2023-02-07 10:58:51'),('91','NATIONALITY','BRITISH VIRGIN ISLAN','British Virgin Islander','','INS->ADMIN->2023-02-07 10:58:51'),('92','NATIONALITY','BRUNEIAN','Bruneian','','INS->ADMIN->2023-02-07 10:58:51'),('93','NATIONALITY','BULGARIAN','Bulgarian','','INS->ADMIN->2023-02-07 10:58:51'),('94','NATIONALITY','BURKINAN','Burkinan','','INS->ADMIN->2023-02-07 10:58:51'),('95','NATIONALITY','BURMESE','Burmese','','INS->ADMIN->2023-02-07 10:58:51'),('96','NATIONALITY','BURUNDIAN','Burundian','','INS->ADMIN->2023-02-07 10:58:51'),('97','NATIONALITY','CAMBODIAN','Cambodian','','INS->ADMIN->2023-02-07 10:58:51'),('98','NATIONALITY','CAMEROONIAN','Cameroonian','','INS->ADMIN->2023-02-07 10:58:51'),('99','NATIONALITY','CANADIAN','Canadian','','INS->ADMIN->2023-02-07 10:58:51');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_system_parameters`
--

LOCK TABLES `global_system_parameters` WRITE;
/*!40000 ALTER TABLE `global_system_parameters` DISABLE KEYS */;
INSERT INTO `global_system_parameters` VALUES (1,'System Parameter','Parameter for system parameters.','',30,'TL-15','UPD->ADMIN->2023-03-10 02:10:46'),(2,'Transaction Log','Parameter for transaction logs.','TL-',667,'TL-16','UPD->ADMIN->2023-03-30 01:12:24'),(3,'Module','Parameter for modules.','',5,'TL-17','UPD->ADMIN->2023-03-09 04:34:08'),(4,'Page','Parameter for pages.','',50,'TL-25','UPD->ADMIN->2023-03-09 04:34:41'),(5,'Action','Parameter for actions.','',138,'TL-33','UPD->ADMIN->2023-03-30 01:12:24'),(6,'Role','Parameter for roles.','',1,'TL-39','UPD->ADMIN->2023-03-09 04:38:52'),(7,'Upload Settings','Parameter for upload settings.','',9,'57','UPD->ADMIN->2023-03-17 03:06:25'),(8,'System Code','Parameter for system code.','',375,'68','UPD->ADMIN->2023-03-09 04:36:44'),(9,'Company','Parameter for company.','',1,'110','UPD->ADMIN->2023-03-10 01:24:19'),(10,'Interface Setting','Parameter for interface setting.','',1,'120','UPD->ADMIN->2023-03-09 04:35:54'),(11,'Email Setting','Parameter for email setting.','',0,'151','UPD->ADMIN->2023-03-09 04:35:29'),(12,'Notification Setting','Parameter for notification setting.','',0,'158','UPD->ADMIN->2023-03-09 04:36:10'),(13,'Country','Parameter for country.','',0,'176','UPD->ADMIN->2023-03-09 04:35:15'),(14,'State','Parameter for state.','',0,'177','UPD->ADMIN->2023-03-09 04:36:26'),(15,'Zoom API','Parameter for Zoom API','',0,'204','UPD->ADMIN->2023-03-09 04:37:39'),(16,'Department','Parameter for department.','',1,'242','UPD->ADMIN->2023-03-10 01:12:48'),(17,'Job Position','Parameter for job position.','',1,'263','UPD->ADMIN->2023-03-10 01:22:15'),(18,'Job Position Responsibility','Parameter for job position responsibility.','',0,'270','UPD->ADMIN->2023-03-09 04:41:31'),(19,'Job Position Requirement','Parameter for job position requirement.','',0,'271','INS->ADMIN->2022-12-24 05:44:34'),(20,'Job Position Qualification','Parameter for job position qualification.','',0,'272','UPD->ADMIN->2023-03-09 04:41:26'),(21,'Job Position Attachment','Parameter for job position attachment.','',15,'273','UPD->ADMIN->2023-03-23 10:50:10'),(22,'Work Location','Parameter for work location.','',1,'TL-306','UPD->ADMIN->2023-03-10 01:29:44'),(23,'Departure Reason','Parameter for departure reason','',1,'TL-310','UPD->ADMIN->2023-03-30 11:04:07'),(24,'Employee Type','Parameter for employee type.','',1,'TL-323','UPD->ADMIN->2023-03-10 01:36:42'),(25,'Wage Type','Parameter for wage type.','',0,'TL-331','UPD->ADMIN->2023-03-09 04:42:16'),(26,'Working Schedule','Parameter for working schedule.','',1,'TL-379','UPD->ADMIN->2023-03-10 01:30:43'),(27,'Working Hours','Parameter for working hours.','',0,'TL-380','UPD->ADMIN->2023-03-09 04:42:27'),(28,'Working Schedule Type','Parameter for working schedule type.','',1,'TL-389','UPD->ADMIN->2023-03-10 01:30:33'),(29,'ID Type','Parameter for ID type.','',0,'TL-409','UPD->ADMIN->2023-03-09 04:41:05'),(30,'Employee ID','Parameter for employee ID.','',6,'TL-586','UPD->ADMIN->2023-03-10 02:24:11');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_transaction_log`
--

LOCK TABLES `global_transaction_log` WRITE;
/*!40000 ALTER TABLE `global_transaction_log` DISABLE KEYS */;
INSERT INTO `global_transaction_log` VALUES ('TL-1','ADMIN','Update','2022-09-20 13:37:24','User ADMIN updated user account password.'),('TL-1','ADMIN','Log In','2022-09-20 13:37:28','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-09-21 09:59:22','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-09-22 13:34:58','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-09-23 08:35:00','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-24 17:01:02','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-24 17:01:20','User ADMIN logged in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:01:45','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-10-24 17:01:52','User ADMIN logged in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:02:56','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-10-24 17:03:37','User ADMIN logged in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:38','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:39','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:40','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:40','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-24 17:05:41','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-25 09:51:47','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-10-25 09:51:50','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-10-25 09:51:52','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-26 08:57:46','User ADMIN logged in.'),('TL-1','ADMIN','Attempt Log In','2022-10-27 13:02:32','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-10-27 13:02:36','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-28 09:08:02','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-29 21:45:41','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-30 09:07:23','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-30 18:47:01','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-10-31 09:44:15','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-15 09:11:11','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-17 15:54:46','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-18 11:34:23','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-21 15:50:26','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-22 10:51:22','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-22 16:49:17','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-11-29 09:14:44','User ADMIN logged in.'),('TL-3','ADMIN','Update','2022-11-29 16:33:49','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:33:51','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:33:56','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:34:02','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:35:09','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:36:19','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:44:11','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:44:30','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:17','User ADMIN updated icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:17','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:45','User ADMIN updated icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:45','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:48','User ADMIN updated module icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:48','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:48','User ADMIN updated module icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:48','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:46:59','User ADMIN updated module icon.'),('TL-3','ADMIN','Update','2022-11-29 16:46:59','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:47:24','User ADMIN updated module icon.'),('TL-3','ADMIN','Update','2022-11-29 16:47:24','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 16:47:35','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 17:12:25','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 17:12:30','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-29 17:12:40','User ADMIN updated module.'),('TL-18','ADMIN','Insert','2022-11-29 17:13:15','User ADMIN inserted module.'),('TL-19','ADMIN','Insert','2022-11-29 17:14:06','User ADMIN inserted module.'),('TL-20','ADMIN','Insert','2022-11-30 12:34:02','User ADMIN inserted module.'),('TL-21','ADMIN','Insert','2022-11-30 12:34:11','User ADMIN inserted module.'),('TL-22','ADMIN','Insert','2022-11-30 12:35:09','User ADMIN inserted module.'),('TL-23','ADMIN','Insert','2022-11-30 12:35:41','User ADMIN inserted module.'),('TL-24','ADMIN','Insert','2022-11-30 12:36:17','User ADMIN inserted module.'),('TL-1','ADMIN','Log In','2022-11-30 17:56:03','User ADMIN logged in.'),('TL-24','ADMIN','Update','2022-11-30 18:03:01','User ADMIN updated module.'),('TL-1','ADMIN','Log In','2022-11-30 20:11:21','User ADMIN logged in.'),('TL-25','ADMIN','Insert','2022-11-30 20:18:14','User ADMIN inserted module.'),('TL-3','ADMIN','Update','2022-11-30 20:22:04','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-30 20:22:09','User ADMIN updated module.'),('TL-3','ADMIN','Update','2022-11-30 20:22:15','User ADMIN updated module.'),('TL-1','ADMIN','Log In','2022-12-01 10:41:23','User ADMIN logged in.'),('TL-26','ADMIN','Insert','2022-12-01 13:22:24','User ADMIN inserted page.'),('TL-27','ADMIN','Insert','2022-12-01 13:33:06','User ADMIN inserted page.'),('TL-28','ADMIN','Insert','2022-12-01 13:34:24','User ADMIN inserted module.'),('TL-29','ADMIN','Insert','2022-12-01 14:05:33','User ADMIN inserted page.'),('TL-26','ADMIN','Update','2022-12-01 14:29:29','User ADMIN updated page.'),('TL-1','ADMIN','Log In','2022-12-02 09:25:01','User ADMIN logged in.'),('TL-34','ADMIN','Insert','2022-12-02 09:30:22','User ADMIN inserted action.'),('TL-21','ADMIN','Update','2022-12-02 09:37:48','User ADMIN updated action.'),('TL-34','ADMIN','Insert','2022-12-02 09:55:03','User ADMIN inserted page.'),('TL-35','ADMIN','Insert','2022-12-02 13:57:57','User ADMIN inserted action.'),('TL-36','ADMIN','Insert','2022-12-02 13:58:13','User ADMIN inserted action.'),('TL-37','ADMIN','Insert','2022-12-02 13:58:24','User ADMIN inserted action.'),('TL-38','ADMIN','Insert','2022-12-02 14:47:02','User ADMIN inserted page.'),('TL-34','ADMIN','Update','2022-12-02 14:47:59','User ADMIN updated page.'),('TL-15','ADMIN','Update','2022-12-02 17:19:30','User ADMIN updated system parameter.'),('TL-39','ADMIN','Insert','2022-12-02 17:20:07','User ADMIN inserted system parameter.'),('TL-39','ADMIN','Update','2022-12-02 17:20:17','User ADMIN updated system parameter.'),('TL-15','ADMIN','Update','2022-12-02 17:21:53','User ADMIN updated system parameter.'),('TL-16','ADMIN','Update','2022-12-02 17:22:03','User ADMIN updated system parameter.'),('39','ADMIN','Insert','2022-12-02 17:22:15','User ADMIN inserted system parameter.'),('39','ADMIN','Update','2022-12-02 17:22:20','User ADMIN updated system parameter.'),('40','ADMIN','Insert','2022-12-02 17:22:59','User ADMIN inserted page.'),('TL-25','ADMIN','Update','2022-12-02 17:23:23','User ADMIN updated system parameter.'),('TL-16','ADMIN','Update','2022-12-02 17:23:32','User ADMIN updated system parameter.'),('40','ADMIN','Insert','2022-12-02 17:24:52','User ADMIN inserted page.'),('41','ADMIN','Insert','2022-12-02 17:25:12','User ADMIN inserted page.'),('42','ADMIN','Insert','2022-12-02 17:25:25','User ADMIN inserted action.'),('TL-33','ADMIN','Update','2022-12-02 17:25:56','User ADMIN updated system parameter.'),('TL-16','ADMIN','Update','2022-12-02 17:26:03','User ADMIN updated system parameter.'),('42','ADMIN','Insert','2022-12-02 17:26:22','User ADMIN inserted action.'),('42','ADMIN','Update','2022-12-02 17:26:24','User ADMIN updated action.'),('43','ADMIN','Insert','2022-12-02 17:26:44','User ADMIN inserted action.'),('43','ADMIN','Update','2022-12-02 17:26:53','User ADMIN updated action.'),('44','ADMIN','Insert','2022-12-02 17:26:59','User ADMIN inserted action.'),('45','ADMIN','Insert','2022-12-02 17:27:18','User ADMIN inserted action.'),('46','ADMIN','Insert','2022-12-02 17:27:34','User ADMIN inserted action.'),('47','ADMIN','Insert','2022-12-02 17:27:51','User ADMIN inserted action.'),('48','ADMIN','Insert','2022-12-02 17:28:04','User ADMIN inserted action.'),('49','ADMIN','Insert','2022-12-02 17:28:17','User ADMIN inserted action.'),('50','ADMIN','Insert','2022-12-02 17:28:30','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-04 09:50:27','User ADMIN logged in.'),('51','ADMIN','Insert','2022-12-04 09:52:54','User ADMIN inserted action.'),('52','ADMIN','Insert','2022-12-04 09:53:16','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-05 11:32:09','User ADMIN logged in.'),('53','ADMIN','Insert','2022-12-05 15:45:35','User ADMIN inserted role.'),('54','ADMIN','Insert','2022-12-05 15:55:59','User ADMIN inserted role.'),('55','ADMIN','Insert','2022-12-05 16:06:03','User ADMIN inserted role.'),('TL-1','ADMIN','Log In','2022-12-06 11:26:41','User ADMIN logged in.'),('56','ADMIN','Insert','2022-12-06 11:28:27','User ADMIN inserted role.'),('TL-39','ADMIN','Update','2022-12-06 15:00:37','User ADMIN updated system parameter.'),('57','ADMIN','Insert','2022-12-06 15:07:33','User ADMIN inserted system parameter.'),('58','ADMIN','Insert','2022-12-06 15:07:55','User ADMIN inserted page.'),('59','ADMIN','Insert','2022-12-06 15:10:25','User ADMIN inserted action.'),('60','ADMIN','Insert','2022-12-06 15:10:45','User ADMIN inserted action.'),('61','ADMIN','Insert','2022-12-06 15:11:18','User ADMIN inserted action.'),('62','ADMIN','Insert','2022-12-06 16:11:17','User ADMIN inserted page.'),('63','ADMIN','Insert','2022-12-06 17:02:51','User ADMIN inserted page.'),('63','ADMIN','Update','2022-12-06 17:02:54','User ADMIN updated page.'),('64','ADMIN','Insert','2022-12-06 17:03:46','User ADMIN inserted page.'),('65','ADMIN','Insert','2022-12-06 17:03:57','User ADMIN inserted action.'),('66','ADMIN','Insert','2022-12-06 17:04:18','User ADMIN inserted action.'),('67','ADMIN','Insert','2022-12-06 17:04:34','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-07 09:41:11','User ADMIN logged in.'),('68','ADMIN','Insert','2022-12-07 10:01:07','User ADMIN inserted system parameter.'),('68','ADMIN','Update','2022-12-07 10:01:31','User ADMIN updated system parameter.'),('69','ADMIN','Insert','2022-12-07 11:43:14','User ADMIN inserted system code.'),('68','ADMIN','Update','2022-12-07 11:43:54','User ADMIN updated system parameter.'),('70','ADMIN','Insert','2022-12-07 11:44:06','User ADMIN inserted system code.'),('68','ADMIN','Update','2022-12-07 11:47:14','User ADMIN updated system parameter.'),('71','ADMIN','Insert','2022-12-07 11:51:52','User ADMIN inserted system code.'),('72','ADMIN','Insert','2022-12-07 11:54:58','User ADMIN inserted system code.'),('73','ADMIN','Insert','2022-12-07 11:55:08','User ADMIN inserted system code.'),('74','ADMIN','Insert','2022-12-07 11:55:23','User ADMIN inserted system code.'),('75','ADMIN','Insert','2022-12-07 11:55:37','User ADMIN inserted system code.'),('76','ADMIN','Insert','2022-12-07 11:55:49','User ADMIN inserted system code.'),('77','ADMIN','Insert','2022-12-07 11:55:59','User ADMIN inserted system code.'),('78','ADMIN','Insert','2022-12-07 11:56:17','User ADMIN inserted system code.'),('79','ADMIN','Insert','2022-12-07 11:56:36','User ADMIN inserted system code.'),('80','ADMIN','Insert','2022-12-07 11:56:50','User ADMIN inserted system code.'),('81','ADMIN','Insert','2022-12-07 11:57:00','User ADMIN inserted system code.'),('82','ADMIN','Insert','2022-12-07 11:57:13','User ADMIN inserted system code.'),('83','ADMIN','Insert','2022-12-07 11:57:23','User ADMIN inserted system code.'),('84','ADMIN','Insert','2022-12-07 11:57:36','User ADMIN inserted system code.'),('85','ADMIN','Insert','2022-12-07 11:57:49','User ADMIN inserted system code.'),('86','ADMIN','Insert','2022-12-07 11:58:05','User ADMIN inserted system code.'),('87','ADMIN','Insert','2022-12-07 11:58:16','User ADMIN inserted system code.'),('88','ADMIN','Insert','2022-12-07 11:58:27','User ADMIN inserted system code.'),('89','ADMIN','Insert','2022-12-07 11:58:38','User ADMIN inserted system code.'),('90','ADMIN','Insert','2022-12-07 11:58:50','User ADMIN inserted system code.'),('91','ADMIN','Insert','2022-12-07 11:59:02','User ADMIN inserted system code.'),('92','ADMIN','Insert','2022-12-07 11:59:11','User ADMIN inserted system code.'),('93','ADMIN','Insert','2022-12-07 11:59:34','User ADMIN inserted system code.'),('94','ADMIN','Insert','2022-12-07 11:59:44','User ADMIN inserted system code.'),('95','ADMIN','Insert','2022-12-07 11:59:55','User ADMIN inserted system code.'),('96','ADMIN','Insert','2022-12-07 12:00:06','User ADMIN inserted system code.'),('97','ADMIN','Insert','2022-12-07 12:00:17','User ADMIN inserted system code.'),('98','ADMIN','Insert','2022-12-07 12:00:27','User ADMIN inserted system code.'),('99','ADMIN','Insert','2022-12-07 12:00:38','User ADMIN inserted system code.'),('100','ADMIN','Insert','2022-12-07 12:00:56','User ADMIN inserted system code.'),('101','ADMIN','Insert','2022-12-07 13:22:51','User ADMIN inserted action.'),('102','ADMIN','Insert','2022-12-07 13:23:12','User ADMIN inserted action.'),('57','ADMIN','Update','2022-12-07 15:18:57','User ADMIN updated system parameter.'),('103','ADMIN','Insert','2022-12-07 15:18:59','User ADMIN inserted upload setting.'),('104','ADMIN','Insert','2022-12-07 15:25:19','User ADMIN inserted upload setting.'),('57','ADMIN','Update','2022-12-07 15:25:36','User ADMIN updated system parameter.'),('105','ADMIN','Insert','2022-12-07 15:50:51','User ADMIN inserted page.'),('106','ADMIN','Insert','2022-12-07 15:51:05','User ADMIN inserted page.'),('107','ADMIN','Insert','2022-12-07 15:54:10','User ADMIN inserted action.'),('107','ADMIN','Update','2022-12-07 15:54:12','User ADMIN updated action.'),('108','ADMIN','Insert','2022-12-07 15:54:24','User ADMIN inserted action.'),('109','ADMIN','Insert','2022-12-07 15:54:36','User ADMIN inserted action.'),('110','ADMIN','Insert','2022-12-07 16:50:52','User ADMIN inserted system parameter.'),('110','ADMIN','Update','2022-12-07 16:51:03','User ADMIN updated system parameter.'),('111','ADMIN','Insert','2022-12-07 16:53:02','User ADMIN inserted upload setting.'),('112','ADMIN','Insert','2022-12-07 17:36:22','User ADMIN inserted company.'),('113','ADMIN','Insert','2022-12-07 17:36:27','User ADMIN inserted company.'),('114','ADMIN','Insert','2022-12-07 17:37:25','User ADMIN inserted company.'),('115','ADMIN','Insert','2022-12-07 17:38:34','User ADMIN inserted company.'),('115','ADMIN','Update','2022-12-07 17:38:34','User ADMIN updated company logo.'),('TL-1','ADMIN','Log In','2022-12-09 08:45:56','User ADMIN logged in.'),('110','ADMIN','Update','2022-12-09 08:48:46','User ADMIN updated system parameter.'),('110','ADMIN','Update','2022-12-09 08:48:51','User ADMIN updated system parameter.'),('116','ADMIN','Insert','2022-12-09 08:50:50','User ADMIN inserted company.'),('116','ADMIN','Update','2022-12-09 08:50:50','User ADMIN updated company logo.'),('110','ADMIN','Update','2022-12-09 09:19:09','User ADMIN updated system parameter.'),('117','ADMIN','Insert','2022-12-09 09:36:26','User ADMIN inserted company.'),('117','ADMIN','Update','2022-12-09 09:36:26','User ADMIN updated company logo.'),('110','ADMIN','Update','2022-12-09 09:41:50','User ADMIN updated system parameter.'),('118','ADMIN','Insert','2022-12-09 09:42:02','User ADMIN inserted company.'),('118','ADMIN','Update','2022-12-09 09:42:02','User ADMIN updated company logo.'),('110','ADMIN','Update','2022-12-09 09:45:44','User ADMIN updated system parameter.'),('119','ADMIN','Insert','2022-12-09 09:45:59','User ADMIN inserted company.'),('119','ADMIN','Update','2022-12-09 09:45:59','User ADMIN updated company logo.'),('120','ADMIN','Insert','2022-12-09 09:56:50','User ADMIN inserted system parameter.'),('120','ADMIN','Update','2022-12-09 09:56:53','User ADMIN updated system parameter.'),('121','ADMIN','Insert','2022-12-09 09:57:14','User ADMIN inserted page.'),('122','ADMIN','Insert','2022-12-09 09:57:28','User ADMIN inserted action.'),('123','ADMIN','Insert','2022-12-09 09:57:41','User ADMIN inserted action.'),('124','ADMIN','Insert','2022-12-09 09:58:04','User ADMIN inserted action.'),('124','ADMIN','Update','2022-12-09 09:58:15','User ADMIN updated action.'),('125','ADMIN','Insert','2022-12-09 09:58:46','User ADMIN inserted action.'),('126','ADMIN','Insert','2022-12-09 09:59:03','User ADMIN inserted action.'),('127','ADMIN','Insert','2022-12-09 10:00:19','User ADMIN inserted page.'),('128','ADMIN','Insert','2022-12-09 10:16:09','User ADMIN inserted upload setting.'),('129','ADMIN','Insert','2022-12-09 10:16:41','User ADMIN inserted upload setting.'),('130','ADMIN','Insert','2022-12-09 10:18:34','User ADMIN inserted upload setting.'),('131','ADMIN','Insert','2022-12-09 10:19:11','User ADMIN inserted upload setting.'),('127','ADMIN','Update','2022-12-09 10:35:36','User ADMIN updated page.'),('121','ADMIN','Update','2022-12-09 10:35:44','User ADMIN updated page.'),('122','ADMIN','Update','2022-12-09 10:38:57','User ADMIN updated action.'),('123','ADMIN','Update','2022-12-09 10:39:01','User ADMIN updated action.'),('123','ADMIN','Update','2022-12-09 10:39:06','User ADMIN updated action.'),('124','ADMIN','Update','2022-12-09 10:39:12','User ADMIN updated action.'),('125','ADMIN','Update','2022-12-09 10:39:18','User ADMIN updated action.'),('126','ADMIN','Update','2022-12-09 10:39:24','User ADMIN updated action.'),('132','ADMIN','Insert','2022-12-09 16:10:09','User ADMIN inserted interface setting.'),('132','ADMIN','Update','2022-12-09 16:10:09','User ADMIN updated login background.'),('132','ADMIN','Update','2022-12-09 16:10:09','User ADMIN updated login logo.'),('132','ADMIN','Update','2022-12-09 16:10:09','User ADMIN updated menu logo.'),('132','ADMIN','Update','2022-12-09 16:10:09','User ADMIN updated favicon.'),('133','ADMIN','Insert','2022-12-09 16:13:29','User ADMIN inserted interface setting.'),('134','ADMIN','Insert','2022-12-09 16:53:03','User ADMIN inserted interface setting.'),('135','ADMIN','Insert','2022-12-09 16:53:14','User ADMIN inserted interface setting.'),('136','ADMIN','Insert','2022-12-09 17:06:14','User ADMIN inserted interface setting.'),('137','ADMIN','Insert','2022-12-09 17:06:22','User ADMIN inserted interface setting.'),('136','ADMIN','Deactivate','2022-12-09 17:06:30','User ADMIN updated interface setting status.'),('136','ADMIN','Activate','2022-12-09 17:07:50','User ADMIN updated interface setting status.'),('136','ADMIN','Activate','2022-12-09 17:08:12','User ADMIN updated interface setting status.'),('136','ADMIN','Deactivate','2022-12-09 17:08:13','User ADMIN updated interface setting status.'),('136','ADMIN','Activate','2022-12-09 17:09:16','User ADMIN updated interface setting status.'),('136','ADMIN','Deactivate','2022-12-09 17:09:16','User ADMIN updated interface setting status.'),('136','ADMIN','Activate','2022-12-09 17:10:29','User ADMIN updated interface setting status.'),('136','ADMIN','Deactivate','2022-12-09 17:10:29','User ADMIN updated interface setting status.'),('136','ADMIN','Deactivate','2022-12-09 17:13:33','User ADMIN updated interface setting status.'),('120','ADMIN','Update','2022-12-09 17:14:11','User ADMIN updated system parameter.'),('TL-1','ADMIN','Log In','2022-12-12 12:02:27','User ADMIN logged in.'),('138','ADMIN','Insert','2022-12-12 12:02:53','User ADMIN inserted interface setting.'),('138','ADMIN','Activate','2022-12-12 12:03:14','User ADMIN updated interface setting status.'),('138','ADMIN','Deactivate','2022-12-12 12:03:14','User ADMIN updated interface setting status.'),('138','ADMIN','Update','2022-12-12 12:47:13','User ADMIN updated interface setting.'),('139','ADMIN','Insert','2022-12-12 12:48:32','User ADMIN inserted page.'),('140','ADMIN','Insert','2022-12-12 12:48:47','User ADMIN inserted page.'),('141','ADMIN','Insert','2022-12-12 12:55:18','User ADMIN inserted action.'),('142','ADMIN','Insert','2022-12-12 12:55:29','User ADMIN inserted action.'),('143','ADMIN','Insert','2022-12-12 12:55:41','User ADMIN inserted action.'),('144','ADMIN','Insert','2022-12-12 12:56:09','User ADMIN inserted action.'),('145','ADMIN','Insert','2022-12-12 12:56:22','User ADMIN inserted action.'),('146','ADMIN','Insert','2022-12-12 13:56:26','User ADMIN inserted system code.'),('147','ADMIN','Insert','2022-12-12 13:56:37','User ADMIN inserted system code.'),('148','ADMIN','Insert','2022-12-12 13:56:45','User ADMIN inserted system code.'),('149','ADMIN','Insert','2022-12-12 13:56:54','User ADMIN inserted system code.'),('150','ADMIN','Insert','2022-12-12 13:57:10','User ADMIN inserted system code.'),('151','ADMIN','Insert','2022-12-12 15:06:17','User ADMIN inserted system parameter.'),('152','ADMIN','Insert','2022-12-12 16:19:14','User ADMIN inserted email setting.'),('153','ADMIN','Insert','2022-12-12 16:20:26','User ADMIN inserted email setting.'),('153','ADMIN','Activate','2022-12-12 16:20:30','User ADMIN updated email setting status.'),('153','ADMIN','Deactivate','2022-12-12 16:20:30','User ADMIN updated email setting status.'),('154','ADMIN','Insert','2022-12-12 16:21:45','User ADMIN inserted email setting.'),('155','ADMIN','Insert','2022-12-12 17:02:01','User ADMIN inserted email setting.'),('156','ADMIN','Insert','2022-12-12 17:10:48','User ADMIN inserted email setting.'),('157','ADMIN','Insert','2022-12-12 17:11:40','User ADMIN inserted email setting.'),('158','ADMIN','Insert','2022-12-12 17:24:52','User ADMIN inserted system parameter.'),('159','ADMIN','Insert','2022-12-12 17:25:24','User ADMIN inserted page.'),('160','ADMIN','Insert','2022-12-12 17:25:42','User ADMIN inserted page.'),('161','ADMIN','Insert','2022-12-12 17:26:27','User ADMIN inserted action.'),('162','ADMIN','Insert','2022-12-12 17:26:45','User ADMIN inserted action.'),('163','ADMIN','Insert','2022-12-12 17:27:06','User ADMIN inserted action.'),('164','ADMIN','Insert','2022-12-12 17:27:35','User ADMIN inserted action.'),('165','ADMIN','Insert','2022-12-12 17:27:56','User ADMIN inserted action.'),('166','ADMIN','Insert','2022-12-12 17:28:15','User ADMIN inserted action.'),('167','ADMIN','Insert','2022-12-12 17:28:32','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-13 08:34:47','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-12-14 09:06:08','User ADMIN logged in.'),('168','ADMIN','Insert','2022-12-14 12:55:28','User ADMIN inserted action.'),('169','ADMIN','Insert','2022-12-14 12:55:41','User ADMIN inserted action.'),('170','ADMIN','Insert','2022-12-14 13:03:03','User ADMIN inserted system code.'),('171','ADMIN','Insert','2022-12-14 13:03:18','User ADMIN inserted system code.'),('172','ADMIN','Insert','2022-12-14 13:03:34','User ADMIN inserted system code.'),('173','ADMIN','Insert','2022-12-14 16:57:54','User ADMIN inserted notification setting.'),('174','ADMIN','Insert','2022-12-14 16:58:00','User ADMIN inserted notification setting.'),('175','ADMIN','Insert','2022-12-14 16:58:19','User ADMIN inserted notification setting.'),('175','ADMIN','Update','2022-12-14 17:02:03','User ADMIN updated notification setting.'),('TL-1','ADMIN','Log In','2022-12-15 08:40:50','User ADMIN logged in.'),('176','ADMIN','Insert','2022-12-15 13:46:51','User ADMIN inserted system parameter.'),('177','ADMIN','Insert','2022-12-15 13:47:01','User ADMIN inserted system parameter.'),('178','ADMIN','Insert','2022-12-15 13:47:18','User ADMIN inserted page.'),('179','ADMIN','Insert','2022-12-15 13:48:05','User ADMIN inserted page.'),('180','ADMIN','Insert','2022-12-15 13:48:26','User ADMIN inserted page.'),('181','ADMIN','Insert','2022-12-15 13:48:40','User ADMIN inserted page.'),('182','ADMIN','Insert','2022-12-15 13:52:37','User ADMIN inserted action.'),('183','ADMIN','Insert','2022-12-15 13:52:49','User ADMIN inserted action.'),('184','ADMIN','Insert','2022-12-15 13:52:58','User ADMIN inserted action.'),('185','ADMIN','Insert','2022-12-15 13:53:08','User ADMIN inserted action.'),('186','ADMIN','Insert','2022-12-15 13:53:17','User ADMIN inserted action.'),('187','ADMIN','Insert','2022-12-15 13:53:27','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-15 14:41:56','User ADMIN logged in.'),('188','ADMIN','Insert','2022-12-15 15:38:23','User ADMIN inserted country.'),('189','ADMIN','Insert','2022-12-15 15:44:07','User ADMIN inserted state.'),('190','ADMIN','Insert','2022-12-15 15:45:30','User ADMIN inserted state.'),('191','ADMIN','Insert','2022-12-15 15:46:18','User ADMIN inserted state.'),('192','ADMIN','Insert','2022-12-15 15:46:39','User ADMIN inserted state.'),('193','ADMIN','Insert','2022-12-15 15:47:58','User ADMIN inserted country.'),('194','ADMIN','Insert','2022-12-15 15:48:06','User ADMIN inserted state.'),('195','ADMIN','Insert','2022-12-15 15:49:08','User ADMIN inserted state.'),('196','ADMIN','Insert','2022-12-15 16:02:51','User ADMIN inserted country.'),('197','ADMIN','Insert','2022-12-15 16:07:37','User ADMIN inserted page.'),('198','ADMIN','Insert','2022-12-15 16:08:21','User ADMIN inserted page.'),('199','ADMIN','Insert','2022-12-15 16:17:49','User ADMIN inserted action.'),('197','ADMIN','Update','2022-12-15 16:20:20','User ADMIN updated page.'),('198','ADMIN','Update','2022-12-15 16:20:30','User ADMIN updated page.'),('200','ADMIN','Insert','2022-12-15 16:20:41','User ADMIN inserted action.'),('201','ADMIN','Insert','2022-12-15 16:20:54','User ADMIN inserted action.'),('202','ADMIN','Insert','2022-12-15 16:21:38','User ADMIN inserted action.'),('203','ADMIN','Insert','2022-12-15 16:21:51','User ADMIN inserted action.'),('TL-1','ADMIN','Log Out','2022-12-16 08:56:56','User ADMIN logged out.'),('TL-1','ADMIN','Log In','2022-12-16 08:57:10','User ADMIN logged in.'),('TL-3','ADMIN','Update','2022-12-16 08:58:15','User ADMIN updated module.'),('TL-1','ADMIN','Attempt Log In','2022-12-16 09:46:01','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-12-16 09:46:04','User ADMIN logged in.'),('204','ADMIN','Insert','2022-12-16 11:10:55','User ADMIN inserted system parameter.'),('205','ADMIN','Insert','2022-12-16 11:42:33','User ADMIN inserted zoom API.'),('206','ADMIN','Insert','2022-12-16 11:42:37','User ADMIN inserted zoom API.'),('207','ADMIN','Insert','2022-12-16 11:43:06','User ADMIN inserted zoom API.'),('207','ADMIN','Activate','2022-12-16 11:46:15','User ADMIN updated zoom API status.'),('208','ADMIN','Insert','2022-12-16 11:48:24','User ADMIN inserted zoom API.'),('208','ADMIN','Activate','2022-12-16 14:05:58','User ADMIN updated zoom API status.'),('209','ADMIN','Insert','2022-12-16 14:16:48','User ADMIN inserted email setting.'),('209','ADMIN','Activate','2022-12-16 14:17:08','User ADMIN updated email setting status.'),('138','ADMIN','Deactivate','2022-12-16 14:24:08','User ADMIN updated interface setting status.'),('138','ADMIN','Activate','2022-12-16 14:24:11','User ADMIN updated interface setting status.'),('209','ADMIN','Deactivate','2022-12-16 14:34:10','User ADMIN updated email setting status.'),('209','ADMIN','Activate','2022-12-16 14:34:14','User ADMIN updated email setting status.'),('210','ADMIN','Insert','2022-12-16 15:03:47','User ADMIN inserted page.'),('210','ADMIN','Update','2022-12-16 15:04:10','User ADMIN updated page.'),('211','ADMIN','Insert','2022-12-16 15:04:19','User ADMIN inserted page.'),('212','ADMIN','Insert','2022-12-16 15:15:40','User ADMIN inserted notification setting.'),('213','ADMIN','Insert','2022-12-16 15:36:46','User ADMIN inserted action.'),('214','ADMIN','Insert','2022-12-16 15:37:00','User ADMIN inserted action.'),('215','ADMIN','Insert','2022-12-16 15:37:13','User ADMIN inserted action.'),('215','ADMIN','Update','2022-12-16 15:37:17','User ADMIN updated action.'),('216','ADMIN','Insert','2022-12-16 15:37:34','User ADMIN inserted action.'),('217','ADMIN','Insert','2022-12-16 15:37:47','User ADMIN inserted action.'),('218','ADMIN','Insert','2022-12-16 15:37:59','User ADMIN inserted action.'),('219','ADMIN','Insert','2022-12-16 15:38:19','User ADMIN inserted action.'),('210','ADMIN','Update','2022-12-16 15:43:20','User ADMIN updated page.'),('220','ADMIN','Insert','2022-12-16 16:09:00','User ADMIN inserted country.'),('221','ADMIN','Insert','2022-12-16 16:09:07','User ADMIN inserted state.'),('222','ADMIN','Insert','2022-12-16 16:10:06','User ADMIN inserted country.'),('223','ADMIN','Insert','2022-12-16 16:10:50','User ADMIN inserted country.'),('TL-1','ADMIN','Log In','2022-12-19 13:58:50','User ADMIN logged in.'),('224','ADMIN','Insert','2022-12-19 14:03:33','User ADMIN inserted action.'),('225','ADMIN','Insert','2022-12-19 14:03:50','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-20 10:03:34','User ADMIN logged in.'),('226','ADMIN','Insert','2022-12-20 15:28:46','User ADMIN inserted user account.'),('226','ADMIN','Deactivated','2022-12-20 15:34:47','User ADMIN deactivated user account.'),('226','ADMIN','Lock','2022-12-20 15:34:51','User ADMIN locked user account.'),('226','ADMIN','Unlock','2022-12-20 15:34:54','User ADMIN unlocked user account.'),('226','ADMIN','Activate','2022-12-20 15:35:56','User ADMIN activated user account.'),('226','ADMIN','Deactivated','2022-12-20 15:35:59','User ADMIN deactivated user account.'),('226','ADMIN','Lock','2022-12-20 15:36:02','User ADMIN locked user account.'),('227','ADMIN','Insert','2022-12-20 15:36:37','User ADMIN inserted user account.'),('227','ADMIN','Update','2022-12-20 15:39:51','User ADMIN updated user account.'),('227','ADMIN','Update','2022-12-20 15:39:55','User ADMIN updated user account.'),('227','ADMIN','Activate','2022-12-20 15:43:07','User ADMIN activated user account.'),('227','ADMIN','Lock','2022-12-20 15:43:10','User ADMIN locked user account.'),('227','ADMIN','Deactivated','2022-12-20 15:45:02','User ADMIN deactivated user account.'),('TL-1','ADMIN','Update','2022-12-20 15:54:11','User ADMIN updated user account.'),('228','ADMIN','Insert','2022-12-20 16:02:49','User ADMIN inserted user account.'),('229','ADMIN','Insert','2022-12-20 16:20:09','User ADMIN inserted user account.'),('229','ADMIN','Activate','2022-12-20 16:20:29','User ADMIN activated user account.'),('229','LDAGULTO','Log In','2022-12-20 16:20:35','User LDAGULTO logged in.'),('229','ADMIN','Deactivated','2022-12-20 16:20:49','User ADMIN deactivated user account.'),('229','LDAGULTO','Log Out','2022-12-20 16:20:52','User LDAGULTO logged out.'),('TL-1','ADMIN','Attempt Log In','2022-12-21 11:36:05','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-12-21 11:36:07','User ADMIN logged in.'),('229','ADMIN','Activate','2022-12-21 11:36:29','User ADMIN activated user account.'),('229','LDAGULTO','Log In','2022-12-21 11:36:37','User LDAGULTO logged in.'),('229','ADMIN','Deactivated','2022-12-21 11:36:52','User ADMIN deactivated user account.'),('229','LDAGULTO','Log Out','2022-12-21 11:36:58','User LDAGULTO logged out.'),('229','ADMIN','Activate','2022-12-21 11:37:07','User ADMIN activated user account.'),('TL-1','ADMIN','Update','2022-12-21 11:43:17','User ADMIN updated user account.'),('TL-3','ADMIN','Update','2022-12-21 14:04:29','User ADMIN updated module.'),('230','ADMIN','Insert','2022-12-21 14:27:06','User ADMIN inserted module.'),('231','ADMIN','Insert','2022-12-21 14:27:52','User ADMIN inserted system code.'),('230','ADMIN','Update','2022-12-21 14:28:03','User ADMIN updated module.'),('232','ADMIN','Insert','2022-12-21 14:31:24','User ADMIN inserted module.'),('233','ADMIN','Insert','2022-12-21 14:32:27','User ADMIN inserted module.'),('234','ADMIN','Insert','2022-12-21 14:36:46','User ADMIN inserted module.'),('234','ADMIN','Update','2022-12-21 14:41:28','User ADMIN updated module.'),('235','ADMIN','Insert','2022-12-21 15:11:23','User ADMIN inserted page.'),('236','ADMIN','Insert','2022-12-21 15:13:34','User ADMIN inserted page.'),('237','ADMIN','Insert','2022-12-21 15:13:49','User ADMIN inserted action.'),('238','ADMIN','Insert','2022-12-21 15:14:24','User ADMIN inserted action.'),('239','ADMIN','Insert','2022-12-21 15:14:34','User ADMIN inserted action.'),('240','ADMIN','Insert','2022-12-21 15:14:44','User ADMIN inserted action.'),('241','ADMIN','Insert','2022-12-21 15:15:00','User ADMIN inserted action.'),('242','ADMIN','Insert','2022-12-21 15:20:57','User ADMIN inserted system parameter.'),('229','LDAGULTO','Log In','2022-12-23 09:47:03','User LDAGULTO logged in.'),('229','LDAGULTO','Log Out','2022-12-23 09:47:12','User LDAGULTO logged out.'),('TL-1','ADMIN','Log In','2022-12-23 09:47:16','User ADMIN logged in.'),('235','ADMIN','Update','2022-12-23 10:20:49','User ADMIN updated page.'),('236','ADMIN','Update','2022-12-23 10:21:00','User ADMIN updated page.'),('236','ADMIN','Update','2022-12-23 10:25:18','User ADMIN updated page.'),('235','ADMIN','Update','2022-12-23 10:25:34','User ADMIN updated page.'),('237','ADMIN','Update','2022-12-23 10:28:08','User ADMIN updated action.'),('238','ADMIN','Update','2022-12-23 10:28:11','User ADMIN updated action.'),('239','ADMIN','Update','2022-12-23 10:28:15','User ADMIN updated action.'),('240','ADMIN','Update','2022-12-23 10:28:23','User ADMIN updated action.'),('241','ADMIN','Update','2022-12-23 10:28:28','User ADMIN updated action.'),('242','ADMIN','Update','2022-12-23 11:57:42','User ADMIN updated system parameter.'),('243','ADMIN','Insert','2022-12-23 15:26:50','User ADMIN inserted department.'),('244','ADMIN','Insert','2022-12-23 15:27:15','User ADMIN inserted department.'),('244','ADMIN','Archive','2022-12-23 16:12:37','User ADMIN updated department status.'),('244','ADMIN','Unarchive','2022-12-23 16:12:46','User ADMIN updated department status.'),('243','ADMIN','Archive','2022-12-23 16:16:10','User ADMIN updated department status.'),('243','ADMIN','Unarchive','2022-12-23 16:16:13','User ADMIN updated department status.'),('245','ADMIN','Insert','2022-12-23 16:16:29','User ADMIN inserted department.'),('246','ADMIN','Insert','2022-12-23 16:32:50','User ADMIN inserted page.'),('247','ADMIN','Insert','2022-12-23 16:33:22','User ADMIN inserted page.'),('248','ADMIN','Insert','2022-12-23 16:35:00','User ADMIN inserted action.'),('249','ADMIN','Insert','2022-12-23 16:35:10','User ADMIN inserted action.'),('250','ADMIN','Insert','2022-12-23 16:35:21','User ADMIN inserted action.'),('251','ADMIN','Insert','2022-12-23 16:36:26','User ADMIN inserted action.'),('251','ADMIN','Update','2022-12-23 16:36:29','User ADMIN updated action.'),('252','ADMIN','Insert','2022-12-23 16:36:47','User ADMIN inserted action.'),('253','ADMIN','Insert','2022-12-23 16:42:20','User ADMIN inserted upload setting.'),('230','ADMIN','Update','2022-12-23 16:48:53','User ADMIN updated module.'),('254','ADMIN','Insert','2022-12-23 17:15:09','User ADMIN inserted action.'),('255','ADMIN','Insert','2022-12-23 17:15:25','User ADMIN inserted action.'),('255','ADMIN','Update','2022-12-23 17:15:30','User ADMIN updated action.'),('256','ADMIN','Insert','2022-12-23 17:16:00','User ADMIN inserted action.'),('257','ADMIN','Insert','2022-12-23 17:16:14','User ADMIN inserted action.'),('258','ADMIN','Insert','2022-12-23 17:16:34','User ADMIN inserted action.'),('259','ADMIN','Insert','2022-12-23 17:16:45','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-24 08:04:50','User ADMIN logged in.'),('260','ADMIN','Insert','2022-12-24 08:05:36','User ADMIN inserted action.'),('260','ADMIN','Update','2022-12-24 08:05:38','User ADMIN updated action.'),('261','ADMIN','Insert','2022-12-24 08:05:54','User ADMIN inserted action.'),('262','ADMIN','Insert','2022-12-24 08:06:08','User ADMIN inserted action.'),('260','ADMIN','Update','2022-12-24 08:09:41','User ADMIN updated action.'),('261','ADMIN','Update','2022-12-24 08:09:56','User ADMIN updated action.'),('262','ADMIN','Update','2022-12-24 08:10:09','User ADMIN updated action.'),('263','ADMIN','Insert','2022-12-24 08:46:45','User ADMIN inserted system parameter.'),('242','ADMIN','Update','2022-12-24 08:47:51','User ADMIN updated system parameter.'),('264','ADMIN','Insert','2022-12-24 09:05:22','User ADMIN inserted department.'),('255','ADMIN','Update','2022-12-24 09:32:35','User ADMIN updated action.'),('256','ADMIN','Update','2022-12-24 09:32:50','User ADMIN updated action.'),('257','ADMIN','Update','2022-12-24 09:33:17','User ADMIN updated action.'),('258','ADMIN','Update','2022-12-24 09:34:02','User ADMIN updated action.'),('259','ADMIN','Update','2022-12-24 09:34:16','User ADMIN updated action.'),('259','ADMIN','Update','2022-12-24 09:34:21','User ADMIN updated action.'),('260','ADMIN','Update','2022-12-24 09:34:39','User ADMIN updated action.'),('261','ADMIN','Update','2022-12-24 09:34:53','User ADMIN updated action.'),('262','ADMIN','Update','2022-12-24 09:35:10','User ADMIN updated action.'),('265','ADMIN','Update','2022-12-24 10:20:17','User ADMIN updated upload setting.'),('266','ADMIN','Update','2022-12-24 10:20:23','User ADMIN updated upload setting.'),('267','ADMIN','Update','2022-12-24 10:20:28','User ADMIN updated upload setting.'),('267','ADMIN','Update','2022-12-24 10:20:48','User ADMIN updated upload setting.'),('268','ADMIN','Insert','2022-12-24 10:50:02','User ADMIN inserted action.'),('269','ADMIN','Insert','2022-12-24 10:50:18','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-24 17:08:42','User ADMIN logged in.'),('270','ADMIN','Insert','2022-12-24 17:27:24','User ADMIN inserted system parameter.'),('270','ADMIN','Update','2022-12-24 17:44:11','User ADMIN updated system parameter.'),('271','ADMIN','Insert','2022-12-24 17:44:34','User ADMIN inserted system parameter.'),('272','ADMIN','Insert','2022-12-24 17:45:01','User ADMIN inserted system parameter.'),('273','ADMIN','Insert','2022-12-24 17:45:19','User ADMIN inserted system parameter.'),('TL-1','ADMIN','Log In','2022-12-26 09:18:14','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2022-12-26 12:33:41','User ADMIN logged in.'),('269','ADMIN','Update','2022-12-26 12:33:51','User ADMIN updated action.'),('274','ADMIN','Insert','2022-12-26 12:34:01','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2022-12-27 09:11:10','User ADMIN logged in.'),('275','ADMIN','Insert','2022-12-27 11:06:15','User ADMIN inserted job position.'),('275','ADMIN','Start','2022-12-27 11:11:52','User ADMIN updated job position recruitment status.'),('276','ADMIN','Insert','2022-12-27 11:12:00','User ADMIN inserted job position.'),('277','ADMIN','Insert','2022-12-27 11:13:18','User ADMIN inserted job position.'),('278','ADMIN','Insert','2022-12-27 11:14:06','User ADMIN inserted job position.'),('275','ADMIN','Update','2022-12-27 11:14:42','User ADMIN updated job position.'),('275','ADMIN','Update','2022-12-27 11:14:43','User ADMIN updated job position.'),('275','ADMIN','Update','2022-12-27 11:14:44','User ADMIN updated job position.'),('275','ADMIN','Update','2022-12-27 11:14:44','User ADMIN updated job position.'),('275','ADMIN','Update','2022-12-27 11:14:44','User ADMIN updated job position.'),('275','ADMIN','Update','2022-12-27 11:14:44','User ADMIN updated job position.'),('275','ADMIN','Update','2022-12-27 11:14:45','User ADMIN updated job position.'),('275','ADMIN','Update','2022-12-27 11:15:03','User ADMIN updated job position.'),('275','ADMIN','Update','2022-12-27 11:15:10','User ADMIN updated job position.'),('275','ADMIN','Stop','2022-12-27 11:16:09','User ADMIN updated job position recruitment status.'),('275','ADMIN','Start','2022-12-27 11:16:28','User ADMIN updated job position recruitment status.'),('275','ADMIN','Update','2022-12-27 11:16:32','User ADMIN updated job position.'),('279','ADMIN','Insert','2022-12-27 11:44:51','User ADMIN inserted job position attachment.'),('280','ADMIN','Insert','2022-12-27 11:45:53','User ADMIN inserted job position attachment.'),('280','ADMIN','Update','2022-12-27 11:45:53','User ADMIN updated job position attachment.'),('281','ADMIN','Insert','2022-12-27 11:46:34','User ADMIN inserted job position attachment.'),('281','ADMIN','Update','2022-12-27 11:46:34','User ADMIN updated job position attachment.'),('279','ADMIN','Update','2022-12-27 11:50:17','User ADMIN updated job position attachment.'),('279','ADMIN','Update','2022-12-27 11:52:02','User ADMIN updated job position attachment.'),('279','ADMIN','Update','2022-12-27 11:52:02','User ADMIN updated job position attachment.'),('279','ADMIN','Update','2022-12-27 11:52:15','User ADMIN updated job position attachment.'),('279','ADMIN','Update','2022-12-27 11:52:15','User ADMIN updated job position attachment.'),('276','ADMIN','Update','2022-12-27 11:58:20','User ADMIN updated job position.'),('282','ADMIN','Insert','2022-12-27 11:58:28','User ADMIN inserted job position attachment.'),('282','ADMIN','Update','2022-12-27 11:58:28','User ADMIN updated job position attachment.'),('276','ADMIN','Start','2022-12-27 12:01:11','User ADMIN updated job position recruitment status.'),('276','ADMIN','Stop','2022-12-27 12:01:16','User ADMIN updated job position recruitment status.'),('276','ADMIN','Start','2022-12-27 12:01:25','User ADMIN updated job position recruitment status.'),('276','ADMIN','Stop','2022-12-27 12:58:23','User ADMIN updated job position recruitment status.'),('276','ADMIN','Start','2022-12-27 12:58:28','User ADMIN updated job position recruitment status.'),('276','ADMIN','Update','2022-12-27 13:02:10','User ADMIN updated job position.'),('283','ADMIN','Insert','2022-12-27 13:10:52','User ADMIN inserted job position attachment.'),('283','ADMIN','Update','2022-12-27 13:10:52','User ADMIN updated job position attachment.'),('280','ADMIN','Update','2022-12-27 13:10:57','User ADMIN updated job position attachment.'),('284','ADMIN','Insert','2022-12-27 13:26:09','User ADMIN inserted job position responsibility.'),('276','ADMIN','Update','2022-12-27 13:26:42','User ADMIN updated job position.'),('285','ADMIN','Insert','2022-12-27 13:26:48','User ADMIN inserted job position responsibility.'),('285','ADMIN','Update','2022-12-27 13:31:01','User ADMIN updated job position responsibility.'),('286','ADMIN','Insert','2022-12-27 13:31:19','User ADMIN inserted job position requirement.'),('287','ADMIN','Update','2022-12-27 13:31:21','User ADMIN updated job position requirement.'),('288','ADMIN','Insert','2022-12-27 13:31:29','User ADMIN inserted job position qualification.'),('289','ADMIN','Update','2022-12-27 13:32:48','User ADMIN updated job position qualification.'),('290','ADMIN','Insert','2022-12-27 13:37:37','User ADMIN inserted job position.'),('TL-16','ADMIN','Update','2022-12-27 13:42:10','User ADMIN updated system parameter.'),('TL-291','ADMIN','Insert','2022-12-27 13:53:17','User ADMIN inserted state.'),('TL-292','ADMIN','Insert','2022-12-27 13:53:38','User ADMIN inserted state.'),('TL-293','ADMIN','Insert','2022-12-27 13:55:35','User ADMIN inserted state.'),('TL-292','ADMIN','Update','2022-12-27 13:56:02','User ADMIN updated state.'),('TL-291','ADMIN','Update','2022-12-27 13:56:05','User ADMIN updated state.'),('TL-294','ADMIN','Insert','2022-12-27 13:56:09','User ADMIN inserted state.'),('TL-295','ADMIN','Insert','2022-12-27 13:56:16','User ADMIN inserted state.'),('TL-296','ADMIN','Insert','2022-12-27 13:57:21','User ADMIN inserted state.'),('TL-297','ADMIN','Insert','2022-12-27 13:57:40','User ADMIN inserted job position.'),('TL-297','ADMIN','Update','2022-12-27 16:53:20','User ADMIN updated job position.'),('TL-1','ADMIN','Log In','2022-12-28 11:28:27','User ADMIN logged in.'),('TL-7','ADMIN','Update','2022-12-28 11:53:55','User ADMIN updated action.'),('TL-7','ADMIN','Update','2022-12-28 11:56:30','User ADMIN updated action.'),('TL-298','ADMIN','Insert','2022-12-28 14:05:54','User ADMIN inserted state.'),('TL-299','ADMIN','Insert','2022-12-28 14:47:00','User ADMIN inserted page.'),('TL-300','ADMIN','Insert','2022-12-28 14:47:17','User ADMIN inserted page.'),('TL-301','ADMIN','Insert','2022-12-28 14:50:18','User ADMIN inserted action.'),('TL-302','ADMIN','Insert','2022-12-28 14:50:30','User ADMIN inserted action.'),('TL-303','ADMIN','Insert','2022-12-28 14:50:50','User ADMIN inserted action.'),('TL-303','ADMIN','Update','2022-12-28 14:50:59','User ADMIN updated action.'),('TL-303','ADMIN','Update','2022-12-28 14:51:03','User ADMIN updated action.'),('TL-304','ADMIN','Insert','2022-12-28 14:51:10','User ADMIN inserted action.'),('TL-305','ADMIN','Insert','2022-12-28 14:51:25','User ADMIN inserted action.'),('TL-299','ADMIN','Update','2022-12-28 14:55:51','User ADMIN updated page.'),('229','LDAGULTO','Attempt Log In','2022-12-29 11:58:44','User LDAGULTO attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2022-12-29 11:58:49','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2022-12-29 11:58:54','User ADMIN logged in.'),('TL-306','ADMIN','Insert','2022-12-29 12:42:19','User ADMIN inserted system parameter.'),('TL-307','ADMIN','Insert','2022-12-29 13:45:57','User ADMIN inserted work location.'),('TL-307','ADMIN','Archive','2022-12-29 13:46:04','User ADMIN updated work location status.'),('TL-308','ADMIN','Insert','2022-12-29 13:46:16','User ADMIN inserted work location.'),('TL-308','ADMIN','Archive','2022-12-29 13:46:56','User ADMIN updated work location status.'),('TL-308','ADMIN','Unarchive','2022-12-29 13:47:01','User ADMIN updated work location status.'),('TL-309','ADMIN','Insert','2022-12-29 13:47:10','User ADMIN inserted work location.'),('TL-1','ADMIN','Log In','2022-12-31 11:25:59','User ADMIN logged in.'),('TL-10','ADMIN','Update','2022-12-31 12:41:25','User ADMIN updated page.'),('208','ADMIN','Update','2022-12-31 12:56:50','User ADMIN updated zoom API.'),('208','ADMIN','Update','2022-12-31 12:57:02','User ADMIN updated zoom API.'),('TL-310','ADMIN','Insert','2022-12-31 12:58:12','User ADMIN inserted system parameter.'),('TL-311','ADMIN','Insert','2022-12-31 12:58:28','User ADMIN inserted page.'),('TL-312','ADMIN','Insert','2022-12-31 12:58:49','User ADMIN inserted page.'),('TL-313','ADMIN','Insert','2022-12-31 12:59:07','User ADMIN inserted action.'),('TL-314','ADMIN','Insert','2022-12-31 12:59:20','User ADMIN inserted action.'),('TL-315','ADMIN','Insert','2022-12-31 12:59:30','User ADMIN inserted action.'),('TL-316','ADMIN','Insert','2022-12-31 13:54:45','User ADMIN inserted departure reason.'),('TL-317','ADMIN','Insert','2022-12-31 13:57:48','User ADMIN inserted departure reason.'),('TL-316','ADMIN','Update','2022-12-31 14:00:34','User ADMIN updated departure reason.'),('TL-316','ADMIN','Update','2022-12-31 14:00:41','User ADMIN updated departure reason.'),('TL-318','ADMIN','Insert','2022-12-31 14:03:02','User ADMIN inserted page.'),('TL-319','ADMIN','Insert','2022-12-31 14:03:22','User ADMIN inserted page.'),('TL-320','ADMIN','Insert','2022-12-31 14:03:47','User ADMIN inserted action.'),('TL-321','ADMIN','Insert','2022-12-31 14:03:59','User ADMIN inserted action.'),('TL-322','ADMIN','Insert','2022-12-31 14:04:13','User ADMIN inserted action.'),('TL-323','ADMIN','Insert','2022-12-31 14:04:32','User ADMIN inserted system parameter.'),('TL-324','ADMIN','Insert','2022-12-31 14:15:44','User ADMIN inserted employee type.'),('TL-325','ADMIN','Insert','2022-12-31 14:15:52','User ADMIN inserted employee type.'),('TL-326','ADMIN','Insert','2022-12-31 14:20:50','User ADMIN inserted employee type.'),('TL-1','ADMIN','Log In','2022-12-31 16:22:13','User ADMIN logged in.'),('TL-327','ADMIN','Insert','2022-12-31 16:22:42','User ADMIN inserted employee type.'),('TL-328','ADMIN','Insert','2022-12-31 16:22:51','User ADMIN inserted employee type.'),('TL-328','ADMIN','Update','2022-12-31 16:22:54','User ADMIN updated employee type.'),('TL-1','ADMIN','Log In','2023-01-02 10:19:33','User ADMIN logged in.'),('TL-329','ADMIN','Insert','2023-01-02 10:21:08','User ADMIN inserted page.'),('TL-329','ADMIN','Update','2023-01-02 10:21:11','User ADMIN updated page.'),('TL-330','ADMIN','Insert','2023-01-02 10:21:28','User ADMIN inserted page.'),('TL-331','ADMIN','Insert','2023-01-02 10:26:24','User ADMIN inserted system parameter.'),('TL-1','ADMIN','Attempt Log In','2023-01-02 11:08:58','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2023-01-02 11:09:02','User ADMIN logged in.'),('TL-332','ADMIN','Insert','2023-01-02 11:10:37','User ADMIN inserted action.'),('TL-333','ADMIN','Insert','2023-01-02 11:10:47','User ADMIN inserted action.'),('TL-334','ADMIN','Insert','2023-01-02 11:10:59','User ADMIN inserted action.'),('TL-333','ADMIN','Update','2023-01-02 11:11:12','User ADMIN updated action.'),('TL-335','ADMIN','Insert','2023-01-02 11:13:25','User ADMIN inserted wage type.'),('TL-336','ADMIN','Insert','2023-01-02 11:13:33','User ADMIN inserted wage type.'),('TL-335','ADMIN','Update','2023-01-02 11:13:59','User ADMIN updated wage type.'),('TL-337','ADMIN','Insert','2023-01-02 11:18:46','User ADMIN inserted wage type.'),('TL-337','ADMIN','Update','2023-01-02 11:18:53','User ADMIN updated wage type.'),('TL-338','ADMIN','Insert','2023-01-02 11:22:09','User ADMIN inserted system code.'),('TL-339','ADMIN','Update','2023-01-02 11:22:32','User ADMIN updated system code.'),('TL-340','ADMIN','Update','2023-01-02 11:22:35','User ADMIN updated system code.'),('TL-341','ADMIN','Update','2023-01-02 11:22:40','User ADMIN updated system code.'),('TL-341','ADMIN','Update','2023-01-02 11:22:53','User ADMIN updated system code.'),('TL-342','ADMIN','Insert','2023-01-02 11:23:13','User ADMIN inserted system code.'),('TL-343','ADMIN','Insert','2023-01-02 11:24:40','User ADMIN inserted system code.'),('TL-344','ADMIN','Insert','2023-01-02 11:25:03','User ADMIN inserted system code.'),('TL-345','ADMIN','Insert','2023-01-02 11:25:21','User ADMIN inserted system code.'),('TL-346','ADMIN','Insert','2023-01-02 11:25:34','User ADMIN inserted system code.'),('TL-347','ADMIN','Insert','2023-01-02 11:25:51','User ADMIN inserted system code.'),('TL-348','ADMIN','Insert','2023-01-02 11:26:17','User ADMIN inserted system code.'),('TL-1','ADMIN','Log In','2023-01-05 10:57:07','User ADMIN logged in.'),('TL-3','ADMIN','Update','2023-01-05 11:07:36','User ADMIN updated module.'),('TL-7','ADMIN','Update','2023-01-05 11:14:18','User ADMIN updated action.'),('TL-349','ADMIN','Insert','2023-01-05 13:48:59','User ADMIN inserted job position attachment.'),('TL-349','ADMIN','Update','2023-01-05 13:48:59','User ADMIN updated job position attachment.'),('TL-350','ADMIN','Insert','2023-01-05 13:49:06','User ADMIN inserted job position responsibility.'),('TL-351','ADMIN','Insert','2023-01-05 13:49:09','User ADMIN inserted job position responsibility.'),('TL-1','ADMIN','Log Out','2023-01-05 13:54:40','User ADMIN logged out.'),('TL-1','ADMIN','Attempt Log In','2023-01-05 13:54:50','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2023-01-05 13:55:30','User ADMIN logged in.'),('229','ADMIN','Lock','2023-01-05 13:58:08','User ADMIN locked user account.'),('229','ADMIN','Unlock','2023-01-05 13:58:11','User ADMIN unlocked user account.'),('TL-352','ADMIN','Insert','2023-01-05 14:43:32','User ADMIN inserted job position attachment.'),('TL-352','ADMIN','Update','2023-01-05 14:43:32','User ADMIN updated job position attachment.'),('TL-353','ADMIN','Insert','2023-01-05 14:56:37','User ADMIN inserted page.'),('TL-354','ADMIN','Insert','2023-01-05 14:57:17','User ADMIN inserted page.'),('TL-1','ADMIN','Log In','2023-01-06 14:39:53','User ADMIN logged in.'),('TL-15','ADMIN','Update','2023-01-06 14:41:50','User ADMIN updated system parameter.'),('TL-15','ADMIN','Update','2023-01-06 14:42:13','User ADMIN updated system parameter.'),('TL-15','ADMIN','Update','2023-01-06 14:43:05','User ADMIN updated system parameter.'),('TL-15','ADMIN','Update','2023-01-06 14:43:22','User ADMIN updated system parameter.'),('TL-356','ADMIN','Insert','2023-01-06 15:09:03','User ADMIN inserted country.'),('TL-357','ADMIN','Insert','2023-01-06 15:09:13','User ADMIN inserted country.'),('TL-358','ADMIN','Insert','2023-01-06 15:11:26','User ADMIN inserted country.'),('TL-358','ADMIN','Update','2023-01-06 15:17:08','User ADMIN updated country.'),('TL-1','ADMIN','Log In','2023-01-09 10:07:59','User ADMIN logged in.'),('TL-359','ADMIN','Insert','2023-01-09 10:15:45','User ADMIN inserted action.'),('TL-360','ADMIN','Insert','2023-01-09 11:02:19','User ADMIN inserted action.'),('TL-361','ADMIN','Insert','2023-01-09 11:02:35','User ADMIN inserted action.'),('TL-362','ADMIN','Insert','2023-01-09 11:23:52','User ADMIN inserted system code.'),('TL-363','ADMIN','Insert','2023-01-09 11:23:57','User ADMIN inserted system code.'),('TL-364','ADMIN','Insert','2023-01-09 11:24:59','User ADMIN inserted system code.'),('TL-365','ADMIN','Insert','2023-01-09 11:28:45','User ADMIN inserted system code.'),('68','ADMIN','Update','2023-01-09 11:32:43','User ADMIN updated system parameter.'),('TL-366','ADMIN','Insert','2023-01-09 11:34:17','User ADMIN inserted system code.'),('TL-367','ADMIN','Insert','2023-01-09 11:34:30','User ADMIN inserted system code.'),('TL-368','ADMIN','Insert','2023-01-09 11:34:45','User ADMIN inserted system code.'),('TL-369','ADMIN','Insert','2023-01-09 11:34:59','User ADMIN inserted system code.'),('TL-370','ADMIN','Insert','2023-01-09 11:35:11','User ADMIN inserted system code.'),('TL-370','ADMIN','Update','2023-01-09 11:35:15','User ADMIN updated system code.'),('TL-371','ADMIN','Insert','2023-01-09 11:35:26','User ADMIN inserted system code.'),('TL-372','ADMIN','Insert','2023-01-09 11:35:36','User ADMIN inserted system code.'),('TL-373','ADMIN','Insert','2023-01-09 11:38:36','User ADMIN inserted system code.'),('TL-374','ADMIN','Insert','2023-01-09 11:39:10','User ADMIN inserted system code.'),('TL-375','ADMIN','Insert','2023-01-09 11:39:24','User ADMIN inserted system code.'),('TL-362','ADMIN','Update','2023-01-09 11:39:50','User ADMIN updated system code.'),('TL-376','ADMIN','Insert','2023-01-09 11:56:10','User ADMIN inserted system code.'),('TL-377','ADMIN','Insert','2023-01-09 11:56:21','User ADMIN inserted system code.'),('TL-377','ADMIN','Update','2023-01-09 12:41:37','User ADMIN updated system code.'),('TL-377','ADMIN','Update','2023-01-09 12:42:25','User ADMIN updated system code.'),('TL-378','ADMIN','Insert','2023-01-09 12:42:46','User ADMIN inserted system code.'),('TL-379','ADMIN','Insert','2023-01-09 13:20:07','User ADMIN inserted system parameter.'),('TL-380','ADMIN','Insert','2023-01-09 13:20:21','User ADMIN inserted system parameter.'),('TL-1','ADMIN','Log In','2023-01-16 08:50:53','User ADMIN logged in.'),('TL-381','ADMIN','Insert','2023-01-16 10:28:30','User ADMIN inserted page.'),('TL-382','ADMIN','Insert','2023-01-16 10:28:46','User ADMIN inserted page.'),('TL-383','ADMIN','Insert','2023-01-16 10:31:44','User ADMIN inserted action.'),('TL-384','ADMIN','Insert','2023-01-16 10:31:55','User ADMIN inserted action.'),('TL-385','ADMIN','Insert','2023-01-16 10:32:05','User ADMIN inserted action.'),('TL-386','ADMIN','Insert','2023-01-16 10:32:52','User ADMIN inserted action.'),('TL-386','ADMIN','Update','2023-01-16 10:32:59','User ADMIN updated action.'),('TL-387','ADMIN','Insert','2023-01-16 10:33:14','User ADMIN inserted action.'),('TL-388','ADMIN','Insert','2023-01-16 10:33:26','User ADMIN inserted action.'),('TL-376','ADMIN','Update','2023-01-16 10:58:17','User ADMIN updated system code.'),('TL-377','ADMIN','Update','2023-01-16 10:58:39','User ADMIN updated system code.'),('TL-378','ADMIN','Update','2023-01-16 10:59:08','User ADMIN updated system code.'),('TL-377','ADMIN','Update','2023-01-16 10:59:18','User ADMIN updated system code.'),('TL-389','ADMIN','Insert','2023-01-16 11:11:58','User ADMIN inserted system parameter.'),('TL-381','ADMIN','Update','2023-01-16 13:05:40','User ADMIN updated page.'),('TL-382','ADMIN','Update','2023-01-16 13:05:47','User ADMIN updated page.'),('TL-390','ADMIN','Insert','2023-01-16 14:32:49','User ADMIN inserted working schedule type.'),('TL-391','ADMIN','Insert','2023-01-16 14:43:02','User ADMIN inserted working schedule type.'),('TL-392','ADMIN','Insert','2023-01-16 14:43:44','User ADMIN inserted working schedule type.'),('TL-392','ADMIN','Update','2023-01-16 14:43:46','User ADMIN updated working schedule type.'),('TL-389','ADMIN','Update','2023-01-16 14:44:55','User ADMIN updated system parameter.'),('TL-393','ADMIN','Insert','2023-01-16 14:56:40','User ADMIN inserted working schedule type.'),('TL-394','ADMIN','Insert','2023-01-16 14:56:55','User ADMIN inserted working schedule type.'),('TL-394','ADMIN','Update','2023-01-16 14:57:03','User ADMIN updated working schedule type.'),('TL-393','ADMIN','Update','2023-01-16 14:57:29','User ADMIN updated working schedule type.'),('TL-393','ADMIN','Update','2023-01-16 14:57:31','User ADMIN updated working schedule type.'),('TL-353','ADMIN','Update','2023-01-16 15:14:26','User ADMIN updated page.'),('TL-354','ADMIN','Update','2023-01-16 15:14:29','User ADMIN updated page.'),('TL-395','ADMIN','Insert','2023-01-16 17:10:36','User ADMIN inserted working schedule.'),('TL-396','ADMIN','Insert','2023-01-16 17:14:42','User ADMIN inserted working schedule.'),('TL-397','ADMIN','Insert','2023-01-16 17:18:37','User ADMIN inserted working schedule.'),('TL-1','ADMIN','Log In','2023-01-17 09:01:50','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2023-01-17 17:19:30','User ADMIN logged in.'),('TL-398','ADMIN','Insert','2023-01-17 17:25:18','User ADMIN inserted job position.'),('TL-397','ADMIN','Update','2023-01-17 17:38:46','User ADMIN updated working schedule.'),('TL-1','ADMIN','Log In','2023-01-18 11:35:21','User ADMIN logged in.'),('TL-394','ADMIN','Update','2023-01-18 11:35:54','User ADMIN updated working schedule type.'),('TL-397','ADMIN','Update','2023-01-18 11:36:06','User ADMIN updated working schedule.'),('TL-397','ADMIN','Update','2023-01-18 12:08:21','User ADMIN updated working schedule.'),('TL-1','ADMIN','Log In','2023-01-23 15:04:10','User ADMIN logged in.'),('TL-399','ADMIN','Insert','2023-01-23 15:45:37','User ADMIN inserted working hours.'),('TL-400','ADMIN','Insert','2023-01-23 15:55:48','User ADMIN inserted working hours.'),('TL-397','ADMIN','Update','2023-01-23 15:56:07','User ADMIN updated working schedule.'),('TL-401','ADMIN','Insert','2023-01-23 16:14:07','User ADMIN inserted working hours.'),('TL-401','ADMIN','Update','2023-01-23 16:34:37','User ADMIN updated working hours.'),('TL-401','ADMIN','Update','2023-01-23 16:34:42','User ADMIN updated working hours.'),('TL-1','ADMIN','Attempt Log In','2023-02-01 10:11:24','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2023-02-01 10:17:17','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2023-02-01 10:19:06','User ADMIN attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2023-02-01 10:44:01','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2023-02-01 10:45:54','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2023-02-02 13:08:43','User ADMIN logged in.'),('TL-402','ADMIN','Insert','2023-02-02 13:29:40','User ADMIN inserted working hours.'),('TL-403','ADMIN','Insert','2023-02-02 13:29:56','User ADMIN inserted working hours.'),('TL-404','ADMIN','Insert','2023-02-02 13:30:39','User ADMIN inserted working hours.'),('TL-397','ADMIN','Update','2023-02-02 13:32:36','User ADMIN updated working schedule.'),('TL-405','ADMIN','Insert','2023-02-02 13:33:01','User ADMIN inserted working hours.'),('TL-397','ADMIN','Update','2023-02-02 13:33:14','User ADMIN updated working schedule.'),('TL-406','ADMIN','Insert','2023-02-02 13:39:36','User ADMIN inserted working hours.'),('TL-397','ADMIN','Update','2023-02-02 13:40:18','User ADMIN updated working schedule.'),('TL-407','ADMIN','Insert','2023-02-02 13:43:20','User ADMIN inserted working hours.'),('TL-397','ADMIN','Update','2023-02-02 13:43:29','User ADMIN updated working schedule.'),('TL-397','ADMIN','Update','2023-02-02 13:45:20','User ADMIN updated working schedule.'),('TL-408','ADMIN','Insert','2023-02-02 13:45:35','User ADMIN inserted working hours.'),('TL-1','ADMIN','Log In','2023-02-02 15:53:11','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2023-02-03 10:40:25','User ADMIN logged in.'),('TL-409','ADMIN','Insert','2023-02-03 10:59:11','User ADMIN inserted system parameter.'),('TL-409','ADMIN','Update','2023-02-03 10:59:22','User ADMIN updated system parameter.'),('TL-410','ADMIN','Insert','2023-02-03 11:10:42','User ADMIN inserted page.'),('TL-411','ADMIN','Insert','2023-02-03 11:10:57','User ADMIN inserted page.'),('TL-412','ADMIN','Insert','2023-02-03 11:13:15','User ADMIN inserted action.'),('TL-413','ADMIN','Insert','2023-02-03 11:13:26','User ADMIN inserted action.'),('TL-414','ADMIN','Insert','2023-02-03 11:13:36','User ADMIN inserted action.'),('TL-415','ADMIN','Insert','2023-02-03 11:13:50','User ADMIN inserted action.'),('TL-416','ADMIN','Insert','2023-02-03 11:14:10','User ADMIN inserted action.'),('229','LDAGULTO','Log In','2023-02-04 18:35:37','User LDAGULTO logged in.'),('229','LDAGULTO','Log Out','2023-02-04 18:52:11','User LDAGULTO logged out.'),('TL-1','ADMIN','Log In','2023-02-04 18:52:18','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2023-02-05 10:04:02','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2023-02-05 12:52:23','User ADMIN logged in.'),('TL-1','ADMIN','Log Out','2023-02-05 12:53:28','User ADMIN logged out.'),('229','LDAGULTO','Attempt Log In','2023-02-05 12:53:35','User LDAGULTO attempted to log in.'),('229','LDAGULTO','Attempt Log In','2023-02-05 12:53:37','User LDAGULTO attempted to log in.'),('229','LDAGULTO','Attempt Log In','2023-02-05 12:53:37','User LDAGULTO attempted to log in.'),('229','LDAGULTO','Attempt Log In','2023-02-05 12:53:38','User LDAGULTO attempted to log in.'),('229','LDAGULTO','Attempt Log In','2023-02-05 12:53:38','User LDAGULTO attempted to log in.'),('TL-1','ADMIN','Attempt Log In','2023-02-05 12:57:06','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2023-02-05 12:57:09','User ADMIN logged in.'),('229','ADMIN','Unlock','2023-02-05 13:01:21','User ADMIN unlocked user account.'),('229','ADMIN','Deactivated','2023-02-05 13:01:31','User ADMIN deactivated user account.'),('229','ADMIN','Activate','2023-02-05 13:01:36','User ADMIN activated user account.'),('TL-1','ADMIN','Log In','2023-02-06 15:00:32','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2023-02-07 10:45:09','User ADMIN logged in.'),('TL-417','ADMIN','Insert','2023-02-07 10:52:56','User ADMIN inserted system code.'),('TL-418','ADMIN','Insert','2023-02-07 10:54:31','User ADMIN inserted system code.'),('TL-419','ADMIN','Insert','2023-02-07 10:54:47','User ADMIN inserted system code.'),('TL-420','ADMIN','Insert','2023-02-07 10:55:12','User ADMIN inserted system code.'),('TL-421','ADMIN','Insert','2023-02-07 10:55:24','User ADMIN inserted system code.'),('TL-422','ADMIN','Insert','2023-02-07 10:55:47','User ADMIN inserted system code.'),('TL-423','ADMIN','Insert','2023-02-07 10:55:59','User ADMIN inserted system code.'),('TL-424','ADMIN','Insert','2023-02-07 10:56:12','User ADMIN inserted system code.'),('TL-425','ADMIN','Insert','2023-02-07 10:56:26','User ADMIN inserted system code.'),('TL-426','ADMIN','Insert','2023-02-07 10:56:36','User ADMIN inserted system code.'),('TL-427','ADMIN','Insert','2023-02-07 10:56:50','User ADMIN inserted system code.'),('TL-428','ADMIN','Insert','2023-02-07 10:57:03','User ADMIN inserted system code.'),('TL-429','ADMIN','Insert','2023-02-07 10:57:15','User ADMIN inserted system code.'),('TL-430','ADMIN','Insert','2023-02-07 10:57:25','User ADMIN inserted system code.'),('TL-431','ADMIN','Insert','2023-02-07 10:57:35','User ADMIN inserted system code.'),('TL-432','ADMIN','Insert','2023-02-07 10:57:47','User ADMIN inserted system code.'),('TL-433','ADMIN','Insert','2023-02-07 10:57:59','User ADMIN inserted system code.'),('TL-434','ADMIN','Insert','2023-02-07 10:58:07','User ADMIN inserted system code.'),('TL-435','ADMIN','Insert','2023-02-07 10:58:16','User ADMIN inserted system code.'),('TL-436','ADMIN','Insert','2023-02-07 10:58:25','User ADMIN inserted system code.'),('TL-437','ADMIN','Insert','2023-02-07 10:58:40','User ADMIN inserted system code.'),('TL-438','ADMIN','Insert','2023-02-07 10:58:51','User ADMIN inserted system code.'),('68','ADMIN','Update','2023-02-07 11:04:30','User ADMIN updated system parameter.'),('TL-439','ADMIN','Insert','2023-02-07 11:06:00','User ADMIN inserted system code.'),('TL-440','ADMIN','Insert','2023-02-07 11:06:32','User ADMIN inserted system code.'),('TL-441','ADMIN','Insert','2023-02-07 11:06:42','User ADMIN inserted system code.'),('TL-442','ADMIN','Insert','2023-02-07 11:07:03','User ADMIN inserted system code.'),('TL-443','ADMIN','Insert','2023-02-07 11:07:35','User ADMIN inserted system code.'),('TL-444','ADMIN','Insert','2023-02-07 11:07:45','User ADMIN inserted system code.'),('TL-445','ADMIN','Insert','2023-02-07 11:07:58','User ADMIN inserted system code.'),('TL-446','ADMIN','Insert','2023-02-07 11:08:11','User ADMIN inserted system code.'),('TL-447','ADMIN','Insert','2023-02-07 11:08:41','User ADMIN inserted system code.'),('TL-448','ADMIN','Insert','2023-02-07 11:10:34','User ADMIN inserted system code.'),('TL-449','ADMIN','Insert','2023-02-07 11:11:02','User ADMIN inserted system code.'),('TL-450','ADMIN','Insert','2023-02-07 11:11:11','User ADMIN inserted system code.'),('TL-451','ADMIN','Insert','2023-02-07 11:11:19','User ADMIN inserted system code.'),('TL-452','ADMIN','Insert','2023-02-07 11:11:28','User ADMIN inserted system code.'),('TL-453','ADMIN','Insert','2023-02-07 11:11:40','User ADMIN inserted system code.'),('TL-454','ADMIN','Insert','2023-02-07 11:11:48','User ADMIN inserted system code.'),('TL-455','ADMIN','Insert','2023-02-07 11:12:41','User ADMIN inserted system code.'),('TL-456','ADMIN','Insert','2023-02-07 11:25:45','User ADMIN inserted system code.'),('TL-457','ADMIN','Insert','2023-02-07 11:25:59','User ADMIN inserted system code.'),('TL-458','ADMIN','Insert','2023-02-07 11:26:16','User ADMIN inserted system code.'),('TL-459','ADMIN','Insert','2023-02-07 11:26:25','User ADMIN inserted system code.'),('TL-460','ADMIN','Insert','2023-02-07 11:26:36','User ADMIN inserted system code.'),('TL-461','ADMIN','Insert','2023-02-07 11:26:43','User ADMIN inserted system code.'),('TL-461','ADMIN','Update','2023-02-07 11:27:00','User ADMIN updated system code.'),('TL-462','ADMIN','Insert','2023-02-07 11:27:10','User ADMIN inserted system code.'),('TL-463','ADMIN','Insert','2023-02-07 11:27:18','User ADMIN inserted system code.'),('TL-464','ADMIN','Insert','2023-02-07 11:33:03','User ADMIN inserted system code.'),('68','ADMIN','Update','2023-02-07 11:34:46','User ADMIN updated system parameter.'),('TL-465','ADMIN','Insert','2023-02-07 12:57:34','User ADMIN inserted system code.'),('TL-466','ADMIN','Insert','2023-02-07 12:57:47','User ADMIN inserted system code.'),('TL-467','ADMIN','Insert','2023-02-07 12:58:00','User ADMIN inserted system code.'),('TL-468','ADMIN','Insert','2023-02-07 12:59:29','User ADMIN inserted system code.'),('TL-469','ADMIN','Insert','2023-02-07 13:00:40','User ADMIN inserted system code.'),('TL-470','ADMIN','Insert','2023-02-07 13:04:40','User ADMIN inserted system code.'),('TL-471','ADMIN','Insert','2023-02-07 13:04:57','User ADMIN inserted system code.'),('TL-472','ADMIN','Insert','2023-02-07 13:10:26','User ADMIN inserted system code.'),('TL-473','ADMIN','Insert','2023-02-07 13:10:38','User ADMIN inserted system code.'),('TL-474','ADMIN','Insert','2023-02-07 13:10:49','User ADMIN inserted system code.'),('TL-475','ADMIN','Insert','2023-02-07 14:55:19','User ADMIN inserted system code.'),('TL-476','ADMIN','Insert','2023-02-07 14:57:15','User ADMIN inserted system code.'),('TL-477','ADMIN','Insert','2023-02-07 14:57:24','User ADMIN inserted system code.'),('TL-477','ADMIN','Update','2023-02-07 14:57:35','User ADMIN updated system code.'),('TL-478','ADMIN','Insert','2023-02-07 14:57:46','User ADMIN inserted system code.'),('TL-479','ADMIN','Insert','2023-02-07 14:58:01','User ADMIN inserted system code.'),('TL-480','ADMIN','Insert','2023-02-07 14:58:15','User ADMIN inserted system code.'),('TL-481','ADMIN','Insert','2023-02-07 14:58:24','User ADMIN inserted system code.'),('TL-482','ADMIN','Insert','2023-02-07 14:58:38','User ADMIN inserted system code.'),('TL-483','ADMIN','Insert','2023-02-07 14:58:46','User ADMIN inserted system code.'),('TL-484','ADMIN','Insert','2023-02-07 14:59:05','User ADMIN inserted system code.'),('TL-485','ADMIN','Insert','2023-02-07 14:59:20','User ADMIN inserted system code.'),('TL-486','ADMIN','Insert','2023-02-07 14:59:34','User ADMIN inserted system code.'),('TL-487','ADMIN','Insert','2023-02-07 15:00:02','User ADMIN inserted system code.'),('TL-488','ADMIN','Insert','2023-02-07 15:01:40','User ADMIN inserted system code.'),('TL-489','ADMIN','Insert','2023-02-07 15:01:51','User ADMIN inserted system code.'),('TL-490','ADMIN','Insert','2023-02-07 15:05:08','User ADMIN inserted system code.'),('TL-491','ADMIN','Insert','2023-02-07 15:05:23','User ADMIN inserted system code.'),('TL-492','ADMIN','Insert','2023-02-07 15:05:33','User ADMIN inserted system code.'),('TL-493','ADMIN','Insert','2023-02-07 15:05:47','User ADMIN inserted system code.'),('TL-494','ADMIN','Insert','2023-02-07 15:06:00','User ADMIN inserted system code.'),('TL-495','ADMIN','Insert','2023-02-07 15:07:01','User ADMIN inserted system code.'),('TL-496','ADMIN','Insert','2023-02-07 15:07:21','User ADMIN inserted system code.'),('TL-497','ADMIN','Insert','2023-02-07 15:14:10','User ADMIN inserted system code.'),('TL-498','ADMIN','Insert','2023-02-07 15:14:18','User ADMIN inserted system code.'),('TL-499','ADMIN','Insert','2023-02-07 15:14:28','User ADMIN inserted system code.'),('TL-412','ADMIN','Update','2023-02-07 15:35:04','User ADMIN updated action.'),('TL-413','ADMIN','Update','2023-02-07 15:35:14','User ADMIN updated action.'),('TL-414','ADMIN','Update','2023-02-07 15:35:23','User ADMIN updated action.'),('TL-410','ADMIN','Update','2023-02-07 15:37:10','User ADMIN updated page.'),('TL-411','ADMIN','Update','2023-02-07 15:37:23','User ADMIN updated page.'),('TL-409','ADMIN','Update','2023-02-07 15:39:03','User ADMIN updated system parameter.'),('TL-1','ADMIN','Log In','2023-02-08 13:20:52','User ADMIN logged in.'),('TL-500','ADMIN','Insert','2023-02-08 13:23:23','User ADMIN inserted page.'),('TL-501','ADMIN','Insert','2023-02-08 13:23:41','User ADMIN inserted page.'),('TL-502','ADMIN','Insert','2023-02-08 13:23:55','User ADMIN inserted action.'),('TL-503','ADMIN','Insert','2023-02-08 13:24:09','User ADMIN inserted action.'),('TL-504','ADMIN','Insert','2023-02-08 13:24:33','User ADMIN inserted action.'),('TL-505','ADMIN','Insert','2023-02-08 13:37:53','User ADMIN inserted action.'),('TL-506','ADMIN','Insert','2023-02-08 13:38:11','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2023-02-09 08:55:44','User ADMIN logged in.'),('230','ADMIN','Update','2023-02-09 11:06:11','User ADMIN updated module.'),('TL-1','ADMIN','Log In','2023-02-12 09:08:06','User ADMIN logged in.'),('229','LDAGULTO','Log In','2023-02-13 08:45:13','User LDAGULTO logged in.'),('229','LDAGULTO','Log Out','2023-02-13 08:45:17','User LDAGULTO logged out.'),('TL-1','ADMIN','Log In','2023-02-13 08:45:21','User ADMIN logged in.'),('TL-1','ADMIN','Log Out','2023-02-13 17:36:26','User ADMIN logged out.'),('229','LDAGULTO','Log In','2023-02-14 09:38:50','User LDAGULTO logged in.'),('229','LDAGULTO','Log Out','2023-02-14 09:38:52','User LDAGULTO logged out.'),('TL-1','ADMIN','Log In','2023-02-14 09:38:56','User ADMIN logged in.'),('TL-1','ADMIN','Log Out','2023-02-14 10:25:57','User ADMIN logged out.'),('TL-1','ADMIN','Log In','2023-02-14 10:29:41','User ADMIN logged in.'),('TL-10','ADMIN','Update','2023-02-14 10:29:50','User ADMIN updated page.'),('TL-507','ADMIN','Insert','2023-02-14 10:40:47','User ADMIN inserted ID type.'),('TL-507','ADMIN','Update','2023-02-14 10:41:14','User ADMIN updated ID type.'),('TL-1','ADMIN','Log Out','2023-02-14 11:08:56','User ADMIN logged out.'),('TL-1','ADMIN','Log In','2023-02-14 11:10:50','User ADMIN logged in.'),('229','ADMIN','Lock','2023-02-14 11:11:08','User ADMIN locked user account.'),('229','ADMIN','Unlock','2023-02-14 11:13:37','User ADMIN unlocked user account.'),('229','LDAGULTO','Log In','2023-02-14 11:13:41','User LDAGULTO logged in.'),('229','LDAGULTO','Log Out','2023-02-14 11:13:43','User LDAGULTO logged out.'),('229','LDAGULTO','Attempt Log In','2023-02-14 11:13:48','User LDAGULTO attempted to log in.'),('229','ADMIN','Deactivated','2023-02-14 11:14:10','User ADMIN deactivated user account.'),('229','ADMIN','Activate','2023-02-14 11:17:27','User ADMIN activated user account.'),('229','LDAGULTO','Attempt Log In','2023-02-14 11:17:29','User LDAGULTO attempted to log in.'),('229','LDAGULTO','Attempt Log In','2023-02-14 11:17:57','User LDAGULTO attempted to log in.'),('229','ADMIN','Update','2023-02-14 12:22:44','User ADMIN updated user account.'),('229','LDAGULTO','Attempt Log In','2023-02-14 12:29:39','User LDAGULTO attempted to log in.'),('229','LDAGULTO','Attempt Log In','2023-02-14 12:29:43','User LDAGULTO attempted to log in.'),('229','ADMIN','Unlock','2023-02-14 12:30:41','User ADMIN unlocked user account.'),('229','LDAGULTO','Log In','2023-02-14 12:30:44','User LDAGULTO logged in.'),('229','LDAGULTO','Log Out','2023-02-14 12:30:46','User LDAGULTO logged out.'),('229','ADMIN','Update','2023-02-14 13:22:30','User ADMIN updated user account.'),('229','LDAGULTO','Attempt Log In','2023-02-14 13:38:26','User LDAGULTO attempted to log in.'),('229','LDAGULTO','Attempt Log In','2023-02-14 13:39:20','User LDAGULTO attempted to log in.'),('229','LDAGULTO','Attempt Log In','2023-02-14 13:39:21','User LDAGULTO attempted to log in.'),('229','LDAGULTO','Attempt Log In','2023-02-14 13:39:24','User LDAGULTO attempted to log in.'),('229','LDAGULTO','Attempt Log In','2023-02-14 13:39:25','User LDAGULTO attempted to log in.'),('229','LDAGULTO','Attempt Log In','2023-02-14 13:54:55','User LDAGULTO attempted to log in.'),('229','LDAGULTO','Log In','2023-02-14 13:54:59','User LDAGULTO logged in.'),('229','LDAGULTO','Log Out','2023-02-14 13:55:01','User LDAGULTO logged out.'),('TL-508','ADMIN','Insert','2023-02-14 14:19:10','User ADMIN inserted user account.'),('TL-8','ADMIN','Update','2023-02-14 14:38:07','User ADMIN updated action.'),('TL-1','ADMIN','Log Out','2023-02-14 14:50:01','User ADMIN logged out.'),('TL-1','ADMIN','Log In','2023-02-14 14:50:06','User ADMIN logged in.'),('TL-1','ADMIN','Log Out','2023-02-14 14:51:38','User ADMIN logged out.'),('TL-1','ADMIN','Log In','2023-02-14 14:52:01','User ADMIN logged in.'),('TL-7','ADMIN','Update','2023-02-14 15:59:45','User ADMIN updated action.'),('TL-7','ADMIN','Update','2023-02-14 15:59:49','User ADMIN updated action.'),('TL-7','ADMIN','Update','2023-02-14 15:59:54','User ADMIN updated action.'),('TL-7','ADMIN','Update','2023-02-14 16:00:24','User ADMIN updated action.'),('TL-7','ADMIN','Update','2023-02-14 16:00:28','User ADMIN updated action.'),('TL-7','ADMIN','Update','2023-02-14 16:00:37','User ADMIN updated action.'),('TL-7','ADMIN','Update','2023-02-14 16:00:41','User ADMIN updated action.'),('TL-7','ADMIN','Update','2023-02-14 16:01:16','User ADMIN updated action.'),('TL-509','ADMIN','Insert','2023-02-14 16:01:39','User ADMIN inserted action.'),('TL-509','ADMIN','Update','2023-02-14 16:01:46','User ADMIN updated action.'),('TL-509','ADMIN','Update','2023-02-14 16:01:49','User ADMIN updated action.'),('TL-509','ADMIN','Update','2023-02-14 16:35:09','User ADMIN updated action.'),('TL-509','ADMIN','Update','2023-02-14 16:35:14','User ADMIN updated action.'),('TL-509','ADMIN','Update','2023-02-14 16:35:25','User ADMIN updated action.'),('TL-509','ADMIN','Update','2023-02-14 16:46:26','User ADMIN updated action.'),('TL-506','ADMIN','Update','2023-02-14 17:04:39','User ADMIN updated action.'),('TL-506','ADMIN','Update','2023-02-14 17:30:50','User ADMIN updated action.'),('TL-1','ADMIN','Attempt Log In','2023-02-15 08:37:50','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2023-02-15 08:38:03','User ADMIN logged in.'),('TL-510','ADMIN','Insert','2023-02-15 10:01:59','User ADMIN inserted action.'),('TL-511','ADMIN','Insert','2023-02-15 10:02:24','User ADMIN inserted action.'),('TL-512','ADMIN','Insert','2023-02-15 10:04:06','User ADMIN inserted action.'),('TL-513','ADMIN','Insert','2023-02-15 10:04:20','User ADMIN inserted action.'),('TL-514','ADMIN','Insert','2023-02-15 10:16:04','User ADMIN inserted action.'),('TL-506','ADMIN','Update','2023-02-15 10:20:05','User ADMIN updated action.'),('TL-506','ADMIN','Update','2023-02-15 10:22:17','User ADMIN updated action.'),('TL-506','ADMIN','Update','2023-02-15 10:22:44','User ADMIN updated action.'),('TL-506','ADMIN','Update','2023-02-15 10:24:19','User ADMIN updated action.'),('TL-506','ADMIN','Update','2023-02-15 10:24:42','User ADMIN updated action.'),('TL-506','ADMIN','Update','2023-02-15 10:24:50','User ADMIN updated action.'),('TL-506','ADMIN','Update','2023-02-15 10:25:20','User ADMIN updated action.'),('TL-506','ADMIN','Update','2023-02-15 10:25:32','User ADMIN updated action.'),('TL-506','ADMIN','Update','2023-02-15 10:46:07','User ADMIN updated action.'),('119','ADMIN','Update','2023-02-15 11:24:36','User ADMIN updated company.'),('119','ADMIN','Update','2023-02-15 11:25:11','User ADMIN updated company.'),('119','ADMIN','Update','2023-02-15 11:28:04','User ADMIN updated company.'),('119','ADMIN','Update','2023-02-15 11:31:21','User ADMIN updated company.'),('TL-515','ADMIN','Insert','2023-02-15 11:35:36','User ADMIN inserted company.'),('TL-515','ADMIN','Update','2023-02-15 11:35:36','User ADMIN updated company logo.'),('TL-516','ADMIN','Insert','2023-02-15 11:37:24','User ADMIN inserted company.'),('TL-516','ADMIN','Update','2023-02-15 11:37:24','User ADMIN updated company logo.'),('TL-517','ADMIN','Insert','2023-02-15 11:41:13','User ADMIN inserted company.'),('TL-517','ADMIN','Update','2023-02-15 11:41:13','User ADMIN updated company logo.'),('TL-355','ADMIN','Update','2023-02-15 12:31:49','User ADMIN updated country.'),('TL-357','ADMIN','Update','2023-02-15 12:33:30','User ADMIN updated country.'),('264','ADMIN','Update','2023-02-15 12:50:42','User ADMIN updated department.'),('264','ADMIN','Update','2023-02-15 12:50:47','User ADMIN updated department.'),('264','ADMIN','Update','2023-02-15 12:52:16','User ADMIN updated department.'),('264','ADMIN','Update','2023-02-15 12:52:26','User ADMIN updated department.'),('TL-518','ADMIN','Insert','2023-02-15 13:04:04','User ADMIN inserted departure reason.'),('TL-518','ADMIN','Update','2023-02-15 13:04:45','User ADMIN updated departure reason.'),('TL-518','ADMIN','Update','2023-02-15 13:06:26','User ADMIN updated departure reason.'),('209','ADMIN','Update','2023-02-15 13:22:37','User ADMIN updated email setting.'),('209','ADMIN','Deactivate','2023-02-15 13:22:44','User ADMIN updated email setting status.'),('209','ADMIN','Deactivate','2023-02-15 13:22:47','User ADMIN updated email setting status.'),('209','ADMIN','Deactivate','2023-02-15 13:22:51','User ADMIN updated email setting status.'),('209','ADMIN','Activate','2023-02-15 13:23:05','User ADMIN updated email setting status.'),('TL-519','ADMIN','Insert','2023-02-15 13:34:31','User ADMIN inserted employee type.'),('TL-519','ADMIN','Update','2023-02-15 13:34:35','User ADMIN updated employee type.'),('TL-519','ADMIN','Update','2023-02-15 13:36:49','User ADMIN updated employee type.'),('TL-519','ADMIN','Update','2023-02-15 13:36:54','User ADMIN updated employee type.'),('TL-507','ADMIN','Update','2023-02-15 13:42:18','User ADMIN updated ID type.'),('TL-507','ADMIN','Update','2023-02-15 13:42:20','User ADMIN updated ID type.'),('TL-520','ADMIN','Insert','2023-02-15 13:42:34','User ADMIN inserted ID type.'),('TL-520','ADMIN','Update','2023-02-15 13:44:26','User ADMIN updated ID type.'),('TL-398','ADMIN','Update','2023-02-15 14:26:44','User ADMIN updated job position.'),('TL-398','ADMIN','Update','2023-02-15 14:27:32','User ADMIN updated job position.'),('TL-398','ADMIN','Update','2023-02-15 14:27:35','User ADMIN updated job position.'),('209','ADMIN','Update','2023-02-15 14:33:39','User ADMIN updated email setting.'),('TL-3','ADMIN','Update','2023-02-15 15:04:40','User ADMIN updated module.'),('TL-3','ADMIN','Update','2023-02-15 15:05:35','User ADMIN updated module.'),('TL-3','ADMIN','Update','2023-02-15 15:10:07','User ADMIN updated module.'),('212','ADMIN','Update','2023-02-15 15:34:54','User ADMIN updated notification setting.'),('212','ADMIN','Update','2023-02-15 15:35:32','User ADMIN updated notification setting.'),('212','ADMIN','Update','2023-02-15 15:36:35','User ADMIN updated notification setting.'),('TL-10','ADMIN','Update','2023-02-15 15:43:49','User ADMIN updated page.'),('TL-2','ADMIN','Update','2023-02-15 15:53:09','User ADMIN updated role.'),('TL-2','ADMIN','Update','2023-02-15 15:53:13','User ADMIN updated role.'),('TL-2','ADMIN','Update','2023-02-15 15:54:29','User ADMIN updated role.'),('TL-521','ADMIN','Insert','2023-02-15 16:00:10','User ADMIN inserted state.'),('TL-521','ADMIN','Update','2023-02-15 16:00:13','User ADMIN updated state.'),('TL-15','ADMIN','Update','2023-02-15 16:17:35','User ADMIN updated system parameter.'),('TL-15','ADMIN','Update','2023-02-15 16:17:36','User ADMIN updated system parameter.'),('TL-1','ADMIN','Update','2023-02-15 16:38:57','User ADMIN updated user account.'),('TL-1','ADMIN','Update','2023-02-15 16:39:08','User ADMIN updated user account.'),('TL-337','ADMIN','Update','2023-02-15 16:43:14','User ADMIN updated wage type.'),('TL-309','ADMIN','Update','2023-02-15 16:48:13','User ADMIN updated work location.'),('TL-309','ADMIN','Update','2023-02-15 16:48:16','User ADMIN updated work location.'),('TL-309','ADMIN','Update','2023-02-15 16:48:20','User ADMIN updated work location.'),('TL-397','ADMIN','Update','2023-02-15 16:54:53','User ADMIN updated working schedule.'),('TL-393','ADMIN','Update','2023-02-15 17:02:24','User ADMIN updated working schedule type.'),('TL-393','ADMIN','Update','2023-02-15 17:02:30','User ADMIN updated working schedule type.'),('TL-393','ADMIN','Update','2023-02-15 17:02:33','User ADMIN updated working schedule type.'),('208','ADMIN','Update','2023-02-15 17:21:32','User ADMIN updated zoom API.'),('208','ADMIN','Update','2023-02-15 17:21:41','User ADMIN updated zoom API.'),('208','ADMIN','Update','2023-02-15 17:21:42','User ADMIN updated zoom API.'),('TL-508','ADMIN','Activate','2023-02-15 17:24:16','User ADMIN activated user account.'),('TL-355','ADMIN','Update','2023-02-15 17:24:53','User ADMIN updated country.'),('TL-1','ADMIN','Log In','2023-02-16 08:25:59','User ADMIN logged in.'),('264','ADMIN','Archive','2023-02-16 08:56:29','User ADMIN updated department status.'),('264','ADMIN','Unarchive','2023-02-16 08:56:42','User ADMIN updated department status.'),('TL-7','ADMIN','Update','2023-02-16 14:59:21','User ADMIN updated action.'),('TL-1','ADMIN','Update','2023-02-16 15:00:27','User ADMIN updated user account.'),('TL-1','ADMIN','Lock','2023-02-16 15:00:33','User ADMIN locked user account.'),('TL-1','ADMIN','Log Out','2023-02-16 15:00:36','User ADMIN logged out.'),('TL-1','ADMIN','Log In','2023-02-16 15:01:05','User ADMIN logged in.'),('TL-3','ADMIN','Update','2023-02-16 15:26:12','User ADMIN updated module.'),('TL-1','ADMIN','Log In','2023-02-17 11:04:21','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2023-02-18 15:21:28','User ADMIN logged in.'),('TL-522','ADMIN','Insert','2023-02-18 15:21:44','User ADMIN inserted action.'),('TL-522','ADMIN','Update','2023-02-18 15:21:52','User ADMIN updated action.'),('TL-523','ADMIN','Insert','2023-02-18 16:50:30','User ADMIN inserted working hours.'),('TL-524','ADMIN','Insert','2023-02-18 16:52:08','User ADMIN inserted working hours.'),('TL-525','ADMIN','Insert','2023-02-18 16:52:27','User ADMIN inserted working hours.'),('TL-524','ADMIN','Update','2023-02-18 16:55:34','User ADMIN updated working hours.'),('TL-524','ADMIN','Update','2023-02-18 16:55:39','User ADMIN updated working hours.'),('TL-397','ADMIN','Update','2023-02-18 18:02:00','User ADMIN updated working schedule.'),('TL-524','ADMIN','Update','2023-02-18 18:02:07','User ADMIN updated working hours.'),('TL-397','ADMIN','Update','2023-02-18 18:52:38','User ADMIN updated working schedule.'),('TL-397','ADMIN','Update','2023-02-18 20:03:19','User ADMIN updated working schedule.'),('229','ADMIN','Lock','2023-02-18 20:09:00','User ADMIN locked user account.'),('229','ADMIN','Unlock','2023-02-18 20:09:04','User ADMIN unlocked user account.'),('229','ADMIN','Deactivated','2023-02-18 20:09:07','User ADMIN deactivated user account.'),('229','ADMIN','Activate','2023-02-18 20:09:10','User ADMIN activated user account.'),('TL-1','ADMIN','Log In','2023-02-21 12:32:44','User ADMIN logged in.'),('TL-397','ADMIN','Update','2023-02-21 12:33:47','User ADMIN updated working schedule.'),('TL-397','ADMIN','Update','2023-02-21 12:35:44','User ADMIN updated working schedule.'),('TL-397','ADMIN','Update','2023-02-21 12:35:56','User ADMIN updated working schedule.'),('TL-33','ADMIN','Update','2023-02-21 13:04:50','User ADMIN updated system parameter.'),('TL-526','ADMIN','Insert','2023-02-21 13:08:32','User ADMIN inserted company.'),('TL-527','ADMIN','Insert','2023-02-21 13:12:31','User ADMIN inserted employee type.'),('TL-528','ADMIN','Insert','2023-02-21 13:12:40','User ADMIN inserted employee type.'),('TL-1','ADMIN','Log In','2023-03-06 11:54:37','User ADMIN logged in.'),('TL-7','ADMIN','Update','2023-03-06 17:11:55','User ADMIN updated action.'),('TL-7','ADMIN','Update','2023-03-06 17:12:13','User ADMIN updated action.'),('TL-7','ADMIN','Update','2023-03-06 17:12:56','User ADMIN updated action.'),('TL-1','ADMIN','Log In','2023-03-07 09:39:51','User ADMIN logged in.'),('TL-7','ADMIN','Update','2023-03-07 09:40:06','User ADMIN updated action.'),('TL-1','ADMIN','Log In','2023-03-09 13:28:23','User ADMIN logged in.'),('TL-3','ADMIN','Update','2023-03-09 13:28:57','User ADMIN updated module.'),('TL-3','ADMIN','Update','2023-03-09 13:29:00','User ADMIN updated module.'),('TL-3','ADMIN','Update','2023-03-09 13:29:07','User ADMIN updated module.'),('TL-3','ADMIN','Update','2023-03-09 13:32:37','User ADMIN updated module.'),('TL-3','ADMIN','Update','2023-03-09 13:34:09','User ADMIN updated module.'),('TL-3','ADMIN','Update','2023-03-09 13:34:15','User ADMIN updated module.'),('TL-3','ADMIN','Update','2023-03-09 13:40:35','User ADMIN updated module.'),('TL-3','ADMIN','Update','2023-03-09 13:40:52','User ADMIN updated module.'),('TL-3','ADMIN','Update','2023-03-09 13:40:58','User ADMIN updated module.'),('TL-3','ADMIN','Update','2023-03-09 13:41:00','User ADMIN updated module.'),('TL-10','ADMIN','Update','2023-03-09 13:44:21','User ADMIN updated page.'),('TL-10','ADMIN','Update','2023-03-09 13:44:24','User ADMIN updated page.'),('TL-355','ADMIN','Update','2023-03-09 13:44:32','User ADMIN updated country.'),('TL-355','ADMIN','Update','2023-03-09 14:41:09','User ADMIN updated country.'),('TL-529','ADMIN','Insert','2023-03-09 14:51:01','User ADMIN inserted action.'),('TL-530','ADMIN','Insert','2023-03-09 14:51:57','User ADMIN inserted action.'),('TL-530','ADMIN','Update','2023-03-09 14:55:18','User ADMIN updated action.'),('TL-530','ADMIN','Update','2023-03-09 14:55:20','User ADMIN updated action.'),('TL-530','ADMIN','Update','2023-03-09 14:55:25','User ADMIN updated action.'),('TL-530','ADMIN','Update','2023-03-09 14:58:39','User ADMIN updated action.'),('TL-3','ADMIN','Update','2023-03-09 15:42:00','User ADMIN updated module.'),('TL-3','ADMIN','Update','2023-03-09 15:42:03','User ADMIN updated module.'),('TL-531','ADMIN','Insert','2023-03-09 15:42:45','User ADMIN inserted page.'),('TL-531','ADMIN','Update','2023-03-09 15:42:49','User ADMIN updated page.'),('TL-532','ADMIN','Insert','2023-03-09 15:49:04','User ADMIN inserted country.'),('TL-533','ADMIN','Insert','2023-03-09 15:49:10','User ADMIN inserted state.'),('TL-533','ADMIN','Update','2023-03-09 15:49:14','User ADMIN updated state.'),('TL-534','ADMIN','Insert','2023-03-09 15:49:46','User ADMIN inserted email setting.'),('TL-534','ADMIN','Update','2023-03-09 15:49:49','User ADMIN updated email setting.'),('TL-534','ADMIN','Activate','2023-03-09 15:49:52','User ADMIN updated email setting status.'),('TL-534','ADMIN','Deactivate','2023-03-09 15:49:54','User ADMIN updated email setting status.'),('TL-535','ADMIN','Insert','2023-03-09 15:50:06','User ADMIN inserted interface setting.'),('TL-535','ADMIN','Activate','2023-03-09 15:50:11','User ADMIN updated interface setting status.'),('TL-535','ADMIN','Deactivate','2023-03-09 15:50:14','User ADMIN updated interface setting status.'),('TL-536','ADMIN','Insert','2023-03-09 15:54:04','User ADMIN inserted notification setting.'),('TL-536','ADMIN','Update','2023-03-09 15:55:09','User ADMIN updated notification setting.'),('TL-536','ADMIN','Update','2023-03-09 15:55:38','User ADMIN updated notification setting.'),('TL-537','ADMIN','Insert','2023-03-09 15:55:52','User ADMIN inserted state.'),('TL-537','ADMIN','Update','2023-03-09 15:55:55','User ADMIN updated state.'),('TL-538','ADMIN','Insert','2023-03-09 15:56:43','User ADMIN inserted system code.'),('TL-538','ADMIN','Update','2023-03-09 15:56:45','User ADMIN updated system code.'),('TL-539','ADMIN','Insert','2023-03-09 15:56:59','User ADMIN inserted system parameter.'),('TL-539','ADMIN','Update','2023-03-09 15:57:01','User ADMIN updated system parameter.'),('TL-15','ADMIN','Update','2023-03-09 15:57:15','User ADMIN updated system parameter.'),('TL-540','ADMIN','Insert','2023-03-09 15:57:27','User ADMIN inserted upload setting.'),('TL-541','ADMIN','Insert','2023-03-09 15:57:56','User ADMIN inserted upload setting.'),('TL-542','ADMIN','Insert','2023-03-09 15:58:13','User ADMIN inserted zoom API.'),('TL-542','ADMIN','Activate','2023-03-09 15:58:15','User ADMIN updated zoom API status.'),('TL-542','ADMIN','Update','2023-03-09 15:58:17','User ADMIN updated zoom API.'),('TL-543','ADMIN','Insert','2023-03-09 15:58:24','User ADMIN inserted zoom API.'),('TL-544','ADMIN','Insert','2023-03-09 15:58:50','User ADMIN inserted role.'),('TL-544','ADMIN','Update','2023-03-09 15:58:57','User ADMIN updated role.'),('TL-544','ADMIN','Update','2023-03-09 15:59:05','User ADMIN updated role.'),('TL-545','ADMIN','Insert','2023-03-09 15:59:47','User ADMIN inserted user account.'),('TL-545','ADMIN','Activate','2023-03-09 15:59:52','User ADMIN activated user account.'),('TL-545','ADMIN','Deactivated','2023-03-09 15:59:54','User ADMIN deactivated user account.'),('TL-545','ADMIN','Lock','2023-03-09 15:59:58','User ADMIN locked user account.'),('TL-545','ADMIN','Unlock','2023-03-09 16:00:00','User ADMIN unlocked user account.'),('TL-545','ADMIN','Update','2023-03-09 16:00:07','User ADMIN updated user account.'),('TL-546','ADMIN','Insert','2023-03-09 16:00:17','User ADMIN inserted company.'),('TL-546','ADMIN','Update','2023-03-09 16:00:19','User ADMIN updated company.'),('TL-547','ADMIN','Insert','2023-03-09 16:00:35','User ADMIN inserted department.'),('TL-547','ADMIN','Update','2023-03-09 16:00:39','User ADMIN updated department.'),('TL-547','ADMIN','Archive','2023-03-09 16:00:43','User ADMIN updated department status.'),('TL-547','ADMIN','Unarchive','2023-03-09 16:00:45','User ADMIN updated department status.'),('TL-548','ADMIN','Insert','2023-03-09 16:00:52','User ADMIN inserted departure reason.'),('TL-548','ADMIN','Update','2023-03-09 16:00:58','User ADMIN updated departure reason.'),('TL-549','ADMIN','Insert','2023-03-09 16:01:05','User ADMIN inserted employee type.'),('TL-549','ADMIN','Update','2023-03-09 16:01:06','User ADMIN updated employee type.'),('TL-550','ADMIN','Insert','2023-03-09 16:01:17','User ADMIN inserted ID type.'),('TL-550','ADMIN','Update','2023-03-09 16:01:20','User ADMIN updated ID type.'),('TL-551','ADMIN','Insert','2023-03-09 16:01:32','User ADMIN inserted job position.'),('TL-551','ADMIN','Update','2023-03-09 16:01:34','User ADMIN updated job position.'),('TL-552','ADMIN','Insert','2023-03-09 16:01:54','User ADMIN inserted job position attachment.'),('TL-552','ADMIN','Update','2023-03-09 16:01:54','User ADMIN updated job position attachment.'),('TL-552','ADMIN','Update','2023-03-09 16:01:59','User ADMIN updated job position attachment.'),('TL-552','ADMIN','Update','2023-03-09 16:01:59','User ADMIN updated job position attachment.'),('TL-553','ADMIN','Insert','2023-03-09 16:02:07','User ADMIN inserted job position responsibility.'),('TL-554','ADMIN','Update','2023-03-09 16:02:09','User ADMIN updated job position responsibility.'),('TL-555','ADMIN','Insert','2023-03-09 16:02:16','User ADMIN inserted job position requirement.'),('TL-556','ADMIN','Update','2023-03-09 16:02:19','User ADMIN updated job position requirement.'),('TL-557','ADMIN','Insert','2023-03-09 16:02:26','User ADMIN inserted job position qualification.'),('TL-558','ADMIN','Update','2023-03-09 16:02:28','User ADMIN updated job position qualification.'),('TL-551','ADMIN','Start','2023-03-09 16:02:36','User ADMIN updated job position recruitment status.'),('TL-551','ADMIN','Stop','2023-03-09 16:02:39','User ADMIN updated job position recruitment status.'),('TL-559','ADMIN','Insert','2023-03-09 16:08:01','User ADMIN inserted wage type.'),('TL-559','ADMIN','Update','2023-03-09 16:08:04','User ADMIN updated wage type.'),('TL-559','ADMIN','Update','2023-03-09 16:08:06','User ADMIN updated wage type.'),('TL-560','ADMIN','Insert','2023-03-09 16:08:22','User ADMIN inserted work location.'),('TL-560','ADMIN','Update','2023-03-09 16:08:24','User ADMIN updated work location.'),('TL-560','ADMIN','Archive','2023-03-09 16:08:36','User ADMIN updated work location status.'),('TL-560','ADMIN','Unarchive','2023-03-09 16:08:39','User ADMIN updated work location status.'),('TL-560','ADMIN','Archive','2023-03-09 16:08:45','User ADMIN updated work location status.'),('TL-560','ADMIN','Unarchive','2023-03-09 16:08:49','User ADMIN updated work location status.'),('TL-309','ADMIN','Update','2023-03-09 16:12:42','User ADMIN updated work location.'),('TL-561','ADMIN','Insert','2023-03-09 16:12:50','User ADMIN inserted working schedule type.'),('TL-561','ADMIN','Update','2023-03-09 16:13:00','User ADMIN updated working schedule type.'),('TL-562','ADMIN','Insert','2023-03-09 16:13:11','User ADMIN inserted working schedule type.'),('TL-563','ADMIN','Insert','2023-03-09 16:13:42','User ADMIN inserted working schedule.'),('TL-564','ADMIN','Insert','2023-03-09 16:14:01','User ADMIN inserted working hours.'),('TL-564','ADMIN','Update','2023-03-09 16:14:03','User ADMIN updated working hours.'),('TL-508','ADMIN','Lock','2023-03-09 16:16:04','User ADMIN locked user account.'),('TL-508','ADMIN','Unlock','2023-03-09 16:16:32','User ADMIN unlocked user account.'),('TL-508','ADMIN','Deactivated','2023-03-09 16:16:41','User ADMIN deactivated user account.'),('TL-508','ADMIN','Activate','2023-03-09 16:16:45','User ADMIN activated user account.'),('TL-565','ADMIN','Insert','2023-03-09 16:18:15','User ADMIN inserted upload setting.'),('TL-566','ADMIN','Insert','2023-03-09 16:18:32','User ADMIN inserted system parameter.'),('TL-567','ADMIN','Insert','2023-03-09 16:18:54','User ADMIN inserted system code.'),('TL-568','ADMIN','Insert','2023-03-09 16:19:07','User ADMIN inserted state.'),('TL-569','ADMIN','Insert','2023-03-09 16:19:34','User ADMIN inserted interface setting.'),('TL-33','ADMIN','Update','2023-03-09 16:31:18','User ADMIN updated system parameter.'),('TL-570','ADMIN','Insert','2023-03-09 16:31:35','User ADMIN inserted module.'),('TL-571','ADMIN','Insert','2023-03-09 16:31:36','User ADMIN inserted module.'),('TL-572','ADMIN','Insert','2023-03-09 16:31:42','User ADMIN inserted module.'),('TL-573','ADMIN','Insert','2023-03-09 16:33:42','User ADMIN inserted module.'),('TL-17','ADMIN','Update','2023-03-09 16:34:08','User ADMIN updated system parameter.'),('TL-574','ADMIN','Insert','2023-03-09 16:34:20','User ADMIN inserted page.'),('TL-25','ADMIN','Update','2023-03-09 16:34:41','User ADMIN updated system parameter.'),('176','ADMIN','Update','2023-03-09 16:35:15','User ADMIN updated system parameter.'),('151','ADMIN','Update','2023-03-09 16:35:29','User ADMIN updated system parameter.'),('120','ADMIN','Update','2023-03-09 16:35:54','User ADMIN updated system parameter.'),('158','ADMIN','Update','2023-03-09 16:36:10','User ADMIN updated system parameter.'),('177','ADMIN','Update','2023-03-09 16:36:24','User ADMIN updated system parameter.'),('177','ADMIN','Update','2023-03-09 16:36:26','User ADMIN updated system parameter.'),('68','ADMIN','Update','2023-03-09 16:36:44','User ADMIN updated system parameter.'),('TL-15','ADMIN','Update','2023-03-09 16:37:01','User ADMIN updated system parameter.'),('57','ADMIN','Update','2023-03-09 16:37:20','User ADMIN updated system parameter.'),('204','ADMIN','Update','2023-03-09 16:37:39','User ADMIN updated system parameter.'),('TL-39','ADMIN','Update','2023-03-09 16:38:52','User ADMIN updated system parameter.'),('110','ADMIN','Update','2023-03-09 16:39:14','User ADMIN updated system parameter.'),('110','ADMIN','Update','2023-03-09 16:39:17','User ADMIN updated system parameter.'),('242','ADMIN','Update','2023-03-09 16:39:37','User ADMIN updated system parameter.'),('TL-310','ADMIN','Update','2023-03-09 16:39:53','User ADMIN updated system parameter.'),('TL-323','ADMIN','Update','2023-03-09 16:40:50','User ADMIN updated system parameter.'),('TL-409','ADMIN','Update','2023-03-09 16:41:05','User ADMIN updated system parameter.'),('263','ADMIN','Update','2023-03-09 16:41:19','User ADMIN updated system parameter.'),('272','ADMIN','Update','2023-03-09 16:41:26','User ADMIN updated system parameter.'),('270','ADMIN','Update','2023-03-09 16:41:31','User ADMIN updated system parameter.'),('273','ADMIN','Update','2023-03-09 16:41:40','User ADMIN updated system parameter.'),('TL-306','ADMIN','Update','2023-03-09 16:41:46','User ADMIN updated system parameter.'),('TL-331','ADMIN','Update','2023-03-09 16:42:16','User ADMIN updated system parameter.'),('TL-379','ADMIN','Update','2023-03-09 16:42:22','User ADMIN updated system parameter.'),('TL-380','ADMIN','Update','2023-03-09 16:42:27','User ADMIN updated system parameter.'),('TL-389','ADMIN','Update','2023-03-09 16:42:33','User ADMIN updated system parameter.'),('TL-1','ADMIN','Log In','2023-03-10 10:08:28','User ADMIN logged in.'),('TL-575','ADMIN','Insert','2023-03-10 10:11:45','User ADMIN inserted action.'),('TL-33','ADMIN','Update','2023-03-10 10:12:04','User ADMIN updated system parameter.'),('TL-576','ADMIN','Insert','2023-03-10 13:12:48','User ADMIN inserted department.'),('TL-577','ADMIN','Insert','2023-03-10 13:22:15','User ADMIN inserted job position.'),('TL-578','ADMIN','Insert','2023-03-10 13:24:19','User ADMIN inserted company.'),('TL-579','ADMIN','Insert','2023-03-10 13:29:44','User ADMIN inserted work location.'),('TL-580','ADMIN','Insert','2023-03-10 13:30:33','User ADMIN inserted working schedule type.'),('TL-581','ADMIN','Insert','2023-03-10 13:30:43','User ADMIN inserted working schedule.'),('TL-582','ADMIN','Insert','2023-03-10 13:36:42','User ADMIN inserted employee type.'),('TL-583','ADMIN','Insert','2023-03-10 13:45:44','User ADMIN inserted employee.'),('TL-584','ADMIN','Insert','2023-03-10 13:45:48','User ADMIN inserted employee.'),('TL-582','ADMIN','Update','2023-03-10 14:09:55','User ADMIN updated employee type.'),('TL-585','ADMIN','Insert','2023-03-10 14:10:01','User ADMIN inserted employee.'),('TL-586','ADMIN','Insert','2023-03-10 14:10:46','User ADMIN inserted system parameter.'),('TL-586','ADMIN','Update','2023-03-10 14:14:24','User ADMIN updated system parameter.'),('TL-587','ADMIN','Insert','2023-03-10 14:17:10','User ADMIN inserted employee.'),('TL-588','ADMIN','Insert','2023-03-10 14:18:10','User ADMIN inserted employee.'),('TL-589','ADMIN','Insert','2023-03-10 14:19:20','User ADMIN inserted employee.'),('TL-590','ADMIN','Insert','2023-03-10 14:22:15','User ADMIN inserted employee.'),('TL-591','ADMIN','Insert','2023-03-10 14:24:11','User ADMIN inserted employee.'),('TL-442','ADMIN','Update','2023-03-10 14:39:40','User ADMIN updated system code.'),('TL-443','ADMIN','Update','2023-03-10 14:39:49','User ADMIN updated system code.'),('TL-444','ADMIN','Update','2023-03-10 14:39:58','User ADMIN updated system code.'),('TL-445','ADMIN','Update','2023-03-10 14:40:06','User ADMIN updated system code.'),('TL-446','ADMIN','Update','2023-03-10 14:40:13','User ADMIN updated system code.'),('TL-447','ADMIN','Update','2023-03-10 14:40:20','User ADMIN updated system code.'),('TL-591','ADMIN','Update','2023-03-10 15:30:21','User ADMIN updated employee.'),('TL-591','ADMIN','Update','2023-03-10 15:30:25','User ADMIN updated employee.'),('TL-591','ADMIN','Update','2023-03-10 15:36:42','User ADMIN updated employee.'),('TL-591','ADMIN','Update','2023-03-10 15:38:42','User ADMIN updated employee.'),('TL-591','ADMIN','Update','2023-03-10 17:08:56','User ADMIN updated employee.'),('TL-1','ADMIN','Log In','2023-03-13 15:29:07','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2023-03-14 15:15:27','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2023-03-14 15:43:15','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2023-03-14 15:44:19','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2023-03-14 16:49:11','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2023-03-15 10:33:44','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2023-03-16 09:02:25','User ADMIN logged in.'),('229','LDAGULTO','Log In','2023-03-17 11:49:22','User LDAGULTO logged in.'),('229','LDAGULTO','Log Out','2023-03-17 11:49:26','User LDAGULTO logged out.'),('TL-1','ADMIN','Log In','2023-03-17 11:49:30','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2023-03-17 12:26:47','User ADMIN logged in.'),('TL-592','ADMIN','Insert','2023-03-17 14:56:07','User ADMIN inserted upload setting.'),('TL-593','ADMIN','Insert','2023-03-17 15:06:25','User ADMIN inserted upload setting.'),('229','LDAGULTO','Attempt Log In','2023-03-21 14:29:45','User LDAGULTO attempted to log in.'),('229','LDAGULTO','Attempt Log In','2023-03-21 14:30:20','User LDAGULTO attempted to log in.'),('229','LDAGULTO','Attempt Log In','2023-03-21 14:30:30','User LDAGULTO attempted to log in.'),('229','LDAGULTO','Log In','2023-03-21 14:30:45','User LDAGULTO logged in.'),('229','LDAGULTO','Log Out','2023-03-21 14:30:49','User LDAGULTO logged out.'),('TL-1','ADMIN','Attempt Log In','2023-03-21 14:30:53','User ADMIN attempted to log in.'),('229','LDAGULTO','Log In','2023-03-21 14:30:59','User LDAGULTO logged in.'),('229','LDAGULTO','Log Out','2023-03-21 14:31:00','User LDAGULTO logged out.'),('229','LDAGULTO','Attempt Log In','2023-03-21 14:31:06','User LDAGULTO attempted to log in.'),('TL-1','ADMIN','Log In','2023-03-21 14:31:15','User ADMIN logged in.'),('TL-577','ADMIN','Update','2023-03-21 15:53:31','User ADMIN updated job position.'),('TL-594','ADMIN','Insert','2023-03-21 15:53:44','User ADMIN inserted job position attachment.'),('TL-595','ADMIN','Insert','2023-03-21 15:53:48','User ADMIN inserted job position attachment.'),('TL-596','ADMIN','Insert','2023-03-21 15:53:59','User ADMIN inserted job position attachment.'),('TL-597','ADMIN','Insert','2023-03-21 15:54:17','User ADMIN inserted job position attachment.'),('TL-598','ADMIN','Insert','2023-03-21 15:55:16','User ADMIN inserted job position attachment.'),('TL-599','ADMIN','Insert','2023-03-21 16:11:16','User ADMIN inserted job position attachment.'),('TL-599','ADMIN','Update','2023-03-21 16:11:16','User ADMIN updated job position attachment.'),('TL-600','ADMIN','Insert','2023-03-21 16:11:34','User ADMIN inserted job position attachment.'),('TL-600','ADMIN','Update','2023-03-21 16:11:34','User ADMIN updated job position attachment.'),('TL-601','ADMIN','Insert','2023-03-21 16:55:50','User ADMIN inserted job position attachment.'),('TL-602','ADMIN','Insert','2023-03-21 16:56:33','User ADMIN inserted job position attachment.'),('TL-603','ADMIN','Insert','2023-03-21 17:02:26','User ADMIN inserted job position attachment.'),('TL-604','ADMIN','Update','2023-03-21 17:03:21','User ADMIN updated job position attachment.'),('TL-605','ADMIN','Update','2023-03-21 17:03:33','User ADMIN updated job position attachment.'),('TL-606','ADMIN','Update','2023-03-21 17:04:05','User ADMIN updated job position attachment.'),('TL-607','ADMIN','Update','2023-03-21 17:04:32','User ADMIN updated job position attachment.'),('TL-608','ADMIN','Update','2023-03-21 17:04:55','User ADMIN updated job position attachment.'),('TL-609','ADMIN','Insert','2023-03-21 17:05:31','User ADMIN inserted job position attachment.'),('TL-610','ADMIN','Update','2023-03-21 17:12:50','User ADMIN updated job position attachment.'),('TL-611','ADMIN','Insert','2023-03-21 17:14:44','User ADMIN inserted job position attachment.'),('TL-612','ADMIN','Update','2023-03-21 17:16:33','User ADMIN updated job position attachment.'),('TL-613','ADMIN','Update','2023-03-21 17:16:38','User ADMIN updated job position attachment.'),('TL-614','ADMIN','Update','2023-03-21 17:16:43','User ADMIN updated job position attachment.'),('TL-615','ADMIN','Update','2023-03-21 17:16:44','User ADMIN updated job position attachment.'),('TL-616','ADMIN','Update','2023-03-21 17:16:44','User ADMIN updated job position attachment.'),('TL-617','ADMIN','Update','2023-03-21 17:16:45','User ADMIN updated job position attachment.'),('TL-618','ADMIN','Update','2023-03-21 17:16:45','User ADMIN updated job position attachment.'),('TL-619','ADMIN','Update','2023-03-21 17:16:46','User ADMIN updated job position attachment.'),('TL-620','ADMIN','Update','2023-03-21 17:16:46','User ADMIN updated job position attachment.'),('TL-621','ADMIN','Update','2023-03-21 17:16:46','User ADMIN updated job position attachment.'),('TL-622','ADMIN','Update','2023-03-21 17:16:46','User ADMIN updated job position attachment.'),('TL-623','ADMIN','Update','2023-03-21 17:16:46','User ADMIN updated job position attachment.'),('TL-624','ADMIN','Update','2023-03-21 17:16:47','User ADMIN updated job position attachment.'),('TL-625','ADMIN','Update','2023-03-21 17:16:47','User ADMIN updated job position attachment.'),('TL-626','ADMIN','Update','2023-03-21 17:16:47','User ADMIN updated job position attachment.'),('TL-627','ADMIN','Update','2023-03-21 17:16:47','User ADMIN updated job position attachment.'),('TL-628','ADMIN','Update','2023-03-21 17:16:47','User ADMIN updated job position attachment.'),('TL-629','ADMIN','Update','2023-03-21 17:16:47','User ADMIN updated job position attachment.'),('TL-630','ADMIN','Update','2023-03-21 17:16:48','User ADMIN updated job position attachment.'),('TL-631','ADMIN','Update','2023-03-21 17:16:48','User ADMIN updated job position attachment.'),('TL-632','ADMIN','Update','2023-03-21 17:16:48','User ADMIN updated job position attachment.'),('TL-633','ADMIN','Update','2023-03-21 17:19:45','User ADMIN updated job position attachment.'),('TL-634','ADMIN','Update','2023-03-21 17:19:46','User ADMIN updated job position attachment.'),('TL-635','ADMIN','Update','2023-03-21 17:19:46','User ADMIN updated job position attachment.'),('TL-636','ADMIN','Update','2023-03-21 17:19:46','User ADMIN updated job position attachment.'),('TL-637','ADMIN','Update','2023-03-21 17:19:46','User ADMIN updated job position attachment.'),('TL-638','ADMIN','Update','2023-03-21 17:19:46','User ADMIN updated job position attachment.'),('TL-639','ADMIN','Update','2023-03-21 17:19:48','User ADMIN updated job position attachment.'),('TL-640','ADMIN','Update','2023-03-21 17:19:49','User ADMIN updated job position attachment.'),('TL-641','ADMIN','Update','2023-03-21 17:19:49','User ADMIN updated job position attachment.'),('TL-642','ADMIN','Update','2023-03-21 17:19:49','User ADMIN updated job position attachment.'),('TL-643','ADMIN','Update','2023-03-21 17:19:49','User ADMIN updated job position attachment.'),('TL-644','ADMIN','Update','2023-03-21 17:19:50','User ADMIN updated job position attachment.'),('TL-645','ADMIN','Update','2023-03-21 17:19:50','User ADMIN updated job position attachment.'),('TL-646','ADMIN','Update','2023-03-21 17:19:50','User ADMIN updated job position attachment.'),('TL-647','ADMIN','Update','2023-03-21 17:19:51','User ADMIN updated job position attachment.'),('TL-648','ADMIN','Update','2023-03-21 17:19:54','User ADMIN updated job position attachment.'),('TL-649','ADMIN','Update','2023-03-21 17:20:05','User ADMIN updated job position attachment.'),('TL-649','ADMIN','Update','2023-03-21 17:20:16','User ADMIN updated job position attachment.'),('TL-650','ADMIN','Update','2023-03-21 17:20:16','User ADMIN updated job position attachment.'),('229','LDAGULTO','Log In','2023-03-21 17:21:44','User LDAGULTO logged in.'),('229','LDAGULTO','Log Out','2023-03-21 17:21:48','User LDAGULTO logged out.'),('TL-1','ADMIN','Log In','2023-03-21 17:21:54','User ADMIN logged in.'),('TL-651','ADMIN','Update','2023-03-21 17:23:01','User ADMIN updated job position attachment.'),('TL-652','ADMIN','Update','2023-03-21 17:23:30','User ADMIN updated job position attachment.'),('TL-653','ADMIN','Update','2023-03-21 17:23:39','User ADMIN updated job position attachment.'),('TL-654','ADMIN','Update','2023-03-21 17:24:54','User ADMIN updated job position attachment.'),('TL-655','ADMIN','Update','2023-03-21 17:25:05','User ADMIN updated job position attachment.'),('TL-656','ADMIN','Update','2023-03-21 17:25:55','User ADMIN updated job position attachment.'),('TL-657','ADMIN','Update','2023-03-21 17:28:02','User ADMIN updated job position attachment.'),('TL-658','ADMIN','Update','2023-03-21 17:29:55','User ADMIN updated job position attachment.'),('TL-659','ADMIN','Update','2023-03-21 17:33:10','User ADMIN updated job position attachment.'),('TL-659','ADMIN','Update','2023-03-21 17:34:14','User ADMIN updated job position attachment.'),('TL-660','ADMIN','Update','2023-03-21 17:34:14','User ADMIN updated job position attachment.'),('TL-661','ADMIN','Insert','2023-03-21 17:50:26','User ADMIN inserted job position attachment.'),('TL-661','ADMIN','Update','2023-03-21 17:50:26','User ADMIN updated job position attachment.'),('TL-662','ADMIN','Insert','2023-03-21 17:50:44','User ADMIN inserted job position attachment.'),('TL-662','ADMIN','Update','2023-03-21 17:50:44','User ADMIN updated job position attachment.'),('TL-1','ADMIN','Log In','2023-03-22 08:50:08','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2023-03-23 10:49:20','User ADMIN logged in.'),('TL-663','ADMIN','Insert','2023-03-23 10:50:10','User ADMIN inserted job position attachment.'),('TL-663','ADMIN','Update','2023-03-23 10:50:10','User ADMIN updated job position attachment.'),('TL-1','ADMIN','Log In','2023-03-24 09:52:59','User ADMIN logged in.'),('229','LDAGULTO','Log In','2023-03-27 09:10:55','User LDAGULTO logged in.'),('229','LDAGULTO','Log Out','2023-03-27 09:11:10','User LDAGULTO logged out.'),('TL-1','ADMIN','Attempt Log In','2023-03-27 09:11:14','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2023-03-27 09:11:18','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2023-03-28 08:45:42','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2023-03-28 11:42:24','User ADMIN logged in.'),('TL-1','ADMIN','Attempt Log In','2023-03-28 17:19:06','User ADMIN attempted to log in.'),('TL-1','ADMIN','Log In','2023-03-28 17:19:11','User ADMIN logged in.'),('TL-1','ADMIN','Log In','2023-03-29 08:50:30','User ADMIN logged in.'),('TL-591','ADMIN','Update','2023-03-29 13:59:30','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-29 14:07:31','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-29 14:35:06','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-29 14:35:11','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-29 14:35:22','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-29 14:37:20','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-29 14:37:30','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-29 14:37:39','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-29 14:37:44','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-29 14:37:54','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-29 15:43:02','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-29 16:18:33','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-29 16:20:44','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-29 16:31:29','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-29 16:33:42','User ADMIN updated employee digital signature.'),('TL-1','ADMIN','Log In','2023-03-30 09:15:31','User ADMIN logged in.'),('TL-591','ADMIN','Update','2023-03-30 09:17:44','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-30 09:21:51','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-30 09:21:58','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-30 10:27:19','User ADMIN updated employee image.'),('TL-591','ADMIN','Update','2023-03-30 10:31:35','User ADMIN updated employee image.'),('TL-591','ADMIN','Update','2023-03-30 10:31:45','User ADMIN updated employee image.'),('TL-591','ADMIN','Update','2023-03-30 10:32:32','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-30 10:32:38','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-30 10:32:42','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-30 10:50:48','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-30 10:50:52','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-30 10:58:17','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-30 10:58:22','User ADMIN updated employee digital signature.'),('TL-591','ADMIN','Update','2023-03-30 10:58:33','User ADMIN updated employee digital signature.'),('TL-664','ADMIN','Insert','2023-03-30 11:04:07','User ADMIN inserted departure reason.'),('TL-591','ADMIN','Update','2023-03-30 11:45:03','User ADMIN updated employee.'),('TL-591','ADMIN','Update','2023-03-30 11:45:03','User ADMIN updated employee personal information.'),('TL-591','ADMIN','Update','2023-03-30 11:45:12','User ADMIN updated employee.'),('TL-591','ADMIN','Update','2023-03-30 11:45:12','User ADMIN updated employee personal information.'),('TL-591','ADMIN','Update','2023-03-30 11:46:52','User ADMIN updated employee.'),('TL-591','ADMIN','Update','2023-03-30 11:46:52','User ADMIN updated employee personal information.'),('TL-591','ADMIN','Archive','2023-03-30 11:54:31','User ADMIN updated employee status.'),('TL-591','ADMIN','Unarchive','2023-03-30 12:33:09','User ADMIN updated employee status.'),('TL-591','ADMIN','Archive','2023-03-30 12:43:33','User ADMIN updated employee status.'),('TL-591','ADMIN','Unarchive','2023-03-30 12:43:40','User ADMIN updated employee status.'),('TL-665','ADMIN','Insert','2023-03-30 13:11:53','User ADMIN inserted action.'),('TL-666','ADMIN','Insert','2023-03-30 13:12:07','User ADMIN inserted action.'),('TL-667','ADMIN','Insert','2023-03-30 13:12:24','User ADMIN inserted action.'),('TL-1','ADMIN','Log In','2023-03-31 08:40:39','User ADMIN logged in.');
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
  KEY `UPLOAD_SETTING_ID` (`UPLOAD_SETTING_ID`),
  CONSTRAINT `global_upload_file_type_ibfk_1` FOREIGN KEY (`UPLOAD_SETTING_ID`) REFERENCES `global_upload_setting` (`UPLOAD_SETTING_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_upload_file_type`
--

LOCK TABLES `global_upload_file_type` WRITE;
/*!40000 ALTER TABLE `global_upload_file_type` DISABLE KEYS */;
INSERT INTO `global_upload_file_type` VALUES (1,'jpeg'),(1,'svg'),(1,'png'),(1,'jpg'),(2,'jpeg'),(2,'jpg'),(2,'png'),(3,'jpeg'),(3,'jpg'),(3,'png'),(4,'jpeg'),(4,'jpg'),(4,'png'),(4,'svg'),(3,'svg'),(2,'svg'),(5,'jpeg'),(5,'jpg'),(5,'png'),(5,'svg'),(6,'ico'),(6,'jpeg'),(6,'jpg'),(6,'png'),(6,'svg'),(7,'pdf'),(8,'png'),(8,'jpg'),(8,'jpeg'),(9,'png'),(9,'jpg'),(9,'jpeg');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_upload_setting`
--

LOCK TABLES `global_upload_setting` WRITE;
/*!40000 ALTER TABLE `global_upload_setting` DISABLE KEYS */;
INSERT INTO `global_upload_setting` VALUES (1,'Module Icon','Upload setting for module icon.',5,'TL-14',NULL),(2,'Company Logo','Upload setting for company logo.',5,'111','INS->ADMIN->2022-12-07 04:53:02'),(3,'Login Background','Upload setting for login background.',5,'128','INS->ADMIN->2022-12-09 10:16:09'),(4,'Login Logo','Upload setting for login logo.',5,'129','INS->ADMIN->2022-12-09 10:16:41'),(5,'Menu Logo','Upload setting for menu logo.',5,'130','INS->ADMIN->2022-12-09 10:18:34'),(6,'Favicon','Upload setting for favicon.',5,'131','INS->ADMIN->2022-12-09 10:19:11'),(7,'Job Position Attachment','Upload setting for job position attachment.',5,'267','UPD->ADMIN->2022-12-24 10:20:48'),(8,'Employee Image','Upload setting for employee image.',5,'TL-592','INS->ADMIN->2023-03-17 02:56:07'),(9,'Employee Digital Signature','Upload setting for employee digital signature.',5,'TL-593','INS->ADMIN->2023-03-17 03:06:25');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_user_account`
--

LOCK TABLES `global_user_account` WRITE;
/*!40000 ALTER TABLE `global_user_account` DISABLE KEYS */;
INSERT INTO `global_user_account` VALUES ('ADMIN','QRyho%2BfZvLj%2FbBqneeHdDLJHCjSufF8U4P%2B1QUxqo%2FU%3D','Administrator','Active','2023-08-16',0,'2023-03-28 17:19:06','2023-03-31 08:40:39','TL-1','LCK->ADMIN->2023-02-16 03:00:33'),('LDAGULTO','epDqiHZKCYMYGdBujdCTypawAuctHHcMNfKaL6IuqPE=','Lawrence Agulto','Active','2023-08-14',0,'2023-03-21 14:31:06','2023-03-27 09:10:55','229','ACT->ADMIN->2023-02-18 08:09:10');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_zoom_api`
--

LOCK TABLES `global_zoom_api` WRITE;
/*!40000 ALTER TABLE `global_zoom_api` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technical_action`
--

LOCK TABLES `technical_action` WRITE;
/*!40000 ALTER TABLE `technical_action` DISABLE KEYS */;
INSERT INTO `technical_action` VALUES ('1','Add Modules','TL-7','UPD->ADMIN->2023-03-07 09:40:06'),('10','Delete Page Access Right','TL-24',NULL),('100','Add Job Position Attachment','268','INS->ADMIN->2022-12-24 10:50:02'),('101','Update Job Position Attachment','269','UPD->ADMIN->2022-12-26 12:33:51'),('102','Delete Job Position Attachment','274','INS->ADMIN->2022-12-26 12:34:01'),('103','Add Work Location','TL-301','INS->ADMIN->2022-12-28 02:50:18'),('104','Update Work Location','TL-302','INS->ADMIN->2022-12-28 02:50:30'),('105','Delete Work Location','TL-303','UPD->ADMIN->2022-12-28 02:51:03'),('106','Archive Work Location','TL-304','INS->ADMIN->2022-12-28 02:51:10'),('107','Unarchive Work Location','TL-305','INS->ADMIN->2022-12-28 02:51:25'),('108','Add Departure Reason','TL-313','INS->ADMIN->2022-12-31 12:59:07'),('109','Update Departure Reason','TL-314','INS->ADMIN->2022-12-31 12:59:20'),('11','Add Action','TL-28',NULL),('110','Delete Departure Reason','TL-315','INS->ADMIN->2022-12-31 12:59:30'),('111','Add Employee Type','TL-320','INS->ADMIN->2022-12-31 02:03:47'),('112','Update Employee Type','TL-321','INS->ADMIN->2022-12-31 02:03:59'),('113','Delete Employee Type','TL-322','INS->ADMIN->2022-12-31 02:04:13'),('114','Add Wage Type','TL-332','INS->ADMIN->2023-01-02 11:10:37'),('115','Update Wage Type','TL-333','UPD->ADMIN->2023-01-02 11:11:12'),('116','Delete Wage Type','TL-334','INS->ADMIN->2023-01-02 11:10:59'),('117','Add Working Schedule','TL-359','INS->ADMIN->2023-01-09 10:15:45'),('118','Update Working Schedule','TL-360','INS->ADMIN->2023-01-09 11:02:19'),('119','Delete Working Schedule','TL-361','INS->ADMIN->2023-01-09 11:02:35'),('12','Update Action','TL-29',NULL),('120','Add Working Hours','TL-383','INS->ADMIN->2023-01-16 10:31:44'),('121','Update Working Hours','TL-384','INS->ADMIN->2023-01-16 10:31:55'),('122','Delete Working Hours','TL-385','INS->ADMIN->2023-01-16 10:32:05'),('123','Add Working Schedule Type','TL-386','UPD->ADMIN->2023-01-16 10:32:59'),('124','Update Working Schedule Type','TL-387','INS->ADMIN->2023-01-16 10:33:14'),('125','Delete Working Schedule Type','TL-388','INS->ADMIN->2023-01-16 10:33:26'),('126','Add ID Type','TL-412','UPD->ADMIN->2023-02-07 03:35:04'),('127','Update ID Type','TL-413','UPD->ADMIN->2023-02-07 03:35:14'),('128','Delete ID Type','TL-414','UPD->ADMIN->2023-02-07 03:35:23'),('129','Archive Employee','TL-415','INS->ADMIN->2023-02-03 11:13:50'),('13','Delete Action','TL-30',NULL),('130','Unarchive Employee','TL-416','INS->ADMIN->2023-02-03 11:14:10'),('131','Add Employee','TL-502','INS->ADMIN->2023-02-08 01:23:55'),('132','Update Employee','TL-503','INS->ADMIN->2023-02-08 01:24:09'),('133','Delete Employee','TL-504','INS->ADMIN->2023-02-08 01:24:33'),('134','Archive Employee','TL-505','INS->ADMIN->2023-02-08 01:37:53'),('135','Unarchive Employee','TL-506','UPD->ADMIN->2023-02-15 10:46:07'),('136','Add Employee Contact Information','TL-665','INS->ADMIN->2023-03-30 01:11:53'),('137','Update Employee Contact Information','TL-666','INS->ADMIN->2023-03-30 01:12:07'),('138','Delete Employee Contact Information','TL-667','INS->ADMIN->2023-03-30 01:12:24'),('14','Add Action Access Right','TL-31',NULL),('15','Delete Action Access Right','TL-32',NULL),('17','Add System Parameter','TL-35','INS->ADMIN->2022-12-02 01:57:57'),('18','Update System Parameter','TL-36','INS->ADMIN->2022-12-02 01:58:13'),('19','Delete System Parameter','TL-37','INS->ADMIN->2022-12-02 01:58:24'),('2','Update Module','TL-8','UPD->ADMIN->2023-02-14 02:38:07'),('20','Add Role','42','UPD->ADMIN->2022-12-02 05:26:24'),('21','Update Role','43','UPD->ADMIN->2022-12-02 05:26:53'),('22','Delete Role','44','INS->ADMIN->2022-12-02 05:26:59'),('23','Add Role Module Access','45','INS->ADMIN->2022-12-02 05:27:18'),('24','Delete Role Module Access','46','INS->ADMIN->2022-12-02 05:27:34'),('25','Add Role Page Access','47','INS->ADMIN->2022-12-02 05:27:51'),('26','Delete Role Page Access','48','INS->ADMIN->2022-12-02 05:28:04'),('27','Add Role Action Access','49','INS->ADMIN->2022-12-02 05:28:17'),('28','Delete Role Action Access','50','INS->ADMIN->2022-12-02 05:28:30'),('29','Add Role User Account','51','INS->ADMIN->2022-12-04 09:52:54'),('3','Delete Module','TL-9',NULL),('30','Delete Role User Account','52','INS->ADMIN->2022-12-04 09:53:16'),('31','Add Upload Setting','59','INS->ADMIN->2022-12-06 03:10:25'),('32','Update Upload Setting','60','INS->ADMIN->2022-12-06 03:10:45'),('33','Delete Upload Setting','61','INS->ADMIN->2022-12-06 03:11:18'),('34','Add System Code','65','INS->ADMIN->2022-12-06 05:03:57'),('35','Update System Code','66','INS->ADMIN->2022-12-06 05:04:18'),('36','Delete System Code','67','INS->ADMIN->2022-12-06 05:04:34'),('37','Add Upload Setting File Type','101','INS->ADMIN->2022-12-07 01:22:51'),('38','Delete Upload Setting File Type','102','INS->ADMIN->2022-12-07 01:23:12'),('39','Add Company','107','UPD->ADMIN->2022-12-07 03:54:12'),('4','Add Module Access Right','TL-12',NULL),('40','Update Company','108','INS->ADMIN->2022-12-07 03:54:24'),('41','Delete Company','109','INS->ADMIN->2022-12-07 03:54:36'),('42','Add Interface Setting','122','UPD->ADMIN->2022-12-09 10:38:57'),('43','Update Interface Setting','123','UPD->ADMIN->2022-12-09 10:39:06'),('44','Delete Interface Setting','124','UPD->ADMIN->2022-12-09 10:39:12'),('45','Activate Interface Setting','125','UPD->ADMIN->2022-12-09 10:39:18'),('46','Deactivate Interface Setting','126','UPD->ADMIN->2022-12-09 10:39:24'),('47','Add Email Setting','141','INS->ADMIN->2022-12-12 12:55:18'),('48','Update Email Setting','142','INS->ADMIN->2022-12-12 12:55:29'),('49','Delete Email Setting','143','INS->ADMIN->2022-12-12 12:55:41'),('5','Delete Module Access Right','TL-13',NULL),('50','Activate Email Setting','144','INS->ADMIN->2022-12-12 12:56:09'),('51','Deactivate Email Setting','145','INS->ADMIN->2022-12-12 12:56:22'),('52','Add Notification Setting','161','INS->ADMIN->2022-12-12 05:26:27'),('53','Update Notification Setting','162','INS->ADMIN->2022-12-12 05:26:45'),('54','Delete Notification Setting','163','INS->ADMIN->2022-12-12 05:27:06'),('55','Add Role Notification Recipient','164','INS->ADMIN->2022-12-12 05:27:35'),('56','Delete Role Notification Recipient','165','INS->ADMIN->2022-12-12 05:27:56'),('57','Add User Notification Recipient','166','INS->ADMIN->2022-12-12 05:28:15'),('58','Delete User Notification Recipient','167','INS->ADMIN->2022-12-12 05:28:32'),('59','Add Notification Channel','168','INS->ADMIN->2022-12-14 12:55:28'),('6','Add Page','TL-20',NULL),('60','Delete Notification Channel','169','INS->ADMIN->2022-12-14 12:55:41'),('61','Add Country','182','INS->ADMIN->2022-12-15 01:52:37'),('62','Update Country','183','INS->ADMIN->2022-12-15 01:52:49'),('63','Delete Country','184','INS->ADMIN->2022-12-15 01:52:58'),('64','Add State','185','INS->ADMIN->2022-12-15 01:53:08'),('65','Update State','186','INS->ADMIN->2022-12-15 01:53:17'),('66','Delete State','187','INS->ADMIN->2022-12-15 01:53:27'),('67','Add Zoom API','199','INS->ADMIN->2022-12-15 04:17:49'),('68','Update Zoom API','200','INS->ADMIN->2022-12-15 04:20:41'),('69','Delete Zoom API','201','INS->ADMIN->2022-12-15 04:20:54'),('7','Update Page','TL-21','UPD->ADMIN->2022-12-02 09:37:48'),('70','Activate Zoom API','202','INS->ADMIN->2022-12-15 04:21:38'),('71','Deactivate Zoom API','203','INS->ADMIN->2022-12-15 04:21:51'),('72','Add User Account','213','INS->ADMIN->2022-12-16 03:36:46'),('73','Update User Account','214','INS->ADMIN->2022-12-16 03:37:00'),('74','Delete User Account','215','UPD->ADMIN->2022-12-16 03:37:17'),('75','Lock User Account','216','INS->ADMIN->2022-12-16 03:37:34'),('76','Unlock User Account','217','INS->ADMIN->2022-12-16 03:37:47'),('77','Activate User Account','218','INS->ADMIN->2022-12-16 03:37:59'),('78','Deactivate User Account','219','INS->ADMIN->2022-12-16 03:38:19'),('79','Add User Account Role','224','INS->ADMIN->2022-12-19 02:03:33'),('8','Delete Page','TL-22',NULL),('80','Delete User Account Role','225','INS->ADMIN->2022-12-19 02:03:50'),('81','Add Department','237','UPD->ADMIN->2022-12-23 10:28:08'),('82','Update Department','238','UPD->ADMIN->2022-12-23 10:28:11'),('83','Delete Department','239','UPD->ADMIN->2022-12-23 10:28:15'),('84','Archive Department','240','UPD->ADMIN->2022-12-23 10:28:23'),('85','Unarchive Department','241','UPD->ADMIN->2022-12-23 10:28:28'),('86','Add Job Position','248','INS->ADMIN->2022-12-23 04:35:00'),('87','Update Job Position','249','INS->ADMIN->2022-12-23 04:35:10'),('88','Delete Job Position','250','INS->ADMIN->2022-12-23 04:35:21'),('89','Start Job Position Recruitment','251','UPD->ADMIN->2022-12-23 04:36:29'),('9','Add Page Access Right','TL-23',NULL),('90','Stop Job Position Recruitment','252','INS->ADMIN->2022-12-23 04:36:47'),('91','Add Job Position Responsibility','254','INS->ADMIN->2022-12-23 05:15:09'),('92','Update Job Position Responsibility','255','UPD->ADMIN->2022-12-24 09:32:35'),('93','Delete Job Position Responsibility','256','UPD->ADMIN->2022-12-24 09:32:50'),('94','Add Job Position Requirement','257','UPD->ADMIN->2022-12-24 09:33:17'),('95','Update Job Position Requirement','258','UPD->ADMIN->2022-12-24 09:34:02'),('96','Delete Job Position Requirement','259','UPD->ADMIN->2022-12-24 09:34:21'),('97','Add Job Position Qualification','260','UPD->ADMIN->2022-12-24 09:34:39'),('98','Update Job Position Qualification','261','UPD->ADMIN->2022-12-24 09:34:53'),('99','Delete Job Position Qualification','262','UPD->ADMIN->2022-12-24 09:35:10');
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
  KEY `ACTION_ID` (`ACTION_ID`),
  CONSTRAINT `technical_action_access_rights_ibfk_1` FOREIGN KEY (`ACTION_ID`) REFERENCES `technical_action` (`ACTION_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technical_action_access_rights`
--

LOCK TABLES `technical_action_access_rights` WRITE;
/*!40000 ALTER TABLE `technical_action_access_rights` DISABLE KEYS */;
INSERT INTO `technical_action_access_rights` VALUES ('10','1'),('11','1'),('12','1'),('13','1'),('14','1'),('15','1'),('17','1'),('18','1'),('19','1'),('2','1'),('20','1'),('21','1'),('22','1'),('23','1'),('24','1'),('25','1'),('26','1'),('27','1'),('28','1'),('29','1'),('3','1'),('30','1'),('4','1'),('5','1'),('6','1'),('7','1'),('8','1'),('9','1'),('31','1'),('32','1'),('33','1'),('34','1'),('35','1'),('36','1'),('37','1'),('38','1'),('39','1'),('40','1'),('41','1'),('42','1'),('43','1'),('44','1'),('45','1'),('46','1'),('47','1'),('48','1'),('49','1'),('50','1'),('52','1'),('53','1'),('54','1'),('55','1'),('56','1'),('57','1'),('58','1'),('59','1'),('60','1'),('61','1'),('62','1'),('63','1'),('64','1'),('65','1'),('66','1'),('67','1'),('68','1'),('69','1'),('70','1'),('71','1'),('51','1'),('72','1'),('73','1'),('74','1'),('75','1'),('76','1'),('77','1'),('78','1'),('79','1'),('80','1'),('81','1'),('82','1'),('83','1'),('84','1'),('85','1'),('86','1'),('87','1'),('88','1'),('89','1'),('90','1'),('91','1'),('92','1'),('93','1'),('94','1'),('95','1'),('96','1'),('97','1'),('98','1'),('99','1'),('100','1'),('101','1'),('102','1'),('103','1'),('104','1'),('105','1'),('106','1'),('107','1'),('108','1'),('109','1'),('110','1'),('111','1'),('112','1'),('113','1'),('114','1'),('115','1'),('116','1'),('117','1'),('118','1'),('119','1'),('120','1'),('121','1'),('122','1'),('123','1'),('124','1'),('125','1'),('126','1'),('127','1'),('128','1'),('129','1'),('130','1'),('131','1'),('132','1'),('133','1'),('134','1'),('135','1'),('1','1'),('136','1'),('137','1'),('138','1');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technical_module`
--

LOCK TABLES `technical_module` WRITE;
/*!40000 ALTER TABLE `technical_module` DISABLE KEYS */;
INSERT INTO `technical_module` VALUES ('1','Settings','1.0.3','Administration Module',NULL,'TECHNICAL','modules.php','TL-3','UPD->ADMIN->2023-03-09 03:42:03',99),('2','Employee','1.0.0','Centralize employee information.',NULL,'HUMANRESOURCES','employees.php','230','UPD->ADMIN->2023-02-09 11:06:11',0),('3','Attendances','1.0.0','Track employee attendance.',NULL,'HUMANRESOURCES','attendance.php','232','INS->ADMIN->2022-12-21 02:31:24',NULL),('4','Time Off ','1.0.0','Allocate PTOs and follow leaves requests',NULL,'HUMANRESOURCES','leave.php','233','INS->ADMIN->2022-12-21 02:32:27',NULL),('5','Payroll ','1.0.0','Manage your employee payroll records.',NULL,'HUMANRESOURCES','payroll.php','234','UPD->ADMIN->2022-12-21 02:41:28',2);
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
  `ROLE_ID` varchar(100) NOT NULL,
  KEY `MODULE_ID` (`MODULE_ID`),
  CONSTRAINT `technical_module_access_rights_ibfk_1` FOREIGN KEY (`MODULE_ID`) REFERENCES `technical_module` (`MODULE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technical_module_access_rights`
--

LOCK TABLES `technical_module_access_rights` WRITE;
/*!40000 ALTER TABLE `technical_module_access_rights` DISABLE KEYS */;
INSERT INTO `technical_module_access_rights` VALUES ('2','1'),('3','1'),('4','1'),('1','1'),('5','1');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technical_page`
--

LOCK TABLES `technical_page` WRITE;
/*!40000 ALTER TABLE `technical_page` DISABLE KEYS */;
INSERT INTO `technical_page` VALUES ('1','Modules','1','TL-10','UPD->ADMIN->2023-03-09 01:44:24'),('10','Role Form','1','41','INS->ADMIN->2022-12-02 05:25:12'),('11','Upload Settings','1','58','INS->ADMIN->2022-12-06 03:07:55'),('12','Upload Setting Form','1','62','INS->ADMIN->2022-12-06 04:11:17'),('13','System Codes','1','63','UPD->ADMIN->2022-12-06 05:02:54'),('14','System Code Form','1','64','INS->ADMIN->2022-12-06 05:03:46'),('15','Company','1','105','INS->ADMIN->2022-12-07 03:50:51'),('16','Company Form','1','106','INS->ADMIN->2022-12-07 03:51:05'),('17','Interface Settings','1','121','UPD->ADMIN->2022-12-09 10:35:44'),('18','Interface Setting Form','1','127','UPD->ADMIN->2022-12-09 10:35:36'),('19','Email Settings','1','139','INS->ADMIN->2022-12-12 12:48:32'),('2','Module Form','1','TL-11',NULL),('20','Email Setting Page','1','140','INS->ADMIN->2022-12-12 12:48:47'),('21','Notification Settings','1','159','INS->ADMIN->2022-12-12 05:25:24'),('22','Notification Setting Form','1','160','INS->ADMIN->2022-12-12 05:25:42'),('23','Country','1','178','INS->ADMIN->2022-12-15 01:47:18'),('24','Country Form','1','179','INS->ADMIN->2022-12-15 01:48:04'),('25','State','1','180','INS->ADMIN->2022-12-15 01:48:26'),('26','State Form','1','181','INS->ADMIN->2022-12-15 01:48:40'),('27','Zoom API','1','197','UPD->ADMIN->2022-12-15 04:20:20'),('28','Zoom API Form','1','198','UPD->ADMIN->2022-12-15 04:20:30'),('29','User Accounts','1','210','UPD->ADMIN->2022-12-16 03:43:20'),('3','Pages','1','TL-18',NULL),('30','User Account Form','1','211','INS->ADMIN->2022-12-16 03:04:19'),('31','Departments','2','235','UPD->ADMIN->2022-12-23 10:25:34'),('32','Department Form','2','236','UPD->ADMIN->2022-12-23 10:25:18'),('33','Job Positions','2','246','INS->ADMIN->2022-12-23 04:32:50'),('34','Job Position Form','2','247','INS->ADMIN->2022-12-23 04:33:22'),('35','Work Locations','2','TL-299','UPD->ADMIN->2022-12-28 02:55:51'),('36','Work Location Form','2','TL-300','INS->ADMIN->2022-12-28 02:47:17'),('37','Departure Reason','2','TL-311','INS->ADMIN->2022-12-31 12:58:28'),('38','Departure Reason Form','2','TL-312','INS->ADMIN->2022-12-31 12:58:49'),('39','Employee Types','2','TL-318','INS->ADMIN->2022-12-31 02:03:02'),('4','Page Form','1','TL-19',NULL),('40','Employee Type Form','2','TL-319','INS->ADMIN->2022-12-31 02:03:22'),('41','Wage Types','2','TL-329','UPD->ADMIN->2023-01-02 10:21:11'),('42','Wage Type Form','2','TL-330','INS->ADMIN->2023-01-02 10:21:28'),('43','Working Schedules','2','TL-353','UPD->ADMIN->2023-01-16 03:14:26'),('44','Working Schedule Form','2','TL-354','UPD->ADMIN->2023-01-16 03:14:29'),('45','Working Schedule Types','2','TL-381','UPD->ADMIN->2023-01-16 01:05:40'),('46','Working Schedule Type Form','2','TL-382','UPD->ADMIN->2023-01-16 01:05:47'),('47','ID Types','2','TL-410','UPD->ADMIN->2023-02-07 03:37:10'),('48','ID Type Form','2','TL-411','UPD->ADMIN->2023-02-07 03:37:23'),('49','Employees','2','TL-500','INS->ADMIN->2023-02-08 01:23:23'),('5','Action','1','TL-26','UPD->ADMIN->2022-12-01 02:29:29'),('50','Employee Form','2','TL-501','INS->ADMIN->2023-02-08 01:23:40'),('6','Action Form','1','TL-27','INS->ADMIN->2022-12-01 02:05:33'),('7','System Parameters','1','TL-34','UPD->ADMIN->2022-12-02 02:47:59'),('8','System Parameter Form','1','TL-38','INS->ADMIN->2022-12-02 02:47:02'),('9','Role','1','40','INS->ADMIN->2022-12-02 05:24:52');
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
  KEY `PAGE_ID` (`PAGE_ID`),
  CONSTRAINT `technical_page_access_rights_ibfk_1` FOREIGN KEY (`PAGE_ID`) REFERENCES `technical_page` (`PAGE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technical_page_access_rights`
--

LOCK TABLES `technical_page_access_rights` WRITE;
/*!40000 ALTER TABLE `technical_page_access_rights` DISABLE KEYS */;
INSERT INTO `technical_page_access_rights` VALUES ('1','1'),('10','1'),('2','1'),('3','1'),('4','1'),('6','1'),('7','1'),('8','1'),('9','1'),('11','1'),('12','1'),('13','1'),('14','1'),('15','1'),('16','1'),('17','1'),('18','1'),('19','1'),('20','1'),('21','1'),('22','1'),('23','1'),('24','1'),('25','1'),('26','1'),('27','1'),('28','1'),('29','1'),('30','1'),('5','1'),('31','1'),('32','1'),('34','1'),('33','1'),('35','1'),('36','1'),('37','1'),('38','1'),('39','1'),('40','1'),('41','1'),('42','1'),('43','1'),('44','1'),('45','1'),('46','1'),('47','1'),('48','1'),('49','1'),('50','1');
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
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_action_access_rights WHERE ACTION_ID = ? AND ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING action_id, role_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_action WHERE ACTION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING action_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_company WHERE COMPANY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING company_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_country_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_country_exist`(IN country_id INT(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_country WHERE COUNTRY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING country_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_department_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_department_exist`(IN department_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_department WHERE DEPARTMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING department_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_departure_reason_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_departure_reason_exist`(IN departure_reason_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_departure_reason WHERE DEPARTURE_REASON_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING departure_reason_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_email_setting_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_email_setting_exist`(IN email_setting_id INT(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_email_setting WHERE EMAIL_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING email_setting_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_employee_bank_information_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_employee_bank_information_exist`(IN employee_bank_information_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_bank_information WHERE EMPLOYEE_BANK_INFORMATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_bank_information_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_employee_contact_information_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_employee_contact_information_exist`(IN employee_contact_information_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_contact_information WHERE EMPLOYEE_CONTACT_INFORMATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_contact_information_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_employee_educational_background_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_employee_educational_background_exist`(IN employee_educational_background_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_educational_background WHERE EMPLOYEE_EDUCATIONAL_BACKGROUND_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_educational_background_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_employee_emergency_contacts_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_employee_emergency_contacts_exist`(IN employee_emergency_contacts_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_emergency_contacts WHERE EMPLOYEE_EMERGENCY_CONTACT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_emergency_contacts_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_employee_employment_history_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_employee_employment_history_exist`(IN employee_employment_history_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_employment_history WHERE EMPLOYEE_EMPLOYMENT_HISTORY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_employment_history_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_employee_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_employee_exist`(IN employee_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employees WHERE EMPLOYEE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_employee_family_details_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_employee_family_details_exist`(IN employee_family_details_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_family_details WHERE EMPLOYEE_FAMILY_DETAILS_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_family_details_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_employee_identification_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_employee_identification_exist`(IN employee_identification_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_identification WHERE EMPLOYEE_IDENTIFICATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_identification_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_employee_training_seminars_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_employee_training_seminars_exist`(IN employee_training_seminars_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_training_seminars WHERE EMPLOYEE_TRAINING_SEMINARS_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_training_seminars_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_employee_type_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_employee_type_exist`(IN employee_type_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_employee_type WHERE EMPLOYEE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_type_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_fixed_working_schedule_overlap` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_fixed_working_schedule_overlap`(IN working_hours_id VARCHAR(100), IN working_schedule_id VARCHAR(100), IN day_of_week VARCHAR(20), IN work_from TIME, IN work_to TIME)
BEGIN
	IF working_hours_id IS NOT NULL OR working_hours_id <> '' THEN
		SET @query = 'SELECT COUNT(WORKING_HOURS_ID) AS TOTAL FROM employee_working_hours WHERE WORKING_HOURS_ID != ? AND WORKING_SCHEDULE_ID = ? AND DAY_OF_WEEK = ? AND ((WORK_FROM BETWEEN ? AND ?) OR (WORK_TO BETWEEN ? AND ?))';

		PREPARE stmt FROM @query;
		EXECUTE stmt USING working_hours_id, working_schedule_id, day_of_week, work_from, work_to, work_from, work_to;
	ELSE
		SET @query = 'SELECT COUNT(WORKING_HOURS_ID) AS TOTAL FROM employee_working_hours WHERE WORKING_SCHEDULE_ID = ? AND DAY_OF_WEEK = ? AND ((WORK_FROM BETWEEN ? AND ?) OR (WORK_TO BETWEEN ? AND ?))';

		PREPARE stmt FROM @query;
		EXECUTE stmt USING working_schedule_id, day_of_week, work_from, work_to, work_from, work_to;
    END IF;

	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_flexible_working_schedule_overlap` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_flexible_working_schedule_overlap`(IN `working_hours_id` VARCHAR(100), IN `working_schedule_id` VARCHAR(100), IN `working_date` DATE, IN `work_from` TIME, IN `work_to` TIME)
BEGIN
	IF working_hours_id IS NOT NULL OR working_hours_id <> '' THEN
		SET @query = 'SELECT COUNT(WORKING_HOURS_ID) AS TOTAL FROM employee_working_hours WHERE WORKING_HOURS_ID != ? AND WORKING_SCHEDULE_ID = ? AND WORKING_DATE = ? AND ((WORK_FROM BETWEEN ? AND ?) OR (WORK_TO BETWEEN ? AND ?))';

		PREPARE stmt FROM @query;
		EXECUTE stmt USING working_hours_id, working_schedule_id, working_date, work_from, work_to, work_from, work_to;
	ELSE
		SET @query = 'SELECT COUNT(WORKING_HOURS_ID) AS TOTAL FROM employee_working_hours WHERE WORKING_SCHEDULE_ID = ? AND WORKING_DATE = ? AND ((WORK_FROM BETWEEN ? AND ?) OR (WORK_TO BETWEEN ? AND ?))';

		PREPARE stmt FROM @query;
		EXECUTE stmt USING working_schedule_id, working_date, work_from, work_to, work_from, work_to;
    END IF;

	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_id_type_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_id_type_exist`(IN id_type_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_id_type WHERE ID_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING id_type_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_interface_setting WHERE INTERFACE_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING interface_setting_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_job_position_attachment_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_job_position_attachment_exist`(IN attachment_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_job_position_attachment WHERE ATTACHMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING attachment_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_job_position_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_job_position_exist`(IN job_position_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_job_position WHERE JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING job_position_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_job_position_qualification_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_job_position_qualification_exist`(IN qualification_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_job_position_qualification WHERE QUALIFICATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING qualification_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_job_position_requirement_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_job_position_requirement_exist`(IN requirement_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_job_position_requirement WHERE REQUIREMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING requirement_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_job_position_responsibility_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_job_position_responsibility_exist`(IN responsibility_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_job_position_responsibility WHERE RESPONSIBILITY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING responsibility_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_module_access_rights WHERE MODULE_ID = ? AND ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_id, role_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_module WHERE MODULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_notification_channel_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_notification_channel_exist`(IN notification_setting_id INT(50), IN channel VARCHAR(20))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_notification_channel WHERE NOTIFICATION_SETTING_ID = ? AND CHANNEL = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, channel;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_notification_role_recipient_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_notification_role_recipient_exist`(IN notification_setting_id INT(50), IN role_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_notification_role_recipient WHERE NOTIFICATION_SETTING_ID = ? AND ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, role_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_notification_setting_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_notification_setting_exist`(IN notification_setting_id INT(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_notification_setting WHERE NOTIFICATION_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_notification_user_account_recipient_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_notification_user_account_recipient_exist`(IN notification_setting_id INT(50), IN username VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_notification_user_account_recipient WHERE NOTIFICATION_SETTING_ID = ? AND USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, username;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_page_access_rights WHERE PAGE_ID = ? AND ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING page_id, role_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_page WHERE PAGE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING page_id;
	DEALLOCATE PREPARE stmt;
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_role_exist`(IN role_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_role WHERE ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id;
	DEALLOCATE PREPARE stmt;
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_role_user_account_exist`(IN role_id VARCHAR(100), IN username VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_role_user_account WHERE ROLE_ID = ? AND USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id, username;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_state_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_state_exist`(IN state_id INT(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_state WHERE STATE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING state_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_system_code WHERE SYSTEM_CODE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING system_code_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_system_parameters WHERE PARAMETER_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING parameter_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_upload_setting WHERE UPLOAD_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_upload_file_type WHERE UPLOAD_SETTING_ID = ? AND FILE_TYPE = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting_id, file_type;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_user_account WHERE BINARY USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING username;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_wage_type_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_wage_type_exist`(IN wage_type_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_wage_type WHERE WAGE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING wage_type_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_working_hours_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_working_hours_exist`(IN `working_hours_id` VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_working_hours WHERE WORKING_HOURS_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_hours_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_working_schedule_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_working_schedule_exist`(IN `working_schedule_id` VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_working_schedule WHERE WORKING_SCHEDULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_working_schedule_type_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_working_schedule_type_exist`(IN working_schedule_type_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_working_schedule_type WHERE WORKING_SCHEDULE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_type_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_work_location_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_work_location_exist`(IN work_location_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_work_location WHERE WORK_LOCATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING work_location_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `check_zoom_api_exist` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_zoom_api_exist`(IN zoom_api_id INT(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_zoom_api WHERE ZOOM_API_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING zoom_api_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM technical_action WHERE ACTION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING action_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM technical_action_access_rights WHERE ACTION_ID = ? AND ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING action_id, role_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM technical_action_access_rights WHERE ACTION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING action_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_job_position_qualification` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_job_position_qualification`(IN job_position_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_job_position_qualification WHERE JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING job_position_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_job_position_requirement` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_job_position_requirement`(IN job_position_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_job_position_requirement WHERE JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING job_position_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_job_position_responsibility` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_job_position_responsibility`(IN job_position_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_job_position_responsibility WHERE JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING job_position_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM technical_module_access_rights WHERE MODULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_notification_channel` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_notification_channel`(IN notification_setting_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_notification_channel WHERE NOTIFICATION_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_notification_role_recipient` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_notification_role_recipient`(IN notification_setting_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_notification_role_recipient WHERE NOTIFICATION_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_notification_user_account_recipient` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_notification_user_account_recipient`(IN notification_setting_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_notification_user_account_recipient WHERE NOTIFICATION_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM technical_page_access_rights WHERE PAGE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING page_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM global_role_user_account WHERE ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_state` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_state`(IN country_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_state WHERE COUNTRY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING country_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM global_upload_file_type WHERE UPLOAD_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_user_account_role` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_user_account_role`(IN username VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM global_role_user_account WHERE USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING username;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_all_working_hours` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_all_working_hours`(IN `working_schedule_id` VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_working_hours WHERE WORKING_SCHEDULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM global_company WHERE COMPANY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING company_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_country` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_country`(IN country_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_country WHERE COUNTRY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING country_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_department` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_department`(IN department_id VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM employee_department WHERE DEPARTMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING department_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_departure_reason` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_departure_reason`(IN departure_reason_id VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM employee_departure_reason WHERE DEPARTURE_REASON_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING departure_reason_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_email_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_email_setting`(IN email_setting_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_email_setting WHERE EMAIL_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING email_setting_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_employee` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_employee`(IN employee_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employees WHERE EMPLOYEE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_employee_bank_information` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_employee_bank_information`(IN employee_bank_information_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_bank_information WHERE EMPLOYEE_BANK_INFORMATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_bank_information_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_employee_contact_information` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_employee_contact_information`(IN employee_contact_information_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_contact_information WHERE EMPLOYEE_CONTACT_INFORMATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_contact_information_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_employee_educational_background` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_employee_educational_background`(IN employee_educational_background_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_educational_background WHERE EMPLOYEE_EDUCATIONAL_BACKGROUND_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_educational_background_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_employee_emergency_contacts` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_employee_emergency_contacts`(IN employee_emergency_contacts_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_emergency_contacts WHERE EMPLOYEE_EMERGENCY_CONTACT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_emergency_contacts_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_employee_employment_history` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_employee_employment_history`(IN employee_employment_history_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_employment_history WHERE EMPLOYEE_EMPLOYMENT_HISTORY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_employment_history_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_employee_family_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_employee_family_details`(IN employee_family_details_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_family_details WHERE EMPLOYEE_FAMILY_DETAILS_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_family_details_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_employee_identification` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_employee_identification`(IN employee_identification_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_identification WHERE EMPLOYEE_IDENTIFICATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_identification_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_employee_personal_information` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_employee_personal_information`(IN employee_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_personal_information WHERE EMPLOYEE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_employee_training_seminars` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_employee_training_seminars`(IN employee_training_seminars_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_training_seminars WHERE EMPLOYEE_TRAINING_SEMINARS_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_training_seminars_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_employee_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_employee_type`(IN employee_type_id VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM employee_employee_type WHERE EMPLOYEE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_type_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_id_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_id_type`(IN id_type_id VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM employee_id_type WHERE ID_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING id_type_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM global_interface_setting WHERE INTERFACE_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING interface_setting_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_job_position` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_job_position`(IN job_position_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_job_position WHERE JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING job_position_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_job_position_attachment` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_job_position_attachment`(IN attachment_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_job_position_attachment WHERE ATTACHMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING attachment_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_job_position_qualification` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_job_position_qualification`(IN qualification_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_job_position_qualification WHERE QUALIFICATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING qualification_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_job_position_requirement` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_job_position_requirement`(IN requirement_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_job_position_requirement WHERE REQUIREMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING requirement_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_job_position_responsibility` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_job_position_responsibility`(IN responsibility_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_job_position_responsibility WHERE RESPONSIBILITY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING responsibility_id;
	DEALLOCATE PREPARE stmt;
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_module`(IN module_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM technical_module WHERE MODULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM technical_module_access_rights WHERE MODULE_ID = ? AND ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_id, role_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_notification_channel` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_notification_channel`(IN notification_setting_id INT(50), IN channel VARCHAR(20))
BEGIN
	SET @query = 'DELETE FROM global_notification_channel WHERE NOTIFICATION_SETTING_ID = ? AND CHANNEL = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, channel;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_notification_role_recipient` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_notification_role_recipient`(IN notification_setting_id INT(50), IN role_id VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM global_notification_role_recipient WHERE NOTIFICATION_SETTING_ID = ? AND ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, role_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_notification_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_notification_setting`(IN notification_setting_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_notification_setting WHERE NOTIFICATION_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_notification_user_account_recipient` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_notification_user_account_recipient`(IN notification_setting_id INT(50), IN username VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM global_notification_user_account_recipient WHERE NOTIFICATION_SETTING_ID = ? AND USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, username;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM technical_page WHERE PAGE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING page_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM technical_page_access_rights WHERE PAGE_ID = ? AND ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING page_id, role_id;
	DEALLOCATE PREPARE stmt;
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_role`(IN role_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM global_role WHERE ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM technical_action_access_rights WHERE ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM technical_module_access_rights WHERE ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM technical_page_access_rights WHERE ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id;
	DEALLOCATE PREPARE stmt;
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_role_user_account`(IN role_id VARCHAR(100), IN username VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM global_role_user_account WHERE ROLE_ID = ? AND USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id, username;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_state` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_state`(IN state_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_state WHERE STATE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING state_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM global_system_code WHERE SYSTEM_CODE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING system_code_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM global_system_parameters WHERE PARAMETER_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING parameter_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM global_upload_setting WHERE UPLOAD_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'DELETE FROM global_upload_file_type WHERE UPLOAD_SETTING_ID = ? AND FILE_TYPE = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting_id, file_type;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_user_account` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_user_account`(IN username VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM global_user_account WHERE USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING username;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_wage_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_wage_type`(IN wage_type_id VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM employee_wage_type WHERE WAGE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING wage_type_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_working_hours` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_working_hours`(IN `working_hours_id` VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_working_hours WHERE WORKING_HOURS_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_hours_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_working_schedule` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_working_schedule`(IN `working_schedule_id` VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_working_schedule WHERE WORKING_SCHEDULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_working_schedule_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_working_schedule_type`(IN working_schedule_type_id VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM employee_working_schedule_type WHERE WORKING_SCHEDULE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_type_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_work_location` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_work_location`(IN work_location_id VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM employee_work_location WHERE WORK_LOCATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING work_location_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delete_zoom_api` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_zoom_api`(IN zoom_api_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_zoom_api WHERE ZOOM_API_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING zoom_api_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_company_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_company_options`()
BEGIN
	SET @query = 'SELECT COMPANY_ID, COMPANY_NAME FROM global_company ORDER BY COMPANY_NAME';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_country_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_country_options`()
BEGIN
	SET @query = 'SELECT COUNTRY_ID, COUNTRY_NAME FROM global_country ORDER BY COUNTRY_NAME';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_department_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_department_options`(IN generation_type VARCHAR(10))
BEGIN
	IF generation_type = 'active' THEN
		SET @query = 'SELECT DEPARTMENT_ID, DEPARTMENT FROM employee_department WHERE STATUS = "1" ORDER BY DEPARTMENT';
	ELSE
		SET @query = 'SELECT DEPARTMENT_ID, DEPARTMENT FROM employee_department ORDER BY DEPARTMENT';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_departure_reason_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_departure_reason_options`()
BEGIN
	SET @query = 'SELECT DEPARTURE_REASON_ID, DEPARTURE_REASON FROM employee_departure_reason ORDER BY DEPARTURE_REASON';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_employee_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_employee_options`(IN generation_type VARCHAR(10))
BEGIN
	IF generation_type = 'active' THEN
		SET @query = 'SELECT EMPLOYEE_ID, FILE_AS FROM employee_personal_information WHERE EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employees WHERE EMPLOYEE_STATUS = "1") ORDER BY FILE_AS';
	ELSEIF generation_type = 'archived' THEN
		SET @query = 'SELECT EMPLOYEE_ID, FILE_AS FROM employee_personal_information WHERE EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employees WHERE EMPLOYEE_STATUS = "2") ORDER BY FILE_AS';
	ELSE
		SET @query = 'SELECT EMPLOYEE_ID, FILE_AS FROM employee_personal_information ORDER BY FILE_AS';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_employee_type_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_employee_type_options`()
BEGIN
	SET @query = 'SELECT EMPLOYEE_TYPE_ID, EMPLOYEE_TYPE FROM employee_employee_type ORDER BY EMPLOYEE_TYPE';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_id_type_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_id_type_options`()
BEGIN
	SET @query = 'SELECT ID_TYPE_ID, ID_TYPE FROM employee_id_type ORDER BY ID_TYPE';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_job_position_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_job_position_options`(IN generation_type VARCHAR(10))
BEGIN
	IF generation_type = 'active' THEN
		SET @query = 'SELECT JOB_POSITION_ID, JOB_POSITION FROM employee_job_position WHERE RECRUITMENT_STATUS = "1" ORDER BY JOB_POSITION';
	ELSEIF generation_type = 'inactive' THEN
		SET @query = 'SELECT JOB_POSITION_ID, JOB_POSITION FROM employee_job_position WHERE RECRUITMENT_STATUS = "2" ORDER BY JOB_POSITION';
	ELSE
		SET @query = 'SELECT JOB_POSITION_ID, JOB_POSITION FROM employee_job_position ORDER BY JOB_POSITION';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt ;
	DEALLOCATE PREPARE stmt;
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
	DEALLOCATE PREPARE stmt;
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
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT SYSTEM_CODE, SYSTEM_DESCRIPTION FROM global_system_code WHERE SYSTEM_TYPE = ? ORDER BY SYSTEM_DESCRIPTION';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING system_type;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_wage_type_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_wage_type_options`()
BEGIN
	SET @query = 'SELECT WAGE_TYPE_ID, WAGE_TYPE FROM employee_wage_type ORDER BY WAGE_TYPE';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_working_schedule_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_working_schedule_options`()
BEGIN
	SET @query = 'SELECT WORKING_SCHEDULE_ID, WORKING_SCHEDULE FROM employee_working_schedule ORDER BY WORKING_SCHEDULE';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_working_schedule_type_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_working_schedule_type_options`()
BEGIN
	SET @query = 'SELECT WORKING_SCHEDULE_TYPE_ID, WORKING_SCHEDULE_TYPE FROM employee_working_schedule_type ORDER BY WORKING_SCHEDULE_TYPE';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generate_work_location_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_work_location_options`(IN generation_type VARCHAR(10))
BEGIN
	IF generation_type = 'active' THEN
		SET @query = 'SELECT WORK_LOCATION_ID, WORK_LOCATION FROM employee_work_location WHERE STATUS = "1" ORDER BY WORK_LOCATION';
	ELSEIF generation_type = 'inactive' THEN
		SET @query = 'SELECT WORK_LOCATION_ID, WORK_LOCATION FROM employee_work_location WHERE STATUS = "2" ORDER BY WORK_LOCATION';
	ELSE
		SET @query = 'SELECT WORK_LOCATION_ID, WORK_LOCATION FROM employee_work_location ORDER BY WORK_LOCATION';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_access_rights_count`(IN role_id VARCHAR(100), IN access_right_id VARCHAR(100), IN access_type VARCHAR(10))
BEGIN
	IF access_type = 'module' THEN
		SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_module_access_rights WHERE MODULE_ID = ?  AND ROLE_ID = ?';
	ELSEIF access_type = 'page' THEN
		SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_page_access_rights WHERE PAGE_ID = ? AND ROLE_ID = ?';
	ELSE
		SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_action_access_rights WHERE ACTION_ID = ? AND ROLE_ID = ?';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt USING access_right_id, role_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT ACTION_NAME, TRANSACTION_LOG_ID, RECORD_LOG FROM technical_action WHERE ACTION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING action_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_activated_email_setting_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_activated_email_setting_details`()
BEGIN
	SET @query = 'SELECT EMAIL_SETTING_ID, EMAIL_SETTING_NAME, DESCRIPTION, STATUS, MAIL_HOST, PORT, SMTP_AUTH, SMTP_AUTO_TLS, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_NAME, MAIL_FROM_EMAIL, TRANSACTION_LOG_ID, RECORD_LOG FROM global_email_setting WHERE STATUS = 1';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
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
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_activated_zoom_api_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_activated_zoom_api_details`()
BEGIN
	SET @query = 'SELECT ZOOM_API_ID, ZOOM_API_NAME, DESCRIPTION, API_KEY, API_SECRET, STATUS, TRANSACTION_LOG_ID, RECORD_LOG FROM global_zoom_api WHERE STATUS = 1';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_all_accessible_module_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_accessible_module_details`(IN username VARCHAR(50))
BEGIN
	SET @query = 'SELECT MODULE_ID, MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPTION, MODULE_ICON, MODULE_CATEGORY, DEFAULT_PAGE, TRANSACTION_LOG_ID, RECORD_LOG, ORDER_SEQUENCE FROM technical_module WHERE MODULE_ID IN (SELECT MODULE_ID FROM technical_module_access_rights WHERE ROLE_ID IN (SELECT ROLE_ID FROM global_role_user_account WHERE USERNAME = ?)) ORDER BY ORDER_SEQUENCE';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING username;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT COMPANY_NAME, COMPANY_LOGO, COMPANY_ADDRESS, EMAIL, TELEPHONE, MOBILE, WEBSITE, TAX_ID, TRANSACTION_LOG_ID, RECORD_LOG FROM global_company WHERE COMPANY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING company_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_country_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_country_details`(IN country_id INT(50))
BEGIN
	SET @query = 'SELECT COUNTRY_NAME, TRANSACTION_LOG_ID, RECORD_LOG FROM global_country WHERE COUNTRY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING country_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_department_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_department_details`(IN department_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT DEPARTMENT, PARENT_DEPARTMENT, MANAGER, STATUS, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_department WHERE DEPARTMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING department_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_departure_reason_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_departure_reason_details`(IN departure_reason_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT DEPARTURE_REASON, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_departure_reason WHERE DEPARTURE_REASON_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING departure_reason_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_email_setting_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_email_setting_details`(IN email_setting_id INT(50))
BEGIN
	SET @query = 'SELECT EMAIL_SETTING_NAME, DESCRIPTION, STATUS, MAIL_HOST, PORT, SMTP_AUTH, SMTP_AUTO_TLS, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_NAME, MAIL_FROM_EMAIL, TRANSACTION_LOG_ID, RECORD_LOG FROM global_email_setting WHERE EMAIL_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING email_setting_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_employee_bank_information_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_employee_bank_information_details`(IN employee_bank_information_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT EMPLOYEE_ID, BANK_NAME, ACCOUNT_NUMBER, ACCOUNT_TYPE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_bank_information WHERE EMPLOYEE_BANK_INFORMATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_bank_information_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_employee_contact_information_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_employee_contact_information_details`(IN employee_contact_information_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT EMPLOYEE_ID, CONTACT_INFORMATION_TYPE, EMAIL, TELEPHONE, MOBILE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_contact_information WHERE EMPLOYEE_CONTACT_INFORMATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_contact_information_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_employee_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_employee_details`(IN employee_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT USERNAME, BADGE_ID, EMPLOYEE_IMAGE, EMPLOYEE_DIGITAL_SIGNATURE, COMPANY, JOB_POSITION, DEPARTMENT, WORK_LOCATION, WORKING_HOURS, MANAGER, COACH, EMPLOYEE_TYPE, EMPLOYEE_STATUS, PERMANENCY_DATE, ONBOARD_DATE, OFFBOARD_DATE, DEPARTURE_REASON, DETAILED_REASON, TRANSACTION_LOG_ID, RECORD_LOG FROM employees WHERE EMPLOYEE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_employee_educational_background_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_employee_educational_background_details`(IN employee_educational_background_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT EMPLOYEE_ID, INSTITUTION_NAME, DEGREE, FIELD_OF_STUDY, START_DATE, END_DATE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_educational_background WHERE EMPLOYEE_EDUCATIONAL_BACKGROUND_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_educational_background_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_employee_emergency_contacts_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_employee_emergency_contacts_details`(IN employee_emergency_contacts_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT EMPLOYEE_ID, EMERGENCY_CONTACT_NAME, RELATIONSHIP, EMAIL, TELEPHONE, MOBILE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_emergency_contacts WHERE EMPLOYEE_EMERGENCY_CONTACT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_emergency_contacts_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_employee_employment_history_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_employee_employment_history_details`(IN employee_employment_history_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT EMPLOYEE_ID, COMPANY_NAME, JOB_TITLE, START_DATE, END_DATE, DESCRIPTION, EMPLOYMENT_CERTIFICATE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_employment_history WHERE EMPLOYEE_EMPLOYMENT_HISTORY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_employment_history_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_employee_family_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_employee_family_details`(IN employee_family_details_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT EMPLOYEE_ID, NAME, RELATIONSHIP, BIRTHDAY, AGE, SCHOOL, EMPLOYMENT, EMAIL, TELEPHONE, MOBILE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_family_details WHERE EMPLOYEE_FAMILY_DETAILS_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_family_details_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_employee_identification_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_employee_identification_details`(IN employee_identification_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT EMPLOYEE_ID, ID_TYPE, ID_NUMBER, ID_CLASSIFICATION, ID_IMAGE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_identification WHERE EMPLOYEE_IDENTIFICATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_identification_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_employee_personal_information_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_employee_personal_information_details`(IN employee_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT FILE_AS, FIRST_NAME, MIDDLE_NAME, LAST_NAME, SUFFIX, NICKNAME, CIVIL_STATUS, NATIONALITY, GENDER, BIRTHDAY, PLACE_OF_BIRTH, BLOOD_TYPE, HEIGHT, WEIGHT, RELIGION, RECORD_LOG FROM employee_personal_information WHERE EMPLOYEE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_employee_training_seminars_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_employee_training_seminars_details`(IN employee_training_seminars_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT EMPLOYEE_ID, TRAINING_NAME, TRAINING_DATE, TRAINING_LOCATION, TRAINING_PROVIDER, DESCRIPTION, TRAINING_CERTIFICATE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_training_seminars WHERE EMPLOYEE_TRAINING_SEMINARS_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_training_seminars_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_employee_type_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_employee_type_details`(IN employee_type_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT EMPLOYEE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_employee_type WHERE EMPLOYEE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_type_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_id_type_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_id_type_details`(IN id_type_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT ID_TYPE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_id_type WHERE ID_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING id_type_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT INTERFACE_SETTING_NAME, DESCRIPTION, STATUS, LOGIN_BACKGROUND, LOGIN_LOGO, MENU_LOGO, FAVICON, TRANSACTION_LOG_ID, RECORD_LOG FROM global_interface_setting WHERE INTERFACE_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING interface_setting_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_job_position_attachment_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_job_position_attachment_details`(IN attachment_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT JOB_POSITION_ID, ATTACHMENT_NAME, ATTACHMENT, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_job_position_attachment WHERE ATTACHMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING attachment_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_job_position_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_job_position_details`(IN job_position_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT JOB_POSITION, DESCRIPTION, RECRUITMENT_STATUS, DEPARTMENT, EXPECTED_NEW_EMPLOYEES, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_job_position WHERE JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING job_position_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_job_position_qualification_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_job_position_qualification_details`(IN qualification_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT JOB_POSITION_ID, QUALIFICATION, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_job_position_qualification WHERE QUALIFICATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING qualification_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_job_position_requirement_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_job_position_requirement_details`(IN requirement_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT JOB_POSITION_ID, REQUIREMENT, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_job_position_requirement WHERE REQUIREMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING requirement_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_job_position_responsibility_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_job_position_responsibility_details`(IN responsibility_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT JOB_POSITION_ID, RESPONSIBILITY, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_job_position_responsibility WHERE RESPONSIBILITY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING responsibility_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPTION, MODULE_ICON, MODULE_CATEGORY, DEFAULT_PAGE, TRANSACTION_LOG_ID, RECORD_LOG, ORDER_SEQUENCE FROM technical_module WHERE MODULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_notification_setting_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_notification_setting_details`(IN notification_setting_id INT(50))
BEGIN
	SET @query = 'SELECT NOTIFICATION_SETTING, DESCRIPTION, NOTIFICATION_TITLE, NOTIFICATION_MESSAGE, SYSTEM_LINK, EMAIL_LINK, TRANSACTION_LOG_ID, RECORD_LOG FROM global_notification_setting WHERE NOTIFICATION_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT PAGE_NAME, MODULE_ID, TRANSACTION_LOG_ID, RECORD_LOG FROM technical_page WHERE PAGE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING page_id;
	DEALLOCATE PREPARE stmt;
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
/*!50003 DROP PROCEDURE IF EXISTS `get_state_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_state_details`(IN state_id INT(50))
BEGIN
	SET @query = 'SELECT STATE_NAME, COUNTRY_ID, TRANSACTION_LOG_ID, RECORD_LOG FROM global_state WHERE STATE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING state_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT SYSTEM_CODE_ID, SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID, RECORD_LOG FROM global_system_code WHERE';

	IF system_code_id IS NOT NULL OR system_code_id <> '' THEN
		SET @query = CONCAT(@query, ' SYSTEM_CODE_ID = ?');

		PREPARE stmt FROM @query; 
		EXECUTE stmt USING system_code_id;
	ELSE
		SET @query = CONCAT(@query, ' SYSTEM_TYPE = ? AND SYSTEM_CODE = ?');
		
		PREPARE stmt FROM @query; 
		EXECUTE stmt USING system_type, system_code;
	END IF;

	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER, TRANSACTION_LOG_ID, RECORD_LOG FROM global_system_parameters WHERE PARAMETER_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING parameter_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT FILE_TYPE FROM global_upload_file_type WHERE UPLOAD_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'SELECT UPLOAD_SETTING, DESCRIPTION, MAX_FILE_SIZE, TRANSACTION_LOG_ID, RECORD_LOG FROM global_upload_setting WHERE UPLOAD_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting_id;
	DEALLOCATE PREPARE stmt;
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_account_details`(IN username VARCHAR(50))
BEGIN
	SET @query = 'SELECT PASSWORD, FILE_AS, USER_STATUS, PASSWORD_EXPIRY_DATE, FAILED_LOGIN, LAST_FAILED_LOGIN, LAST_CONNECTION_DATE, TRANSACTION_LOG_ID, RECORD_LOG FROM global_user_account WHERE USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING username;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_wage_type_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_wage_type_details`(IN wage_type_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT WAGE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_wage_type WHERE WAGE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING wage_type_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_working_hours_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_working_hours_details`(IN working_hours_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT WORKING_SCHEDULE_ID, WORKING_HOURS, WORKING_DATE, DAY_OF_WEEK, DAY_PERIOD, WORK_FROM, WORK_TO, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_working_hours WHERE WORKING_HOURS_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_hours_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_working_schedule_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_working_schedule_details`(IN working_schedule_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT WORKING_SCHEDULE, WORKING_SCHEDULE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_working_schedule WHERE WORKING_SCHEDULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_working_schedule_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_working_schedule_type`(IN working_schedule_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT WORKING_SCHEDULE_TYPE_CATEGORY FROM employee_working_schedule_type WHERE WORKING_SCHEDULE_TYPE_ID = (SELECT WORKING_SCHEDULE_TYPE FROM employee_working_schedule WHERE WORKING_SCHEDULE_ID = ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_working_schedule_type_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_working_schedule_type_details`(IN working_schedule_type_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT WORKING_SCHEDULE_TYPE, WORKING_SCHEDULE_TYPE_CATEGORY, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_working_schedule_type WHERE WORKING_SCHEDULE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_type_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_work_location_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_work_location_details`(IN work_location_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT WORK_LOCATION, WORK_LOCATION_ADDRESS, EMAIL, TELEPHONE, MOBILE, LOCATION_NUMBER, STATUS, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_work_location WHERE WORK_LOCATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING work_location_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_zoom_api_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_zoom_api_details`(IN zoom_api_id INT(50))
BEGIN
	SET @query = 'SELECT ZOOM_API_NAME, DESCRIPTION, API_KEY, API_SECRET, STATUS, TRANSACTION_LOG_ID, RECORD_LOG FROM global_zoom_api WHERE ZOOM_API_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING zoom_api_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING action_id, action_name, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES(?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING action_id, role_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'INSERT INTO global_company (COMPANY_ID, COMPANY_NAME, COMPANY_ADDRESS, EMAIL, TELEPHONE, MOBILE, WEBSITE, TAX_ID, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING company_id, company_name, company_address, email, telephone, mobile, website, tax_id, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_country` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_country`(IN country_id INT(50), IN country_name VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_country (COUNTRY_ID, COUNTRY_NAME, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING country_id, country_name, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_department` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_department`(IN department_id VARCHAR(50), IN department VARCHAR(100), IN parent_department VARCHAR(50), IN manager VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_department (DEPARTMENT_ID, DEPARTMENT, PARENT_DEPARTMENT, MANAGER, STATUS, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, "1", ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING department_id, department, parent_department, manager, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_departure_reason` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_departure_reason`(IN departure_reason_id VARCHAR(50), IN departure_reason VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_departure_reason (DEPARTURE_REASON_ID, DEPARTURE_REASON, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING departure_reason_id, departure_reason, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_email_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_email_setting`(IN email_setting_id INT(50), IN email_setting_name VARCHAR(100), IN description VARCHAR(200), IN mail_host VARCHAR(100), IN port INT, IN smtp_auth INT(1), IN smtp_auto_tls INT(1), IN mail_username VARCHAR(200), IN mail_password VARCHAR(200), IN mail_encryption VARCHAR(20), IN mail_from_name VARCHAR(200), IN mail_from_email VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_email_setting (EMAIL_SETTING_ID, EMAIL_SETTING_NAME, DESCRIPTION, STATUS, MAIL_HOST, PORT, SMTP_AUTH, SMTP_AUTO_TLS, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_NAME, MAIL_FROM_EMAIL, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, "2", ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING email_setting_id, email_setting_name, description, mail_host, port, smtp_auth, smtp_auto_tls, mail_username, mail_password, mail_encryption, mail_from_name, mail_from_email, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_employee` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_employee`(IN employee_id VARCHAR(100), IN badge_id VARCHAR(100), IN company VARCHAR(50), IN job_position VARCHAR(50), IN department VARCHAR(50), IN work_location VARCHAR(50), IN working_hours VARCHAR(50), IN manager VARCHAR(100), IN coach VARCHAR(100), IN employee_type VARCHAR(100), IN permanency_date DATE, IN onboard_date DATE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employees (EMPLOYEE_ID, BADGE_ID, COMPANY, JOB_POSITION, DEPARTMENT, WORK_LOCATION, WORKING_HOURS, MANAGER, COACH, EMPLOYEE_TYPE, EMPLOYEE_STATUS, PERMANENCY_DATE, ONBOARD_DATE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "1", ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_id, badge_id, company, job_position, department, work_location, working_hours, manager, coach, employee_type, permanency_date, onboard_date, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_employee_bank_information` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_employee_bank_information`(IN employee_bank_information_id VARCHAR(100), IN employee_id VARCHAR(100), IN bank_name VARCHAR(500), IN account_number VARCHAR(50), IN account_type VARCHAR(20), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_bank_information (EMPLOYEE_BANK_INFORMATION_ID, EMPLOYEE_ID, BANK_NAME, ACCOUNT_NUMBER, ACCOUNT_TYPE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_bank_information_id, employee_id, bank_name, account_number, account_type, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_employee_contact_information` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_employee_contact_information`(IN employee_contact_information_id VARCHAR(100), IN employee_id VARCHAR(100), IN contact_information_type VARCHAR(20), IN email VARCHAR(50), IN telephone VARCHAR(20), IN mobile VARCHAR(20), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_contact_information (EMPLOYEE_CONTACT_INFORMATION_ID, EMPLOYEE_ID, CONTACT_INFORMATION_TYPE, EMAIL, TELEPHONE, MOBILE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_contact_information_id, employee_id, contact_information_type, email, telephone, mobile, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_employee_educational_background` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_employee_educational_background`(IN employee_educational_background_id VARCHAR(100), IN employee_id VARCHAR(100), IN institution_name VARCHAR(500), IN degree VARCHAR(100), IN field_of_study VARCHAR(200), IN start_date DATE, IN end_date DATE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_educational_background (EMPLOYEE_EDUCATIONAL_BACKGROUND_ID, EMPLOYEE_ID, INSTITUTION_NAME, DEGREE, FIELD_OF_STUDY, START_DATE, END_DATE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_educational_background_id, employee_id, institution_name, degree, field_of_study, start_date, end_date, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_employee_emergency_contacts` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_employee_emergency_contacts`(IN employee_emergency_contacts_id VARCHAR(100), IN employee_id VARCHAR(100), IN emergency_contact_name VARCHAR(500), IN relationship VARCHAR(20), IN email VARCHAR(50), IN telephone VARCHAR(20), IN mobile VARCHAR(20), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_emergency_contacts (EMPLOYEE_EMERGENCY_CONTACT_ID, EMPLOYEE_ID, EMERGENCY_CONTACT_NAME, RELATIONSHIP, EMAIL, TELEPHONE, MOBILE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_emergency_contacts_id, employee_id, emergency_contact_name, relationship, email, telephone, mobile, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_employee_employment_history` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_employee_employment_history`(IN employee_employment_history_id VARCHAR(100), IN employee_id VARCHAR(100), IN company_name VARCHAR(500), IN job_title VARCHAR(100), IN start_date DATE, IN end_date DATE, IN description VARCHAR(10000), IN employment_certificate VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_employment_history (EMPLOYEE_EMPLOYMENT_HISTORY_ID, EMPLOYEE_ID, COMPANY_NAME, JOB_TITLE, START_DATE, END_DATE, DESCRIPTION, EMPLOYMENT_CERTIFICATE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_employment_history_id, employee_id, company_name, job_title, start_date, end_date, description, employment_certificate, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_employee_family_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_employee_family_details`(IN employee_family_details_id VARCHAR(100), IN employee_id VARCHAR(100), IN name VARCHAR(500), IN relationship VARCHAR(20), IN birthday DATE, IN age INT(5), IN school VARCHAR(100), IN employment VARCHAR(100), IN email VARCHAR(50), IN telephone VARCHAR(20), IN mobile VARCHAR(20), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_family_details (EMPLOYEE_FAMILY_DETAILS_ID, EMPLOYEE_ID, NAME, RELATIONSHIP, BIRTHDAY, AGE, SCHOOL, EMPLOYMENT, EMAIL, TELEPHONE, MOBILE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_family_details_id, employee_id, name, relationship, birthday, age, school, employment, email, telephone, mobile, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_employee_identification` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_employee_identification`(IN employee_identification_id VARCHAR(100), IN employee_id VARCHAR(100), IN id_type VARCHAR(100), IN id_number VARCHAR(100), IN id_classification VARCHAR(20), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_identification (EMPLOYEE_IDENTIFICATION_ID, EMPLOYEE_ID, ID_TYPE, ID_NUMBER, ID_CLASSIFICATION, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_identification_id, employee_id, id_type, id_number, id_classification, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_employee_personal_information` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_employee_personal_information`(IN employee_id VARCHAR(100), IN file_as VARCHAR(350), IN first_name VARCHAR(100), IN middle_name VARCHAR(100), IN last_name VARCHAR(100), IN suffix VARCHAR(5), IN nickname VARCHAR(20), IN civil_status VARCHAR(20), IN nationality VARCHAR(20), IN gender VARCHAR(20), IN birthday DATE, IN place_of_birth VARCHAR(500), IN blood_type VARCHAR(20), IN height DOUBLE, IN weight DOUBLE, IN religion VARCHAR(20), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_personal_information (EMPLOYEE_ID, FILE_AS, FIRST_NAME, MIDDLE_NAME, LAST_NAME, SUFFIX, NICKNAME, CIVIL_STATUS, NATIONALITY, GENDER, BIRTHDAY, PLACE_OF_BIRTH, BLOOD_TYPE, HEIGHT, WEIGHT, RELIGION, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_id, file_as, first_name, middle_name, last_name, suffix, nickname, civil_status, nationality, gender, birthday, place_of_birth, blood_type, height, weight, religion, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_employee_training_seminars` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_employee_training_seminars`(IN employee_training_seminars_id VARCHAR(100), IN employee_id VARCHAR(100), IN training_name VARCHAR(200), IN training_date DATE, IN training_location VARCHAR(100), IN training_provider VARCHAR(100), IN description VARCHAR(1000), IN training_certificate VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_training_seminars (EMPLOYEE_TRAINING_SEMINARS_ID, EMPLOYEE_ID, TRAINING_NAME, TRAINING_DATE, TRAINING_LOCATION, TRAINING_PROVIDER, DESCRIPTION, TRAINING_CERTIFICATE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_training_seminars_id, employee_id, training_name, training_date, training_location, training_provider, description, training_certificate, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_employee_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_employee_type`(IN employee_type_id VARCHAR(50), IN employee_type VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_employee_type (EMPLOYEE_TYPE_ID, EMPLOYEE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_type_id, employee_type, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_id_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_id_type`(IN id_type_id VARCHAR(50), IN id_type VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_id_type (ID_TYPE_ID, ID_TYPE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING id_type_id, id_type, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'INSERT INTO global_interface_setting (INTERFACE_SETTING_ID, INTERFACE_SETTING_NAME, DESCRIPTION, STATUS, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, "2", ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING interface_setting_id, interface_setting_name, description, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_job_position` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_job_position`(IN job_position_id VARCHAR(100), IN job_position VARCHAR(100), IN description VARCHAR(500), IN department VARCHAR(50), IN expected_new_employees INT(10), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_job_position (JOB_POSITION_ID, JOB_POSITION, DESCRIPTION, RECRUITMENT_STATUS, DEPARTMENT, EXPECTED_NEW_EMPLOYEES, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, "2", ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING job_position_id, job_position, description, department, expected_new_employees, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_job_position_attachment` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_job_position_attachment`(IN attachment_id VARCHAR(100), IN job_position_id VARCHAR(100), IN attachment_name VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_job_position_attachment (ATTACHMENT_ID, JOB_POSITION_ID, ATTACHMENT_NAME, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING attachment_id, job_position_id, attachment_name, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_job_position_qualification` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_job_position_qualification`(IN qualification_id VARCHAR(100), IN job_position_id VARCHAR(100), IN qualification VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_job_position_qualification (QUALIFICATION_ID, JOB_POSITION_ID, QUALIFICATION, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING qualification_id, job_position_id, qualification, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_job_position_requirement` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_job_position_requirement`(IN requirement_id VARCHAR(100), IN job_position_id VARCHAR(100), IN requirement VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_job_position_requirement (REQUIREMENT_ID, JOB_POSITION_ID, REQUIREMENT, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING requirement_id, job_position_id, requirement, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_job_position_responsibility` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_job_position_responsibility`(IN responsibility_id VARCHAR(100), IN job_position_id VARCHAR(100), IN responsibility VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_job_position_responsibility (RESPONSIBILITY_ID, JOB_POSITION_ID, RESPONSIBILITY, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING responsibility_id, job_position_id, responsibility, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_module`(IN module_id VARCHAR(100), IN module_name VARCHAR(200), IN module_version VARCHAR(20), IN module_description VARCHAR(500), IN module_category VARCHAR(50), IN default_page VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100), IN order_sequence INT)
BEGIN
	SET @query = 'INSERT INTO technical_module (MODULE_ID, MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPTION, MODULE_CATEGORY, DEFAULT_PAGE, TRANSACTION_LOG_ID, RECORD_LOG, ORDER_SEQUENCE) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_id, module_name, module_version, module_description, module_category, default_page, transaction_log_id, record_log, order_sequence;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'INSERT INTO technical_module_access_rights (MODULE_ID, ROLE_ID) VALUES(?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_id, role_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_notification_channel` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_notification_channel`(IN notification_setting_id INT(50), IN channel VARCHAR(20))
BEGIN
	SET @query = 'INSERT INTO global_notification_channel (NOTIFICATION_SETTING_ID, CHANNEL) VALUES(?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, channel;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_notification_role_recipient` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_notification_role_recipient`(IN notification_setting_id INT(50), IN role_id VARCHAR(50))
BEGIN
	SET @query = 'INSERT INTO global_notification_role_recipient (NOTIFICATION_SETTING_ID, ROLE_ID) VALUES(?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, role_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_notification_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_notification_setting`(IN notification_setting_id INT(50), IN notification_setting VARCHAR(100), IN description VARCHAR(200), IN notification_title VARCHAR(500), IN notification_message VARCHAR(500), IN system_link VARCHAR(200), IN email_link VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_notification_setting (NOTIFICATION_SETTING_ID, NOTIFICATION_SETTING, DESCRIPTION, NOTIFICATION_TITLE, NOTIFICATION_MESSAGE, SYSTEM_LINK, EMAIL_LINK, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, notification_setting, description, notification_title, notification_message, system_link, email_link, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_notification_user_account_recipient` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_notification_user_account_recipient`(IN notification_setting_id INT(50), IN username VARCHAR(50))
BEGIN
	SET @query = 'INSERT INTO global_notification_user_account_recipient (NOTIFICATION_SETTING_ID, USERNAME) VALUES(?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, username;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'INSERT INTO technical_page (PAGE_ID, PAGE_NAME, MODULE_ID, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING page_id, page_name, module_id, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'INSERT INTO technical_page_access_rights (PAGE_ID, ROLE_ID) VALUES(?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING page_id, role_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_password_history` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_password_history`(IN username VARCHAR(50), IN password VARCHAR(200))
BEGIN
	SET @query = 'INSERT INTO global_password_history (USERNAME, PASSWORD) VALUES(?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING username, password;
	DEALLOCATE PREPARE stmt;
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_role`(IN role_id VARCHAR(100), IN role VARCHAR(100), IN role_description VARCHAR(200), IN assignable TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_role (ROLE_ID, ROLE, ROLE_DESCRIPTION, ASSIGNABLE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id, role, role_description, assignable, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'INSERT INTO global_role_user_account (ROLE_ID, USERNAME) VALUES(?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id, username;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_state` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_state`(IN state_id INT(50), IN state_name VARCHAR(200), IN country_id INT(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_state (STATE_ID, STATE_NAME, COUNTRY_ID, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING state_id, state_name, country_id, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'INSERT INTO global_system_code (SYSTEM_CODE_ID, SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING system_code_id, system_type, system_code, system_description, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'INSERT INTO global_system_parameters (PARAMETER_ID, PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING parameter_id, parameter, parameter_description, extension, parameter_number, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'INSERT INTO global_transaction_log (TRANSACTION_LOG_ID, USERNAME, LOG_TYPE, LOG_DATE, LOG) VALUES(?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING transaction_log_id, username, log_type, log_date, log;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'INSERT INTO global_upload_setting (UPLOAD_SETTING_ID, UPLOAD_SETTING, DESCRIPTION, MAX_FILE_SIZE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting_id, upload_setting, description, max_file_size, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'INSERT INTO global_upload_file_type (UPLOAD_SETTING_ID, FILE_TYPE) VALUES(?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting_id, file_type;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_user_account` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_user_account`(IN username VARCHAR(50), IN password VARCHAR(200), IN file_as VARCHAR (300), IN password_expiry_date DATE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_user_account (USERNAME, PASSWORD, FILE_AS, USER_STATUS, PASSWORD_EXPIRY_DATE, FAILED_LOGIN, LAST_FAILED_LOGIN, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, "Inactive", ?, 0, DEFAULT, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING username, password, file_as, password_expiry_date, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_wage_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_wage_type`(IN wage_type_id VARCHAR(50), IN wage_type VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_wage_type (WAGE_TYPE_ID, WAGE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING wage_type_id, wage_type, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_working_hours` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_working_hours`(IN working_hours_id VARCHAR(100), IN working_schedule_id VARCHAR(100), IN working_hours VARCHAR(100), IN working_date DATE, IN day_of_week VARCHAR(20), IN day_period VARCHAR(20), IN work_from TIME, IN work_to TIME, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_working_hours (WORKING_HOURS_ID, WORKING_SCHEDULE_ID, WORKING_HOURS, WORKING_DATE, DAY_OF_WEEK, DAY_PERIOD, WORK_FROM, WORK_TO, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_hours_id, working_schedule_id, working_hours, working_date, day_of_week, day_period, work_from, work_to, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_working_schedule` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_working_schedule`(IN `working_schedule_id` VARCHAR(100), IN `working_schedule` VARCHAR(100), IN `working_schedule_type` VARCHAR(20), IN `transaction_log_id` VARCHAR(100), IN `record_log` VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_working_schedule (WORKING_SCHEDULE_ID, WORKING_SCHEDULE, WORKING_SCHEDULE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_id, working_schedule, working_schedule_type, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_working_schedule_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_working_schedule_type`(IN working_schedule_type_id VARCHAR(50), IN working_schedule_type VARCHAR(100), IN working_schedule_type_category VARCHAR(20), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_working_schedule_type (WORKING_SCHEDULE_TYPE_ID, WORKING_SCHEDULE_TYPE, WORKING_SCHEDULE_TYPE_CATEGORY, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_type_id, working_schedule_type, working_schedule_type_category, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_work_location` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_work_location`(IN work_location_id VARCHAR(50), IN work_location VARCHAR(100), IN work_location_address VARCHAR(500), IN email VARCHAR(50), IN telephone VARCHAR(50), IN mobile VARCHAR(50), IN location_number INT, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_work_location (WORK_LOCATION_ID, WORK_LOCATION, WORK_LOCATION_ADDRESS, EMAIL, TELEPHONE, MOBILE, LOCATION_NUMBER, STATUS, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?, "1", ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING work_location_id, work_location, work_location_address, email, telephone, mobile, location_number, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_zoom_api` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_zoom_api`(IN zoom_api_id INT(50), IN zoom_api_name VARCHAR(100), IN description VARCHAR(200), IN api_key VARCHAR(1000), IN api_secret VARCHAR(1000), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_zoom_api (ZOOM_API_ID, ZOOM_API_NAME, DESCRIPTION, API_KEY, API_SECRET, STATUS, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, "2", ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING zoom_api_id, zoom_api_name, description, api_key, api_secret, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'UPDATE technical_action SET ACTION_NAME = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE ACTION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING action_name, transaction_log_id, record_log, action_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'UPDATE global_company SET COMPANY_NAME = ?, COMPANY_ADDRESS = ?, EMAIL = ?, TELEPHONE = ?, MOBILE = ?, WEBSITE = ?, TAX_ID = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE COMPANY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING company_name, company_address, email, telephone, mobile, website, tax_id, transaction_log_id, record_log, company_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'UPDATE global_company SET COMPANY_LOGO = ? WHERE COMPANY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING company_logo, company_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_country` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_country`(IN country_id INT(50), IN country_name VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_country SET COUNTRY_NAME = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE COUNTRY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING country_name, transaction_log_id, record_log, country_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_department` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_department`(IN department_id VARCHAR(50), IN department VARCHAR(100), IN parent_department VARCHAR(50), IN manager VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_department SET DEPARTMENT = ?, PARENT_DEPARTMENT = ?, MANAGER = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE DEPARTMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING department, parent_department, manager, transaction_log_id, record_log, department_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_department_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_department_status`(IN department_id VARCHAR(50), IN status TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_department SET STATUS = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE DEPARTMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING status, transaction_log_id, record_log, department_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_departure_reason` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_departure_reason`(IN departure_reason_id VARCHAR(50), IN departure_reason VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_departure_reason SET DEPARTURE_REASON = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE DEPARTURE_REASON_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING departure_reason, transaction_log_id, record_log, departure_reason_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_email_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_email_setting`(IN email_setting_id INT(50), IN email_setting_name VARCHAR(100), IN description VARCHAR(200), IN mail_host VARCHAR(100), IN port INT, IN smtp_auth INT(1), IN smtp_auto_tls INT(1), IN mail_username VARCHAR(200), IN mail_password VARCHAR(200), IN mail_encryption VARCHAR(20), IN mail_from_name VARCHAR(200), IN mail_from_email VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_email_setting SET EMAIL_SETTING_NAME = ?, DESCRIPTION = ?, MAIL_HOST = ?, PORT = ?, SMTP_AUTH = ?, SMTP_AUTO_TLS = ?, MAIL_USERNAME = ?, MAIL_PASSWORD = ?, MAIL_ENCRYPTION = ?, MAIL_FROM_NAME = ?, MAIL_FROM_EMAIL = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE EMAIL_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING email_setting_name, description, mail_host, port, smtp_auth, smtp_auto_tls, mail_username, mail_password, mail_encryption, mail_from_name, mail_from_email, transaction_log_id, record_log, email_setting_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_email_setting_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_email_setting_status`(IN email_setting_id INT(50), IN status TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_email_setting SET STATUS = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE EMAIL_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING status, transaction_log_id, record_log, email_setting_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_employee` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_employee`(IN employee_id VARCHAR(100), IN badge_id VARCHAR(100), IN company VARCHAR(50), IN job_position VARCHAR(50), IN department VARCHAR(50), IN work_location VARCHAR(50), IN working_hours VARCHAR(50), IN manager VARCHAR(100), IN coach VARCHAR(100), IN employee_type VARCHAR(100), IN permanency_date DATE, IN onboard_date DATE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employees SET BADGE_ID = ?, COMPANY = ?, JOB_POSITION = ?, DEPARTMENT = ?, WORK_LOCATION = ?, WORKING_HOURS = ?, MANAGER = ?, COACH = ?, EMPLOYEE_TYPE = ?, PERMANENCY_DATE = ?, ONBOARD_DATE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE EMPLOYEE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING badge_id, company, job_position, department, work_location, working_hours, manager, coach, employee_type, permanency_date, onboard_date, transaction_log_id, record_log, employee_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_employee_bank_information` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_employee_bank_information`(IN employee_bank_information_id VARCHAR(100), IN bank_name VARCHAR(500), IN account_number VARCHAR(50), IN account_type VARCHAR(20), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_bank_information SET BANK_NAME = ?, ACCOUNT_NUMBER = ?, ACCOUNT_TYPE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE EMPLOYEE_BANK_INFORMATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING bank_name, account_number, account_type, transaction_log_id, record_log, employee_bank_information_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_employee_contact_information` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_employee_contact_information`(IN employee_contact_information_id VARCHAR(100), IN contact_information_type VARCHAR(20), IN email VARCHAR(50), IN telephone VARCHAR(20), IN mobile VARCHAR(20), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_contact_information SET CONTACT_INFORMATION_TYPE = ?, EMAIL = ?, TELEPHONE = ?, MOBILE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE EMPLOYEE_CONTACT_INFORMATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING contact_information_type, email, telephone, mobile, transaction_log_id, record_log, employee_contact_information_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_employee_digital_signature` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_employee_digital_signature`(IN employee_id VARCHAR(100), IN employee_digital_signature VARCHAR(500), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employees SET EMPLOYEE_DIGITAL_SIGNATURE = ?, RECORD_LOG = ? WHERE EMPLOYEE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_digital_signature, record_log, employee_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_employee_educational_background` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_employee_educational_background`(IN employee_educational_background_id VARCHAR(100), IN institution_name VARCHAR(500), IN degree VARCHAR(100), IN field_of_study VARCHAR(200), IN start_date DATE, IN end_date DATE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_educational_background SET INSTITUTION_NAME = ?, DEGREE = ?, FIELD_OF_STUDY = ?, START_DATE = ?, END_DATE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE EMPLOYEE_EDUCATIONAL_BACKGROUND_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING institution_name, degree, field_of_study, start_date, end_date, transaction_log_id, record_log, employee_educational_background_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_employee_emergency_contacts` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_employee_emergency_contacts`(IN employee_emergency_contacts_id VARCHAR(100), IN emergency_contact_name VARCHAR(500), IN relationship VARCHAR(20), IN email VARCHAR(50), IN telephone VARCHAR(20), IN mobile VARCHAR(20), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_emergency_contacts SET EMERGENCY_CONTACT_NAME = ?, RELATIONSHIP = ?, EMAIL = ?, TELEPHONE = ?, MOBILE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE EMPLOYEE_EMERGENCY_CONTACT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING emergency_contact_name, relationship, email, telephone, mobile, transaction_log_id, record_log, employee_emergency_contacts_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_employee_employment_history` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_employee_employment_history`(IN employee_employment_history_id VARCHAR(100), IN company_name VARCHAR(500), IN job_title VARCHAR(100), IN start_date DATE, IN end_date DATE, IN description VARCHAR(10000), IN employment_certificate VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_employment_history SET COMPANY_NAME = ?, JOB_TITLE = ?, START_DATE = ?, END_DATE = ?, DESCRIPTION = ?, EMPLOYMENT_CERTIFICATE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE EMPLOYEE_EMPLOYMENT_HISTORY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING company_name, job_title, start_date, end_date, description, employment_certificate, transaction_log_id, record_log, employee_employment_history_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_employee_family_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_employee_family_details`(IN employee_family_details_id VARCHAR(100), IN name VARCHAR(500), IN relationship VARCHAR(20), IN birthday DATE, IN age INT(5), IN school VARCHAR(100), IN employment VARCHAR(100), IN email VARCHAR(50), IN telephone VARCHAR(20), IN mobile VARCHAR(20), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_family_details SET NAME = ?, RELATIONSHIP = ?, BIRTHDAY = ?, AGE = ?, SCHOOL = ?, EMPLOYMENT = ?, EMAIL = ?, TELEPHONE = ?, MOBILE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE EMPLOYEE_FAMILY_DETAILS_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING name, relationship, birthday, age, school, employment, email, telephone, mobile, transaction_log_id, record_log, employee_family_details_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_employee_identification` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_employee_identification`(IN employee_identification_id VARCHAR(100), IN id_type VARCHAR(100), IN id_number VARCHAR(100), IN id_classification VARCHAR(20), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_identification SET ID_TYPE = ?, ID_NUMBER = ?, ID_CLASSIFICATION = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE EMPLOYEE_IDENTIFICATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING id_type, id_number, id_classification, transaction_log_id, record_log, employee_identification_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_employee_id_image` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_employee_id_image`(IN employee_identification_id VARCHAR(100), IN id_image VARCHAR(500), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_identification SET ID_IMAGE = ?, RECORD_LOG = ? WHERE EMPLOYEE_IDENTIFICATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING id_image, record_log, employee_identification_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_employee_image` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_employee_image`(IN employee_id VARCHAR(100), IN employee_image VARCHAR(500), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employees SET EMPLOYEE_IMAGE = ?, RECORD_LOG = ? WHERE EMPLOYEE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_image, record_log, employee_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_employee_personal_information` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_employee_personal_information`(IN employee_id VARCHAR(100), IN file_as VARCHAR(350), IN first_name VARCHAR(100), IN middle_name VARCHAR(100), IN last_name VARCHAR(100), IN suffix VARCHAR(5), IN nickname VARCHAR(20), IN civil_status VARCHAR(20), IN nationality VARCHAR(20), IN gender VARCHAR(20), IN birthday DATE, IN place_of_birth VARCHAR(500), IN blood_type VARCHAR(20), IN height DOUBLE, IN weight DOUBLE, IN religion VARCHAR(20), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_personal_information SET FILE_AS = ?, FIRST_NAME = ?, MIDDLE_NAME = ?, LAST_NAME = ?, SUFFIX = ?, NICKNAME = ?, CIVIL_STATUS = ?, NATIONALITY = ?, GENDER = ?, BIRTHDAY = ?, PLACE_OF_BIRTH = ?, BLOOD_TYPE = ?, HEIGHT = ?, WEIGHT = ?, RELIGION = ?, RECORD_LOG = ? WHERE EMPLOYEE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING file_as, first_name, middle_name, last_name, suffix, nickname, civil_status, nationality, gender, birthday, place_of_birth, blood_type, height, weight, religion, record_log, employee_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_employee_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_employee_status`(IN employee_id VARCHAR(100), IN employee_status TINYINT(1), IN offboard_date DATE, IN departure_reason VARCHAR(50), IN detailed_reason VARCHAR(500), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employees SET EMPLOYEE_STATUS = ?, OFFBOARD_DATE = ?, DEPARTURE_REASON = ?, DETAILED_REASON = ?, RECORD_LOG = ? WHERE EMPLOYEE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_status, offboard_date, departure_reason, detailed_reason, record_log, employee_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_employee_training_seminars` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_employee_training_seminars`(IN employee_training_seminars_id VARCHAR(100), IN training_name VARCHAR(200), IN training_date DATE, IN training_location VARCHAR(100), IN training_provider VARCHAR(100), IN description VARCHAR(1000), IN training_certificate VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_training_seminars SET TRAINING_NAME = ?, TRAINING_DATE = ?, TRAINING_LOCATION = ?, TRAINING_PROVIDER = ?, DESCRIPTION = ?, TRAINING_CERTIFICATE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE EMPLOYEE_TRAINING_SEMINARS_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING training_name, training_date, training_location, training_provider, description, training_certificate, transaction_log_id, record_log, employee_training_seminars_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_employee_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_employee_type`(IN employee_type_id VARCHAR(50), IN employee_type VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_employee_type SET EMPLOYEE_TYPE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE EMPLOYEE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_type, transaction_log_id, record_log, employee_type_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_id_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_id_type`(IN id_type_id VARCHAR(50), IN id_type VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_id_type SET ID_TYPE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE ID_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING id_type, transaction_log_id, record_log, id_type_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'UPDATE global_interface_setting SET INTERFACE_SETTING_NAME = ?, DESCRIPTION = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE INTERFACE_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING interface_setting_name, description, transaction_log_id, record_log, interface_setting_id;
	DEALLOCATE PREPARE stmt;
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
	IF request_type = 'login background' THEN
		SET @query = 'UPDATE global_interface_setting SET LOGIN_BACKGROUND = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE INTERFACE_SETTING_ID = ?';
	ELSEIF request_type = 'login logo' THEN
		SET @query = 'UPDATE global_interface_setting SET LOGIN_LOGO = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE INTERFACE_SETTING_ID = ?';
	ELSEIF request_type = 'menu logo' THEN
		SET @query = 'UPDATE global_interface_setting SET MENU_LOGO = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE INTERFACE_SETTING_ID = ?';
	ELSE
		SET @query = 'UPDATE global_interface_setting SET FAVICON = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE INTERFACE_SETTING_ID = ?';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt USING file_path, transaction_log_id, record_log, interface_setting_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'UPDATE global_interface_setting SET STATUS = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE INTERFACE_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING status, transaction_log_id, record_log, interface_setting_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_job_position` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_job_position`(IN job_position_id VARCHAR(100), IN job_position VARCHAR(100), IN description VARCHAR(500), IN department VARCHAR(50), IN expected_new_employees INT(10), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_job_position SET JOB_POSITION = ?, DESCRIPTION = ?, DEPARTMENT = ?, EXPECTED_NEW_EMPLOYEES = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING job_position, description, department, expected_new_employees, transaction_log_id, record_log, job_position_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_job_position_attachment` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_job_position_attachment`(IN attachment_id VARCHAR(100), IN attachment VARCHAR(500))
BEGIN
	SET @query = 'UPDATE employee_job_position_attachment SET ATTACHMENT = ? WHERE ATTACHMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING attachment, attachment_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_job_position_attachment_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_job_position_attachment_details`(IN attachment_id VARCHAR(100), IN job_position_id VARCHAR(100), IN attachment_name VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_job_position_attachment SET ATTACHMENT_NAME = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE ATTACHMENT_ID = ? AND JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING attachment_name, transaction_log_id, record_log, attachment_id, job_position_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_job_position_qualification` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_job_position_qualification`(IN qualification_id VARCHAR(100), IN job_position_id VARCHAR(100), IN qualification VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_job_position_qualification SET QUALIFICATION = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE QUALIFICATION_ID = ? AND JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING qualification, transaction_log_id, record_log, qualification_id, job_position_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_job_position_recruitment_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_job_position_recruitment_status`(IN job_position_id VARCHAR(50), IN recruitment_status TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	IF recruitment_status = 2 THEN
		SET @query = 'UPDATE employee_job_position SET RECRUITMENT_STATUS = ?, EXPECTED_NEW_EMPLOYEES = 0, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE JOB_POSITION_ID = ?';
	ELSE
		SET @query = 'UPDATE employee_job_position SET RECRUITMENT_STATUS = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE JOB_POSITION_ID = ?';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt USING recruitment_status, transaction_log_id, record_log, job_position_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_job_position_requirement` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_job_position_requirement`(IN requirement_id VARCHAR(100), IN job_position_id VARCHAR(100), IN requirement VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_job_position_requirement SET REQUIREMENT = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE REQUIREMENT_ID = ? AND JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING requirement, transaction_log_id, record_log, requirement_id, job_position_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_job_position_responsibility` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_job_position_responsibility`(IN responsibility_id VARCHAR(100), IN job_position_id VARCHAR(100), IN responsibility VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_job_position_responsibility SET RESPONSIBILITY = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE RESPONSIBILITY_ID = ? AND JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING responsibility, transaction_log_id, record_log, responsibility_id, job_position_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'UPDATE global_user_account SET';

    IF login_attemp > 0 THEN
		SET @query = CONCAT(@query, ' FAILED_LOGIN = ?, LAST_FAILED_LOGIN = ? WHERE USERNAME = ?');

		PREPARE stmt FROM @query;
		EXECUTE stmt USING login_attemp, last_failed_attempt_date, username;
	ELSE
		SET @query = CONCAT(@query, ' FAILED_LOGIN = ? WHERE USERNAME = ?');

		PREPARE stmt FROM @query;
		EXECUTE stmt USING login_attemp, username;
    END IF;

	DEALLOCATE PREPARE stmt;
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_module`(IN module_id VARCHAR(100), IN module_name VARCHAR(200), IN module_version VARCHAR(20), IN module_description VARCHAR(500), IN module_category VARCHAR(50), IN default_page VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100), IN order_sequence INT)
BEGIN
	SET @query = 'UPDATE technical_module SET MODULE_NAME = ?, MODULE_VERSION = ?, MODULE_DESCRIPTION = ?, MODULE_CATEGORY = ?, DEFAULT_PAGE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ?, ORDER_SEQUENCE = ? WHERE MODULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_name, module_version, module_description, module_category, default_page, transaction_log_id, record_log, order_sequence, module_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'UPDATE technical_module SET MODULE_ICON = ? WHERE MODULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_icon, module_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_notification_setting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_notification_setting`(IN notification_setting_id INT(50), IN notification_setting VARCHAR(100), IN description VARCHAR(200), IN notification_title VARCHAR(500), IN notification_message VARCHAR(500), IN system_link VARCHAR(200), IN email_link VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_notification_setting SET NOTIFICATION_SETTING = ?, DESCRIPTION = ?, NOTIFICATION_TITLE = ?, NOTIFICATION_MESSAGE = ?, SYSTEM_LINK = ?, EMAIL_LINK = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE NOTIFICATION_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting, description, notification_title, notification_message, system_link, email_link, transaction_log_id, record_log, notification_setting_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_other_email_setting_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_other_email_setting_status`(IN email_setting_id INT(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_email_setting SET STATUS = 2, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE EMAIL_SETTING_ID != ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING transaction_log_id, record_log, email_setting_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'UPDATE global_interface_setting SET STATUS = 2, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE INTERFACE_SETTING_ID != ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING transaction_log_id, record_log, interface_setting_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_other_zoom_api_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_other_zoom_api_status`(IN zoom_api_id INT(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_zoom_api SET STATUS = 2, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE ZOOM_API_ID != ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING transaction_log_id, record_log, zoom_api_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'UPDATE technical_page SET PAGE_NAME = ?, MODULE_ID = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE PAGE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING page_name, module_id, transaction_log_id, record_log, page_id;
	DEALLOCATE PREPARE stmt;
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_role`(IN role_id VARCHAR(100), IN role VARCHAR(100), IN role_description VARCHAR(200), IN assignable TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_role SET ROLE = ?, ROLE_DESCRIPTION = ?, ASSIGNABLE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role, role_description, assignable, transaction_log_id, record_log, role_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_state` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_state`(IN state_id INT(50), IN state_name VARCHAR(200), IN country_id INT(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_state SET STATE_NAME = ?, COUNTRY_ID = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE STATE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING state_name, country_id, transaction_log_id, record_log, state_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'UPDATE global_system_code SET SYSTEM_TYPE = ?, SYSTEM_CODE = ?, SYSTEM_DESCRIPTION = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE SYSTEM_CODE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING system_type, system_code, system_description, transaction_log_id, record_log, system_code_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'UPDATE global_system_parameters SET PARAMETER = ?, PARAMETER_DESCRIPTION = ?, PARAMETER_EXTENSION = ?, PARAMETER_NUMBER = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE PARAMETER_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING parameter, parameter_description, extension, parameter_number, transaction_log_id, record_log, parameter_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'UPDATE global_system_parameters SET PARAMETER_NUMBER = ?, RECORD_LOG = ? WHERE PARAMETER_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING parameter_number, record_log, parameter_id;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'UPDATE global_upload_setting SET UPLOAD_SETTING = ?, DESCRIPTION = ?, MAX_FILE_SIZE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE UPLOAD_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting, description, max_file_size, transaction_log_id, record_log, upload_setting_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_user_account` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_user_account`(IN username VARCHAR(50), IN password VARCHAR(200), IN file_as VARCHAR (300), IN password_expiry_date DATE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_user_account SET';

	IF password IS NOT NULL OR password <> '' THEN
		SET @query = CONCAT(@query, ' FILE_AS = ?, PASSWORD = ?, PASSWORD_EXPIRY_DATE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE USERNAME = ?');

		PREPARE stmt FROM @query;
		EXECUTE stmt USING file_as, password, password_expiry_date, transaction_log_id, record_log, username;
	ELSE
		SET @query = CONCAT(@query, ' FILE_AS = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE USERNAME = ?');

		PREPARE stmt FROM @query;
		EXECUTE stmt USING file_as, transaction_log_id, record_log, username;
    END IF;

	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_user_account_lock_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_user_account_lock_status`(IN username VARCHAR(50), IN transaction_type VARCHAR(10), IN last_failed_login DATE, IN record_log VARCHAR(100))
BEGIN
	IF transaction_type = 'unlock' THEN
		SET @query = 'UPDATE global_user_account SET FAILED_LOGIN = 0, LAST_FAILED_LOGIN = ?, RECORD_LOG = ? WHERE USERNAME = ?';
	ELSE
		SET @query = 'UPDATE global_user_account SET FAILED_LOGIN = 5, LAST_FAILED_LOGIN = ?, RECORD_LOG = ? WHERE USERNAME = ?';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt USING last_failed_login, record_log, username;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'UPDATE global_user_account SET PASSWORD = ?, PASSWORD_EXPIRY_DATE = ? WHERE USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING password, password_expiry_date, username;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_user_account_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_user_account_status`(IN username VARCHAR(50), IN user_status VARCHAR(10), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_user_account SET USER_STATUS = ?, RECORD_LOG = ? WHERE USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING user_status, record_log, username;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'UPDATE global_user_account SET LAST_CONNECTION_DATE = ? WHERE USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING last_connection_date, username;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_wage_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_wage_type`(IN wage_type_id VARCHAR(50), IN wage_type VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_wage_type SET WAGE_TYPE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE WAGE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING wage_type, transaction_log_id, record_log, wage_type_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_working_hours` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_working_hours`(IN working_hours_id VARCHAR(100), IN working_schedule_id VARCHAR(100), IN working_hours VARCHAR(100), IN working_date DATE, IN day_of_week VARCHAR(20), IN day_period VARCHAR(20), IN work_from TIME, IN work_to TIME, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_working_hours SET WORKING_HOURS = ?, WORKING_DATE = ?, DAY_OF_WEEK = ?, DAY_PERIOD = ?, WORK_FROM = ?, WORK_TO = ?, TRANSACTION_LOG_ID =?, RECORD_LOG = ? WHERE WORKING_HOURS_ID = ? AND WORKING_SCHEDULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_hours, working_date, day_of_week, day_period, work_from, work_to, transaction_log_id, record_log, working_hours_id, working_schedule_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_working_schedule` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_working_schedule`(IN `working_schedule_id` VARCHAR(100), IN `working_schedule` VARCHAR(100), IN `working_schedule_type` VARCHAR(20), IN `transaction_log_id` VARCHAR(100), IN `record_log` VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_working_schedule SET WORKING_SCHEDULE = ?, WORKING_SCHEDULE_TYPE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE WORKING_SCHEDULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule, working_schedule_type, transaction_log_id, record_log, working_schedule_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_working_schedule_type` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_working_schedule_type`(IN working_schedule_type_id VARCHAR(50), IN working_schedule_type VARCHAR(100), IN working_schedule_type_category VARCHAR(20), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_working_schedule_type SET WORKING_SCHEDULE_TYPE = ?, WORKING_SCHEDULE_TYPE_CATEGORY = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE WORKING_SCHEDULE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_type, working_schedule_type_category, transaction_log_id, record_log, working_schedule_type_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_work_location` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_work_location`(IN work_location_id VARCHAR(50), IN work_location VARCHAR(100), IN work_location_address VARCHAR(500), IN email VARCHAR(50), IN telephone VARCHAR(50), IN mobile VARCHAR(50), IN location_number INT, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_work_location SET WORK_LOCATION = ?, WORK_LOCATION_ADDRESS = ?, EMAIL = ?, TELEPHONE = ?, MOBILE = ?, LOCATION_NUMBER = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE WORK_LOCATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING work_location, work_location_address, email, telephone, mobile, location_number, transaction_log_id, record_log, work_location_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_work_location_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_work_location_status`(IN work_location_id VARCHAR(50), IN status TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_work_location SET STATUS = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE WORK_LOCATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING status, transaction_log_id, record_log, work_location_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_zoom_api` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_zoom_api`(IN zoom_api_id INT(50), IN zoom_api_name VARCHAR(100), IN description VARCHAR(200), IN api_key VARCHAR(1000), IN api_secret VARCHAR(1000), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_zoom_api SET ZOOM_API_NAME = ?, DESCRIPTION = ?, API_KEY = ?, API_SECRET = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE ZOOM_API_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING zoom_api_name, description, api_key, api_secret, transaction_log_id, record_log, zoom_api_id;
	DEALLOCATE PREPARE stmt;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_zoom_api_status` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_zoom_api_status`(IN zoom_api_id INT(50), IN status TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_zoom_api SET STATUS = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE ZOOM_API_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING status, transaction_log_id, record_log, zoom_api_id;
	DEALLOCATE PREPARE stmt;
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

-- Dump completed on 2023-03-31 17:23:50
