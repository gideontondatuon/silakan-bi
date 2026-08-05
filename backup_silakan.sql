-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: silakan
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `audit_log`
--

DROP TABLE IF EXISTS `audit_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `aksi` varchar(255) NOT NULL,
  `modul` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_log_user_id_foreign` (`user_id`),
  CONSTRAINT `audit_log_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_log`
--

LOCK TABLES `audit_log` WRITE;
/*!40000 ALTER TABLE `audit_log` DISABLE KEYS */;
INSERT INTO `audit_log` VALUES (1,3,'Membuat Pemesanan','Pemesanan','Membuat pengajuan pemesanan SIL-20260715-V3J5H','2026-07-15 06:05:50','2026-07-15 06:05:50'),(2,2,'Menyetujui Pemesanan','Approval','Menyetujui pemesanan SIL-20260715-V3J5H','2026-07-15 06:06:26','2026-07-15 06:06:26'),(3,3,'Membuat Pemesanan','Pemesanan','Membuat pengajuan pemesanan SIL-20260715-K1ZAM','2026-07-15 06:19:18','2026-07-15 06:19:18'),(4,2,'Menyetujui Pemesanan','Approval','Menyetujui pemesanan SIL-20260715-K1ZAM','2026-07-15 06:19:42','2026-07-15 06:19:42'),(5,2,'Menambahkan Ruangan','Master Ruangan','Menambahkan ruangan Sitaro','2026-07-15 14:31:39','2026-07-15 14:31:39'),(6,2,'Menambahkan Fasilitas','Master Fasilitas','Menambahkan fasilitas Meja','2026-07-15 14:32:50','2026-07-15 14:32:50'),(7,2,'Menambahkan Layout','Master Layout','Menambahkan layout Teater','2026-07-15 14:34:03','2026-07-15 14:34:03'),(8,3,'Membuat Pemesanan','Pemesanan','Membuat pengajuan pemesanan SIL-20260717-A9PKT','2026-07-17 06:50:28','2026-07-17 06:50:28'),(9,2,'Menyetujui Pemesanan','Approval','Menyetujui pemesanan SIL-20260717-A9PKT','2026-07-17 06:51:28','2026-07-17 06:51:28');
/*!40000 ALTER TABLE `audit_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('silakan_kpwbi_prov_sulut_cache_staff_if|10.136.243.156','i:1;',1784270999),('silakan_kpwbi_prov_sulut_cache_staff_if|10.136.243.156:timer','i:1784270999;',1784270999),('silakan_kpwbi_prov_sulut_cache_staff_ir|10.136.243.156','i:1;',1784270984),('silakan_kpwbi_prov_sulut_cache_staff_ir|10.136.243.156:timer','i:1784270984;',1784270984),('silakan_kpwbi_prov_sulut_cache_tondatuongideon@gmail.com|127.0.0.1','i:1;',1784189493),('silakan_kpwbi_prov_sulut_cache_tondatuongideon@gmail.com|127.0.0.1:timer','i:1784189493;',1784189493);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fasilitas`
--

DROP TABLE IF EXISTS `fasilitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fasilitas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_fasilitas` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fasilitas`
--

LOCK TABLES `fasilitas` WRITE;
/*!40000 ALTER TABLE `fasilitas` DISABLE KEYS */;
INSERT INTO `fasilitas` VALUES (1,'Proyektor','2026-07-11 04:17:43','2026-07-11 04:17:43'),(2,'Meja','2026-07-15 14:32:50','2026-07-15 14:32:50');
/*!40000 ALTER TABLE `fasilitas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `layout_ruangan`
--

DROP TABLE IF EXISTS `layout_ruangan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `layout_ruangan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ruangan_id` bigint(20) unsigned NOT NULL,
  `nama_layout` varchar(255) NOT NULL,
  `kapasitas_layout` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `layout_ruangan_ruangan_id_foreign` (`ruangan_id`),
  CONSTRAINT `layout_ruangan_ruangan_id_foreign` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `layout_ruangan`
--

LOCK TABLES `layout_ruangan` WRITE;
/*!40000 ALTER TABLE `layout_ruangan` DISABLE KEYS */;
INSERT INTO `layout_ruangan` VALUES (1,3,'U-Shape',10,'2026-07-11 05:01:47','2026-07-11 05:01:47'),(2,3,'Classroom',60,'2026-07-11 10:35:42','2026-07-11 10:35:42'),(3,3,'Teater',100,'2026-07-11 10:35:55','2026-07-11 10:35:55'),(4,4,'Teater',100,'2026-07-15 14:34:03','2026-07-15 14:34:03');
/*!40000 ALTER TABLE `layout_ruangan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_07_11_092816_create_ruangan_table',1),(5,'2026_07_11_093341_create_sessions_table',1),(6,'2026_07_11_094109_create_fasilitas_table',2),(7,'2026_07_11_094135_create_ruangan_fasilitas_table',2),(8,'2026_07_11_094143_create_layout_ruangan_table',2),(9,'2026_07_11_094501_create_pemesanan_table',3),(10,'2026_07_11_094637_create_pemesanan_status_history_table',4),(11,'2026_07_11_094700_create_audit_log_table',4),(12,'2026_07_11_094745_create_notifications_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES ('3e45db9d-05ce-4263-8d5f-a5116c4e8c28','App\\Notifications\\PemesananNotification','App\\Models\\User',2,'{\"judul\":\"Pengajuan Pemesanan Baru\",\"pesan\":\"Pemesanan SIL-20260715-DVI6T membutuhkan approval admin.\",\"waktu\":\"15-07-2026 08:39\"}','2026-07-15 00:54:52','2026-07-15 00:39:57','2026-07-15 00:54:52'),('4a593590-bd6c-4337-b78d-1e9cbc858088','App\\Notifications\\PemesananNotification','App\\Models\\User',2,'{\"judul\":\"Pengajuan Pemesanan Baru\",\"pesan\":\"Pemesanan SIL-20260715-0GWSF membutuhkan approval admin.\",\"pemesanan_id\":11,\"waktu\":\"15-07-2026 09:01\"}','2026-07-15 01:01:50','2026-07-15 01:01:16','2026-07-15 01:01:50'),('5097bda4-afe1-4cc1-baaf-469083147e57','App\\Notifications\\StatusPemesananNotification','App\\Models\\User',3,'{\"judul\":\"Pemesanan Disetujui\",\"pesan\":\"Pemesanan SIL-20260715-K1ZAM telah disetujui admin.\",\"pemesanan_id\":13,\"waktu\":\"15-07-2026 14:19\"}','2026-07-15 17:25:42','2026-07-15 06:19:42','2026-07-15 17:25:42'),('541b8572-8add-4a82-9638-d91f0b9705a9','App\\Notifications\\PemesananNotification','App\\Models\\User',2,'{\"judul\":\"Pengajuan Pemesanan Baru\",\"pesan\":\"Pemesanan SIL-20260715-OBQQI membutuhkan approval admin.\",\"pemesanan_id\":10,\"waktu\":\"15-07-2026 08:59\"}','2026-07-15 01:17:35','2026-07-15 00:59:33','2026-07-15 01:17:35'),('6de0f047-042e-4444-962b-17ae237936f0','App\\Notifications\\PemesananNotification','App\\Models\\User',1,'{\"judul\":\"Test SILAKAN\",\"pesan\":\"Notifikasi berhasil dibuat.\",\"waktu\":\"15-07-2026 07:52\"}',NULL,'2026-07-14 23:52:11','2026-07-14 23:52:11'),('7450d5de-60a4-4aca-94a0-21b7c729a460','App\\Notifications\\PemesananNotification','App\\Models\\User',2,'{\"judul\":\"Pengajuan Pemesanan Baru\",\"pesan\":\"Pemesanan SIL-20260715-7LEYT membutuhkan approval admin.\",\"waktu\":\"15-07-2026 08:22\"}','2026-07-15 00:58:19','2026-07-15 00:22:07','2026-07-15 00:58:19'),('75a5303e-934c-4c9f-a850-5d339073a982','App\\Notifications\\PemesananNotification','App\\Models\\User',2,'{\"judul\":\"Pengajuan Pemesanan Baru\",\"pesan\":\"Pemesanan SIL-20260717-A9PKT membutuhkan approval admin.\",\"pemesanan_id\":14,\"waktu\":\"17-07-2026 14:50\"}','2026-07-17 06:51:25','2026-07-17 06:50:29','2026-07-17 06:51:25'),('7688f4c2-07c5-4734-bf1d-6d8cac0f84a2','App\\Notifications\\PemesananNotification','App\\Models\\User',2,'{\"judul\":\"Pengajuan Pemesanan Baru\",\"pesan\":\"Pemesanan SIL-20260715-K1ZAM membutuhkan approval admin.\",\"pemesanan_id\":13,\"waktu\":\"15-07-2026 14:19\"}','2026-07-15 06:19:38','2026-07-15 06:19:18','2026-07-15 06:19:38'),('95adea99-b3ee-497f-bb11-999a21959a76','App\\Notifications\\PemesananNotification','App\\Models\\User',2,'{\"judul\":\"Pengajuan Pemesanan Baru\",\"pesan\":\"Pemesanan SIL-20260715-V3J5H membutuhkan approval admin.\",\"pemesanan_id\":12,\"waktu\":\"15-07-2026 14:05\"}','2026-07-15 06:06:23','2026-07-15 06:05:52','2026-07-15 06:06:23'),('9deb32b7-2259-4814-9f2c-fb4f825bb1ee','App\\Notifications\\StatusPemesananNotification','App\\Models\\User',3,'{\"judul\":\"Pemesanan Disetujui\",\"pesan\":\"Pemesanan SIL-20260715-V3J5H telah disetujui admin.\",\"pemesanan_id\":12,\"waktu\":\"15-07-2026 14:06\"}','2026-07-15 17:33:22','2026-07-15 06:06:26','2026-07-15 17:33:22'),('c957d613-791e-4b4e-992d-7262a30afdd3','App\\Notifications\\PemesananNotification','App\\Models\\User',2,'{\"judul\":\"Pengajuan Pemesanan Baru\",\"pesan\":\"Pemesanan SIL-20260715-YCXPU membutuhkan approval admin.\",\"waktu\":\"15-07-2026 08:31\"}','2026-07-15 00:58:17','2026-07-15 00:31:06','2026-07-15 00:58:17'),('cf5831fc-4f52-4287-becf-00a6af5a6a16','App\\Notifications\\PemesananNotification','App\\Models\\User',2,'{\"judul\":\"Pengajuan Pemesanan Baru\",\"pesan\":\"Pemesanan SIL-20260715-XRQ43 membutuhkan approval admin.\",\"waktu\":\"15-07-2026 08:29\"}','2026-07-15 00:58:09','2026-07-15 00:29:40','2026-07-15 00:58:09'),('de628dd8-29c2-4977-b10e-1fe7d78c11e1','App\\Notifications\\StatusPemesananNotification','App\\Models\\User',3,'{\"judul\":\"Pemesanan Ditolak\",\"pesan\":\"Pemesanan SIL-20260715-OBQQI ditolak admin. Alasan: Kosong\",\"pemesanan_id\":10,\"waktu\":\"15-07-2026 09:17\"}','2026-07-15 01:25:53','2026-07-15 01:17:44','2026-07-15 01:25:53'),('ecbc8654-a07d-44bc-b9db-ab2b0f9fb090','App\\Notifications\\PemesananNotification','App\\Models\\User',2,'{\"judul\":\"Pengajuan Pemesanan Baru\",\"pesan\":\"Pemesanan SIL-20260715-13GYP membutuhkan approval admin.\",\"waktu\":\"15-07-2026 08:14\"}','2026-07-15 00:58:21','2026-07-15 00:14:45','2026-07-15 00:58:21'),('f5f23115-5b2e-4581-a4bd-6c98a1f5544e','App\\Notifications\\PemesananNotification','App\\Models\\User',2,'{\"judul\":\"Pengajuan Pemesanan Baru\",\"pesan\":\"Pemesanan SIL-20260715-YE8I7 membutuhkan approval admin.\",\"waktu\":\"15-07-2026 08:34\"}','2026-07-15 00:57:59','2026-07-15 00:34:03','2026-07-15 00:57:59'),('fc5bfa86-2d37-407d-a9e6-c73adcd3f4de','App\\Notifications\\StatusPemesananNotification','App\\Models\\User',3,'{\"judul\":\"Pemesanan Disetujui\",\"pesan\":\"Pemesanan SIL-20260717-A9PKT telah disetujui admin.\",\"pemesanan_id\":14,\"waktu\":\"17-07-2026 14:51\"}','2026-07-17 06:51:34','2026-07-17 06:51:28','2026-07-17 06:51:34');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pemesanan`
--

DROP TABLE IF EXISTS `pemesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pemesanan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_pemesanan` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `ruangan_id` bigint(20) unsigned NOT NULL,
  `layout_ruangan_id` bigint(20) unsigned DEFAULT NULL,
  `tanggal_kegiatan` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL,
  `judul_kegiatan` varchar(150) NOT NULL,
  `pic_kegiatan` varchar(255) NOT NULL,
  `jenis_pic` enum('Organik','Non Organik') NOT NULL,
  `jumlah_tamu` int(11) NOT NULL,
  `keterangan_layout` text DEFAULT NULL,
  `catatan_user` text DEFAULT NULL,
  `status` enum('Pending','Disetujui','Ditolak','Cancel') NOT NULL DEFAULT 'Pending',
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint(20) unsigned DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `alasan_penolakan` text DEFAULT NULL,
  `cancelled_by` bigint(20) unsigned DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `alasan_pembatalan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pemesanan_kode_pemesanan_unique` (`kode_pemesanan`),
  KEY `pemesanan_user_id_foreign` (`user_id`),
  KEY `pemesanan_layout_ruangan_id_foreign` (`layout_ruangan_id`),
  KEY `pemesanan_approved_by_foreign` (`approved_by`),
  KEY `pemesanan_rejected_by_foreign` (`rejected_by`),
  KEY `pemesanan_cancelled_by_foreign` (`cancelled_by`),
  KEY `pemesanan_ruangan_id_tanggal_kegiatan_status_index` (`ruangan_id`,`tanggal_kegiatan`,`status`),
  CONSTRAINT `pemesanan_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pemesanan_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pemesanan_layout_ruangan_id_foreign` FOREIGN KEY (`layout_ruangan_id`) REFERENCES `layout_ruangan` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pemesanan_rejected_by_foreign` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pemesanan_ruangan_id_foreign` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pemesanan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pemesanan`
--

LOCK TABLES `pemesanan` WRITE;
/*!40000 ALTER TABLE `pemesanan` DISABLE KEYS */;
INSERT INTO `pemesanan` VALUES (1,'SIL-20260714-T1CCT',3,3,2,'2026-07-14','13:00:00','14:00:00','Rapat ORMAWA','Gideon','Organik',10,'Bentuk','Akan ada','Disetujui',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-14 07:48:57','2026-07-14 07:58:53'),(2,'SIL-20260714-LYEKN',3,3,1,'2026-07-17','08:00:00','09:00:00','k','k','Organik',9,'k','8','Disetujui',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-14 08:20:42','2026-07-14 08:37:09'),(3,'SIL-20260715-WCY6U',3,3,2,'2026-07-17','04:05:00','06:07:00','Rapat ORMAWA','y','Organik',60,'y','h','Disetujui',2,'2026-07-14 20:38:16',NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-14 20:13:09','2026-07-14 20:38:16'),(4,'SIL-20260715-13GYP',3,3,2,'2026-07-17','16:16:00','20:14:00','Rapat ORMAWA','h','Organik',1,'FR','FG','Pending',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-15 00:14:45','2026-07-15 00:14:45'),(5,'SIL-20260715-7LEYT',3,3,2,'2026-07-17','16:23:00','16:26:00','Rapat ORMAWA','h','Organik',1,'g','t','Pending',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-15 00:22:07','2026-07-15 00:22:07'),(6,'SIL-20260715-XRQ43',3,3,1,'2026-07-18','19:29:00','20:29:00','Rapat ORMAWA','h','Organik',1,'g','b','Pending',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-15 00:29:40','2026-07-15 00:29:40'),(7,'SIL-20260715-YCXPU',3,3,2,'2026-07-17','16:30:00','17:30:00','Rapat ORMAWA','h','Organik',1,'f','r','Pending',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-15 00:31:06','2026-07-15 00:31:06'),(8,'SIL-20260715-YE8I7',3,3,1,'2026-07-17','16:33:00','18:33:00','Rapat ORMAWA','h','Organik',1,'t','g','Pending',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-15 00:34:03','2026-07-15 00:34:03'),(9,'SIL-20260715-DVI6T',3,3,1,'2026-08-01','16:39:00','21:39:00','Rapat ORMAWA','h','Organik',1,'er','ter','Pending',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-15 00:39:57','2026-07-15 00:39:57'),(10,'SIL-20260715-OBQQI',3,3,1,'2026-07-17','19:59:00','21:59:00','Rapat ORMAWA','h','Organik',1,'g','h','Ditolak',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-15 00:59:33','2026-07-15 01:17:44'),(11,'SIL-20260715-0GWSF',3,3,3,'2026-07-25','19:01:00','22:04:00','Rapat ORMAWA','h','Organik',1,'g','b','Disetujui',2,'2026-07-15 01:02:42',NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-15 01:01:16','2026-07-15 01:02:42'),(12,'SIL-20260715-V3J5H',3,3,2,'2026-07-18','22:05:00','23:05:00','Rapat ORMAWA','Gid','Organik',1,'1','1','Disetujui',2,'2026-07-15 06:06:26',NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-15 06:05:50','2026-07-15 06:06:26'),(13,'SIL-20260715-K1ZAM',3,3,1,'2026-07-15','21:00:00','23:19:00','Rapat ORMAWA 2','Gid','Organik',1,'1','1','Disetujui',2,'2026-07-15 06:19:42',NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-15 06:19:18','2026-07-15 06:19:42'),(14,'SIL-20260717-A9PKT',3,3,1,'2026-07-24','14:50:00','17:50:00','Acara','Gid','Organik',1,'d','f','Disetujui',2,'2026-07-17 06:51:28',NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-17 06:50:28','2026-07-17 06:51:28');
/*!40000 ALTER TABLE `pemesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pemesanan_status_history`
--

DROP TABLE IF EXISTS `pemesanan_status_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pemesanan_status_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pemesanan_id` bigint(20) unsigned NOT NULL,
  `status_lama` enum('Pending','Disetujui','Ditolak','Cancel') DEFAULT NULL,
  `status_baru` enum('Pending','Disetujui','Ditolak','Cancel') NOT NULL,
  `changed_by` bigint(20) unsigned DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pemesanan_status_history_pemesanan_id_foreign` (`pemesanan_id`),
  KEY `pemesanan_status_history_changed_by_foreign` (`changed_by`),
  CONSTRAINT `pemesanan_status_history_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pemesanan_status_history_pemesanan_id_foreign` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pemesanan_status_history`
--

LOCK TABLES `pemesanan_status_history` WRITE;
/*!40000 ALTER TABLE `pemesanan_status_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `pemesanan_status_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ruangan`
--

DROP TABLE IF EXISTS `ruangan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ruangan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_ruangan` varchar(255) NOT NULL,
  `kapasitas` int(11) NOT NULL,
  `status` enum('aktif','nonaktif','perawatan') NOT NULL DEFAULT 'aktif',
  `lokasi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ruangan`
--

LOCK TABLES `ruangan` WRITE;
/*!40000 ALTER TABLE `ruangan` DISABLE KEYS */;
INSERT INTO `ruangan` VALUES (3,'Linow',50,'aktif','Jawa Timur','2026-07-11 04:55:48','2026-07-11 04:55:48'),(4,'Sitaro',50,'aktif','Jawa Timur','2026-07-15 14:31:39','2026-07-15 14:31:39');
/*!40000 ALTER TABLE `ruangan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ruangan_fasilitas`
--

DROP TABLE IF EXISTS `ruangan_fasilitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ruangan_fasilitas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ruangan_id` bigint(20) unsigned NOT NULL,
  `fasilitas_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ruangan_fasilitas_ruangan_id_fasilitas_id_unique` (`ruangan_id`,`fasilitas_id`),
  KEY `ruangan_fasilitas_fasilitas_id_foreign` (`fasilitas_id`),
  CONSTRAINT `ruangan_fasilitas_fasilitas_id_foreign` FOREIGN KEY (`fasilitas_id`) REFERENCES `fasilitas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ruangan_fasilitas_ruangan_id_foreign` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ruangan_fasilitas`
--

LOCK TABLES `ruangan_fasilitas` WRITE;
/*!40000 ALTER TABLE `ruangan_fasilitas` DISABLE KEYS */;
INSERT INTO `ruangan_fasilitas` VALUES (1,3,1,NULL,NULL),(2,4,1,NULL,NULL);
/*!40000 ALTER TABLE `ruangan_fasilitas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('2o94fTUCr7a26vsUnDeJcCyw21Zf5hn4nMPjyEH9',3,'10.136.243.156','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZE5LOUE5RDlsaHp3alZIRDJvQklzc1hFa3lVT05raFZOREdCR3ZQUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMC4xMzYuMjQzLjE4NDo4MDAwL2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=',1784271099),('uXBXoWt1SmocRS9Ug8n2lSAddoWklgCCgNZSHZYx',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNHVmR1g5ZEo4SlNMbDNyeXFCNW1HWmtIS1hZUUNhenRjWGJOMnNOciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9',1784271129);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `nama_unit` varchar(255) NOT NULL,
  `kode_unit` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Test User','bschimmel','test@example.com','$2y$12$XuqNceBCv0nu.PTsDdjZE.Fhdl2SatNrasR5LhiT8gn1mQocQLB42','user','Schuppe, McDermott and Champlin','UNITXH','fII0vEwSj4','2026-07-11 02:13:46','2026-07-11 02:13:46'),(2,'Administrator','admin','admin@silakan.local','$2y$12$QbdNGyUn1Qw6Cq3tuBlwIeX0K2HBHTH.iPus93FCUNE2Ki9yGtVa6','admin','Administrator','ADM','MYhkHBwNci4uqsH3XOFOFPznGmQ4poYIsNRBtgrysk2P1Lv0nT5Obxyv84ZG','2026-07-11 02:13:47','2026-07-11 02:13:47'),(3,'Staff IT','staff_it','staffit@test.com','$2y$12$0jIA85rHG0vzsTYiOTn3peWfbImGjscAe2/X7/YGx/PMCMbkE/1ca','user','Teknologi Informasi','TI',NULL,'2026-07-11 06:22:16','2026-07-11 06:22:16');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-17 15:24:40
