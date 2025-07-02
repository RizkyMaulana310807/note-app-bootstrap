-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 11 Okt 2024 pada 02.38
-- Versi server: 8.0.37-0ubuntu0.24.04.1
-- Versi PHP: 8.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `note-app`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `Images`
--

CREATE TABLE `Images` (
  `id` int NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `tanggal_upload` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `warna` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id`, `nama`, `warna`) VALUES
(1, 'kerja', 'blue'),
(2, 'kerja', 'blue'),
(3, 'kerja', 'blue'),
(4, 'kerja', 'blue'),
(5, 'kerja', 'blue'),
(6, 'kerja', 'blue');

-- --------------------------------------------------------

--
-- Struktur dari tabel `Labels`
--

CREATE TABLE `Labels` (
  `id_label` int NOT NULL,
  `nama_label` varchar(50) NOT NULL,
  `warna` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `Labels`
--

INSERT INTO `Labels` (`id_label`, `nama_label`, `warna`) VALUES
(12, 'Kerja', 'blue'),
(13, 'Sekolah', 'yellow');

-- --------------------------------------------------------

--
-- Struktur dari tabel `Notes`
--

CREATE TABLE `Notes` (
  `id_catatan` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `tanggal_buat` datetime DEFAULT CURRENT_TIMESTAMP,
  `tanggal_ubah` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_label` int DEFAULT NULL,
  `bg_color` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `Notes`
--

INSERT INTO `Notes` (`id_catatan`, `judul`, `isi`, `tanggal_buat`, `tanggal_ubah`, `id_label`, `bg_color`) VALUES
(13, 'AAA', 'asd', '2024-10-11 09:14:52', '2024-10-11 09:14:52', 12, 'blue'),
(14, 'BBB', 'asddwae', '2024-10-11 09:15:45', '2024-10-11 09:15:45', 13, 'green'),
(15, 'zzz', 'adasdasd', '2024-10-11 09:15:55', '2024-10-11 09:15:55', 13, 'orange'),
(16, 'dfsdf', 'adswae', '2024-10-11 09:16:08', '2024-10-11 09:16:08', 12, 'purple');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `Images`
--
ALTER TABLE `Images`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `Labels`
--
ALTER TABLE `Labels`
  ADD PRIMARY KEY (`id_label`),
  ADD UNIQUE KEY `nama_label` (`nama_label`);

--
-- Indeks untuk tabel `Notes`
--
ALTER TABLE `Notes`
  ADD PRIMARY KEY (`id_catatan`),
  ADD KEY `id_label` (`id_label`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `Images`
--
ALTER TABLE `Images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `Labels`
--
ALTER TABLE `Labels`
  MODIFY `id_label` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `Notes`
--
ALTER TABLE `Notes`
  MODIFY `id_catatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
