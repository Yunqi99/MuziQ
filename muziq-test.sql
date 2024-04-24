-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2024 at 10:40 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `muziq-test`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `AdminID` int(11) NOT NULL,
  `AdminGeneratedID` varchar(50) NOT NULL,
  `AdminName` varchar(30) NOT NULL,
  `AdminPosition` varchar(30) NOT NULL,
  `AdminImg` varchar(255) NOT NULL,
  `AdminEmail` varchar(30) NOT NULL,
  `AdminPassword` varchar(50) NOT NULL,
  `Trn_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`AdminID`, `AdminGeneratedID`, `AdminName`, `AdminPosition`, `AdminImg`, `AdminEmail`, `AdminPassword`, `Trn_date`) VALUES
(1, 'AD00001', 'Admin Aly', 'Manager', 'vecteezy_ai-generated-music-notes-flying-behind-a-black-background_35716190.jpg', 'yunqi.t@ypccollege.edu.my', 'Thanyunqi123#', '2024-04-15 12:04:29'),
(3, 'AD00002', 'Admin Jasmine', 'Manager', '29.jpg', 'Jasmine456@gmail.com', 'Jas12345%', '0000-00-00 00:00:00'),
(12, 'AD00006', 'Admin Jiaxi', 'Staff', '11.jpg', 'jiaxi.t@ypccollege.edu.my', 'Jiaxi123#', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `FAQID` int(11) NOT NULL,
  `Question` varchar(255) NOT NULL,
  `Answer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`FAQID`, `Question`, `Answer`) VALUES
(1, 'How do I play music on the web player?', 'To play music, simply click on the play button of the track you wish to listen to. You can find the play button on the track\'s cover image.'),
(2, 'How can I create a playlist on the music player?', 'Navigate to the \"Create Playlist\" option in the menu, give your playlist a name, and then start adding your favorite tracks. '),
(3, 'How can I adjust the volume on the web player?', 'You can adjust the volume by using the volume slider located in the player controls. Simply drag the slider to increase or decrease the volume.'),
(4, 'Can I skip tracks on the web player?', 'Yes, you can skip tracks by clicking on the next track button in the player controls. You can also go back to the previous track by clicking on the previous track button.'),
(5, 'How can I adjust the volume on the web player?', 'You can adjust the volume by using the volume slider located in the player controls. Simply drag the slider to increase or decrease the volume.'),
(6, 'Is there a repeat feature on the web player?', 'Yes, you can enable repeat mode to continuously loop through your playlist. Look for the repeat button in the bottom track playback control.'),
(7, 'How do I search for specific tracks or artists on the web player?', 'You can search for specific tracks or artists by using the search bar located in top navigation bar. Simply type in the name of the track or artist you\'re looking for and press enter to see the search results.'),
(8, 'Do I need to create an account to use the web player?', 'Yes, you must sign up or register an account to enjoy features provided on the web player.'),
(9, 'What format of audio files are supported?', 'MuziQ supports audio file formats, including MP3, WAV and AAC. If your file is in one of these formats, it should play without any issues.');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `FeedbackID` int(11) NOT NULL,
  `FeedbackTitle` varchar(255) NOT NULL,
  `FeedbackMsg` varchar(255) NOT NULL,
  `ReplyTitle` varchar(255) DEFAULT NULL,
  `ReplyAnswer` varchar(255) DEFAULT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`FeedbackID`, `FeedbackTitle`, `FeedbackMsg`, `ReplyTitle`, `ReplyAnswer`, `UserID`) VALUES
(1, 'Feedback on user satisfaction and experiences', 'Nice experiences in listening to tracks. I hope that more unique features will be updated soon.', 'Thank you for your feedback.', 'We appreciate your insights and are dedicated to continuously improving our service.', 11),
(3, 'Fix search function', 'Having issue in search. Hope it can be fixed ASAP.', 'Solved search issue', 'The issue is solved. Thank you for sending this feedback for us.', 14),
(25, 'Share my thoughts', 'I\'m happy to use MuziQ. Looking forward to new updates!', 'Thank you', 'Will update soon. Thank you!', 48);

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `GenreID` int(11) NOT NULL,
  `GenreName` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`GenreID`, `GenreName`) VALUES
(1, 'Classical'),
(2, 'C-Pop'),
(3, 'Electronic'),
(4, 'Hindi'),
(5, 'Jazz'),
(6, 'J-pop'),
(7, 'K-pop'),
(8, 'Lo-fi'),
(9, 'Malay'),
(10, 'Pop'),
(11, 'Rock'),
(12, 'R&B'),
(13, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `HistoryID` int(11) NOT NULL,
  `DateListened` datetime NOT NULL,
  `UserID` int(11) NOT NULL,
  `TrackID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`HistoryID`, `DateListened`, `UserID`, `TrackID`) VALUES
(93, '2024-04-14 00:35:20', 14, 57),
(96, '2024-04-18 08:40:14', 14, 74),
(98, '2024-04-18 08:43:19', 14, 68),
(99, '2024-04-12 22:07:48', 14, 55),
(105, '2024-04-17 18:24:00', 14, 60),
(162, '2024-04-18 08:41:24', 14, 59),
(164, '2024-04-18 08:41:55', 14, 69),
(165, '2024-04-14 00:28:55', 14, 65),
(166, '2024-04-13 20:35:40', 14, 63),
(168, '2024-04-14 00:28:47', 14, 64),
(170, '2024-04-12 22:08:26', 14, 47),
(171, '2024-04-13 20:36:09', 14, 52),
(203, '2024-04-18 08:30:05', 14, 58),
(204, '2024-04-18 08:41:17', 14, 56),
(207, '2024-04-24 11:06:59', 14, 72);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_admin`
--

CREATE TABLE `password_reset_admin` (
  `PRA_ID` int(11) NOT NULL,
  `Email` varchar(250) NOT NULL,
  `Key` varchar(250) NOT NULL,
  `ExpDate` datetime NOT NULL,
  `AdminID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_user`
--

CREATE TABLE `password_reset_user` (
  `PRU_ID` int(11) NOT NULL,
  `Email` varchar(250) NOT NULL,
  `Key` varchar(250) NOT NULL,
  `ExpDate` datetime NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

CREATE TABLE `playlist` (
  `PlaylistID` int(11) NOT NULL,
  `PlaylistGeneratedID` varchar(50) NOT NULL,
  `PlaylistName` varchar(30) NOT NULL,
  `PlaylistDesc` varchar(100) NOT NULL,
  `PlaylistImg` varchar(255) NOT NULL,
  `UserID` int(11) NOT NULL,
  `AdminID` int(11) NOT NULL,
  `GenreID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `playlist`
--

INSERT INTO `playlist` (`PlaylistID`, `PlaylistGeneratedID`, `PlaylistName`, `PlaylistDesc`, `PlaylistImg`, `UserID`, `AdminID`, `GenreID`) VALUES
(51, 'PL00001', 'Classical', 'Dive into the beauty of classical music with this Classical playlist.', 'classical.jpg', 0, 1, 1),
(54, 'PL00002', 'Cpop', 'Discover captivating Chinese melodies.', 'cpop.jpg', 0, 1, 2),
(55, 'PL00003', 'Electronic', 'Experience the futuristic sounds and throbbing beats of electronic music.', 'electronic.jpg', 0, 1, 3),
(56, 'PL00004', 'Hindi', 'Enjoy the vivid rhythms and heartfelt melodies of Hindi songs.', 'hindi.jpg', 0, 1, 4),
(57, 'PL00005', 'Jazz', 'Smooth rhythms and soulful improvisations of jazz music.', 'jazz.jpg', 0, 1, 5),
(58, 'PL00006', 'Jpop', 'Japanese tunes that transport you to the heart of Japan.', 'jpop.jpg', 0, 1, 6),
(59, 'PL00007', 'Kpop', 'Infectious beats and catchy melodies of K-pop.', 'kpop.jpg', 0, 1, 7),
(60, 'PL00008', 'Lofi', 'Relax with the gentle beats and chilled-out feelings of Lo-fi music.\r\n', 'lofi.jpeg', 0, 1, 8),
(61, 'PL00009', 'Malay', 'The soul-stirring melodies and rhythmic beats of Malay music.', 'malay.jpg', 0, 1, 9),
(62, 'PL000010', 'Pop', 'Chart-topping hits, hottest tracks around the globe.', 'pop.jpg', 0, 1, 10),
(63, 'PL000011', 'Rock Hiphop', 'Hip-hop music with dynamic beats and powerful rhymes.', 'hiphop.jpg', 0, 1, 11),
(64, 'PL000012', 'R&B', 'The best of contemporary urban sounds.', 'r&b.jpg', 0, 1, 12),
(65, 'PL000013', 'Others', 'Explore diverse array of music genres.', 'others.jpg', 0, 1, 13),
(67, 'PL00010', 'Happy vibe <3', 'abc', 'happy.jpg', 14, 0, 1),
(78, 'PL00011', 'Blue sky', 'Relax and chill tracks', '13.jpg', 11, 0, 13),
(79, 'PL00012', 'Blue sky vibe', 'Chill and relax tracks', '13.jpg', 48, 0, 13);

-- --------------------------------------------------------

--
-- Table structure for table `playlist_track`
--

CREATE TABLE `playlist_track` (
  `P_TrackID` int(11) NOT NULL,
  `P_DateAdded` datetime NOT NULL,
  `UserID` int(11) NOT NULL,
  `PlaylistID` int(11) NOT NULL,
  `TrackID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `playlist_track`
--

INSERT INTO `playlist_track` (`P_TrackID`, `P_DateAdded`, `UserID`, `PlaylistID`, `TrackID`) VALUES
(26, '2024-03-27 02:48:28', 14, 51, 43),
(28, '2024-03-27 02:48:55', 14, 60, 59),
(29, '2024-03-27 02:49:05', 14, 59, 57),
(31, '2024-03-27 02:49:27', 14, 55, 50),
(33, '2024-03-27 02:49:47', 14, 56, 52),
(34, '2024-03-27 02:49:56', 14, 58, 55),
(36, '2024-03-27 02:50:19', 14, 55, 49),
(41, '2024-03-31 08:17:05', 0, 51, 45),
(42, '2024-03-31 08:17:20', 0, 54, 47),
(43, '2024-03-31 08:17:24', 0, 54, 48),
(44, '2024-03-31 08:17:40', 0, 56, 51),
(46, '2024-03-31 08:18:06', 0, 57, 54),
(47, '2024-03-31 08:18:20', 0, 58, 56),
(48, '2024-03-31 08:19:09', 0, 61, 62),
(50, '2024-03-31 08:19:25', 0, 61, 63),
(51, '2024-03-31 08:19:39', 0, 62, 64),
(52, '2024-03-31 08:19:46', 0, 62, 65),
(53, '2024-03-31 08:19:59', 0, 63, 66),
(54, '2024-03-31 08:20:09', 0, 63, 67),
(55, '2024-03-31 14:22:53', 0, 64, 68),
(56, '2024-03-31 14:22:59', 0, 64, 69),
(57, '2024-03-31 14:23:09', 0, 65, 70),
(58, '2024-03-31 14:23:14', 0, 65, 71),
(65, '2024-04-05 11:03:30', 14, 67, 71),
(66, '2024-04-05 11:03:37', 14, 67, 68),
(72, '2024-04-13 14:40:56', 14, 67, 59),
(75, '2024-04-16 18:40:11', 0, 64, 74),
(76, '2024-04-16 18:40:44', 0, 62, 73),
(77, '2024-04-16 18:41:30', 0, 59, 58),
(80, '2024-04-16 18:44:01', 0, 60, 60),
(83, '2024-04-16 22:21:41', 0, 51, 53);

-- --------------------------------------------------------

--
-- Table structure for table `track`
--

CREATE TABLE `track` (
  `TrackID` int(11) NOT NULL,
  `TrackGeneratedID` varchar(50) NOT NULL,
  `TrackName` varchar(255) NOT NULL,
  `TrackFile` varchar(255) NOT NULL,
  `TrackImg` varchar(255) NOT NULL,
  `UploadDate` date NOT NULL,
  `ReleaseDate` date NOT NULL,
  `ValidationStatus` varchar(20) NOT NULL,
  `Reason` varchar(255) NOT NULL,
  `TrackCount` int(11) NOT NULL,
  `ShareCount` int(11) NOT NULL,
  `AdminID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `GenreID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `track`
--

INSERT INTO `track` (`TrackID`, `TrackGeneratedID`, `TrackName`, `TrackFile`, `TrackImg`, `UploadDate`, `ReleaseDate`, `ValidationStatus`, `Reason`, `TrackCount`, `ShareCount`, `AdminID`, `UserID`, `GenreID`) VALUES
(45, 'TR00002', 'The Lamp Is Low - Laurindo Almeida', 'The Lamp Is Low.mp3', 'The Lamp Is Low.jpg', '2024-01-06', '1934-04-24', 'Approved', 'Sufficient data.  A good classical music that can recommend to others.', 5, 0, 0, 10, 1),
(47, 'TR00003', 'Ni Xiang Yao De - Yan Ren Zhong', 'Ni Xiang Yao De.mp3', 'Ni Xiang Yao De.jpeg', '2024-01-20', '2020-03-28', 'Approved', 'The track has been approved due to engaging lyrics, and overall contribution to enhancing the listening experience.', 1, 1, 0, 14, 2),
(48, 'TR00004', 'Hen Xu Yao - Yan Ren Zhong', 'Hen xu yao.mp3', 'Hen Xu Yao.jpg', '2024-02-10', '2019-10-11', 'Approved', '', 2, 1, 0, 14, 2),
(49, 'TR00005', 'Metamorphosis - Interworld', 'METAMORPHOSIS.mp3', 'Metamorphosis.jpg', '2024-02-01', '2021-11-25', 'Approved', '', 0, 0, 0, 14, 3),
(50, 'TR00006', 'Animals - Martin Garrix', 'Animals.mp3', 'Animals.jpg', '2024-01-29', '2013-06-17', 'Approved', '', 3, 0, 0, 11, 3),
(51, 'TR00007', 'Leja Re - Dhvani Bhanushali\r\n', 'Leja Re.mp3', 'Leja Re.jpg', '2024-02-27', '2018-11-23', 'Approved', '', 0, 0, 0, 14, 4),
(52, 'TR00008', 'Mera Yaar - Dhvani Bhanushali & Ash King', 'Mera Yaar.mp3', 'Mera Yaar.jpeg', '2024-02-05', '2021-12-01', 'Approved', '', 3, 0, 0, 14, 4),
(53, 'TR00009', 'Ladyfingers - Herb Alpert\r\n', 'Ladyfingers.mp3', 'Ladyfingers.jpg', '2024-01-15', '1965-04-01', 'Approved', '', 1, 0, 0, 10, 5),
(54, 'TR00010', 'Somethin\' Stupid - Frank Sinatra\r\n', 'Somethin\' Stupid.mp3', 'Somethin\' Stupid.jpeg', '2023-12-28', '1967-03-05', 'Approved', '', 1, 1, 0, 10, 5),
(55, 'TR00011', 'Kaikai Kitan - Eve', 'Jujutsu Kaisen - Opening 1.mp3', 'Jujutsu Kaisen.jpg', '2023-12-26', '2020-10-03', 'Approved', '', 9, 0, 0, 11, 6),
(56, 'TR00012', 'Overdose - Natori', 'Overdose.mp3', 'Overdose.jpeg', '2024-01-18', '2023-03-19', 'Approved', '', 16, 1, 0, 14, 6),
(57, 'TR00013', 'Super - Seventeen', 'Super.mp3', 'Super.jpg', '2024-01-31', '2023-04-24', 'Approved', '', 9, 2, 0, 14, 7),
(58, 'TR00014', 'Love Scenario - IKON', 'Love Scenario.mp3', 'Love Scenario.jpg', '2024-02-20', '2018-01-25', 'Approved', '', 7, 1, 0, 10, 7),
(59, 'TR00015', 'Sweetly - Lord Kael', 'Sweetly.mp3', 'Sweetly.jpg', '2024-02-03', '2022-10-22', 'Approved', '', 16, 1, 0, 14, 8),
(60, 'TR00016', 'Pure Imagination Lofi - G Sounds', 'Pure Imagination Lofi.mp3', 'Pure Imagination Lofi.jpg', '2024-02-16', '2022-04-06', 'Approved', '', 13, 2, 0, 11, 8),
(61, 'TR00017', 'Sah - Sarah Suhairi', 'SAH.mp3', 'sah.jpeg', '2024-03-27', '2024-01-19', 'Approved', '', 1, 0, 0, 14, 9),
(62, 'TR00018', 'Pedih - Sarah Suhairi', 'Pedih.mp3', 'Pedih.jpg', '2020-01-02', '2020-03-08', 'Approved', '', 2, 0, 0, 14, 9),
(63, 'TR00019', 'Pesan Terakhir - Lyodra', 'Pesan Terakhir.mp3', 'Pesan Terakhir.jpg', '2024-03-02', '2021-07-16', 'Approved', '', 1, 0, 0, 14, 9),
(64, 'TR00020', 'Cheating on You - Charlie Puth', 'Cheating on You.mp3', 'Cheating On You.jpg', '2024-03-20', '2019-10-02', 'Approved', '', 8, 0, 1, 11, 10),
(65, 'TR00021', 'Rockabye - Clean Bandit\r\n', 'Rockabye.mp3', 'Rockabye.png', '2024-03-15', '2016-10-21', 'Approved', '', 2, 0, 0, 10, 10),
(66, 'TR00022', 'Blueberry Faygo - Lil Mose', 'Blueberry Faygo.mp3', 'Blueberry Faygo.png', '2024-03-20', '2020-02-07', 'Approved', '', 13, 1, 0, 11, 11),
(67, 'TR00023', 'AHHH HA - Lil Durk', 'AHHH HA.mp3', 'AHHH HA.jpg', '2024-03-21', '2022-02-22', 'Approved', '', 1, 0, 0, 14, 11),
(68, 'TR00024', 'No Scrubs - TLC', 'No Scrubs.mp3', 'No Scrubs.jpg', '2024-03-21', '1999-02-02', 'Approved', '', 11, 0, 1, 10, 12),
(69, 'TR00025', 'Tip Toe - Jason Derulo', 'Tip Toe.mp3', 'Tip Toe.jpeg', '2024-03-22', '2017-11-10', 'Approved', '', 21, 2, 1, 10, 12),
(70, 'TR00026', 'See Tình', 'See Tinh.mp3', 'See Tinh.png', '2020-12-15', '2020-12-20', 'Approved', '', 1, 0, 0, 11, 13),
(71, 'TR00027', 'Vitamin A', 'Vitamin A.mp3', 'Vitamin A.jpg', '2020-12-15', '2020-12-20', 'Approved', '', 6, 2, 0, 11, 13),
(72, 'TR00028', 'Attention - Charlie Puth', 'Attention.mp3', 'Attention.jpg', '2021-04-21', '2024-02-15', 'Approved', '', 4, 0, 1, 10, 10),
(73, 'TR00029', 'We Don\'t Talk Anymore - Charlie Puth', 'We Don\'t Talk Anymore.mp3', 'We Don\'t Talk Anymore.png', '2016-05-24', '2023-12-31', 'Approved', '', 6, 0, 1, 10, 10),
(74, 'TR00030', 'Fantasy - HYBS', 'Fantasy.mp3', 'Fantasy.png', '2024-03-31', '2023-11-14', 'Approved', '', 6, 1, 1, 10, 12),
(75, 'TR00031', 'That\'s Hilarious', 'Charlie Puth - That\'s Hilarious (Lyrics).mp3', 'top.png', '2024-04-01', '2022-04-08', 'Pending', 'Good and well-know music that should be recommended to others. Information for this track is sufficient.', 1, 0, 0, 14, 10),
(89, 'TR00032', 'Love Story', 'Love Story.mp3', '1.jpg', '2024-04-18', '2024-04-18', 'Approved', 'Sufficient data', 0, 0, 0, 14, 10),
(91, 'TR00034', 'Love Story', 'Love Story.mp3', 'Love Story.png', '2024-04-22', '2024-04-09', 'Rejected', 'Wrong release date for this track.', 0, 0, 0, 48, 10);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `UserGeneratedID` varchar(50) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `UserImage` varchar(255) NOT NULL,
  `UserBio` varchar(100) NOT NULL,
  `UserEmail` varchar(50) NOT NULL,
  `UserPassword` varchar(50) NOT NULL,
  `Trn_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `UserGeneratedID`, `Username`, `UserImage`, `UserBio`, `UserEmail`, `UserPassword`, `Trn_date`) VALUES
(10, 'US00001', 'MuziQ Official Account', 'MuziQ.png', 'Hola~ Enjoy music here', 'MuziQ@gmail.com', 'MuziQ12345%', '0000-00-00 00:00:00'),
(11, 'US00002', 'Luna Serenade', 'default.jpg', 'Music lover with a passion for catchy tunes and infectious rhythms! 🎶', 'playersky00@gmail.com', 'Yunqi.t0426^', '0000-00-00 00:00:00'),
(14, 'US00004', 'Penguinny', '31.jpg', 'Diverse musical influences, from classical to electronic~', 'sevynpenguin@gmail.com', 'Sevyn0426^', '2024-04-13 16:46:43'),
(48, 'US00005', 'Yunqii', '4.jpg', 'Music speaks louder than words. Bio coming soon!', 'Yunqi0426@gmail.com', 'Thanyunqi123#', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`FAQID`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`FeedbackID`),
  ADD KEY `FK_UserID` (`UserID`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`GenreID`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`HistoryID`),
  ADD KEY `TrackID` (`TrackID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `password_reset_admin`
--
ALTER TABLE `password_reset_admin`
  ADD PRIMARY KEY (`PRA_ID`),
  ADD KEY `FK_AdminID` (`AdminID`);

--
-- Indexes for table `password_reset_user`
--
ALTER TABLE `password_reset_user`
  ADD PRIMARY KEY (`PRU_ID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `playlist`
--
ALTER TABLE `playlist`
  ADD PRIMARY KEY (`PlaylistID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `GenreID` (`GenreID`);

--
-- Indexes for table `playlist_track`
--
ALTER TABLE `playlist_track`
  ADD PRIMARY KEY (`P_TrackID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `PlaylistID` (`PlaylistID`),
  ADD KEY `TrackID` (`TrackID`);

--
-- Indexes for table `track`
--
ALTER TABLE `track`
  ADD PRIMARY KEY (`TrackID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `GenreID` (`GenreID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `FAQID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `FeedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `genre`
--
ALTER TABLE `genre`
  MODIFY `GenreID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `HistoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `password_reset_admin`
--
ALTER TABLE `password_reset_admin`
  MODIFY `PRA_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `password_reset_user`
--
ALTER TABLE `password_reset_user`
  MODIFY `PRU_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `playlist`
--
ALTER TABLE `playlist`
  MODIFY `PlaylistID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `playlist_track`
--
ALTER TABLE `playlist_track`
  MODIFY `P_TrackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `track`
--
ALTER TABLE `track`
  MODIFY `TrackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `FK_UserID` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `password_reset_admin`
--
ALTER TABLE `password_reset_admin`
  ADD CONSTRAINT `FK_AdminID` FOREIGN KEY (`AdminID`) REFERENCES `admin` (`AdminID`);

--
-- Constraints for table `password_reset_user`
--
ALTER TABLE `password_reset_user`
  ADD CONSTRAINT `password_reset_user_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
