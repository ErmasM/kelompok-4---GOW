-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 14, 2025 at 05:37 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_god_of_war`
--

-- --------------------------------------------------------

--
-- Table structure for table `characters`
--

CREATE TABLE `characters` (
  `id` int NOT NULL,
  `series_id` int DEFAULT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `peran` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `characters`
--

INSERT INTO `characters` (`id`, `series_id`, `nama`, `peran`, `gambar`) VALUES
(1, 1, 'Athena', 'Goddess of Wisdom', 'Athena_29.webp'),
(2, 1, 'Artemis', 'Goddess of Hunt', 'Artemis.webp'),
(3, 1, 'Kratos', 'The Ghost of Sparta', 'GOW 2018.jpg'),
(4, 1, 'Atreus', 'The Son (Loki)', 'GOW RAGNAROK.jpg'),
(5, 1, 'Kratos (God)', 'God of War', 'Pcsx2-r4600_2011-07-17_19-56-02-28.webp'),
(6, 2, 'jono', 'pler', 'Gow2-titan.webp');

-- --------------------------------------------------------

--
-- Table structure for table `realms`
--

CREATE TABLE `realms` (
  `id` int NOT NULL,
  `nama_realm` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `gambar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `posisi_top` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `posisi_left` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `realms`
--

INSERT INTO `realms` (`id`, `nama_realm`, `deskripsi`, `gambar`, `posisi_top`, `posisi_left`) VALUES
(1, 'Mount Olympus', 'Rumah para Dewa Yunani. Tempat Kratos membalas dendam.', 'Temple_5.webp', '20%', '60%'),
(2, 'Island of Creation', 'Tempat para Sisters of Fate bersemayam.', 'Island_of_creation.webp', '65%', '25%'),
(3, 'Midgard', 'Realm manusia di mitologi Nordik, tempat Kratos pensiun.', 'GOWRG_Wallpaper_Desktop_Vista_4k.jpg', '50%', '50%'),
(4, 'Sparta', 'Tanah kelahiran Kratos dan pasukan Spartan.', 'GOW ghost of sparta.jpg', '80%', '70%');

-- --------------------------------------------------------

--
-- Table structure for table `series`
--

CREATE TABLE `series` (
  `id` int NOT NULL,
  `judul` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `platform` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `gambar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `header_img` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `link_teleport` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `boss_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'Unknown God',
  `boss_hp` int DEFAULT '1000',
  `boss_img` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'logo.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `series`
--

INSERT INTO `series` (`id`, `judul`, `tahun`, `platform`, `deskripsi`, `gambar`, `header_img`, `link_teleport`, `boss_name`, `boss_hp`, `boss_img`) VALUES
(1, 'God of War', '2005', 'PS 2', 'cobbaaaaaa', 'GOW.jpg', 'GOW.jpg', 'detail.php?id=1', 'ARES', 800, 'AresGod.webp'),
(2, 'God of War II', '2007', 'PS 2', 'Kratos dikhianati Zeus dan mulai memburu para Dewa Olympus dengan bantuan Titans.', 'GOW2.jpg', 'GOW2.jpg', 'detail.php?id=2', 'ZEUS', 1200, 'Youngzeus.webp'),
(3, 'God of War III', '2010', 'PS 3', 'Akhir dari era Yunani. Kratos mendaki Gunung Olympus untuk membunuh Zeus.', 'GOW3.jpg', 'GOW3.jpg', 'detail.php?id=3', 'HADES', 1000, 'GOW3.jpg'),
(4, 'God of War (2018)', '2018', 'PS 4', 'Hidup baru di tanah Nordik bersama putranya, Atreus. Perjalanan menyebar abu istri.', 'GOW 2018.jpg', 'GOWRG_Wallpaper_Desktop_Boat_4k.jpg', 'detail.php?id=4', 'BALDUR', 1500, 'GOW 2018.jpg'),
(5, 'God of War Ragnarök', '2022', 'PS 5', 'Fimbulwinter telah tiba. Perang akhir zaman dimulai melawan Odin dan Thor.', 'GOW RAGNAROK.jpg', 'GOW RAGNAROK.jpg', 'detail.php?id=5', 'THOR', 2000, 'GOW RAGNAROK.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `timeline`
--

CREATE TABLE `timeline` (
  `id` int NOT NULL,
  `series_id` int DEFAULT NULL,
  `judul_chapter` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `urutan` int DEFAULT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timeline`
--

INSERT INTO `timeline` (`id`, `series_id`, `judul_chapter`, `deskripsi`, `urutan`, `gambar`) VALUES
(1, 1, 'Hydra Battle', 'Kratos melawan Hydra di Laut Aegean atas perintah Poseidon.', 1, 'Hydra_Boss_Fight4_-_God_of_War2005.webp'),
(2, 1, 'Pandora Temple', 'Mencari Kotak Pandora untuk mengalahkan Ares.', 2, 'Temple_5.webp'),
(3, 1, 'The Journey Begins', 'Kratos menebang pohon bertanda tangan istrinya.', 3, 'GOW 2018.jpg'),
(4, 1, 'The Stranger', 'Pertarungan pertama melawan Baldur.', 4, 'GOW ascension.jpg'),
(5, 5, 'lu', 'coba', 1, 'GOW 2018.jpg'),
(6, 5, 'The Journey Begins', 'cobaaacobaa', 5, 'maxresdefault.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_general_ci DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`) VALUES
(1, 'Kratos Admin', 'admin@gow.com', 'admin123', 'admin'),
(2, 'Boy', 'user@gow.com', 'user123', 'user'),
(3, 'ezera', 'EZ@gmail.com', '123', 'user'),
(4, 'Ermas', 'ERMAS@GOW.com', '123', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `weapons`
--

CREATE TABLE `weapons` (
  `id` int NOT NULL,
  `series_id` int DEFAULT NULL,
  `nama_senjata` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `gambar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `stat_damage` int DEFAULT '50',
  `stat_speed` int DEFAULT '50',
  `stat_range` int DEFAULT '50',
  `stat_cc` int DEFAULT '50'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `weapons`
--

INSERT INTO `weapons` (`id`, `series_id`, `nama_senjata`, `deskripsi`, `gambar`, `stat_damage`, `stat_speed`, `stat_range`, `stat_cc`) VALUES
(1, 1, 'Blades of Chaos', 'Senjata ikonik terikat rantai di lengan Kratos.', 'BladeOfChaos_29.webp', 80, 70, 90, 60),
(2, 1, 'Blade of Artemis', 'Pedang besar pemberian Dewi Artemis.', 'Blade_of_Artemis_29.webp', 95, 40, 50, 80),
(3, 2, 'Blade of Olympus', 'Pedang legendaris yang mengakhiri perang besar.', 'Blade_of_Olympus.jpg', 100, 50, 70, 90),
(4, 2, 'Spear of Destiny', 'Tombak ungu dengan serangan jarak jauh.', 'GOW2_Spear_Of_Destiny.jpg', 60, 80, 95, 40),
(5, 1, 'Barbarian Hammer', 'Palu raksasa milik Raja Barbarian.', 'Barbarian_Hammer.jpg', 77, 20, 94, 83),
(6, 4, 'Leviathan Axe', 'Kapak es buatan Brok & Sindri untuk Faye.', 'GOW 2018.jpg', 85, 60, 50, 75);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `characters`
--
ALTER TABLE `characters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `series_id` (`series_id`);

--
-- Indexes for table `realms`
--
ALTER TABLE `realms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `series`
--
ALTER TABLE `series`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timeline`
--
ALTER TABLE `timeline`
  ADD PRIMARY KEY (`id`),
  ADD KEY `series_id` (`series_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `weapons`
--
ALTER TABLE `weapons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `series_id` (`series_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `characters`
--
ALTER TABLE `characters`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `realms`
--
ALTER TABLE `realms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `series`
--
ALTER TABLE `series`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `timeline`
--
ALTER TABLE `timeline`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `weapons`
--
ALTER TABLE `weapons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `characters`
--
ALTER TABLE `characters`
  ADD CONSTRAINT `characters_ibfk_1` FOREIGN KEY (`series_id`) REFERENCES `series` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `timeline`
--
ALTER TABLE `timeline`
  ADD CONSTRAINT `timeline_ibfk_1` FOREIGN KEY (`series_id`) REFERENCES `series` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `weapons`
--
ALTER TABLE `weapons`
  ADD CONSTRAINT `weapons_ibfk_1` FOREIGN KEY (`series_id`) REFERENCES `series` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
