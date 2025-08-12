-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for topsis_spk


-- Dumping structure for table topsis_spk.alternative
DROP TABLE IF EXISTS `alternative`;
CREATE TABLE IF NOT EXISTS `alternative` (
  `alternative_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `criteria_id` char(25) NOT NULL,
  `alternative_value` bigint NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`alternative_id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;

-- Dumping data for table topsis_spk.alternative: ~40 rows (approximately)
DELETE FROM `alternative`;
INSERT INTO `alternative` (`alternative_id`, `user_id`, `criteria_id`, `alternative_value`, `created_at`, `updated_at`) VALUES
	(22, 35, 'C1', 3, '2025-06-10 11:27:12', '2025-06-10 12:57:04'),
	(23, 35, 'C2', 3, '2025-06-10 11:27:12', '2025-06-10 12:57:04'),
	(24, 35, 'C3', 19, '2025-06-10 11:27:12', '2025-06-10 12:57:04'),
	(25, 35, 'C4', 3, '2025-06-10 11:27:12', '2025-06-10 12:57:04'),
	(26, 36, 'C1', 9, '2025-06-10 11:29:32', '2025-06-10 11:29:32'),
	(27, 36, 'C2', 3, '2025-06-10 11:29:32', '2025-06-10 11:29:32'),
	(28, 36, 'C3', 15, '2025-06-10 11:29:32', '2025-06-10 11:29:32'),
	(29, 36, 'C4', 5, '2025-06-10 11:29:32', '2025-06-10 11:29:32'),
	(30, 37, 'C1', 4, '2025-06-10 11:31:01', '2025-06-10 11:31:01'),
	(31, 37, 'C2', 3, '2025-06-10 11:31:01', '2025-06-10 11:31:01'),
	(32, 37, 'C3', 18, '2025-06-10 11:31:01', '2025-06-10 11:31:01'),
	(33, 37, 'C4', 2, '2025-06-10 11:31:01', '2025-06-10 11:31:01'),
	(34, 38, 'C1', 7, '2025-06-10 11:31:33', '2025-06-10 11:31:33'),
	(35, 38, 'C2', 3, '2025-06-10 11:31:33', '2025-06-10 11:31:33'),
	(36, 38, 'C3', 19, '2025-06-10 11:31:33', '2025-06-10 11:31:33'),
	(37, 38, 'C4', 3, '2025-06-10 11:31:33', '2025-06-10 11:31:33'),
	(38, 39, 'C1', 3, '2025-06-10 11:32:05', '2025-06-10 11:32:05'),
	(39, 39, 'C2', 3, '2025-06-10 11:32:05', '2025-06-10 11:32:05'),
	(40, 39, 'C3', 20, '2025-06-10 11:32:05', '2025-06-10 11:32:05'),
	(41, 39, 'C4', 2, '2025-06-10 11:32:05', '2025-06-10 11:32:05'),
	(42, 40, 'C1', 8, '2025-06-10 11:33:37', '2025-06-10 11:33:37'),
	(43, 40, 'C2', 3, '2025-06-10 11:33:37', '2025-06-10 11:33:37'),
	(44, 40, 'C3', 17, '2025-06-10 11:33:37', '2025-06-10 11:33:37'),
	(45, 40, 'C4', 5, '2025-06-10 11:33:37', '2025-06-10 11:33:37'),
	(46, 41, 'C1', 3, '2025-06-10 11:34:05', '2025-06-10 11:34:05'),
	(47, 41, 'C2', 3, '2025-06-10 11:34:05', '2025-06-10 11:34:05'),
	(48, 41, 'C3', 23, '2025-06-10 11:34:05', '2025-06-10 11:34:05'),
	(49, 41, 'C4', 3, '2025-06-10 11:34:05', '2025-06-10 11:34:05'),
	(50, 42, 'C1', 7, '2025-06-10 11:34:49', '2025-06-10 11:34:49'),
	(51, 42, 'C2', 3, '2025-06-10 11:34:49', '2025-06-10 11:34:49'),
	(52, 42, 'C3', 24, '2025-06-10 11:34:49', '2025-06-10 11:34:49'),
	(53, 42, 'C4', 2, '2025-06-10 11:34:49', '2025-06-10 11:34:49'),
	(54, 43, 'C1', 6, '2025-06-10 11:35:25', '2025-06-10 11:35:25'),
	(55, 43, 'C2', 3, '2025-06-10 11:35:25', '2025-06-10 11:35:25'),
	(56, 43, 'C3', 25, '2025-06-10 11:35:25', '2025-06-10 11:35:25'),
	(57, 43, 'C4', 2, '2025-06-10 11:35:25', '2025-06-10 11:35:25'),
	(58, 44, 'C1', 7, '2025-06-10 11:35:55', '2025-06-10 11:35:55'),
	(59, 44, 'C2', 3, '2025-06-10 11:35:55', '2025-06-10 11:35:55'),
	(60, 44, 'C3', 10, '2025-06-10 11:35:55', '2025-06-10 11:35:55'),
	(61, 44, 'C4', 2, '2025-06-10 11:35:55', '2025-06-10 11:35:55');

-- Dumping structure for table topsis_spk.criteria
DROP TABLE IF EXISTS `criteria`;
CREATE TABLE IF NOT EXISTS `criteria` (
  `criteria_id` char(25) NOT NULL,
  `criteria_name` varchar(255) NOT NULL,
  `criteria_type` varchar(255) NOT NULL,
  `criteria_information` longtext NOT NULL,
  `criteria_value` float NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`criteria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table topsis_spk.criteria: ~4 rows (approximately)
DELETE FROM `criteria`;
INSERT INTO `criteria` (`criteria_id`, `criteria_name`, `criteria_type`, `criteria_information`, `criteria_value`, `created_at`, `updated_at`) VALUES
	('C1', 'Pendapatan Bulanan (Rp - Juta)', 'cost', 'Semakin sedikit pendapatan, semakin butuh bantuan', 0.2, '2025-06-10 11:18:49', '2025-06-10 11:24:07'),
	('C2', 'Legalitas (Izin)', 'benefit', 'Usaha yang legal lebih bisa di priorotaskan.', 0.2, '2025-06-10 11:20:12', '2025-07-07 12:28:57'),
	('C3', 'Lama Usaha (Tahun)', 'benefit', 'Semakin lama yang dijalankan semakin layak dibantu karena dianggap serius.', 0.3, '2025-06-10 11:20:52', '2025-07-07 12:29:04'),
	('C4', 'Tanggungan (Orang)', 'benefit', 'Semakin banyak yang di tanggung semakin layak dibantu.', 0.3, '2025-06-10 11:21:58', '2025-07-07 12:29:12');

-- Dumping structure for table topsis_spk.masyarakat
DROP TABLE IF EXISTS `masyarakat`;
CREATE TABLE IF NOT EXISTS `masyarakat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `nik` varchar(16) NOT NULL,
  `alamat` text NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `ktp_img` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT (now()),
  `updated_at` timestamp NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `nik` (`nik`),
  CONSTRAINT `fk_masyarakat_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table topsis_spk.masyarakat: ~1 rows (approximately)
DELETE FROM `masyarakat`;
INSERT INTO `masyarakat` (`id`, `user_id`, `nik`, `alamat`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `ktp_img`, `created_at`, `updated_at`) VALUES
	(2, 47, '1121232131232131', 'Jalan Jalan', 'Pekanbaru', '1995-03-31', 'Laki-laki', 'ktp_689acf443aa64.jpg', '2025-08-12 02:09:06', '2025-08-12 05:21:08');

-- Dumping structure for table topsis_spk.ranking
DROP TABLE IF EXISTS `ranking`;
CREATE TABLE IF NOT EXISTS `ranking` (
  `ranking_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `score` decimal(10,8) NOT NULL,
  `peringkat` int NOT NULL,
  `calculated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ranking_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `ranking_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Dumping data for table topsis_spk.ranking: ~10 rows (approximately)
DELETE FROM `ranking`;
INSERT INTO `ranking` (`ranking_id`, `user_id`, `score`, `peringkat`, `calculated_at`) VALUES
	(1, 41, 0.60355893, 1, '2025-08-05 16:44:36'),
	(2, 40, 0.60172855, 2, '2025-08-05 16:44:36'),
	(3, 35, 0.54838475, 3, '2025-08-05 16:44:36'),
	(4, 36, 0.54456280, 4, '2025-08-05 16:44:36'),
	(5, 39, 0.45543720, 5, '2025-08-05 16:44:36'),
	(6, 43, 0.45145493, 6, '2025-08-05 16:44:36'),
	(7, 38, 0.41939258, 7, '2025-08-05 16:44:36'),
	(8, 42, 0.41507461, 8, '2025-08-05 16:44:36'),
	(9, 37, 0.39827145, 9, '2025-08-05 16:44:36'),
	(10, 44, 0.14328114, 10, '2025-08-05 16:44:36');

-- Dumping structure for table topsis_spk.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `level` enum('1','2','3') NOT NULL,
  `img` longtext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;

-- Dumping data for table topsis_spk.users: ~13 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`user_id`, `username`, `password`, `name`, `level`, `img`, `created_at`, `updated_at`) VALUES
	(11, 'admin', '$2y$12$yos5xFMt26uwxRPbKrTU3e1/k3tFcbr/BuTqDgL41G7guw7tuNoCK', 'Admin Super', '1', NULL, '2025-05-13 00:37:21', '2025-05-13 00:37:21'),
	(35, 'mul', '$2y$12$F8tUGC6NcH7uoCBPzm2RXOnooWE5Z2LNROjIjHczwBFmeZ4e/p6eu', 'Mul', '2', NULL, '2025-06-10 11:14:20', '2025-06-10 11:14:20'),
	(36, 'sarno', '$2y$12$e/696fMAFIKcx7d5bCHwaOTFRMJuz2vcCOiQceeWbAJawXmwVMnEq', 'Sarno', '2', NULL, '2025-06-10 11:14:40', '2025-06-10 11:14:40'),
	(37, 'safar', '$2y$12$kCvWY2qyTO/xwSNvrn3yauioVxB6Cwdb4VDikWfdtWO.un4rG6c7C', 'Safar', '2', NULL, '2025-06-10 11:15:07', '2025-06-10 11:15:07'),
	(38, 'warto', '$2y$12$aSQ8wpIeY1pJbnrtrfZOHekralLORUBQTIO.hpa/fr.gQroaihn9y', 'Warto', '2', NULL, '2025-06-10 11:15:32', '2025-06-10 11:15:32'),
	(39, 'jamil', '$2y$12$tQ0fvnJC/XZO0dwDrsLQrujRafmWhf8tiRjb5jOULBDipf1gf9Vgq', 'Jamil', '2', NULL, '2025-06-10 11:16:04', '2025-06-10 11:16:04'),
	(40, 'sairun', '$2y$12$a4WWQHTfB4hLAkvTEJS7VuyTeU57w9Zq9RWkabQc055bdoKDmqARu', 'Sairun', '2', '', '2025-06-10 11:16:28', '2025-06-10 11:32:55'),
	(41, 'gunjal', '$2y$12$2qhZzL47fMmuqe3R59H/SOK8CakH8segwbAR.Nqj.qs6gjKqDDxP2', 'Gunjal', '2', NULL, '2025-06-10 11:16:56', '2025-06-10 11:16:56'),
	(42, 'unedo', '$2y$12$lDidY93BrHvOZCV/VI5dleUw.sUfkBskhpk9KjlOQ7ypZINvgFBIW', 'Unedo', '2', NULL, '2025-06-10 11:17:24', '2025-06-10 11:17:24'),
	(43, 'kari', '$2y$12$lUg.gNQLn5aZSDqJtjgdA.wYeeoKVsYAqAR9Kd4DdkFAU7B5z08.G', 'Kari', '2', NULL, '2025-06-10 11:17:40', '2025-06-10 11:17:40'),
	(44, 'rafli', '$2y$12$wxsznivkz92zIL5xEeS0r.pp4F3GCtF.UNHb3sd5ROUGVJtMtxAv6', 'Rafli', '2', NULL, '2025-06-10 11:18:01', '2025-06-10 11:18:01'),
	(45, 'rita', '$2y$12$ZtZfxaPEZSm8cGdB4yS0/.uYU7T2cW5qEDRVUoAcYF4oUq4ZvPdWe', 'Rita', '1', '689acabfbdb89.jpg', '2025-06-10 12:53:52', '2025-08-12 12:01:52'),
	(47, 'amek', '$2y$10$6XQSbdnUKOGS1ILpBoTU2eau3uO0v8qHHrdISJZiNaRP9lDSrf6YK', 'Rahmad Riskiad', '2', 'profile_689acf37b34a3.jpg', '2025-08-12 09:09:06', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
