-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2022 at 03:22 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fyp`
--

-- --------------------------------------------------------

--
-- Table structure for table `reminder`
--

CREATE TABLE `reminder` (
  `Reminder_ID` varchar(50) NOT NULL,
  `Reminder_Period` varchar(50) NOT NULL,
  `User_ID` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `User_ID` varchar(50) NOT NULL,
  `User_Email` varchar(100) DEFAULT NULL,
  `User_Password` varchar(200) DEFAULT NULL,
  `User_Master_Password` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `vault`
--

CREATE TABLE `vault` (
  `Vault_ID` varchar(50) NOT NULL,
  `Vault_URL` varchar(200) NOT NULL,
  `Vault_Name` varchar(200) NOT NULL,
  `Vault_Email` varchar(100) NOT NULL,
  `Vault_Password` varchar(200) NOT NULL,
  `Vault_Date_Create` varchar(200) NOT NULL,
  `User_ID` varchar(50) NOT NULL,
  `Website_ID` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `website`
--

CREATE TABLE `website` (
  `Website_ID` varchar(100) NOT NULL,
  `Website_URL` varchar(300) NOT NULL,
  `Website_Path` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `website`
--

INSERT INTO `website` (`Website_ID`, `Website_URL`, `Website_Path`) VALUES
('W63837951ce5e8', 'https://web.tarc.edu.my/portal/login.jsp', '/assets/img/tarumt.png'),
('W6385832ad1a23', 'https://www.linkedin.com/home', '/assets/img/linkedin.png'),
('W638eb48673b2b', 'https://github.com/login', '/assets/img/github.png'),
('W638eb4f72ea73', 'https://www.reddit.com/login/', '/assets/img/reddit.png'),
('W638eb55e7bbb7', 'https://discord.com/login', '/assets/img/discord.png'),
('W638eb817235f4', 'https://www.instagram.com/', '/assets/img/default.png'),
('W638eb8b1b93f2', 'https://www.facebook.com/', '/assets/img/facebook.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reminder`
--
ALTER TABLE `reminder`
  ADD PRIMARY KEY (`Reminder_ID`),
  ADD KEY `Reminder_ID` (`Reminder_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`User_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `vault`
--
ALTER TABLE `vault`
  ADD PRIMARY KEY (`Vault_ID`),
  ADD KEY `Vault_ID` (`Vault_ID`),
  ADD KEY `User_ID` (`User_ID`),
  ADD KEY `Website_ID` (`Website_ID`);

--
-- Indexes for table `website`
--
ALTER TABLE `website`
  ADD PRIMARY KEY (`Website_ID`),
  ADD KEY `Website_ID` (`Website_ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reminder`
--
ALTER TABLE `reminder`
  ADD CONSTRAINT `User__ID` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`);

--
-- Constraints for table `vault`
--
ALTER TABLE `vault`
  ADD CONSTRAINT `User_ID` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`),
  ADD CONSTRAINT `Website_ID` FOREIGN KEY (`Website_ID`) REFERENCES `website` (`Website_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
