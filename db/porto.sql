-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 24, 2025 at 02:33 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `porto`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `email_contact` varchar(50) NOT NULL,
  `name_contact` varchar(50) NOT NULL,
  `subject_contact` varchar(100) NOT NULL,
  `message_contact` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

CREATE TABLE `education` (
  `id` int(11) NOT NULL,
  `year_in` year(4) NOT NULL,
  `year_out` year(4) NOT NULL,
  `name_instansi` varchar(100) NOT NULL,
  `degree` varchar(100) NOT NULL,
  `major` varchar(100) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `education`
--

INSERT INTO `education` (`id`, `year_in`, `year_out`, `name_instansi`, `degree`, `major`, `description`) VALUES
(1, '2016', '2019', 'AL-BAHRI', 'High School Diploma', 'Software Engineering', 'Berhasil Menyelesaikan Project Program Websites untuk Kesiswaan Sekolah dengan Menggunakan Bahasa PHP Native dan Databases MySQL'),
(2, '2022', '2026', 'Universitas Tebuka', 'Bachelor Degree', 'Information Systems', 'Mahasiswa Aktif Semester 6'),
(4, '2026', '2028', 'Imperial College London', 'Master of Informatics', 'Information Systems', 'SEMOGA YAA');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `subject`, `message`, `status`) VALUES
(4, 'Abroor Rizky', 'datambp@gmail.com', 'TEST', 'TEST', 2),
(5, 'Jamaludin', 'admin@gmail.com', 'Colaboration', 'TESTING', 2),
(6, 'Abroor Rizky', 'abroorizno@gmail.com', 'TEST', 'TESTING', 1),
(7, 'Syaiful', 'syaifulyuzak@gmail.com', 'TEST', 'Halo', 1),
(8, 'Syaiful Yuzack', 'syaifulyuzak17@gmail.com', 'TEST', 'halo', 1),
(9, 'Jamaludin', 'datambp@gmail.com', 'TEST', 'TEST', 2),
(10, 'Reiji', 'admin@gmail.com', 'TEST', 'test', 2);

-- --------------------------------------------------------

--
-- Table structure for table `portfolio`
--

CREATE TABLE `portfolio` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `portfolio`
--

INSERT INTO `portfolio` (`id`, `nama`, `photo`, `description`) VALUES
(1, 'Point Of Sales', '67dcb0fd721c0_mobile-pic.jpg', 'Mobile App'),
(2, 'Point Of Sales', '67dcb1903fdac_pos-pict.jpg', 'Websites App'),
(3, 'Laundry Orders', '67dcb1b8898ed_web-pict.png', 'Websites App');

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `profession` varchar(200) NOT NULL,
  `birthday` date NOT NULL,
  `websites` varchar(200) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `email` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `background` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `name`, `profession`, `birthday`, `websites`, `phone`, `email`, `city`, `photo`, `description`, `background`, `status`) VALUES
(14, 'Abroor Rizky Nouvaldy', 'Fullstack Web Developers', '2002-01-08', 'www.abroorrizky.com', '082113107659', 'abroorrizky@gmail.com', 'Jakarta, Indonesia', '67dbb68fd5dc0_IMG_4943.JPG', 'I believe that collaboration is the key to success. Lets work together to create something extraordinary', '67de07add6359_7b9937d6-a01a-481d-a47d-856161520193.JPG', 1);

-- --------------------------------------------------------

--
-- Table structure for table `resume`
--

CREATE TABLE `resume` (
  `id` int(11) NOT NULL,
  `year_in` year(4) NOT NULL,
  `year_out` year(4) NOT NULL,
  `company` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resume`
--

INSERT INTO `resume` (`id`, `year_in`, `year_out`, `company`, `city`, `description`) VALUES
(1, '2019', '2020', 'PT. Jakarta Cakratunggal Steel Mills', 'Jakarta, Indonesia', 'Saya bekerja sebagai IT Support Technician dan Warehouse Checker');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `pict` varchar(100) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `pict`, `description`) VALUES
(1, 'Backend Engineering', '67db5fa8b1441_backend.png', 'Membuat TEST'),
(2, 'Frontend Engineering', '67db5fc36e361_coding.png', 'Membuat TEST');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `skill` varchar(50) NOT NULL,
  `percent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `skill`, `percent`) VALUES
(1, 'php', 90),
(3, 'JavaScript', 40),
(4, 'HTML', 70),
(5, 'SQL', 45),
(6, 'React JS', 30);

-- --------------------------------------------------------

--
-- Table structure for table `testimoni`
--

CREATE TABLE `testimoni` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `profession` varchar(200) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, 'Abroor Rizky', 'data@gmail.com', '7c222fb2927d828af22f592134e8932480637c0d');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `portfolio`
--
ALTER TABLE `portfolio`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resume`
--
ALTER TABLE `resume`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimoni`
--
ALTER TABLE `testimoni`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `education`
--
ALTER TABLE `education`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `portfolio`
--
ALTER TABLE `portfolio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `resume`
--
ALTER TABLE `resume`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `testimoni`
--
ALTER TABLE `testimoni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
