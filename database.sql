-- MySQL dump 10.13  Distrib 5.6.28, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: Attendance
-- ------------------------------------------------------
-- Server version	5.6.28-0ubuntu0.15.10.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Class`
--

DROP TABLE IF EXISTS `Class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Class` (
  `ClassId` int(10) NOT NULL AUTO_INCREMENT,
  `Name` varchar(10) NOT NULL,
  `DeptId` int(10) NOT NULL,
  PRIMARY KEY (`ClassId`),
  KEY `DeptId` (`DeptId`),
  CONSTRAINT `Class_ibfk_1` FOREIGN KEY (`DeptId`) REFERENCES `Department` (`DeptId`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Class`
--

LOCK TABLES `Class` WRITE;
/*!40000 ALTER TABLE `Class` DISABLE KEYS */;
INSERT INTO `Class` VALUES (1,'D10',1),(2,'D15',1);
/*!40000 ALTER TABLE `Class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Department`
--

DROP TABLE IF EXISTS `Department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Department` (
  `DeptId` int(10) NOT NULL AUTO_INCREMENT,
  `Name` varchar(20) NOT NULL,
  PRIMARY KEY (`DeptId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Department`
--

LOCK TABLES `Department` WRITE;
/*!40000 ALTER TABLE `Department` DISABLE KEYS */;
INSERT INTO `Department` VALUES (1,'IT');
/*!40000 ALTER TABLE `Department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Record`
--

DROP TABLE IF EXISTS `Record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Record` (
  `Date` date NOT NULL,
  `StudentId` int(10) NOT NULL,
  `SubjectId` int(10) NOT NULL,
  `P/A` bit(1) NOT NULL,
  KEY `StudentId` (`StudentId`,`SubjectId`),
  KEY `SubjectId` (`SubjectId`),
  CONSTRAINT `Record_ibfk_1` FOREIGN KEY (`StudentId`) REFERENCES `Student` (`StudentId`),
  CONSTRAINT `Record_ibfk_2` FOREIGN KEY (`SubjectId`) REFERENCES `Subjects` (`SubjectId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Record`
--

LOCK TABLES `Record` WRITE;
/*!40000 ALTER TABLE `Record` DISABLE KEYS */;
/*!40000 ALTER TABLE `Record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Staff`
--

DROP TABLE IF EXISTS `Staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Staff` (
  `StaffId` int(10) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `Gender` bit(1) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Salt` varchar(255) NOT NULL,
  `RegisterTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`StaffId`),
  UNIQUE KEY `Email` (`Email`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Staff`
--

LOCK TABLES `Staff` WRITE;
/*!40000 ALTER TABLE `Staff` DISABLE KEYS */;
/*!40000 ALTER TABLE `Staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Student`
--

DROP TABLE IF EXISTS `Student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Student` (
  `StudentId` int(10) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `ClassId` int(10) NOT NULL,
  `RollNo` int(3) NOT NULL,
  PRIMARY KEY (`StudentId`),
  UNIQUE KEY `ClassId` (`ClassId`,`RollNo`),
  CONSTRAINT `Student_ibfk_1` FOREIGN KEY (`ClassId`) REFERENCES `Class` (`ClassId`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Student`
--

LOCK TABLES `Student` WRITE;
/*!40000 ALTER TABLE `Student` DISABLE KEYS */;
INSERT INTO `Student` VALUES (1,'Agarwal Chayan Dilip Jyoti\r\n',1,1),(2,'Ahuja Jitesh Hareshlal Rachna\r\n',1,2),(3,'Ahuja Pratik Deepak Vinitha\r\n',1,3),(4,'Ambavane Arpita Anil Seema\r\n',1,4),(5,'Chanchlani Sahil Anil Mamta\r\n',1,5),(6,'Chandiramani Jeetiksha Jagdish Uma\r\n',1,6),(7,'Chawla Girish Ajitlal Manisha\r\n',1,7),(8,'Chhabria Rashmi Sunil Tejal\r\n',1,8),(9,'Chhabria Sonia Vinod Payal\r\n',1,9),(10,'Daryani Sonia Haresh Renu\r\n',1,10),(11,'Dayaramani Kunal Ashokkumar Kiran\r\n',1,11),(12,'Dharne Ankita Yashavant Vijaya\r\n',1,12),(13,'Fatnani Amit jayant Chandni\r\n',1,13),(14,'Gaikwad Laukik Deepak Pooja\r\n',1,14),(15,'Gaikwad Vaibhav Ramdas Ratika\r\n',1,15),(16,'Gavane Aditya Jayant Sangeeta\r\n',1,16),(17,'Ghosalkar Shubham Sudesh Shraddha\r\n',1,17),(18,'Goyal Vaibhav Shailandra Daya\r\n',1,18),(19,'Gulrajani Arpan Pralhad Meera\r\n',1,19),(20,'Gurumurthy Ajay Sujatha\r\n',1,20),(21,'Harjani Neeraj Haresh Renu\r\n',1,21),(22,'Hindalekar Siddhesh Deepak Vanita\r\n',1,22),(23,'Hinduja Jai Ravindra Bharati\r\n',1,23),(24,'Holla Sooraj Vaman Jayalakshmi\r\n',1,24),(25,'Jagiasi Rohan Mahesh Ritu\r\n',1,25),(26,'Jaisinghani Anjali Chander Varsha\r\n',1,26),(27,'Jethwani Nikhil Vijay Anita\r\n',1,27),(28,'Jetwani Aniket Pradeep Bhavna\r\n',1,28),(29,'Jhawar Raghav Sandeepkumar Kiran\r\n',1,29),(30,'Juvekar Kalpesh Deepak Dipali\r\n',1,30),(31,'Kalla Ayush Opendra Arti\r\n',1,31),(32,'Karda Uddesh Narayan Divya\r\n',1,32),(33,'Karle Nihar Girish Dipali\r\n',1,33),(34,'Katkar Akash Nitin Rakhi\r\n',1,34),(35,'Kesharwani Ankit Ramdas Usha\r\n',1,35),(36,'Kewlani Dhiren Jagdish Vanita\r\n',1,36),(37,'Khatwani Bhavna Rajesh Sonal\r\n',1,37),(38,'Khiani Akash Gopal Kajal\r\n',1,38),(39,'Kogta Sushil Satish Rajashree\r\n',1,39),(40,'Kulal Punit Sukumar Premalatha\r\n',1,40),(41,'Lalwani Rohit Rameshlal Bhavna\r\n',1,41),(42,'Lilani Akshaykumar Jagdish Vinita\r\n',1,42),(43,'Mhadnak Siddhesh Satish Sunita\r\n',1,43),(44,'Mirjankar Siddhesh Nitin Anita\r\n',1,44),(45,'Motwani Jayesh Naresh Divya\r\n',1,45),(46,'Motwani Ram Naresh Pushpa\r\n',1,46),(47,'Murpana Karan Vijay Mala\r\n',1,47),(48,'Nagpal Jayesh Harish Reshma\r\n',1,48),(49,'Nighot Aniket Prakash Surekha\r\n',1,49),(50,'Parab Ameya Dattaram Shubhada\r\n',1,50),(51,'Parwani Mahesh Suresh Kanchan\r\n',1,51),(52,'Popat Payal Dipak Arti\r\n',1,52),(53,'Purswani Juhi Deepak Kiran\r\n',1,53),(54,'Raghuwanshi Radhika Rajendra Geeta\r\n',1,54),(55,'Rajpal Simran Prakash Vinita\r\n',1,55),(56,'Ramchandani Raj Mahesh Neha\r\n',1,56),(57,'Rohra Nitin Rajesh Sonia\r\n',1,57),(58,'Rupani Dinesh Dilip Vinita\r\n',1,58),(59,'Sajnani Shikha Gurmukh Varsha\r\n',1,59),(60,'Sapla Sumeet Gobindram Lata\r\n',1,60),(61,'Sayyed Ashraf Hussain \r\n',1,61),(62,'Shah Krima Nikesh Shilpa\r\n',1,62),(63,'Shaha Aarju Rahim Faridabanu\r\n',1,63),(64,'Shahani Hitesh Dayal Vidya\r\n',1,64),(65,'Shetty Rahul Jayaram Ahalya\r\n',1,65),(66,'Singh Pooja Vijaykumar Sarla\r\n',1,66),(67,'Solanki Miten Chandulal Rita\r\n',1,67),(68,'Srinivasan Krithika Sanjay Radhika\r\n',1,68),(69,'Tilwani Pawan Dilip Varsha\r\n',1,69),(70,'Tripathi Saurabh Rameshkumar Dipti\r\n',1,70),(71,'Turshani Deepa Khemaldas Mohini\r\n',1,71),(72,'Valecha Abhinav Vinod Manisha\r\n',1,72),(73,'Wadhwa Rohit Dilipkumar kanchan\r\n',1,73),(74,'Wagle Sushant Surendra Supriya\r\n',1,74),(75,'Wani Anjali Ananth Anagha\r\n',1,75),(76,'Yadav Rajeevkumar Shridhar Malti\r\n',1,76),(77,'Yadav Ravi Shyamnarayan Savita\r\n',1,77),(78,'Yedelli Rohini Dhananjay Lavanya',1,78);
/*!40000 ALTER TABLE `Student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Subjects`
--

DROP TABLE IF EXISTS `Subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Subjects` (
  `SubjectId` int(10) NOT NULL AUTO_INCREMENT,
  `Name` varchar(20) NOT NULL,
  `ClassId` int(10) NOT NULL,
  `StaffId` int(10) NOT NULL,
  PRIMARY KEY (`SubjectId`),
  KEY `ClassId` (`ClassId`),
  KEY `StaffId` (`StaffId`),
  CONSTRAINT `Subjects_ibfk_1` FOREIGN KEY (`ClassId`) REFERENCES `Class` (`ClassId`),
  CONSTRAINT `Subjects_ibfk_2` FOREIGN KEY (`StaffId`) REFERENCES `Staff` (`StaffId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Subjects`
--

LOCK TABLES `Subjects` WRITE;
/*!40000 ALTER TABLE `Subjects` DISABLE KEYS */;
/*!40000 ALTER TABLE `Subjects` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-06 16:17:55
