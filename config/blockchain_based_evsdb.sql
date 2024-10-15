-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2023 at 11:23 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blockchain_based_evsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidates_table`
--

CREATE TABLE `candidates_table` (
  `candidate_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `section` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `id_number` varchar(20) NOT NULL,
  `position` varchar(20) NOT NULL,
  `platform` text NOT NULL,
  `candidate_image_path` varchar(255) NOT NULL,
  `front_id_image_path` varchar(255) NOT NULL,
  `back_id_image_path` varchar(255) NOT NULL,
  `isData_verified` int(11) NOT NULL,
  `isEmail_verified` int(11) NOT NULL,
  `election_id` int(11) NOT NULL,
  `registration_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `candidates_table`
--

INSERT INTO `candidates_table` (`candidate_id`, `name`, `section`, `email`, `id_number`, `position`, `platform`, `candidate_image_path`, `front_id_image_path`, `back_id_image_path`, `isData_verified`, `isEmail_verified`, `election_id`, `registration_timestamp`) VALUES
(2, 'MIRANDA, MICHAEL A.', 'BSIT-1C', 'miranda_michael@plpasig.edu.ph', '20-00368', '34', '<h1>Magpayaman</h1>', 'http://localhost/Blockchain-Based_EVS/uploads/cropped_image_654eea9a8401f.jpg', 'http://localhost/Blockchain-Based_EVS/uploads/654eea9a85532_ID.jpg', 'http://localhost/Blockchain-Based_EVS/uploads/654eea9a85739_id-back.jpg', 1, 1, 41, '2023-11-11 02:44:44'),
(3, 'WATSON, LILY M.', 'BSIT-4C', 'watson_lily@plpasig.edu.ph', '20-00361', '44', '<h1>Magpayaman</h1>', 'http://localhost/Blockchain-Based_EVS/uploads/leni.jpg', 'http://localhost/Blockchain-Based_EVS/uploads/654eea9a85532_ID.jpg', 'http://localhost/Blockchain-Based_EVS/uploads/654eea9a85739_id-back.jpg', 1, 1, 41, '2023-11-14 17:00:10'),
(6, 'DUTERTE, SARA R.', 'BSIT-4C', 'michaelatejeramiranda@gmail.com', '20-00360', '44', '<h1>Hello</h1>', 'http://localhost/Blockchain-Based_EVS/uploads/sara.jpg', 'http://localhost/Blockchain-Based_EVS/uploads/654eea9a85532_ID.jpg', 'http://localhost/Blockchain-Based_EVS/uploads/654eea9a85739_id-back.jpg', 0, 1, 41, '2023-11-20 03:38:08');

-- --------------------------------------------------------

--
-- Table structure for table `candidate_registration`
--

CREATE TABLE `candidate_registration` (
  `candidate_registration_id` int(11) NOT NULL,
  `election_code` varchar(255) NOT NULL,
  `candidate_regis_startdate` datetime NOT NULL,
  `candidate_regis_enddate` datetime NOT NULL,
  `candidate_regis_timezone` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `candidate_registration`
--

INSERT INTO `candidate_registration` (`candidate_registration_id`, `election_code`, `candidate_regis_startdate`, `candidate_regis_enddate`, `candidate_regis_timezone`) VALUES
(1, 'ldcusxehzm', '2023-11-06 11:40:00', '2023-11-15 11:40:00', 'Asia/Manila');

-- --------------------------------------------------------

--
-- Table structure for table `electiontable`
--

CREATE TABLE `electiontable` (
  `election_id` int(11) NOT NULL,
  `election_name` text NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `timezone` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `status` text NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `election_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `electiontable`
--

INSERT INTO `electiontable` (`election_id`, `election_name`, `start_date`, `end_date`, `timezone`, `date_created`, `status`, `created_by`, `election_code`) VALUES
(41, 'Com Soc Election 2023', '2023-11-24 05:00:00', '2023-11-25 15:51:00', 'Asia/Manila', '2023-07-28 01:09:45', 'building', 'michaelatejeramiranda015@gmail.com', 'ldcusxehzm'),
(44, 'Philippine Election 2028', '2023-10-18 16:45:00', '2023-10-19 16:45:00', 'Asia/Manila', '2023-10-18 16:46:06', 'building', 'michaelatejeramiranda015@gmail.com', 'aqfznbtijx');

-- --------------------------------------------------------

--
-- Table structure for table `positionstable`
--

CREATE TABLE `positionstable` (
  `position_id` int(11) NOT NULL,
  `position_desc` varchar(255) NOT NULL,
  `maximum_vote` int(11) NOT NULL,
  `maximum_candidate` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `election_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `positionstable`
--

INSERT INTO `positionstable` (`position_id`, `position_desc`, `maximum_vote`, `maximum_candidate`, `priority`, `election_id`) VALUES
(34, 'President', 1, 10, 1, 41),
(44, 'Vice President', 1, 5, 2, 41),
(45, 'Congressman', 1, 5, 3, 41);

-- --------------------------------------------------------

--
-- Table structure for table `usertable`
--

CREATE TABLE `usertable` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  `code` mediumint(50) NOT NULL,
  `status` text NOT NULL,
  `user_photo` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `usertable`
--

INSERT INTO `usertable` (`id`, `name`, `email`, `password`, `role`, `code`, `status`, `user_photo`) VALUES
(53, 'Michael A. Miranda', 'michaelatejeramiranda015@gmail.com', '$2y$10$OLKZsxD1HaJ2dzCKwyisr.GAei8InHhc8wmg7MuzQOtWD4zXIOZLa', 'election_creator', 190936, 'verified', 'profile.jpg'),
(65, 'Michael A. Miranda', 'miranda_michael@plpasig.edu.ph', '$2y$10$qSYn0Ej6Fgp5imXQ05uF2OVaWGiCQGuivpDH.PoPozUbycqEIAgNC', 'election_creator', 351671, 'notverified', 'profile.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `voters_table`
--

CREATE TABLE `voters_table` (
  `voter_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `section` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `id_number` varchar(20) NOT NULL,
  `front_id_image_path` varchar(255) NOT NULL,
  `back_id_image_path` varchar(255) NOT NULL,
  `voter_password` varchar(255) DEFAULT NULL,
  `isData_verified` int(11) NOT NULL DEFAULT 0,
  `isEmail_verified` int(11) NOT NULL DEFAULT 0,
  `election_id` int(11) NOT NULL,
  `registration_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `voters_table`
--

INSERT INTO `voters_table` (`voter_id`, `name`, `section`, `email`, `id_number`, `front_id_image_path`, `back_id_image_path`, `voter_password`, `isData_verified`, `isEmail_verified`, `election_id`, `registration_timestamp`) VALUES
(25, 'MARTINEZ, NEIL IVERSON D.', 'BSIT-4C', 'michaelatejeramiranda015@gmail.com', '20-00360', 'http://localhost/Blockchain-Based_EVS/uploads/6552b8019a87d_ID.jpg', 'http://localhost/Blockchain-Based_EVS/uploads/6552b8019ad48_id-back.jpg', '$2y$10$7IPQDv8WX8ExKRJm.fBLq.N71PeFZBJ1Ue2i12toN4wzu2ISSYfLi', 1, 1, 41, '2023-11-20 02:31:23'),
(26, 'MIRANDA, MICHAEL A.', 'BSIT-1C', 'miranda_michael@plpasig.edu.ph', '20-00368', 'http://localhost/Blockchain-Based_EVS/uploads/655c10b631e36_ID.jpg', 'http://localhost/Blockchain-Based_EVS/uploads/655c10b632a94_id-back.jpg', '$2y$10$ThC1y2re/dNxEAt4XEFcjeSBEACAbg2Z2jv4nehK4MFTfVF2XBm/G', 1, 1, 41, '2023-11-21 02:06:51');

-- --------------------------------------------------------

--
-- Table structure for table `voter_registration`
--

CREATE TABLE `voter_registration` (
  `voter_registration_id` int(11) NOT NULL,
  `election_code` varchar(255) NOT NULL,
  `voter_regis_startdate` datetime NOT NULL,
  `voter_regis_enddate` datetime NOT NULL,
  `voter_regis_timezone` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `voter_registration`
--

INSERT INTO `voter_registration` (`voter_registration_id`, `election_code`, `voter_regis_startdate`, `voter_regis_enddate`, `voter_regis_timezone`) VALUES
(9, 'ldcusxehzm', '2023-11-22 20:30:00', '2023-11-25 09:00:00', 'Asia/Manila');

-- --------------------------------------------------------

--
-- Table structure for table `votestable`
--

CREATE TABLE `votestable` (
  `vote_id` int(11) NOT NULL,
  `voter_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `votestable`
--

INSERT INTO `votestable` (`vote_id`, `voter_id`, `candidate_id`, `position_id`, `election_id`) VALUES
(428, 26, 0, 44, 41),
(427, 26, 2, 34, 41);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candidates_table`
--
ALTER TABLE `candidates_table`
  ADD PRIMARY KEY (`candidate_id`),
  ADD UNIQUE KEY `email` (`email`,`id_number`),
  ADD KEY `election_id` (`election_id`);

--
-- Indexes for table `candidate_registration`
--
ALTER TABLE `candidate_registration`
  ADD PRIMARY KEY (`candidate_registration_id`),
  ADD KEY `election_code` (`election_code`);

--
-- Indexes for table `electiontable`
--
ALTER TABLE `electiontable`
  ADD PRIMARY KEY (`election_id`),
  ADD UNIQUE KEY `election_code` (`election_code`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `positionstable`
--
ALTER TABLE `positionstable`
  ADD PRIMARY KEY (`position_id`),
  ADD KEY `election_id` (`election_id`);

--
-- Indexes for table `usertable`
--
ALTER TABLE `usertable`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `voters_table`
--
ALTER TABLE `voters_table`
  ADD PRIMARY KEY (`voter_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `id_number` (`id_number`),
  ADD KEY `election_id` (`election_id`);

--
-- Indexes for table `voter_registration`
--
ALTER TABLE `voter_registration`
  ADD PRIMARY KEY (`voter_registration_id`),
  ADD KEY `election_code` (`election_code`);

--
-- Indexes for table `votestable`
--
ALTER TABLE `votestable`
  ADD PRIMARY KEY (`vote_id`),
  ADD UNIQUE KEY `voter_userid` (`voter_id`,`candidate_id`,`position_id`,`election_id`),
  ADD KEY `voter_userid_2` (`voter_id`,`candidate_id`,`position_id`,`election_id`),
  ADD KEY `position_id` (`position_id`),
  ADD KEY `election_id` (`election_id`),
  ADD KEY `candidate_id` (`candidate_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidates_table`
--
ALTER TABLE `candidates_table`
  MODIFY `candidate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `candidate_registration`
--
ALTER TABLE `candidate_registration`
  MODIFY `candidate_registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `electiontable`
--
ALTER TABLE `electiontable`
  MODIFY `election_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `positionstable`
--
ALTER TABLE `positionstable`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `usertable`
--
ALTER TABLE `usertable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `voters_table`
--
ALTER TABLE `voters_table`
  MODIFY `voter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `voter_registration`
--
ALTER TABLE `voter_registration`
  MODIFY `voter_registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `votestable`
--
ALTER TABLE `votestable`
  MODIFY `vote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=429;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `candidate_registration`
--
ALTER TABLE `candidate_registration`
  ADD CONSTRAINT `candidate_registration_ibfk_1` FOREIGN KEY (`election_code`) REFERENCES `electiontable` (`election_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `electiontable`
--
ALTER TABLE `electiontable`
  ADD CONSTRAINT `electiontable_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `usertable` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `positionstable`
--
ALTER TABLE `positionstable`
  ADD CONSTRAINT `positionstable_ibfk_1` FOREIGN KEY (`election_id`) REFERENCES `electiontable` (`election_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `voters_table`
--
ALTER TABLE `voters_table`
  ADD CONSTRAINT `voters_table_ibfk_1` FOREIGN KEY (`election_id`) REFERENCES `electiontable` (`election_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `voter_registration`
--
ALTER TABLE `voter_registration`
  ADD CONSTRAINT `voter_registration_ibfk_1` FOREIGN KEY (`election_code`) REFERENCES `electiontable` (`election_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `votestable`
--
ALTER TABLE `votestable`
  ADD CONSTRAINT `votestable_ibfk_1` FOREIGN KEY (`position_id`) REFERENCES `positionstable` (`position_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `votestable_ibfk_4` FOREIGN KEY (`election_id`) REFERENCES `electiontable` (`election_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `votestable_ibfk_5` FOREIGN KEY (`voter_id`) REFERENCES `voters_table` (`voter_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
