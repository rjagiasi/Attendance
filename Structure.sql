-- phpMyAdmin SQL Dump
-- version 4.4.13.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 04, 2016 at 11:28 PM
-- Server version: 5.6.31-0ubuntu0.15.10.1
-- PHP Version: 5.6.11-1ubuntu3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Attendance`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetClasses`(IN `sid` INT)
    NO SQL
SELECT classes.Name as c, dept.Name as d from
					(SELECT Name, DeptId from Class Where ClassId in
						(SELECT distinct ClassId From Subjects Where SubjectId in
							(SELECT distinct SubjectId from Labs Where StaffId = sid
							UNION
							SELECT distinct SubjectId from Lectures Where StaffId = sid
							)
						)
					) as classes, Department as dept
					WHERE classes.DeptId = dept.DeptId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetClassReport`(IN `startDate` DATE, IN `endDate` DATE, IN `classId` INT(10))
    NO SQL
(select 
 `Student`.`RollNo` AS `RollNo`,
 `Record`.`SubjectId` AS `SubjectId`,
 
COUNT(CASE WHEN Record.PA = 0x01 then 1 ELSE NULL END) as "Pres",
COUNT(CASE WHEN Record.PA = 0x00 then 1 ELSE NULL END) as "Abs"
 
 from (`Student` 
       join `Record` 
       on(((
           `Student`.`StudentId` = `Record`.`StudentId`
       ) 
           AND Student.ClassId = classId 
           AND Record.Date BETWEEN startDate AND endDate))) 
 group by `Record`.`SubjectId`,`Record`.`StudentId`)
 ORDER BY RollNo, SubjectId ASC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetStudentReport`(IN `class` INT, IN `rollno` INT)
    NO SQL
(select 
 `Attendance`.`Record`.`SubjectId` AS `SubjectId`,
 
COUNT(CASE WHEN Record.PA = 0x01 then 1 ELSE NULL END) as "Pres",
COUNT(CASE WHEN Record.PA = 0x00 then 1 ELSE NULL END) as "Abs"
 
 from (`Attendance`.`Student` 
       join `Attendance`.`Record` 
       on(((
           `Attendance`.`Student`.`StudentId` = `Attendance`.`Record`.`StudentId`
       ) 
           AND Student.ClassId = class 
           AND Student.RollNo = rollno
          ))) 
 group by `Attendance`.`Record`.`SubjectId`)
 ORDER BY SubjectId ASC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetSubjectReport`(IN `startDate` DATE, IN `endDate` DATE, IN `classId` INT, IN `subjectId` INT)
    NO SQL
(select 
 `Attendance`.`Student`.`RollNo` AS `RollNo`,
 
COUNT(CASE WHEN Record.PA = 0x01 then 1 ELSE NULL END) as "Pres",
COUNT(CASE WHEN Record.PA = 0x00 then 1 ELSE NULL END) as "Abs"
 
 from (`Attendance`.`Student` 
       join `Attendance`.`Record` 
       on(((
           `Attendance`.`Student`.`StudentId` = `Attendance`.`Record`.`StudentId`
       ) 
           AND Student.ClassId = classId 
           AND Record.SubjectId = subjectId
           AND Record.Date BETWEEN startDate AND endDate))) 
 group by `Attendance`.`Record`.`SubjectId`,`Attendance`.`Record`.`StudentId`)
 ORDER BY RollNo ASC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetSubjects`(IN `startDate` DATE, IN `endDate` DATE, IN `classId` INT)
    NO SQL
SELECT s.Name, s.SubjectId 
FROM Subjects as s
Where 
EXISTS 
(SELECT * FROM Record WHERE Record.Date BETWEEN startDate AND endDate
AND Record.SubjectId = s.SubjectId
AND s.ClassId = classId)

ORDER BY SubjectId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertSub`(IN `class` INT(10), IN `name` VARCHAR(20), IN `ll` BIT(1), IN `id` INT(10), IN `days` BIT(6))
    NO SQL
Select * from Class$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Cancelled`
--

CREATE TABLE IF NOT EXISTS `Cancelled` (
  `StaffId` int(10) NOT NULL,
  `SubjectId` int(10) NOT NULL,
  `Date` date NOT NULL,
  `Reason` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Class`
--

CREATE TABLE IF NOT EXISTS `Class` (
  `ClassId` int(10) NOT NULL,
  `Name` varchar(10) NOT NULL,
  `DeptId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Department`
--

CREATE TABLE IF NOT EXISTS `Department` (
  `DeptId` int(10) NOT NULL,
  `Name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Labs`
--

CREATE TABLE IF NOT EXISTS `Labs` (
  `LabId` int(10) NOT NULL,
  `SubjectId` int(10) NOT NULL,
  `BatchId` int(1) NOT NULL,
  `StaffId` int(10) NOT NULL,
  `Days` bit(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `LabStudent`
--

CREATE TABLE IF NOT EXISTS `LabStudent` (
  `ClassId` int(10) NOT NULL,
  `BatchId` int(1) NOT NULL,
  `StudentId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Lectures`
--

CREATE TABLE IF NOT EXISTS `Lectures` (
  `SubjectId` int(10) NOT NULL,
  `StaffId` int(10) NOT NULL,
  `Days` bit(6) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Stand-in structure for view `NOL`
--
CREATE TABLE IF NOT EXISTS `NOL` (
`Name` varchar(20)
,`ClassId` int(10)
,`SubjectId` int(10)
,`nooflect` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `Notifs`
--

CREATE TABLE IF NOT EXISTS `Notifs` (
  `SubjectId` int(10) NOT NULL,
  `DateMissed` date NOT NULL,
  `StaffId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Record`
--

CREATE TABLE IF NOT EXISTS `Record` (
  `Date` date NOT NULL,
  `StudentId` int(10) NOT NULL,
  `SubjectId` int(10) NOT NULL,
  `PA` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Staff`
--

CREATE TABLE IF NOT EXISTS `Staff` (
  `StaffId` int(10) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Gender` bit(1) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Salt` varchar(255) NOT NULL,
  `RegisterTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Student`
--

CREATE TABLE IF NOT EXISTS `Student` (
  `StudentId` int(10) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `ClassId` int(10) NOT NULL,
  `RollNo` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Subjects`
--

CREATE TABLE IF NOT EXISTS `Subjects` (
  `SubjectId` int(10) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `ClassId` int(10) NOT NULL,
  `LectorLab` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure for view `NOL`
--
DROP TABLE IF EXISTS `NOL`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `NOL` AS (select `Subjects`.`Name` AS `Name`,`Subjects`.`ClassId` AS `ClassId`,`Subjects`.`SubjectId` AS `SubjectId`,count(distinct `Record`.`Date`) AS `nooflect` from (`Subjects` join `Record` on((`Subjects`.`SubjectId` = `Record`.`SubjectId`))) group by `Record`.`SubjectId`);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Cancelled`
--
ALTER TABLE `Cancelled`
  ADD UNIQUE KEY `StaffId` (`StaffId`,`SubjectId`,`Date`),
  ADD KEY `SubjectId` (`SubjectId`);

--
-- Indexes for table `Class`
--
ALTER TABLE `Class`
  ADD PRIMARY KEY (`ClassId`),
  ADD UNIQUE KEY `Name` (`Name`),
  ADD KEY `DeptId` (`DeptId`);

--
-- Indexes for table `Department`
--
ALTER TABLE `Department`
  ADD PRIMARY KEY (`DeptId`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `Labs`
--
ALTER TABLE `Labs`
  ADD PRIMARY KEY (`LabId`),
  ADD UNIQUE KEY `SubjectId` (`SubjectId`,`BatchId`),
  ADD KEY `StaffId` (`StaffId`);

--
-- Indexes for table `LabStudent`
--
ALTER TABLE `LabStudent`
  ADD UNIQUE KEY `SubjectId` (`ClassId`,`StudentId`),
  ADD KEY `LabId` (`BatchId`),
  ADD KEY `StudentId` (`StudentId`);

--
-- Indexes for table `Lectures`
--
ALTER TABLE `Lectures`
  ADD UNIQUE KEY `SubjectId` (`SubjectId`),
  ADD KEY `ClassId` (`StaffId`);

--
-- Indexes for table `Notifs`
--
ALTER TABLE `Notifs`
  ADD UNIQUE KEY `SubjectId_2` (`SubjectId`,`DateMissed`,`StaffId`),
  ADD KEY `StaffId` (`StaffId`);

--
-- Indexes for table `Record`
--
ALTER TABLE `Record`
  ADD PRIMARY KEY (`Date`,`StudentId`,`SubjectId`),
  ADD KEY `StudentId` (`StudentId`,`SubjectId`),
  ADD KEY `Record_ibfk_2` (`SubjectId`);

--
-- Indexes for table `Staff`
--
ALTER TABLE `Staff`
  ADD PRIMARY KEY (`StaffId`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `Student`
--
ALTER TABLE `Student`
  ADD PRIMARY KEY (`StudentId`),
  ADD UNIQUE KEY `ClassId` (`ClassId`,`RollNo`);

--
-- Indexes for table `Subjects`
--
ALTER TABLE `Subjects`
  ADD PRIMARY KEY (`SubjectId`),
  ADD UNIQUE KEY `Name` (`Name`),
  ADD KEY `ClassId` (`ClassId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Class`
--
ALTER TABLE `Class`
  MODIFY `ClassId` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Department`
--
ALTER TABLE `Department`
  MODIFY `DeptId` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Labs`
--
ALTER TABLE `Labs`
  MODIFY `LabId` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Staff`
--
ALTER TABLE `Staff`
  MODIFY `StaffId` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Student`
--
ALTER TABLE `Student`
  MODIFY `StudentId` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Subjects`
--
ALTER TABLE `Subjects`
  MODIFY `SubjectId` int(10) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `Cancelled`
--
ALTER TABLE `Cancelled`
  ADD CONSTRAINT `Cancelled_ibfk_1` FOREIGN KEY (`StaffId`) REFERENCES `Staff` (`StaffId`),
  ADD CONSTRAINT `Cancelled_ibfk_2` FOREIGN KEY (`SubjectId`) REFERENCES `Subjects` (`SubjectId`);

--
-- Constraints for table `Class`
--
ALTER TABLE `Class`
  ADD CONSTRAINT `Class_ibfk_1` FOREIGN KEY (`DeptId`) REFERENCES `Department` (`DeptId`);

--
-- Constraints for table `Labs`
--
ALTER TABLE `Labs`
  ADD CONSTRAINT `Labs_ibfk_1` FOREIGN KEY (`SubjectId`) REFERENCES `Subjects` (`SubjectId`) ON DELETE CASCADE,
  ADD CONSTRAINT `Labs_ibfk_2` FOREIGN KEY (`StaffId`) REFERENCES `Staff` (`StaffId`);

--
-- Constraints for table `LabStudent`
--
ALTER TABLE `LabStudent`
  ADD CONSTRAINT `LabStudent_ibfk_2` FOREIGN KEY (`ClassId`) REFERENCES `Class` (`ClassId`),
  ADD CONSTRAINT `LabStudent_ibfk_3` FOREIGN KEY (`StudentId`) REFERENCES `Student` (`StudentId`);

--
-- Constraints for table `Lectures`
--
ALTER TABLE `Lectures`
  ADD CONSTRAINT `Lectures_ibfk_1` FOREIGN KEY (`SubjectId`) REFERENCES `Subjects` (`SubjectId`) ON DELETE CASCADE,
  ADD CONSTRAINT `Lectures_ibfk_3` FOREIGN KEY (`StaffId`) REFERENCES `Staff` (`StaffId`);

--
-- Constraints for table `Notifs`
--
ALTER TABLE `Notifs`
  ADD CONSTRAINT `Notifs_ibfk_1` FOREIGN KEY (`SubjectId`) REFERENCES `Subjects` (`SubjectId`) ON DELETE CASCADE,
  ADD CONSTRAINT `Notifs_ibfk_2` FOREIGN KEY (`StaffId`) REFERENCES `Staff` (`StaffId`);

--
-- Constraints for table `Record`
--
ALTER TABLE `Record`
  ADD CONSTRAINT `Record_ibfk_1` FOREIGN KEY (`StudentId`) REFERENCES `Student` (`StudentId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Record_ibfk_2` FOREIGN KEY (`SubjectId`) REFERENCES `Subjects` (`SubjectId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Student`
--
ALTER TABLE `Student`
  ADD CONSTRAINT `Student_ibfk_1` FOREIGN KEY (`ClassId`) REFERENCES `Class` (`ClassId`);

--
-- Constraints for table `Subjects`
--
ALTER TABLE `Subjects`
  ADD CONSTRAINT `Subjects_ibfk_1` FOREIGN KEY (`ClassId`) REFERENCES `Class` (`ClassId`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
