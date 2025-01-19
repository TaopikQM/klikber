-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2025 at 07:43 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `klikber`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `no_hp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `alamat`, `no_hp`) VALUES
(8, 'kiki', 'jalan k', 1233),
(9, 'Aji', 'pucang', 123),
(10, 'ari', 'jiku', 321);

-- --------------------------------------------------------

--
-- Table structure for table `daftar_poli`
--

CREATE TABLE `daftar_poli` (
  `id` int(11) NOT NULL,
  `id_pasien` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `keluhan` text NOT NULL,
  `no_antrian` int(11) DEFAULT NULL,
  `status_periksa` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daftar_poli`
--

INSERT INTO `daftar_poli` (`id`, `id_pasien`, `id_jadwal`, `keluhan`, `no_antrian`, `status_periksa`) VALUES
(19, 21, 14, 'tidak bisa mendengar 3 hari', 1, 1),
(20, 17, 14, 'susah menelan makanan', 2, 0),
(21, 21, 14, 'SAKIT TIDAK BISA MENDENGAR 3 HARI', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `detail_periksa`
--

CREATE TABLE `detail_periksa` (
  `id` int(11) NOT NULL,
  `id_periksa` int(11) NOT NULL,
  `id_obat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_periksa`
--

INSERT INTO `detail_periksa` (`id`, `id_periksa`, `id_obat`) VALUES
(9, 8, 3),
(17, 8, 11),
(18, 8, 18),
(19, 9, 7);

-- --------------------------------------------------------

--
-- Table structure for table `dokter`
--

CREATE TABLE `dokter` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `no_hp` int(15) NOT NULL,
  `id_poli` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dokter`
--

INSERT INTO `dokter` (`id`, `nama`, `alamat`, `no_hp`, `id_poli`) VALUES
(16, 'kiku', 'assasasa', 120121, 1),
(19, 'koki', 'nmnm', 90909, 1),
(20, 'Martin', 'jalan 098', 12134, 3),
(21, 'yono', 'jalan 23', 12134, 16),
(22, 'MITO', 'Jalan 129', 123455, 8),
(23, 'ISNO', 'JALAN OIU', 8787, 1),
(24, 'yatno', 'jalan 0888', 12312344, 8);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_periksa`
--

CREATE TABLE `jadwal_periksa` (
  `id` int(11) NOT NULL,
  `id_dokter` int(11) NOT NULL,
  `hari` varchar(10) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `status` enum('active','inactive') DEFAULT 'inactive',
  `last_reset_day` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_periksa`
--

INSERT INTO `jadwal_periksa` (`id`, `id_dokter`, `hari`, `jam_mulai`, `jam_selesai`, `status`, `last_reset_day`) VALUES
(10, 19, 'Senin', '13:04:00', '13:08:00', 'active', '2024-12-19'),
(11, 19, 'Selasa', '16:09:00', '17:09:00', 'inactive', '2024-12-19'),
(12, 16, 'Senin', '07:00:00', '12:38:00', 'inactive', NULL),
(13, 16, 'Selasa', '15:45:00', '17:46:00', 'inactive', NULL),
(14, 22, 'Senin', '07:00:00', '11:55:00', 'active', '2024-12-27'),
(15, 22, 'Selasa', '09:00:00', '11:50:00', 'inactive', '2024-12-26'),
(16, 21, 'Senin', '07:00:00', '10:04:00', 'inactive', '2025-01-04'),
(17, 21, 'Selasa', '07:04:00', '10:05:00', 'active', '2025-01-04'),
(18, 22, 'Rabu', '08:13:00', '11:13:00', 'inactive', '2024-12-27'),
(21, 23, 'Senin', '19:17:00', '21:17:00', 'active', '2025-01-18'),
(24, 23, 'Selasa', '17:47:00', '20:10:00', 'inactive', NULL),
(25, 23, 'Rabu', '20:14:00', '21:14:00', 'inactive', NULL),
(26, 16, 'Rabu', '19:14:00', '20:14:00', 'inactive', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `konsultasi`
--

CREATE TABLE `konsultasi` (
  `id` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `pertanyaan` text NOT NULL,
  `jawaban` text NOT NULL,
  `tgl_konsultasi` datetime DEFAULT NULL,
  `id_pasien` int(11) NOT NULL,
  `id_dokter` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `konsultasi`
--

INSERT INTO `konsultasi` (`id`, `subject`, `pertanyaan`, `jawaban`, `tgl_konsultasi`, `id_pasien`, `id_dokter`) VALUES
(25, 'sakit ', 'tsesak napas', '', '2025-01-18 12:00:57', 21, 19),
(26, 'sakit gigi', 'gigi goyang 3 hari', 'm,ungkin anda perlu bernapas', '2025-01-18 12:01:43', 21, 23),
(27, 'GINJAL BOCOR', 'SOLUSI GINJAL BOCOR DIMANA?', '', '2025-01-18 13:06:15', 21, 23),
(28, 'sesak napas', 'saya susah napas 3 hari', '', '2025-01-18 13:30:30', 21, 23);

-- --------------------------------------------------------

--
-- Table structure for table `login_history`
--

CREATE TABLE `login_history` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `login_time` datetime DEFAULT current_timestamp(),
  `logout_time` datetime DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_history`
--

INSERT INTO `login_history` (`id`, `id_user`, `login_time`, `logout_time`, `ip_address`) VALUES
(90, 29, '2024-12-13 15:46:55', NULL, '127.0.0.1'),
(91, 29, '2024-12-13 16:22:00', NULL, '127.0.0.1'),
(92, 29, '2024-12-13 17:31:51', NULL, '127.0.0.1'),
(93, 29, '2024-12-13 18:49:21', '2024-12-13 19:38:33', '127.0.0.1'),
(94, 29, '2024-12-13 20:37:06', '2024-12-13 22:00:32', '127.0.0.1'),
(95, 29, '2024-12-13 22:10:46', NULL, '127.0.0.1'),
(96, 37, '2024-12-15 13:06:05', '2024-12-15 13:06:16', '127.0.0.1'),
(97, 40, '2024-12-15 13:06:47', NULL, '127.0.0.1'),
(98, 40, '2024-12-15 19:43:35', NULL, '127.0.0.1'),
(99, 40, '2024-12-17 08:25:12', NULL, '127.0.0.1'),
(100, 40, '2024-12-17 12:45:47', NULL, '127.0.0.1'),
(101, 40, '2024-12-17 14:28:00', NULL, '127.0.0.1'),
(102, 40, '2024-12-17 20:33:30', NULL, '127.0.0.1'),
(103, 40, '2024-12-18 06:47:12', NULL, '127.0.0.1'),
(104, 40, '2024-12-18 08:23:21', NULL, '127.0.0.1'),
(105, 38, '2024-12-19 09:13:58', '2024-12-19 09:14:46', '127.0.0.1'),
(106, 36, '2024-12-19 09:15:01', '2024-12-19 10:24:53', '127.0.0.1'),
(107, 40, '2024-12-19 10:25:17', '2024-12-19 12:00:37', '127.0.0.1'),
(108, 36, '2024-12-19 12:00:49', NULL, '127.0.0.1'),
(109, 29, '2024-12-19 13:22:33', '2024-12-19 13:22:46', '::1'),
(110, 35, '2024-12-19 13:59:12', NULL, '::1'),
(111, 36, '2024-12-19 14:47:44', NULL, '127.0.0.1'),
(112, 35, '2024-12-19 16:07:35', NULL, '::1'),
(113, 36, '2024-12-19 20:19:17', NULL, '127.0.0.1'),
(114, 36, '2024-12-19 22:51:32', NULL, '127.0.0.1'),
(115, 36, '2024-12-20 08:49:18', '2024-12-20 10:05:36', '127.0.0.1'),
(116, 41, '2024-12-20 10:49:08', '2024-12-20 10:50:26', '127.0.0.1'),
(117, 41, '2024-12-20 10:50:43', '2024-12-20 10:52:04', '127.0.0.1'),
(118, 40, '2024-12-20 10:53:23', NULL, '127.0.0.1'),
(119, 40, '2024-12-20 12:36:17', NULL, '127.0.0.1'),
(120, 40, '2024-12-20 17:16:56', NULL, '127.0.0.1'),
(121, 40, '2024-12-20 20:50:53', NULL, '127.0.0.1'),
(122, 40, '2024-12-21 03:05:31', NULL, '127.0.0.1'),
(123, 40, '2024-12-21 07:25:25', NULL, '127.0.0.1'),
(124, 40, '2024-12-21 14:10:23', NULL, '127.0.0.1'),
(125, 40, '2024-12-21 19:44:48', NULL, '127.0.0.1'),
(126, 40, '2024-12-22 09:43:56', NULL, '127.0.0.1'),
(127, 40, '2024-12-22 11:42:50', '2024-12-22 14:22:01', '127.0.0.1'),
(128, 40, '2024-12-22 14:22:21', '2024-12-22 14:22:33', '127.0.0.1'),
(129, 40, '2024-12-22 14:24:14', '2024-12-22 14:56:21', '127.0.0.1'),
(130, 40, '2024-12-22 14:56:51', '2024-12-22 15:04:24', '127.0.0.1'),
(131, 29, '2024-12-22 15:05:56', NULL, '127.0.0.1'),
(132, 40, '2024-12-22 15:30:47', NULL, '127.0.0.1'),
(133, 29, '2024-12-22 17:11:23', NULL, '127.0.0.1'),
(134, 29, '2024-12-22 21:02:05', NULL, '127.0.0.1'),
(135, 40, '2024-12-22 21:22:23', NULL, '127.0.0.1'),
(136, 40, '2024-12-23 00:41:24', NULL, '127.0.0.1'),
(137, 41, '2024-12-23 12:50:23', '2024-12-23 12:58:44', '127.0.0.1'),
(138, 29, '2024-12-23 12:59:04', NULL, '127.0.0.1'),
(139, 29, '2024-12-23 13:13:21', NULL, '127.0.0.1'),
(140, 29, '2024-12-23 15:57:39', NULL, '127.0.0.1'),
(141, 41, '2024-12-24 11:07:12', NULL, '127.0.0.1'),
(142, 41, '2024-12-24 11:12:29', NULL, '127.0.0.1'),
(143, 34, '2024-12-24 14:09:05', NULL, '127.0.0.1'),
(144, 41, '2024-12-25 10:54:12', NULL, '127.0.0.1'),
(145, 34, '2024-12-25 14:18:47', NULL, '127.0.0.1'),
(146, 34, '2024-12-25 16:14:06', NULL, '127.0.0.1'),
(147, 37, '2024-12-25 16:30:26', '2024-12-25 16:30:33', '127.0.0.1'),
(148, 29, '2024-12-25 16:31:22', NULL, '127.0.0.1'),
(149, 29, '2024-12-25 19:30:32', NULL, '127.0.0.1'),
(150, 29, '2024-12-26 00:58:43', NULL, '127.0.0.1'),
(151, 44, '2024-12-26 03:48:47', NULL, '127.0.0.1'),
(152, 43, '2024-12-26 04:04:09', NULL, '127.0.0.1'),
(153, 43, '2024-12-26 06:32:30', '2024-12-26 07:43:21', '127.0.0.1'),
(154, 44, '2024-12-26 06:35:22', NULL, '127.0.0.1'),
(155, 49, '2024-12-26 07:43:42', NULL, '127.0.0.1'),
(156, 44, '2024-12-26 08:14:30', NULL, '127.0.0.1'),
(157, 45, '2024-12-26 08:15:48', NULL, '127.0.0.1'),
(158, 45, '2024-12-26 15:58:30', '2024-12-26 16:08:26', '127.0.0.1'),
(159, 44, '2024-12-26 16:08:59', NULL, '127.0.0.1'),
(160, 44, '2024-12-26 19:43:54', NULL, '127.0.0.1'),
(161, 44, '2024-12-26 21:58:02', NULL, '127.0.0.1'),
(162, 44, '2024-12-27 11:19:51', NULL, '127.0.0.1'),
(163, 44, '2024-12-27 13:49:33', NULL, '127.0.0.1'),
(164, 44, '2024-12-27 16:09:11', NULL, '127.0.0.1'),
(165, 44, '2024-12-27 17:42:12', '2024-12-27 22:09:06', '127.0.0.1'),
(166, 49, '2024-12-27 19:29:42', NULL, '127.0.0.1'),
(167, 49, '2024-12-27 21:33:38', '2024-12-27 22:08:02', '127.0.0.1'),
(168, 49, '2024-12-27 22:10:11', NULL, '127.0.0.1'),
(169, 44, '2024-12-27 22:12:15', '2024-12-27 22:52:35', '127.0.0.1'),
(170, 43, '2025-01-04 19:16:56', '2025-01-04 19:18:25', '127.0.0.1'),
(171, 44, '2025-01-04 19:16:57', '2025-01-04 19:18:58', '127.0.0.1'),
(172, 34, '2025-01-04 19:18:43', NULL, '127.0.0.1'),
(173, 37, '2025-01-04 19:19:14', '2025-01-04 19:22:01', '127.0.0.1'),
(174, 29, '2025-01-04 19:22:25', '2025-01-04 19:23:22', '127.0.0.1'),
(175, 52, '2025-01-04 19:24:12', NULL, '127.0.0.1'),
(176, 52, '2025-01-05 18:44:58', NULL, '127.0.0.1'),
(177, 34, '2025-01-05 18:45:17', NULL, '127.0.0.1'),
(178, 52, '2025-01-05 23:37:54', '2025-01-05 23:49:10', '127.0.0.1'),
(179, 34, '2025-01-05 23:39:47', NULL, '127.0.0.1'),
(180, 44, '2025-01-05 23:49:49', NULL, '127.0.0.1'),
(181, 44, '2025-01-06 02:22:16', NULL, '127.0.0.1'),
(182, 34, '2025-01-06 02:38:47', NULL, '127.0.0.1'),
(183, 44, '2025-01-06 03:54:37', NULL, '127.0.0.1'),
(184, 49, '2025-01-18 08:50:49', NULL, '::1'),
(185, 50, '2025-01-18 10:25:52', '2025-01-18 11:23:10', '::1'),
(186, 45, '2025-01-18 11:23:26', NULL, '::1'),
(187, 49, '2025-01-18 12:33:16', '2025-01-18 12:42:56', '::1'),
(188, 52, '2025-01-18 12:43:08', NULL, '::1'),
(189, 49, '2025-01-18 13:03:57', NULL, '::1');

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `id` int(11) NOT NULL,
  `nama_obat` varchar(50) NOT NULL,
  `kemasan` varchar(35) DEFAULT NULL,
  `harga` int(10) UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`id`, `nama_obat`, `kemasan`, `harga`) VALUES
(3, 'oskadon', '0', 150000),
(6, 'Ramol', '1', 150000),
(7, 'Paracetamol', '0', 50000),
(8, 'Amoxicillin', '0', 8000),
(9, 'Cetirizine', '0', 7000),
(10, 'Ibuprofen', '0', 10000),
(11, 'Metformin', '0', 9000),
(12, 'Loperamide', '0', 6000),
(13, 'Omeprazole', '0', 12000),
(14, 'Aspirin', '0', 4000),
(15, 'Captopril', '0', 7500),
(16, 'Simvastatin', '0', 8500),
(17, 'Salbutamol Syrup', '1', 20000),
(18, 'Cough Syrup', '1', 15000),
(19, 'Vitamin C Syrup', '1', 18000),
(20, 'Antacid Suspension', '1', 13000),
(21, 'Multivitamin Syrup', '1', 22000),
(22, 'Dextromethorphan Syrup', '1', 14000),
(23, 'Ranitidine Syrup', '1', 17000),
(24, 'Antihistamine Syrup', '1', 16000),
(25, 'Ibuprofen Syrup', '1', 19000),
(26, 'Paracetamol Syrup', '1', 15000);

-- --------------------------------------------------------

--
-- Table structure for table `pasien`
--

CREATE TABLE `pasien` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_ktp` int(10) UNSIGNED NOT NULL,
  `no_hp` int(10) UNSIGNED NOT NULL,
  `no_rm` char(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pasien`
--

INSERT INTO `pasien` (`id`, `nama`, `alamat`, `no_ktp`, `no_hp`, `no_rm`) VALUES
(16, 'kokia', 'jalann', 4294967295, 1230000, '202412-016'),
(17, 'beben', 'jalan21', 123, 12333, '202412-017'),
(21, 'juni', 'jalanan', 321, 321, '202412-021');

-- --------------------------------------------------------

--
-- Table structure for table `periksa`
--

CREATE TABLE `periksa` (
  `id` int(11) NOT NULL,
  `id_daftar_poli` int(11) NOT NULL,
  `tgl_periksa` date NOT NULL,
  `catatan` text NOT NULL,
  `biaya_periksa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `periksa`
--

INSERT INTO `periksa` (`id`, `id_daftar_poli`, `tgl_periksa`, `catatan`, `biaya_periksa`) VALUES
(8, 19, '2024-12-30', 'obat diminum setelah makan 2x sehari', 324000),
(9, 21, '2024-12-30', 'MINUM 3X SEHARI', 200000);

-- --------------------------------------------------------

--
-- Table structure for table `poli`
--

CREATE TABLE `poli` (
  `id` int(11) NOT NULL,
  `nama_poli` varchar(25) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poli`
--

INSERT INTO `poli` (`id`, `nama_poli`, `keterangan`) VALUES
(1, 'Anak', 'ini anakwdw'),
(3, 'Dalam', 'organ dalam'),
(5, 'Poli Umum', 'Memberikan pelayanan kesehatan umum kepada pasien.'),
(6, 'Poli Anak', 'Pelayanan kesehatan khusus untuk anak-anak.'),
(7, 'Poli Gigi', 'Menangani permasalahan kesehatan gigi dan mulut.'),
(8, 'Poli THT', 'Pelayanan untuk kesehatan telinga, hidung, dan tenggorokan.'),
(9, 'Poli Jantung', 'Khusus menangani permasalahan kesehatan jantung dan pembuluh darah.'),
(10, 'Poli Mata', 'Pelayanan untuk kesehatan mata dan penglihatan.'),
(11, 'Poli Saraf', 'Menangani gangguan pada sistem saraf.'),
(12, 'Poli Kulit dan Kelamin', 'Khusus menangani kesehatan kulit dan kelamin.'),
(13, 'Poli Kandungan', 'Pelayanan untuk kesehatan ibu hamil dan kandungan.'),
(14, 'Poli Bedah', 'Memberikan pelayanan untuk tindakan bedah.'),
(15, 'Poli Gizi', 'Konsultasi dan layanan terkait kebutuhan gizi pasien.'),
(16, 'Poli Paru', 'Pelayanan untuk kesehatan paru-paru dan pernapasan.'),
(17, 'Poli Ortopedi', 'Khusus menangani kesehatan tulang dan sendi.'),
(18, 'Poli Psikiatri', 'Pelayanan untuk kesehatan mental dan psikologi.'),
(19, 'Poli Rehabilitasi Medis', 'Menangani pemulihan pasien setelah cedera atau operasi.'),
(20, 'Poli Penyakit Dalam', 'Pelayanan untuk gangguan organ dalam tubuh.'),
(21, 'Poli Endokrinologi', 'Menangani gangguan hormonal seperti diabetes.'),
(22, 'Poli Hematologi', 'Khusus menangani kesehatan darah dan kelainan darah.'),
(23, 'Poli Urologi', 'Pelayanan untuk masalah saluran kemih dan reproduksi pria.'),
(24, 'Poli Onkologi', 'Menangani pasien dengan penyakit kanker.');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `nama_role` varchar(50) NOT NULL,
  `keterangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `nama_role`, `keterangan`) VALUES
(1, 'Admin', 'Mengelola semua data'),
(2, 'Pasien', 'Mengelola data pasien'),
(3, 'Dokter', 'Mengelola data dokter');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `id_role`) VALUES
(29, 'A2024-008', '$2y$10$TI6iSLNygMgpvb7XBroXvOMDrrRBgYFhaJGxK2A9VCYDg/op/PEai', 1),
(34, 'D2024-016', '$2y$10$q8kODRbxvgJQ1cDHRIVrKe1dUzNuZw618j1mH7kza2Joiu6g4AYvu', 3),
(37, 'D2024-017', '$2y$10$3e8xtRQI8AzBYKBKGn6ET..KKgOuou69L3eaW607jXcUZkcwAhRE6', 3),
(39, 'D2024-017', '$2y$10$Dy8V83qC3fAFNC/nwrceiOKP4HI4TLyZHxawAHlAPpanER.xdiuda', 3),
(40, 'D2024-019', '$2y$10$2Q4OBeU6zuL8CudGuKuOzuURNXu3BCSsX6w/txnRAmluU0ke/CZFO', 3),
(41, 'P2024-016', '$2y$10$I7Vuzsj3J0u3ZTnCfdaize8zz.KfbU5LZ2x9kGKA.k3Ad4BDYnhm.', 2),
(42, 'D2024-020', '$2y$10$3R2y53uteAdKk.i7kdZMeug5bC4KTMu5IbWGyr5YvGJOwaLF.4KvO', 3),
(43, 'D2024-021', '$2y$10$zFNzOrw95j5nSQXMc38ipeRy9yx2Ql26tLy8wNWjZ1H.2i9LDHbLG', 3),
(44, 'D2024-022', '$2y$10$kI88VmmYyIzVKtxflfCxF.DoWf.rkkUKqKHGZ.HNUKcz2F40qUSZ6', 3),
(45, 'P2024-017', '$2y$10$rFbEkFG4rG8rSb/M.AG4IudM5GJD6i5mZLR3LP2f6heQ/tOHbdP5u', 2),
(49, 'P2024-021', '$2y$10$npBmpCrEFtM3qz6lPwGRd.aKL73fvMjOjhxrUld9YO/iNjn4CkYbq', 2),
(50, 'A2024-009', '$2y$10$sucaDwDGFJWVD4bvtADL4ueDPmlScTnf8lGzuTHuUaKy29FHR78s6', 1),
(51, 'A2024-010', '$2y$10$IqV.N8JTVxZvhtyrGW68DO6aTRmUgLhB3F2TEipsZDQMxzJa5DXsi', 1),
(52, 'D2025-023', '$2y$10$cn0qpTInvlfgitpwp6dalesipNOSEMpTA8MB3IGhKPNPLlCUGoNSK', 3),
(53, 'D2025-024', '$2y$10$DQR.lbEWQ1q/Jmn.HnNpk.yNxsxDqk1nJG5lZHedBphgGSxq9lJWG', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daftar_poli`
--
ALTER TABLE `daftar_poli`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pasien` (`id_pasien`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- Indexes for table `detail_periksa`
--
ALTER TABLE `detail_periksa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_periksa` (`id_periksa`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indexes for table `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_poli` (`id_poli`);

--
-- Indexes for table `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_dokter` (`id_dokter`);

--
-- Indexes for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pasien` (`id_pasien`),
  ADD KEY `id_dokter` (`id_dokter`);

--
-- Indexes for table `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `periksa`
--
ALTER TABLE `periksa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_daftar_poli` (`id_daftar_poli`);

--
-- Indexes for table `poli`
--
ALTER TABLE `poli`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_role` (`id_role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `daftar_poli`
--
ALTER TABLE `daftar_poli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `detail_periksa`
--
ALTER TABLE `detail_periksa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `konsultasi`
--
ALTER TABLE `konsultasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT for table `obat`
--
ALTER TABLE `obat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `periksa`
--
ALTER TABLE `periksa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `poli`
--
ALTER TABLE `poli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `daftar_poli`
--
ALTER TABLE `daftar_poli`
  ADD CONSTRAINT `daftar_poli_ibfk_1` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id`),
  ADD CONSTRAINT `daftar_poli_ibfk_2` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_periksa` (`id`);

--
-- Constraints for table `detail_periksa`
--
ALTER TABLE `detail_periksa`
  ADD CONSTRAINT `detail_periksa_ibfk_1` FOREIGN KEY (`id_periksa`) REFERENCES `periksa` (`id`),
  ADD CONSTRAINT `detail_periksa_ibfk_2` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id`);

--
-- Constraints for table `dokter`
--
ALTER TABLE `dokter`
  ADD CONSTRAINT `dokter_ibfk_1` FOREIGN KEY (`id_poli`) REFERENCES `poli` (`id`);

--
-- Constraints for table `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  ADD CONSTRAINT `jadwal_periksa_ibfk_1` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id`);

--
-- Constraints for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD CONSTRAINT `konsultasi_ibfk_1` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id`),
  ADD CONSTRAINT `konsultasi_ibfk_2` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id`);

--
-- Constraints for table `periksa`
--
ALTER TABLE `periksa`
  ADD CONSTRAINT `periksa_ibfk_1` FOREIGN KEY (`id_daftar_poli`) REFERENCES `daftar_poli` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
