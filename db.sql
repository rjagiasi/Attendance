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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
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
  `Salt` varchar(255) NOT NULL,
  `RegisterTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`StaffId`),
  UNIQUE KEY `Email` (`Email`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Staff`
--

LOCK TABLES `Staff` WRITE;
/*!40000 ALTER TABLE `Staff` DISABLE KEYS */;
INSERT INTO `Staff` VALUES (2,'Rohan','','rjagiasi@gmail.com','admin','$1$9hWOk6kq$2GJTfzS214qNqXvy8Scsb1','2016-03-10 08:56:34');
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
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Student`
--

LOCK TABLES `Student` WRITE;
/*!40000 ALTER TABLE `Student` DISABLE KEYS */;
INSERT INTO `Student` VALUES (79,'Agarwal Chayan Dilip Jyoti',1,1),(80,'Ahuja Jitesh Hareshlal Rachna',1,2),(81,'Ahuja Pratik Deepak Vinitha',1,3),(82,'Ambavane Arpita Anil Seema',1,4),(83,'Chanchlani Sahil Anil Mamta',1,5),(84,'Chandiramani Jeetiksha Jagdish Uma',1,6),(85,'Chawla Girish Ajitlal Manisha',1,7),(86,'Chhabria Rashmi Sunil Tejal',1,8),(87,'Chhabria Sonia Vinod Payal',1,9),(88,'Daryani Sonia Haresh Renu',1,10),(89,'Dayaramani Kunal Ashokkumar Kiran',1,11),(90,'Dharne Ankita Yashavant Vijaya',1,12),(91,'Fatnani Amit jayant Chandni',1,13),(92,'Gaikwad Laukik Deepak Pooja',1,14),(93,'Gaikwad Vaibhav Ramdas Ratika',1,15),(94,'Gavane Aditya Jayant Sangeeta',1,16),(95,'Ghosalkar Shubham Sudesh Shraddha',1,17),(96,'Goyal Vaibhav Shailandra Daya',1,18),(97,'Gulrajani Arpan Pralhad Meera',1,19),(98,'Gurumurthy Ajay Sujatha',1,20),(99,'Harjani Neeraj Haresh Renu',1,21),(100,'Hindalekar Siddhesh Deepak Vanita',1,22),(101,'Hinduja Jai Ravindra Bharati',1,23),(102,'Holla Sooraj Vaman Jayalakshmi',1,24),(103,'Jagiasi Rohan Mahesh Ritu',1,25),(104,'Jaisinghani Anjali Chander Varsha',1,26),(105,'Jethwani Nikhil Vijay Anita',1,27),(106,'Jetwani Aniket Pradeep Bhavna',1,28),(107,'Jhawar Raghav Sandeepkumar Kiran',1,29),(108,'Juvekar Kalpesh Deepak Dipali',1,30),(109,'Kalla Ayush Opendra Arti',1,31),(110,'Karda Uddesh Narayan Divya',1,32),(111,'Karle Nihar Girish Dipali',1,33),(112,'Katkar Akash Nitin Rakhi',1,34),(113,'Kesharwani Ankit Ramdas Usha',1,35),(114,'Kewlani Dhiren Jagdish Vanita',1,36),(115,'Khatwani Bhavna Rajesh Sonal',1,37),(116,'Khiani Akash Gopal Kajal',1,38),(117,'Kogta Sushil Satish Rajashree',1,39),(118,'Kulal Punit Sukumar Premalatha',1,40),(119,'Lalwani Rohit Rameshlal Bhavna',1,41),(120,'Lilani Akshaykumar Jagdish Vinita',1,42),(121,'Mhadnak Siddhesh Satish Sunita',1,43),(122,'Mirjankar Siddhesh Nitin Anita',1,44),(123,'Motwani Jayesh Naresh Divya',1,45),(124,'Motwani Ram Naresh Pushpa',1,46),(125,'Murpana Karan Vijay Mala',1,47),(126,'Nagpal Jayesh Harish Reshma',1,48),(127,'Nighot Aniket Prakash Surekha',1,49),(128,'Parab Ameya Dattaram Shubhada',1,50),(129,'Parwani Mahesh Suresh Kanchan',1,51),(130,'Popat Payal Dipak Arti',1,52),(131,'Purswani Juhi Deepak Kiran',1,53),(132,'Raghuwanshi Radhika Rajendra Geeta',1,54),(133,'Rajpal Simran Prakash Vinita',1,55),(134,'Ramchandani Raj Mahesh Neha',1,56),(135,'Rohra Nitin Rajesh Sonia',1,57),(136,'Rupani Dinesh Dilip Vinita',1,58),(137,'Sajnani Shikha Gurmukh Varsha',1,59),(138,'Sapla Sumeet Gobindram Lata',1,60),(139,'Sayyed Ashraf Hussain ',1,61),(140,'Shah Krima Nikesh Shilpa',1,62),(141,'Shaha Aarju Rahim Faridabanu',1,63),(142,'Shahani Hitesh Dayal Vidya',1,64),(143,'Shetty Rahul Jayaram Ahalya',1,65),(144,'Singh Pooja Vijaykumar Sarla',1,66),(145,'Solanki Miten Chandulal Rita',1,67),(146,'Srinivasan Krithika Sanjay Radhika',1,68),(147,'Tilwani Pawan Dilip Varsha',1,69),(148,'Tripathi Saurabh Rameshkumar Dipti',1,70),(149,'Turshani Deepa Khemaldas Mohini',1,71),(150,'Valecha Abhinav Vinod Manisha',1,72),(151,'Wadhwa Rohit Dilipkumar kanchan',1,73),(152,'Wagle Sushant Surendra Supriya',1,74),(153,'Wani Anjali Ananth Anagha',1,75),(154,'Yadav Rajeevkumar Shridhar Malti',1,76),(155,'Yadav Ravi Shyamnarayan Savita',1,77),(156,'Yedelli Rohini Dhananjay Lavanya',1,78);
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

-- Dump completed on 2016-03-11 13:30:19
