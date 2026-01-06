-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2026 at 04:47 PM
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
-- Database: `restoran`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `catatan_item` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail`, `id_pesanan`, `id_menu`, `qty`, `harga_satuan`, `subtotal`, `catatan_item`) VALUES
(2, 2, 3, 2, 17000.00, 34000.00, NULL),
(3, 1, 4, 1, 95000.00, 95000.00, NULL),
(4, 3, 4, 1, 95000.00, 95000.00, NULL),
(5, 4, 4, 1, 95000.00, 95000.00, NULL),
(6, 5, 4, 1, 95000.00, 95000.00, NULL),
(7, 6, 4, 1, 95000.00, 95000.00, NULL),
(8, 7, 4, 1, 95000.00, 95000.00, NULL),
(10, 9, 4, 1, 95000.00, 95000.00, NULL),
(15, 16, 4, 1, 95000.00, 95000.00, NULL),
(16, 16, 2, 1, 15000.00, 15000.00, NULL),
(18, 19, 4, 2, 95000.00, 190000.00, NULL),
(19, 19, 3, 1, 17000.00, 17000.00, NULL),
(21, 15, 24, 1, 43000.00, 43000.00, NULL),
(22, 15, 20, 1, 38000.00, 38000.00, NULL),
(23, 15, 19, 1, 42000.00, 42000.00, NULL),
(24, 15, 22, 1, 58000.00, 58000.00, NULL),
(25, 15, 10, 1, 104000.00, 104000.00, NULL),
(26, 15, 11, 1, 150000.00, 150000.00, NULL),
(27, 14, 22, 2, 58000.00, 116000.00, NULL),
(28, 14, 9, 1, 100000.00, 100000.00, NULL),
(29, 14, 13, 1, 161000.00, 161000.00, NULL),
(30, 14, 17, 1, 55000.00, 55000.00, NULL),
(31, 14, 16, 1, 18000.00, 18000.00, NULL),
(32, 30, 24, 1, 43000.00, 43000.00, NULL),
(33, 30, 23, 2, 48000.00, 96000.00, NULL),
(34, 30, 21, 1, 22000.00, 22000.00, NULL),
(35, 30, 20, 2, 38000.00, 76000.00, NULL),
(36, 30, 14, 2, 141000.00, 282000.00, NULL),
(37, 31, 23, 4, 48000.00, 192000.00, NULL),
(38, 31, 19, 1, 42000.00, 42000.00, NULL),
(39, 31, 18, 2, 42000.00, 84000.00, NULL),
(40, 31, 9, 2, 100000.00, 200000.00, NULL),
(41, 31, 2, 1, 15000.00, 15000.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `deskripsi`, `created_at`) VALUES
(1, 'Main Dishes', 'Menu makanan utama', '2025-12-29 19:57:55'),
(2, 'Side Snacks', 'Menu cemilan', '2025-12-29 19:57:55'),
(3, 'Drinks', 'Menu minuman', '2025-12-29 19:57:55');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('utama','admin','kasir','customer') NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id_user`, `nama`, `email`, `username`, `password`, `role`, `no_hp`, `status`, `created_at`) VALUES
(1, 'Revani Nurul Fadilla', 'revaninurul@gmail.com', 'revaninf', 'revani123', 'admin', '08432846123', 'aktif', '2025-12-27 16:49:15'),
(2, 'Queen Nara Salzabilla', 'queennaras@gmail.com', 'queenns', 'queen123', 'admin', '084324632', 'aktif', '2025-12-27 16:49:15'),
(3, 'Syahnata', 'syahnata11@gmail.com', 'syahnata11', 'nata123', 'customer', '083421647324', 'aktif', '2025-12-29 19:28:08'),
(5, 'Fathan Qindi', 'fathanqindi12@gmail.com', 'fathanq', 'fathan11', 'customer', '0832163743', 'aktif', '2025-12-30 15:37:14'),
(19, 'Alaska', 'alaskaa12@gmail.com', 'alaskaa1', 'ala123', 'customer', '0848327954', 'aktif', '2026-01-04 10:22:39'),
(24, 'Nuke Aprianda', NULL, 'nukeap', '$2y$10$kyxlrfSIP7us5M4j6a37VegNspo9RSMncV8jrXXmV376nBzfZhPV.', 'kasir', '081211642183', 'aktif', '2026-01-04 10:47:23'),
(27, 'david', 'davdavid@gmail.com', 'david12', 'david1234', 'customer', '0843284546', 'aktif', '2026-01-04 11:01:07'),
(28, 'Chintiya Darmayanti', NULL, 'chintiya29', 'pass123', 'kasir', '0864284827', 'aktif', '2026-01-04 17:14:29'),
(29, 'Vina Anggreini', NULL, 'vina', '$2y$10$2iHehOyFEmUjMEV5mRimIeEoMwesEThNRyzk5OQ7bOzZ52oa2tnU2', 'kasir', '0862736427', 'aktif', '2026-01-04 17:15:05'),
(30, 'Jule', 'jule087@gmail.com', 'jule2', '87621', 'customer', '08354713463', 'aktif', '2026-01-04 17:16:02'),
(31, 'Dwi', 'dwiiii127@gmail.com', 'dwi562', '67362', 'customer', '0836163444', 'aktif', '2026-01-04 17:16:51'),
(32, 'nurul zize', 'nurr311@gmail.com', 'nurulll17', '756563', 'customer', '08516561533', 'aktif', '2026-01-04 17:17:24'),
(33, 'Aril', 'aril354@gmail.com', 'aril278', '4716748', 'customer', '08234526354', 'aktif', '2026-01-04 17:18:05');

-- --------------------------------------------------------

--
-- Table structure for table `meja`
--

CREATE TABLE `meja` (
  `id_meja` int(11) NOT NULL,
  `nomor_meja` varchar(20) NOT NULL,
  `kapasitas` int(11) NOT NULL,
  `status` enum('kosong','terisi','reservasi') DEFAULT 'kosong',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meja`
--

INSERT INTO `meja` (`id_meja`, `nomor_meja`, `kapasitas`, `status`, `created_at`) VALUES
(1, 'A1', 2, 'reservasi', '2025-12-30 16:13:34'),
(2, 'A2', 2, 'kosong', '2025-12-30 16:13:34'),
(3, 'B1', 4, 'terisi', '2025-12-30 16:13:34'),
(4, 'B2', 4, 'terisi', '2025-12-30 16:13:34'),
(5, 'C1', 8, 'terisi', '2025-12-30 16:13:34'),
(6, 'C2', 8, 'terisi', '2025-12-30 16:13:34'),
(7, 'D1', 15, 'kosong', '2026-01-03 17:50:52'),
(8, 'D2', 15, 'kosong', '2026-01-03 17:50:52'),
(9, 'D3', 15, 'kosong', '2026-01-03 17:50:52'),
(10, 'A3', 2, 'terisi', '2026-01-03 17:50:52'),
(11, 'A4', 2, 'kosong', '2026-01-03 17:50:52'),
(12, 'A5', 2, 'kosong', '2026-01-03 17:50:52'),
(13, 'A6', 2, 'terisi', '2026-01-03 17:50:52'),
(14, 'B3', 4, 'kosong', '2026-01-03 17:50:52'),
(15, 'B4', 4, 'terisi', '2026-01-03 17:50:52'),
(16, 'B5', 4, 'kosong', '2026-01-03 17:50:52'),
(17, 'B6', 4, 'kosong', '2026-01-03 17:50:52'),
(18, 'B7', 4, 'kosong', '2026-01-03 17:50:52'),
(19, 'B8', 4, 'kosong', '2026-01-03 17:50:52'),
(20, 'C3', 8, 'terisi', '2026-01-03 17:50:52'),
(21, 'C4', 8, 'kosong', '2026-01-03 17:50:52'),
(22, 'C5', 8, 'kosong', '2026-01-03 17:50:52'),
(23, 'C6', 8, 'reservasi', '2026-01-03 17:50:52'),
(24, 'C7', 8, 'kosong', '2026-01-03 17:50:52');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('tersedia','habis') DEFAULT 'tersedia',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `id_kategori`, `nama_menu`, `harga`, `deskripsi`, `foto`, `status`, `created_at`) VALUES
(2, 2, 'French Fries', 15000.00, 'Kentang goreng crispy', 'french fries.jpg\r\n', 'tersedia', '2025-12-29 20:29:00'),
(3, 3, 'Ice Lemon Tea', 17000.00, 'Teh lemon segar', 'Lemon Tea.jpg', 'tersedia', '2025-12-29 20:29:00'),
(4, 1, 'Steak Sirloin', 95000.00, 'Daging sapi dengan lemak dipinggir, tekstur lembut dan cita rasa yang gurih', '1767256149_sirloin.png', 'tersedia', '2026-01-01 15:29:09'),
(7, 1, 'Prime Tenderloin', 122000.00, 'Daging sapi tanpa lemak dengan tekstur paling lembut dan empuk, tepat untuk anda pecinta steak.', '1767517508_tenderloin.jpeg', 'tersedia', '2026-01-04 16:05:08'),
(8, 1, 'Prime Special Sirloin With Rice', 99000.00, 'Potongan daging steak yang dimasak dengan dua saus berbeda menghadirkan rasa istimewa.', '1767517657_spesial sirloin with rice.jpeg', 'tersedia', '2026-01-04 16:07:37'),
(9, 1, 'Prime Rib Eye', 100000.00, 'Tekstur lembut dengan sedikit lemak ditengah memberikan sensasi gurih dari prime rib eye steak Two Cousin, menyajikan kelezatan yang wajib dicoba.', '1767517852_rib eye.jpeg', 'tersedia', '2026-01-04 16:10:52'),
(10, 1, 'Prime Sirloin Original', 104000.00, 'Dengan tambahan butter dot, daging sapi dengan lemak dipinggir menambah citarasa daging semakin beraroma dan gurih', '1767517961_sirloin original.jpeg', 'tersedia', '2026-01-04 16:12:41'),
(11, 1, 'Supreme Tenderloin', 150000.00, 'Daging sapi yang diolah dengan teknologi jepang. sehingga menghasilkan teksture empuk dan citarasa gurih.', '1767518304_supreme tanderloin.jpeg', 'tersedia', '2026-01-04 16:18:24'),
(12, 1, 'Supreme Sirloin', 130000.00, 'Daging sapi yang diolah dengan teknologi jepang, sehingga menghasilkan teksture empuk dan citarasa gurih.', '1767518412_supreme sirloin.jpeg', 'tersedia', '2026-01-04 16:20:12'),
(13, 1, 'Truffle Exclusif Tenderloin', 161000.00, 'Daging sapi tanpa lemak yang lembut, dipadukan dengan lezatnya Truffle sause', '1767518586_tuffle exclusive tanderloin.jpeg', 'tersedia', '2026-01-04 16:23:06'),
(14, 1, 'Truffle Exclusive Sirloin', 141000.00, 'Daging sapi berlemak yang juicy, dipadukan dengan lezatnya truffle sauce', '1767518691_tuffle exclusive sirloin.jpeg', 'tersedia', '2026-01-04 16:24:51'),
(15, 1, 'Tuffle Exclusive Rib Eye', 142000.00, 'Tekstur empuk daging dan lemak di bagian tengah, dipadukan dengan lezatnya truffle sauce', '1767518810_tuffle exclusive rib eye.jpeg', 'tersedia', '2026-01-04 16:26:50'),
(16, 3, 'Ice Tea', 18000.00, 'perpaduan teh pilihan dengan rasa manis ringan, disajikan dingin untuk kesegaran setiap saat.', '1767520174_ice tea.png', 'tersedia', '2026-01-04 16:49:34'),
(17, 3, 'air mineral', 55000.00, 'air putih pilihan yg segar', '1767520225_AIR MINERAL EQUIL.jpg', 'tersedia', '2026-01-04 16:50:25'),
(18, 3, 'Smoothies Mangga', 42000.00, 'perpaduan mangga pilihan dengan tekstur lembut dan rasa manis segar cocok untuk dinikmati kapan saja.', '1767520351_Smoothies Mangga yang rasanya lumer di mulut! 📲Cari Kesegaran lainnya pada aplikasi kami 👉 Link di bio_  BAHA.jpg', 'tersedia', '2026-01-04 16:52:31'),
(19, 3, 'Smoothies Strawberry', 42000.00, 'perpaduan strawberry pilihan dengan tekstur lembut dan rasa fruity yang manis segar.', '1767520463_Delicious Strawberry Smoothie Recipe for Happy Snacking.jpg', 'tersedia', '2026-01-04 16:54:23'),
(20, 3, 'Blueberry Lavender Iced Tea', 38000.00, 'ice tea segar dengan paduan blueberry manis dan aroma lavender yang lembut.', '1767520573_download (1).jpg', 'tersedia', '2026-01-04 16:56:13'),
(21, 3, 'Ice Cream (Vanilla, Strawberry, dan Coklat)', 22000.00, 'Ice Cream berkualitas dengan tekstur halus dan creamy, memberikan sensasi manis dan lembut.', '1767520707_Ice Cream.jpg', 'tersedia', '2026-01-04 16:58:27'),
(22, 2, 'Signature French Fries', 58000.00, 'French Fries dipadu dengan taburan daging sapi yang gurih.', '1767521220_signature-french-fries.jpg', 'tersedia', '2026-01-04 17:06:03'),
(23, 2, 'Beef Taco', 48000.00, 'kulit tortilla renyah berisi daging sapi berbumbu rempah', '1767521332_beef-taco.jpg', 'tersedia', '2026-01-04 17:08:52'),
(24, 2, 'Nachos With Cheese', 43000.00, 'keripik tortilla yang crunchy dengan saus keju.', '1767521541_nachos-with-cheese.jpg', 'tersedia', '2026-01-04 17:12:21');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `metode` enum('cash','qris','debit','transfer') NOT NULL,
  `total_bayar` decimal(12,2) NOT NULL,
  `uang_diterima` decimal(12,2) NOT NULL,
  `kembalian` decimal(12,2) NOT NULL,
  `status_bayar` enum('lunas','belum') DEFAULT 'lunas',
  `tanggal_bayar` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_pesanan`, `metode`, `total_bayar`, `uang_diterima`, `kembalian`, `status_bayar`, `tanggal_bayar`) VALUES
(1, 2, 'cash', 34000.00, 35000.00, 1000.00, 'lunas', '2026-01-01 22:16:42'),
(4, 1, 'cash', 95000.00, 100000.00, 5000.00, 'lunas', '2026-01-01 22:39:02'),
(8, 3, 'cash', 95000.00, 100000.00, 5000.00, 'lunas', '2026-01-01 22:41:04'),
(9, 4, 'cash', 95000.00, 100000.00, 5000.00, 'lunas', '2026-01-01 22:46:56'),
(10, 5, 'cash', 95000.00, 100000.00, 5000.00, 'lunas', '2026-01-02 07:04:21'),
(14, 6, 'cash', 95000.00, 95000.00, 0.00, 'lunas', '2026-01-02 07:24:19'),
(15, 7, 'debit', 95000.00, 95000.00, 0.00, 'lunas', '2026-01-02 08:03:58'),
(17, 9, 'debit', 95000.00, 95000.00, 0.00, 'lunas', '2026-01-02 08:05:39'),
(19, 16, 'cash', 110000.00, 150000.00, 40000.00, 'lunas', '2026-01-03 11:50:54'),
(20, 19, 'cash', 207000.00, 250000.00, 43000.00, 'lunas', '2026-01-03 18:32:12'),
(21, 15, 'qris', 435000.00, 435000.00, 0.00, 'lunas', '2026-01-04 17:22:15'),
(22, 14, 'cash', 450000.00, 500000.00, 50000.00, 'lunas', '2026-01-04 17:23:42'),
(23, 30, 'transfer', 519000.00, 519000.00, 0.00, 'lunas', '2026-01-04 17:24:33'),
(24, 31, 'debit', 533000.00, 533000.00, 0.00, 'lunas', '2026-01-04 17:25:30');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `id_meja` int(11) DEFAULT NULL,
  `id_kasir` int(11) DEFAULT NULL,
  `id_customer` int(11) DEFAULT NULL,
  `nama_customer` varchar(100) DEFAULT NULL,
  `tanggal_pesan` datetime DEFAULT current_timestamp(),
  `status_pesanan` enum('pending','diproses','selesai','reservasi','batal') DEFAULT 'pending',
  `total_harga` decimal(12,2) DEFAULT 0.00,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_meja`, `id_kasir`, `id_customer`, `nama_customer`, `tanggal_pesan`, `status_pesanan`, `total_harga`, `catatan`) VALUES
(1, 2, NULL, 5, NULL, '2025-12-31 19:00:00', 'selesai', 95000.00, ''),
(2, 1, NULL, 5, NULL, '2025-12-31 10:58:40', 'selesai', 34000.00, ''),
(3, NULL, NULL, NULL, NULL, '2026-01-01 22:40:31', 'selesai', 95000.00, NULL),
(4, NULL, NULL, NULL, NULL, '2026-01-01 22:46:39', 'selesai', 95000.00, NULL),
(5, NULL, NULL, NULL, NULL, '2026-01-02 07:03:52', 'selesai', 95000.00, NULL),
(6, NULL, NULL, NULL, NULL, '2026-01-02 07:23:50', 'selesai', 95000.00, NULL),
(7, NULL, NULL, NULL, NULL, '2026-01-02 07:32:54', 'selesai', 95000.00, NULL),
(9, NULL, NULL, NULL, NULL, '2026-01-02 08:05:18', 'selesai', 95000.00, NULL),
(14, 4, NULL, 5, NULL, '2026-01-07 16:41:00', 'selesai', 450000.00, ''),
(15, 3, NULL, 5, NULL, '2026-01-03 11:47:53', 'selesai', 435000.00, ''),
(16, 5, NULL, 5, NULL, '2026-01-03 11:50:15', 'selesai', 110000.00, 'saos BBQ'),
(17, 1, NULL, NULL, NULL, '2026-01-07 13:00:00', '', 0.00, 'minta kursi bayi'),
(18, 6, NULL, 5, 'Fathan Qindi', '2026-01-07 14:06:00', '', 0.00, ''),
(19, 6, NULL, 5, NULL, '2026-01-10 13:08:00', 'selesai', 207000.00, '2'),
(20, 1, NULL, NULL, 'Queen', '2026-01-07 15:15:00', '', 0.00, ''),
(21, 1, NULL, NULL, 'Queen', '2026-01-07 13:12:00', '', 0.00, '3'),
(27, 23, NULL, 3, NULL, '2026-01-09 18:54:00', 'reservasi', 0.00, 'minta kursi bayi 3'),
(30, 15, NULL, NULL, NULL, '2026-01-04 17:23:45', 'selesai', 519000.00, NULL),
(31, 20, NULL, NULL, NULL, '2026-01-04 17:24:37', 'selesai', 533000.00, NULL),
(32, NULL, NULL, NULL, NULL, '2026-01-04 17:25:34', 'pending', 0.00, NULL),
(33, 10, NULL, NULL, 'Dwi', '2026-01-11 19:00:00', 'reservasi', 0.00, ''),
(34, 2, NULL, NULL, 'Alaska', '2026-01-17 20:30:00', 'reservasi', 0.00, '');

-- --------------------------------------------------------

--
-- Table structure for table `stok`
--

CREATE TABLE `stok` (
  `id_stok` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `jumlah_stok` int(11) NOT NULL DEFAULT 0,
  `stok_minimum` int(11) NOT NULL DEFAULT 0,
  `satuan` varchar(20) DEFAULT 'porsi',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stok`
--

INSERT INTO `stok` (`id_stok`, `id_menu`, `jumlah_stok`, `stok_minimum`, `satuan`, `updated_at`) VALUES
(6, 24, 50, 10, 'porsi', '2026-01-05 21:00:58'),
(7, 23, 45, 10, 'porsi', '2026-01-05 22:21:13'),
(8, 22, 54, 10, 'porsi', '2026-01-05 22:21:30'),
(9, 21, 20, 5, 'porsi', '2026-01-05 22:21:47'),
(10, 20, 100, 20, 'porsi', '2026-01-05 22:22:14'),
(11, 19, 80, 10, 'porsi', '2026-01-05 22:22:35'),
(12, 7, 45, 10, 'porsi', '2026-01-05 22:23:02'),
(13, 4, 48, 10, 'porsi', '2026-01-05 22:23:23'),
(14, 2, 120, 10, 'porsi', '2026-01-05 22:23:38'),
(15, 17, 500, 50, 'porsi', '2026-01-05 22:24:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `idx_detail_menu` (`id_menu`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `meja`
--
ALTER TABLE `meja`
  ADD PRIMARY KEY (`id_meja`),
  ADD UNIQUE KEY `nomor_meja` (`nomor_meja`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD UNIQUE KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `idx_pembayaran_tanggal` (`tanggal_bayar`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_meja` (`id_meja`),
  ADD KEY `id_kasir` (`id_kasir`),
  ADD KEY `id_customer` (`id_customer`),
  ADD KEY `idx_pesanan_tanggal` (`tanggal_pesan`);

--
-- Indexes for table `stok`
--
ALTER TABLE `stok`
  ADD PRIMARY KEY (`id_stok`),
  ADD UNIQUE KEY `id_menu` (`id_menu`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `meja`
--
ALTER TABLE `meja`
  MODIFY `id_meja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `stok`
--
ALTER TABLE `stok`
  MODIFY `id_stok` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON UPDATE CASCADE;

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_meja`) REFERENCES `meja` (`id_meja`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`id_kasir`) REFERENCES `login` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pesanan_ibfk_3` FOREIGN KEY (`id_customer`) REFERENCES `login` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `stok`
--
ALTER TABLE `stok`
  ADD CONSTRAINT `stok_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
