-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versi server:                 10.4.21-MariaDB - mariadb.org binary distribution
-- OS Server:                    Win64
-- HeidiSQL Versi:               11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Membuang struktur basisdata untuk visio
CREATE DATABASE IF NOT EXISTS `visio` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `visio`;

-- membuang struktur untuk table visio.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel visio.failed_jobs: ~0 rows (lebih kurang)
DELETE FROM `failed_jobs`;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

-- membuang struktur untuk table visio.jadwal_dokter
CREATE TABLE IF NOT EXISTS `jadwal_dokter` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `hari` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kuota` int(11) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `jenis` enum('On Site','Panggilan') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel visio.jadwal_dokter: ~4 rows (lebih kurang)
DELETE FROM `jadwal_dokter`;
/*!40000 ALTER TABLE `jadwal_dokter` DISABLE KEYS */;
INSERT INTO `jadwal_dokter` (`id`, `users_id`, `hari`, `kuota`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `jenis`) VALUES
	(1, 5, 'senin', 10, 'true', 'superuser', 'superuser', '2022-06-30 16:04:36', '2022-06-30 16:04:36', 'On Site'),
	(2, 5, 'rabu', 12, 'true', 'superuser', 'superuser', '2022-06-30 16:04:44', '2022-06-30 16:04:44', 'On Site'),
	(3, 5, 'selasa', 5, 'true', 'superuser', 'superuser', '2022-06-30 16:04:54', '2022-06-30 16:04:54', 'Panggilan'),
	(4, 5, 'jumat', 5, 'true', 'superuser', 'superuser', '2022-06-30 16:05:04', '2022-06-30 16:05:04', 'Panggilan');
/*!40000 ALTER TABLE `jadwal_dokter` ENABLE KEYS */;

-- membuang struktur untuk table visio.jadwal_dokter_log
CREATE TABLE IF NOT EXISTS `jadwal_dokter_log` (
  `jadwal_dokter_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `hari` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pasien_id` int(11) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_reservasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` enum('On Site','Panggilan') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`jadwal_dokter_id`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel visio.jadwal_dokter_log: ~4 rows (lebih kurang)
DELETE FROM `jadwal_dokter_log`;
/*!40000 ALTER TABLE `jadwal_dokter_log` DISABLE KEYS */;
INSERT INTO `jadwal_dokter_log` (`jadwal_dokter_id`, `id`, `hari`, `tanggal`, `created_at`, `updated_at`, `pasien_id`, `status`, `no_reservasi`, `telp`, `alamat`, `jenis`, `status_pembayaran`) VALUES
	(2, 1, 'rabu', '2022-07-06', '2022-06-30 17:18:16', '2022-06-30 18:10:04', 1, 'Done', 'R0001', '32323', '23443434', 'On Site', NULL),
	(3, 1, 'selasa', '2022-07-05', '2022-06-30 17:19:36', '2022-06-30 17:19:36', 1, 'Reserved', 'PR0001', '32323', '23443434', 'Panggilan', NULL),
	(4, 1, 'jumat', '2022-07-08', '2022-06-30 17:19:36', '2022-06-30 17:19:36', 1, 'Reserved', 'PR0001', '32323', '23443434', 'Panggilan', NULL);
/*!40000 ALTER TABLE `jadwal_dokter_log` ENABLE KEYS */;

-- membuang struktur untuk table visio.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel visio.migrations: ~21 rows (lebih kurang)
DELETE FROM `migrations`;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2022_06_29_141551_create_sessions_table', 2),
	(6, '2022_06_29_142112_add_username_to_users', 2),
	(7, '2022_06_29_143854_add_role_id_to_users', 3),
	(8, '2022_06_29_144309_create_role', 3),
	(10, '2022_06_29_145045_add_column_batch_1_to_users', 4),
	(13, '2022_06_29_173319_create_jadwal_dokter', 5),
	(14, '2022_06_29_173524_create_jadwal_dokter_log', 5),
	(17, '2022_06_30_053303_create_pasien', 6),
	(18, '2022_06_30_053618_add_pasien_id_to_jadwal_dokter_log', 6),
	(19, '2022_06_30_061024_add_status_to_jadwal_dokter_log', 7),
	(22, '2022_06_30_061526_create_pasien_rekam_medis', 8),
	(25, '2022_06_30_091321_add_no_reservasi_to_jadwal_dokter_log', 9),
	(26, '2022_06_30_115448_add_jenis_to_jadwal_dokter_log', 10),
	(27, '2022_06_30_153900_add_column_jenis_to_jadwal_dokter', 11),
	(30, '2022_07_01_035806_create_pembayaran', 12),
	(31, '2022_07_01_040047_add_status_pembayaran_to_jadwal_dokter_log', 12),
	(32, '2022_07_01_041325_create_pembayaran_detail', 13),
	(33, '2022_07_01_044714_create_item', 14);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- membuang struktur untuk table visio.pasien
CREATE TABLE IF NOT EXISTS `pasien` (
  `id` int(11) NOT NULL,
  `id_pasien` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `telp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel visio.pasien: ~1 rows (lebih kurang)
DELETE FROM `pasien`;
/*!40000 ALTER TABLE `pasien` DISABLE KEYS */;
INSERT INTO `pasien` (`id`, `id_pasien`, `name`, `tanggal_lahir`, `jenis_kelamin`, `telp`, `alamat`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
	(1, 'RM0001', 'Rani wongso', '1995-12-12', 'Perempuan', '32323', '23443434', 'superuser', 'superuser', '2022-06-30 05:52:03', '2022-06-30 06:05:33');
/*!40000 ALTER TABLE `pasien` ENABLE KEYS */;

-- membuang struktur untuk table visio.pasien_rekam_medis
CREATE TABLE IF NOT EXISTS `pasien_rekam_medis` (
  `pasien_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `id_rekam_medis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `dokter_id` int(11) NOT NULL,
  `tindakan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pasien_id`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel visio.pasien_rekam_medis: ~1 rows (lebih kurang)
DELETE FROM `pasien_rekam_medis`;
/*!40000 ALTER TABLE `pasien_rekam_medis` DISABLE KEYS */;
INSERT INTO `pasien_rekam_medis` (`pasien_id`, `id`, `id_rekam_medis`, `tanggal`, `dokter_id`, `tindakan`, `keterangan`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
	(1, 1, 'PE0001', '2022-06-30', 5, '23', '23232', 'superuser', 'superuser', '2022-06-30 18:10:04', '2022-06-30 18:10:04');
/*!40000 ALTER TABLE `pasien_rekam_medis` ENABLE KEYS */;

-- membuang struktur untuk table visio.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel visio.password_resets: ~0 rows (lebih kurang)
DELETE FROM `password_resets`;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;

-- membuang struktur untuk table visio.pembayaran
CREATE TABLE IF NOT EXISTS `pembayaran` (
  `id` int(11) NOT NULL,
  `nomor_invoice` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `pasien_id` int(11) NOT NULL,
  `metode_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` double(20,2) NOT NULL,
  `diagnosa` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_rekening` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_transaksi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel visio.pembayaran: ~0 rows (lebih kurang)
DELETE FROM `pembayaran`;
/*!40000 ALTER TABLE `pembayaran` DISABLE KEYS */;
/*!40000 ALTER TABLE `pembayaran` ENABLE KEYS */;

-- membuang struktur untuk table visio.pembayaran_detail
CREATE TABLE IF NOT EXISTS `pembayaran_detail` (
  `pembayaran_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `total` double(20,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pembayaran_id`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel visio.pembayaran_detail: ~0 rows (lebih kurang)
DELETE FROM `pembayaran_detail`;
/*!40000 ALTER TABLE `pembayaran_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `pembayaran_detail` ENABLE KEYS */;

-- membuang struktur untuk table visio.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel visio.personal_access_tokens: ~0 rows (lebih kurang)
DELETE FROM `personal_access_tokens`;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;

-- membuang struktur untuk table visio.role
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel visio.role: ~3 rows (lebih kurang)
DELETE FROM `role`;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` (`id`, `name`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
	(1, 'Terapis', 'me', 'me', '2022-06-29 23:29:53', '2022-06-29 23:29:54'),
	(2, 'Perawat', 'me', 'me', '2022-06-29 23:30:10', '2022-06-29 23:30:11'),
	(3, 'SuperAdmin', 'me', 'me', '2022-06-30 00:19:33', '2022-06-30 00:19:34');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;

-- membuang struktur untuk table visio.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel visio.sessions: ~0 rows (lebih kurang)
DELETE FROM `sessions`;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;

-- membuang struktur untuk table visio.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `telp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel visio.users: ~5 rows (lebih kurang)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `username`, `role_id`, `telp`, `alamat`, `user_id`, `tanggal_lahir`) VALUES
	(1, 'superuser', 'dewa17a@gmail.com', NULL, '$2y$10$5OIakeeAS./56pYgK0pO8.1FzUZW3k6M71OS3cFlgJbRRTirJfsQy', '2', '2022-06-29 13:16:09', '2022-06-29 13:18:58', '', 3, NULL, NULL, NULL, NULL),
	(2, 'superuser', 'superuser@gmail.com', NULL, '$2y$10$X3RVXw5Cv2tqrMdeaRLtSOUqvbt.ZqJuE.fXWqqWJ1x5YiWAnc9Vu', NULL, '2022-06-29 14:28:31', '2022-06-29 14:28:31', 'superuser', 3, NULL, NULL, NULL, NULL),
	(3, 'tes', 'tes@gmail.com', NULL, '$2y$10$HJFFui4Cqe3JpaZ1F8ys/eODG2l2wZrwnb/i9na6EqoT9Hy.MVzbG', NULL, '2022-06-29 17:13:10', '2022-06-29 17:13:10', 'P0001', 2, '2323', '23232', 'P0001', '2022-06-21'),
	(4, 'deni', 'deni@gmail.com', NULL, '$2y$10$1jaFzmUWwSRwqtn3v2nbOOO.7y1xdA7xWINh7L8OBu0udwHarGEoC', NULL, '2022-06-29 17:14:05', '2022-06-29 17:14:05', 'P0002', 2, '23232', '323232', 'P0002', '2022-06-16'),
	(5, 'faisal', 'faisal@gmail.com', NULL, '$2y$10$wXmWwLqRdw6B4tBBmfnsN.zznDDH7.7ikXw/q2Qfd2kdq5Egi8J3W', NULL, '2022-06-29 17:14:19', '2022-06-29 18:39:08', 'T0001', 1, '2312', '312321321', 'T0001', '2022-06-21');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
