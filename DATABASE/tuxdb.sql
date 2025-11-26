-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2025 at 08:28 PM
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
-- Database: `tuxdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `aname` varchar(50) NOT NULL,
  `apass` varchar(255) NOT NULL,
  `path` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `aname`, `apass`, `path`, `created_at`, `last_login`, `is_active`) VALUES
(6, 'admin', '$2y$10$vZS/hpWBoAuhFTJfiKeK7ebSM0bUN7uRwaDKOlR2MQpFPtAotITXW', '', '2025-10-31 07:33:05', '2025-11-05 12:20:16', 1),
(8, 'kripal', '$2y$10$nhcMMdveVnIzo14SC/uoXeJzNHrgZYikii.3UQ0bAeBZHPWm0mG22', '', '2025-10-31 07:33:05', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL DEFAULT 'Unknown',
  `isbn` varchar(50) NOT NULL DEFAULT 'Unknown',
  `language` varchar(50) NOT NULL DEFAULT 'Unknown',
  `publisher` varchar(255) NOT NULL DEFAULT 'Unknown',
  `edition` varchar(50) NOT NULL DEFAULT 'Unknown',
  `paperback` varchar(50) NOT NULL DEFAULT 'Unknown',
  `description` text NOT NULL DEFAULT 'No description available.',
  `publish_date` date DEFAULT NULL,
  `upload_date` datetime NOT NULL DEFAULT current_timestamp(),
  `file_path` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `extra_files` text DEFAULT NULL,
  `publisher_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `is_latest` tinyint(1) DEFAULT 0,
  `downloads` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `isbn`, `language`, `publisher`, `edition`, `paperback`, `description`, `publish_date`, `upload_date`, `file_path`, `thumbnail`, `extra_files`, `publisher_id`, `category_id`, `is_latest`, `downloads`) VALUES
(22, 'Managing Projects with GNU Make, Third Edition', 'Robert meclenburg', '0-596-00610-1', 'English', 'Robert meclenburg', 'Third Edition.', '272', 'began using Unix as a student in 1977 and has been programming professionally for 23 years. His make experience started in 1982 at NASA with\r\nUnix Version 7. Robert received his PhD in computer science from the University of\r\nUtah in 1991. Since then, he has worked in many fields ranging from mechanical\r\nCAD to bioinformatics, and brings his extensive experience in C++, Java, and Lisp\r\nto bear on the problems of project management with make.', '2004-11-04', '2025-11-04 19:21:46', 'uploads/book_690a04f2042fd6.96093927.pdf', 'uploads/thumbnails/thumb_690a04f204993.jpg', '[\"uploads\\/extra\\/extra_690a04f205036_Screenshot 2025-10-30 154650.png\",\"uploads\\/extra\\/extra_690a04f2055f5_Screenshot 2025-10-30 154714.png\",\"uploads\\/extra\\/extra_690a04f205b22_Screenshot 2025-10-30 154726.png\",\"uploads\\/extra\\/extra_690a04f205fa6_Screenshot 2025-10-30 154742.png\",\"uploads\\/extra\\/extra_690a04f2065b7_Screenshot 2025-10-30 154802.png\",\"uploads\\/extra\\/extra_690a04f206aec_Screenshot 2025-10-30 154814.png\",\"uploads\\/extra\\/extra_690a04f206fa4_Screenshot 2025-10-30 154848.png\",\"uploads\\/extra\\/extra_690a04f20743f_Screenshot 2025-10-30 154905.png\",\"uploads\\/extra\\/extra_690a04f2078c9_Screenshot 2025-10-30 154921.png\",\"uploads\\/extra\\/extra_690a04f207cf5_Screenshot 2025-10-30 154953.png\"]', 10, 14, 0, 0),
(23, 'Dark Psychology Secrets', 'Daniel James Hollins', 'Unknown', 'English', 'McGraw Hill', 'first Edition', '120', 'Dark Psychology iѕ bоth thе ѕtudу оf criminal & dеviаnt bеhаviоr аnd a\r\nсоnсерtuаl frаmеwоrk for deciphering thе potential fоr еvil within аll\r\nhumаn bеingѕ. Dаrk Pѕусhоlоgу is thе ѕtudу оf thе human соnditiоn as it\r\nrеlаtеѕ to thе psychological nаturе оf people tо рrеу upon other реорlе\r\nmotivated bу criminal and/or dеviаnt drivеѕ that lасk рurроѕе аnd gеnеrаl\r\nassumptions оf inѕtinсtuаl drivеѕ and ѕосiаl sciences thеоrу. All of\r\nhumаnitу hаѕ thiѕ роtеntiаl to viсtimizе оthеr humаnѕ аnd living creatures.\r\nWhilе mаnу rеѕtrаin or ѕublimаtе thiѕ tеndеnсу, ѕоmе асt upon thеѕе\r\nimрulѕеѕ. Dаrk Psychology ѕееkѕ tо understand thоѕе thоughtѕ, fееlingѕ,\r\nреrсерtiоnѕ аnd subjective processing ѕуѕtеmѕ that lеаd to predatory\r\nbеhаviоr that iѕ аntithеtiсаl tо соntеmроrаrу undеrѕtаndingѕ of humаn\r\nbеhаviоr. Dark Psychology assumes that сriminаl, dеviаnt аnd abusive\r\nbehaviors are рurроѕivе аnd have some rаtiоnаl, gоаl-оriеntеd\r\nmotivation99% оf thе timе. It is thе rеmаining1%, Dаrk Pѕусhоlоgу раrtѕ\r\nfrom Adlеriаn theory аnd the Teleological Approach. Dаrk Psychology\r\npostulates thеrе iѕ a rеgiоn within thе human рѕусhе that еnаblеѕ ѕоmе\r\nреорlе tо соmmit аtrосiоuѕ асtѕ without рurроѕе. In this thеоrу, it hаѕ bееn\r\nсоinеd the Dark Singulаritу.', '2019-06-04', '2025-11-04 19:26:02', 'uploads/book_690a05f2c3eba4.18526547.pdf', 'uploads/thumbnails/thumb_690a05f2c4664.jpg', '[\"uploads\\/extra\\/extra_690a05f2c4c46_Screenshot 2025-11-04 184955.png\",\"uploads\\/extra\\/extra_690a05f2c5085_Screenshot 2025-11-04 185009.png\",\"uploads\\/extra\\/extra_690a05f2c5554_Screenshot 2025-11-04 185030.png\",\"uploads\\/extra\\/extra_690a05f2c5997_Screenshot 2025-11-04 185046.png\",\"uploads\\/extra\\/extra_690a05f2c67bf_Screenshot 2025-11-04 185104.png\"]', 9, 13, 1, 0),
(24, 'History STD', 'gujrat gov', 'Unknown', 'Gujrati', 'Gujrat GOV', 'First Edition', '152', 'No description available.', '2020-05-04', '2025-11-04 19:31:32', 'uploads/book_690a073c41d9e1.76874151.pdf', 'uploads/thumbnails/thumb_690a073c424a9.jpg', '[\"uploads\\/extra\\/extra_690a073c42a92_Screenshot 2025-11-04 185839.png\",\"uploads\\/extra\\/extra_690a073c42f35_Screenshot 2025-11-04 185851.png\",\"uploads\\/extra\\/extra_690a073c43382_Screenshot 2025-11-04 185904.png\",\"uploads\\/extra\\/extra_690a073c437d9_Screenshot 2025-11-04 185915.png\",\"uploads\\/extra\\/extra_690a073c43c6f_Screenshot 2025-11-04 185926.png\",\"uploads\\/extra\\/extra_690a073c440e3_Screenshot 2025-11-04 185954.png\"]', 6, 16, 0, 0),
(25, 'The Universe in a Nutshell', 'STEPHE N HAWKIN G', '059 3 04815 6', 'English', 'STEPHEN HAWKING', 'First Edition', '219', 'A Brief History of Time,\r\nto be such a success. It was on the London Sunday Times bestseller\r\nlist for over four years, which is longer than any other book has\r\nbeen, and remarkable for a book on science that was not easy going.\r\nAfter that, people kept asking when I would write a sequel. I resisted because I didn\'t want to write Son of Brief History or A Slightly\r\nLonger History of Time, and because I was busy with research. But I\r\nhave come to realize that there is room for a different kind of book\r\nthat might be easier to understand. A Brief History of Time was\r\norganized in a linear fashion, with most chapters following and logically depending on the preceding chapters. This appealed to some\r\nreaders, but others got stuck in the early chapters and never reached\r\nthe more exciting material later on. By contrast, the present book is\r\nmore like a tree: Chapters 1 and 2 form a central trunk from which\r\nthe other chapters branch off.', '2001-01-19', '2025-11-04 19:35:18', 'uploads/book_690a081e24e8d0.84430456.pdf', 'uploads/thumbnails/thumb_690a081e2502d.jpg', '[\"uploads\\/extra\\/extra_690a081e256a3_Screenshot 2025-11-04 190223.png\",\"uploads\\/extra\\/extra_690a081e25bdc_Screenshot 2025-11-04 190233.png\",\"uploads\\/extra\\/extra_690a081e260b6_Screenshot 2025-11-04 190241.png\",\"uploads\\/extra\\/extra_690a081e26540_Screenshot 2025-11-04 190252.png\",\"uploads\\/extra\\/extra_690a081e274dd_Screenshot 2025-11-04 190300.png\",\"uploads\\/extra\\/extra_690a081e27858_Screenshot 2025-11-04 190313.png\",\"uploads\\/extra\\/extra_690a081e27d1b_Screenshot 2025-11-04 190331.png\",\"uploads\\/extra\\/extra_690a081e28184_Screenshot 2025-11-04 190346.png\"]', 7, 15, 0, 0),
(26, 'Penetration testing', 'Peter Van Eeckhoutte', '978-1-59327-564-8', 'English', 'McGraw Hill', 'First Edition', '531', 'mobile device security is only one of the things Georgia does.\r\nGeorgia performs penetration tests for a living; travels the world to deliver\r\ntraining on pentesting, the Metasploit Framework, and mobile device security; and presents novel and innovative ideas on how to assess the security of\r\nmobile devices at conferences.\r\nGeorgia spares no effort in diving deeper into more advanced topics and working hard to learn new things. She is a former student of my\r\n(rather challenging) Exploit Development Bootcamp, and I can attest to\r\nthe fact that she did very well throughout the entire class. Georgia is a true\r\nxx   Foreword\r\nhacker—always willing to share her findings and knowledge with our great\r\ninfosec community—and when she asked me to write the foreword to this\r\nbook, I felt very privileged and honored.', '2014-05-04', '2025-11-04 19:39:08', 'uploads/book_690a09047dc165.90215695.pdf', 'uploads/thumbnails/thumb_690a09047e278.jpg', '[\"uploads\\/extra\\/extra_690a09047e8d0_Screenshot 2025-11-04 190817.png\",\"uploads\\/extra\\/extra_690a09047ed44_Screenshot 2025-11-04 190828.png\",\"uploads\\/extra\\/extra_690a09047f17d_Screenshot 2025-11-04 190854.png\",\"uploads\\/extra\\/extra_690a09047fcc7_Screenshot 2025-11-04 190914.png\",\"uploads\\/extra\\/extra_690a090480213_Screenshot 2025-11-04 190926.png\",\"uploads\\/extra\\/extra_690a0904806ac_Screenshot 2025-11-04 190945.png\",\"uploads\\/extra\\/extra_690a090480b02_Screenshot 2025-11-04 190956.png\"]', 9, 14, 0, 0),
(27, 'The Web Applicațion Hacker\'s Handbook', 'Dafydd Stuttard and Marcus Pinto', '978-1-118-02647-2', 'English', 'McGraw Hill', 'Third Edition', '914', 'This book is a practical guide to discovering and exploiting security fl aws in\r\nweb applications. By “web applications” we mean those that are accessed using\r\na web browser to communicate with a web server. We examine a wide variety\r\nof different technologies, such as databases, fi le systems, and web services, but\r\nonly in the context in which these are employed by web applications.\r\nIf you want to learn how to run port scans, attack fi rewalls, or break into servers in other ways, we suggest you look elsewhere. But if you want to know how\r\nto hack into a web application, steal sensitive data, and perform unauthorized\r\nactions, this is the book for you. There is enough that is interesting and fun to\r\nsay on that subject without straying into any other territory', '2011-12-04', '2025-11-04 19:42:56', 'uploads/book_690a09e84a8989.56860570.pdf', 'uploads/thumbnails/thumb_690a09e84afd9.jpg', '[\"uploads\\/extra\\/extra_690a09e84b80b_Screenshot 2025-11-04 191112.png\",\"uploads\\/extra\\/extra_690a09e84be65_Screenshot 2025-11-04 191123.png\",\"uploads\\/extra\\/extra_690a09e84c414_Screenshot 2025-11-04 191141.png\",\"uploads\\/extra\\/extra_690a09e84c88c_Screenshot 2025-11-04 191209.png\",\"uploads\\/extra\\/extra_690a09e84ce2c_Screenshot 2025-11-04 191225.png\",\"uploads\\/extra\\/extra_690a09e84d300_Screenshot 2025-11-04 191314.png\",\"uploads\\/extra\\/extra_690a09e84d7b4_Screenshot 2025-11-04 191324.png\"]', 9, 14, 0, 0),
(28, 'Wizard: The Life and Times of Nikola Tesla: Biography of a Genius', 'MARC J. SEIFER', '0-8065-3996-8', 'English', 'Nikola Tesla', 'First Edition', '494', 'Nikola Tesla was my father’s uncle, and as such he was treated by our family much as any uncle\r\nmight have been who lived at a considerable distance and was advanced in years. But there were\r\nstronger bonds between my father and Tesla than might otherwise have been the case. They\r\ncame from identical social backgrounds, sons of Serbian Orthodox priests, born and raised a few\r\nmiles apart in the Austro-Hungarian military frontier district county of Lika in the Province of\r\nCroatia (my grandmother was Tesla’s sister Angelina); they were the only members of a\r\nrelatively limited extended family to emigrate to America; and they were the only members to\r\nundertake science and technology as their life’s work', '1996-05-04', '2025-11-04 20:05:54', 'uploads/book_690a0f49f2f656.17447044.pdf', 'uploads/thumbnails/thumb_690a0f49f36e6.jpg', '[\"uploads\\/extra\\/extra_690a0f49f3c73_Screenshot 2025-11-04 195237.png\",\"uploads\\/extra\\/extra_690a0f49f4084_Screenshot 2025-11-04 195335.png\",\"uploads\\/extra\\/extra_690a0f4a001ad_Screenshot 2025-11-04 195400.png\",\"uploads\\/extra\\/extra_690a0f4a0052f_Screenshot 2025-11-04 195414.png\",\"uploads\\/extra\\/extra_690a0f4a0134e_Screenshot 2025-11-04 195426.png\",\"uploads\\/extra\\/extra_690a0f4a01692_Screenshot 2025-11-04 195450.png\",\"uploads\\/extra\\/extra_690a0f4a019d3_Screenshot 2025-11-04 195514.png\",\"uploads\\/extra\\/extra_690a0f4a04b42_Screenshot 2025-11-04 195545.png\",\"uploads\\/extra\\/extra_690a0f4a05436_Screenshot 2025-11-04 195558.png\",\"uploads\\/extra\\/extra_690a0f4a062da_Screenshot 2025-11-04 195606.png\",\"uploads\\/extra\\/extra_690a0f4a069d2_Screenshot 2025-11-04 195620.png\",\"uploads\\/extra\\/extra_690a0f4a06ec1_Screenshot 2025-11-04 195655.png\"]', 8, 17, 0, 0),
(29, 'ISRO Centralized Recruitment  Report', 'ISRO', 'Unknown', 'English', 'default', 'Unknown', '6', 'No description available.', '2018-06-04', '2025-11-04 20:08:37', 'uploads/book_690a0fed327ca7.57600779.pdf', 'uploads/thumbnails/thumb_690a0fed32c44.jpg', '[\"uploads\\/extra\\/extra_690a0fed3301d_Screenshot 2025-11-04 195914.png\",\"uploads\\/extra\\/extra_690a0fed33540_Screenshot 2025-11-04 195923.png\",\"uploads\\/extra\\/extra_690a0fed33c87_Screenshot 2025-11-04 195933.png\"]', 5, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `icon`) VALUES
(1, 'General', 'uploads/categories/1762260909_1759662904_illustration-of-math-symbols-and-numbers-M8Y9D6.jpg'),
(13, 'Psychology', 'uploads/categories/1762261153_1759662820_vector-mental-health-and-psychology-concept.jpg'),
(14, 'Programming', 'uploads/categories/1762261168_1759662966_OIP.webp'),
(15, 'Science', 'uploads/categories/1762261374_Liquid.png'),
(16, 'History', 'uploads/categories/1762261522_Screenshot 2025-11-04 183458.png'),
(17, 'Sociology', 'uploads/categories/1762261737_Screenshot 2025-11-04 183829.png');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read','replied') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `surname`, `email`, `subject`, `message`, `status`, `created_at`, `updated_at`, `admin_notes`) VALUES
(1, 'krupal', 'katariya', 'bcakkh1@gmail.com', 'for Appointment', 'when you are free message me in mail we meet outside office to talk about web security.', 'replied', '2025-11-04 14:47:06', '2025-11-04 14:54:13', 'i have to meet tomorrow');

-- --------------------------------------------------------

--
-- Table structure for table `donated_books`
--

CREATE TABLE `donated_books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `donated_by` varchar(255) NOT NULL,
  `isbn` varchar(100) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `publisher_id` int(11) DEFAULT NULL,
  `edition` varchar(50) DEFAULT NULL,
  `paperback` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `publish_date` date DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `extra_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`extra_images`)),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) DEFAULT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `rating` int(11) DEFAULT 0,
  `status` enum('pending','read','replied') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `subject`, `message`, `rating`, `status`, `created_at`, `updated_at`) VALUES
(10, 'krupal katariya', 'bcakkh1@gmail.com', 'for Testing purpose', 'make Some basic changes in accessibility and design.', 5, 'pending', '2025-11-04 14:48:59', '2025-11-04 14:48:59');

-- --------------------------------------------------------

--
-- Table structure for table `publishers`
--

CREATE TABLE `publishers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `icon` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `publishers`
--

INSERT INTO `publishers` (`id`, `name`, `created_at`, `icon`) VALUES
(5, 'default', '2025-10-30 10:23:53', 'uploads/publishers/pub_69033cb9a8e288.36149630.png'),
(6, 'Gujrat GOV', '2025-11-04 13:09:58', 'uploads/publishers/pub_6909fb26e64a91.27385587.png'),
(7, 'STEPHEN HAWKING', '2025-11-04 13:13:09', 'uploads/publishers/pub_6909fbe5ec43b1.72111752.png'),
(8, 'Nikola Tesla', '2025-11-04 13:15:20', 'uploads/publishers/1762262134_Screenshot 2025-11-04 184500.png'),
(9, 'McGraw Hill', '2025-11-04 13:17:41', 'uploads/publishers/pub_6909fcf5ce6901.66552929.png'),
(10, 'Robert meclenburg', '2025-11-04 13:48:19', 'uploads/publishers/pub_690a04239d9a66.68504799.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `book_id` int(10) UNSIGNED NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(150) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `book_id`, `user_name`, `user_email`, `rating`, `comment`, `status`, `created_at`, `updated_at`) VALUES
(5, 29, 'anonymous person 1', 'anonymous@anonymous.anonymous', 1, 'this book is very helpful for people who interested in space science field', 'approved', '2025-11-04 14:51:06', '2025-11-04 14:53:18'),
(6, 23, 'anonymous person 2', 'anonymous@anonymous.com', 2, 'this psychology book help me to understand how people get controlled by words and techniques.', 'approved', '2025-11-04 14:52:50', '2025-11-04 14:53:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_books_publisher` (`publisher_id`),
  ADD KEY `fk_category` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donated_books`
--
ALTER TABLE `donated_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_donated_books_publisher` (`publisher_id`),
  ADD KEY `fk_donated_books_category` (`category_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `publishers`
--
ALTER TABLE `publishers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_book_id` (`book_id`),
  ADD KEY `idx_status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `donated_books`
--
ALTER TABLE `donated_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `publishers`
--
ALTER TABLE `publishers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_books_publisher` FOREIGN KEY (`publisher_id`) REFERENCES `publishers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `donated_books`
--
ALTER TABLE `donated_books`
  ADD CONSTRAINT `fk_donated_books_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_donated_books_publisher` FOREIGN KEY (`publisher_id`) REFERENCES `publishers` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_books` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
