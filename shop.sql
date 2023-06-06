-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2023 at 05:15 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoryID` smallint(6) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Parent` smallint(6) NOT NULL,
  `Ordering` int(11) NOT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT 0,
  `AllowComment` tinyint(4) NOT NULL DEFAULT 0,
  `AllowAds` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `Name`, `Description`, `Parent`, `Ordering`, `Visibility`, `AllowComment`, `AllowAds`) VALUES
(2, 'computers', 'this category is the most common category', 0, 2, 0, 0, 0),
(3, 'clothes', 'we make a big sale in this category', 0, 3, 1, 0, 0),
(4, 'Games', 'life in our world', 0, 4, 0, 0, 0),
(5, 'APPLE', 'hight performance', 1, 4, 0, 0, 0),
(6, 'SAMSUNG', 'Live In Your Device', 1, 9, 0, 0, 0),
(9, 'Iphone 14 Pro Max', 'we make a big sale in this category', 7, 1, 0, 0, 0),
(11, 'Phone', 'we make a big sale in this category', 0, 1, 1, 1, 1),
(12, 'books', 'we make abig sale in this category', 0, 5, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `ID` int(11) NOT NULL,
  `Comment` varchar(255) NOT NULL,
  `Status` tinyint(4) NOT NULL,
  `Date` date NOT NULL,
  `Item_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`ID`, `Comment`, `Status`, `Date`, `Item_ID`, `User_ID`) VALUES
(2, 'this prudect so bad', 1, '2022-11-30', 3, 2),
(4, 'this product is very good', 0, '2022-11-30', 3, 3),
(20, '                         this is good matrial                       ', 0, '2022-12-06', 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `ItemID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` int(11) NOT NULL,
  `Date` date NOT NULL,
  `CountryMade` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Rating` smallint(6) NOT NULL,
  `Cat_ID` smallint(6) NOT NULL,
  `Member_ID` int(11) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`ItemID`, `Name`, `Description`, `Price`, `Date`, `CountryMade`, `Image`, `Status`, `Rating`, `Cat_ID`, `Member_ID`, `Approve`) VALUES
(2, 'FIFA 23', 'play with your friend', 22, '2022-11-30', 'United States', '', '1', 0, 4, 3, 0),
(3, 'PES 22', 'PRO EVSLUTION SOCCER', 23, '2022-11-30', 'Iceland', '', '2', 0, 4, 4, 0),
(5, 'Air Jorden', 'easy to walk and run', 90, '2022-12-02', 'United States', '', '2', 0, 3, 2, 0),
(6, 'Vest', 'vest waterproof from Groowi', 110, '2022-12-02', 'Egypt', '', '2', 0, 3, 2, 1),
(7, 'T-shirt', '100% cotton', 32, '2022-12-05', 'Egy', '', '2', 0, 3, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `GroupID` tinyint(4) NOT NULL DEFAULT 0,
  `TrustStatus` tinyint(4) NOT NULL DEFAULT 0,
  `RegStatus` tinyint(4) NOT NULL DEFAULT 0,
  `Date` date NOT NULL,
  `Avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `UserName`, `Password`, `Email`, `FullName`, `GroupID`, `TrustStatus`, `RegStatus`, `Date`, `Avatar`) VALUES
(2, 'omar', '601f1889667efaebb33b8c12572835da3f027f78', 'omar@gmail.com', 'omar mohsen', 1, 0, 1, '2022-11-28', 'pic1.jpg'),
(3, 'mohsen', '601f1889667efaebb33b8c12572835da3f027f78', 'mohsen@gmail.com', 'mohsen ahmed', 1, 0, 1, '2022-11-29', ''),
(4, 'khaled', '601f1889667efaebb33b8c12572835da3f027f78', 'khaled@gmail.com', 'khaled mohamed', 0, 0, 1, '2022-11-30', ''),
(5, 'saleh', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', 'saleh@gmail.com', '', 0, 0, 0, '2022-12-01', ''),
(6, 'nour', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', 'nourkhaled@gmail.com', 'nour khaled', 0, 0, 0, '2022-12-07', ''),
(7, 'ahmed', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', 'ahmed@gmail.com', 'abdelghany', 0, 0, 1, '2022-12-07', ''),
(8, 'yasser', '601f1889667efaebb33b8c12572835da3f027f78', 'yaseer@gmail.com', 'yasser gamal', 0, 0, 1, '2023-01-07', '395428_macbook.jpg'),
(9, 'osama', '601f1889667efaebb33b8c12572835da3f027f78', 'yaseer@gmail.com', 'yasser gamal', 0, 0, 1, '2023-01-07', '62536_forest_fog_deer_129931_1280x720.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Item_ID` (`Item_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `Cat_ID` (`Cat_ID`),
  ADD KEY `Member_ID` (`Member_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`Item_ID`) REFERENCES `items` (`ItemID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`CategoryID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
