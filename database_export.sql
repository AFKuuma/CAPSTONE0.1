-- MySQL dump 10.19  Distrib 10.3.39-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: mariadb
-- ------------------------------------------------------
-- Server version	10.3.39-MariaDB-0ubuntu0.20.04.2

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
-- Table structure for table `Booking`
--

DROP TABLE IF EXISTS `Booking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Booking` (
  `BookingID` varchar(40) NOT NULL,
  `UserID` varchar(40) NOT NULL,
  `ShowtimeID` varchar(40) NOT NULL,
  `TotalTickets` int(11) NOT NULL,
  `TotalPrice` decimal(10,2) NOT NULL,
  `BookingDate` date NOT NULL,
  `Status` tinyint(4) NOT NULL COMMENT '1: Confirmed, 0: Pending, -1: Cancelled',
  PRIMARY KEY (`BookingID`),
  KEY `User_Booking` (`UserID`),
  KEY `Showtime_Booking` (`ShowtimeID`),
  CONSTRAINT `Showtime_Booking` FOREIGN KEY (`ShowtimeID`) REFERENCES `Showtime` (`ShowtimeID`),
  CONSTRAINT `User_Booking` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Booking`
--

LOCK TABLES `Booking` WRITE;
/*!40000 ALTER TABLE `Booking` DISABLE KEYS */;
/*!40000 ALTER TABLE `Booking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Movie`
--

DROP TABLE IF EXISTS `Movie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Movie` (
  `MovieID` varchar(40) NOT NULL COMMENT 'Unique identifier for the movie',
  `Title` varchar(255) NOT NULL COMMENT 'Movie title',
  `Genre` varchar(100) NOT NULL COMMENT 'Movie genre (Action, Comedy, etc.)',
  `Duration` int(11) NOT NULL COMMENT 'Duration in minutes',
  `ReleaseDate` date NOT NULL COMMENT 'Movie release date',
  `Language` varchar(50) NOT NULL COMMENT 'Language of the movie',
  `Rating` decimal(10,0) NOT NULL COMMENT 'Movie rating (e.g., 7.5/10)',
  `Description` text NOT NULL COMMENT 'Brief description of the movie',
  PRIMARY KEY (`MovieID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Movie`
--

LOCK TABLES `Movie` WRITE;
/*!40000 ALTER TABLE `Movie` DISABLE KEYS */;
/*!40000 ALTER TABLE `Movie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Insert data into `Movie`
--
INSERT INTO `Movie` (`MovieID`, `Title`, `Genre`, `Duration`, `ReleaseDate`, `Language`, `Rating`, `Description`) VALUES
('movie001', 'Inception', 'Sci-Fi', 148, '2010-07-16', 'English', 9, 'A thief who steals corporate secrets through dream-sharing technology.'),
('movie002', 'The Godfather', 'Crime', 175, '1972-03-24', 'English', 10, 'The aging patriarch of an organized crime dynasty transfers control to his reluctant son.'),
('movie003', 'Parasite', 'Thriller', 132, '2019-05-30', 'Korean', 9, 'A poor family schemes to become employed by a wealthy family.'),
('movie004', 'The Dark Knight', 'Action', 152, '2008-07-18', 'English', 9, 'Batman faces the Joker, a criminal mastermind who wants to create chaos.'),
('movie005', 'Forrest Gump', 'Drama', 142, '1994-07-06', 'English', 9, 'The story of a man with a low IQ who achieves great things in life.'),
('movie006', 'Spirited Away', 'Animation', 125, '2001-07-20', 'Japanese', 10, 'A young girl becomes trapped in a mysterious world of spirits.'),
('movie007', 'The Avengers', 'Superhero', 143, '2012-05-04', 'English', 8, 'Earth\'s mightiest heroes must come together to stop Loki.'),
('movie008', 'Titanic', 'Romance', 195, '1997-12-19', 'English', 9, 'A love story unfolds aboard the ill-fated RMS Titanic.'),
('movie009', 'Get Out', 'Horror', 104, '2017-02-24', 'English', 8, 'A young African-American visits his white girlfriend\'s family estate.'),
('movie010', 'Coco', 'Family', 105, '2017-11-22', 'English', 9, 'A young boy embarks on a journey to the Land of the Dead to discover his family\'s history.');

--
-- Table structure for table `Payment`
--

DROP TABLE IF EXISTS `Payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Payment` (
  `PaymentID` varchar(40) NOT NULL,
  `BookingID` varchar(40) NOT NULL,
  `PaymentMethod` varchar(20) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `PaymentStatus` tinyint(4) NOT NULL,
  `PaymentDate` datetime NOT NULL,
  PRIMARY KEY (`PaymentID`),
  KEY `Booking_Payment` (`BookingID`),
  CONSTRAINT `Booking_Payment` FOREIGN KEY (`BookingID`) REFERENCES `Booking` (`BookingID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Payment`
--

LOCK TABLES `Payment` WRITE;
/*!40000 ALTER TABLE `Payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `Payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Review`
--

DROP TABLE IF EXISTS `Review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Review` (
  `ReviewID` varchar(40) NOT NULL,
  `UserID` varchar(40) NOT NULL,
  `MovieID` varchar(40) NOT NULL,
  `Rating` int(11) NOT NULL,
  `Comment` text DEFAULT NULL,
  `ReviewDate` date NOT NULL,
  PRIMARY KEY (`ReviewID`),
  KEY `User_Review` (`UserID`),
  KEY `Movie_Review` (`MovieID`),
  CONSTRAINT `Movie_Review` FOREIGN KEY (`MovieID`) REFERENCES `Movie` (`MovieID`),
  CONSTRAINT `User_Review` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Review`
--

LOCK TABLES `Review` WRITE;
/*!40000 ALTER TABLE `Review` DISABLE KEYS */;
/*!40000 ALTER TABLE `Review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Seat`
--

DROP TABLE IF EXISTS `Seat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Seat` (
  `SeatID` varchar(40) NOT NULL,
  `TheaterID` varchar(40) NOT NULL,
  `SeatNumber` int(11) NOT NULL,
  `SeatType` varchar(20) NOT NULL,
  `IsBooked` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Available, 1: Booked',
  PRIMARY KEY (`SeatID`),
  KEY `Theater_Seat` (`TheaterID`),
  CONSTRAINT `Theater_Seat` FOREIGN KEY (`TheaterID`) REFERENCES `Theater` (`TheaterID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

-- Ensure the IsBooked column exists in the Seat table
ALTER TABLE `Seat`
ADD COLUMN IF NOT EXISTS `IsBooked` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0: Available, 1: Booked';

--
-- Dumping data for table `Seat`
--

LOCK TABLES `Seat` WRITE;
/*!40000 ALTER TABLE `Seat` DISABLE KEYS */;
/*!40000 ALTER TABLE `Seat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Seat_Booking`
--

DROP TABLE IF EXISTS `Seat_Booking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Seat_Booking` (
  `SeatID` varchar(40) NOT NULL,
  `BookingID` varchar(40) NOT NULL,
  `BookedBy` varchar(40) DEFAULT NULL COMMENT 'UserID of the person who booked the seat',
  PRIMARY KEY (`SeatID`),
  KEY `Booking_Seat_Booking` (`BookingID`),
  CONSTRAINT `Booking_Seat_Booking` FOREIGN KEY (`BookingID`) REFERENCES `Booking` (`BookingID`),
  CONSTRAINT `Seat_Seat_Booking` FOREIGN KEY (`SeatID`) REFERENCES `Seat` (`SeatID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Ensure no double booking for the same seat and showtime
ALTER TABLE `Seat_Booking` ADD UNIQUE (`SeatID`, `BookingID`);

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Seat_Booking`
--

LOCK TABLES `Seat_Booking` WRITE;
/*!40000 ALTER TABLE `Seat_Booking` DISABLE KEYS */;
/*!40000 ALTER TABLE `Seat_Booking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Showtime`
--

DROP TABLE IF EXISTS `Showtime`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Showtime` (
  `ShowtimeID` varchar(40) NOT NULL COMMENT 'Unique identifier for the showtime',
  `MovieID` varchar(40) NOT NULL COMMENT 'The movie being shown',
  `TheaterID` varchar(40) NOT NULL COMMENT 'The theater where the movie is played',
  `ShowDate` date NOT NULL COMMENT 'Date of the show',
  `StartTime` time NOT NULL COMMENT 'Start time of the movie',
  `EndTime` time NOT NULL COMMENT 'End time of the movie',
  `AvailableSeats` int(11) NOT NULL COMMENT 'Number of available seats for the show',
  PRIMARY KEY (`ShowtimeID`),
  KEY `Movie_Showtime` (`MovieID`),
  KEY `Theater_Showtime` (`TheaterID`),
  CONSTRAINT `Movie_Showtime` FOREIGN KEY (`MovieID`) REFERENCES `Movie` (`MovieID`),
  CONSTRAINT `Theater_Showtime` FOREIGN KEY (`TheaterID`) REFERENCES `Theater` (`TheaterID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Showtime`
--

LOCK TABLES `Showtime` WRITE;
/*!40000 ALTER TABLE `Showtime` DISABLE KEYS */;
/*!40000 ALTER TABLE `Showtime` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Theater`
--

DROP TABLE IF EXISTS `Theater`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Theater` (
  `TheaterID` varchar(40) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Location` varchar(255) NOT NULL,
  `City` varchar(100) NOT NULL COMMENT 'City where the theater is located',
  `TotalSeats` int(11) NOT NULL,
  PRIMARY KEY (`TheaterID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Theater`
--

LOCK TABLES `Theater` WRITE;
/*!40000 ALTER TABLE `Theater` DISABLE KEYS */;
/*!40000 ALTER TABLE `Theater` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `UserID` varchar(40) NOT NULL COMMENT 'Unique identifier for the user',
  `Name` varchar(100) NOT NULL COMMENT 'Full name of the user',
  `Email` varchar(100) NOT NULL COMMENT 'User''s email for login',
  `Password` varchar(255) NOT NULL COMMENT 'Encrypted password',
  `Phone` varchar(15) NOT NULL COMMENT 'Contact number',
  `Role` ENUM('Admin', 'Customer') NOT NULL DEFAULT 'Customer' COMMENT 'User role (Admin, Customer)',
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

-- Ensure the User table has unique constraints on UserID and Email
ALTER TABLE `User`
ADD UNIQUE (`UserID`),
ADD UNIQUE (`Email`);

-- Update the User table to ensure Role is an ENUM type
ALTER TABLE `User`
MODIFY COLUMN `Role` ENUM('Admin', 'Customer') NOT NULL DEFAULT 'Customer';

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Insert default user 'janblaire'
--
INSERT INTO `User` (`UserID`, `Name`, `Email`, `Password`, `Phone`, `Role`) 
VALUES ('janblaire', 'Jan Blaire', 'janblaire@example.com', '$2y$10$eImiTXuWVxfM37uY4JANjQe5xK4Zp1s9aGzFz9eV1K6Q9z9eFzFzG', '1234567890', 'Admin');

-- Insert default admin user 'admin001'
INSERT INTO `User` (`UserID`, `Name`, `Email`, `Password`, `Phone`, `Role`) 
VALUES ('admin001', 'Admin User', 'admin@example.com', '$2y$10$eImiTXuWVxfM37uY4JANjQe5xK4Zp1s9aGzFz9eV1K6Q9z9eFzFzG', '1234567890', 'Admin');

-- Insert default admin account
INSERT INTO `User` (`UserID`, `Name`, `Email`, `Password`, `Phone`, `Role`) 
VALUES ('admin', 'Administrator', 'admin@example.com', '$2y$10$eImiTXuWVxfM37uY4JANjQe5xK4Zp1s9aGzFz9eV1K6Q9z9eFzFzG', '1234567890', 'Admin');

-- Insert sample data for theaters
INSERT INTO `Theater` (`TheaterID`, `Name`, `Location`, `City`, `TotalSeats`) VALUES
('theater001', 'Cinema One', '123 Main St', 'New York', 200),
('theater002', 'Cinema Two', '456 Elm St', 'Los Angeles', 150);

-- Insert sample data for showtimes
INSERT INTO `Showtime` (`ShowtimeID`, `MovieID`, `TheaterID`, `ShowDate`, `StartTime`, `EndTime`, `AvailableSeats`) VALUES
('showtime001', 'movie001', 'theater001', '2025-03-30', '18:00:00', '20:30:00', 200),
('showtime002', 'movie002', 'theater002', '2025-03-30', '19:00:00', '21:45:00', 150);

-- Insert sample data for seats
INSERT INTO `Seat` (`SeatID`, `TheaterID`, `SeatNumber`, `SeatType`, `IsBooked`) VALUES
('seat001', 'theater001', 1, 'Regular', 0),
('seat002', 'theater001', 2, 'Regular', 0),
('seat003', 'theater002', 1, 'VIP', 0),
('seat004', 'theater002', 2, 'VIP', 0);

-- Add a table to notify admins of new bookings
CREATE TABLE IF NOT EXISTS `AdminNotifications` (
  `NotificationID` INT AUTO_INCREMENT PRIMARY KEY,
  `BookingID` VARCHAR(40) NOT NULL,
  `Message` TEXT NOT NULL,
  `IsRead` TINYINT(1) NOT NULL DEFAULT 0,
  `CreatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`BookingID`) REFERENCES `Booking`(`BookingID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-25 11:54:15
