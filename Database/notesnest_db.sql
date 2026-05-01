-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2025 at 11:50 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `notesnest_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_comment`
--

CREATE TABLE `tbl_comment` (
  `user_id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL,
  `comment` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notes`
--

CREATE TABLE `tbl_notes` (
  `note_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(150) NOT NULL,
  `file_url` varchar(150) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `isApproved` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notes`
--

INSERT INTO `tbl_notes` (`note_id`, `user_id`, `title`, `description`, `file_url`, `subject_id`, `isApproved`, `createdAt`) VALUES
(35, 19, 'Home Science', 'Home Science Expert', 'uploads/692753c592234_1764185029.pdf', 4, 1, '2025-11-27 00:53:49'),
(36, 19, 'World-War-1', 'ww1 full book', 'uploads/69275407c8076_1764185095.pdf', 9, 1, '2025-11-27 00:54:55'),
(37, 20, 'Web Development', 'Ths is the best notes for learning Web Development', 'uploads/6927d5813b178_1764218241.pdf', 1, 1, '2025-11-27 10:07:21'),
(38, 20, 'Visual Arts', 'Visual Arts', 'uploads/6927d5d89640e_1764218328.pdf', 3, 1, '2025-11-27 10:08:48'),
(40, 24, 'MacroEconomics', 'Macro Economics', 'uploads/6927d6f70e31a_1764218615.pdf', 2, 1, '2025-11-27 10:13:35'),
(41, 22, 'Beginners-Piano-Book', 'Piano-Book best beginner friendly Music learning Book', 'uploads/6927d773ae61c_1764218739.pdf', 16, 1, '2025-11-27 10:15:39'),
(43, 23, 'Political Sociology', 'political sociology', 'uploads/6927d87b9fa0c_1764219003.pdf', 8, 1, '2025-11-27 10:20:03'),
(44, 23, 'Trignometry', 'Trignometery Basics', 'uploads/6927d8b3bc3c3_1764219059.pdf', 10, 1, '2025-11-27 10:20:59'),
(45, 24, 'Hindi Poetry', 'Poetry', 'uploads/6927d9822c02b_1764219266.pdf', 28, 1, '2025-11-27 10:24:26'),
(47, 21, 'How to Get Full Control of Your Mind', 'Mind Control', 'uploads/6927da0ba5db9_1764219403.pdf', 5, 1, '2025-11-27 10:26:43'),
(48, 24, 'science unit 1', 'science', 'uploads/6927eedbb6158_1764224731.pdf', 4, 1, '2025-11-27 11:55:31'),
(49, 21, 'How to be a Good Politician', 'politician', 'uploads/692d8a699911f_sociology-strategic-document.pdf', 7, 1, '2025-12-01 18:00:33'),
(50, 23, 'Quantum computer ', 'Quantum computer basic ', 'uploads/693250c830d95_Python Interview Prep!.pdf', 1, 0, '2025-12-05 08:56:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_note_tags`
--

CREATE TABLE `tbl_note_tags` (
  `note_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_note_tags`
--

INSERT INTO `tbl_note_tags` (`note_id`, `tag_id`) VALUES
(35, 19),
(36, 20),
(37, 21),
(37, 22),
(38, 23),
(38, 24),
(40, 26),
(41, 27),
(41, 28),
(44, 32),
(45, 33),
(47, 35),
(47, 36),
(48, 30),
(50, 37);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rating`
--

CREATE TABLE `tbl_rating` (
  `user_id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_rating`
--

INSERT INTO `tbl_rating` (`user_id`, `note_id`, `rating`) VALUES
(19, 35, 3),
(19, 40, 2),
(19, 43, 3),
(19, 44, 4),
(19, 47, 5),
(21, 40, 4),
(21, 41, 4),
(21, 48, 4),
(23, 45, 5),
(24, 44, 2),
(24, 45, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_role`
--

CREATE TABLE `tbl_role` (
  `role_id` tinyint(4) NOT NULL,
  `role` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_role`
--

INSERT INTO `tbl_role` (`role_id`, `role`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subject`
--

CREATE TABLE `tbl_subject` (
  `subject_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `img` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_subject`
--

INSERT INTO `tbl_subject` (`subject_id`, `name`, `img`) VALUES
(1, 'Computer Science', 'Image/img/compSci.png'),
(2, 'Commerce', 'Image/img/notes.png'),
(3, 'Arts', 'Image/img/philisophhy.png'),
(4, 'Science', 'Image/img/Science.png'),
(5, 'Physcology', 'Image/img/physcology.png'),
(6, 'Literature', 'Image/img/literature.png'),
(7, 'Politics', 'Image/img/politics.png'),
(8, 'Sociology', 'Image/img/sociology.png'),
(9, 'History', 'Image/img/history.png'),
(10, 'Mathematics', 'Image/img/maths.png'),
(16, 'Music', 'Image/img/biology.png'),
(28, 'Language', 'Image/img/languages.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subscription`
--

CREATE TABLE `tbl_subscription` (
  `user_id_from` int(11) NOT NULL,
  `user_id_to` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tags`
--

CREATE TABLE `tbl_tags` (
  `tag_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_tags`
--

INSERT INTO `tbl_tags` (`tag_id`, `name`) VALUES
(19, 'home'),
(20, 'ww1'),
(21, 'web'),
(22, 'development'),
(23, 'arts'),
(24, 'visual'),
(25, 'forensic'),
(26, 'macro'),
(27, 'music'),
(28, 'musiclover'),
(29, 'computer'),
(30, 'science'),
(31, 'llm'),
(32, 'trigo'),
(33, 'poetry'),
(34, 'mla'),
(35, 'mind'),
(36, 'control'),
(37, 'Quantum computer');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `phone_no` bigint(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role_id` tinyint(4) NOT NULL,
  `isBlocked` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `name`, `user_name`, `phone_no`, `email`, `password`, `role_id`, `isBlocked`, `createdAt`) VALUES
(1, 'admin', 'admin', 7865867564, 'admin@gmail.com', '$2y$10$h0N1GVOeeGCZDlIDje.gVeDuLVPhzwz19FS9Ee0RJFJ1qz.xTSgtK', 1, 0, '2025-08-03 00:00:00'),
(19, 'Jhon Deo', 'Deo', 8976756453, 'deo@gmail.com', '$2y$10$sdumDFSswfgIId3nQB9Vou.qmaYrhOD69orB2B5hA7W87SM56482K', 2, 0, '2025-11-27 00:51:37'),
(20, 'Harry Potter', 'Harry', 6785645678, 'potter@gmail.com', '$2y$10$X9VAkFbGEPAtRlzj0SiNUOQ6bUHW6G0JDEaT2WykTqrA9TooEo8B6', 2, 0, '2025-10-12 09:57:01'),
(21, 'Alex Carry', 'Alex Carry', 5675654321, 'alex@gmail.com', '$2y$10$Tb6R4XoV4WiBErsZXP0RmehsOexnpmgP5C7.ogbqrtaGRidqUNGgq', 2, 0, '2025-11-27 09:57:54'),
(22, 'Temba Babuma', 'Temba Babuma', 9878967567, 'temba@gmail.com', '$2y$10$7rM2FKkx4IHe09BW7Q7ywegIQ0tJBJ.7GIWEuDlNezDJJPxZvemde', 2, 1, '2025-11-07 09:58:54'),
(23, 'Mitaliraj Pandit', 'Mitali', 5644345545, 'mitaliraj@gmail.com', '$2y$10$NXJdqUrDupzjE.0mnJZ1DetD/ynwteDCwfdlaVdppsQetvQQj0Nrq', 2, 0, '2025-11-27 10:00:30'),
(24, 'Joe Root', 'Jeo', 8899774543, 'joe@gmail.com', '$2y$10$ADECTuCh1eUcMdaHmxLdkuY5yvxpmjReDQGLa5ORAOu.s.3WVyFim', 2, 0, '2025-11-27 10:01:56'),
(25, 'Mark Wood', 'Mark Wood', 7787656767, 'mark@gmail.com', '$2y$10$I.8I9z1dEF8Xf6AV6gbQju9jl1DODh0MFPZpZ/ocqV3i9O5/q/vs2', 2, 0, '2025-12-05 08:28:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_comment`
--
ALTER TABLE `tbl_comment`
  ADD PRIMARY KEY (`user_id`,`note_id`),
  ADD KEY `note_comment_constraint` (`note_id`);

--
-- Indexes for table `tbl_notes`
--
ALTER TABLE `tbl_notes`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `subject_constraint` (`subject_id`);

--
-- Indexes for table `tbl_note_tags`
--
ALTER TABLE `tbl_note_tags`
  ADD PRIMARY KEY (`note_id`,`tag_id`),
  ADD KEY `tad_id_constraint` (`tag_id`);

--
-- Indexes for table `tbl_rating`
--
ALTER TABLE `tbl_rating`
  ADD PRIMARY KEY (`user_id`,`note_id`),
  ADD KEY `note_id_constraint` (`note_id`);

--
-- Indexes for table `tbl_role`
--
ALTER TABLE `tbl_role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `tbl_subject`
--
ALTER TABLE `tbl_subject`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `tbl_subscription`
--
ALTER TABLE `tbl_subscription`
  ADD PRIMARY KEY (`user_id_from`,`user_id_to`),
  ADD KEY `user_subscription_constrain2` (`user_id_to`);

--
-- Indexes for table `tbl_tags`
--
ALTER TABLE `tbl_tags`
  ADD PRIMARY KEY (`tag_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_constraint` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_notes`
--
ALTER TABLE `tbl_notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `tbl_role`
--
ALTER TABLE `tbl_role`
  MODIFY `role_id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_subject`
--
ALTER TABLE `tbl_subject`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `tbl_tags`
--
ALTER TABLE `tbl_tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_comment`
--
ALTER TABLE `tbl_comment`
  ADD CONSTRAINT `note_comment_constraint` FOREIGN KEY (`note_id`) REFERENCES `tbl_notes` (`note_id`),
  ADD CONSTRAINT `user_comment_constraint` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`);

--
-- Constraints for table `tbl_notes`
--
ALTER TABLE `tbl_notes`
  ADD CONSTRAINT `subject_constraint` FOREIGN KEY (`subject_id`) REFERENCES `tbl_subject` (`subject_id`);

--
-- Constraints for table `tbl_note_tags`
--
ALTER TABLE `tbl_note_tags`
  ADD CONSTRAINT `notes_constraint` FOREIGN KEY (`note_id`) REFERENCES `tbl_notes` (`note_id`),
  ADD CONSTRAINT `tad_id_constraint` FOREIGN KEY (`tag_id`) REFERENCES `tbl_tags` (`tag_id`);

--
-- Constraints for table `tbl_rating`
--
ALTER TABLE `tbl_rating`
  ADD CONSTRAINT `note_id_constraint` FOREIGN KEY (`note_id`) REFERENCES `tbl_notes` (`note_id`),
  ADD CONSTRAINT `user_id_constraint` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`);

--
-- Constraints for table `tbl_subscription`
--
ALTER TABLE `tbl_subscription`
  ADD CONSTRAINT `user_subscription_constrain2` FOREIGN KEY (`user_id_to`) REFERENCES `tbl_user` (`user_id`),
  ADD CONSTRAINT `user_subscription_constraint` FOREIGN KEY (`user_id_from`) REFERENCES `tbl_user` (`user_id`);

--
-- Constraints for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD CONSTRAINT `role_constraint` FOREIGN KEY (`role_id`) REFERENCES `tbl_role` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
