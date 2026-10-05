-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2026 at 08:21 PM
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
-- Database: `anasera_wo`
--
CREATE DATABASE IF NOT EXISTS `anasera_wo` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `anasera_wo`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(150) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `level` int(2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `username`, `password`, `level`, `created_at`, `updated_at`) VALUES
(1, 'Paduka Agung', 'BosBesar1', '$2y$10$XAJiMFIXg8qe1C6bOrOq.ONkzayC.GYxA1HNtjbTFCGaBm1oy2BBS', 2, '2026-04-16 13:22:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_profiles`
--

CREATE TABLE `admin_profiles` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `profile_pict` text DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `speciality1` varchar(100) DEFAULT NULL,
  `speciality2` varchar(100) DEFAULT NULL,
  `speciality3` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `tagline` text DEFAULT NULL,
  `site_description` text DEFAULT NULL,
  `visi` text DEFAULT NULL,
  `misi` text DEFAULT NULL,
  `site_keywords` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `location_url` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `tiktok` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `weddings`
--

CREATE TABLE `weddings` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `client_name` varchar(150) DEFAULT NULL,
  `cmp` varchar(150) DEFAULT NULL,
  `cmw` varchar(150) DEFAULT NULL,
  `concept` varchar(255) DEFAULT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `location_url` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `guest_count` int(4) DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `cover_image` varchar(255) DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `weddings`
--

INSERT INTO `weddings` (`id`, `title`, `slug`, `client_name`, `cmp`, `cmw`, `concept`, `venue`, `location_url`, `event_date`, `description`, `guest_count`, `status`, `cover_image`, `seo_title`, `seo_description`, `seo_keywords`, `created_at`, `updated_at`) VALUES
(1, 'Beach Wedding Andi & Sinta', 'beach-wedding-andi-sinta', 'Andi & Sinta', NULL, NULL, 'Tropical Elegant', 'Tanjung Aan Beach', NULL, '2025-07-20', 'Beautiful beach wedding with tropical elegant decoration and sunset ceremony.', NULL, 'draft', 'weddings/andi-sinta-cover.jpg', 'Beach Wedding Andi & Sinta', 'Beautiful beach wedding at Tanjung Aan Beach Lombok.', 'beach wedding lombok', '2026-03-08 16:07:14', NULL),
(2, 'Garden Wedding Budi & Ayu', 'garden-wedding-budi-ayu', 'Budi & Ayu', NULL, NULL, 'Rustic Garden', 'Private Villa Senggigi', NULL, '2025-08-15', 'Romantic garden wedding with rustic decoration and intimate atmosphere.', NULL, 'draft', 'weddings/budi-ayu-cover.jpg', 'Garden Wedding Budi & Ayu', 'Rustic garden wedding at Senggigi Lombok.', 'garden wedding lombok', '2026-03-08 16:07:14', NULL),
(3, 'The Wedding of Jasmin & Jungkuk', 'jasmin & jungkuk', 'Jasmin & jungkuk', 'Aaaa', 'Aaaaa', '', 'ic', '', '2026-12-31', '', 200, '', 'uploads/wedding-3/Cover-Image/3.jpg', NULL, NULL, NULL, '2026-04-16 07:26:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wedding_images`
--

CREATE TABLE `wedding_images` (
  `id` int(11) NOT NULL,
  `wedding_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wedding_images`
--

INSERT INTO `wedding_images` (`id`, `wedding_id`, `image_path`, `caption`, `sort_order`, `created_at`) VALUES
(1, NULL, 'weddings/andi-sinta/moment1.jpg', 'Wedding Ceremony', 1, '2026-03-08 16:07:58'),
(2, NULL, 'weddings/andi-sinta/moment2.jpg', 'Couple Portrait', 2, '2026-03-08 16:07:58'),
(3, NULL, 'weddings/andi-sinta/decoration1.jpg', 'Beach Decoration', 1, '2026-03-08 16:07:58'),
(4, NULL, 'weddings/andi-sinta/decoration2.jpg', 'Table Setup', 2, '2026-03-08 16:07:58'),
(5, NULL, 'weddings/andi-sinta/catering1.jpg', 'Buffet Catering', 1, '2026-03-08 16:07:58'),
(6, NULL, 'weddings/budi-ayu/moment1.jpg', 'Wedding Kiss', 1, '2026-03-08 16:07:58'),
(7, NULL, 'weddings/budi-ayu/decoration1.jpg', 'Garden Decoration', 1, '2026-03-08 16:07:58'),
(8, NULL, 'weddings/budi-ayu/reception1.jpg', 'Reception Dinner', 1, '2026-03-08 16:07:58'),
(9, 3, 'uploads/wedding-3/wedding-3_33fcf5.jpg', '-1', 1, '2026-04-16 07:33:27');

-- --------------------------------------------------------

--
-- Table structure for table `wedding_vendor`
--

CREATE TABLE `wedding_vendor` (
  `id` int(11) NOT NULL,
  `wedding_id` int(11) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `vendor` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wedding_vendor`
--

INSERT INTO `wedding_vendor` (`id`, `wedding_id`, `type`, `vendor`, `sort_order`, `created_at`) VALUES
(1, 1, 'Moment', NULL, 1, '2026-03-08 16:07:30'),
(2, 1, 'Decoration', NULL, 2, '2026-03-08 16:07:30'),
(3, 1, 'Catering', NULL, 3, '2026-03-08 16:07:30'),
(4, 2, 'Moment', NULL, 1, '2026-03-08 16:07:30'),
(5, 2, 'Decoration', NULL, 2, '2026-03-08 16:07:30'),
(6, 2, 'Reception', NULL, 3, '2026-03-08 16:07:30'),
(7, 3, 'Lainnya', 'Adena Griya Manten', 0, '2026-04-16 07:29:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admin_profiles`
--
ALTER TABLE `admin_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_admin_profiles_admin` (`admin_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weddings`
--
ALTER TABLE `weddings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `wedding_images`
--
ALTER TABLE `wedding_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_wedding_images` (`wedding_id`);

--
-- Indexes for table `wedding_vendor`
--
ALTER TABLE `wedding_vendor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wedding_id` (`wedding_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_profiles`
--
ALTER TABLE `admin_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weddings`
--
ALTER TABLE `weddings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wedding_images`
--
ALTER TABLE `wedding_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `wedding_vendor`
--
ALTER TABLE `wedding_vendor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_profiles`
--
ALTER TABLE `admin_profiles`
  ADD CONSTRAINT `fk_admin_profiles_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wedding_images`
--
ALTER TABLE `wedding_images`
  ADD CONSTRAINT `fk_wedding_images` FOREIGN KEY (`wedding_id`) REFERENCES `weddings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wedding_vendor`
--
ALTER TABLE `wedding_vendor`
  ADD CONSTRAINT `wedding_vendor_ibfk_1` FOREIGN KEY (`wedding_id`) REFERENCES `weddings` (`id`) ON DELETE CASCADE;
--
-- Database: `digital_invitation`
--
CREATE DATABASE IF NOT EXISTS `digital_invitation` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `digital_invitation`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(150) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `level` int(2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `username`, `password`, `level`, `created_at`, `updated_at`) VALUES
(1, 'Paduka Agung', 'BosBesar1', '$2y$10$XAJiMFIXg8qe1C6bOrOq.ONkzayC.GYxA1HNtjbTFCGaBm1oy2BBS', 2, '2026-04-16 05:22:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `template_id` int(11) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `couple_name` varchar(255) DEFAULT NULL,
  `groom_nickname` varchar(100) DEFAULT NULL,
  `bride_nickname` varchar(100) DEFAULT NULL,
  `token` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `template_id`, `slug`, `couple_name`, `groom_nickname`, `bride_nickname`, `token`) VALUES
(12, NULL, 'jasmin-jungkuk', NULL, 'jasmin', 'jungkuk', '852a8803207c4fdbeda9c6a0f31c3e98c2a1093b74fd98b85736aa8b1b818bff'),
(13, NULL, 'Jungkuk-Jasmin', NULL, 'Jungkuk', 'Jasmin', 'd6a80c8e76263841952b6b641c3d7fee0676ab4d71211f4ec7340ee161f4104a'),
(14, NULL, 'Farrel Putra Wiana-aca', NULL, 'Farrel Putra Wiana', 'aca', 'cf3425de21d89420c4d3cebc7cf0a4b74558e9a29a7817ab8b6a617590e07b02'),
(15, NULL, 'bagas-adinata', NULL, 'bagas', 'adinata', '53a6bd9187ff91808c422b3098cfcc7a13f1eb61c4f0a4b7dd811e03c33220db'),
(16, 2, 'farrel-Fika', 'Farrel & Fika', 'farrel', 'Fika', '3c53477c0faa69560363f283926abe09459d65969c8e633dd531c0beec1c5237'),
(17, 2, 'Sandi-Kana', NULL, 'Sandi', 'Kana', 'cde4a491e6dc87dfee0572c483a45aec2ac8003745bbc24b3722029546267079');

-- --------------------------------------------------------

--
-- Table structure for table `client_detail`
--

CREATE TABLE `client_detail` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `nama_mempelai_pria` varchar(100) NOT NULL,
  `nama_panggilan_cmp` varchar(100) DEFAULT NULL,
  `nama_mempelai_wanita` varchar(100) NOT NULL,
  `nama_panggilan_cmw` varchar(100) DEFAULT NULL,
  `nama_ayah_pria` varchar(100) DEFAULT NULL,
  `nama_ibu_pria` varchar(100) DEFAULT NULL,
  `nama_ayah_wanita` varchar(100) DEFAULT NULL,
  `nama_ibu_wanita` varchar(100) DEFAULT NULL,
  `tanggal_akad` date DEFAULT NULL,
  `jam_akad` time DEFAULT NULL,
  `lokasi_akad` varchar(255) DEFAULT NULL,
  `alamat_akad` text DEFAULT NULL,
  `tanggal_resepsi` date DEFAULT NULL,
  `jam_resepsi` time DEFAULT NULL,
  `lokasi_resepsi` varchar(255) DEFAULT NULL,
  `alamat_resepsi` text DEFAULT NULL,
  `cerita_cinta` text DEFAULT NULL,
  `maps` text DEFAULT NULL,
  `quote` text DEFAULT NULL,
  `music` varchar(255) DEFAULT NULL,
  `nama_bank` varchar(100) DEFAULT NULL,
  `nomor_rekening` varchar(50) DEFAULT NULL,
  `atas_nama` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_detail`
--

INSERT INTO `client_detail` (`id`, `client_id`, `nama_mempelai_pria`, `nama_panggilan_cmp`, `nama_mempelai_wanita`, `nama_panggilan_cmw`, `nama_ayah_pria`, `nama_ibu_pria`, `nama_ayah_wanita`, `nama_ibu_wanita`, `tanggal_akad`, `jam_akad`, `lokasi_akad`, `alamat_akad`, `tanggal_resepsi`, `jam_resepsi`, `lokasi_resepsi`, `alamat_resepsi`, `cerita_cinta`, `maps`, `quote`, `music`, `nama_bank`, `nomor_rekening`, `atas_nama`, `created_at`, `updated_at`) VALUES
(1, 16, 'aaaa', 'sandi', 'aaa', 'Kana', '', '', '', '', '2026-05-06', '00:00:00', '', NULL, '0000-00-00', '00:00:00', '', NULL, '', NULL, NULL, 'uploads/client-16/audio_1778000968_19fee4.mp3', NULL, NULL, NULL, '2026-05-03 13:18:01', '2026-05-05 17:09:28'),
(8, 17, 'Sandy Febriyan Tri Subban, S.Tr.Kes', 'Sandi', 'Baiq Dinde Gusti Kana, S.Tr.Kep', 'Kana', '', '', '', '', '2026-05-10', '13:30:00', '', NULL, '0000-00-00', '00:00:00', '', NULL, '', NULL, NULL, 'uploads/client-17/audio_1778003959_6b63e3.mp3', 'aaaa', 'aaaa', 'aaaa', '2026-05-05 17:15:41', '2026-05-05 18:10:53');

-- --------------------------------------------------------

--
-- Table structure for table `client_images`
--

CREATE TABLE `client_images` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `slot_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_images`
--

INSERT INTO `client_images` (`id`, `client_id`, `image_path`, `type`, `slot_id`) VALUES
(1, 16, 'uploads/client-16/cover_69f6cf357fc68.webp', 'cover', NULL),
(2, 16, 'uploads/client-16/galeri_69f9e4dbbb588.webp', 'galeri', NULL),
(3, 16, 'uploads/client-16/galeri_69f9f3fc862b9.webp', 'galeri', NULL),
(4, 16, 'uploads/client-16/galeri_69f9f3ff91ecd.webp', 'galeri', NULL),
(5, 16, 'uploads/client-16/galeri_69f9f4039a0d7.webp', 'galeri', NULL),
(7, 16, 'uploads/client-16/cmp_69f9fa1789b3d.webp', 'cmp', NULL),
(8, 16, 'uploads/client-16/cmw_69f9fa99c33c0.webp', 'cmw', NULL),
(15, 17, 'uploads/client-17/slider_69fa2e0c6d399.webp', 'slider', NULL),
(16, 17, 'uploads/client-17/slider_69fa2e0da64eb.webp', 'slider', NULL),
(17, 17, 'uploads/client-17/slider_69fa2e0eb1723.webp', 'slider', NULL),
(19, 17, 'uploads/client-17/galeri_69fa2ea9200b8.webp', 'galeri', NULL),
(20, 17, 'uploads/client-17/galeri_69fa2eaa2acfc.webp', 'galeri', NULL),
(21, 17, 'uploads/client-17/galeri_69fa2eab4cfa5.webp', 'galeri', NULL),
(22, 17, 'uploads/client-17/galeri_69fa2eac65661.webp', 'galeri', NULL),
(23, 17, 'uploads/client-17/galeri_69fa2ead55d11.webp', 'galeri', NULL),
(24, 17, 'uploads/client-17/galeri_69fa2eae410ae.webp', 'galeri', NULL),
(25, 17, 'uploads/client-17/cover_69fa317c27030.webp', 'cover', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `guest_name` varchar(100) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`id`, `client_id`, `guest_name`, `keterangan`, `status`, `message`) VALUES
(1, 16, 'Andi Saputra', 'Keluarga', 'hadir', 'InsyaAllah hadir'),
(2, 16, 'Budi Santoso', 'Teman', 'tidak_hadir', 'Maaf berhalangan'),
(3, 16, 'Citra Lestari', 'Rekan Kerja', NULL, NULL),
(4, 16, 'Dewi Anggraini', 'Keluarga', 'hadir', 'Akan hadir bersama keluarga'),
(5, 16, 'Eko Prasetyo', 'Teman', NULL, NULL),
(6, 16, 'Fajar Ramadhan', 'Teman', 'hadir', 'Selamat menempuh hidup baru'),
(7, 16, 'Gita Permata', 'Rekan Kerja', 'tidak_hadir', 'Sedang di luar kota'),
(8, 16, 'Hendra Wijaya', 'Keluarga', 'hadir', 'Siap hadir'),
(9, 16, 'Intan Maharani', 'Teman', NULL, NULL),
(10, 16, 'Joko Susilo', 'Teman', 'hadir', 'Turut berbahagia'),
(11, 16, 'Kartika Sari', 'Rekan Kerja', 'tidak_hadir', 'Mohon maaf tidak bisa hadir'),
(12, 16, 'Lukman Hakim', 'Keluarga', 'hadir', 'Semoga lancar sampai hari H'),
(13, 12, 'TEST CLIENT LAIN', NULL, 'hadir', 'Tidak boleh muncul'),
(17, 16, 'Bagas', 'Cowonya Jasmin', NULL, NULL),
(18, 17, 'Bagas', 'Teman', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rsvp`
--

CREATE TABLE `rsvp` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `status` enum('hadir','tidak') DEFAULT NULL,
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rsvp`
--

INSERT INTO `rsvp` (`id`, `client_id`, `status`, `message`) VALUES
(1, 16, 'hadir', 'InsyaAllah hadir, semoga acaranya lancar'),
(2, 16, 'hadir', 'Selamat ya, sampai jumpa di hari bahagia'),
(3, 16, '', 'Mohon maaf tidak bisa hadir karena ada acara keluarga'),
(4, 16, 'hadir', 'Semoga menjadi keluarga sakinah mawaddah warahmah'),
(5, 16, '', 'Maaf berhalangan hadir, doa terbaik untuk kalian'),
(6, 16, 'hadir', 'Akan hadir bersama pasangan'),
(7, 16, NULL, NULL),
(8, 16, NULL, NULL),
(9, 16, 'hadir', 'Selamat menempuh hidup baru'),
(10, 16, '', 'Sedang di luar kota, mohon maaf'),
(11, 16, 'hadir', 'Siap hadir'),
(12, 16, NULL, NULL),
(13, 16, 'hadir', 'Turut berbahagia atas pernikahan kalian'),
(14, 16, '', 'Maaf belum bisa datang'),
(15, 16, 'hadir', 'Semoga lancar sampai hari H');

-- --------------------------------------------------------

--
-- Table structure for table `template`
--

CREATE TABLE `template` (
  `id` int(11) NOT NULL,
  `nama_katalog` varchar(255) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `template`
--

INSERT INTO `template` (`id`, `nama_katalog`, `slug`) VALUES
(1, 'template ngetes', 'template-ngetes'),
(2, 'Custom', 'custom');

-- --------------------------------------------------------

--
-- Table structure for table `template_slots`
--

CREATE TABLE `template_slots` (
  `id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `type` enum('cover','galeri','slider','cmp','cmw','slider') NOT NULL,
  `label` varchar(100) NOT NULL DEFAULT '',
  `max_upload` tinyint(2) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `template_slots`
--

INSERT INTO `template_slots` (`id`, `template_id`, `type`, `label`, `max_upload`, `created_at`, `updated_at`) VALUES
(1, 2, 'cover', 'Cover Depan', 1, '2026-05-03 06:38:23', '2026-05-03 06:38:23'),
(2, 2, 'galeri', 'Galeri', 10, '2026-05-03 06:38:23', '2026-05-03 06:38:23'),
(3, 2, 'cmp', 'Foto Calon Mempelai Pria', 1, '2026-05-03 06:38:23', '2026-05-03 06:38:23'),
(4, 2, 'cmw', 'Foto Calon Mempelai Wanita', 1, '2026-05-03 06:38:23', '2026-05-03 06:38:23'),
(6, 2, 'slider', 'Slider', 3, '2026-05-05 13:46:24', '2026-05-05 13:46:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_clients_template` (`template_id`);

--
-- Indexes for table `client_detail`
--
ALTER TABLE `client_detail`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `client_id` (`client_id`);

--
-- Indexes for table `client_images`
--
ALTER TABLE `client_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `client_images_ibfk_2` (`slot_id`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guests_ibfk_1` (`client_id`);

--
-- Indexes for table `rsvp`
--
ALTER TABLE `rsvp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guest_id` (`client_id`);

--
-- Indexes for table `template`
--
ALTER TABLE `template`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `template_slots`
--
ALTER TABLE `template_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `template_id` (`template_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `client_detail`
--
ALTER TABLE `client_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `client_images`
--
ALTER TABLE `client_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `rsvp`
--
ALTER TABLE `rsvp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `template`
--
ALTER TABLE `template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `template_slots`
--
ALTER TABLE `template_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `client_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `template` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_clients_template` FOREIGN KEY (`template_id`) REFERENCES `template` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `client_detail`
--
ALTER TABLE `client_detail`
  ADD CONSTRAINT `fk_client_detail_client` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `client_images`
--
ALTER TABLE `client_images`
  ADD CONSTRAINT `client_images_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `client_images_ibfk_2` FOREIGN KEY (`slot_id`) REFERENCES `template_slots` (`id`);

--
-- Constraints for table `guests`
--
ALTER TABLE `guests`
  ADD CONSTRAINT `guests_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rsvp`
--
ALTER TABLE `rsvp`
  ADD CONSTRAINT `rsvp_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `template_slots`
--
ALTER TABLE `template_slots`
  ADD CONSTRAINT `template_slots_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `template` (`id`) ON DELETE CASCADE;
--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Dumping data for table `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"digital_invitation\",\"table\":\"client_detail\"},{\"db\":\"digital_invitation\",\"table\":\"client_images\"},{\"db\":\"digital_invitation\",\"table\":\"client\"},{\"db\":\"digital_invitation\",\"table\":\"template\"},{\"db\":\"digital_invitation\",\"table\":\"guests\"},{\"db\":\"digital_invitation\",\"table\":\"template_slots\"},{\"db\":\"digital_invitation\",\"table\":\"undangan\"},{\"db\":\"digital_invitation\",\"table\":\"rsvp\"},{\"db\":\"digital_invitation\",\"table\":\"katalog\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

--
-- Dumping data for table `pma__table_info`
--

INSERT INTO `pma__table_info` (`db_name`, `table_name`, `display_field`) VALUES
('digital_invitation', 'client', 'template_id'),
('digital_invitation', 'client_images', 'slot_id'),
('digital_invitation', 'rsvp', 'status');

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2026-05-05 18:21:05', '{\"Console\\/Mode\":\"collapse\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
