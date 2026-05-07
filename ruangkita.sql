-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2026 at 10:28 AM
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
-- Database: `ruangkita`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `prodi` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `ruangan_nama` varchar(100) DEFAULT NULL,
  `checkin` datetime DEFAULT NULL,
  `checkout` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `angkatan` varchar(10) DEFAULT NULL,
  `kelas` varchar(20) DEFAULT NULL,
  `keperluan_booking` text DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `nama`, `prodi`, `email`, `checkin`, `checkout`, `created_at`, `angkatan`, `kelas`, `keperluan_booking`) VALUES
(1, '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2026-04-22 02:47:53', NULL, NULL, NULL),
(2, 'uytr', 'ytre', 'elitasnm@gmail.com', '2026-04-23 09:00:00', '2026-04-23 18:00:00', '2026-04-22 02:52:30', NULL, NULL, NULL),
(3, 'dea', 'psti', 'dea@gmail.com', '2026-04-22 10:00:00', '2026-04-22 18:00:00', '2026-04-22 03:02:36', NULL, NULL, NULL),
(4, 'lit', 'psti', 'elitsnm1246@upi.edu', '2026-04-24 10:00:00', '2026-04-24 18:00:00', '2026-04-22 03:13:46', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ruangan`
--

CREATE TABLE `ruangan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `kapasitas` int(11) DEFAULT NULL,
  `fasilitas` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ruangan`
--

INSERT INTO `ruangan` (`id`, `nama`, `kapasitas`, `fasilitas`, `gambar`) VALUES
(1, 'RUANG A', 40, 'AC, Proyektor, Whiteboard, Stop Kontak Lengkap', 'img/ruang_a.jpg'),
(2, 'RUANG B', 40, 'AC, Smartboard, Stop Kontak Lengkap', 'img/ruang_b.jpg'),
(3, 'RUANG C', 40, 'AC, TV, Whiteboard', 'img/ruang_c.jpg'),
(4, 'LAB A', 45, 'Komputer Lengkap, AC, Smartboard, Internet Stabil', 'img/lab_a.jpg'),
(5, 'LAB B', 45, 'Komputer Lengkap, AC, Smartboard, Internet Stabil, Audio', 'img/lab_b.jpg'),
(6, 'SMART CLASSROOM', 100, 'Smart TV, Komputer, Kamera, Mic Wireless, AC, Stop Kontak Lengkap', 'img/smartclass.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `angkatan` year(4) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `jurusan`, `angkatan`, `kelas`) VALUES
(1, 'zira', 'ra@gmail.com', '$2y$10$4Ktu3uDVTl8nlECuvPtK1.euFr/GqXfXWwVeS28HDOMDZjeXs0zjC', 'PSTI', '2024', '4A'),
(2, 'dea', 'dea@gmail.com', '$2y$10$N47IgMEg8ll70iJuKrM.COcjvFyUFN6XArocrWROUMnymSPL.b8XO', 'MKB', '2024', '4B'),
(3, 'lita', 'lita@gmail.com', '$2y$10$jwYRn2N.XTsbguYj8zmPa.U8gsnYLNj3F4vKBgX9LROvt0O4Ti3FK', 'SISTEL', '2023', '6A'),
(4, 'amirul', 'amir@gmail.com', '$2y$10$ASecLXAaKNX1V35qHxUjy.Sk/VIddy2n4sH7UbGGUaVjyjqAFrzAK', 'PSTI', '2025', '2A'),
(5, 'amir', 'amira@gmail.com', '$2y$10$8NkUfNCLF/y.dHPNl8PXu.zHCGyAVkHjVIUnfWeOPtMnVhLYOVevS', 'PSTI', '2025', '2A');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
