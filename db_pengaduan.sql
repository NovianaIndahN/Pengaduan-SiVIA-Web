-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 14, 2026 at 09:40 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pengaduan`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengaduan`
--

CREATE TABLE `tb_pengaduan` (
  `id_pengaduan` int NOT NULL,
  `id_user` int NOT NULL,
  `tgl_pengaduan` date NOT NULL,
  `judul` varchar(100) NOT NULL,
  `isi_laporan` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('menunggu','diproses','selesai') NOT NULL DEFAULT 'menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_pengaduan`
--

INSERT INTO `tb_pengaduan` (`id_pengaduan`, `id_user`, `tgl_pengaduan`, `judul`, `isi_laporan`, `foto`, `status`) VALUES
(1, 1, '2026-09-23', 'Lampu Jalan Mati', 'Lampu di depan gapura utama mati sejak kemarin', NULL, 'selesai'),
(2, 1, '2026-09-23', 'Ada Jalan Berlubang di jalan Rt 20', 'dijalan Rt 20 ada 2 lubang yang sangat mengganggu untuk dilewati pengendara motor maupun mobil', '1790171345_WhatsApp Image 2026-09-23 at 8.04.22 PM.jpeg', 'selesai'),
(3, 1, '2026-09-30', 'Ada Klitih di jalan Yang ga pernah ada 07', 'Entah kapan itu ada klitih dijalan situ main celurit celutitan mohon ditindak', '1790753400_🖤.jpg', 'diproses'),
(4, 1, '2026-10-01', 'ada ular besar digorong gorong', 'blabalablabala', '1790822791_WhatsApp Image 2026-09-30 at 1.58.15 PM.jpeg', 'menunggu'),
(5, 1, '2026-10-14', 'ada pohon tumabng', 'pohon tumbang dekat selokan jalan raya', '1791967979_kartunwebpns.png', 'menunggu');

-- --------------------------------------------------------

--
-- Table structure for table `tb_tanggapan`
--

CREATE TABLE `tb_tanggapan` (
  `id_tanggapan` int NOT NULL,
  `id_pengaduan` int NOT NULL,
  `id_user` int NOT NULL,
  `tgl_tanggapan` datetime NOT NULL,
  `isi_tanggapan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_tanggapan`
--

INSERT INTO `tb_tanggapan` (`id_tanggapan`, `id_pengaduan`, `id_user`, `tgl_tanggapan`, `isi_tanggapan`) VALUES
(1, 2, 1, '2026-09-23 00:00:00', 'woilahhh'),
(2, 2, 2, '2026-09-23 00:00:00', 'sabar pak'),
(3, 2, 1, '2026-10-01 00:00:00', 'yo yo');

-- --------------------------------------------------------

--
-- Table structure for table `tb_users`
--

CREATE TABLE `tb_users` (
  `id_user` int NOT NULL,
  `nik` varchar(16) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telp` varchar(15) NOT NULL,
  `role` enum('warga','admin','teknisi') NOT NULL DEFAULT 'warga'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_users`
--

INSERT INTO `tb_users` (`id_user`, `nik`, `nama`, `password`, `telp`, `role`) VALUES
(1, '1111111111111111', 'Budi (Warga)', '202cb962ac59075b964b07152d234b70', '081200000001', 'warga'),
(2, '2222222222222222', 'Pak RT (Admin)', '202cb962ac59075b964b07152d234b70', '081200000002', 'admin'),
(3, '3333333333333333', 'Joko (Teknisi)', '202cb962ac59075b964b07152d234b70', '081200000003', 'teknisi'),
(4, '0909090909090909', 'Novivi', '202cb962ac59075b964b07152d234b70', '087813661831', 'admin'),
(5, '1234123412341234', 'Mamat', '202cb962ac59075b964b07152d234b70', '', 'warga'),
(6, '3456345634563456', 'Tata', '202cb962ac59075b964b07152d234b70', '08674235467', 'warga');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_pengaduan`
--
ALTER TABLE `tb_pengaduan`
  ADD PRIMARY KEY (`id_pengaduan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_tanggapan`
--
ALTER TABLE `tb_tanggapan`
  ADD PRIMARY KEY (`id_tanggapan`),
  ADD KEY `id_pengaduan` (`id_pengaduan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_pengaduan`
--
ALTER TABLE `tb_pengaduan`
  MODIFY `id_pengaduan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_tanggapan`
--
ALTER TABLE `tb_tanggapan`
  MODIFY `id_tanggapan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_users`
--
ALTER TABLE `tb_users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_pengaduan`
--
ALTER TABLE `tb_pengaduan`
  ADD CONSTRAINT `fk_pengaduan_user` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_tanggapan`
--
ALTER TABLE `tb_tanggapan`
  ADD CONSTRAINT `fk_tanggapan_pengaduan` FOREIGN KEY (`id_pengaduan`) REFERENCES `tb_pengaduan` (`id_pengaduan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tanggapan_user` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
