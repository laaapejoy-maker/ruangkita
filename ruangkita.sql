-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Jun 2026 pada 14.35
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

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
-- Struktur dari tabel `bookings`
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
-- Dumping data untuk tabel `bookings`
--

INSERT INTO `bookings` (`id`, `nama`, `prodi`, `email`, `ruangan_nama`, `checkin`, `checkout`, `created_at`, `angkatan`, `kelas`, `keperluan_booking`, `status`) VALUES
(9, 'amirul', 'PSTI', 'am@gmail.com', 'RUANG B', '2026-05-01 01:00:00', '2026-05-01 02:00:00', '2026-05-07 08:57:35', '24', '4A', 'kelas', 'disetujui'),
(10, 'Amirul Muhammad Rabbani', 'PSTI', 'amirulmr@student.upi.edu', 'RUANG A', '2026-06-07 08:06:00', '2026-06-07 17:16:00', '2026-05-28 13:04:19', '24', '4A', 'Biasaalah Anak Muda', 'disetujui'),
(11, 'Amirul Muhammad Rabbani', 'PSTI', 'amirulmr@student.upi.edu', 'RUANG A', '2026-06-08 08:06:00', '2026-06-08 17:16:00', '2026-05-28 13:05:00', '24', '4A', 'Biasaalah Anak Muda', 'disetujui'),
(12, 'Amiruo Muhammad Rabbani', 'PSTI', 'a@gmail.com', 'RUANG A', '2026-05-30 16:02:00', '2026-05-30 17:03:00', '2026-05-29 09:02:53', '24', '4A', 'Mau make buat dugem', 'disetujui'),
(13, 'Amirul Muhammad Rabbani', 'PSTI', 'amirulmr@student.upi.edu', 'RUANG B', '2026-06-05 10:00:00', '2026-06-05 13:40:00', '2026-06-03 06:41:32', '24', '4A', 'Buat Cokber', 'pending'),
(14, 'Amirul Muhammad Rabbani', 'PSTI', 'irgiebintang7@gmail.com', 'RUANG C', '2026-06-04 13:40:00', '2026-06-04 13:46:00', '2026-06-03 06:46:34', '24', '4A', 'Iclick', 'pending'),
(15, 'ammirr', 'PSTI', 'a@gmail.com', 'RUANG B', '2026-06-04 13:49:00', '2026-06-04 13:50:00', '2026-06-03 06:50:01', '24', '4A', 'asdasd', 'pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ruangan`
--

CREATE TABLE `ruangan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `kapasitas` int(11) DEFAULT NULL,
  `fasilitas` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ruangan`
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
-- Struktur dari tabel `users`
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
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `jurusan`, `angkatan`, `kelas`, `role`) VALUES
(1, 'zira', 'ra@gmail.com', '$2y$10$4Ktu3uDVTl8nlECuvPtK1.euFr/GqXfXWwVeS28HDOMDZjeXs0zjC', 'PSTI', '2024', '4A', 'user'),
(2, 'dea', 'dea@gmail.com', '$2y$10$N47IgMEg8ll70iJuKrM.COcjvFyUFN6XArocrWROUMnymSPL.b8XO', 'MKB', '2024', '4B', 'user'),
(3, 'lita', 'lita@gmail.com', '$2y$10$jwYRn2N.XTsbguYj8zmPa.U8gsnYLNj3F4vKBgX9LROvt0O4Ti3FK', 'SISTEL', '2023', '6A', 'user'),
(4, 'amirul', 'amir@gmail.com', '$2y$10$ASecLXAaKNX1V35qHxUjy.Sk/VIddy2n4sH7UbGGUaVjyjqAFrzAK', 'PSTI', '2025', '2A', 'user'),
(5, 'amir', 'amira@gmail.com', '$2y$10$8NkUfNCLF/y.dHPNl8PXu.zHCGyAVkHjVIUnfWeOPtMnVhLYOVevS', 'PSTI', '2025', '2A', 'user'),
(6, 'amirulmr', 'amirulmr@student.upi.edu', '$2y$10$3KzlDwO31.t7KEg.xHU07Ow9znp3iKPUQoTnCHAHZbRPRDeXBKV6S', 'PSTI', '2024', '4A', 'admin'),
(7, 'aril', 'amirul@gmaill.com', '$2y$10$zn8mGmaCwdkuD6OTDXF6Iu9MUMLA3t4WNQd6ItDHERhmZhdOQ4Sye', 'PSTI', '2024', '4', 'user'),
(8, 'ammirr', 'a@gmail.com', '$2y$10$R.YCFgkeZ0.iBFmXxJqDy.NiiUVprN/JP6MEzTBYH9e6o5/TeERNe', 'PSTI', '2024', '4', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
