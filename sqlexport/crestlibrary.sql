-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 04, 2023 at 11:57 PM
-- Server version: 5.7.24
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crestlibrary`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `book_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_title`, `author`, `price`, `image_path`, `book_id`, `status`) VALUES
('1000 Black Umbrellas', 'Amanda Chong', '20.99', '../styling/images/bookImgs/1000BlackUmbrellas.jpg', 1, 'Available'),
('Adland', 'James P. Othmer', '15.99', '../styling/images/bookImgs/adland.jpg', 2, 'Available'),
('Alena: A Novel', 'Rachel Pastan', '18.50', '../styling/images/bookImgs/alenaANovel.jpg', 3, 'Available'),
('Dead Astronauts', 'Jeff VanderMeer', '25.00', '../styling/images/bookImgs/deadAstronauts.jpg', 4, 'Available'),
('In the Year of the Long Division', 'Dawn Raffel', '12.75', '../styling/images/bookImgs/inTheYearOfTheLongDivision.jpg', 5, 'Available'),
('Witches of America', 'Alex Mar', '19.95', '../styling/images/bookImgs/witchesOfAmerica.jpg', 6, 'Available'),
('Lost Decades', 'Miyuki Miyabe', '16.99', '../styling/images/bookImgs/lostDecades.jpg', 7, 'Available'),
('Nature and Value', 'Ronald Hepburn', '21.50', '../styling/images/bookImgs/natureAndValue.jpeg', 8, 'Available'),
('The Psychopath Test', 'Jon Ronson', '14.25', '../styling/images/bookImgs/psychopathTest.jpg', 9, 'Available'),
('The Book of Strange New Things', 'Michel Faber', '22.50', '../styling/images/bookImgs/theBookOfStrangeNewThings.jpg', 10, 'Available'),
('The Essential Goethe', 'Johann Wolfgang von Goethe', '17.99', '../styling/images/bookImgs/theEssentialGoethe.jpg', 11, 'Available'),
('The Everlasting', 'Katy Simpson Smith', '20.50', '../styling/images/bookImgs/theEverlasting.jpg', 12, 'Available'),
('The Ghost Sequences', 'Alessandra Lynch', '16.25', '../styling/images/bookImgs/theGhostSequences.jpeg', 13, 'Available'),
('The Revolution of Little Girls', 'Blanche McCrary Boyd', '19.95', '../styling/images/bookImgs/theRevolutionOfLittleGirls.jpg', 14, 'Available'),
('Three Delays', 'Charlie Smith', '13.99', '../styling/images/bookImgs/threeDelays.jpg', 15, 'Available'),
('Time Travel', 'James Gleick', '23.50', '../styling/images/bookImgs/timeTravel.jpg', 16, 'Available'),
('TYLL', 'Daniel Kehlmann', '18.50', '../styling/images/bookImgs/TYLL.jpg', 17, 'Available'),
('Version Control', 'Dexter Palmer', '14.95', '../styling/images/bookImgs/versionControl.jpg', 18, 'Available'),
('Wall Street', 'Doug Henwood', '20.99', '../styling/images/bookImgs/wallStreet.jpg', 19, 'Available'),
('Who Will Run the Frog Hospital?', 'Lorrie Moore', '15.99', '../styling/images/bookImgs/whoWillRunTheFrogHospital.jpg', 20, 'Available'),
('Closing Time', 'Joe Queenan', '50.10', '../styling/images/bookImgs/closingTime.jpg', 21, 'Available'),
('Macbeth', 'William Shakespeare', '15.99', '../styling/images/bookImgs/macbeth.jpg', 22, 'Available'),
('Romeo and Juliet', 'William Shakespeare', '15.99', '../styling/images/bookImgs/romeoAndJuliet.jpg', 23, 'Available'),
('Othello', 'William Shakespeare', '15.99', '../styling/images/bookImgs/othello.jpg', 24, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `librarians`
--

CREATE TABLE `librarians` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'librarian'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `librarians`
--

INSERT INTO `librarians` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'matt', 'matt@example.com', '$2y$10$o3ZJTkvXsC7OoeyYso7BoOOoMp2JohZVNsmwRO5WrJHb1CFl5/gfG', 'librarian'),
(2, 'kyla', 'kyla@example.com', '$2y$10$Ilx96xG1DrZgKYZWkilp5e8X/49syayfbYbREEhVVzrpc0ul77rL.', 'librarian');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `name`, `email`, `password`, `role`) VALUES
(7, 'matthew', 'matthew@example.com', '$2y$10$h/PTGw4OGtWDsUv.957K4.PSASQYJbpzKYocLs.5XlxwaMVr4mBbe', 'member');

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `order_number` int(11) NOT NULL,
  `order_date` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`order_number`, `order_date`, `user_id`, `book_id`, `price`, `end_date`) VALUES
(48, '2023-08-04 23:48:14', 7, 1, '20.99', '2023-08-18'),
(49, '2023-08-04 23:48:14', 7, 2, '15.99', '2023-08-18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `librarians`
--
ALTER TABLE `librarians`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`order_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `librarians`
--
ALTER TABLE `librarians`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `order_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `members` (`id`),
  ADD CONSTRAINT `rentals_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
